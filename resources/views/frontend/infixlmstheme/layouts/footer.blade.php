<!-- FOOTER::START  -->

<footer>
    @if($newsletterSetting)
        @if($newsletterSetting->home_status==1)
            <div class="footer_top_area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="footer__cta">
                                <div class="thumb">
                                    <img src="{{asset(@$frontendContent->subscribe_logo)}}" alt="" class="w-100">
                                </div>
                                <div class="cta_content">
                                    <h3>{{@$frontendContent->subscribe_title}}</h3>
                                    <p>{{@$frontendContent->subscribe_sub_title}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">

                            <div class="subcribe-form theme_mailChimp">
                                <form action="{{route('subscribe')}}"
                                      method="POST" class="subscription relative">@csrf
                                    <input name="email" class="form-control"
                                           placeholder="{{__('frontend.Enter e-mail Address')}}"
                                           onfocus="this.placeholder = ''"
                                           onblur="this.placeholder = '{{__('frontend.Email')}}'"
                                           required="" type="email" value="{{old('email')}}">

                                    <button type="submit">{{__('frontend.Subscribe Now')}}</button>
                                    <div class="info">
                                        <span class="text-danger" role="alert">{{$errors->first('email')}}</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    @endif
    <div class="copyright_area">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer_widget">
                        <div class="footer_logo">
                            <a href="#">
                                <img src="{{getCourseImage(getSetting()->logo2)}}" alt="" style="width: 108px">
                            </a>
                        </div>
                        <p>{{ getSetting()->footer_about_description }}</p>
                        <div class="copyright_text">
                            <p>{!! getSetting()->footer_copy_right !!}</p>
                        </div>

                        <style>


                        </style>
                        <div class="">
                            <ul class="pt-3 ">
                                <ul class="social-network social-circle col-lg-12 ">
                                    @if(isset($social_links))
                                        @foreach($social_links as $social)
                                            <li><a target="_blank" href="{{$social->link}}" class=""
                                                   title="{{$social->name  }}"><i
                                                        class="{{$social->icon}}"></i></a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </ul>
                        </div>


                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-6">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="footer_widget">
                                <div class="footer_title">
                                    <h3>{{ getSetting()->footer_section_one_title }}</h3>
                                </div>
                                <ul class="footer_links">
                                    @if(isset($sectionWidgets))
                                        @foreach($sectionWidgets->where('section','1') as $page)
                                            @if(isset($page->frontPage->id))
                                                @php
                                                    $route = $page->is_static == 0 ? route('frontPage',[$page->frontpage->id,$page->frontpage->slug]) : url($page->frontpage->slug)
                                                @endphp
                                                <li><a href="{{ $route }}">{{$page->name}} </a></li>
                                            @else
                                                <li><a href="">{{$page->name}} </a></li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="footer_widget">
                                <div class="footer_title">
                                    <h3>{{ getSetting()->footer_section_two_title }}</h3>
                                </div>
                                <ul class="footer_links">
                                    @if(isset($sectionWidgets))
                                        @foreach($sectionWidgets->where('section','2') as $key=> $page)
                                            @if(isset($page->frontPage->id))
                                                @php
                                                    $route = $page->is_static == 0 ? route('frontPage',[$page->frontpage->id,$page->frontpage->slug]) : url($page->frontpage->slug)
                                                @endphp
                                                <li><a href="{{ $route }}">{{$page->name}} </a></li>
                                            @else
                                                <li><a href="">{{$page->name}} </a></li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="footer_widget">
                                <div class="footer_title">
                                    <h3>{{ getSetting()->footer_section_three_title }}</h3>
                                </div>
                                <ul class="footer_links">
                                    @if(isset($sectionWidgets))
                                        @foreach($sectionWidgets->where('section','3') as $page)
                                            @if(isset($page->frontPage->id))
                                                @php
                                                    $route = $page->is_static == 0 ? route('frontPage',[$page->frontpage->id,$page->frontpage->slug]) : url($page->frontpage->slug)
                                                @endphp
                                                <li><a href="{{ $route }}">{{$page->name}} </a></li>
                                            @else
                                                <li><a href="">{{$page->name}} </a></li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER::END  -->
<!-- shoping_cart::start  -->
<div class="shoping_wrapper">
    <div class="dark_overlay"></div>
    <div class="shoping_cart">
        <div class="shoping_cart_inner">
            <div class="cart_header d-flex justify-content-between">
                <h4>{{__('frontend.Shopping Cart')}}</h4>
                <div class="chart_close">
                    <i class="ti-close"></i>
                </div>
            </div>
            <div id="cartView">
                <div class="single_cart">
                    Loading...
                </div>
            </div>


        </div>
        <div class="view_checkout_btn d-flex justify-content-end " style="display: none!important;">
            <a href="{{url('my-cart')}}" class="theme_btn small_btn3 mr_10">{{__('frontend.View cart')}}</a>
            <a href="{{route('myCart',['checkout'=>true])}}"
               class="theme_btn small_btn3">{{__('frontend.Checkout')}}</a>
        </div>
    </div>
</div>
<!-- shoping_cart::end  -->

<!-- UP_ICON  -->
<div id="back-top" style="display: none;">
    <a title="Go to Top" href="#">
        <i class="ti-angle-up"></i>
    </a>
</div>

<input type="hidden" name="item_list" class="item_list" value="{{url('getItemList')}}">
<input type="hidden" name="enroll_cart" class="enroll_cart" value="{{url('enrollOrCart')}}">
<input type="hidden" name="csrf_token" class="csrf_token" value="{{csrf_token()}}">
<!--/ UP_ICON -->

<style>
    .remove_cart {
        margin-left: -22px;
        margin-right: 8px;
        cursor: pointer;
    }

    .footer_cookics {
        background-color: {{@$cookie->bg_color??'#FB1159'}};
        padding: 10px;
        color: {{@$cookie->text_color??'#fff'}};
        bottom: 0;
        position: fixed;
        width: 100%;
        z-index: 9999999999;
        left: 0;
    }

    .cookies a, .cookies a:hover {
        color: {{@$cookie->text_color??'#fff'}};
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
    }

    .footer_cookics .accept, .cookies .accept:hover {
        background: {{@$cookie->bg_color??'#FB1159'}};
        border-color: {{@$cookie->text_color??'#fff'}};
        border-radius: 0px;
        font-size: 16px;
    }

    .footer_cookics .container {
        display: flex;
        align-items: center;
    }

    .newBtn {
        background-color: {{@$cookie->bg_color??'#FB1159'}}; /* Green */
        border: none;
        color: {{@$cookie->text_color??'#fff'}};
        transition-duration: 0.4s;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 6px;
    }


    .button4 {
        background-color: {{@$cookie->bg_color??'#FB1159'}};
        color: {{@$cookie->text_color??'#fff'}};
        border: 2px solid{{@$cookie->text_color??'#fff'}};
    }

    .button4:hover {
        color: {{@$cookie->bg_color??'#FB1159'}};
        background-color: {{@$cookie->text_color??'#fff'}};
    }

    .title {
        font-size: 17px;
        font-weight: bold;

    }
</style>

<!--cookies-->
<div class="cookies footer_cookics" style="display: none">
    <div class="container flex-column align-items-start">
        <div class="d-flex  align-items-center justify-content-between w-100">
            <span class="title text-white">{{@$cookie->title}}</span>
            <span class="" style="text-align: right;float: right">
            <button class="close-div  newBtn button4" onclick="setCookies();">{{@$cookie->btn_text}}</button>

        </span>
        </div>
        <div>
            <span>{!! @$cookie->details !!}</span>

        </div>

    </div>
</div>


<!--ALL JS SCRIPTS -->

<script src="{{ asset('public') }}/js/jquery-3.5.1.min.js"></script>
@if(isRtl())
    <script src="{{ asset('public') }}/js/popper.min.js"></script>
    <script src="{{ asset('public') }}/js/bootstrap.rtl.min.js"></script>
@else
    <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>
@endif

<script src="{{ asset('public/frontend/infixlmstheme') }}/js/owl.carousel.min.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/waypoints.min.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/jquery.counterup.min.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/imagesloaded.pkgd.min.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/wow.min.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/nice-select.min.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/barfiller.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/jquery.slicknav.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/jquery.magnific-popup.min.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/jquery.ajaxchimp.min.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/parallax.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/mail-script.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/main.js"></script>
<script src="{{ asset('public/frontend/infixlmstheme') }}/js/footer.js"></script>
<script src="{{asset('public')}}/js/toastr.min.js"></script>
{!! Toastr::message() !!}
@yield('js')

<script>
    setTimeout(function () {
        $('.preloader').fadeOut('slow', function () {
            $(this).remove();
            @if($cookie->allow)
            checkCookie();
            @endif
        });
    }, 0);

    function setCookies() {
        $('.cookies').hide(500);
        var d = new Date();
        document.cookie = "allow=1; expires=Thu, 21 Dec " + (d.getFullYear() + 1) + " 12:00:00 UTC";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function checkCookie() {
        var check = getCookie("allow");
        if (check != "") {
            $('.cookies').hide();
        } else {
            $('.cookies').show();
        }
    }

    $('#cartView').on('click', '.remove_cart', function () {
        let id = $(this).data('id');
        let total = $('.notify_count').html();

        $(this).closest(".single_cart").hide();
        let url = '{{url(('/home/removeItemAjax'))}}' + '/' + id;

        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {

                $('.notify_count').html(total - 1);
            }
        });
    });

</script>
@if(str_contains(request()->url(), 'chat'))
    <script src="{{asset('public/backend/js/jquery-ui.js')}}"></script>
    <script src="{{asset('public/backend/vendors/select2/select2.min.js')}}"></script>
    <script src="{{ asset('public/js/app.js') }}"></script>
    <script src="{{ asset('public/chat/js/custom.js') }}"></script>
@endif

@if(auth()->check() && auth()->user()->role_id == 3 && !str_contains(request()->url(), 'chat'))
    <script src="{{ asset('public/js/app.js') }}"></script>
    @endif
    </body>

    </html>
