import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IpAddressesComponent } from './ip-addresses.component';

describe('IpAddressesComponent', () => {
  let component: IpAddressesComponent;
  let fixture: ComponentFixture<IpAddressesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [IpAddressesComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(IpAddressesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
