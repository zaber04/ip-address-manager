import { Injectable } from '@angular/core';
import { JwtHelperService } from '@auth0/angular-jwt';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ILoginRequest, ILoginResponse } from "../interfaces/Login.interfaces";
import { IUser, IUserBasic } from '../interfaces/User.interfaces';
import { environment } from '../../environments/environment';
import { LocalStorageUtil } from '../utils/local-storage.util';
import { Router } from '@angular/router';

@Injectable({
	providedIn: 'root'
})
export class AuthService {
	private baseUrl = `${environment.apiUrl}/${environment.apiPrefix}/${environment.apiVersion}/auth`;

	constructor(private http: HttpClient, private jwtHelper: JwtHelperService) { }

	register(user: IUserBasic): Observable<any> {
		return this.http.post<any>(`${this.baseUrl}/register`, user);
	}

	login(credentials: ILoginRequest): Observable<ILoginResponse> {
		// @TODO: MUST check if credentials.email and credentials.password is not empty and sanitized
		return this.http.post<ILoginResponse>(`${this.baseUrl}/login`, credentials);
	}

	logout(): Observable<any> {
		return this.http.post<any>(`${this.baseUrl}/logout`, {});
	}

	refresh(): Observable<any> {
		return this.http.post<any>(`${this.baseUrl}/refresh`, {});
	}

	getUserProfile(): Observable<any> {
		return this.http.post<any>(`${this.baseUrl}/user-profile`, {});
	}




	// in real app we will use cookie instead of localstorage for security
	authenticate(user: IUser, token: string, ttl: number = 1800): void {
		if (LocalStorageUtil.Supported()) {
			LocalStorageUtil.set('token', token, ttl);
			LocalStorageUtil.set('user', JSON.stringify(user), ttl);
		}
	}

	getUser(): IUser | null {
		if (LocalStorageUtil.Supported() && LocalStorageUtil.get('token') && LocalStorageUtil.get('user')) {
			const userStorage = LocalStorageUtil.get('user') as string;
			const user = JSON.parse(userStorage) as IUser;
			return user;
		}

		return null;
	}

	getUserId(): string {
		const user: IUser | null = this.getUser();

		if (!!user) {
			return user.id;
		}

		return '';
	}


	isAuthenticated(): boolean {
		if (LocalStorageUtil.Supported()) {
			const token = LocalStorageUtil.get('token');
			return !this.jwtHelper.isTokenExpired(token);
		}

		return false;
	}

	getToken(): string | null {
		return LocalStorageUtil.get('token'); // auto removes after expiry
	}

	isLoggedIn(): boolean {
		return this.isAuthenticated();
	}

	clearStorage(): void {
		if (LocalStorageUtil.Supported()) {
			LocalStorageUtil.clear('remember');
			LocalStorageUtil.clear('token');
			LocalStorageUtil.clear('user');
		}
	}

	redirectToHome(router: Router): void {
		router.navigate(['/']);
	}


	// Ensure JwtHelperService is provided
	static jwtHelperServiceFactory(): JwtHelperService {
		return new JwtHelperService();
	}

	// Provide JwtHelperService in your AppModule
	static jwtHelperServiceProvider = {
		provide: JwtHelperService,
		useFactory: AuthService.jwtHelperServiceFactory
	};


	// Provide the AuthService and JwtHelperService in your module
	static authServiceAndJwtHelperServiceProviders: any[] = [AuthService, AuthService.jwtHelperServiceProvider];
}
