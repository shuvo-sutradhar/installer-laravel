@extends('vendor.installer.layouts.master')

@section('template_title')
{{ trans('installer_messages.envato.templateTitle') }}
@endsection

@section('title')
<i class="fa fa-key fa-fw" aria-hidden="true"></i>
{{ __('Verify Envato Purchase Code') }}
@endsection

@section('container')

<form method="post" action="{{ route('LaravelInstaller::codeVerifyProcess') }}" class="tabs-wrap">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group {{ $errors->has('profile_name') ? ' has-error ' : '' }}">
        <label for="profile_name">
            {{ __('Envato Profile Name') }}
        </label>
        <input type="text" name="profile_name" id="profile_name" value="" placeholder="{{ __('Envato Profile Name')}}" />
        @if ($errors->has('profile_name'))
        <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('profile_name') }}
        </span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('purchase_code') ? ' has-error ' : '' }}">
        <label for="purchase_code">
            {{ __('Purchase Code') }}
        </label>
        <input type="text" name="purchase_code" id="purchase_code" value="" placeholder="{{ __('Envato purchase code')}}" />
        @if ($errors->has('purchase_code'))
        <span class="error-block">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            {{ $errors->first('purchase_code') }}
        </span>
        @endif
    </div>
    <div class="buttons">
        <button class="button" type="submit">
            {{ __('Verify Code') }}
            <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
        </button>
    </div>
</form>

@endsection