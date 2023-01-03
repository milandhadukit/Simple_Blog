<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\AuthController;
use Validator;
use App\Models\Comment;

class CommentController extends AuthController
{
    //
    public function  addComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
             'body'=>'required',  
             'post_id'=>'required|exists:posts,id',  

            
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
       
        $addcomment = [
            'user_id' => auth()->user()->id,
            'post_id'=>$request->post_id,
            'body' => $request->body,
        ];
        Comment::create($addcomment);
        return $this->sendResponse('success', 'successfully Add');
    }



    public function  addReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
             'body'=>'required',    
             'post_id'=>'required|exists:posts,id',  
             'parent_id'=>'required|exists:posts,id',  
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
       
        $addreply = [
            'user_id' => auth()->user()->id,
            'post_id'=>$request->post_id,
            'parent_id'=>$request->parent_id,
            'body' => $request->body,
        ];
        Comment::create($addreply);
        return $this->sendResponse('success', 'successfully Add');
    }
}
