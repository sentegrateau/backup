import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {HttpClientModule} from '@angular/common/http';
import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {NgxSpinnerModule} from 'ngx-spinner';
import {ToastrModule} from 'ngx-toastr';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import {NgxStripeModule} from 'ngx-stripe';


@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    HttpClientModule,
    NgxSpinnerModule,
    ToastrModule.forRoot(),
    NgbModule,
    NgxStripeModule.forRoot('pk_test_51Hn5ulKEVFNIyXJqpkPEewU8TYgrL2lx3loOaJX1eo2Vp5nzMoeRzl6kHcUmUJBZJ0DQjtZ1RiqUa82K45fa40Gz00At5kTqT7')
  ],
  exports: [
    BrowserAnimationsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
}
