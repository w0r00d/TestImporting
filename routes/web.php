<?php

use App\Livewire\Emp;
use App\Livewire\Importer;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/imp', Importer::class);
Route::get('/emp', Emp::class);
