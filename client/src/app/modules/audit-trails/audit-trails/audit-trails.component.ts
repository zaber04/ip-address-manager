import { Component, OnDestroy, OnInit } from '@angular/core';
import { Observable, Subscription, finalize } from 'rxjs';
import { SubSink } from 'subsink';
import { ActivatedRoute } from '@angular/router';
import { IpHandlerService } from '../../../services/ip-handler.service';
import { IIPResponse, IPaginatedAuditResponse, ActionEnum } from '../../../interfaces/IpAddress.interfaces';

@Component({
	selector: 'app-audit-trails',
	templateUrl: './audit-trails.component.html',
	styleUrl: './audit-trails.component.scss'
})
export class AuditTrailsComponent implements OnInit, OnDestroy {
	logs$: Observable<IIPResponse> | undefined;
	subscribe = new SubSink();
	auditTrailSubscribe = new Subscription();
	currentPageIndex = 1;
	isLoading = false;
	auditLogList: IPaginatedAuditResponse | undefined;
	errors: any;

	public textLabels = {
		pageTitle  : 'Audit Trail',
		createdBy  : 'Performed by',
		noAuditLogs: 'No audit trail available.',
		pleaseWait : 'Please wait for a while...'
	};

	constructor(private ipHandlerService: IpHandlerService, private route: ActivatedRoute) { }


	ngOnInit(): void {
		throw new Error('Method not implemented.');
	}
	ngOnDestroy(): void {
		throw new Error('Method not implemented.');
	}

}
