import {ICart, Items as CItem} from '../../model/cart.model';
import {User} from '../../model/user.model';
import {DraftCategory, DraftType, IDraft, IDrafts} from '../../model/draft.model';
import {Package} from '../../model/package.interface';
import {Room} from '../../model/room.interface';
import {Device} from '../../model/device.interface';
import {IDraftItem} from '../../model/draft-item.model';

export class DraftManagement {

  /**
   * getting data from cart to save it as a draft
   */
  static draftData(cart: ICart[], user: User, partner: User, title: string): any {
    const draft: any = {};
    const draftItems: any = [];
    draft.user_id = user.id;
    draft.partner_id = partner.id;
    draft.type = 'user';
    draft.title = title;
    for (const c of cart) {
      for (const item of c.Items) {
        const data = {
          package_id: c.package_id,
          room_id: item.roomId,
          device_id: item.id,
          quantity: item.quantity,
          price: item.price
        };
        draftItems.push(data);
      }
    }
    draft.draft_items = draftItems;
    return draft;
  }

  /**
   * Check Single Draft is empty
   */
  static isDraftEmpty(value: IDraft): boolean {
    return value !== null && Object.keys(value).length === 0;
  }

  /**
   * Checking Draft or Quotation Belongs to user
   */
  static isDraftQuotationBelongsToUser(value: IDraft): boolean {
    return value.type === DraftType.USER && value.category === DraftCategory.DRAFT;
  }

  /**
   *  Is Package Active
   */
  static isPackageActive(value: Package): boolean {
    return value.status === '1';
  }

  /**
   * Is Package Exists in cart
   */
  static packageExistsInCart(value: Package, cart: ICart[]): ICart | undefined {
    return cart.find(e => e.package_id === value.id);

  }

  /**
   * Is Room Active
   */
  static isRoomActive(value: Room): boolean {
    return value.status === '1';
  }

  /**
   * Is Device Active
   */
  static isDeviceActive(value: Device): boolean {
    return value.status === '1';
  }

  /**
   * Check if Room Exists in cart then return Cart Item
   */
  static roomExistsInCart(index: number, roomId: number, cart: ICart[]): CItem | undefined {
    return cart[index].Items.find(x => x.roomId === roomId);
  }

  /**
   *  check if device exists in cart then return Cart Item
   */
  static deviceExistsInCart(index: number, deviceId: number, cart: ICart[]): CItem | undefined {
    return cart[index].Items.find(x => x.id === deviceId);
  }

  /**
   *  Filtering Quotation and Return Active Package DraftItem
   */
  static getActivePackageDraftItems(items: IDraftItem[]): IDraftItem[] {
    return items.filter(x => x.package.status === '1');
  }

  /**
   *  Setting the value of Drop Down to null
   */
  static setDropDownToDefault(userDrafts: IDrafts[]): void {
    const findSelected = userDrafts.find((x) => x.selected);
    if (findSelected) {
      const index = userDrafts.indexOf(findSelected);
      userDrafts[index].selected = false;
    }
  }

  /**
   * Finding and Selecting the Quotation
   */
  static findAndSelectDraft(quotations: IDrafts[], singleQuotation: IDraft): void {
    const findQuotation = quotations.find(x => x.id === singleQuotation.id);
    if (findQuotation) {
      const index = quotations.indexOf(findQuotation);
      const findActive = quotations.find(x => x.selected);
      if (findActive) {
        const activeIndex = quotations.indexOf(findActive);
        quotations[activeIndex].selected = false;
      }
      quotations[index].selected = true;
    }
  }
}
