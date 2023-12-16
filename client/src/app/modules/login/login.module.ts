import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LoginComponent } from './login/login.component';
import { ReactiveFormsModule } from '@angular/forms';
import { FormErrorComponent } from '../../components/form-error/form-error.component';
import { RouterModule } from '@angular/router';



@NgModule({
	declarations: [
		LoginComponent
	],
	imports: [
		CommonModule,
		RouterModule.forChild([
			{ path: '', component: LoginComponent, title: "Ip Address Manager - Login" }
		]),
		ReactiveFormsModule,
		FormErrorComponent
	]
})

export class LoginModule { }
