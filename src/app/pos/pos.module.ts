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
import { ItemsComponent } from './items/items.component';
import { DevicesComponent } from './devices/devices.component';
import { PosFooterComponent } from './pos-footer/pos-footer.component';



@NgModule({
  declarations: [PosComponent, PosHeaderComponent, PosPackagesComponent, PosRoomsComponent, ItemsComponent, 
    DevicesComponent, PosFooterComponent
  ],
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
