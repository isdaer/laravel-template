<?php

namespace App\Api;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionReport
{
    use ApiResponse;

    /**
     * @var Exception
     */
    public $exception;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var
     */
    protected $report;

    /**
     * ExceptionReport constructor.
     *
     * @param Request $request
     * @param Exception $exception
     */
    function __construct(Request $request, Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    /**
     * @var array
     */

    public $doReport = [
        AuthenticationException::class => ['未授权', 210],
        ModelNotFoundException::class => ['该模型未找到', 404],
        AuthorizationException::class => ['没有此权限', 403],
        ValidationException::class => ['数据验证失败', 320],
        UnauthorizedHttpException::class => ['未登录或登录状态失效', 422],
        NotFoundHttpException::class => ['没有找到该页面', 404],
        MethodNotAllowedHttpException::class => ['访问方式不正确', 405],
        QueryException::class => ['参数错误', 401],
    ];

    public function register($className, callable $callback)
    {

        $this->doReport[$className] = $callback;
    }

    /**
     * @return bool
     */
    public function shouldReturn()
    {
        foreach (array_keys($this->doReport) as $report) {
            if ($this->exception instanceof $report) {
                $this->report = $report;

                if ($this->exception == QueryException::class) {
                    Log::error($this->exception->getMessage(), [
                        'exception' => get_class($this->exception),
                        'file' => $this->exception->getFile(),
                        'line' => $this->exception->getLine(),
                        'trace' => collect($this->exception->getTrace())->map(function ($trace) {
                            return Arr::except($trace, ['args']);
                        })->all(),
                    ]);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param Exception $e
     * @return static
     */
    public static function make(Exception $e)
    {

        return new static(\request(), $e);
    }

    /**
     * @return mixed
     */
    public function report()
    {
        if ($this->exception instanceof ValidationException) {
            $error = array_first($this->exception->errors());

            return $this->failed($this->exception->status, array_first($error));
        }
        $message = $this->doReport[$this->report];

        return $this->failed($message[1], $message[0]);
    }

    public function prodReport()
    {
        return $this->failed('500', '服务器错误');
    }

    public function debugReport(Exception $exception)
    {
        $data = [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];

        return $this->message('500', $exception->getMessage(), $data);
    }
}