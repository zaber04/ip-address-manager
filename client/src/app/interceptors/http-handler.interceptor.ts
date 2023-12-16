import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor, HttpResponse, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, tap } from 'rxjs/operators';
import { Router } from '@angular/router';
import { noAuth } from '../config/constants';
import { AuthService } from '../services/auth.service';
import Swal from 'sweetalert2';

@Injectable()
export class HttpHandlerInterceptor implements HttpInterceptor {

	constructor(
		private router: Router,
		private authService: AuthService,
	) { }

	intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {

		const token = this.authService.getToken();
		let headers: { [header: string]: string | string[] } = {
			'Content-Type': 'application/json; charset=utf-8',
			'Accept': 'application/json',
		};

		if (!noAuth.includes(request.url)) {
			headers['Authorization'] = `Bearer ${token}`;
		}

		const modifiedRequest = request.clone({ setHeaders: headers });

		return next.handle(modifiedRequest).pipe(
			tap(event => {
				if (event instanceof HttpResponse && event.status === 401) {
					this.handleUnauthorizedResponse();
				}
			}),
			catchError((error: HttpErrorResponse) => {
				if (error.status === 401) {
					this.handleUnauthorizedResponse();
				} else if (error.status === 404) {
					this.handleNotFoundResponse();
				}
				return throwError(error);
			})
		);
	}

	private handleUnauthorizedResponse(): void {
		this.authService.logout().subscribe(result => {
			if (result.success) {
				this.authService.clearStorage();
			}
		});

		Swal.fire({
			title            : 'Login Required',
			text             : 'Authorization Error. Please login.',
			icon             : 'error',
			confirmButtonText: 'Login',
			allowOutsideClick:  false,
		}).then(() => {
			this.router.navigate(['login']);
		});
	}

	private handleNotFoundResponse(): void {
		console.log('Page Not Found!');
	}
}
