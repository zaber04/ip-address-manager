import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';
import { AuditLogStore, IpAddressStore, IPaginatedAuditResponse, IPaginatedIpResponse, ISingleIp } from '../interfaces/IpAddress.interfaces';

@Injectable({
	providedIn: 'root'
})
export class PaginationService {

	auditLog: AuditLogStore = {
		list: null as any,
		pageSelected: 1,
	};

	ipAddress: IpAddressStore = {
		list: null as any,
		selectedIp: null as any,
		pageSelected: 1,
	};

	constructor() { }


	/**
	   * Ip Adress
	   */
	setIpAddresses(ipAddresses: IPaginatedIpResponse) {
		this.ipAddress.list = ipAddresses;
	}

	getIpAddresses(): Observable<IPaginatedIpResponse> {
		return of(this.ipAddress.list);
	}

	setSelectedIp(selectedIpAddress: ISingleIp) {
		this.ipAddress.selectedIp = selectedIpAddress;
	}

	getSelectedIp(): Observable<ISingleIp> | null {
		return of(this.ipAddress.selectedIp);
	}

	setSelectedPage(index: number) {
		this.ipAddress.pageSelected = index;
	}

	getSelectedPage(): number {
		return this.ipAddress.pageSelected;
	}

	/**
	 * Audit Logs
	 */
	setAuditLogs(auditLogs: IPaginatedAuditResponse) {
		this.auditLog.list = auditLogs;
	}

	getAuditLogs(): Observable<IPaginatedAuditResponse> {
		return of(this.auditLog.list);
	}

	setAuditSelectedPageIndex(index: number) {
		this.auditLog.pageSelected = index;
	}

	getAuditLogSelectedPageIndex(): number | null {
		return this.auditLog.pageSelected;
	}
}
