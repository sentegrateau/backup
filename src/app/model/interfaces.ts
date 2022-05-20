export interface FilteredItem {
  id: number;
  title: string;
  quantity: number;
  price?: number;
}

export interface FilteredResult {
  id: number;
  title: string;
  quantity: number;
  price: number;
  totalPrice: number;
}

export interface Room {
  id?: number;
  title?: string;
  devices: Device[];
}

export interface Device {
  id: number;
  title: string;
  quantity: number;
  price: number;
}

export interface DecodedToken {
  partner_id: string;
  user_name: string;
  user_email: string;
  abn?: string;
  company?: string;
  kit_name?: string;
  role2: string;
  role: string;
}

export interface User {
  abn: string;
  company: string;
  contact: string;
  created_at: string;
  customer: number;
  deleted_at: string;
  email: string;
  email_verified_at: string;
  id: number;
  last_login: string;
  name: string;
  role: string;
  role2: string;
  status: number;
  updated_at: string;
}
