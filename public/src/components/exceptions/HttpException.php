<?php 
namespace components\exceptions;

class HttpException extends \Exception implements \Throwable {
    
    private int $statusCode;
    private array $headers;
    
    public function __construct(int $statusCode, string $message = '', ?\Throwable $previous = null, array $headers = [], int $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        
        parent::__construct($message, $code, $previous);
    }
}

