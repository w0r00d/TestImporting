<?php

use App\Livewire\Importer;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/imp', Importer::class);
