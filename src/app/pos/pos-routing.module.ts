import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {PosComponent} from './pos.component';
import {CheckoutComponent} from './checkout/checkout.component';
import {PaymentSuccessComponent} from './payment-success/payment-success.component';
import {PaymentErrorComponent} from './payment-error/payment-error.component';
import {CartComponent} from './cart/cart.component';
import {NotFoundComponent} from './not-found/not-found.component';

const routes: Routes = [{
  path: '',
  children: [
    {
      path: '',
      component: PosComponent
    },
    {
      path: 'cart',
      component: CartComponent
    }, {
      path: 'checkout',
      component: CheckoutComponent
    },
    {
      path: 'order-success',
      component: PaymentSuccessComponent
    },
    {
      path: 'order-error',
      component: PaymentErrorComponent
    }, {
      path: '*',
      component: NotFoundComponent
    }, {
      path: 'invalid-token',
      component: NotFoundComponent
    }
  ]
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PosRoutingModule {
}
