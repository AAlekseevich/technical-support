<?php

namespace App\Http\Controllers;

use App\AppMailer;
use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function add(Request $request, AppMailer $mailer)
    {

        $this->validate($request, [
            'comment' => 'required',
        ]);

        $comment = new Comment([
            'ticket_id' => $request->input('ticket_id'),
            'user_id' => Auth::user()->id,
            'comment' => $request->input('comment'),
            'file' => $request->input('file'),
        ]);

        $comment->save();

        if ($comment->ticket->user->id !== Auth::user()->id) {
            $mailer->sendManagerComment($comment->ticket->user, Auth::user(), $comment->ticket, $comment);
        } else {
            $manager = User::where('is_manager', '=', '1')->first();
            $mailer->sendUserComment(Auth::user(), $comment->ticket, $comment);
        }

        return redirect()->back();
    }
}
