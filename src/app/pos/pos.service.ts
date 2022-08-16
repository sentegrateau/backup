import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
// custom implementation
import {HttpClient} from '@angular/common/http';
import {IPackage} from '../model/package.interface';
import {environment} from '../../environments/environment';
import {map, tap} from 'rxjs/operators';
import {User} from '../model/user.model';
import {StripeService} from 'ngx-stripe';

@Injectable({
    providedIn: 'root'
})
export class PosService {

    APP_URL: any = environment.apiUrl;
    shipingDetails = this.APP_URL + '/getShippingAddress';
    //http://192.168.2.22/sentegrate-server/public/api/getCouponDetails?user_id=62&coupon_code=sweeze
    CoupnDetails = this.APP_URL + '/getCouponDetails';

    constructor(private http: HttpClient, private stripeService: StripeService) {
    }

    // custom implementation
    // 1- Getting All the packages from the API
    getAllPackages(ifPkgNoRoom = false): Observable<IPackage[]> {
        return this.http.get<IPackage[]>(this.APP_URL + '/package?status=1' + (ifPkgNoRoom ? '&ifPkgNoRoom=true' : ''));
    }

    getShippingAddress(params: any): Observable<any> {
        return this.http.get(this.shipingDetails, {'params': params});
    }

    getCoupnDetails(params: any): Observable<any> {
        return this.http.get(this.CoupnDetails, {'params': params});
    }

    /**
     *  getUserDetails(params: any): Observable<any>{
    return this.http.get(this.getUserbyId,{'headers':headers,'params':params});
  }
     */
    getPackageData(id: number): Observable<any> {
        return this.http.get<any>(this.APP_URL + '/package-rooms/' + id);
    }

    // getAllDevicesFromRoom(id: number): Observable<IDevice> {
    //   return this.http.get<IDevice>(this.APP_URL + '/room-devices/' + id);
    // }
    // save an order
    saveOrder(data: any): Observable<any> {
        return this.http.post(this.APP_URL + '/order', data);
    }

    // get all orders
    getOrders(data?: any): Observable<any> {
        return this.http.get(this.APP_URL + '/order', {params: data});
    }

    // getting all data of the selected order
    getOrderData(id: number): Observable<any> {
        return this.http.get(this.APP_URL + '/order-items/' + id);
    }

    // send quotation email
    sendEmail(data: any): Observable<any> {
        return this.http.post(`${this.APP_URL}/send-email`, data);
    }

    /**
     * Get user or create if not exists
     */
    // tslint:disable-next-line:max-line-length variable-name
    findOrCreateUser(name: string, role: string, email?: string, role2?: string, abn?: string, company?: string, kit_name?: string): Observable<User> {
        return this.http.post(`${this.APP_URL}/find-or-create-user`, {
            name,
            email,
            role,
            role2,
            abn,
            company,
            kit_name
        }).pipe(
            map((res: any) => {
                if (role === 'user') {

                    sessionStorage.setItem('user', JSON.stringify(res.data));
                }
                return res.data;
            })
        );
    }

    /**
     * Save Draft
     */
    saveDraft(data: any): Observable<any> {
        return this.http.post(`${this.APP_URL}/draft`, data);
    }

    /**
     * Get draft by user id
     */
    getDrafts(data?: any): Observable<any> {
        return this.http.get(`${this.APP_URL}/draft`, {params: data}).pipe(
            map((res: any) => res.data.map((x: any) => {
                return {
                    ...x,
                    selected: false
                };
            }))
        );
    }

    /**
     * Get Draft Items
     */
    getDraftItems(id: number): Observable<any> {
        return this.http.get(`${this.APP_URL}/draft-items/${id}`);
    }

    /**
     * Get Settings
     */
    getGstSetting(): Observable<any> {
        return this.http.get(`${this.APP_URL}/getGstSetting`);
    }

    /**
     *  Get Single Draft/Quotation
     */
    getSingleDraft(id: number): Observable<any> {
        return this.http.get(`${this.APP_URL}/draft/${id}`).pipe(
            map((res: any) => {
                return res.data;
            })
        );
    }

    /**
     * Updating the Draft
     */
    updateDraft(id: number, data: any): Observable<string> {
        return this.http.put(`${this.APP_URL}/draft/${id}`, data).pipe(
            map((res: any) => {
                return res.message;
            })
        );
    }

    /**
     * Make Quotation
     */
    saveQuotation(data: any): Observable<any> {
        return this.http.post(`${this.APP_URL}/save-quotation`, data);
    }

    /**
     * Delete Draft or Quotation
     */
    deleteDraftQuotation(id: number): Observable<any> {
        return this.http.delete(`${this.APP_URL}/draft/${id}`).pipe(
            map((res: any) => {
                return res.message;
            })
        );
    }

    /**
     * Checkout Session Creation
     */
    checkout(data?: any,partner?: string): Observable<any> {
         console.log(partner);
        return this.http.post(`${this.APP_URL}/order/`+ partner ,data).pipe(
            tap((res: any) => {
                sessionStorage.removeItem('quotation');
                sessionStorage.removeItem('cart_items');
            }),
            map((res: any) => {
                return res;
            })
            /* switchMap((session: any) => {
               return this.stripeService.redirectToCheckout({sessionId: session.id});
             })*/
        );
    }

    updateProfile(data?: any): Observable<any> {
        return this.http.post(`${this.APP_URL}/order/update-profile`, data).pipe(
            tap((res: any) => {
            }),
            map((res: any) => {
                sessionStorage.setItem('user', JSON.stringify(res.profile));
                return res;
            })
        );
    }

    getUserData(data?: any): Observable<any> {
        return this.http.post(`${this.APP_URL}/user/get-user`, data).pipe(
            tap((res: any) => {
            }),
            map((res: any) => {
                sessionStorage.setItem('user', JSON.stringify(res.data));
                return res;
            })
        );
    }

    updateShipping(data?: any): Observable<any> {
        return this.http.post(`${this.APP_URL}/order/update-shipping`, data).pipe(
            tap((res: any) => {
            }),
            map((res: any) => {
                return res;
            })
        );
    }

    checkoutPaypal(data?: any): Observable<any> {
        return this.http.post(`${this.APP_URL}/order`, data).pipe(
            tap((res: any) => {
                // sessionStorage.removeItem('quotation');
                // sessionStorage.removeItem('cart_items');
            }),
            map((res: any) => {
                return res;
            })
        );
    }

  checkoutDeleteOrder(data?: any): Observable<any> {
    return this.http.post(`${this.APP_URL}/delete-order`, data).pipe(
      tap((res: any) => {

      }),
      map((res: any) => {
        return res;
      })
    );
  }

  checkoutPaypalSuccess(data?: any,partner?: string): Observable<any> {
    return this.http.post(`${this.APP_URL}/order-paypal-success/`+partner, data).pipe(
      tap((res: any) => {

      }),
      map((res: any) => {
        return res;
      })
    );
  }




    getInvalidTokenString(): Observable<any> {
        return this.http.get(`${this.APP_URL}/get-invalid-token-string`);
    }
}
