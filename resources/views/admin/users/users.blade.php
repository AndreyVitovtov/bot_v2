@extends("admin.template")

@section("title")
    @lang('pages.users')
@endsection

@section("h3")
    <h3>@lang('pages.users')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/users.css')}}">

    <form action="/admin/users/search" method="GET">
        @csrf
        <div class="users">
            <div>
                <label for="search">@lang('pages.users_search_label')</label>
                <input type="text" name="str" id="search" value="@if(!empty($str)){{ $str }}@endif" autofocus>
            </div>
            <div class="block_buttons">
                <input type="submit" value="@lang('pages.search')" class="button">
            </div>
        </div>
    </form>
    <div class="overflow-X-auto">
    <table>
        <tr>
            <td>№</td>
            <td>ID Chat</td>
            <td>@lang('pages.users_username')</td>
            <td>@lang('pages.users_date')</td>
            <td>@lang('pages.users_profile')</td>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user['chat'] }}</td>
                <td>{{ $user['username'] }}</td>
                <td>{{ $user['date'] }} {{ $user['time'] }}</td>
                <td class="actions">
                    <div>
                        <a href="/admin/users/profile/{{ $user['id'] }}">
                            <button><i class='icon-user-8'></i></button>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        @if(empty($user))
            <tr>
                <td colspan="5" style="text-align: center; font-size: 18px; padding: 20px;">
                    @lang('pages.users_no')
                </td>
            </tr>
        @endif
    </table>
    </div>
    {{ $users->links() }}
@endsection
