import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})

export class IpHandlerService {
  private baseUrl = `${environment.apiUrl}/${environment.apiPrefix}/${environment.apiVersion}/ip-handler` ;

  constructor(private http: HttpClient) {}

  // IP Addresses Endpoints

  getIpAddresses(): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/ip-addresses`);
  }

  createIpAddress(ipAddress: any): Observable<any> {
    return this.http.post<any>(`${this.baseUrl}/ip-addresses`, ipAddress);
  }

  getIpAddressById(id: string): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/ip-addresses/${id}`);
  }

  updateIpAddress(id: string, updatedIpAddress: any): Observable<any> {
    return this.http.put<any>(`${this.baseUrl}/${id}`, updatedIpAddress);
  }

  // Audit Trails Endpoints

  getAuditTrails(): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/audit-trails`);
  }

  getAuditTrailById(id: string): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/audit-trails/${id}`);
  }
}
