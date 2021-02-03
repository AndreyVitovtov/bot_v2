@extends("developer.template")

@section("title")
    @lang('pages.send_request')
@endsection

@section("h3")
    <h3>@lang('pages.send_request')</h3>
@endsection

@section("main")
    <style>
        textarea {
            width: 100%;
            height: 100px;
            resize: none;
            border: solid 1px #ddd;
        }

        iframe {
            height: 390px;
        }
    </style>

    <form action="{{ route('get-response') }}" method="POST">
        @csrf
        <label>@lang('pages.headers')</label>
        <br>
        <div>
            <input type="checkbox" name="messenger" value="viber" id="viber"
                @if((isset($messenger) && $messenger == 'viber'))
                    checked
                @endif
            >
            <label for="viber" class="cursor-pointer">Viber</label>
        </div>
        <br>
        <label>@lang('pages.options')</label>
        <br>
        <div>
            <input type="radio" name="options" value="none" class="options" id="none" checked>
            <label for="none" class="cursor-pointer">None</label>

            <input type="radio" name="options" value="migrate" class="options" id="migrate">
            <label for="migrate" class="cursor-pointer">Migrate</label>

            <input type="radio" name="options" value="seed" class="options" id="seed">
            <label for="seed" class="cursor-pointer">Seed</label>

            <input type="radio" name="options" value="webhook" class="options" id="webhook">
            <label for="webhook" class="cursor-pointer">Webhook</label>
        </div>
        <div class="hidden" id="options_webhook">
            <br>
            <label>Webhook</label>
            <br>
            <input type="radio" name="type" value="telegram" class="type" id="type_telegram" checked>
            <label for="type_telegram" class="cursor-pointer">Telegram</label>
            <input type="radio" name="type" value="viber" class="type" id="type_viber">
            <label for="type_viber" class="cursor-pointer">Viber</label>
            <br>
            <br>
            <label for="token">Token</label>
            <input type="text" name="token" id="token">
        </div>
        <br>
        <label>@lang('pages.method')</label>
        <br>
        <div>
            <input type="radio" name="method" value="post" id="post"
            @if((isset($method) && $method == 'post') || !isset($method))
                checked
            @endif
            class="method">
            <label for="post" class="cursor-pointer">POST</label>
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="method" value="get" id="get"
            @if(isset($method) && $method == 'get')
               checked
            @endif
            class="method">
            <label for="get" class="cursor-pointer">GET</label>
        </div>
        <br>
        <div>
            <label for="url">Url</label>
            <input type="text" name="url" value="{{ isset($url) ? $url : route('bot-request-handler') }}" id="url">
            <input type="hidden" name="old_url" value="{{ isset($url) ? $url : route('bot-request-handler') }}" id="old_url">
        </div>
        <br>
        <div>
            <label for="request">@lang('pages.request')</label>
            <textarea name="data" id="request">{{ $data }}</textarea>
        </div>
        <br>
        <button class="button">@lang('pages.send')</button>
    </form>
    @if(isset($response) && $response != null)
        <br>
        <br>
        <label>@lang('pages.response')</label>
        <br>
        @if(substr($response, 0, 1) == '{')
            <div id="json-renderer" class="json-body"></div>
            <script>
                let json = '{!! str_replace('OK', '', $response) !!}';
                console.log(json);
                $('#json-renderer').jsonBrowse(JSON.parse(json), {withQuotes: true});
            </script>
        @else
            <iframe src="{{ url('html/response.html') }}" width="100%"></iframe>
        @endif
    @endif

    <script>
        $('body').on('change', '.options', function() {
            let url = $('#old_url').val();
            $('#url').val(url);
            let radio = $(this).val();
            if(radio === 'webhook') {
                $('#url').prop('disabled', true);
                $('#get').prop('disabled', true);
                $('#post').prop('disabled', true);
                $('textarea').prop('disabled', true);
                $('#options_webhook').toggle();
                $('#token').focus();
                $('#token').val('{{ $telegramToken }}');
            }
            else {
                if($('div #options_webhook').is((':visible'))) {
                    $('#options_webhook').toggle();
                    $('#url').prop('disabled', false);
                    $('#get').prop('disabled', false);
                    $('#post').prop('disabled', false);
                    $('textarea').prop('disabled', false);
                }
                if(radio === 'none') {
                    $('#post').prop('checked', true);
                    $('textarea').prop('disabled', false);
                    return;
                }
                let old_url = $('#old_url').val();
                $('#old_url').val(old_url);
                $('#get').prop('checked', true);
                $('textarea').prop('disabled', true);
                $('#url').val('{{ url('') }}/'+radio);
            }
        });

        $('body').on('change', '.type', function() {
            let type = $(this).val();
            if(type == 'telegram') {
                $('#token').val('{{ $telegramToken }}');
            }
            else if(type == 'viber') {
                $('#token').val('{{ $viberToken }}');
            }
            $('#token').focus();
        });

        let method = $('input[name=method]:checked').val();

        if(method == 'get') {
            $('textarea').prop('disabled', true);
        }
        $('body').on('change', '.method', function() {
            method = $('input[name=method]:checked').val();

            if(method == 'get') {
                $('textarea').prop('disabled', true);
            }
            else {
                $('textarea').prop('disabled', false);
            }
        });
    </script>
@endsection
