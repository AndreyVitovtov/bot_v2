@extends("developer.template")

@section("title")
    @lang('pages.info')
@endsection

@section("h3")
    <h3>@lang('pages.info')</h3>
@endsection

@section("main")
    <form action="{{ route('info-save') }}" method="POST">
        @csrf
        <div>
            <label for="db_address">@lang('pages.db_address')
                @if(isset($info->db_address) && $info->db_address != '')
                    <a href="{{ $info->db_address }}" class="link" target="_blank"><i class="icon-forward-1"></i></a>
                @endif
            </label>
            <input type="text" name="db_address" value="{{ $info->db_address ?? '' }}" id="db_address">
        </div>
        <div>
            <label for="db_login">@lang('pages.db_login')</label>
            <input type="text" name="db_login" value="{{ $info->db_login ?? '' }}" id="db_login">
        </div>
        <div>
            <label for="db_password">@lang('pages.db_password')</label>
            <input type="text" name="db_password" value="{{ $info->db_password ?? '' }}" id="db_password">
        </div>
        <div>
            <label for="db_name">@lang('pages.db_name')</label>
            <input type="text" name="db_name" value="{{ $info->db_name ?? '' }}" id="db_name">
        </div>
        <div>
            <label for="ftp_type">@lang('pages.ftp_type')</label>
            <select name="ftp_type" id="ftp_type">
                <option value="FTP"
                    @if(($info->ftp_type ?? '') == 'FTP')
                        selected
                    @endif
                >FTP</option>
                <option value="FTPS"
                    @if(($info->ftp_type ?? '') == 'FTPS')
                        selected
                    @endif
                >FTPS</option>
                <option value="SFTP"
                    @if(($info->ftp_type ?? '') == 'SFTP')
                        selected
                    @endif
                >SFTP</option>
            </select>
        </div>
        <div>
            <label for="ftp_address">@lang('pages.ftp_address')</label>
            <input type="text" name="ftp_address" value="{{ $info->ftp_address ?? '' }}" id="ftp_address">
        </div>
        <div>
            <label for="ftp_login">@lang('pages.ftp_login')</label>
            <input type="text" name="ftp_login" value="{{ $info->ftp_login ?? '' }}" id="ftp_login">
        </div>
        <div>
            <label for="ftp_password">@lang('pages.ftp_password')</label>
            <input type="text" name="ftp_password" value="{{ $info->ftp_password ?? '' }}" id="ftp_password">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.save')" class="button">
        </div>
    </form>
@endsection
