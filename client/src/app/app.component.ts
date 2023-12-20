import { Component } from '@angular/core';
import { NavigationStart, Router } from '@angular/router';
import { initFlowbite } from 'flowbite';
import { UiService } from './services/ui.service';

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
		private router: Router, private uiService: UiService
	) {
		this.router.events.subscribe(event => {
			if (event instanceof NavigationStart) {
				this.loginUI = false;

				const validPaths = ['/login', '/register'];

				if (validPaths.includes(event.url.toLowerCase().trim())) {
					this.loginUI = true;
				}
			}
		});

		this.uiService.sidebarCollapseStatus.subscribe(result => this.sidebar = result);
	}

	ngOnInit(): void {
		// initFlowbite();
	}
}
