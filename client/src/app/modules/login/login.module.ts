import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LoginComponent } from './login/login.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { MatCardModule } from '@angular/material/card';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';

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
		FormsModule,
		MatCardModule,
		MatFormFieldModule,
    	MatInputModule,
	]
})

export class LoginModule { }
