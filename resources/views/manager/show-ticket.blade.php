@extends('layouts.app')

@section('title', 'Заявка')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    #{{ $ticket->ticket_id }} - {{ $ticket->title }}
                    @if($ticket->status !== 'Close')
                        <a href="{{ route('close-ticket', ['ticket_id' => $ticket->ticket_id]) }}" class="btn btn-danger btn-xs pull-right" role="button">Закрыть</a>
                    @else
                    <a href="{{ route('open-ticket', ['ticket_id' => $ticket->ticket_id]) }}" style="margin-right: 5px" class="btn btn-success btn-xs pull-right" role="button">Открыть</a>
                    @endif
                    <a href="{{ route('process-ticket', ['ticket_id' => $ticket->ticket_id]) }}" style="margin-right: 5px" class="btn btn-primary btn-xs pull-right" role="button">Выполнить</a>
                </div>

                <div class="panel-body">
                    <div class="ticket-info">
                        <p>Сообщение: {{ $ticket->message }}</p>
                        <p>Прикрепленный файл: <a href="{{ asset('uploads/' . $ticket->file ) }}" target="_blank">Файл</a></p>
                        <p>
                            @if ($ticket->status === 'Open')
                                Статус: <span class="label label-success">Открыта</span>
                            @elseif ($ticket->status === 'Process')
                                Статус: <span class="label label-primary">Выполняется</span>
                            @else
                                Статус: <span class="label label-danger">Закрыта</span>
                            @endif
                        </p>
                        <p>Создана: {{ $ticket->created_at }}</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <hr>

                    <div class="comments">
                        @foreach ($comments as $comment)
                            <div class="panel panel-@if($ticket->user->id === $comment->user_id){{"default"}}@else{{"success"}}@endif">
                                <div class="panel panel-heading">
                                    От: {{ $comment->user->name }}
                                    <span class="pull-right">{{ $comment->created_at->difffOrHumans() }}</span>
                                </div>

                                <div class="panel panel-body">
                                    {{ $comment->comment }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($ticket->status !== "Close")
                        <div class="comment-form">
                            <form action="{{ route('add-comment') }}" method="POST" class="form">
                                {{ csrf_field() }}

                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                                <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                                    <textarea rows="10" id="comment" class="form-control" name="comment"></textarea>

                                    @if ($errors->has('comment'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('comment') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Ответить</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
@endsection