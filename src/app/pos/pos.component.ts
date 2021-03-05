import { Component, OnInit } from '@angular/core';
import { PosService } from './pos.service';
import { Items } from "../model/item.model";


@Component({
  selector: 'app-pos',
  templateUrl: './pos.component.html',
  styleUrls: ['./pos.component.scss']
})
export class PosComponent implements OnInit {
  items : Items[] = [];
  roomItems : Items[] = [];
  devicesItems : Items[] = [];

  constructor(private posService: PosService) { }

  ngOnInit(): void {
    this.get('package', 10);
  }

  get(type : string, limit: number): void {
    limit = limit || 0;
    type = type || "package"
    this.posService.get(type, limit)
        .subscribe(items => {
          
          switch (type) {
            case "room":
              this.roomItems = items
              break;
            case "device":
                this.devicesItems = items
                break;
            default:
              this.items = items
              break;
          };
          
         console.log(items);
          
        });
  }

  handleChange (event: any) : void  {
    let type = event.label.split(" ")[0];

    switch (type) {
      case "package":
        this.get('device',0)
        type = 'room'
        break;
        case "room":
        type = 'device'
        break;
      default:
        type = 'device'
        break;
    };


    this.get(type,event.id)
  }
}
