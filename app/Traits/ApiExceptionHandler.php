<?php

namespace App\Traits;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait ApiExceptionHandler
{
    protected function errorResponse($message, $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ], $code);
    }

    protected function handleApiException(Throwable $e, Request $request): JsonResponse
    {
        if ($e instanceof ThrottleRequestsException) {
            $limitRequest = $e->getHeaders()['X-RateLimit-Limit'];
            $retryAfter = $e->getHeaders()['Retry-After'];
            return $this->errorResponse("Demasiados intentos, solo puede hacer $limitRequest cada minuto, reintente en $retryAfter segundos", 429);
        }

        if ($e instanceof ValidationException) {
            return $this->errorResponse($e->validator->errors()->first(), 422);
        }

        if ($e instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($e->getModel()));
            return $this->errorResponse("No existe resultados de $model", 404);
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->errorResponse("No existe la ruta especificada", 404);
        }

        if ($e instanceof AuthenticationException) {
            return $this->errorResponse('No autenticado', 401);
        }

        if ($e instanceof AuthorizationException) {
            return $this->errorResponse('No posee permisos para esta acción', 403);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('No es valido este verbo HTTP para esta ruta', 405);
        }

        if ($e instanceof QueryException && $e->errorInfo[1] === 1451) {
            return $this->errorResponse('Este recurso ya esta relacionado con otros', 409);
        }

        return $this->errorResponse('Error interno del servidor', 500);
    }
}
