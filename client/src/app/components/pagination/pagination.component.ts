import { Component, Input } from '@angular/core';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ILink } from '../../interfaces/IpAddress.interfaces';

@Component({
	selector: 'app-pagination',
	templateUrl: './pagination.component.html',
	styleUrls: ['./pagination.component.scss'],
	imports: [CommonModule],
	standalone: true
})
export class PaginationComponent {
	@Input() baseUrl         : string              = '';
	@Input() currentPageIndex: number | undefined  = 0;
	@Input() lastPageIndex   : number | undefined  = 0;
	@Input() pageLinks       : ILink[] | undefined = [];

	constructor(private router: Router) { }

	getPageIndex(url: string): number {
		return +(url.match(/page=(\d+)/)?.[1] ?? 1);

	}

	navigatePage(link: ILink) {
		const pageIndex = this.getPageIndex(link.url);
		this.router.navigate([this.baseUrl], { queryParams: { page: pageIndex } });
	}
}
