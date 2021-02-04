@extends("developer.template")

@section("title")
    @lang('pages.edit_question')
@endsection

@section("h3")
    <h3>@lang('pages.edit_question')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/answers.css')}}">

    <form action="{!! route('save-answer') !!}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $answer->id }}">
        <div class="answers">
            <div>
                <label for="question">@lang('pages.answers_add_option_question')</label>
                <input type="text" name="question" value="{{ $answer->question }}" id="question">
            </div>
            <div>
                <label for="answer">@lang('pages.answers_add_answer_bot')</label>
                <input type="text" name="answer" value="{{ $answer->answer }}" id="answer">
            </div>
            <div>
                <label for="method">@lang('pages.method')</label>
                <input type="text" name="method" value="{{ $answer->method }}" id="method">
            </div>
            <div>
                <label for="menu">@lang('pages.menu')</label>
                <select name="menu" id="menu">
                    <option value="null">--</option>
                    @foreach($menus as $menu)
                        <option value="{{ str_replace('.json', '', $menu) }}"
                        @if($answer->menu == str_replace('.json', '', $menu)) selected @endif
                        >{{ str_replace('.json', '', $menu) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="block_buttons">
                <input type="submit" value="@lang('pages.save')" class="button">
            </div>
        </div>
    </form>
@endsection
