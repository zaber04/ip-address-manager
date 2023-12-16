import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { environment } from '../../environments/environment';
import { IIp } from '../interfaces/IpAddress.interfaces';

@Injectable({
	providedIn: 'root'
})

export class IpHandlerService {
	private baseUrl = `${environment.apiUrl}/${environment.apiPrefix}/${environment.apiVersion}/ip-handler`;


	constructor(private http: HttpClient) { }

	/**
	 * IP Addresses Endpoints
	 */
	// observable any --> IPaginatedIpResponse
	getIpAddresses(page: number = 1): Observable<any> {
		return this.http.get<any>(`${this.baseUrl}/ip-addresses?page=${page}`);
	}
	// observable any --> IPaginatedIpResponse
	createIpAddress(ipAddress: IIp): Observable<any> {
		return this.http.post<any>(`${this.baseUrl}/ip-addresses`, ipAddress);
	}

	//  observable any --> IPaginatedIpResponse
	getIpAddressById(id: string): Observable<any> {
		return this.http.get<any>(`${this.baseUrl}/ip-addresses/${id}`);
	}

	//  observable any --> IPaginatedIpResponse
	updateIpAddress(id: string, updatedIpAddress: IIp): Observable<any> {
		return this.http.patch<any>(`${this.baseUrl}/${id}`, updatedIpAddress);
	}


	/**
	 * Audit Trails Endpoints
	 */
	// Get All audit trail entries
	// Observable any --> IPaginatedAuditResponse
	getAuditTrails(page: number = 1): Observable<any> {
		return this.http.get<any>(`${this.baseUrl}/audit-trails?page=${page}`);
	}

	// this end point returns the changes current user did in this session
	// Observable any --> IPaginatedAuditResponse
	getAuditTrailByUserId(user_id: string, page: number = 1): Observable<any> {
		return this.http.get<any>(`${this.baseUrl}/audit-trails/user/${user_id}?page=${page}`);
	}

	// get a specific audit trail entry
	// Observable any --> IPaginatedAuditResponse
	getAuditTrailByAuditId(audit_id: string): Observable<any> {
		return this.http.get<any>(`${this.baseUrl}/audit-trails/trail/${audit_id}`);
	}




}
