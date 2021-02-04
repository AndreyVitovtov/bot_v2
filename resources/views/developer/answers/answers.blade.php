@extends("developer.template")

@section("title")
    @lang('pages.answers')
@endsection

@section("h3")
    <h3>@lang('pages.answers')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/answers.css')}}">

    <div class="answers">
        <div>
            <table border="1">
                <tr class="head">
                    <td>
                        №
                    </td>
                    <td>
                        @lang('pages.answer')
                    </td>
                    <td>
                        @lang('pages.question')
                    </td>
                    <td>
                        @lang('pages.method')
                    </td>
                    <td>
                        @lang('pages.menu')
                    </td>
                    <td>
                        @lang('pages.actions')
                    </td>
                </tr>
                @foreach($answers as $answer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $answer['question'] }}
                        </td>
                        <td>
                            {{ $answer['answer'] }}
                        </td>
                        <td>
                            {{ $answer['method'] }}
                        </td>
                        <td>
                            {{ $answer['menu'] }}
                        </td>
                        <td class="actions">
                            <div>
                                <form action="{!! route('edit-answer') !!}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $answer['id'] }}">
                                    <button>
                                        <i class='icon-pen'></i>
                                    </button>
                                </form>

                                <form action="{!! route('delete-answer') !!}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $answer['id'] }}">
                                    <button>
                                        <i class='icon-trash-empty'></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <br>
        <br>
        <form action="{!! route('add-answer') !!}" method="POST">
            @csrf
            <div>
                <label for="question">@lang('pages.answer')</label>
                <input type="text" name="question" id="question">
            </div>
            <div>
                <label for="answer">@lang('pages.question')</label>
                <input type="text" name="answer" id="answer">
            </div>
            <div>
                <label for="method">@lang('pages.method')</label>
                <input type="text" name="method" id="method">
            </div>
            <div>
                <label for="menu">@lang('pages.menu')</label>
                <select name="menu" id="menu">
                    <option value="null">--</option>
                    @foreach($menus as $menu)
                        <option value="{{ str_replace('.json', '', $menu) }}">{{ str_replace('.json', '', $menu) }}</option>
                    @endforeach
                </select>
            </div>
            <br>
            <div>
                <input type="submit" value="@lang('pages.add')" class="button">
            </div>
        </form>
    </div>
@endsection
