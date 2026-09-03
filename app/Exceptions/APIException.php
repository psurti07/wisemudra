<?php

namespace App\Exceptions;

use Exception;

class APIException extends Exception
{
    public $httpResponseCode;
    public $status;
    public $errorCode;
    public $errorMessage;
    public function __construct($httpResponseCode, $status, $errorCode, $errorMessage) {
        parent::__construct ( $errorMessage == null ? "Something went wrong" : $errorMessage );
        $this->httpResponseCode = $httpResponseCode;
        $this->status = $status;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }
    public function getHttpResponseCode() {
        return $this->httpResponseCode;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getErrorCode() {
        return $this->errorCode;
    }
    public function getErrorMessage() {
        return $this->errorMessage;
    }
}
