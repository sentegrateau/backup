export interface IDrafts extends  IDraft {
  selected: boolean;
}
export interface IDraft {
  id: number;
  user_id: number;
  partner_id: number;
  type: DraftType;
  title: string;
  category: DraftCategory;
  quotation_no: string;
  quot_name: string;
  total_amount: number;
  validity: string;
}
export enum DraftCategory {
  DRAFT = 'draft',
  QUOTATION = 'quotation'
}

export enum DraftType {
  USER= 'user',
  ADMIN = 'admin'
}

