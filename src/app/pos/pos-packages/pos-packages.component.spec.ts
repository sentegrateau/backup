import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PosPackagesComponent } from './pos-packages.component';

describe('PosPackagesComponent', () => {
  let component: PosPackagesComponent;
  let fixture: ComponentFixture<PosPackagesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PosPackagesComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(PosPackagesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
