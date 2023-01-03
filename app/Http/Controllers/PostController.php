<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Models\Post;

class PostController extends AuthController
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function  addPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
             'title' => 'required|string|between:1,200',
             'body'=>'required',    
            
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        if (Auth::user()->role != 'Admin') {
            return $this->wrongPass('Sorry', ' Sorry Not Access');
        }
        $addPost = [
            'title' => $request->title,
            'body' => $request->body,
        ];
        Post::create($addPost);
        return $this->sendResponse('success', 'successfully Add');
    }

    public function  viewPost()
    {
    
        $viewPost=Post::get();
        return $this->sendResponse('success',  $viewPost);
    }
}
