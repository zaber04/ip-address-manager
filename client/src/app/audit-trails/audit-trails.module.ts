import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { AuditTrailsComponent } from './audit-trails/audit-trails.component';


@NgModule({
  imports: [
    RouterModule.forChild([
      { path: '', component: AuditTrailsComponent },
      // other routes for this module
    ]),
    CommonModule
  ],
})
export class AuditTrailsModule {}
