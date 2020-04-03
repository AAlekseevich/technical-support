<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 03.04.2020
 * Time: 15:06
 */

namespace App;


use Illuminate\Contracts\Mail\Mailer;

class AppMailer
{
    protected $mailer;
    protected $fromAdress = 'manager@support.loc';
    protected $fromName = 'Technical Support';
    protected $to;
    protected $subject;
    protected $view;
    protected $data = [];

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewTicket($user, Ticket $ticket, $manager)
    {
        $this->to = $manager->email;
        $this->subject = "Новое обращение клента: [ID:$ticket->ticket_id] $ticket->title";
        $this->view = 'user.email.new-ticket';
        $this->data = compact('user', 'ticket');

        return $this->deliver();
    }

    public function sendUserComment($user, Ticket $ticket, Comment $comment)
    {
        $this->to = $ticket->manager_email;
        $this->subject = "Новые ответ клиента - [ID:$ticket->ticket_id] $ticket->title";
        $this->view = 'user.email.new-comment';
        $this->data = compact(  'user', 'ticket', 'comment');

        return $this->deliver();
    }

    public function sendManagerComment($user, $manager, Ticket $ticket, Comment $comment)
    {
        $this->to = $user->email;
        $this->subject = "Новые ответ менеджера - [ID:$ticket->ticket_id] $ticket->title";
        $this->view = 'manager.email.new-comment';
        $this->data = compact(  'manager','ticket', 'comment');

        return $this->deliver();
    }

    public function sendCloseTicket(Ticket $ticket)
    {
        $this->to = $ticket->manager_email;
        $this->subject = "Клиент закрыл заявку [ID:$ticket->ticket_id] $ticket->title";
        $this->view = 'user.email.close-ticket';
        $this->data = compact(  'ticket');

        return $this->deliver();
    }

    public function deliver(){
        $this->mailer->send($this->view, $this->data, function($message) {
            $message->from($this->fromAdress, $this->fromName)
                ->to($this->to)->subject($this->subject);
        });
    }

}