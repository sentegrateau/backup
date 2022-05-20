export interface IRoom extends Room{
  active: boolean;
}
export interface Room {
  id: number;
  partner_id: number;
  name: string;
  description: string;
  status: string;
  created_at: string;
  updated_at: string;
}
