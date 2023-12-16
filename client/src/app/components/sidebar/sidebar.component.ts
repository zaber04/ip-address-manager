import { Component, Input, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NavigationEnd, Router, RouterLink } from '@angular/router';
import { SubSink } from 'subsink';

@Component({
	selector: 'app-sidebar',
	standalone: true,
	imports: [CommonModule, RouterLink],
	templateUrl: './sidebar.component.html',
	styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnDestroy {
	sidebar = {
		title: 'IP Address Management',
		menuItems: [
			{ path: '/', icon: 'fa fa-tachometer', label: 'Audit Trail' },
			{ path: '/ip-addresses', icon: 'fa fa-object-group', label: 'IP Address' }
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
