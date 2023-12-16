import { Component } from '@angular/core';
import { NavigationStart, Router } from '@angular/router';

@Component({
	selector: 'app-root',
	templateUrl: './app.component.html',
	styleUrl: './app.component.scss'
})

export class AppComponent {
	title = 'client';

	loginUI = false;
	sidebar = false;

	constructor(
		private router: Router,
		// private sharedService: SharedService,
	) {
		this.router.events.subscribe(event => {
			if (event instanceof NavigationStart) {
				this.loginUI = false;

				if (event.url === '/login') {
					this.loginUI = true;
				}
			}
		});
	}
}
