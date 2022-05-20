import { Component, OnInit, Input , Output, EventEmitter  } from '@angular/core';
import { Items } from '../../model/item.model';
import {IPackage} from '../../model/package.interface';


@Component({
  selector: 'app-pos-packages',
  templateUrl: './pos-packages.component.html',
  styleUrls: ['./pos-packages.component.scss']
})
export class PosPackagesComponent implements OnInit {

  @Input() items: IPackage[] = [];
  @Output() onClick = new EventEmitter();

  constructor() {

  }

  ngOnInit(): void {

  }

  // handleChange(event: any): void  {
  //   this.onClick.emit(event);
  // }
  handleClick(item: IPackage): void {
    this.onClick.emit(item);
  }
}
