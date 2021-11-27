<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Throwable;
use App\Traits\Helpers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if (App::environment('production')) {
            if (app()->bound('sentry') && $this->shouldReport($exception)) {
                app('sentry')->captureException($exception);
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return Helpers::apiResponse(false, 'Endpoint Not Found', [], 404);
        }

        if ($exception instanceof ModelNotFoundException) {
            return Helpers::apiResponse(false, 'Record Not Found', [], 404);
        }

        if ($exception instanceof ValidationException) {
            return Helpers::apiResponse(false, 'Validation Error', $this->transformErrors($exception), $exception->status);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return Helpers::apiResponse(false, 'Method Not Allowed', [], 405);
        }

        if ($exception instanceof AccessDeniedHttpException) {
            return Helpers::apiResponse(false, 'Access Denied', [], 403);
        }

        if ($exception instanceof AuthenticationException) {
            return Helpers::apiResponse(false, 'Unauthenticated', [], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return Helpers::apiResponse(false, 'Unauthenticated', [], 401);
        }

        if ($exception instanceof HttpExceptionInterface) {
            return Helpers::apiResponse(false, $exception->getMessage(), [], $exception->getStatusCode());
        }

        return Helpers::apiResponse(false, $exception->getMessage(), [], 500);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return Helpers::apiResponse(false, 'Unauthenticated', [], 401);
    }

    private function transformErrors(ValidationException $exception): array
    {
        $errors = [];
        if (is_array($exception->errors())) {
            foreach ($exception->errors() as $field => $message) {
                $errors[] = [
                    'field' => $field,
                    'message' => $message[0],
                ];
            }
        }

        return $errors;
    }
}
