import { Component, OnInit } from '@angular/core';
import { PosHnandler } from "../../handler/pos.handler";

@Component({
  selector: 'app-devices',
  templateUrl: './devices.component.html',
  styleUrls: ['./devices.component.scss']
})
export class DevicesComponent extends PosHnandler implements OnInit {

  ngOnInit(): void {
    for (let index = 0; index < 10; index++) {
      this.items.push({
        label : `Room ${index}`,
        id: index,
        imgUrl : 'https://picsum.photos/200' ,
        active : index == 0 ?  true : false
      });
    }
  }

  handleChange (event: any) : void  {
    console.log(event);
  }

}
