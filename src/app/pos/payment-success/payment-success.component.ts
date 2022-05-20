import { Component, OnInit } from '@angular/core';
import {Router} from '@angular/router';

@Component({
  selector: 'app-payment-success',
  templateUrl: './payment-success.component.html',
  styleUrls: ['./payment-success.component.scss']
})
export class PaymentSuccessComponent implements OnInit {

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
