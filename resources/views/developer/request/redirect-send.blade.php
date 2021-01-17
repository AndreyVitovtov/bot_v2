<form action="{{ route('send-request') }}" method="POST" id="send-request">
    @csrf
    <input type="hidden" name="method" value="{{ $method }}">
    <input type="hidden" name="messenger" value="{{ $messenger }}">
    <input type="hidden" name="url" value="{{ $url }}">
    <input type="hidden" name="data" value="{{ $data }}">
    <input type="hidden" name="response" value="{{ $response }}">
</form>

<script>
    window.onload = function() {
        let form = document.getElementById('send-request');
        form.submit();
    }
</script>
