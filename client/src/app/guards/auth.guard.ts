import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';
import Swal from 'sweetalert2';

@Injectable({
	providedIn: 'root',
})
export class AuthGuard implements CanActivate {
	constructor(private authService: AuthService, private router: Router) { }

	canActivate(): boolean {
		if (this.authService.isLoggedIn()) {
			return true;
		}

		// not logged in
		this.authService.clearStorage();

		Swal.fire({
			title            : 'Failed!',
			text             : 'Unauthorized. Please log in',
			icon             : 'error',
			confirmButtonText: 'Login'
		}).then(() => {
			this.router.navigate(['login']);
		});

		return false;
	}
}
