<?php

namespace App\Api;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ApiResponse
{
    protected $statusCode = FoundationResponse::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respond($data, $header = [])
    {
        return Response::json($data, $this->getStatusCode(), $header);
    }

    public function status($status, $data = [], $code = null)
    {
        if (!is_null($code)) {
            $this->setStatusCode($code);
        }
        $data = array_merge($status, $data);

        return $this->respond($data);
    }

    public function failed($code = 400, $message = '', $httpStatusCode = FoundationResponse::HTTP_OK)
    {

        return $this->setStatusCode($httpStatusCode)->message($code, $message);
    }

    public function message($code, $message = '', $data = [])
    {
        if (empty($message)) {
            if (array_key_exists($code, ApiResponseCode::$responseCodeMap)) {
                $message = ApiResponseCode::$responseCodeMap[$code];
            } elseif (array_key_exists($code, FoundationResponse::$statusTexts)) {
                $message = FoundationResponse::$statusTexts[$code];
            }
        }
        $XCmdrCode = $code;
        $XCmdrMessage = $message;

        return $this->status(compact('XCmdrCode', 'XCmdrMessage'), $data);
    }

    public function success($data = null, $code = ApiResponseCode::SUCCESS, $message = '')
    {
        if ($data instanceof ResourceCollection) {
            $resource = $data->resource;
            switch ($resource) {
                case $resource instanceof LengthAwarePaginator:
                    $XCmdrResult = [
                        config('app.key_data_list') => $data->jsonSerialize(),
                        config('app.key_total_item_count') => $resource->total(),
                        config('app.key_total_page_count') => $resource->lastPage(),
                    ];
                    foreach ($data->additional as $key => $item) {
                        $XCmdrResult[$key] = $item;
                    }
                    break;
                case  $resource instanceof Collection:
                    $XCmdrResult = [
                        config('app.key_data_list') => $data->jsonSerialize(),
                    ];
                    break;
            }
        } else {
            if ($data instanceof LengthAwarePaginator) {
                $XCmdrResult = [
                    config('app.key_data_list') => $data->items(),
                    config('app.key_total_item_count') => $data->total(),
                    config('app.key_total_page_count') => $data->lastPage(),
                ];

            } else {
                $XCmdrResult = $data;
            }
        }

        return $this->message($code, $message, compact('XCmdrResult'));
    }
}