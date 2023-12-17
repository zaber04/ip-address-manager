import { Component, OnDestroy, OnInit } from '@angular/core';
import { IPaginatedIpResponse, ISingleIp } from '../../../interfaces/IpAddress.interfaces';
import { Observable, Subscription, finalize } from 'rxjs';
import { SubSink } from 'subsink';
import { ActivatedRoute, Router } from '@angular/router';
import { IpHandlerService } from '../../../services/ip-handler.service';
import { PaginationService } from '../../../services/pagination.service';

@Component({
	selector: 'app-ip-list',
	templateUrl: './ip-list.component.html',
	styleUrl: './ip-list.component.scss'
})

export class IpListComponent implements OnInit, OnDestroy {
	isLoading = false;
	errors: any = null;
	ipAddresses$: Observable<IPaginatedIpResponse> | undefined;
	ipAddressList: IPaginatedIpResponse | undefined;
	subscriptions = new SubSink();
	ipAddressSubscription = new Subscription;
	currentPageIndex: number = 1;


	titles = {
		pageTitles:  'IP Address List',
		cardTitles:  'IP Address List'
	}

	buttons = {
		addNewIp: 'Add New IP',
		edit: 'Update',
	};

	messages = {
		noIpFound: 'No IP address available.',
		pleaseWait: 'Please Wait For A While...',
	};

	constructor(
		private router: Router,
		private ipHandlerService: IpHandlerService,
		private paginationService: PaginationService,
		private route: ActivatedRoute
	) {

	}

	ngOnInit(): void {
		this.subscriptions.sink = this.route.queryParams.subscribe(queryParams => {
			if (queryParams['page']) {
				this.isLoading = true;

				if (this.paginationService.ipAddress?.list && this.currentPageIndex === queryParams['page']) {
					this.ipAddresses$ = this.paginationService.getIpAddresses();
				} else {
					this.currentPageIndex = queryParams['page'];
					this.paginationService.setIpAddresses(null as any);
					this.paginationService.setSelectedPage(null as any);
					this.ipAddresses$ = this.ipHandlerService.getIpAddresses(this.currentPageIndex);
				}

				if (this.ipAddressSubscription) {
					this.ipAddressSubscription.unsubscribe();
				}

				this.ipAddressSubscription = this.ipAddresses$.pipe(finalize(() => this.isLoading = false)).subscribe({
					next: response => {
						this.ipAddressList = response;
						this.paginationService.setIpAddresses(this.ipAddressList);
						this.paginationService.setSelectedPage(this.currentPageIndex);
					},
					error: ({ error }) => {
						this.errors = error?.errors;
					}
				});
			} else {
				this.isLoading = true;
				this.currentPageIndex = 1;
				if (this.paginationService.ipAddress?.list && this.currentPageIndex === this.paginationService.ipAddress?.pageSelected) {
					this.ipAddresses$ = this.paginationService.getIpAddresses();
				} else {
					this.ipAddresses$ = this.ipHandlerService.getIpAddresses(this.currentPageIndex);
				}

				if (this.ipAddressSubscription) {
					this.ipAddressSubscription.unsubscribe();
				}

				this.ipAddressSubscription = this.ipAddresses$.pipe(finalize(() => this.isLoading = false)).subscribe({
					next: response => {
						this.ipAddressList = response;
						this.paginationService.setIpAddresses(this.ipAddressList);
						this.paginationService.setSelectedPage(this.currentPageIndex);
					},
					error: ({ error }) => {
						this.errors = error?.errors;
					}
				});
			}
		});

	}

	createIpFormView() {
		this.router.navigate(['ip-handler/create']);
	}

	setSelectedIp(ipAddress: ISingleIp) {
		this.paginationService.setSelectedIp(ipAddress);
	}

	ngOnDestroy(): void {
		this.subscriptions.unsubscribe();
		this.ipAddressSubscription.unsubscribe();
	}
}
