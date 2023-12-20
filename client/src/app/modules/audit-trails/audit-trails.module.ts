import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { PaginationComponent } from '../../components/pagination/pagination.component';
import { AuditTrailsComponent } from './audit-trails/audit-trails.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { MatCardModule } from '@angular/material/card';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';


@NgModule({
	declarations: [
		AuditTrailsComponent
	],
	imports: [
		CommonModule,
		RouterModule.forChild([
			{ path: '', component: AuditTrailsComponent, title: 'IP Address Manager - Audit Trail' },
		]),
		PaginationComponent,
		ReactiveFormsModule,
		FormsModule,
		MatCardModule,
		MatFormFieldModule,
    	MatInputModule,
	]
})
export class AuditTrailsModule { }
