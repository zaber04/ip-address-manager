import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';


@Injectable({
	providedIn: 'root'
})
export class UiService {

	private sidebarSubject = new BehaviorSubject<boolean>(false);
	sidebarCollapseStatus = this.sidebarSubject.asObservable();
	private sidebarCollapsed = false;

	constructor() { }

	toggleSidebar() {
		this.sidebarCollapsed = !this.sidebarCollapsed;
		this.sidebarSubject.next(this.sidebarCollapsed);
	}
}
