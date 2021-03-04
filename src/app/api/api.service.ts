import { Items } from "../model/item.model";

export class ApiServics {
    items : Items[] = [];
    constructor() {

    }

  getGata(type: string, limit: number) {
    this.items = [];
    for (let index = 0; index < limit; index++) {

        this.items.push({
          label : `${type} ${index}`,
          id: index,
          imgUrl : type == 'device' ? 'https://picsum.photos/200' : null,
          active : index == 1 ?  true : false
        });
        
      }

      return this.items;
  }

}