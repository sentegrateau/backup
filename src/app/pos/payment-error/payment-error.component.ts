import { Component, OnInit } from '@angular/core';
import {Route, Router} from '@angular/router';

@Component({
  selector: 'app-payment-error',
  templateUrl: './payment-error.component.html',
  styleUrls: ['./payment-error.component.scss']
})
export class PaymentErrorComponent implements OnInit {

  constructor(private route: Router) { }

  ngOnInit(): void {
  }
  home(): void {
    let token = null;
    const getToken = localStorage.getItem('token');
    if (getToken != null) {
      token = getToken;
    }
    this.route.navigate(['/'], { queryParams: {token}});
  }

}
