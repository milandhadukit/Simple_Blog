<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Validator;
use App\Models\Like;

class LikeController extends AuthController
{
    public function addLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            // 'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        // if((int)auth()->user()->id !== (int)$request->user_id){
        //     return response()->json('invalid user id');
        // }

        $Like = Like::where('post_id', $request->post_id)
            ->where('user_id', auth()->user()->id)
            ->count();

        if ($Like > 0) {
            Like::where('post_id', $request->post_id)
                ->where('user_id', auth()->user()->id)
                ->update([
                    'like' => 1,
                    'unlike' => 0,
                ]);
            return $this->sendResponse('success', ' Like Done');
        } else {
            Like::where('post_id', $request->post_id)
                ->where('user_id', auth()->user()->id)
                ->create([
                    'like' => 1,
                    'post_id' => $request->post_id,
                    'user_id' => auth()->user()->id,
                ]);
            return $this->sendResponse('success', 'successfully Like Add');
        }
    }

    public function UnLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $Like = Like::where('post_id', $request->post_id)
            ->where('user_id', auth()->user()->id)
            ->count();

        if ($Like > 0) {
            Like::where('post_id', $request->post_id)
                ->where('user_id', auth()->user()->id)
                ->update([
                    'unlike' => 1,
                    'like' => 0,
                ]);
            return $this->sendResponse('success', ' UnLike Done');
        } else {
            Like::where('post_id', $request->post_id)->create([
                'unlike' => 1,
                'post_id' => $request->post_id,
                'user_id' => auth()->user()->id,
            ]);
            return $this->sendResponse('success', 'successfully UnLike');
        }
    }

    public function likeCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $countLike=Like::where('post_id','=',$request->post_id)
        ->where('like',1)
        ->count();
        return $this->sendResponse('success', ['Total Like'=>$countLike]);

    }

    public function unLikeCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $countLike=Like::where('post_id','=',$request->post_id)
        ->where('unlike',1)
        ->count();
        return $this->sendResponse('success', ['Total UnLike'=>$countLike]);

    }


}
