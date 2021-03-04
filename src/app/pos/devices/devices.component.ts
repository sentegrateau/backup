import { Component, OnInit } from '@angular/core';
import { Items } from "../../model/item.model";
import { PosService } from '../pos.service';

@Component({
  selector: 'app-devices',
  templateUrl: './devices.component.html',
  styleUrls: ['./devices.component.scss']
})
export class DevicesComponent implements OnInit {
  items : Items[] = [];

  constructor(private posService: PosService) { 
      
  }

  ngOnInit(): void {
    this.get('device', 7)
  }

  handleChange (event: any) : void  {
    console.log(event);
  }

  get(type : string, limit: number): void {
    limit = limit || 10;
    type = type || "device"
    this.posService.get(type, limit)
        .subscribe(items => this.items = items);
  }

}
