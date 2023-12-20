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
	loginResponse: ILoginResponse | undefined;
	hasError: boolean = false;
	errors: IError = { message: "We have error" };

	pageContent = {
		pageTitle: 'IP Address Manager',
		signInTitle: 'Sign in',
		emailLabel: 'Your email',
		passwordLabel: 'Your password',
		emailInvalidMessage: 'Invalid Email',
		passwordRequiredMessage: 'Password is required.',
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
		this.form = this.initializeForm();

		if (this.authService.getToken() !== null) {
			this.authService.redirectToHome(router);
		}
	}

	private initializeForm(): FormGroup {
		const emailValidators = [
			Validators.required,
			Validators.email,
			Validators.minLength(6),
			Validators.maxLength(255),
			Validators.pattern(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/)
		];

		const passwordValidators = [
			Validators.required,
			Validators.minLength(8),
			Validators.maxLength(255),
			// Password with at least one lowercase, one uppercase, one digit
			// Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/)
		];

		return this.fb.group({
			email: ['', emailValidators],
			password: ['', passwordValidators]
		});
	}

	processFormSubmit() {
		this.hasError = false;
		this.triedSubmission = true;

		if (this.form.valid) {
			this.subscribed.sink = this.authService.login(this.form.value)
			.pipe(finalize(() => this.triedSubmission = false))
			.subscribe({
				next: (response) => this.handleSuccessResponse(response),
				error: ({ error }) => this.handleErrorResponse(error)
			});
		}
	}

	private handleSuccessResponse(response: any): void {
		const userInfo = response.user;
		const token = response.accessToken || "";
		const decodedToken = new JwtHelperService().decodeToken(token) ?? {};
		const sub = decodedToken?.sub ?? '';

		const user: IUser = {
			first_name: userInfo?.first_name || "",
			last_name: userInfo?.last_name || "",
			email: userInfo?.email || this.form.get('email')?.value || (decodedToken?.user?.email ?? ''),
			email_verified_at: userInfo?.email_verified_at,
			id: userInfo?.id || sub
		};

		this.authService.authenticate(user, token);

		this.showSuccessMessage('Login successful');
		this.form.reset();

		setTimeout(() => this.authService.redirectToHome(this.router), 1000);
	}

	private handleErrorResponse(error: any): void {
		this.hasError = true;
		this.errors = error?.errors;
	}

	private showSuccessMessage(message: string): void {
		Swal.fire({
			title: message,
			text: message,
			icon: 'success',
			showConfirmButton: false,
			timer: 2000
		});
	}

	ngOnDestroy(): void {
		this.subscribed.unsubscribe();
	}
}
