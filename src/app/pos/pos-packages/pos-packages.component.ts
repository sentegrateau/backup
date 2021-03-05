import { Component, OnInit, Input ,Output, EventEmitter  } from '@angular/core';
import { Items } from "../../model/item.model";


@Component({
  selector: 'app-pos-packages',
  templateUrl: './pos-packages.component.html',
  styleUrls: ['./pos-packages.component.scss']
})
export class PosPackagesComponent implements OnInit {

  @Input() items : Items[] = []; 
  @Output() onClick = new EventEmitter();

  constructor() { 
      
  }

  ngOnInit(): void {
   
  }

  handleChange (event: any) : void  {
    this.onClick.emit(event);
  }

  
}
