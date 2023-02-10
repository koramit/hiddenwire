<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chats', function () {
    return App\Models\SimplifiedEvent::query()
        ->with('user', 'group')
        ->orderBy('timestamp')
        ->get()
        ->transform(function ($event) {
            return [
                'type' => $event->type,
                'user' => $event->user->name,
                'group' => $event->group->name,
                'timestamp' => $event->timestamp->format('Y-m-d H:i:s'),
                'message' => $event->message,
            ];
        });
});
