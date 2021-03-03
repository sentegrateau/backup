import { Component, OnInit } from '@angular/core';
import { PosHnandler } from "../../handler/pos.handler";

@Component({
  selector: 'app-pos-packages',
  templateUrl: './pos-packages.component.html',
  styleUrls: ['./pos-packages.component.scss']
})
export class PosPackagesComponent extends PosHnandler implements OnInit {

  ngOnInit(): void {
    for (let index = 0; index < 5; index++) {

      this.items.push({
        label : `Package ${index}`,
        id: index,
        imgUrl : null,
        active : index == 1 ?  true : false
      });
      
    }
   
  }
  handleChange (event: any) : void  {
    console.log(event);
  }
}
