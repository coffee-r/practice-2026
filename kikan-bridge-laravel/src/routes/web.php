<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/stored/{userId}', function ($userId) {
    $outputEmail = '';

    return \Illuminate\Support\Facades\DB::select('
        DECLARE @out NVARCHAR(255);
        EXEC GetUserEmailById ?, @out OUTPUT;
        SELECT @out as out;
    ', [$userId]);
});
