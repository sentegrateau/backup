<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController as BaseController;

class EmailController extends BaseController
{
    public function index(Request $request){
        $rules = [
           'email' => 'required|email',
            'total' => 'required',
            'data' => 'required|array'
        ];
        $message =[
            'email.required' => 'Email is required',
            'email.email' => 'Please provide valid email',
            'total' => 'Total is required',
            'data' => 'Data is required'
        ];
        try {
            $validation = Validator::make($request->all(), $rules, $message);
            if ( $validation->fails()){
                return $this->sendError('Validation error', $validation->errors(), 422);
            }
            $mailData['email'] = $request['email'];
            $mailData['subject'] = 'Sentegrate Quotation';
            $total = $request['total'];
            $data = $request['data'];
            $pdf = PDF::loadView("quotation", ["data" =>$data, "total" => $total]);
            Mail::send("quotation",["data" =>$data, "total" => $total], function($message) use ($mailData, $pdf){
                $message->to($mailData['email'])
                    ->subject($mailData['subject'])
                    ->attachData($pdf->output(),'quotation.pdf');
            });
            if (Mail::failures()){
                return $this->sendError('Error', 'Error in sending email', 422);
            }else {
                return $this->sendResponse('','Email has been send successfully');
            }

        }catch (\Exception $e){
            return $this->exceptionHandler($e->getMessage());
        }
    }
}
