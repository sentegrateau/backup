import {Component, ElementRef, OnInit, ViewChild, ViewEncapsulation} from '@angular/core';
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
    templateUrl: './cart.component.html',
    styleUrls: ['./cart.component.scss'],
    encapsulation: ViewEncapsulation.None,
    providers: [CurrencyPipe]
})
export class CartComponent implements OnInit {
    @ViewChild('couponRef') couponName: any;
    public isApplyButton = true;
    public isRemoveButton = false;
    public checkoutForm: FormGroup;
    public billingAddressForm: FormGroup;
    public submitted = false;
    public cart: ICart[] = [];
    public items: Items[] = [];
    public totalAmountAfter = 0;
    public GSTAmountAfter = 0;
    public totalAmount = 0;
    public GSTAmount = 0;
    public countries: Countries[] = countries;
    public sameAddress = true;
    public isCouponApplied = false;
    public ErrMessage = '';
    public payment_due_now_tooltip = '';
    public payment_due_on_installation_tooltip = '';
    public successMessage = '';
    public settings: any = {gst: 0, pay_installation: 0};
    totalDiscount: number = 0;
    GSTDiscount: number = 0;
    public pay_on_installation = 0;

    public pay_due_now = 0;
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

    billing_address: any = '';
    billing_address_2: any = '';
    billing_first_name: any = '';
    billing_last_name: any = '';
    billing_state: any = '';
    billing_city: any = '';
    billing_email: any = '';
    billing_phone: any = '';
    userData: any;
    userId: any;

    constructor(private stripeService: StripeService,
                private fb: FormBuilder,
                private route: Router,
                private posService: PosService,
                private toaster: ToastrService,
                private spinner: NgxSpinnerService,
                private currencyPipe: CurrencyPipe) {
        this.userData = sessionStorage.getItem('user');
        this.getShippAdress();
        this.getSettings(() => {
            this.getItemsFromLocalStorage();
        });
        sessionStorage.setItem('complete_discount', '0');
    }

    ngOnInit(): void {
        sessionStorage.setItem('gst_discount', '0');
        this.formGroupInit();
        this.billingAddressFormGroup();

    }

    /**
     * Checkout Form
     */
    private formGroupInit(): void {
        this.checkoutForm = this.fb.group({
            firstName: ['', Validators.required],
            lastName: ['', Validators.required],
            email: ['', Validators.compose([Validators.required, Validators.email])],
            phone: ['', Validators.required],
            address: ['', Validators.required],
            address2: [''],
            country: [null, Validators.required],
            state: ['', Validators.required],
            city: ['', Validators.required],
            zip: ['', Validators.required]
        });
    }

    get f(): any {
        return this.checkoutForm.controls;
    }

    /**
     * @private Billing Address Form Init
     */
    private billingAddressFormGroup(): void {
        this.billingAddressForm = this.fb.group({
            firstName: ['', Validators.required],
            lastName: ['', Validators.required],
            email: ['', Validators.required],
            phone: ['', Validators.required],
            address: ['', Validators.required],
            address2: [''],
            country: [null, Validators.required],
            state: ['', Validators.required],
            city: ['', Validators.required],
            zip: ['', Validators.required]
        });
    }

    get g(): any {
        return this.billingAddressForm.controls;
    }

    /***
     * Checkout
     */
    public checkout(): void {
        this.submitted = true;
        if (this.checkoutForm.invalid) {
            if (!this.sameAddress && this.billingAddressForm.invalid) {
                return;
            }
            return;
        }
        const billingData = this.sameAddress ? this.getFormValues(this.checkoutForm.value) : this.getFormValues(this.billingAddressForm.value);
        const data = {
            order_data: this.orderData(),
            shipping_information: this.getFormValues(this.checkoutForm.value),
            billing_information: billingData,
            stripe_data: this.filterStripeData()
        };
        this.spinner.show();
        this.posService.checkout(data).subscribe(
            (res: any) => {
                this.spinner.hide();
            }, error => {
                this.spinner.hide();
                this.toaster.error('Error in creating stripe sessions');
            }
        );
    }

    /**
     * Order Data
     */
    private orderData(): any {
        const order: any = {};
        const orderItems: any = [];
        order.user_id = 1;
        order.partner_id = 1;
        order.type = 'admin';
        order.amount = this.totalAmount;
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
        const data: any = {
            token: '',
            fromCheckout: true
        };
        const getToken = sessionStorage.getItem('token');
        if (getToken != null) {
            data.token = getToken;
        }
        Object.keys(data).forEach(key => data[key] === '' ? delete data[key] : data[key]);
        this.route.navigate(['/'], {queryParams: data});
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
            zip: formControl.zip
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
            console.log(this.cart);
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
        this.sameAddress = !this.sameAddress;
    }

    /**
     * Adjusting Total Amount
     */
    private adjustAmount(): any {

        //console.log("call")
        this.totalAmount = 0;
        this.GSTAmount = 0;
        for (const item of this.cart) {

            this.totalAmount += item.total_amount;
            this.GSTAmount += this.getGstAmt2Digits(item.total_amount);
            /*  obj.total_amount = 0;
            for (const item of obj.Items) {
                 let price = (item.price * item.quantity);
                 obj.total_amount += price;
                 console.log(price,this.getGstAmt2Digits(price))
                 this.GSTAmount += this.getGstAmt2Digits(price);
             }*/

            // this.totalAmount += obj.total_amount;


        }

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
                    const data = {
                        id: filteredItem.id,
                        title: filteredItem.title,
                        quantity: filteredItem.quantity,
                        price: filteredItem.price
                    };
                    result.push(data);
                }
            }
        }
        return result;
    }

    getSubItems() {
        const returnData = [];
        for (const items of this.cart) {
            const Newinsert = items;
            let InsertTitle = '';
            for (const item of items.Items) {
                InsertTitle += item.title + ',';
            }
            //Newinsert.titles = InsertTitle;
        }

    }

    productTotalPrice(item: Items): any {
        let price = ((item.quantity) * item.price);
        let gst = this.getGstAmt(price);
        return this.currencyPipe.transform(price + gst);
    }

    roundoff(amount: any): any {
        return Math.round((amount + Number.EPSILON) * 100) / 100;
        //return amount.toFixed(2);
    }

    productTotalPriceAnother(amount: any): any {

        let price = amount;
        let gst = this.getGstAmt(amount);
        return this.currencyPipe.transform(this.roundoff(price + gst));


        //return this.currencyPipe.transform(price + gst);
    }

    getItemslistTitle(items: any): any {


        let itemsnew = this.filteringSubItems(items);
        let title = '';
        for (const ItemData of itemsnew) {
            title += '<span>' + ItemData.title + '</span>';
        }
        return title;
        ;
    }

    getItemslistQuanity(items: any): any {
        let quantity = '';
        let itemsnew = this.filteringSubItems(items);
        for (const ItemData of itemsnew) {
            quantity += '<span>' + ItemData.quantity + '</span>';
        }
        return quantity;
        ;
    }

    getGstAmt(price: number) {
        //return parseFloat((((price * this.settings.gst) / 100)).toFixed(2));

        //return ( Math.round(( ( (price * this.settings.gst) / 100) + Number.EPSILON ) * 100) / 100  );
        return this.roundoff(( (price * this.settings.gst) / 100));


    }

    getGstAmt2Digits(price: number) {
        //return ( Math.round((( (price * this.settings.gst) / 100) + Number.EPSILON) * 100) / 100  );
        return this.roundoff((price * this.settings.gst) / 100);
    }

    goToCheckout() {
        this.route.navigate(['/checkout']);
    }

    getSettings(cb: any) {
        this.posService.getGstSetting().subscribe(setting => {
            this.settings = setting.data.settings;
            this.pay_due_now = 100 - setting.data.settings.pay_installation;
            this.pay_on_installation = 100 - this.pay_due_now;
            this.payment_due_now_tooltip = setting.data.settings.payment_due_now;
            this.payment_due_on_installation_tooltip = setting.data.settings.payment_due_on_installation;
            cb();
        });
    }

    getShippAdress() {
        const getUser = JSON.parse(this.userData);
        this.userId = getUser.id;
        let params = {'user_id': this.userId};
        this.posService.getShippingAddress(params).subscribe(
            (res: any) => {
                if (res.data != null && res.data != 'need User Id') {
                    //console.log('5555', res.data);
                    sessionStorage.setItem('shippingDetails', JSON.stringify(res.data));

                    this.sameAddress = (res.data.same_add);

                    this.shipping_address = res.data.shipping_address == null ? '' : res.data.shipping_address;
                    sessionStorage.setItem('shipping_address', this.shipping_address);
                    //if (res.data.shipping_address_2 != null) {

                    this.shipping_address_2 = res.data.shipping_address_2 == null ? '' : res.data.shipping_address_2;
                    sessionStorage.setItem('shipping_address_2', this.shipping_address_2);
                    //}
                    //if (res.data.shipping_first_name != null) {
                    this.shipping_first_name = res.data.shipping_first_name == null ? '' : res.data.shipping_first_name;
                    sessionStorage.setItem('shipping_first_name', this.shipping_first_name);
                    //}
                    //if (res.data.shipping_last_name != null) {
                    this.shipping_last_name = res.data.shipping_last_name == null ? '' : res.data.shipping_last_name;
                    sessionStorage.setItem('shipping_last_name', this.shipping_last_name);
                    //}
                    //if (res.data.shipping_state != null) {
                    this.shipping_state = res.data.shipping_state == null ? '' : res.data.shipping_state;
                    sessionStorage.setItem('shipping_state', this.shipping_state);
                    //}
                    //if (res.data.shipping_zip != null) {
                    this.shipping_zip = res.data.shipping_zip == null ? '' : res.data.shipping_zip;
                    sessionStorage.setItem('shipping_zip', this.shipping_zip);
                    //}
                    //if (res.data.shipping_city != null) {
                    this.shipping_city = res.data.shipping_city == null ? '' : res.data.shipping_city;
                    sessionStorage.setItem('shipping_city', this.shipping_city);
                    //}
                    //if (res.data.billing_zip != null) {
                    this.billing_zip = res.data.billing_zip == null ? '' : res.data.billing_zip;
                    sessionStorage.setItem('billing_zip', this.billing_zip);
                    //}
                    //if (res.data.shipping_email != null) {
                    this.shipping_email = res.data.shipping_email == null ? '' : res.data.shipping_email;
                    sessionStorage.setItem('shipping_email', this.shipping_email);
                    //}
                    //if (res.data.shipping_phone != null) {
                    this.shipping_phone = res.data.shipping_phone == null ? '' : res.data.shipping_phone;
                    sessionStorage.setItem('shipping_phone', this.shipping_phone);
                    //}
                    //if (res.data.billing_address != null) {
                    this.billing_address = res.data.billing_address == null ? '' : res.data.billing_address;
                    sessionStorage.setItem('billing_address', this.billing_address);
                    //}
                    //if (res.data.billing_address_2 != null) {
                    this.billing_address_2 = res.data.billing_address_2 == null ? '' : res.data.billing_address_2;
                    sessionStorage.setItem('billing_address_2', this.billing_address_2);
                    //}
                    //if (res.data.billing_first_name != null) {
                    this.billing_first_name = res.data.billing_first_name == null ? '' : res.data.billing_first_name;
                    sessionStorage.setItem('billing_first_name', this.billing_first_name);
                    //}
                    //if (res.data.billing_last_name != null) {
                    this.billing_last_name = res.data.billing_last_name == null ? '' : res.data.billing_last_name;
                    sessionStorage.setItem('billing_last_name', this.billing_last_name);
                    //}
                    //if (res.data.billing_state != null) {
                    this.billing_state = res.data.billing_state == null ? '' : res.data.billing_state;
                    sessionStorage.setItem('billing_state', this.billing_state);
                    //}
                    //if (res.data.billing_city != null) {
                    this.billing_city = res.data.billing_city == null ? '' : res.data.billing_city;
                    sessionStorage.setItem('billing_city', this.billing_city);
                    //}
                    //if (res.data.billing_email != null) {
                    this.billing_email = res.data.billing_email == null ? '' : res.data.billing_email;
                    sessionStorage.setItem('billing_email', this.billing_email);
                    //}
                    //if (res.data.billing_phone != null) {
                    this.billing_phone = res.data.billing_phone == null ? '' : res.data.billing_phone;
                    sessionStorage.setItem('billing_phone', this.billing_phone);
                    //}
                    //if (res.data.shipping_country != null) {
                    this.shipping_country = res.data.shipping_country == null ? '' : res.data.shipping_country;
                    sessionStorage.setItem('shipping_country', this.shipping_country);
                    //}
                    //if (res.data.billing_country != null) {
                    this.billing_country = res.data.billing_country == null ? '' : res.data.billing_country;
                    sessionStorage.setItem('billing_country', this.billing_country);
                    //}
                } else {
                    sessionStorage.setItem('shippingDetails', 'null');
                }
            }, error => {
                sessionStorage.setItem('shippingDetails', 'null');
            }
        );
    }

    CheckUserCoupon(coupon: any) {
        if (coupon == '') {
            this.ErrMessage = 'Coupon cant be empty';
        } else if (this.isCouponApplied == true) {
            this.ErrMessage = 'you have already applied the coupon';
            this.successMessage = '';
        } else {
            //user_id=62&coupon_code=sweeze
            const params = {user_id: this.userId, coupon_code: coupon};
            this.posService.getCoupnDetails(params).subscribe(
                (res: any) => {
                    if (res.code == 200) {
                        this.successMessage = res.data;
                        this.ErrMessage = '';
                        this.isCouponApplied = true;
                        sessionStorage.setItem('coupon_code', coupon);

                        this.ApplyCoupon(res.discount, res.type);
                        // this.
                    } else {
                        this.ErrMessage = res.data;
                        this.successMessage = '';
                    }
                });
        }
    }

    ApplyCoupon(disc: number, type: any) {
        this.isApplyButton = false;
        this.isRemoveButton = true;
        if (type == 2) {
            this.totalDiscount = disc;
            this.totalAmountAfter = this.totalAmount - disc;
        } else {
            this.totalDiscount = disc * this.totalAmount / 100;
            this.totalAmountAfter = (100 - disc) * this.totalAmount / 100;
            this.GSTDiscount = disc * this.GSTAmount / 100;
            sessionStorage.setItem('gst_discount', this.roundoff(this.GSTDiscount));
            this.GSTAmountAfter = (100 - disc) * this.GSTAmount / 100;
        }
        const discount = this.roundoff((this.GSTDiscount + this.totalDiscount));
        sessionStorage.setItem('complete_discount', discount);
        //
    }


    removeCoupon(coupon: any) {
        sessionStorage.setItem('complete_discount', '0');
        sessionStorage.setItem('coupon_code', '');
        this.totalAmountAfter = 0;
        this.GSTAmountAfter = 0;
        this.totalDiscount = 0;
        this.GSTDiscount = 0;
        this.isCouponApplied = false;
        this.isApplyButton = true;
        this.isRemoveButton = false;
        this.successMessage = 'Coupon Removed';
        this.couponName.nativeElement.value = '';
    }
}
