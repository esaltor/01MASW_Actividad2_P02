<?php

namespace App\Http\Responses;

class ResultResponse
{
    const SUCCESS_CODE = 200;
    const ERROR_CODE = 400;
    const UNAUTHORIZED_CODE = 401;
    const FORBIDDEN_CODE = 403;
    const NOT_FOUND_CODE = 404;
    const INTERNAL_SERVER_ERROR_CODE = 500;

    const TXT_SUCCESS_CODE = 'Success';
    const TXT_ERROR_CODE = 'Error';
    const TXT_UNAUTHORIZED_CODE = 'Unauthorized';
    const TXT_FORBIDDEN_CODE = 'Forbidden';
    const TXT_NOT_FOUND_CODE = 'Not Found';
    const TXT_INTERNAL_SERVER_ERROR_CODE = 'Internal Server Error';

    public $statusCode;
    public $message;
    public $data;

    // Constructor
    function __construct() {
        $this->statusCode = self::ERROR_CODE;
        $this->message = self::TXT_ERROR_CODE;
        $this->data = null;
    }

    // Getters and Setters
    public function getStatusCode() {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode): void {
        $this->statusCode = $statusCode;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message): void {
        $this->message = $message;
    }

    public function getData() {
        return $this->data;
    }
    
    public function setData($data): void {
        $this->data = $data;
    }

    // Helpers
    public function toArray(): array {
        return [
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    public static function ok(mixed $data): array {
        $resultResponse = new self();
        $resultResponse->setData($data);
        $resultResponse->setStatusCode(self::SUCCESS_CODE);
        $resultResponse->setMessage(TXT_SUCCESS_CODE);
        return $resultResponse->toArray();
    }

    public static function fail(int $statusCode, string $message, mixed $data = null): array {
        $resultResponse = new self();
        $resultResponse->setStatusCode($statusCode);
        $resultResponse->setMessage($message);
        $resultResponse->setData($data);
        return $resultResponse->toArray();
    }
}