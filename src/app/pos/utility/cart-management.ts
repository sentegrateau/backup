import {IPackage} from '../../model/package.interface';
import {IRoom} from '../../model/room.interface';
import {IDevice} from '../../model/device.interface';
import {ICart} from '../../model/cart.model';

export class CartManagement{
  /**
   * Get the Quantity of Item exist in cart
   */
  static getQuantityOfItemExistInCart(packages: IPackage[], rooms: IRoom[], devices: IDevice[], cart: ICart[]): number {
    const packageId = packages.find(x => x.active);
    const roomId = rooms.find(x => x.active);
    const deviceId = devices.find(x => x.selected);
    let quantity = 0;
    if (cart.length) {
      if (packageId) {
        const packageExistInCart = cart.find(x => x.package_id === packageId.id);
        if (packageExistInCart) {
          const packageIndex = cart.indexOf(packageExistInCart);
          if (roomId && deviceId) {
            const deviceExistInCart = cart[packageIndex].Items.find(x => x.id === deviceId.id && x.roomId === roomId.id);
            if (deviceExistInCart) {
              const deviceIndex = cart[packageIndex].Items.indexOf(deviceExistInCart);
              quantity = cart[packageIndex].Items[deviceIndex].quantity;
            }
          }
        }
      }
    }
    return quantity;
  }

  /**
   * Comparing the cart with cart copy of Draft/Quotation
   */
  static cartEdited(a: any, b: any): boolean {
    return (a.length === b.length && a.every((v: any, i: any) => v === b[i]));
  }
}
