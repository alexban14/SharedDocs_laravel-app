<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;


class GeneralJsonException extends Exception
{
    /**
     * Report method => responsible with reporting an error
     * (ex: logging the error, send the dev an email with the error, etc)
     *
     * @return void
     */
    public function report()
    {

    }

    /**
     * Render the exception as an HTTP response
     *
     * @param \Illuminate\Http\Request $request
     */
    public function render($request)
    {
        return new JsonResponse([
            'errors' => [
                'message' => $this->getMessage()
            ]
        ], $this->code); // getting the status code from the exception error thrown
    }
}
