export interface IDevice extends Device {
  selected: boolean;
  quantity: number;
  min_qty: number;
  max_qty: number;
  imageUrl: string;
}

export interface Device {
  id: number;
  partner_id: number;
  device_id: string;
  name: string;
  description: string;
  brand: string;
  model: string;
  active: boolean;
  price: number;
  discount: number;
  stock_status: boolean;
  supplier: string;
  manual_url: string;
  image: string;
  imageUrl: string;
  status: string;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
}
