@extends("developer.template")

@section("title")
    @lang('pages.todo')
@endsection

@section("h3")
    <h3>@lang('pages.todo')</h3>
@endsection

@section("main")
    <style>
        .task {
            text-align: left!important;
        }

        table tr td:nth-child(1) {
            width: 50px;
        }

        table tr:nth-child(1) {
            font-weight: normal!important;
            text-align: center;
        }

        table tr td:last-child {
            width: 150px;
            text-align: center;
        }

        label i {
            font-size: 19px;
            color: #3c8dbc;
        }
    </style>

    <label><i class="icon-doc-alt"></i> @lang('pages.to_do')</label>
    <table>
        @php($todoNumber = 1)
        @foreach($todo as $t)
            @if($t->status == '1')
            <tr>
                <td>{{ $todoNumber }}</td>
                <td class="task">{{ $t->title }}</td>
                <td class="actions">
                    <div>
                        <form action="{{ route('todo-to-work') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $t->id }}">
                            <button><i class="icon-fire-1"></i></button>
                        </form>
                        <form action="{{ route('todo-to-performed') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $t->id }}">
                            <button><i class="icon-flag-1"></i></button>
                        </form>
                        <form action="{{ route('todo-delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $t->id }}">
                            <button><i class="icon-trash-empty"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @php($todoNumber++)
            @endif
        @endforeach
    </table>
    <hr>
    <br>
    <label><i class="icon-fire-1"></i> @lang('pages.in_work')</label>
    <table>
        @php($workNumber = 1)
        @foreach($todo as $t)
            @if($t->status == '2')
                <tr>
                    <td>{{ $workNumber }}</td>
                    <td class="task">{{ $t->title }}</td>
                    <td class="actions">
                        <div>
                            <form action="{{ route('todo-to-performed') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $t->id }}">
                                <button><i class="icon-flag-1"></i></button>
                            </form>
                            <form action="{{ route('todo-to-make') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $t->id }}">
                                <button><i class="icon-doc-alt"></i></button>
                            </form>
                            <form action="{{ route('todo-delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $t->id }}">
                                <button><i class="icon-trash-empty"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @php($workNumber++)
            @endif
        @endforeach
    </table>
    <hr>
    <br>
    <label><i class="icon-flag-1"></i> @lang('pages.performed')</label>
    <table>
        @php($performedNumber = 1)
        @foreach($todo as $t)
            @if($t->status == '3')
                <tr>
                    <td>{{ $performedNumber }}</td>
                    <td class="task">{{ $t->title }}</td>
                    <td class="actions">
                        <div>
                            <form action="{{ route('todo-to-work') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $t->id }}">
                                <button><i class="icon-fire-1"></i></button>
                            </form>
                            <form action="{{ route('todo-to-make') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $t->id }}">
                                <button><i class="icon-doc-alt"></i></button>
                            </form>
                            <form action="{{ route('todo-delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $t->id }}">
                                <button><i class="icon-trash-empty"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @php($performedNumber++)
            @endif
        @endforeach
    </table>
    <hr>
    <br>
    <br>
    <form action="{{ route('todo-add') }}" method="POST">
        @csrf
        <label for="add_todo">@lang('pages.task')</label>
        <input type="text" name="title" autofocus>
        <br>
        <br>
        <button class="button">@lang('pages.add')</button>
    </form>
@endsection
