import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PosRoutingModule } from './pos-routing.module';
import { PosComponent } from './pos.component';
import {MatGridListModule} from '@angular/material/grid-list';
import {MatInputModule} from '@angular/material/input';
import {MatCardModule} from '@angular/material/card';
import { PosHeaderComponent } from './pos-header/pos-header.component';
import { PosPackagesComponent } from './pos-packages/pos-packages.component';
import { PosRoomsComponent } from './pos-rooms/pos-rooms.component';



@NgModule({
  declarations: [PosComponent, PosHeaderComponent, PosPackagesComponent, PosRoomsComponent],
  imports: [
    CommonModule,
    PosRoutingModule,
    MatGridListModule,
    MatInputModule,
    MatCardModule
  ],
  exports: [
    MatGridListModule,
    MatInputModule,
    MatCardModule
  ],
})
export class PosModule { }
