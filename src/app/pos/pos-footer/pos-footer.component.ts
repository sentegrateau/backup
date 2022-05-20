import { Component, OnInit , Output, EventEmitter, Input} from '@angular/core';

@Component({
  selector: 'app-pos-footer',
  templateUrl: './pos-footer.component.html',
  styleUrls: ['./pos-footer.component.scss']
})
export class PosFooterComponent implements OnInit {
  // tslint:disable-next-line:no-output-on-prefix
  @Output() onClick = new EventEmitter();
  @Output() order = new EventEmitter();

  constructor() { }

  ngOnInit(): void {
  }

  handleChange(event: any): void  {
    this.onClick.emit(event);
  }
  placeOrder(event: any): void {
    this.order.emit(event);
  }

}
