<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Новый ответ менеджера</title>
</head>
<body>
<p>
    Обращение [ID:{{ $ticket->ticket_id }}] {{ $ticket->title }} - закрыто.
</p>

<p>Клиент закрыл обращение</p>

<p>
    Ссылка на заявку: <a href="{{ route('show-ticket', ['ticket_id' => $ticket->ticket_id]) }}">Ссылка</a>
</p>

</body>
</html>