import {Component, ElementRef, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {PosService} from './pos.service';
import {Items} from '../model/item.model';
import {ICart, Items as CItems} from '../model/cart.model';
import {IPackage} from '../model/package.interface';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {forkJoin, Subscription} from 'rxjs';
import {IDevice} from '../model/device.interface';
import {NgxSpinnerService} from 'ngx-spinner';
import {ToastrService} from 'ngx-toastr';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {ActivatedRoute, Router} from '@angular/router';
import jwt_decode from 'jwt-decode';
import {DecodedToken, FilteredItem, Room} from '../model/interfaces';
import {User} from '../model/user.model';
import {PackageData} from '../model/package-data.model';
import {DraftManagement} from './utility/draft-management';
import {CartManagement} from './utility/cart-management';
import {DraftCategory, DraftType, IDraft, IDrafts} from '../model/draft.model';
import Swal from 'sweetalert2';
import {indexOf, isEqual} from 'lodash';
import {IDraftItem} from '../model/draft-item.model';

@Component({
  selector: 'app-pos',
  templateUrl: './pos.component.html',
  styleUrls: ['./pos.component.scss']
})
export class PosComponent implements OnInit, OnDestroy {
  public isCollapsed = false;
  packages: any[] = [];
  rooms: any[] = [];
  devices: any[] = [];
  items: Items[] = [];
  item: any = {
    id: 0,
    device_id: '',
    name: '',
    quantity: 1,
    price: 0,
    roomId: 0,
    roomTitle: '',
    min_qty: 1,
    max_qty: 0,
    description: '',
    model: '',
    brand: ''
  };
  cart: ICart[] = [];
  // cart copy for draft and quotation
  private cartCopyOfDraftOrQuotation: ICart[] = [];
  private draftOrQuotationEdited: boolean;
  totalAmount = 0;
  savingForm: FormGroup;
  submitted = false;
  orders: any[];
  adminOrders: any[];
  savingSuccessMessage: string;
  savingErrorMessage: string;
  packageData: PackageData[];
  selectedTab = 0;
  saveAsForm: FormGroup;
  saveAsFormSubmitted = false;
  // if redirected from checkout page
  private fromCheckout = false;
  /**
   * Drafts
   */
  public drafts: IDrafts[] = [];
  public standardDrafts: IDrafts[] = [];
  public userDrafts: IDrafts[] = [];
  public userQuotations: IDrafts[] = [];
  public singleDraft: IDraft = {} as IDraft;
  public draftItems: IDraftItem[] = [];
  public showHideItems = {hideSave: true, hideUpdate: false};
  public isMobile = false;

  /**
   * Public
   */
  public defaultSelection = true;
  /**
   * Private States
   */
  private token: string | null;
  private partner: User;
  private user: User;
  private subscription: Subscription[] = [];

  @ViewChild('selectQuote') selectQuote: ElementRef;

  kitId: number = 0;

  constructor(
    private posService: PosService,
    private spinnerService: NgxSpinnerService,
    private toaster: ToastrService,
    private modal: NgbModal,
    private router: ActivatedRoute,
    private route: Router
  ) {
      this.isMobile = this.detectMob();
      if(this.isMobile){
          this.isCollapsed = true;
      }

    this.savingForm = this.returnSavingForm();
    this.router.queryParams.subscribe(
      (param) => {
        if (param.token) {
          this.token = param.token;
          sessionStorage.setItem('token', param.token);
          const decodedToken: DecodedToken = jwt_decode(param.token);
       //   console.log(decodedToken.partner_id);
          if (decodedToken.role2 === 'owner') {
            this.route.navigate(['invalid-token']);
          }
        } else {
          this.route.navigate(['invalid-token']);
        }
        const qt = sessionStorage.getItem('quotation');
        if (param.fromCheckout || qt != null) {

          if (qt != null) {
            this.fromCheckout = true;
            this.singleDraft = JSON.parse(qt);
          }
        }
      }
    );


  }

  ngOnInit(): void { 
    this.getAllPackages();
    this.findOrCreateUser();
    this.saveAsForm = new FormGroup({
      title: new FormControl('', [Validators.required])
    });

    const cartItems = sessionStorage.getItem('cart_items');
    if (cartItems != null) {
      this.cart = JSON.parse(cartItems);
      this.cartCopyOfDraftOrQuotation = JSON.parse(JSON.stringify(this.cart));
      this.adjustAmount();
    }
    sessionStorage.setItem('changesSomeone', '');


  }

  ngOnDestroy(): void {
    this.subscription.forEach(sb => {
      sb.unsubscribe();
    });
  }

    detectMob() {
        const toMatch = [
            /Android/i,
            /webOS/i,
            /iPhone/i,
            /iPad/i,
            /iPod/i,
            /BlackBerry/i,
            /Windows Phone/i
        ];
        return toMatch.some((toMatchItem) => {
            return navigator.userAgent.match(toMatchItem);
        });
    }

  /**
   *  create or find user based on token result
   */
  findOrCreateUser(): void {
      try {
          if (!this.token) {
              return;
          }
          const decodedToken: DecodedToken = jwt_decode(this.token);
          const partner = this.posService.findOrCreateUser(decodedToken.partner_id, 'partner');
          // tslint:disable-next-line:max-line-length
          const user = this.posService.findOrCreateUser(decodedToken.user_name, 'user', decodedToken.user_email, decodedToken.role2, decodedToken.abn, decodedToken.company, decodedToken.kit_name);
          // @ts-ignore
          const sb = forkJoin([partner, user]).subscribe(
              (result) => {
                  this.partner = result[0];
                  this.user = result[1];
                  if (this.user) {
                      // @ts-ignore
                      if (result[1].kitId) {
                          // @ts-ignore
                          this.kitId = result[1].kitId;
                          this.singleDraft.id = this.kitId;
                          // sessionStorage.setItem('user')
                      }
                      this.getDrafts();
                  }
              },
              error => {
                  console.log(error);
                  this.route.navigate(['invalid-token']);
                  this.spinnerService.hide();
                  sessionStorage.setItem('user', '');
              }
          );
          this.subscription.push(sb);
      }catch (error){
          console.log(error);
          this.route.navigate(['invalid-token']);
          this.spinnerService.hide();
          sessionStorage.setItem('user', '');
      }
  }

  /**
   * Get Drafts of currently loggedIn user with admin based drafts
   *
   */
  getDrafts(): any {
    const standardDrafts = this.posService.getDrafts({type: 'admin'});
    const userDrafts = this.posService.getDrafts({user_id: this.user.id, category: 'draft'});
    const userQuotations = this.posService.getDrafts({user_id: this.user.id, category: 'quotation', isValid: true});
    const sb = forkJoin([standardDrafts, userDrafts, userQuotations]).subscribe(
      (result: any) => {
        this.drafts = [...result[0], ...result[1], ...result[2]];
        if (sessionStorage.getItem('cart_items') != null && sessionStorage.getItem('quotation') != null) {
          this.singleDraft = JSON.parse(sessionStorage.getItem('quotation') as string);
          DraftManagement.findAndSelectDraft(this.drafts, this.singleDraft);

          if (this.singleDraft) {
            if (this.singleDraft.category === DraftCategory.DRAFT && this.singleDraft.type === DraftType.USER) {
              this.showHideItems = {
                hideSave: false,
                hideUpdate: true
              };
            }
            if (this.singleDraft.category === DraftCategory.DRAFT && this.singleDraft.type === DraftType.ADMIN) {
              this.showHideItems = {
                hideSave: true,
                hideUpdate: false
              };
            }
            if (this.singleDraft.category === DraftCategory.QUOTATION && this.singleDraft.type === DraftType.USER) {
              this.showHideItems = {
                hideSave: true,
                hideUpdate: false
              };
            }
            const cartItems = sessionStorage.getItem('cart_items');
            if (cartItems != null) {
              this.cart = JSON.parse(cartItems);
              this.cartCopyOfDraftOrQuotation = JSON.parse(JSON.stringify(this.cart));
              this.adjustAmount();
            }
          }
        } else {
          if (this.singleDraft && this.singleDraft.id) {
            DraftManagement.findAndSelectDraft(this.drafts, this.singleDraft);
            setTimeout(() => {
              this.onChangeOption(this.selectQuote.nativeElement);
            }, 100);
          }

        }
      }, error => {
        this.toaster.error('Error in getting Saved kit');
        console.log(error);
      }
    );
    this.subscription.push(sb);
  }

  // change tab
  changeTab(value: number): void {
    this.selectedTab = value;
  }


  // form initializing
  returnSavingForm(): FormGroup {
    return new FormGroup({
      title: new FormControl('', Validators.required)
    });
  }

  // tslint:disable-next-line:typedef
  get f() {
    return this.savingForm.controls;
  }

  getAllPackages(): void {
    this.spinnerService.show();
    const sb = this.posService.getAllPackages(true).subscribe(
      (res: any) => {
        this.packages = res.data;

        if (this.packages.length) {
          this.packages[0].active = true;
          this.getPackageData(this.packages[0].id);
        }
        this.spinnerService.hide();
      }, error => {
        this.spinnerService.hide();
        console.log(error.message);
      }
    );
    this.subscription.push(sb);
  }

  // get package data with package id, that contains rooms and devices and min, max qty
  getPackageData(packageId: number): void {
    this.spinnerService.show();
    const sb = this.posService.getPackageData(packageId).subscribe(
      (res: any) => {
        this.packageData = res.data;
        this.rooms = res.data.map((e: any) => {
          return {
            id: e.id,
            partner_id: e.id,
            name: e.name,
            description: e.description,
            status: e.status,
            created_at: e.created_at,
            updated_at: e.updated_at,
            active: false
          };
        });
        if (this.rooms.length > 0) {
          const room = this.rooms[0];
          room.active = true;
          this.getRoomDevices(room.id, room);

        }
        this.spinnerService.hide();
      }, error => {
        this.spinnerService.hide();
        console.log(error);
      }
    );
    this.subscription.push(sb);
  }

  setItem(deviceInfo: IDevice, quantity: number, room: any): void {
    const item = this.item;
    item.id = deviceInfo.id;
    item.name = deviceInfo.name;
    item.quantity = quantity === 0 ? deviceInfo.min_qty : quantity;
    item.price = deviceInfo.price;
    item.description = deviceInfo.description;
    item.roomId = room.id;
    item.roomTitle = room.name;
    item.min_qty = deviceInfo.min_qty;
    item.max_qty = deviceInfo.max_qty;
    item.model = deviceInfo.model;
    item.brand = deviceInfo.brand;
    item.device_id = deviceInfo.device_id;
  }

  // set room devices
  setRoomDevices(data: PackageData): void {
    this.devices = data.devices.map((e: any) => {
        return {
          id: e.id,
          partner_id: e.partner_id,
          name: e.name,
          description: e.description,
          brand: e.brand,
          model: e.model,
          device_id: e.device_id,
          active: e.active,
          price: e.price,
          discount: e.discount,
          stock_status: e.stock_status,
          supplier: e.supplier,
          manual_url: e.manual_url,
          image: e.image,
          imageUrl: e.imageUrl,
          status: e.status,
          created_at: e.created_at,
          updated_at: e.updated_at,
          selected: false,
          quantity: 0,
          min_qty: e.min_qty,
          max_qty: e.max_qty
        };
      }
    );
  }

  // get room devices and set 1st device
  getRoomDevices(roomId: number, roomInfo: any): any {
    const room = this.packageData.find(x => x.id === roomId);
    if (room) {
      this.setRoomDevices(room);
      if (this.devices.length > 0) {
        const device = this.devices[0];
        device.selected = true;
        const quantity = CartManagement.getQuantityOfItemExistInCart(this.packages, this.rooms, this.devices, this.cart);
        this.setItem(device, quantity, roomInfo);
      }
    }
  }

  // get min max quantity
  onPackageClick(event: any): void {
    this.item = {};
    this.rooms = [];
    this.devices = [];
    const findActive = this.packages.find(x => x.active === true);
    if (findActive) {
      const activeIndex = this.packages.indexOf(findActive);
      this.packages[activeIndex].active = false;
    }
    const index = this.packages.indexOf(event);
    this.packages[index].active = true;
    this.getPackageData(event.id);
  }

  onRoomChange(event: any): void {
    this.item = {};
    this.devices = [];
    const findActiveRoom = this.rooms.find((el) => {
      return el.active === true;
    });
    if (findActiveRoom) {
      const activeIndex = this.rooms.indexOf(findActiveRoom);
      this.rooms[activeIndex].active = false;
    }
    const index = this.rooms.indexOf(event);
    this.rooms[index].active = true;
    this.getRoomDevices(event.id, event);
  }

  handleChange(event: any): void {
    const findActive = this.devices.find((el) => {
      return el.selected === true;
    });
    if (findActive) {
      const activeIndex = this.devices.indexOf(findActive);
      this.devices[activeIndex].selected = false;
    }
    const index = this.devices.indexOf(event);
    this.devices[index].selected = true;
    const activeRoom = this.rooms.find(el => el.active === true);
    const qty = CartManagement.getQuantityOfItemExistInCart(this.packages, this.rooms, this.devices, this.cart);
    this.setItem(event, qty, activeRoom);

  }

  adjustQuantity(actionType: string): void {
    if (this.item === undefined) {
      return;
    }
    if (actionType === '+') {
      if (this.item.quantity === this.item.max_qty) {
        return;
      }
      this.item.quantity += 1;
    } else {
      if (this.item.quantity === 0) {
        return;
      }
      if (this.item.quantity > this.item.min_qty) {
        this.item.quantity -= 1;
      }
    }
  }

  adjustAmount(): any {
    this.totalAmount = 0;
    for (const obj of this.cart) {
      obj.total_amount = 0;

      for (const item of obj.Items) {
        obj.total_amount += (item.price * item.quantity);
      }

      this.totalAmount += obj.total_amount;

    }
  }

  addToCart(): void {
    if (this.item === undefined || this.item?.quantity < 0) {
      return;
    }

    if (!DraftManagement.isDraftEmpty(this.singleDraft) && this.singleDraft.category === DraftCategory.QUOTATION) {
      this.cart = [];
      this.setDraftItemsInCart(DraftManagement.getActivePackageDraftItems(this.draftItems), DraftCategory.DRAFT);
      this.singleDraft = {} as IDraft;
      this.draftItems = [];
      this.defaultSelection = true;
    }
    const activePackage = this.packages.find(x => x.active === true);
    const packageExistsInCart = this.cart.find(x => x.package_id === activePackage.id);
    if (packageExistsInCart) {
      const indexOfExistedPackage = this.cart.findIndex(x => x.package_id === activePackage.id);
      const activeRoom = this.rooms.find(x => x.active === true);
      // const roomExistsInCart = this.cart[indexOfExistedPackage].Items.find(x => x.roomId === activeRoom.id);
      const activeDevice = this.devices.find(x => x.selected === true);
      // const deviceExistsInCart = this.cart[indexOfExistedPackage].Items.find(x => x.id === activeDevice.id);
      const roomAndDeviceExistsInCart = this.cart[indexOfExistedPackage].Items.find(x => {
        return (x.roomId === activeRoom.id && x.id === activeDevice.id);
      });
      if (roomAndDeviceExistsInCart) {
        const index = this.cart[indexOfExistedPackage].Items.indexOf(roomAndDeviceExistsInCart);
        if (this.item.quantity === 0) {
          this.cart[indexOfExistedPackage].Items.splice(index, 1);
          if (this.cart[indexOfExistedPackage].Items.length > 0) {
            this.adjustAmount();
            return;
          } else {
            this.cart.splice(indexOfExistedPackage, 1);
            this.adjustAmount();
            return;
          }
        }
        if (this.item.quantity < this.item.min_qty) {
          this.toaster.warning(`You can not add ${this.item.quantity}, minimum quantity is ${this.item.min_qty}`);
          return;
        }
        this.cart[indexOfExistedPackage].Items[index].quantity = this.item.quantity;
      } else {
        if (this.item.quantity === 0) {
          return;
        }
        if (this.item.quantity < this.item.min_qty) {
          this.toaster.warning(`You can not add ${this.item.quantity}, minimum quantity is ${this.item.min_qty}`);
          return;
        }
        const selectedDevice = this.devices.find(x => x.selected === true);
        const device = {
          id: selectedDevice.id,
          title: selectedDevice.name,
          price: selectedDevice.price,
          quantity: this.item.quantity,
          roomId: activeRoom.id,
          roomTitle: activeRoom.name
        };
        this.cart[indexOfExistedPackage].Items.push(device);
      }
    } else {
      this.insertIntoCart();
    }
    this.adjustAmount();
    sessionStorage.setItem('changesSomeone', 'true');
  }

  insertIntoCart(): void {
    const selectedPackage = this.packages.find((x: IPackage) => x.active);
    const activeRoom = this.rooms.find(x => x.active === true);
    if (this.item.quantity === 0) {
      return;
    }
    if (this.item.quantity < this.item.min_qty) {
      this.toaster.warning(`You can not add ${this.item.quantity}, minimum quantity is ${this.item.min_qty}`);
      return;
    }
    const device = {
      id: this.item.id,
      roomId: activeRoom?.id,
      roomTitle: activeRoom?.name,
      title: this.item?.name,
      quantity: this.item?.quantity,
      price: this.item?.price
    };
    if (selectedPackage) {
      this.cart.push({
        total_amount: 0,
        package_id: selectedPackage.id,
        package_title: selectedPackage.name,
        Items: [device]
      }); 

/*Sorting for cart Items code*/
/*this.cart.sort(function (a, b) {
    return a.package_id - b.package_id;
});
   console.log(this.cart);*/

    }
  }





  /**
   * Saving Draft
   */
  saveDraft(): void {
    this.savingSuccessMessage = '';
    this.savingErrorMessage = '';
    this.submitted = true;
    if (this.savingForm.invalid) {
      return;
    }
    if (this.cart.length && this.user && this.partner) {
      this.spinnerService.show();
      const data = DraftManagement.draftData(this.cart, this.user, this.partner, this.f.title.value);
      const sb = this.posService.saveDraft(data).subscribe(
        (res: any) => {
          this.submitted = false;
          this.savingForm.reset();
          this.spinnerService.hide();
          // this.resetSelection();
          this.toaster.success(res.message);
          this.drafts.push({...res.data, selected: true, category: 'draft'});
          this.singleDraft = {...res.data, category: 'draft'};
          // this.savingForm.setValue({title: this.singleDraft.title});
          this.savingForm.setValue({title: ''});
          sessionStorage.setItem('quotation', JSON.stringify(this.singleDraft));
          this.showHideItems = {
            hideSave: false,
            hideUpdate: true
          };
          this.cartCopyOfDraftOrQuotation = JSON.parse(JSON.stringify(this.cart));
          sessionStorage.setItem('changesSomeone', '');
        }, error => {
          this.submitted = false;
          this.spinnerService.hide();
          if (error.error.data && error.error.data.title) {
            this.toaster.error(error.error.data.title);
          } else {
            this.toaster.error(error.error.message);
          }
          console.log(error);

        }
      );

      this.subscription.push(sb);
    } else {
      this.toaster.error('Can not save an empty kit');
      this.submitted = false;
      this.savingForm.reset();
    }

  }

  onChangeOptionWork(event: any) {
    if (event.value === null) {
      return;
    }
    if (this.singleDraft) {
      if (this.singleDraft.category === DraftCategory.DRAFT && this.draftOrQuotationEdited) {
        this.toaster.warning('Please Save the changes');
      }
    }
    sessionStorage.setItem('draft_id', event.value);
    this.defaultSelection = false;
    this.submitted = false;
    this.spinnerService.show();
    this.cart = [];
    this.item.quantity = 1;
    const getDraft = this.posService.getSingleDraft(event.value);
    const getDraftItems = this.posService.getDraftItems(event.value);
    const sb = forkJoin([getDraft, getDraftItems]).subscribe(
      (res: any) => {

        this.singleDraft = res[0];
        sessionStorage.setItem('quotation', JSON.stringify(this.singleDraft));
        sessionStorage.setItem('changesSomeone', '');
        const data = res[1].data;
        if (this.singleDraft) {
          if (this.singleDraft.category === DraftCategory.DRAFT && this.singleDraft.type === DraftType.USER) {
            this.showHideItems = {
              hideSave: false,
              hideUpdate: true
            };
          }
          if (this.singleDraft.category === DraftCategory.DRAFT && this.singleDraft.type === DraftType.ADMIN) {
            this.showHideItems = {
              hideSave: true,
              hideUpdate: false
            };
          }
          if (this.singleDraft.category === DraftCategory.QUOTATION && this.singleDraft.type === DraftType.USER) {
            this.showHideItems = {
              hideSave: true,
              hideUpdate: false
            };
          }
          this.setDraftItemsInCart(data, this.singleDraft.category);
          if (this.cart.length) {
            this.setPackageRoomAndDeviceFromDropDownList();
          }
          this.cartCopyOfDraftOrQuotation = JSON.parse(JSON.stringify(this.cart));
          this.draftItems = JSON.parse(JSON.stringify(data));
        }
        this.spinnerService.hide();
      },
      error => {
        this.spinnerService.hide();
        console.log(error);
      }
    );
    this.subscription.push(sb);
  }


  onChangeOption(event: any): void {
    let changesSomeone = sessionStorage.getItem('changesSomeone');
    if (changesSomeone == 'true') {
      // @ts-ignore
      Swal.fire({
        title: '',
        text: 'There are unsaved changes. Do you want to save the kit ?',
        icon: '',
        showCancelButton: true,
        confirmButtonColor: '#f38022',
        cancelButtonColor: '#f38022',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        customClass: {
          cancelButton: 'cancel-btn-width',
          confirmButton: 'cancel-btn-width',
          content: 'text_siz',

          htmlContainer: 'text_siz'
        }
      }).then((result) => {
        if (result.value) {
          this.singleDraft = JSON.parse(sessionStorage.getItem('quotation') as string);
          if (this.singleDraft && this.singleDraft.id) {
            this.selectQuote.nativeElement.value = this.singleDraft.id;
          } else {
            //working here
            this.singleDraft = {} as IDraft;
            this.selectQuote.nativeElement.value = null;
          }
          return;
        } else {
          this.onChangeOptionWork(event);
        }
      });
    } else {
      this.onChangeOptionWork(event);
    }
  }

  // reset selection
  resetSelection(): void {
    if (!DraftManagement.isDraftEmpty(this.singleDraft)) {
      if (this.singleDraft.category === DraftCategory.DRAFT && !this.cartComparison()) {
        // @ts-ignore
        Swal.fire({
          title: '',
          text: 'Do you want to save changes in draft?',
          icon: '',
          showCancelButton: true,
          confirmButtonColor: '#f38022',
          cancelButtonColor: '#f38022',
          confirmButtonText: 'Update',
          customClass: {
            cancelButton: 'cancel-btn-width',
            confirmButton: 'cancel-btn-width',
            content: 'text_siz',

            htmlContainer: 'text_siz'
          }
        }).then((result) => {
          if (result.value) {
            this.updateDraftUtility();
          } else {
            this.resetUtility();
          }
        });
      } else {
        this.resetUtility();
      }
    } else {
      this.resetUtility();
    }
  }

  // filtering cart subItems
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

  /**
   *  Filtering For Room
   */
  filteringCartItemForRoom(cart: ICart[]): Room[] {
    if (this.cart.length) {
      const devices: CItems[] = [];
      const data = cart;
      for (const devicePackage of data) {
        const device = devicePackage.Items;
        for (const dev of device) {
          devices.push(dev);
        }
      }
      const ids = devices.map(e => e.roomId);
      const uniqueIds = [...new Set(ids)];
      const rooms: Room[] = [];
      for (const id of uniqueIds) {
        const findRoom = devices.find(x => x.roomId === id);
        const roomData = {id: findRoom?.roomId, title: findRoom?.roomTitle, devices: []};
        rooms.push(roomData);
      }
      const deviceIds = devices.map(e => e.id);
      const uniqueDeviceIds = [...new Set(deviceIds)];
      for (const deviceId of uniqueDeviceIds) {
        const filterDevices = devices.filter(x => x.id === deviceId);
        for (const filteredDevice of filterDevices) {
          const findDeviceRoom = rooms.find(x => x.id === filteredDevice.roomId);
          if (findDeviceRoom) {
            const indexOfDeviceRoom = rooms.indexOf(findDeviceRoom);
            const findDeviceInRoom = rooms[indexOfDeviceRoom].devices.find(x => x.id === filteredDevice.id);
            if (findDeviceInRoom) {
              const indexOfDeviceInRoom = rooms[indexOfDeviceRoom].devices.indexOf(findDeviceInRoom);
              rooms[indexOfDeviceRoom].devices[indexOfDeviceInRoom].quantity += filteredDevice.quantity;
            } else {
              // tslint:disable-next-line:max-line-length
              rooms[indexOfDeviceRoom].devices.push({
                id: filteredDevice.id,
                title: filteredDevice.title,
                price: filteredDevice.price,
                quantity: filteredDevice.quantity
              });
            }
          }
        }
      }
      return rooms;
    } else {
      return [];
    }

  }

  /**
   * Setting the package, room and device from the drop list selected by user
   *
   */
  setPackageRoomAndDeviceFromDropDownList(): void {
    const getFirstElementOfCart = this.cart[0];
    const getActivePackage = this.packages.find(x => x.active === true);
    if (getActivePackage) {
      const index = this.packages.indexOf(getActivePackage);
      this.packages[index].active = false;
    }
    const cartPackage = this.packages.find(x => x.id === getFirstElementOfCart.package_id);
    if (cartPackage) {
      const index = this.packages.indexOf(cartPackage);
      this.packages[index].active = true;
    }
    const sb = this.posService.getPackageData(getFirstElementOfCart.package_id).subscribe(
      (res: any) => {
        this.packageData = res.data;
        this.rooms = res.data.map((e: any) => {
          return {
            id: e.id,
            partner_id: e.id,
            name: e.name,
            description: e.description,
            status: e.status,
            created_at: e.created_at,
            updated_at: e.updated_at,
            active: false
          };
        });
        if (this.rooms.length) {
          const findRoom = this.rooms.find(x => x.id === getFirstElementOfCart.Items[0].roomId);
          if (findRoom) {
            const index = this.rooms.indexOf(findRoom);
            this.rooms[index].active = true;
            // setting device
            const getRoom = this.packageData.find(x => x.id === findRoom.id);
            if (getRoom) {
              this.setRoomDevices(getRoom);
              if (this.devices) {
                const findDevice = this.devices.find(x => x.id === getFirstElementOfCart.Items[0].id);
                if (findDevice) {
                  const indexOfDevice = this.devices.indexOf(findDevice);
                  this.devices[indexOfDevice].selected = true;
                  const getDevice = getRoom.devices.find(x => x.id === getFirstElementOfCart.Items[0].id);
                  if (getDevice) {
                    this.setItem(getDevice,
                      CartManagement.getQuantityOfItemExistInCart(this.packages, this.rooms, this.devices, this.cart), getRoom);
                  }
                }
              }
            }
          }
        }
      }
    );
    this.subscription.push(sb);
  }

  /**
   * Update the Draft If the Draft Edited
   */
  updateDraft(): void {
    if (!DraftManagement.isDraftEmpty(this.singleDraft)) {
      if (this.singleDraft.category === DraftCategory.DRAFT && !this.cartComparison()) {
        this.updateDraftUtility();
      } else {
        this.toaster.warning('Saved Kit already upto date');
      }
    }
  }

  updateDraftUtility(): void {
    const data = DraftManagement.draftData(this.cart, this.user, this.partner, this.singleDraft.title);
    const sb = this.posService.updateDraft(this.singleDraft.id, data).subscribe(
      (res) => {
        this.toaster.success(res);
        this.cartCopyOfDraftOrQuotation = JSON.parse(JSON.stringify(this.cart));
        sessionStorage.setItem('changesSomeone', '');
      }, error => {
        this.toaster.error('Error to update kit, please try again');
        console.log(error);
      }
    );
    this.subscription.push(sb);
  }

  /**
   * Convert Draft to Quotation
   */
  saveQuotation(): void {
    // if (!DraftManagement.isDraftEmpty(this.singleDraft)) {
    if (this.singleDraft.category === DraftCategory.QUOTATION) {
      this.toaster.error('Can not make quotation of quotation');
      return;
    }
    // if (this.singleDraft.category === DraftCategory.DRAFT && this.singleDraft.type === DraftType.ADMIN) {
    //   this.toaster.error('Please first save kit');
    //   return;
    // }

    let getDraft = {};
    if (sessionStorage.getItem('quotation')) {
      getDraft = JSON.parse(<string> sessionStorage.getItem('quotation'));
    }

    //this.cartCopyOfDraftOrQuotation = this.cart;
    //if (this.cartComparison()) {
      const draftData = DraftManagement.draftData(this.cart, this.user, this.partner, this.singleDraft.title);
      draftData.total_amount = draftData.draft_items.reduce((total: number, record: any) => {
        return total + (record.price * record.quantity);
      }, 0);
      if (!draftData.title) {
        draftData.title = 'Quotation-' + (new Date()).toISOString().slice(0, 10).replace(/-/g, '');
      }
      if (getDraft) {
        // @ts-ignore
       draftData.draft_id = getDraft['id'];
      }

    if (this.singleDraft && this.cart.length){

      const cartitem = JSON.stringify(this.cart);
      const activePackage = this.packages.find(x => x.active === true);
      const indexOfExistedPackage = this.cart.findIndex(x => x.package_id === activePackage.id);
      const activeDevice = this.devices.find(x => x.selected === true);
      const deviceExistsInCart = this.cart[indexOfExistedPackage].Items.find(x => x.id === activeDevice.id);
      const products=this.cart[indexOfExistedPackage].Items;
      var cartitemid = [];

         for (let i = 0; i < this.cart.length; i++) {

           let itemlen=this.cart[i].Items.length;

           for (let j = 0; j < itemlen; j++) 
           {
            cartitemid.push(this.cart[i].Items[j].id);

           }

        }   

      var controllerid:any = [1,53,54];
      var deviceid:any = [20,21,42,43];

      var comparrayconrlor:any[] = [];
      var comparraydevice:any[] = [];

      cartitemid.forEach((y) => {
                
         if(controllerid.indexOf(y) > -1 ){
            comparrayconrlor.push(y)
        }
      })

       cartitemid.forEach((z) => {
                
         if(deviceid.indexOf(z) > -1 ){
            comparraydevice.push(z)
        }
      })
     
      //console.log(comparrayconrlor.length+'Conlength');
     // console.log(comparraydevice.length+'Devlength');



    
     if(comparrayconrlor.length > 1)
        {
           this.toaster.error('More than one Zwave controller can not be selected');
        }
        else if(comparrayconrlor.length==1 && comparraydevice.length==0){
            this.toaster.error('Please select Zwave device');
        }
         else if( comparraydevice.length>0 && comparrayconrlor.length == 0 )
         {
              this.toaster.error('Please select at least one Zwave controller');
         }
         else{

              this.spinnerService.show();     
              this.posService.saveQuotation(draftData).subscribe((res: any) => {
              this.toaster.success('Your quotation has been submitted to ' + this.user.email + '. Thank you');
              this.getDrafts();
              this.spinnerService.hide();
              this.resetUtility();
              this.singleDraft = res.data.quotation;
               }, error => {
                this.toaster.error('Error in creating kit');
                console.log(error);
                this.spinnerService.hide();
                }

              );
              sessionStorage.setItem('changesSomeone', '');
         }

       
    }
    else {
            this.toaster.error('Cart Is Empty Please Check');
    }


   /* } else {
      this.toaster.error('Error');
    }*/
    /*  } else {
        this.toaster.warning('Can not create Quotation, first make kit');
      }*/
  }

  /**
   * Delete Draft or Quotation
   * @Param id
   */
  deleteDraftQuotation(id: number): void {
    // @ts-ignore
    Swal.fire({
      title: '',
      text: 'Are you sure you want to delete the kit?',
      icon: '',
      showCancelButton: true,
      confirmButtonColor: '#f38022',
      cancelButtonColor: '#f38022',
      confirmButtonText: 'Delete',
      customClass: {
        cancelButton: 'cancel-btn-width',
        confirmButton: 'cancel-btn-width',
        content: 'text_siz',
        htmlContainer: 'text_siz'
      }
    }).then(result => {
      if (result.value) {
        this.spinnerService.show();
        const sb = this.posService.deleteDraftQuotation(id).subscribe(
          (res: string) => {
            this.resetUtility();
            this.getDrafts();
            this.spinnerService.hide();
            this.toaster.success(res);
          }, error => {
            this.spinnerService.hide();
            this.toaster.error('Error in deleting, please try again');
          }
        );
        this.subscription.push(sb);
      }
    });
  }

  /**
   * Reset Utility
   */
  private resetUtility(): void {
    this.savingErrorMessage = '';
    this.savingSuccessMessage = '';
    this.cart = [];
    this.cartCopyOfDraftOrQuotation = [];
    this.draftOrQuotationEdited = false;
    this.submitted = false;
    this.item = {};
    this.rooms = [];
    this.devices = [];
    this.totalAmount = 0;
    this.getAllPackages();
    this.defaultSelection = true;
    DraftManagement.setDropDownToDefault(this.userDrafts);
    this.showHideItems = {hideSave: true, hideUpdate: false};
    this.singleDraft = {} as IDraft;
    this.savingForm.reset();
    sessionStorage.removeItem('quotation');
  }

  /***
   * Cart Comparison utility
   */
  cartComparison(): boolean {
    const cartData = DraftManagement.draftData(this.cart, this.user, this.partner, this.singleDraft.title).draft_items;
    const draftData = DraftManagement.draftData(this.cartCopyOfDraftOrQuotation, this.user, this.partner, this.singleDraft.title).draft_items;
    return isEqual(cartData, draftData);
  }

  /***
   * Is Draft Or Quotation Empty
   */
  isDraftQuotationEmpty(): boolean {
    if (this.singleDraft) {
      return DraftManagement.isDraftEmpty(this.singleDraft);
    }
    return true;
  }

  isDraftQuotationBelongsToUser(): boolean {
    if (!DraftManagement.isDraftEmpty(this.singleDraft)) {
      return DraftManagement.isDraftQuotationBelongsToUser(this.singleDraft);
    }
    return false;
  }

  /**
   * Setting Draft and Quotation Items in Cart
   */
  private setDraftItemsInCart(items: IDraftItem[], draftCategory: DraftCategory): void {
    for (const item of items) {
      const packageExistInCart = DraftManagement.packageExistsInCart(item.package, this.cart);
      // if package exists
      if (packageExistInCart) {
        const indexOfPackageExistInCart = indexOf(this.cart, packageExistInCart);
        const roomExistInCart = DraftManagement.roomExistsInCart(indexOfPackageExistInCart, item.room_id, this.cart);
        // if room exists
        if (roomExistInCart) {
          const deviceExitsInCart = DraftManagement.deviceExistsInCart(indexOfPackageExistInCart, item.device_id, this.cart);
          // if room exists and device also exists
          if (deviceExitsInCart) {
            const indexOfDeviceExistsInCart = indexOf(this.cart[indexOfPackageExistInCart].Items, deviceExitsInCart);
            this.cart[indexOfPackageExistInCart].Items[indexOfDeviceExistsInCart].quantity = item.quantity;
            // if (this.item.id === item.device.id) {
            //   this.item.quantity = item.quantity;
            // }
          } else {
            if (item.device) {
              // // if room exists but device does not exists then we have to add new item
              const device = {
                id: item.device.id,
                roomId: item.room_id,
                roomTitle: item.room.name,
                quantity: item.quantity,
                price: draftCategory === DraftCategory.QUOTATION ? item.price as number : item.device.price,
                title: item.device.name
              };
              this.cart[indexOfPackageExistInCart].Items.push(device);
              // if (this.item.id === item.device.id) {
              //   this.item.quantity = item.quantity;
              // }
            }
          }
        }
        // if package exits but room does not exists
        else {
          if (item.device) {
            const device = {
              id: item.device.id,
              roomId: item.room_id,
              roomTitle: item.room.name,
              quantity: item.quantity,
              price: draftCategory === DraftCategory.QUOTATION ? item.price as number : item.device.price,
              title: item.device.name
            };
            this.cart[indexOfPackageExistInCart].Items.push(device);
          }
          // if (this.item.id === item.device.id) {
          //   this.item.quantity = item.quantity;
          // }
        }
      }
      // if package does not exist
      else {
        const device = {
          id: item.device.id,
          roomId: item.room.id,
          roomTitle: item.room.name,
          title: item.device.name,
          quantity: item.quantity,
          price: draftCategory === DraftCategory.QUOTATION ? item.price as number : item.device.price
        };
        this.cart.push({
          total_amount: 0,
          package_id: item.package.id,
          package_title: item.package.name,
          Items: [device]
        });
        // if (this.item.id === item.device.id) {
        //   this.item.quantity = item.quantity;
        // }
      }
      this.adjustAmount();
    }
  }

  /**
   *  Save As Draft
   */
  public openSaveAsModal(content: any): void {
    this.modal.open(content);
  }

  public saveAsDraft(): void {
    this.saveAsFormSubmitted = true;
    if (this.saveAsForm.invalid) {
      return;
    }
    if (this.cart.length && this.user && this.partner) {
      this.spinnerService.show();
      const data = DraftManagement.draftData(this.cart, this.user, this.partner, this.g.title.value);
      const sb = this.posService.saveDraft(data).subscribe(
        (res: any) => {
          this.saveAsFormSubmitted = false;
          this.saveAsForm.reset();
          this.spinnerService.hide();
          // this.resetSelection();
          this.toaster.success(res.message);
          this.userDrafts.push({...res.data, selected: true, category: 'draft'});
          this.drafts.push({...res.data, selected: true, category: 'draft'});
          this.singleDraft = {...res.data, category: 'draft'};
          this.savingForm.setValue({title: this.singleDraft.title});
          this.showHideItems = {
            hideSave: false,
            hideUpdate: true
          };
          this.cartCopyOfDraftOrQuotation = JSON.parse(JSON.stringify(this.cart));
          this.modal.dismissAll();
          this.singleDraft.title = '';
          this.savingForm.reset();
        }, error => {
          this.saveAsFormSubmitted = false;
          this.spinnerService.hide();
          if (error.error.data.title) {
            this.toaster.error(error.error.data.title[0]);
          } else {
            this.toaster.error(error.error.message);
          }
          console.log(error);
        }
      );
      this.subscription.push(sb);
    } else {
      this.toaster.error('Can not save an empty kit');
      this.saveAsFormSubmitted = false;
      this.saveAsForm.reset();
    }
  }

  public dismissModel(): void {
    this.modal.dismissAll();
    this.saveAsFormSubmitted = false;
  }

  // tslint:disable-next-line:typedef
  get g() {
    return this.saveAsForm.controls;
  }

  /**
   * Go to the CheckOut Page
   */


  public goToCheckOut(): void {
    if (this.singleDraft && this.cart.length){
      const cartitem = JSON.stringify(this.cart);
      const activePackage = this.packages.find(x => x.active === true);
      const indexOfExistedPackage = this.cart.findIndex(x => x.package_id === activePackage.id);
      const activeDevice = this.devices.find(x => x.selected === true);
      const deviceExistsInCart = this.cart[indexOfExistedPackage].Items.find(x => x.id === activeDevice.id);
      const products=this.cart[indexOfExistedPackage].Items;
      var cartitemid = [];

         for (let i = 0; i < this.cart.length; i++) {

           let itemlen=this.cart[i].Items.length;

           for (let j = 0; j < itemlen; j++) 
           {
            cartitemid.push(this.cart[i].Items[j].id);

           }

        }   

        var controllerid:any = [1,53,54];
      var deviceid:any = [20,21,42,43];

      var comparrayconrlor:any[] = [];
      var comparraydevice:any[] = [];

      cartitemid.forEach((y) => {
                
         if(controllerid.indexOf(y) > -1 ){
            comparrayconrlor.push(y)
        }
      })

       cartitemid.forEach((z) => {
                
         if(deviceid.indexOf(z) > -1 ){
            comparraydevice.push(z)
        }
      })

       if(comparrayconrlor.length > 1)
        {
           this.toaster.error('More than one Zwave controller can not be selected');
        }
        else if(comparrayconrlor.length==1 && comparraydevice.length==0){
            this.toaster.error('Please select Zwave device');
        }
         else if( comparraydevice.length>0 && comparrayconrlor.length == 0 )
         {
              this.toaster.error('Please select at least one Zwave controller');
         }

        
         else {
       // setting up cart itmes to the session storage
      sessionStorage.removeItem('cart_items');
      sessionStorage.removeItem('quotation');
      sessionStorage.setItem('cart_items', JSON.stringify(this.cart));
      sessionStorage.setItem('quotation', JSON.stringify(this.singleDraft));     
     this.route.navigate(['/cart']);
         }
     }

    else {
      this.toaster.error('Cart Is Empty Please Check');
    }




   /* else if (this.singleDraft && this.cart.length) {
      // setting up cart itmes to the session storage
      sessionStorage.removeItem('cart_items');
      sessionStorage.removeItem('quotation');
      sessionStorage.setItem('cart_items', JSON.stringify(this.cart));
      sessionStorage.setItem('quotation', JSON.stringify(this.singleDraft));
     
     this.route.navigate(['/cart']);
    } 
    else {
      this.toaster.error('Cart Is Empty Please Check');
    }*/
  }

  /**
   * Utility Function For Filtering Drafts
   */
  public filterDrafts(category: string, type: string): IDrafts[] {
    return this.drafts.filter(x => x.category === category && x.type === type);
  }

  public saveQuoteId(id: any) {
  }

  arrayEquals(a: any, b: any) {
    return Array.isArray(a) &&
      Array.isArray(b) &&
      a.length === b.length &&
      a.every((val, index) => val === b[index]);
  }
}

