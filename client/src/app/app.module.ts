import { NgModule } from '@angular/core';
import { BrowserModule, provideClientHydration } from '@angular/platform-browser';
import { provideHttpClient, withFetch } from '@angular/common/http';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';
import { HttpHandlerInterceptor } from './interceptors/http-handler.interceptor';
import { HeaderComponent } from './components/header/header.component';
import { SidebarComponent } from './components/sidebar/sidebar.component';
import { JwtHelperService, JWT_OPTIONS } from '@auth0/angular-jwt';
import { JwtModule } from "@auth0/angular-jwt";
import { LoginComponent } from './modules/login/login/login.component';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MaterialModule } from './modules/material/material.module';

@NgModule({
	declarations: [
		AppComponent
	],
	imports: [
		BrowserModule,
		RouterModule,
		AppRoutingModule,
		MaterialModule,
		BrowserAnimationsModule,
		HttpClientModule,
		HeaderComponent,
    	SidebarComponent
	],
	providers: [
		provideHttpClient(withFetch()),
		provideClientHydration(),
		{
			provide: HTTP_INTERCEPTORS, useClass: HttpHandlerInterceptor, multi: true,
		},
		{ provide: JWT_OPTIONS, useValue: JWT_OPTIONS },
        JwtHelperService
	],
	bootstrap: [AppComponent]
})
export class AppModule { }
