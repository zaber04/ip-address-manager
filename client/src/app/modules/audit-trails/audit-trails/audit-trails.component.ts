import { Component, OnDestroy, OnInit } from '@angular/core';
import { Observable, Subscription, finalize } from 'rxjs';
import { SubSink } from 'subsink';
import { ActivatedRoute } from '@angular/router';
import { IpHandlerService } from '../../../services/ip-handler.service';
import { IPaginatedAuditResponse } from '../../../interfaces/IpAddress.interfaces';
import { PaginationService } from '../../../services/pagination.service';
import { AuthService } from '../../../services/auth.service';

@Component({
	selector: 'app-audit-trails',
	templateUrl: './audit-trails.component.html',
	styleUrl: './audit-trails.component.scss'
})
export class AuditTrailsComponent implements OnInit, OnDestroy {
	logs$: Observable<IPaginatedAuditResponse> | undefined;
	subscribe = new SubSink();
	auditTrailSubscribe = new Subscription();
	currentPageIndex = 1;
	isLoading = false;
	auditLogList: IPaginatedAuditResponse | undefined;
	errors: any;

	public textLabels = {
		pageTitle: 'Audit Trail',
		createdBy: 'Performed by',
		noAuditLogs: 'No audit trail available.',
		pleaseWait: 'Please wait for a while...'
	};

	constructor(
		private ipHandlerService: IpHandlerService,
		private paginationService: PaginationService,
		private route: ActivatedRoute,
		private authService: AuthService
	) { }

	ngOnInit(): void {
		this.subscribe.sink = this.route.queryParams.subscribe(queryParams => {
			if (queryParams['page']) {
				this.isLoading = true;

				if (this.paginationService.ipAddress?.list && this.currentPageIndex === queryParams['page']) {
					this.logs$ = this.paginationService.getAuditLogs();
				} else {
					this.currentPageIndex = queryParams['page'];
					this.paginationService.setAuditLogs(null as any);
					this.paginationService.setAuditSelectedPageIndex(null as any);
					// this.logs$ = this.ipHandlerService.getLogs(this.currentPageIndex);
					this.logs$ = this.ipHandlerService.getAuditTrailByUserId(this.authService.getUserId(), this.currentPageIndex);
				}

				if (this.auditTrailSubscribe) {
					this.auditTrailSubscribe.unsubscribe();
				}

				this.auditTrailSubscribe = this.logs$.pipe(finalize(() => this.isLoading = false)).subscribe({
					next: response => {
						this.auditLogList = response;
						this.paginationService.setAuditLogs(this.auditLogList);
						this.paginationService.setAuditSelectedPageIndex(this.currentPageIndex);
					},
					error: ({ error }) => {
						this.errors = error?.errors;
					}
				});
			} else {
				this.isLoading = true;
				this.currentPageIndex = 1;
				if (this.paginationService.auditLog?.list && this.currentPageIndex === this.paginationService.auditLog.pageSelected) {
					this.logs$ = this.paginationService.getAuditLogs();
				} else {
					// this.logs$ = this.ipHandlerService.getLogs(this.currentPageIndex);
					this.logs$ = this.ipHandlerService.getAuditTrailByUserId(this.authService.getUserId(), this.currentPageIndex);
				}

				if (this.auditTrailSubscribe) {
					this.auditTrailSubscribe.unsubscribe();
				}

				this.auditTrailSubscribe = this.logs$?.pipe(finalize(() => this.isLoading = false)).subscribe({
					next: response => {
						this.auditLogList = response;
						this.paginationService.setAuditLogs(this.auditLogList);
						this.paginationService.setAuditSelectedPageIndex(this.currentPageIndex);
					},
					error: ({ error }) => {
						this.errors = error?.errors;
					}
				});
			}
		});
	}

	ngOnDestroy(): void {
		this.subscribe.unsubscribe();
		this.auditTrailSubscribe.unsubscribe();
	}
}
