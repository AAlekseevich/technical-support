<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function add(Request $request)
    {

        $this->validate($request, [
            'comment' => 'required',
        ]);

        $comment = new Comment([
            'ticket_id' => $request->input('ticket_id'),
            'user_id' => Auth::user()->id,
            'comment' => $request->input('comment'),
        ]);

        $comment->save();

        return redirect()->back()->with("status", "Ваш ответ добавлен");
    }
}
