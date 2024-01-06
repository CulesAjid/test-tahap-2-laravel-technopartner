<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (ValidationException $e, $request) {
            $error = config('response.invalid');
            $error['data'] = $e->errors();

            return response()->json($error, 400);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            $error = empty($e->getMessage()) ? config('response.url_unreachable') : config('response.not_found');
            return response()->json($error, $e->getStatusCode());
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json(config('response.method_not_allowed'), 405);
        });


        $this->reportable(function (QueryException $e) {
            $error = config('response.query');
            $error['message'] = $e->getMessage();

            return response()->json($error, 500);
        });

        $this->reportable(function (Exception $e) {
            $error = config('response.error');
            $error['message'] = $e->getMessage();

            return response()->json($error, 500);
        });

        $this->reportable(function (Throwable $e) {
            $error = config('response.error');
            $error['message'] = $e->getMessage();

            return response()->json($error, 500);
        });
    }
}
