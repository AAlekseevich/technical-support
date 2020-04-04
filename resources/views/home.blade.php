@extends('layouts.app')

@section('title', 'Моя заявки')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-ticket">@if($user->isManager()) Обращения @else Мои заявки @endif</i>
                </div>

                <div class="panel-body">
                    @if (empty($tickets))
                        <p>У Вас нет созданных заявок.</p>
                    @else
                        <table class="table">
                            <thead>
                            @php
                                $downArrow = '<svg class="bi bi-caret-down-fill" width="10px" height="10px"
                                    viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 01.753 1.659l-4.796 5.48a1 1 0 01-1.506 0z"/></svg>';
                                $upArrow = '<svg class="bi bi-caret-up-fill" width="10px" height="10px"
                                    viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.247 4.86l-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 00.753-1.659l-4.796-5.48a1 1 0 00-1.506 0z"/></svg>';
                            @endphp
                            <tr>
                                @php
                                    $theads = [
                                        'title' => 'Тема',
                                        'ticket_id' => 'ID заявки',
                                        'status' => 'Статус',
                                        'updated_at' => 'Последнее редактирование',
                                    ];
                                @endphp
                                @foreach($theads as $key => $item)
                                    <th><a href="{{ route('home', ['sort_col' => $key, 'sort_type' => $sort = $key == $filter['sort_col'] ? $filter['sort_type'] ? 0 : 1 : 0] ) }}">@php echo $sort ? $upArrow : $downArrow @endphp {{ $item }}</a></th>
                                @endforeach
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
                                            <a style="text-decoration: unset;" href="{{ route('close-ticket', ['ticket_id' => $ticket->ticket_id]) }}"><span class="btn btn-danger btn-sm">Закрыть</span></a>
                                        @else
                                            <a style="text-decoration: unset;" href="{{ route('open-ticket', ['ticket_id' => $ticket->ticket_id]) }}"><span class="btn btn-success btn-sm">Открыть</span></a>
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
