import { Component, OnDestroy } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { SubSink } from 'subsink';
import { IError, ILoginResponse } from '../../../interfaces/Login.interfaces';
import { finalize } from 'rxjs';
import { AuthService } from '../../../services/auth.service';
import { IUser } from '../../../interfaces/User.interfaces';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';
import { JwtHelperService } from '@auth0/angular-jwt';

@Component({
	selector: 'app-login',
	templateUrl: './login.component.html',
	styleUrl: './login.component.scss'
})
export class LoginComponent implements OnDestroy {
	form: FormGroup;
	subscribed = new SubSink();
	triedSubmission = false;
	loginResponse: ILoginResponse = {};
	hasError: boolean = false;
	errors: IError = { message: "We have error" };

	pageContent = {
		pageTitle: 'IP Address Management',
		signInTitle: 'Sign in to your account',
		emailLabel: 'Your email',
		passwordLabel: 'Your password',
		emailInvalidMessage: 'Email is invalid.',
		passwordRequiredMessage: 'Password is required.',
		rememberMeLabel: 'Remember me',
		forgotPasswordLink: 'Forgot password?',
		signInButtonText: 'Sign in',
		signUpMessage: "Don't have an account yet?",
		signUpLink: 'Sign up'
	};


	constructor(
		private fb: FormBuilder,
		private authService: AuthService,
		private router: Router,
	) {
		this.form = this.fb.group({
			email: ['', [Validators.required, Validators.email, Validators.maxLength(255)]],
			password: ['', [Validators.required, Validators.minLength(8), Validators.maxLength(255)]],
			remember: [false],
		});

		if (this.authService.getToken() !== null) {
			// If there is a valid token redirect the user
			this.authService.redirectToHome(router);
		}
	}

	processFormSubmit() {
		this.hasError = false;
		this.triedSubmission = true;

		if (this.form.valid) {
			this.subscribed.sink = this.authService.login(this.form.value)
				.pipe(finalize(() => this.triedSubmission = false))
				.subscribe({
					next: response => {
						const userInfo = response.user;
						const token = response.accessToken || "";
						const decodedToken = new JwtHelperService().decodeToken(token) ?? {};
						const sub = decodedToken?.sub ?? '';

						const user: IUser = {
							first_name: userInfo?.first_name,
							last_name: userInfo?.last_name,
							email: userInfo?.email || (this.form.get('email'))?.value || (decodedToken?.email ?? ''),
							email_verified_at: userInfo?.email_verified_at,
							id: userInfo?.id || sub
						}
						this.authService.authenticate(user, token);

						Swal.fire({
							title: 'Login successful',
							text: 'Login successful.',
							icon: 'success',
							showConfirmButton: false,
							timer: 2000
						});

						if (this.form.get('remember')?.value || false) {
							localStorage.setItem('remember', 'true');
						}

						this.form.reset();

						setTimeout(() => { this.authService.redirectToHome(this.router); }, 1000);
					},
					error: ({ error }) => {
						this.hasError = true;
						this.errors = error?.errors;
					}
				});
		}
	}

	ngOnDestroy(): void {
		this.subscribed.unsubscribe();
	}
}
