import { TestBed } from '@angular/core/testing';

import { HttpHandlerInterceptor } from './http-handler.interceptor';
import { HttpClientTestingModule } from '@angular/common/http/testing';

describe('HttpHandlerInterceptor', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [
      HttpClientTestingModule,
    ],
    providers: [
      HttpHandlerInterceptor
      ]
  }));

  it('should be created', () => {
    const interceptor: HttpHandlerInterceptor = TestBed.inject(HttpHandlerInterceptor);
    expect(interceptor).toBeTruthy();
  });
});
