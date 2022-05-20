import {NgModule} from '@angular/core';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import {CommonModule} from '@angular/common';
import {HttpClientModule} from '@angular/common/http';
import {PosRoutingModule} from './pos-routing.module';
import {PosComponent} from './pos.component';
import {MatGridListModule} from '@angular/material/grid-list';
import {MatInputModule} from '@angular/material/input';
import {MatCardModule} from '@angular/material/card';
import {PosHeaderComponent} from './pos-header/pos-header.component';
import {PosPackagesComponent} from './pos-packages/pos-packages.component';
import {PosRoomsComponent} from './pos-rooms/pos-rooms.component';
import {ItemsComponent} from './items/items.component';
import {DevicesComponent} from './devices/devices.component';
import {PosFooterComponent} from './pos-footer/pos-footer.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {SweetAlert2Module} from '@sweetalert2/ngx-sweetalert2';
import {CheckoutComponent} from './checkout/checkout.component';
import {NgxStripeModule} from 'ngx-stripe';
import {PerfectScrollbarModule} from 'ngx-perfect-scrollbar';
import {NgSelectModule} from '@ng-select/ng-select';
import {PaymentSuccessComponent} from './payment-success/payment-success.component';
import {PaymentErrorComponent} from './payment-error/payment-error.component';
import {CartComponent} from './cart/cart.component';
import {PaymentComponent} from './payment/payment.component';
import {NotFoundComponent} from './not-found/not-found.component';


@NgModule({
  declarations: [PosComponent, PosHeaderComponent, PosPackagesComponent, PosRoomsComponent, ItemsComponent,
    DevicesComponent, PosFooterComponent, CheckoutComponent, PaymentSuccessComponent, PaymentErrorComponent,
    CartComponent,
    PaymentComponent,
    NotFoundComponent
  ],
  imports: [
  
    NgbModule,
    CommonModule,
    PosRoutingModule,
    MatGridListModule,
    MatInputModule,
    MatCardModule,
    HttpClientModule,
    ReactiveFormsModule,
    SweetAlert2Module.forRoot(),
    NgxStripeModule,
    PerfectScrollbarModule,
    NgSelectModule,
    FormsModule,

  ],
  exports: [
    MatGridListModule,
    MatInputModule,
    MatCardModule
  ],
})
export class PosModule {
}
