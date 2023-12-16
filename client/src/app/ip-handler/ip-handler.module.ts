import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { IpAddressesComponent } from './ip-addresses/ip-addresses.component';


@NgModule({
  imports: [
    RouterModule.forChild([
      { path: 'ip-addresses', component: IpAddressesComponent },
      // other routes for this module
    ]),
    CommonModule
  ],
})
export class IpHandlerModule {}
