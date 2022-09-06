<?php

namespace App\Http\Controllers;

use App\Exports\TestExport;
use App\Imports\TestImport;
use App\Models\TestModel;
use App\Models\TestMongoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function mysqlTest()
    {
        $model = TestModel::query()->first();
        return $this->success($model);
    }

    public function mongoTest()
    {
        $model = TestMongoModel::query()->first();
        return $this->success($model);
    }

    public function redisTest()
    {
        $test = Redis::get('test');
        return $this->success($test);
    }

    public function export()
    {
        $testArr = [
            [1, 2, 3, 1, 2, 3, 1, 2, 3]
        ];
        return new TestExport($testArr);
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $data = (new TestImport())->setHighestColumn('1')->importExcel($file);
        return $this->success($data);
    }
}