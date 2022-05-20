import {IDevice} from './device.interface';

export interface PackageData{
  id: number;
  partner_id: number;
  name: string;
  description: string;
  status: string;
  created_at: string;
  updated_at: string;
  devices: IDevice[];
}
