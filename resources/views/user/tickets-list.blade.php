@extends('layouts.app')

@section('title', 'Моя заявки')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-ticket"> Мои заявки</i>
                </div>

                <div class="panel-body">
                        @if (empty($tickets))
                            <p>У Вас нет созданных заявок.</p>
                        @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Тема</th>
                                <th>ID заявки</th>
                                <th>Статус</th>
                                <th>Последнее редактирование</th>
                                <th>Действие</th>

                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                <tr>
                                    <td>
                                        {{ $ticket->title }}
                                    </td>
                                    <td>
                                        <a href="{{ route('show-ticket', ['ticket_id' => $ticket->ticket_id]) }}">
                                            #{{ $ticket->ticket_id }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($ticket->status === 'Open')
                                            <span class="label label-success">Открыта</span>
                                        @elseif ($ticket->status === 'Process')
                                            <span class="label label-primary">Выполняется</span>
                                        @else
                                            <span class="label label-danger">Закрыта</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->updated_at }}</td>
                                    <td>
                                        @if ($ticket->status === 'Open' || $ticket->status === 'Process')
                                            <a style="text-decoration: unset;" href="{{ route('close-ticket', ['ticket_id' => $ticket->ticket_id]) }}"><span class="label label-danger">Закрыть</span></a>
                                        @else
                                            <a style="text-decoration: unset;" href="{{ route('open-ticket', ['ticket_id' => $ticket->ticket_id]) }}"><span class="label label-success">Открыть</span></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection