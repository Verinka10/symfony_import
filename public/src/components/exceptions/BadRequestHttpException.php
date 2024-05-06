<?php 
namespace components\exceptions;


class BadRequestHttpException extends HttpException {
    
    public function __construct(string $message = 'Bad request', ?\Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(400, $message, $previous, $headers, $code);
    }
}

