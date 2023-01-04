<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group([
    'middleware' => 'api',
    // 'prefix' => 'auth'
], function ($router) {
    
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);  

    Route::post('/add-post', [PostController::class, 'addPost']);
    Route::get('/view-post', [PostController::class, 'viewPost']);  

    Route::post('/add-comment', [CommentController::class, 'addComment']);
    Route::post('/add-reply', [CommentController::class, 'addReply']);
    Route::post('/view-comment', [CommentController::class, 'viewCommentByPost']);

    Route::post('/like', [LikeController::class, 'addLike']);
    Route::post('/unlike', [LikeController::class, 'UnLike']);
    Route::post('/like-count', [LikeController::class, 'likeCount']);
    Route::post('/unlike-count', [LikeController::class, 'unLikeCount']);







    Route::get('/cache', function () {
        Artisan::call('route:clear');
        Artisan::call('route:cache');
        echo "Done";
        exit;
    });
    
});



// $sets = Comment::select('body')->where('post_id', $request->post_id)->whereNull('parent_id')->get(); //get commnet for post id

// $name=Post::where('id',$request->post_id)->get(); //name get for post id

// dd($name,$sets); 