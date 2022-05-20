export interface ICart {
  total_amount: number;
  package_id: number;
  package_title: string;
  Items: Items[];
}

export interface Items {
  id: number;
  roomId: number;
  roomTitle: string;
  title: string;
  quantity: number;
  price: number;
  //titles:string;
}
