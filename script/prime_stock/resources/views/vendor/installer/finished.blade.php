@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.final.title'))
@section('container')
    <p class="paragraph" style="text-align: center;">{{ session('message')['message'] }}</p>
    <p style="text-align: center; margin-bottom: 0;">Email: admin@gmail.com</p>
    <p style="text-align: center; margin-bottom: 0;">Password: password</p>
    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>
@stop
