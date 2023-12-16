import { Injectable } from '@angular/core';
import { JwtHelperService } from '@auth0/angular-jwt';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { User } from '../models/user.model';
import { environment } from '../../environments/environment';
import { LocalStorageUtil } from '../utils/local-storage.util';

@Injectable({
    providedIn: 'root'
})
export class AuthService {
    private baseUrl = `${environment.apiUrl}/${environment.apiPrefix}/${environment.apiVersion}/auth/`;

    constructor(private http: HttpClient, private jwtHelper: JwtHelperService) { }

    register(user: any): Observable<any> {
        return this.http.post<any>(`${this.baseUrl}/register`, user);
    }

    login(credentials: { email: string; password: string }): Observable<any> {
        return this.http.post<any>(`${this.baseUrl}/login`, credentials);
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
    authenticate(user: User, token: string, ttl: number = 1800): void {
        // if (LocalStorageUtil.Supported()) {
        LocalStorageUtil.set('token', token, ttl);
        LocalStorageUtil.set('user', JSON.stringify(user), ttl);
        // }
    }

    getUser(): User | null {
        // if (LocalStorageUtil.Supported()) {
        if (LocalStorageUtil.get('token') && LocalStorageUtil.get('user')) {
            const userStorage = LocalStorageUtil.get('user') as string;
            const user        = JSON.parse(userStorage) as User;
            return user;
        }
        // }

        return null;
    }

    isAuthenticated(): boolean {
        // if (LocalStorageUtil.Supported()) {
        const token = LocalStorageUtil.get('token');
        return !!token && !this.jwtHelper.isTokenExpired(token);
        // }
    }

    getToken(): string | null {
        return LocalStorageUtil.get('token');
    }

    isLoggedIn(): boolean {
        return this.isAuthenticated();
    }

    clearStorage(): void {
        // if (LocalStorageUtil.Supported()) {
        LocalStorageUtil.clear('remember');
        LocalStorageUtil.clear('token');
        LocalStorageUtil.clear('user');
        // }
    }
}
