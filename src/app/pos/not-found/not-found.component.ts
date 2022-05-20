import {Component, OnInit, ViewEncapsulation} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {StripeService} from 'ngx-stripe';
import {ICart, Items as CItems, Items} from '../../model/cart.model';
import {Items as CItems1} from '../../model/cartSecond.model';
import {Router} from '@angular/router';
import {PosService} from '../pos.service';
import {ToastrService} from 'ngx-toastr';
import {countries, Countries} from '../data/countries';
import {NgxSpinnerService} from 'ngx-spinner';
import {FilteredItem} from '../../model/interfaces';
import {CurrencyPipe} from '@angular/common';

@Component({
  selector: 'app-cart',
  templateUrl: './not-found.component.html',
  styleUrls: ['./not-found.component.scss']
})
export class NotFoundComponent implements OnInit {


  invalidTokenHeading = '';
  invalidTokenContent = '';

  constructor(private posService: PosService) {

  }

  ngOnInit(): void {
    this.posService.getInvalidTokenString().subscribe((data) => {
      this.invalidTokenHeading = data.setting.invalid_token_heading;
      this.invalidTokenContent = data.setting.invalid_token_content;
    });


  }

}
