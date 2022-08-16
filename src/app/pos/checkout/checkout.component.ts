import {Component, ElementRef, OnInit, ViewChild, ViewEncapsulation, HostListener} from '@angular/core';
import {FormBuilder, FormGroup, ValidationErrors, Validators} from '@angular/forms';
import {StripeService} from 'ngx-stripe';
import {ICart, Items as CItems, Items} from '../../model/cart.model';
import {Router} from '@angular/router';
import {PosService} from '../pos.service';
import {ToastrService} from 'ngx-toastr';
import {countries, Countries} from '../data/countries';
import {states, States} from '../data/states';
import {NgxSpinnerService} from 'ngx-spinner';
import jwt_decode from 'jwt-decode';
import {DecodedToken,FilteredItem, User} from '../../model/interfaces';
import {environment} from '../../../environments/environment';
import {LocationStrategy} from '@angular/common';

declare var paypal: any;

@Component({
  selector: 'app-checkout',
  templateUrl: './checkout.component.html',
  styleUrls: ['./checkout.component.scss'],
  encapsulation: ViewEncapsulation.None
})

@HostListener('window:popstate', ['$event'])

export class CheckoutComponent implements OnInit {


  @ViewChild('shipzip') shipZip: any;
  @ViewChild('billzip') billZip: any;
  @ViewChild('shipstateinput') shipStateInput: any;
  @ViewChild('shipstateselect') shipStateSelect: any;
  public showPassword: boolean;
  public showPasswordOnPress: boolean;
  public conshowPassword: boolean;
  public showProfileDetails = 1;
  public checkoutForm: FormGroup;
  public ContactForm: FormGroup;
  public billingAddressForm: FormGroup;
  public submitted = false;
  public isCountryAusShip = false;
  public isCountryAusBill = false;
  public cart: ICart[] = [];
  public items: Items[] = [];
  public totalAmount = 0;
  public totalAmountDisShipping = 0;
  public GSTAmount = 0;
  public countries: Countries[] = countries;
  public states: States[] = states;
  public sameAddress = true;
  public sameAdd = true;
  public userId: any;
  public ShipDetails: any;
  public DiscountOnCoupon: any = 0;
  public isAcceptTerms = false;
  public GSTDiscount: any = 0;
  shipping_address: any = '';
  shipping_address_2: any = '';
  shipping_first_name: any = '';
  shipping_last_name: any = '';
  shipping_state: any = '';
  shipping_zip: any = '';
  shipping_city: any = '';
  billing_zip: any = '';
  shipping_email: any = '';
  shipping_country: any = '';
  shipping_phone: any = '';
  billing_country: any = '';
  shipping_data: any;
  billing_address: any = '';
  billing_address_2: any = '';
  billing_first_name: any = '';
  billing_last_name: any = '';
  billing_state: any = '';
  billing_city: any = '';
  billing_email: any = '';
  billing_phone: any = '';
  userData: any;
  getUserData: any;
  getOrderData: any = '';
  public changeValue: string;
  @ViewChild('paypal') paypalElement: ElementRef;
  @ViewChild('checkoutBtn') checkoutBtnElement: ElementRef;

  public paymentMethod = 'bank_transfer';

  public settings: any = {
    gst: 0,
    pay_installation: 0,
    ship_standard_text: '', ship_express_text: '', standard_ship_amt: 0, express_ship_amt: 0
  };

  public ship_method = 'standard';

  public pay_on_installation = 0;
  showShippingDetails: number = 2;
  usertypes: number = 1; 
  public pay_due_now = 0;

  constructor(location: LocationStrategy,
              private stripeService: StripeService,
              private fb: FormBuilder,
              private route: Router,
              private posService: PosService,
              private toaster: ToastrService,
              private spinner: NgxSpinnerService,
              private el: ElementRef) {
    this.getUserData = sessionStorage.getItem('user');
    this.getUserData = JSON.parse(this.getUserData);

     var param:any = sessionStorage.getItem('token');
    // console.log(param);
          var decodedToken: DecodedToken = jwt_decode(param);
       //   console.log(decodedToken.partner_id);

    this.reloadIfNull();
    this.getSettings(() => {
      this.getItemsFromLocalStorage();
    });
    this.getUserOnLoad();
  }

  ngOnInit(): void {
    // this.DiscountOnCoupon =
    const discData = sessionStorage.getItem('complete_discount');
    const gstDiscount = sessionStorage.getItem('gst_discount');
    if (discData != null) {
      this.DiscountOnCoupon = parseFloat(discData);
    }
    if (gstDiscount != null) {
      this.GSTDiscount = gstDiscount;
    }

    this.shipping_data = sessionStorage.getItem('shippingDetails');

      const shipping_address = this.shipping_address = sessionStorage.getItem('shipping_address');
      const shipping_address_2 = this.shipping_address_2 = sessionStorage.getItem('shipping_address_2');
      const shipping_first_name = this.shipping_first_name = sessionStorage.getItem('shipping_first_name');
      const shipping_last_name = this.shipping_last_name = sessionStorage.getItem('shipping_last_name');
      const shipping_state = this.shipping_state = sessionStorage.getItem('shipping_state');
      const shipping_zip = this.shipping_zip = sessionStorage.getItem('shipping_zip');
      const shipping_city = this.shipping_city = sessionStorage.getItem('shipping_city');
      const shipping_email = this.shipping_email = sessionStorage.getItem('shipping_email');
      const shipping_country = this.shipping_country = sessionStorage.getItem('shipping_country');
  
    if (this.shipping_data == 'null') {
      this.showShippingDetails = 0;
    }
    console.log(this.shipping_first_name);
    if(this.shipping_first_name =="" && this.shipping_address =="" && this.shipping_address_2 =="" && this.shipping_last_name =="" && this.shipping_state =="" && this.shipping_zip =="" && this.shipping_city =="" && this.shipping_country =="")
    {
           this.showShippingDetails = 3;
    }

      var param:any = sessionStorage.getItem('token');
          var decodedToken: DecodedToken = jwt_decode(param);
       //   console.log(decodedToken.partner_id);
       if (decodedToken.partner_id=="dhs")
       {
         this.usertypes=1;
       }
       else{

         this.usertypes=0;

       }

    // this.getShippAdress();
    this.formGroupInit();
    this.billingAddressFormGroup();
  }

  reloadIfNull() {
    const items = sessionStorage.getItem('cart_items');
    if (items === null) {
      parent.window.location.href = environment.siteUrl + 'quote';
    }
    //
  }

  AddShippingAddress() {
    this.showShippingDetails = 1;
     if (this.shipping_data == 'null') {
    this.isCountryAusShip = true;
  }
    // parent.window.location.href = environment.siteUrl+'user/profile';
  }

  /**
   * Checkout Form
   */
  private formGroupInit() {
    // @ts-ignore
    const getUser: User = JSON.parse(sessionStorage.getItem('user'));
    // @ts-ignore
    const newData = (sessionStorage.getItem('shippingDetails')) ? JSON.parse(sessionStorage.getItem('shippingDetails')) : {};
    //this.userId = getUser.id;
    if (newData != null) {
      this.sameAdd = newData.same_add == null ? false : (newData.same_add == 1 ? true : false);
      const shipping_address = this.shipping_address = sessionStorage.getItem('shipping_address');
      const shipping_address_2 = this.shipping_address_2 = sessionStorage.getItem('shipping_address_2');
      const shipping_first_name = this.shipping_first_name = sessionStorage.getItem('shipping_first_name');
      const shipping_last_name = this.shipping_last_name = sessionStorage.getItem('shipping_last_name');
      const shipping_state = this.shipping_state = sessionStorage.getItem('shipping_state');
      const shipping_zip = this.shipping_zip = sessionStorage.getItem('shipping_zip');
      const shipping_city = this.shipping_city = sessionStorage.getItem('shipping_city');
      const billing_zip = this.billing_zip = sessionStorage.getItem('billing_zip');
      const shipping_email = this.shipping_email = sessionStorage.getItem('shipping_email');
      const shipping_country = this.shipping_country = sessionStorage.getItem('shipping_country');
      if (shipping_country == 'Australia') {
        this.isCountryAusShip = true;
      }


      const shipping_phone = this.shipping_phone = sessionStorage.getItem('shipping_phone');
      const billing_country = this.billing_country = sessionStorage.getItem('billing_country');
      if (billing_country == 'Australia') {
        this.isCountryAusBill = true;
      }
      const billing_address = this.billing_address = sessionStorage.getItem('billing_address');
      const billing_address_2 = this.billing_address_2 = sessionStorage.getItem('billing_address_2');
      const billing_first_name = this.billing_first_name = sessionStorage.getItem('billing_first_name');
      const billing_last_name = this.billing_last_name = sessionStorage.getItem('billing_last_name');
      const billing_state = this.billing_state = sessionStorage.getItem('billing_state');
      const billing_city = this.billing_city = sessionStorage.getItem('billing_city');
      const billing_email = this.billing_email = sessionStorage.getItem('billing_email');
      const billing_phone = this.billing_phone = sessionStorage.getItem('billing_phone');

      //this.sameAdd = !newData.same_add;
      this.checkoutForm = this.fb.group({
        name: [getUser.name, Validators.required],
        contact: [getUser.contact, Validators.required],
        company: [getUser.company, Validators.required],
        abn: [getUser.abn, Validators.required],
        password: ['', [Validators.pattern('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{6,}')]],
        confirm_password: ['', [Validators.pattern('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{6,}')]],
        firstName: [shipping_first_name, Validators.required],
        lastName: [shipping_last_name, Validators.required],
        //email: [shipping_email, Validators.compose([Validators.required, Validators.email])],
        phone: [shipping_phone, Validators.required],
        address: [shipping_address, Validators.required],
        address2: [shipping_address_2],
        country: [shipping_country, Validators.required],
        state: [shipping_state, Validators.required],
        city: [shipping_city, Validators.required],
        zip: [shipping_zip, Validators.required],
       

      }, {validators: this.ConfirmedValidator('password', 'confirm_password')});
      this.ContactForm = this.fb.group({
        name: [getUser.name, Validators.required],
        contact: [getUser.contact, Validators.required],
        company: [getUser.company, Validators.required],
        abn: [getUser.abn, Validators.required],
        password: ['', [Validators.pattern('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{6,}')]],
        confirm_password: ['', [Validators.pattern('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{6,}')]]     
      }, {validators: this.ConfirmedValidator('password', 'confirm_password')});
    } else {
      const shipping_country = this.shipping_country = 'Australia';
      const billing_country = this.billing_country = 'Australia';
      this.checkoutForm = this.fb.group({
        name: [getUser.name, Validators.required],
        contact: [getUser.contact, Validators.required],
        company: [getUser.company, Validators.required],
        abn: [getUser.abn, Validators.required],
        password: ['', [Validators.pattern('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{6,}')]],
        confirm_password: ['', [Validators.pattern('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{6,}')]],
        firstName: ['', Validators.required],
        lastName: ['', Validators.required],
       // email: ['', Validators.compose([Validators.required, Validators.email])],
        phone: ['', Validators.required],
        address: ['', Validators.required],
        address2: [''],
        country: ['', Validators.required],
        state: ['', Validators.required],
        city: ['', Validators.required],
        zip: ['', Validators.required],


      }, {validators: this.ConfirmedValidator('password', 'confirm_password')});

       this.ContactForm = this.fb.group({
        name: [getUser.name, Validators.required],
        contact: [getUser.contact, Validators.required],
        company: [getUser.company, Validators.required],
        abn: [getUser.abn, Validators.required],
        password: ['', [Validators.pattern('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{6,}')]],
        confirm_password: ['', [Validators.pattern('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{6,}')]]      
      }, {validators: this.ConfirmedValidator('password', 'confirm_password')});
    }
  }

  ConfirmedValidator(controlName: string, matchingControlName: string) {
    return (formGroup: FormGroup) => {
      const control = formGroup.controls[controlName];
      const matchingControl = formGroup.controls[matchingControlName];
      if (matchingControl.errors && !matchingControl.errors.confirmedValidator) {
        return;
      }
      if (control.value !== matchingControl.value) {
        matchingControl.setErrors({confirmedValidator: true});
      } else {
        matchingControl.setErrors(null);
      }
    };
  }

  get f(): any {
    return this.checkoutForm.controls;
  }

  /**
   * @private Billing Address Form Init
   */
  private billingAddressFormGroup(): void {
    const billing_country = sessionStorage.getItem('billing_country');
    const billing_address = sessionStorage.getItem('billing_address');
    const billing_address_2 = sessionStorage.getItem('billing_address_2');
    const billing_first_name = sessionStorage.getItem('billing_first_name');
    const billing_last_name = sessionStorage.getItem('billing_last_name');
    const billing_state = sessionStorage.getItem('billing_state');
    const billing_city = sessionStorage.getItem('billing_city');
    const billing_email = sessionStorage.getItem('billing_email');
    const billing_phone = sessionStorage.getItem('billing_phone');
    const billing_zip = sessionStorage.getItem('billing_zip');
    this.billingAddressForm = this.fb.group({
      firstName: [billing_first_name, Validators.required],
      lastName: [billing_last_name, Validators.required],
     // email: [billing_email, Validators.required],
      phone: [billing_phone, Validators.required],
      address: [billing_address, Validators.required],
      address2: [billing_address_2],
      country: [billing_country, Validators.required],
      state: [billing_state, Validators.required],
      city: [billing_city, Validators.required],
      zip: [billing_zip, Validators.required]
    });
  }


  get g(): any {
    return this.billingAddressForm.controls;
  }

  /***
   * Checkout
   */
  errorAddress: any = '';

  public checkout(): void {
    this.submitted = true;
    //let err = this.getFormValidationErrors();
  
    if (this.isAcceptTerms == false) {
     // this.spinner.hide();
      this.toaster.error('please accept terms and conditions.');
      return;
    }
      if(this.checkoutForm.invalid)
      {
      //this.errorAddress = err;
       this.toaster.error('Contact/Shipping Details are Missing');
      return;
    }
   


    const billingData = this.sameAdd ? this.getFormValues(this.checkoutForm.value) : this.getFormValues(this.billingAddressForm.value);
    const shippingData = this.getFormValues(this.checkoutForm.value);
    if ((billingData.country == 'Australia' && billingData.zip.length != 4) || (shippingData.country == 'Australia' && shippingData.zip.length != 4)) {
      this.toaster.error('Postcode must be 4 digits.');
    } else {
      shippingData.same_add = this.sameAdd;
      const userInfo = this.getUserValues(this.checkoutForm.value);
      const coupon_code = sessionStorage.getItem('coupon_code');
      const data = {
        order_data: this.orderData(),
        shipping_information: shippingData,
        billing_information: billingData,
        user_information: userInfo,
        stripe_data: this.filterStripeData(),
        coupon_code: coupon_code,
        paid_amount: (((this.totalAmountDisShipping) * this.pay_due_now) / 100),
      };
      this.spinner.show();
      var param:any = sessionStorage.getItem('token');
     console.log(param);
          var decodedToken: DecodedToken = jwt_decode(param);
          console.log(decodedToken.partner_id);

      this.posService.checkout(data,decodedToken.partner_id).subscribe(
        (res: any) => {
          this.spinner.hide();
          // this.route.navigate(['/order-success']);
          location.href = environment.siteUrl + 'thank-you/' + res.order.id +'/'+ decodedToken.partner_id ;
        }, error => {
          this.spinner.hide();
          this.toaster.error('Error in creating stripe sessions');
        }
      );
    }
  }

  public updateProfile(): void {
    this.submitted = true;
    //let err = this.getFormValidationErrors();
    if (this.ContactForm.invalid) {
    //  this.errorAddress = err;
       //  this.toaster.error('Profile Details are Missing');

    //  return;
    }
    const userInfo = this.getUserValues(this.checkoutForm.value);
    userInfo.user_id = this.orderData().user_id;
    userInfo.password_confirmation = userInfo.confirm_password;
    const data = {
      user_information: userInfo,
    };
    this.spinner.show();
    this.posService.updateProfile(data).subscribe(
      (res: any) => {
        this.spinner.hide();
        this.toaster.success('Updated Successfully...');
        const settingtooltip=this.getUserData.settings;
        this.getUserData = sessionStorage.getItem('user');
        this.getUserData = JSON.parse(this.getUserData);
        this.getUserData.settings=settingtooltip;
        this.showProfileDetails = 1;
      }, error => {
        this.spinner.hide();
        this.toaster.error('Error in updating');
      }
    );
  }

  public updateShipping(): void {
    this.submitted = true;
   //let err = this.getFormValidationErrors();
  //  console.log(this.billingAddressForm);
    if (this.billingAddressForm.invalid) {
     // this.errorAddress = err;
     // this.toaster.error('Shipping Details are Missing');
    // return;
    }
    const billingData = this.sameAdd ? this.getFormValues(this.checkoutForm.value) : this.getFormValues(this.billingAddressForm.value);
    const shippingData = this.getFormValues(this.checkoutForm.value);
    console.log(billingData.country);
    console.log(billingData.zip);
    console.log(shippingData.country);
    console.log(shippingData.zip);
    if ((billingData.country == 'Australia' && billingData.zip.length != 4) || (shippingData.country == 'Australia' && shippingData.zip.length != 4)) {
      this.toaster.error('Postcode must be 4 digits.');
    } else {
      shippingData.same_add = this.sameAdd;
      shippingData.user_id = this.orderData().user_id;
      const data = {
        shipping_information: shippingData,
        billing_information: billingData
      };
      this.spinner.show();
      this.posService.updateShipping(data).subscribe(
        (res: any) => {
          this.sameAdd = res.shipping_details.same_add == null ? false : (res.shipping_details.same_add == 1 ? true : false);
          this.shipping_address = res.shipping_details.shipping_address;
          this.shipping_address_2 = res.shipping_details.shipping_address_2;
          this.shipping_first_name = res.shipping_details.shipping_first_name;
          this.shipping_last_name = res.shipping_details.shipping_last_name;
          this.shipping_state = res.shipping_details.shipping_state;
          this.shipping_zip = res.shipping_details.shipping_zip;
          this.shipping_city = res.shipping_details.shipping_city;
          this.billing_zip = res.shipping_details.billing_zip;
          this.shipping_email = res.shipping_details.shipping_email;
          this.shipping_country = res.shipping_details.shipping_country;
          this.shipping_phone = res.shipping_details.shipping_phone;
          this.billing_country = res.shipping_details.billing_country;
          this.billing_address = res.shipping_details.billing_address;
          this.billing_address_2 = res.shipping_details.billing_address_2;
          this.billing_first_name = res.shipping_details.billing_first_name;
          this.billing_last_name = res.shipping_details.billing_last_name;
          this.billing_state = res.shipping_details.billing_state;
          this.billing_city = res.shipping_details.billing_city;
          this.billing_email = res.shipping_details.billing_email;
          this.billing_phone = res.shipping_details.billing_phone;
          this.showShippingDetails = 2;
          this.spinner.hide();
          this.toaster.success('Updated Successfully...');
        }, error => {
          this.spinner.hide();
          this.toaster.error('Error in updating');
        }
      );
    }
  }

  /**
   * Order Data
   */
  private orderData(): any {
    const order: any = {};
    const orderItems: any = [];
    // @ts-ignore
    const getUser = JSON.parse(sessionStorage.getItem('user'));
    // @ts-ignore
    order.user_id = getUser.id;
    order.partner_id = 1;
    order.type = 'admin';
    order.amount = this.totalAmount;
    order.discount = this.DiscountOnCoupon;
    for (const cart of this.cart) {
      for (const cartItem of cart.Items) {
        const data = {
          package_id: cart.package_id,
          room_id: cartItem.roomId,
          device_id: cartItem.id,
          quantity: cartItem.quantity
        };
        orderItems.push(data);
      }
    }
    order.order_items = orderItems;
    order.ship_type = this.ship_method;
    order.payment_type = this.paymentMethod;
    order.draft_id = sessionStorage.getItem('draft_id');
    return order;
  }

  /**
   * Filtering Stripe Data
   */
  private filterStripeData(): any {
    const data: any[] = [];
    for (const item of this.items) {
      const itemData = {
        price_data: {
          product_data: {
            name: item.title
          },
          unit_amount: item.price * 100,
          currency: 'usd'
        },
        quantity: item.quantity
      };
      data.push(itemData);
    }
    return data;
  }

  /**
   * Back to Main Page
   */
  public backToMain(): void {
    /*const data: any = {
      token: '',
      fromCheckout: true
    };
    const getToken = sessionStorage.getItem('token');
    if (getToken != null) {
      data.token = getToken;
    }
    Object.keys(data).forEach(key => data[key] === '' ?  delete  data[key] : data[key]);*/
    this.route.navigate(['/cart']);
  }

  /**
   * Collect Form Value
   */
  private getFormValues(formControl: any): any {
    const data: any = {
      first_name: formControl.firstName,
      last_name: formControl.lastName,
      email: formControl.email,
      address: formControl.address,
      address2: formControl.address2,
      country: formControl.country,
      state: formControl.state,
      city: formControl.city,
      zip: formControl.zip,
      phone: formControl.phone
    };
    Object.keys(data).forEach(key => data[key] === null || data[key] === '' || data[key] === undefined ? delete data[key] : data[key]);
    return data;
  }

  private getUserValues(formControl: any): any {
    const data: any = {
      name: formControl.name,
      contact: formControl.contact,
      abn: formControl.abn,
      company: formControl.company,
      password: formControl.password,
      confirm_password: formControl.confirm_password
    };
    Object.keys(data).forEach(key => data[key] === null || data[key] === '' || data[key] === undefined ? delete data[key] : data[key]);
    return data;
  }

  /**
   * Parsing Data from LocalStorage
   */
  private getItemsFromLocalStorage(): void {
    const items = sessionStorage.getItem('cart_items');
    if (items != null) {
      this.cart = JSON.parse(items);
    }
    if (this.cart.length) {
      for (const cItem of this.cart) {
        for (const item of cItem.Items) {
          this.items.push(item);
        }
      }
    }
    this.adjustAmount();
  }

  /**
   * Same address check
   */
  public checkSameAddress(): void {
    this.sameAdd = !this.sameAdd;
  }

  /**
   * Adjusting Total Amount
   */

  /*private adjustAmount(): any {
      this.totalAmount = 0;
      this.GSTAmount = 0;
      for (const obj of this.cart) {
          obj.total_amount = 0;

          for (const item of obj.Items) {
              const price = (item.price * item.quantity);
              obj.total_amount += price;
              this.GSTAmount += this.getGstAmt(price);
          }

          this.totalAmount += obj.total_amount;


      }
      this.totalAmount += this.GSTAmount;
      this.totalAmount = this.totalAmount - this.DiscountOnCoupon;
      this.totalAmount += parseInt((this.ship_method == 'standard') ? this.settings.standard_ship_amt : this.settings.express_ship_amt);

  }*/

  private adjustAmount(): any {
    //console.log("call")
    this.totalAmount = 0;
    this.GSTAmount = 0;
    for (const item of this.cart) {
      this.totalAmount += item.total_amount;
      this.GSTAmount += this.getGstAmt2Digits(item.total_amount);
    }
    this.totalAmount += this.GSTAmount;
    this.totalAmountDisShipping = this.totalAmount - this.DiscountOnCoupon;
    this.totalAmountDisShipping += parseInt((this.ship_method == 'standard') ? this.settings.standard_ship_amt : this.settings.express_ship_amt);

  }

  filteringSubItems(items: CItems[]): any {
    const ids = items.map(e => e.id);
    const uniqueIds = [...new Set(ids)];
    const result: FilteredItem[] = [];
    for (const id of uniqueIds) {
      const filteredItems = items.filter(e => e.id === id);
      for (const filteredItem of filteredItems) {
        const filteredItemInResult = result.find(e => e.id === filteredItem.id);
        if (filteredItemInResult) {
          const index = result.indexOf(filteredItemInResult);
          result[index].quantity = result[index].quantity + filteredItem.quantity;
        } else {
          const data = {id: filteredItem.id, title: filteredItem.title, quantity: filteredItem.quantity};
          result.push(data);
        }
      }
    }
    return result;
  }

  getSettings(cb: any) {
    this.posService.getGstSetting().subscribe(setting => {
      this.settings = setting.data.settings;
      this.pay_due_now = 100 - setting.data.settings.pay_installation;
      this.pay_on_installation = 100 - this.pay_due_now;
      cb();
    });
  }

  getUserOnLoad() {
    const data = {
      user_id: this.orderData().user_id,
    };
    this.posService.getUserData(data).subscribe(
      (res: any) => {
        this.getUserData = sessionStorage.getItem('user');
        this.getUserData = JSON.parse(this.getUserData);
      }, error => {
        console.log('error in getting user data');
      }
    );
  }

  getGstAmt(price: number) {
    //return parseFloat((((price * this.settings.gst) / 100)).toFixed(2));
    return parseFloat(this.roundoff(((price * this.settings.gst) / 100)));
  }

  // tslint:disable-next-line:typedef
  shipMethod() {
    this.adjustAmount();
  }

  // tslint:disable-next-line:typedef
  paypalPaymentButton() {
    paypal.Buttons({
      //intent:'CAPTURE',
      style: {
        shape: 'rect',
        color: 'gold',
        layout: 'vertical',
        label: 'paypal',
      },
      advanced: {
        commit: 'true'
      },
      createOrder: async (data: any, actions: any) => {
        try {
          this.submitted = true;
          if (this.isAcceptTerms == false) {
            this.toaster.error('please accept terms and conditions.');
            return;
          }
          if (this.checkoutForm.invalid) {
           /* if (!this.sameAddress && this.billingAddressForm.invalid) {
              return;
            }*/
           this.toaster.error('Contact/Shipping Details are Missing');
            return;
          } 

         
          // tslint:disable-next-line:max-line-length
          const billingData = this.sameAdd ? this.getFormValues(this.checkoutForm.value) : this.getFormValues(this.billingAddressForm.value);
          const shippingData = this.getFormValues(this.checkoutForm.value);
          shippingData.same_add = this.sameAdd;
          const coupon_code = sessionStorage.getItem('coupon_code');
          const dataVal = {
            order_data: this.orderData(),
            shipping_information: shippingData,
            user_information: this.getUserValues(this.checkoutForm.value),
            billing_information: billingData,
            stripe_data: this.filterStripeData(),
            coupon_code: coupon_code,
            paid_amount: (((this.totalAmountDisShipping) * this.pay_due_now) / 100),
          };
          //this.spinner.show();
          const orderD = await this.posService.checkoutPaypal(dataVal).toPromise();
          this.getOrderData = orderD;
          console.log(orderD.order.id);
          return await actions.order.create({
            purchase_units: [{
              invoice_id: 'INVOICE-' + orderD.order.user_id + '-' + orderD.order.id,
              amount: {currency_code: 'AUD', value: orderD.order.paid_amt}
            }],
            payer: {
              name: {
                given_name: billingData.first_name,
                surname: billingData.last_name
              },
              address: {
                address_line_1: billingData.address,
                address_line_2: this.billing_address_2,
                admin_area_1: billingData.state,
                admin_area_2: billingData.city,
                postal_code: billingData.zip,
                country_code: 'AU'
              },
              email_address: this.getUserData.email,
              phone: {
                phone_type: 'MOBILE',
                phone_number: {
                  national_number: this.getUserValues(this.checkoutForm.value).contact
                }
              }
            },
            application_context: {
              shipping_preference: 'NO_SHIPPING',
            }
          });
        } catch (error) {
          this.spinner.hide();
        }
      },

      onApprove: async (data: any, actions: any) => {
        this.spinner.hide();
        return actions.order.capture().then((orderData: any) => {
          //send emails after payment success
          if (this.getOrderData.order != null) {
            const dataVal = {
              order_id: this.getOrderData.order.id,
              email: orderData.payer.email_address
            };
            var param:any = sessionStorage.getItem('token');
             var decodedToken: DecodedToken = jwt_decode(param);

            this.posService.checkoutPaypalSuccess(dataVal,decodedToken.partner_id).toPromise().then(res => {
              this.spinner.hide();

              // Full available details
              const orderValues = (orderData.purchase_units[0].invoice_id).split('-');
              sessionStorage.removeItem('quotation');
              sessionStorage.removeItem('cart_items');
              
              // this.route.navigate(['/order-success']);
              location.href = environment.siteUrl + 'thank-you/' + orderValues[2] +'/'+ decodedToken.partner_id;
            });
          }
        });
      },
      onError: (err: any) => {
        //console.log(this.getOrderData);
        this.spinner.hide();
      },
      onCancel: () => {
        if (this.getOrderData.order != null) {
          const dataVal = {
            order_id: this.getOrderData.order.id
          };
          this.posService.checkoutDeleteOrder(dataVal).toPromise();
        }
        this.spinner.hide();
      }
    }).render(this.paypalElement.nativeElement);
  }

  paymentMethods() {
    switch (this.paymentMethod) {
      case 'paypal':
        this.paypalPaymentButton();
        this.checkoutBtnElement.nativeElement.style.display = 'none';
        break;
      default:
        this.paypalElement.nativeElement.innerHTML = '';
        this.checkoutBtnElement.nativeElement.style.display = 'block';
        break;
    }
  }

  changeBillCountry() {
    this.billZip.nativeElement.value = '';
    if (this.billing_country == 'Australia') {
      this.isCountryAusBill = true;
    } else {
      this.isCountryAusBill = false;
    }
  }

  changeShipCountry() {
    this.shipZip.nativeElement.value = '';
    if (this.shipping_country == 'Australia') {
      this.isCountryAusShip = true;
    } else {
      this.isCountryAusShip = false;
    }
  }

  changeZipShip(newval: string) {
    if (this.shipping_country == 'Australia' && newval.length != 4) {
      //this.toaster.error('Postcode must be 4 digits.');
      this.shipZip.nativeElement.value = '';
    }
  }

  changeZipBill(newval: string) {
    if (this.billing_country == 'Australia' && newval.length != 4) {
      //this.toaster.error('Postcode must be 4 digits.');
      this.billZip.nativeElement.value = '';
    }
  }

  getShippAdress() {
    const getUser = JSON.parse(this.userData);
    this.userId = getUser.id;
    let params = {'user_id': this.userId};
    this.posService.getShippingAddress(params).subscribe(
      (res: any) => {
        this.ShipDetails = res.data;
        if (res.data != null) {
          this.shipping_address = res.data.billing_address;
          if (res.data.shipping_address_2 != null) {
            this.shipping_address_2 = res.data.shipping_address_2;
          }
          if (res.data.shipping_first_name != null) {
            this.shipping_first_name = res.data.shipping_first_name;
          }
          if (res.data.shipping_last_name != null) {
            this.shipping_last_name = res.data.shipping_last_name;
          }
          if (res.data.shipping_state != null) {
            this.shipping_state = res.data.shipping_state;
          }
          if (res.data.shipping_zip != null) {
            this.shipping_zip = res.data.shipping_zip;
          }
          if (res.data.shipping_city != null) {
            this.shipping_city = res.data.shipping_city;
          }
          if (res.data.billing_zip != null) {
            this.billing_zip = res.data.billing_zip;
          }
          if (res.data.shipping_email != null) {
            this.shipping_email = res.data.shipping_email;
          }
          if (res.data.shipping_phone != null) {
            this.shipping_phone = res.data.shipping_phone;
          }
          if (res.data.billing_address != null) {
            this.billing_address = res.data.billing_address;
          }
          if (res.data.billing_address_2 != null) {
            this.billing_address_2 = res.data.billing_address_2;
          }
          if (res.data.billing_first_name != null) {
            this.billing_first_name = res.data.billing_first_name;
          }
          if (res.data.billing_last_name != null) {
            this.billing_last_name = res.data.billing_last_name;
          }
          if (res.data.billing_state != null) {
            this.billing_state = res.data.billing_state;
          }
          if (res.data.billing_city != null) {
            this.billing_city = res.data.billing_city;
          }
          if (res.data.billing_email != null) {
            this.billing_email = res.data.billing_email;
          }
          if (res.data.billing_phone != null) {
            this.billing_phone = res.data.billing_phone;
          }
          if (res.data.shipping_country != null) {
            this.shipping_country = res.data.shipping_country;
          }
          if (res.data.shipping_country != null) {
            this.shipping_country = res.data.shipping_country;
          }
          if (res.data.billing_country != null) {
            this.billing_country = res.data.billing_country;
          }
        }
      }, error => {
        //this.toaster.error('Error in creating stripe sessions');
      }
    );
  }

  getFormValidationErrors() {
    //console.log(this.checkoutForm.controls);
    let isError = 'false';
    Object.keys(this.checkoutForm.controls).forEach((key) => {
      // @ts-ignore
      const controlErrors: ValidationErrors = this.checkoutForm.get(key).errors;
      if (controlErrors != null) {
        Object.keys(controlErrors).forEach((keyError, index,cutArr) => {
          isError = 'true';
          if ( index === 0){
            const invalidControl = this.el.nativeElement.querySelector('[formcontrolname="' + key + '"]');
            invalidControl.focus();
          }
          console.log('Key control: ' + key + ', keyError: ' + keyError + ', err value: ', controlErrors[keyError]);
        });
      }
    });

    if (isError == 'true') {
      this.toaster.error('Please fill all the required fields..');
      this.showProfileDetails = 0;
      this.showShippingDetails = 1;
    }
  }

  editProfile() {
    this.showProfileDetails = 0;
  }

  termsConditions() {
    //location.href = environment.siteUrl + 'terms-conditions';
    window.open(environment.siteUrl + 'terms-conditions', '_blank');
  }

  roundoff(amount: any): any {
    return Math.round((amount + Number.EPSILON) * 100) / 100;
  }

  getGstAmt2Digits(price: number) {
    //return ( Math.round((( (price * this.settings.gst) / 100) + Number.EPSILON) * 100) / 100  );
    return this.roundoff((price * this.settings.gst) / 100);
  }
}
