<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
*/

Route::get('/posts', [App\Http\Controllers\PostController::class, 'posts']);
Route::get('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'index']);
Route::post('/posts/{post}/comment', [App\Http\Controllers\CommentController::class, 'store']);
// Route::post('/postcomment', [App\Http\Controllers\CommentController::class, 'postComment']);

Route::middleware('auth:api')->group(function () {
});

