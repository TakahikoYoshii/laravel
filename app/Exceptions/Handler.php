<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        // HTTP STATUS CODE 400
        if ($e instanceof BadRequestHttpException) {
            if(\Request::ajax()){
                return \Response::json([
                    'status' => '400',
                    'message' => 'Bad Request'
                ]);
            }
        }

        // HTTP STATUS CODE 401
        if ($e instanceof UnauthorizedHttpException) {
            if(\Request::ajax()){
                return \Response::json([
                    'status' => '401',
                    'message' => 'Unauthorized'
                ]);
            }
        }

        // HTTP STATUS CODE 403
        if ($e instanceof AccessDeniedHttpException) {
            if(\Request::ajax()){
                return \Response::json([
                    'status' => '403',
                    'message' => 'Forbidden'
                ]);
            }
        }

        // HTTP STATUS CODE 404
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            if(\Request::ajax()){
                return \Response::json([
                    'status' => '404',
                    'message' => 'Not Found'
                ]);
            }
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        // else HTTP STATUS CODE 500
        if(\Request::ajax()){
            return \Response::json([
                'status' => '500',
                'message' => 'Internal Server Error'
            ]);
        }
        return parent::render($request, $e);
    }
}
