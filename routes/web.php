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

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', function () {
    if(Auth::user()->is_manager) {
        return view('manager.tickets-list');
    }
    return view('user.tickets-list');
});

Route::group(['prefix' => 'client'], function () {
    Route::get('/home', 'TicketController@userListTickets')->name('user-list-tickets');
    Route::get('/create', 'TicketController@create')->name('create-ticket');
    Route::post('/new-ticket', 'TicketController@newTicket')->name('new-ticket');
    Route::get('/ticket/{ticket_id}', 'TicketController@show')->name('show-ticket');
    Route::get('/ticket/close/{ticket_id}', 'TicketController@close')->name('close-ticket');
    Route::get('/ticket/open/{ticket_id}', 'TicketController@open')->name('open-ticket');
    Route::post('/comment/add', 'CommentController@add')->name('add-comment');
});

Route::group(['middleware' => 'manager', 'prefix' => 'manager'], function () {
    Route::get('/home', 'TicketController@managerListTickets')->name('manager-list-tickets');
    Route::get('/ticket/{ticked_id}', 'TicketController@showTicket')->name('manager-show-ticket');
    Route::get('/process/{ticked_id}', 'TicketController@processTicket')->name('process-ticket');
});

