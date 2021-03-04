import { Component, OnInit } from '@angular/core';
import { PosService } from '../pos.service';
import { Items } from "../../model/item.model";


@Component({
  selector: 'app-pos-packages',
  templateUrl: './pos-packages.component.html',
  styleUrls: ['./pos-packages.component.scss']
})
export class PosPackagesComponent implements OnInit {
  items : Items[] = [];

  constructor(private posService: PosService) { 
      
  }

  ngOnInit(): void {
    this.get('package', 10)
  }

  handleChange (event: any) : void  {
    console.log(event);
  }

  get(type : string, limit: number): void {
    limit = limit || 10;
    type = type || "package"
    this.posService.get(type, limit)
        .subscribe(items => this.items = items);
  }
}
