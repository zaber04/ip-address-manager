import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { IpCreateComponent } from './ip-create/ip-create.component';
import { IpEditComponent } from './ip-edit/ip-edit.component';
import { IpListComponent } from './ip-list/ip-list.component';
import { ReactiveFormsModule } from '@angular/forms';
import { FormErrorComponent } from '../../components/form-error/form-error.component';
import { PaginationComponent } from '../../components/pagination/pagination.component';


@NgModule({
	declarations: [
		IpListComponent,
		IpCreateComponent,
		IpEditComponent
	],
	imports: [
		CommonModule,
		RouterModule.forChild([
			{ path: '', component: IpListComponent, title: 'IP Address Manager - List' },
			{ path: 'create', component: IpCreateComponent, title: 'IP Address Manager - Create' },
			{ path: 'edit/:id', component: IpEditComponent, title: 'IP Address Manager - Update' }
		]),
		ReactiveFormsModule,
		FormErrorComponent,
		PaginationComponent,
	],
})
export class IpHandlerModule { }
