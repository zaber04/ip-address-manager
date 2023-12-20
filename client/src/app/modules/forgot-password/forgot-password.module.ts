import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';

@NgModule({
	declarations: [
		ForgotPasswordComponent
	],
	imports: [
		CommonModule,
		RouterModule.forChild([
			{ path: '', component: ForgotPasswordComponent, title: 'IP Address Manager - Reset',
			},
		]),
	]
})
export class ForgotPasswordModule { }
