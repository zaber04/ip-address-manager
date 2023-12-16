import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

export const routes: Routes = [
  {
    path: 'api/v1/ip-handler',
    loadChildren: () => import('./ip-handler/ip-handler.module').then(m => m.IpHandlerModule),
  },
  {
    path: 'api/v1/audit-trails',
    loadChildren: () => import('./audit-trails/audit-trails.module').then(m => m.AuditTrailsModule),
  },
];
