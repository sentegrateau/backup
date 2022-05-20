import {Component, OnInit, Input, Output, EventEmitter} from '@angular/core';
import {Items} from '../../model/item.model';
import {PosService} from '../pos.service';
import {IDevice} from '../../model/device.interface';

@Component({
  selector: 'app-devices',
  templateUrl: './devices.component.html',
  styleUrls: ['./devices.component.scss']
})
export class DevicesComponent implements OnInit {


  @Input() items: IDevice[] = [];
  @Output() onClick = new EventEmitter();

  constructor() {

  }

  ngOnInit(): void {
    // this.get('device', 7)
  }

  handleClick(event: IDevice): void {
    this.onClick.emit(event);
  }


}
