<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    public function sendResponse($result,$message){
        $response = [
            'success' => true,
            'data' => $result,
            'message'=> $message
        ];
        return response()->json($response, 200);
    }

    public function sendError($error, $message = [], $code = 404){
        $response =[
            'success' => false,
            'message' => $error
        ];

        if(!empty($message)){
            $response['data'] = $message;
        }
        return response()->json($response,$code);
    }

    public function exceptionHandler($result, $code = 500){
        $response = [
            'error'=> true,
            'message' => $result,
            'data' => null
        ];

        return response()->json($response, $code);
    }
}
