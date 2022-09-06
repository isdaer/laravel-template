<?php

Route::get('/mysql-test', 'TestController@mysqlTest');
Route::get('/mongo-test', 'TestController@mongoTest');
Route::get('/redis-test', 'TestController@redisTest');
Route::get('/export', 'TestController@export');
Route::post('/import', 'TestController@import');

