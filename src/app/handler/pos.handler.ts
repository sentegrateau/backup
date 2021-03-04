import { Items } from "../model/item.model";
import { PosService } from "../pos/pos.service";

export class PosHnandler {
    items : Items[] = [];

    constructor(private posService: PosService) { 
        
    }

    get(type : string, limit: number): void {
        limit = limit || 10;
        type = type || "package"
        this.posService.get(type, limit)
            .subscribe(items => this.items = items);
      }

}