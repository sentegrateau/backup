import { Component, OnInit } from '@angular/core';
import { PosHnandler } from "../../handler/pos.handler";

@Component({
  selector: 'app-pos-rooms',
  templateUrl: './pos-rooms.component.html',
  styleUrls: ['./pos-rooms.component.scss']
})
export class PosRoomsComponent extends PosHnandler implements OnInit {


  ngOnInit(): void {
    for (let index = 0; index < 10; index++) {

      this.items.push({
        label : `Room ${index}`,
        id: index,
        imgUrl :  null,
        active : index == 3 ?  true : false
      });
    }
  }


    handleChange (event: any) : void  {
        console.log(event);
    }
}
