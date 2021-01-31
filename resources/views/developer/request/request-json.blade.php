@extends("developer.template")

@section("title")
    @lang('pages.request')
@endsection

@section("h3")
    <h3>Request</h3>
@endsection

@section("main")
    <style>
        textarea {
            width: 100%;
            height: 100px;
            resize: none;
            border: solid 1px #ddd;
        }
    </style>
    <code id="json-renderer" class="json-body"></code>
<br>
<br>
<textarea id="json"></textarea>
<br>
<br>

@php
    $jsonFull = json_encode(json_decode($json));
@endphp

<form action="{{ route('send-request') }}" method="POST" id="send_request">
    @csrf
    <input type="hidden" name="data" value="{{ $json }}">
</form>
<button onclick="Copy()" class="button">@lang('pages.copy_text')</button>
<button form="send_request" class="button">@lang('pages.send_request')</button>

<script>
    let jsonFull = '{!! $jsonFull !!}';
    $('#json-renderer').jsonBrowse(JSON.parse(jsonFull), {withQuotes: true});
    $('textarea').val(jsonFull);

    function Copy() {
        let copyText = document.getElementById("json");
        copyText.select();
        document.execCommand("copy");
    }
</script>
@endsection
