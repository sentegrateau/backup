import {Room} from './room.interface';
import {Device} from './device.interface';
import {Package} from './package.interface';

export interface IDraftItem {
  id: number;
  draft_id: number;
  package_id: number;
  room_id: number;
  device_id: number;
  quantity: number;
  price: number | null;
  deleted_at?: string;
  created_at?: string;
  updated_at?: string;
  room: Room;
  device: Device;
  package: Package;
}
