<?php

namespace App\Mail;

use App\Http\Controllers\Front\UserController;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order;


    public function __construct($order,$emailContent)
    {
        $this->order = $order;
		$this->emailContent = $emailContent;
        $this->subject(config('APP_NAME') . 'Sentegrate Order Confirmation No: ' . $this->order->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /*$orderData = (new UserController())->getOrderDetails($this->order->id);
        $order = $orderData['order'];
        $shippingText = $orderData['shippingText'];
        $orders = $orderData['orders'];
        $total = $orderData['total'];

        $pdf = PDF::loadView("invoice", ["order" => $order, 'shippingText' => $shippingText, 'orders' => $orders, 'total' => $total]);*/

        $orderData = (new UserController())->getOrderDetails($this->order->id);
        $order = $orderData['order'];
        $items = (new UserController())->filteringSubItems($order->toArray());
		
		
		$quotation = ['title'=>'','category'=>''];
		if(is_numeric($orderData['order']->draft_id)){
			$quotationQ = DB::table('drafts')->where('id','=',$orderData['order']->draft_id)->first();
			if($quotationQ->category =='quotation' ){
				 $quotation['title']='Quotation-'.$quotationQ->id.'-'.date('Ymd', strtotime($quotationQ->created_at));
			}else{
				$quotation['title']='';
			}
			$quotation['category']= $quotationQ->category =='quotation' ? 'Quotation': 'Kit Name';
		}




        $shippingText = $orderData['shippingText'];
        $orders = $orderData['orders'];
        //echo "<pre>";print_r($orders);die;
        $total = $orderData['total'];
        $discount = $orderData['discount'];
        $paid_amt = $orderData['paid_amt'];
        $order_id = $this->order->id;
        $pdf = PDF::loadView("invoice", compact('order', 'shippingText', 'orders', 'total', 'order_id', 'discount', 'paid_amt','items','quotation'));
        return $this->from('sales@sentegrate.com.au', config('app.name'))
            //->bcc(config('app.admin_email'),config('app.name'))
			->bcc([config('app.admin_email'),'syed.zaid@sentegrate.com.au'],config('app.name'))
            ->view('emails.order-received')->with(['emailContent'=>$this->emailContent,'order'=>$order])
            ->attachData($pdf->output(), 'Sentegrate Invoice '.$order_id.'-' . date('Ymd') . '.pdf');
    }
}
