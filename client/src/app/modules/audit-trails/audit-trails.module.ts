import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { PaginationComponent } from '../../components/pagination/pagination.component';
import { AuditTrailsComponent } from './audit-trails/audit-trails.component';

@NgModule({
	declarations: [
		AuditTrailsComponent
	],
	imports: [
		CommonModule,
		RouterModule.forChild([
			{ path: '', component: AuditTrailsComponent, title: 'IP Address Manager - Audit Trail' },
		]),
		PaginationComponent
	]
})
export class AuditTrailsModule { }
