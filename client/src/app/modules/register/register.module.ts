import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { RegisterComponent } from './register/register.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatCardModule } from '@angular/material/card';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';


@NgModule({
	declarations: [
		RegisterComponent
	],
	imports: [
		CommonModule,
		RouterModule.forChild([
			{ path: '', component: RegisterComponent, title: 'IP Address Manager - Registration' },
		]),
		ReactiveFormsModule,
		FormsModule,
		MatCardModule,
		MatFormFieldModule,
    	MatInputModule,
	]
})
export class RegisterModule { }
