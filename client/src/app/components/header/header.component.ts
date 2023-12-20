import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import Swal from 'sweetalert2';
import { AuthService } from '../../services/auth.service';
import { IUser } from '../../interfaces/User.interfaces';
import { UiService } from '../../services/ui.service';

@Component({
	selector: 'app-header',
	standalone: true,
	imports: [CommonModule, RouterLink],
	templateUrl: './header.component.html',
	styleUrls: ['./header.component.scss']
})
export class HeaderComponent {
	uiTexts = {
		logout: 'Log Out',
		header: 'IP Adress Manager',
	};

	user: IUser | null;

	constructor(
		private router: Router,
		private authService: AuthService,
		private uiService: UiService
	) {
		this.user = this.authService.getUser();
	}

	logout() {
		Swal.fire({
			title: 'Log Out',
			text: 'Thanks for visiting us',
			showConfirmButton: false,
			allowOutsideClick: false
		});

		this.authService.logout().subscribe(result => {
			if (result.success) {
				this.authService.clearStorage();
				Swal.close();
				this.router.navigate(['login']);
			}
		});
	}
}
