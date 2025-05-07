@extends('auth-layouts.auth')

@section('title', trans('default.reset_pass', [], app_get_locale()))

@section('contents')
    <div id="app">
        <verify-email :config-data="{{ json_encode(config('settings.application')) }}"
            email="{{ request()->query('email') }}">
        </verify-email>
    </div>
@endsection
