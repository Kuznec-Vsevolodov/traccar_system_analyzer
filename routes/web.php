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


Route::get('/devices/{id}', 'TraccarController@devices');
Route::get('/get-server-data', 'TraccarController@getServerData');
Route::get('/get-max-speed-by-lesson/{lesson_id}', 'TraccarController@getMaxSpeedByLesson');
Route::get('/get-accelerations/{lesson_id}', 'TraccarController@getRapidAccelerations');
Route::get('/get-brakes/{lesson_id}', 'TraccarController@getHarchBrakes');
Route::get('/get-full-distanse/{lesson_id}', 'TraccarController@getFullDistance');
Route::get('/get-wide-turns/{lesson_id}', 'TraccarController@getWideTurns');
Route::get('/notes/{id}', 'CommentsController@getAllCommentsByLesson');
Route::get('/get-requests', 'RequestsController@index');
Route::get('/lesson_positions/{lesson_id}', 'TraccarController@getLessonPositions');
Route::get('/lesson_current_position/{lesson_id}', 'TraccarController@getCurrentPositionByLesson');

Route::post('/create-lesson', 'LessonController@createLesson');
Route::post('/add-instructor', 'InstructorsController@addInstructor');
Route::post('/add-student', 'StudentsController@addStudent');

Route::get('/report/download/{id}', 'PdfReportController@download');
Route::get('/report', function () {
    return view('pdf.report');
});

Route::post('/add-comment', 'CommentsController@addComment');
Route::post('/add-raiting', 'RatingController@addRaiting');
