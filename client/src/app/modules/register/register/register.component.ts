import { Component, OnDestroy } from '@angular/core';
import { FormBuilder, FormGroup, Validators, PatternValidator } from '@angular/forms';
import { Router } from '@angular/router';
import { SubSink } from 'subsink';
import { JwtHelperService } from '@auth0/angular-jwt';
import { AuthService } from '../../../services/auth.service';
import { IUser } from '../../../interfaces/User.interfaces';
import { ILoginResponse } from '../../../interfaces/Login.interfaces';
import Swal from 'sweetalert2';
import { finalize } from 'rxjs';

@Component({
	selector: 'app-register',
	templateUrl: './register.component.html',
	styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnDestroy {
	form: FormGroup;
	subscribed = new SubSink();
	triedSubmission = false;
	loginResponse: ILoginResponse | undefined;
	hasError = false;
	errors: any = {};

	constructor(
		private fb: FormBuilder,
		private authService: AuthService,
		private router: Router
	) {
		this.form = this.initializeForm();
	}

	private initializeForm(): FormGroup {
		const nameValidators = [
			Validators.required,
			Validators.maxLength(255),
			Validators.pattern(/^[a-zA-Z]+$/)
		];

		const emailValidators = [
			Validators.required,
			Validators.email,
			Validators.minLength(6),
			Validators.maxLength(255),
			// need a valid local part, domain part, and a top-level domain (TLD) with two or more alphabetical characters.
			Validators.pattern(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)
		];

		const passwordValidators = [
			Validators.required,
			Validators.minLength(8),
			Validators.maxLength(255),
			// at least one lowercase, one uppercase, one digit, and allowed special characters
			Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/)
		];

		return this.fb.group({
			first_name: ['', nameValidators],
			last_name: ['', nameValidators],
			email: ['', emailValidators],
			password: ['', passwordValidators]
		});
	}

	processFormSubmit(): void {
		this.hasError = false;
		this.triedSubmission = true;

		if (this.form.valid) {
			this.subscribed.sink = this.authService.register(this.form.value)
			.pipe(finalize(() => this.triedSubmission = false))
			.subscribe({
				next: (response) => this.handleSuccessResponse(response),
				error: ({ error }) => this.handleErrorResponse(error)
			});
		}
	}

	private handleSuccessResponse(response: any): void {
		const userInfo = response.user;
		const token = response.accessToken || '';
		const decodedToken = new JwtHelperService().decodeToken(token) ?? {};
		const sub = decodedToken?.sub ?? '';

		const user: IUser = {
			first_name: userInfo?.first_name || '',
			last_name: userInfo?.last_name || '',
			email: userInfo?.email || this.form.get('email')?.value || (decodedToken?.user?.email ?? ''),
			email_verified_at: userInfo?.email_verified_at,
			id: userInfo?.id || sub
		};

		this.authService.authenticate(user, token);
		this.showSuccessMessage('Registration successful');
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
