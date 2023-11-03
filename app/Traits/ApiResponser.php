<?php

namespace App\Traits;

trait ApiResponser
{
    /**
     * Returns a success response
     * 
     * @param mixed $data
     * @param null $message
     * @param int $code
     * 
     * @return \Iluminate\Http\Response
     */
    protected function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'status'    => 'Success',
            'message'   => $message,
            'data'      => $data
        ], $code);
    }
        
    /**
     * Returns error response
     * 
     * @param null $message
     * @param int $code
     * 
     * @return \Iluminate\Http\Response
     */
    protected function errorResponse($message = null, $code = 400)
    {
        return response()->json([
            'status'    => 'Error',
            'message'   => $message,
            'data'      => null
        ], $code);
    }

    /**
     * Returns validation error response
     * 
     * @param mixed $errors
     * @param null $message
     * @param int $code
     * 
     * @return \Iluminate\Http\Response
     */
    protected function validationErrorResponse($errors, $message = null, $code = 403)
    {
        return response()->json([
            'status'    => 'Validation Error',
            'message'   => $message,
            'data'      => $errors
        ], $code);
    }
}