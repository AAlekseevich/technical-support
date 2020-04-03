<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Новый ответ менеджера</title>
</head>
<body>
<p>
    Новый ответ менеджера {{ $manager->name }} - [ID:{{ $ticket->ticket_id }}] {{ $ticket->title }}.
</p>

<p>Ответ по теме: {{ $ticket->title }}</p>
<p>Сообщение: {{ $ticket->message }}</p>

<p>
    Ссылка на заявку: <a href="{{ route('show-ticket', ['ticket_id' => $ticket->ticket_id]) }}">Ссылка</a>
</p>

</body>
</html>