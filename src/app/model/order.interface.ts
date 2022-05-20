export interface IOrder{
  user_id?: number;
  partner_id?: number;
  type?: string;
  amount?: number;
  order_items?: [OrderItem];
}
interface OrderItem{
  package_id: number;
  room_id: number;
  device_id: number;
  quantity: number;
}
