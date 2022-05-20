export interface IPackage extends Package{
  active: boolean;
}
export interface Package {
  id: number;
  partner_id: number;
  name: string;
  description: string;
  status: string;
  created_at?: string;
  updated_at?: string;
  deleted_at?: string | null;
  order?: number | null;
}

