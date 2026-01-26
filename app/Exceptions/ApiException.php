<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    private $statusCode;

    public function __construct($message, $code = 1, $statusCode = 200)
    {
        parent::__construct($message, $code);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
