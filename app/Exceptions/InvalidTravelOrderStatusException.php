<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidTravelOrderStatusException extends HttpException
{
    public function __construct()
    {
        $message = "Cannot cancel an approved order";
        parent::__construct(Response::HTTP_BAD_REQUEST, $message);
    }
}
