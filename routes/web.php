<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/db-test', function () {
    return DB::select('SELECT DATABASE() as db');
});
?>