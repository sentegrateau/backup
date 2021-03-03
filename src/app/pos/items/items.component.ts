import { Component, OnInit,  Input ,Output, EventEmitter   } from '@angular/core';
import { Items } from "../../model/item.model";

@Component({
  selector: 'app-items',
  templateUrl: './items.component.html',
  styleUrls: ['./items.component.scss']
})
export class ItemsComponent implements OnInit {

  @Input() items : Items[] = []; 
  @Output() onClick = new EventEmitter();


  constructor() { }

  ngOnInit(): void {
    
  }

  handelClick(event : Items) : void {
    this.onClick.emit(event);
  }

}
