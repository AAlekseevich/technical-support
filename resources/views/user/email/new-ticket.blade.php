<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Новое обращение клента</title>
</head>
<body>
<p>
    Новое обращение клиента {{ $user->name }} - [ID:{{ $ticket->ticket_id }}] {{ $ticket->title }}.
</p>

<p>Тема: {{ $ticket->title }}</p>
<p>Сообщение: {{ $ticket->message }}</p>

<p>
    Ссылка на заявку: <a href="{{ route('manager-show-ticket', ['ticket_id' => $ticket->ticket_id]) }}">Ссылка</a>
</p>

</body>
</html>