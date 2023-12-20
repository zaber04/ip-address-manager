import { Component, Input, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NavigationEnd, Router, RouterLink } from '@angular/router';
import { SubSink } from 'subsink';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatListModule } from '@angular/material/list';

@Component({
	selector: 'app-sidebar',
	standalone: true,
	imports: [CommonModule, RouterLink, MatSidenavModule, MatListModule],
	templateUrl: './sidebar.component.html',
	styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnDestroy {
	@Input() collapse = false;

	sidebar = {
		title: 'IP Address Manager',
		menuItems: [
			{ path: '/', label: 'Audit Trail' },
			{ path: '/ip-handler', label: 'IP Address' }
		]
	};

	thisPath = '/';
	subscribed = new SubSink();

	constructor(private router: Router) {
		router.events.subscribe((event) => {
			if (event instanceof NavigationEnd) {
				this.thisPath = event.url.split('?')[0];
			}
		});
	}

	ngOnDestroy(): void {
		this.subscribed.unsubscribe();
	}

}
