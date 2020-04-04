<?php

namespace App\Http\Controllers;

use App\AppMailer;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->isManager() ? null : Auth::user()->id;
        $filter = $request->only(['sort_col', 'sort_type']);
        $tickets = (new Ticket())->getDataForTable($userId, $filter);
        return view('home', ['tickets' => $tickets, 'filter' => $filter, 'user' => Auth::user()]);
    }

    /**
     * @param $ticket_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function open($ticket_id)
    {
        Ticket::where('ticket_id', $ticket_id)->update(['status' => 'Open']);
        return redirect()->back()->with("status", "Заявка под номером #$ticket_id открыта.");
    }


    /**
     * @param $ticket_id
     * @param $manager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processTicket($ticket_id)
    {
        $manager = Auth::user();
        Ticket::where('ticket_id', $ticket_id)->update(['status' => 'Process', 'manager_email' => $manager->email]);
        return redirect()->back()->with("status", "Заявка под номером #$ticket_id взята на выполнение.");
    }

    /**
     * @param $ticket_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close($ticket_id, AppMailer $mailer)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->first();
        $ticket->update(['status' => 'Close']);
        $mailer->sendCloseTicket($ticket);
        return redirect()->back()->with("status", "Заявка под номером #$ticket_id закрыта.");
    }

    /**
     * @param $ticket_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        return view('user.show-ticket', ['ticket' => $ticket, 'comments' => $ticket->comments]);
    }

    /**
     * @param $ticket_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTicket($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        return view('manager.show-ticket', ['ticket' => $ticket, 'comments' => $ticket->comments]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function newTicket(Request $request, AppMailer $mailer)
    {
        $lastTicket = Ticket::where('user_id', Auth::user()->id)
            ->where('created_at', '>', Carbon::now()->subMinutes(Config::get('constants.MINUTES')))
            ->first();
        if ($lastTicket) {
            return redirect()->back()->with("status", "Заявку можно подавать только раз в сутки.");
        }

        $manager = User::where('is_manager', '=', '1')->first();

        $this->validate($request, [
            'title' => 'required',
            'message' => 'required',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('file', ['disk' => 'public']);
        }

        $ticket = new Ticket([
            'user_id' => Auth::user()->id,
            'ticket_id' => strtoupper(str_random(8)),
            'title' => $request->input('title'),
            'message' => $request->input('message'),
            'manager_email' => $manager->email,
            'file' => isset($path) ? $path : '',
            'status' => 'Open',
        ]);

        $ticket->save();

        $mailer->sendNewTicket(Auth::user(), $ticket, $manager);

        return redirect()->back()->with("status", "Ваша заявка под номером #$ticket->ticket_id открыта.");
    }
}
