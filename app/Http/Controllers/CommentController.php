<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\AuthController;
use Validator;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;

class CommentController extends AuthController
{
    //
    public function addComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required',
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $addcomment = [
            'user_id' => auth()->user()->id,
            'post_id' => $request->post_id,
            'body' => $request->body,
        ];
        Comment::create($addcomment);
        return $this->sendResponse('success', 'successfully Add');
    }

    public function addReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $addreply = [
            'user_id' => auth()->user()->id,
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id,
            'body' => $request->body,
        ];
        Comment::create($addreply);
        return $this->sendResponse('success', 'successfully Add');
    }

    public function viewCommentByPost()
    {
        $commentByPost = Comment::select(
            'comments.post_id',
            'comments.body',
            'posts.title'
        )
            // ->where('comments.post_id', $request->post_id)
            ->whereNull('parent_id')
            ->join('posts', 'posts.id', 'comments.post_id')
            ->get();

        $commentByPostReply = Comment::select(
            'comments.post_id',
            'comments.body',
            'posts.title'
        )
            ->where('comments.parent_id', '!=', 'Null')
            ->join('posts', 'posts.id', 'comments.post_id')
            ->get();

        return $this->sendResponse('success', [
            $commentByPost,
            'Reply' => $commentByPostReply,
        ]);
    }

    public function editComment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'body' => 'required ',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $CommentId = Comment::find($id);
        if (Gate::allows('userId',$CommentId->user_id)) {

            // dd($CommentId->user_id,$CommentId->post_id);

            Comment::
                where('post_id', $CommentId->post_id)
                ->update(['body' => $request->body]);
            return $this->sendResponse('success', 'successfully Update');
        }
        echo 'sorry';

  
    }
}
