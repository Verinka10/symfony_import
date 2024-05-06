<?php 
namespace components\exceptions;


class NotFoundHttpException extends HttpException {
    
    public function __construct(string $message = 'Not found http request', ?\Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(404, $message, $previous, $headers, $code);
    }
}

