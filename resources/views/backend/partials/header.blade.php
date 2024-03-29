<!DOCTYPE html>
<html dir="{{isRtl()?'rtl':''}}" class="{{isRtl()?'rtl':''}}">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{getCourseImage(getSetting()->favicon)}}" type="image/png"/>
    <title>
        {{getSetting()->site_title ? getSetting()->site_title : 'Infix LMS'}}
    </title>
    <meta name="_token" content="{!! csrf_token() !!}"/>
    @include('backend.partials.style')
    <script src="{{asset('public/js/jquery-3.5.1.min.js')}}"></script>

    <script>
        window.Laravel = {
            "baseUrl": '{{ url('/') }}'+'/',
            "current_path_without_domain": '{{request()->path()}}'
        }
    </script>

    <script>
        window._locale = '{{ app()->getLocale() }}';
        window._translations = {!! cache('translations') !!};
    </script>
</head>

<body class="admin">
<div class="preloader">
    <img src="{{ asset('public/frontend/infixlmstheme') }}/img/Spinner.gif">
</div>
<input type="hidden" name="demoMode" id="demoMode" value="{{appMode()}}">
<input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
<input type="hidden" name="table_name" id="table_name" value="@yield('table')">
<input type="hidden" name="csrf_token" class="csrf_token" value="{{csrf_token()}}">
<div class="main-wrapper" style="min-height: 600px">
    <!-- Sidebar  -->
@include('backend.partials.sidebar')

<!-- Page Content  -->
    <div id="main-content">
@include('backend.partials.menu')
