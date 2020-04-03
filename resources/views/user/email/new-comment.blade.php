<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Новый ответ клиента</title>
</head>
<body>
<p>
    Новый ответ клиента {{ $user->name }} - [ID:{{ $ticket->ticket_id }}] {{ $ticket->title }}.
</p>

<p>Ответ по теме: {{ $ticket->title }}</p>
<p>Сообщение: {{ $ticket->message }}</p>

<p>
    Ссылка на заявку: <a href="{{ route('manager-show-ticket', ['ticket_id' => $ticket->ticket_id]) }}">Ссылка</a>
</p>

</body>
</html>