import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { IpAddressesComponent } from './ip-addresses/ip-addresses.component';

const routes: Routes = [
	{ path: 'ip-addresses', component: IpAddressesComponent },
];

@NgModule({
	declarations: [
		IpAddressesComponent
	],
	imports: [
		CommonModule,
		RouterModule.forChild(routes),
		//
	],
})
export class IpHandlerModule { }

