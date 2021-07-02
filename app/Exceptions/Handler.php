<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($this->isHttpException($e)) {
            switch ($e->getStatusCode()) {
                // not authorized
                case '403':
                    $message = !empty($e->getMessage()) ? $e->getMessage() : 'Not authorized';
                    break;
                // not found
                case '404':
                    $message = !empty($e->getMessage()) ? $e->getMessage() : 'Page not found';
                    break;
                // method is not allowed
                case '405':
                    $message = !empty($e->getMessage()) ? $e->getMessage() : 'Method is not allowed';
                    break;
                // internal error
                case '500':
                    $message = !empty($e->getMessage()) ? $e->getMessage() : 'Internal server error';
                    break;
                // bad gateway
                case '502':
                    $message = !empty($e->getMessage()) ? $e->getMessage() : 'Bad gateway';
                    break;
                default:
                    return $this->renderHttpException($e);
            }
            return response([
                "data" => [],
                "message" => $message,
                "errorCode" => $e->getStatusCode(),
                "errors" => []
            ], $e->getStatusCode());
        } else if ($e instanceof ModelNotFoundException) {
            return response([
                "data" => [],
                "message" => !empty($e->getMessage()) ? $e->getMessage() : 'Data not found',
                "errorCode" => Response::HTTP_NOT_FOUND,
                "errors" => []
            ], Response::HTTP_NOT_FOUND);
        }
        return parent::render($request, $e);
    }
}
