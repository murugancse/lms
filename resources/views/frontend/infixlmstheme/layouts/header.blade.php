<!doctype html>
<html dir="{{isRtl()?'rtl':''}}" class="{{isRtl()?'rtl':''}}" lang="en" itemscope
      itemtype="{{url('/')}}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
        @yield('title')
    </title>
    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="{{ getSetting()->site_name }}">
    <meta itemprop="description" content="{{ getSetting()->meta_description }}">
    <meta itemprop="image" content="{{asset(getSetting()->logo)}}">
    @if (Request::is('/'))
        <meta itemprop="keywords" content="{{ getSetting()->meta_keywords}}">
    @endif
    <meta itemprop="author" content="InfixLMS">

    <!-- Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ getSetting()->site_title }}">
    @if (Request::is('/'))
        <meta property="og:description" content="{{ getSetting()->meta_description }}">
    @endif
    <meta property="og:image" content="{{asset(getSetting()->logo)}}"/>
    <meta property="og:image:type" content="image/png"/>
    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset(getSetting()->favicon)}}">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    @if(isRtl())
        <link rel="stylesheet" href="{{ asset('public') }}/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="{{ asset('public') }}/css/bootstrap.min.css">
    @endif
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/fontawesome.css ">
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/flaticon.css">
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/nice-select.css">
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/animate.min.css">
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/slicknav.css">
    <link rel="stylesheet" href="{{asset('public')}}/css/toastr.min.css"/>
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/rtl_style.css">
    <link rel="stylesheet" href="{{ asset('public/frontend/infixlmstheme') }}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/css/preloader.css')}}"/>

    @if(str_contains(request()->url(), 'chat'))
        <link rel="stylesheet" href="{{asset('public/backend/css/jquery-ui.css')}}"/>
        <link rel="stylesheet" href="{{asset('public/backend/vendors/select2/select2.css')}}"/>
        <link rel="stylesheet" href="{{asset('public/chat/css/style-student.css')}}">
    @endif

    @if(auth()->check() && auth()->user()->role_id == 3 && !str_contains(request()->url(), 'chat'))
        <link rel="stylesheet" href="{{asset('public/chat/css/notification.css')}}">
    @endif

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

    @if(auth()->check() && auth()->user()->role_id == 3)
        <style>
            .admin-visitor-area{
                margin: 0 30px 30px 30px!important;
            }
            .dashboard_main_wrapper .main_content_iner.main_content_padding{
                padding-top: 0!important;
            }
            .primary_input {
                height: 50px;
                border-radius: 0px;
                border: unset;
                font-family: "Jost", sans-serif;
                font-size: 14px;
                font-weight: 400;
                color: unset;
                padding: unset;
                width: 100%;
                margin-bottom: 30px;
            }
            .primary_input_field {
                border: 1px solid #ECEEF4;
                font-size: 14px;
                color: #415094;
                padding-left: 20px;
                height: 46px;
                border-radius: 30px;
                width: 100%;
                padding-right: 15px;
            }
            .primary_input_label {
                font-size: 12px;
                text-transform: uppercase;
                color: #828BB2;
                display: block;
                margin-bottom: 6px;
                font-weight: 400;
            }

            .chat_badge {
                color: #ffffff;
                border-radius: 20px;
                font-size: 10px;
                position: relative;
                left: -11px;
                top: -12px;
                padding: 0px 4px !important;
                max-width: 18px;
                max-height: 18px;
                box-shadow: none;
                background: #ed353b;
            }

            .chat-icon-size{
                font-size: 1.25em;
                color: #687083;
            }
        </style>
    @endif


    @yield('css')

</head>

<body>
<div class="preloader">
    <h3 data-text="{{getSetting()->site_title}}...">{{getSetting()->site_title}}...</h3>
</div>
