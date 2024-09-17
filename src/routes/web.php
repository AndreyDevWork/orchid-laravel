<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $notification = new \App\Notifications\NewsForOperator(
        'Новый оператор в штат',
        'Мы наняли нового неверойтного оператора в штат'
    );
    \App\Models\User::find(2)->notify($notification);
    Notification::send(\App\Models\User::all(), $notification);
    return view('welcome');
});
