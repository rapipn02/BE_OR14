<?php

namespace App\Helper;

class ResponseHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * function :common function to display success response - json response
     * @param string $status
     * @param string $message
     * @param array $data
     * @param integer $statusCode
     * @return response
     */
    public static function success($status = 'success', $message= null, $data=[], $statuscode=200)
   { return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ], $statuscode);
}
    
/**
     * function :common function to display erroe response - json response
     * @param string $status
     * @param string $message
     * @param integer $statusCode
     * @return response
     */
    public static function error($status = 'error', $message= null, $statuscode=400)
    {return response()->json([
        'status' => $status,
        'message' => $message,
    ], $statuscode);

    
}
}
