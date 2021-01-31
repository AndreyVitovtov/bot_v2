@extends("admin.template")

@section("title")
    @lang('pages.moderators_permissions')
@endsection

@section("h3")
    <h3>@lang('pages.moderators_permissions')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/permissions.css')}}">

    <form action="{{ route('moderators-save-permissions') }}" method="POST">
        @csrf
        <div class="overflow-X-auto">
            <table>
                <tr>
                    <td>@lang('pages.moderators')</td>
                    @foreach($permissions as $permission)
                        <td>{{ $permission->name }}</td>
                    @endforeach
                </tr>
                @foreach($moderators as $moderator)
                    <tr>
                        <td>{{ $moderator->name }}</td>
                        @foreach($permissions as $permission)
                            <td class="tac">
                                <input type="checkbox"
                                       name="{{ $moderator->id }}_{{ $permission->id }}"
                                       value="{{ $permission->id }}"
                                @if($moderator->hasPermissionById($permission->id))
                                    checked
                                @endif>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>
        <br>
        <input type="submit" value="@lang('pages.save')" class="button">
    </form>
@endsection


