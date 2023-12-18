import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './guards/auth.guard';


const routes: Routes = [
	{
		path: '',
		loadChildren: () => import('./modules/audit-trails/audit-trails.module').then(m => m.AuditTrailsModule),
		canActivate: [AuthGuard],
	},
	{
		path: 'ip-handler',
		loadChildren: () => import('./modules/ip-handler/ip-handler.module').then(m => m.IpHandlerModule),
		canActivate: [AuthGuard],
	},
	{
		path: 'login',
		loadChildren: () => import('./modules/login/login.module').then(m => m.LoginModule)
	},
	{
		path: 'register',
		loadChildren: () => import('./modules/register/register.module').then(m => m.RegisterModule)
	},
	{
		path: 'forgot-password',
		loadChildren: () => import('./modules/forgot-password/forgot-password.module').then(m => m.ForgotPasswordModule)
	},
	{
		path: '**',
		redirectTo: '',
		pathMatch: 'full',
	}
];


@NgModule({
	imports: [RouterModule.forRoot(routes)],
	exports: [RouterModule]
})
export class AppRoutingModule { }
