import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IpEditComponent } from './ip-edit.component';

describe('IpEditComponent', () => {
  let component: IpEditComponent;
  let fixture: ComponentFixture<IpEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [IpEditComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(IpEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
