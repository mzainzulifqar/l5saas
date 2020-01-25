<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
$real_path = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'sparky' . DIRECTORY_SEPARATOR;

Route::get('/testing', function ()
{
	// /testing?color=gold
});

Auth::routes();

include_once($real_path . 'sparky.php');
