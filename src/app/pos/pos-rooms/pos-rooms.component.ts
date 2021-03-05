import { Component, OnInit , Input ,Output, EventEmitter } from '@angular/core';
import { Items } from "../../model/item.model";
import { PosService } from "../../pos/pos.service";
@Component({
  selector: 'app-pos-rooms',
  templateUrl: './pos-rooms.component.html',
  styleUrls: ['./pos-rooms.component.scss']
})
export class PosRoomsComponent implements OnInit {

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
