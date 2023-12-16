import { NgModule } from '@angular/core';
import { BrowserModule, provideClientHydration } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';

import { HttpHandlerInterceptor } from './interceptors/http-handler.interceptor';
import { LocalStorageUtil } from './utils/local-storage.util';
import { IpHandlerModule } from './modules/ip-handler/ip-handler.module';
import { AuditTrailsModule } from './modules/audit-trails/audit-trails.module';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
	HttpClientModule,
	// IpHandlerModule,
	// AuditTrailsModule
  ],
  providers: [
    // provideClientHydration(),
	{
		provide: HTTP_INTERCEPTORS, useClass: HttpHandlerInterceptor, multi:true,
	  }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
