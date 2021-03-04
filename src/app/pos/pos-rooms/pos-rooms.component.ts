import { Component, OnInit } from '@angular/core';
import { Items } from "../../model/item.model";
import { PosService } from "../../pos/pos.service";
@Component({
  selector: 'app-pos-rooms',
  templateUrl: './pos-rooms.component.html',
  styleUrls: ['./pos-rooms.component.scss']
})
export class PosRoomsComponent implements OnInit {

  items : Items[] = [];

  constructor(private posService: PosService) { 
      
  }
  ngOnInit(): void {
    this.get('room', 4)
  }


  handleChange (event: any) : void  {
      console.log(event);
  }

  get(type : string, limit: number): void {
    limit = limit || 10;
    type = type || "package"
    this.posService.get(type, limit)
        .subscribe(items => {
          this.items = items
          console.log(this.items);
        });
  }
}
