<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiExceptionHandler;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Contracts\Container\Container;

class Handler extends ExceptionHandler
{
    use ApiExceptionHandler;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function register(): void
    {
        // Register the exception handling logic here if needed
    }

    public function render($request, Throwable $e)
    {
        return $this->handleApiException($e, $request);
    }
}
