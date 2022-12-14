<?php

namespace App\Exceptions;

use App\Api\ExceptionReport;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $reporter = ExceptionReport::make($exception);
        if ($reporter->shouldReturn()) {
            return $reporter->report();
        }
        //未知异常处理
        if (config('app.debug')) {
            return $reporter->debugReport($exception);
            //return parent::render($request, $exception);
        } else {
            //线上环境,未知错误，则显示500
            return $reporter->prodReport();
        }
    }
}
