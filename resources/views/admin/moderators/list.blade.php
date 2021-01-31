@extends("admin.template")

@section("title")
    @lang('pages.moderators')
@endsection

@section("h3")
    <h3>@lang('pages.moderators')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/moderators.css')}}">
    <div class="overflow-X-auto">
        <table>
            <tr>
                <td>@lang('pages.moderators')</td>
                <td>@lang('pages.actions')</td>
            </tr>
            @foreach($moderators as $moderator)
                <tr>
                    <td>{{ $moderator->name }}</td>
                    <td class="actions">
                        <div>
                            <form action="{{ route('moderators-edit') }}"
                                  method="POST"
                                  id="form-moderators-edit-{{ $moderator->id }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $moderator->id }}">
                                <button form="form-moderators-edit-{{ $moderator->id }}">
                                    <i class='icon-pen'></i>
                                </button>
                            </form>

                            <form action="{{ route('moderators-delete') }}" method="POST" id="form-delete-{{ $moderator->id }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $moderator->id }}">
                                <button form="form-delete-{{ $moderator->id }}">
                                    <i class='icon-trash-empty'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection


