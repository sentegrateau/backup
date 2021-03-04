import { Injectable } from '@angular/core';
import { ApiServics } from '../api/api.service';
import { Items } from "../model/item.model";
import { Observable, of } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class PosService extends ApiServics {

  get(type: string, limt : number): Observable<any> {
    return of(this.getGata(type, limt));
  }
  
}
