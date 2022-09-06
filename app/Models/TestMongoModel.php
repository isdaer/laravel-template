<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class TestMongoModel extends Model
{
    protected $collection = 'test_mongo_model';

    protected $connection = 'mongodb';
}