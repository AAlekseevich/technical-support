<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function open($ticket_id)
    {
        Ticket::where('ticket_id', $ticket_id)->update(['status' => 'Open']);
        return redirect()->back()->with("status", "Заявка под номером #$ticket_id открыта.");
    }

    public function processTicket($ticket_id)
    {
        Ticket::where('ticket_id', $ticket_id)->update(['status' => 'Process']);
        return redirect()->back()->with("status", "Заявка под номером #$ticket_id взята на выполнение.");
    }

    public function close($ticket_id)
    {
        Ticket::where('ticket_id', $ticket_id)->update(['status' => 'Close']);
        return redirect()->back()->with("status", "Заявка под номером #$ticket_id закрыта.");
    }

    public function show($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments;
        return view('user.show-ticket', ['ticket' => $ticket, 'comments' => $comments]);
    }

    public function showTicket($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments;
        return view('manager.show-ticket', ['ticket' => $ticket, 'comments' => $comments]);
    }

    public function userListTickets()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->get();
        return view('user.tickets-list', ['tickets' => $tickets]);
    }

    public function managerListTickets()
    {
        $tickets = Ticket::all();
        return view('manager.tickets-list', ['tickets' => $tickets]);
    }

    public function create()
    {
        return view('user.create');
    }

    public function newTicket(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'message' => 'required',
        ]);

        $ticket = new Ticket([
            'user_id' => Auth::user()->id,
            'ticket_id' => strtoupper(str_random(8)),
            'title' => $request->input('title'),
            'message' => $request->input('message'),
            'status' => 'Open',
        ]);

        $ticket->save();

        return redirect()->back()->with("status", "Ваша заявка под номером #$ticket->ticket_id открыта.");
    }
}
