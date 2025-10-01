<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/test-home', function () {
    return Inertia::render('SimpleHome');
});
