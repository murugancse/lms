@extends('frontend.infixlmstheme.layouts.master')
@section('title'){{getSetting()->site_title ? getSetting()->site_title : 'Infix LMS'}} | {{__('courses.Courses')}} @endsection
@section('css') @endsection

@section('js')
    <script src="{{asset('public/frontend/infixlmstheme/js/classes.js')}}"></script>
@endsection
@section('mainContent')
    <!-- bradcam::start  -->
    <div class="breadcrumb_area bradcam_bg_2"
         style="background-image: url('{{asset(@$frontendContent->course_page_banner)}}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="breadcam_wrap">
                        <span>
                            {{@$frontendContent->course_page_title}}
                        </span>
                        <h3>
                            {{@$frontendContent->course_page_sub_title}}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- bradcam::end  -->
    <input type="hidden" class="class_route" name="class_route" value="{{route('courses')}}">

    <!-- course ::start  -->
    <div class="courses_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3">
                    <div class="course_category_chose mb_30 mt_10">
                        <div class="course_title mb_30 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19.5" height="13" viewBox="0 0 19.5 13">
                                <g id="filter-icon" transform="translate(28)">
                                    <rect id="Rectangle_1" data-name="Rectangle 1" width="19.5" height="2" rx="1"
                                          transform="translate(-28)" fill="#fb1159"/>
                                    <rect id="Rectangle_2" data-name="Rectangle 2" width="15.5" height="2" rx="1"
                                          transform="translate(-26 5.5)" fill="#fb1159"/>
                                    <rect id="Rectangle_3" data-name="Rectangle 3" width="5" height="2" rx="1"
                                          transform="translate(-20.75 11)" fill="#fb1159"/>
                                </g>
                            </svg>
                            <h5 class="font_16 f_w_500 mb-0">{{__('frontend.Filter Category')}}</h5>
                        </div>
                        <div class="course_category_inner">
                            <div class="single_course_categry">
                                <h4 class="font_18 f_w_700">
                                    {{__('frontend.Course Category')}}
                                </h4>
                                <ul class="Check_sidebar">
                                    @if(isset($categories))
                                        @foreach ($categories as $cat)
                                            <li>
                                                <label class="primary_checkbox d-flex">
                                                    <input type="checkbox" value="{{$cat->id}}"
                                                           class="category" {{in_array($cat->id,explode(',',$category))?'checked':''}}>
                                                    <span class="checkmark mr_15"></span>
                                                    <span class="label_name">{{$cat->name}}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    @endif

                                </ul>
                            </div>
                            <div class="single_course_categry">
                                <h4 class="font_18 f_w_700">
                                    {{__('frontend.Level')}}
                                </h4>
                                <ul class="Check_sidebar">

                                    @foreach($levels as $l)
                                        <li>
                                            <label class="primary_checkbox d-flex">
                                                <input name="level" type="checkbox" value="{{$l->id}}"
                                                       class="level" {{in_array($l->id,explode(',',$level))?'checked':''}}>
                                                <span class="checkmark mr_15"></span>
                                                <span class="label_name">{{$l->title}}</span>
                                            </label>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                            <div class="single_course_categry">
                                <h4 class="font_18 f_w_700">
                                    {{__('frontend.Course Price')}}
                                </h4>
                                <ul class="Check_sidebar">
                                    <li>
                                        <label class="primary_checkbox d-flex">
                                            <input type="checkbox" class="type"
                                                   value="paid" {{in_array("paid",explode(',',$type))?'checked':''}}>
                                            <span class="checkmark mr_15"></span>
                                            <span class="label_name">{{__('frontend.Paid Course')}}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="primary_checkbox d-flex">
                                            <input type="checkbox" class="type"
                                                   value="free" {{in_array("free",explode(',',$type))?'checked':''}}>
                                            <span class="checkmark mr_15"></span>
                                            <span class="label_name">{{__('frontend.Free Course')}}</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="single_course_categry">
                                <h4 class="font_18 f_w_700">
                                    {{__('frontend.Language')}}
                                </h4>
                                <ul class="Check_sidebar">
                                    @if(isset($languages))
                                        @foreach ($languages as $lang)

                                            <li>
                                                <label class="primary_checkbox d-flex">
                                                    <input type="checkbox" class="language"
                                                           value="{{$lang->code}}" {{in_array($lang->code,explode(',',$language))?'checked':''}}>
                                                    <span class="checkmark mr_15"></span>
                                                    <span class="label_name">{{$lang->name}}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="row">
                        <div class="col-12">
                            <div class="box_header d-flex flex-wrap align-items-center justify-content-between">
                                <h5 class="font_16 f_w_500 mb_30">{{$total}} {{__('frontend.Course are found')}}</h5>
                                <div class="box_header_right mb_30">
                                    <div class="short_select d-flex align-items-center">
                                        <h5 class="mr_10 font_16 f_w_500 mb-0">{{__('frontend.Order By')}}:</h5>
                                        <select class="small_select" id="order">
                                            <option data-display="None">{{__('frontend.None')}}</option>
                                            <option
                                                value="price" {{$order=="price"?'selected':''}}>{{__('frontend.Price')}}</option>
                                            <option
                                                value="date" {{$order=="date"?'selected':''}}>{{__('frontend.Date')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($courses))
                            @foreach ($courses as $course)
                                <div class="col-lg-6 col-xl-4">
                                    <div class="couse_wizged">
                                        <a href="{{route('courseDetailsView',[@$course->id,@$course->slug])}}">
                                            <div class="thumb">
                                                <div class="thumb_inner"
                                                     style="background-image: url('{{ file_exists($course->thumbnail) ? asset($course->thumbnail) : asset('public/\uploads/course_sample.png') }}')">


                                                </div>
                                                <span class="prise_tag">
                                            <span>
                                                @if (@$course->discount_price!=null)
                                                    {{getPriceFormat($course->discount_price)}}
                                                @else
                                                    {{getPriceFormat($course->price)}}
                                                @endif

                                              </span>
                                        </span>
                                            </div>
                                        </a>
                                        <div class="course_content">
                                            <a href="{{route('courseDetailsView',[@$course->id,@$course->slug])}}">

                                                <h4 class="noBrake" title=" {{$course->title}}">
                                                    {{$course->title}}
                                                </h4>
                                            </a>
                                            <div class="rating_cart">
                                                <div class="rateing">
                                                    <span>{{getTotalRating($course->id)}}/5</span>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                @auth()
                                                    @if(!isEnrolled($course->id,\Illuminate\Support\Facades\Auth::user()->id) && !isCart($course->id))
                                                        <a href="#" class="cart_store"
                                                           data-id="{{$course->id}}">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    @else
                                                        @if(count($course->lessons)>0)
                                                        <a href="{{ url('fullscreen-view/'.$course->id.'/'.$course->lessons[0]->id) }}" data-course="{{$course->id}}" data-lesson="{{$course->lessons[0]->id}}" class="theme_btn_lite goFullScreen" style="line-height:2;padding: 5px 22px;" >Start Lesson</a>
                                                        @endif
                                                    @endif
                                                @endauth
                                                @guest()
                                                    @if(!isCart($course->id))
                                                        <a href="#" class="cart_store"
                                                           data-id="{{$course->id}}">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    @endif
                                                @endguest

                                            </div>
                                            <div class="course_less_students">
                                                @php
                                               // dd($course->lessons);
                                                @endphp
                                                <a> <i class="ti-agenda"></i> {{count($course->lessons)}}
                                                    {{__('frontend.Lessons')}}</a>
                                                <a>
                                                    <i class="ti-user"></i> {{$course->total_enrolled}} {{__('frontend.Students')}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if(count($courses)==0)
                            <div class="col-lg-12">
                                <div class="couse_wizged text-center">
                                    <h1>
                                        <div class="thumb">
                                            <img style="width: 50px"
                                                 src="{{ asset('public/frontend/infixlmstheme') }}/img/not-found.png"
                                                 alt="">
                                            {{__('frontend.No Course Found')}}
                                        </div>
                                    </h1>
                                </div>
                            </div>

                        @endif
                    </div>
                    {{ $courses->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- course ::end  -->
@endsection

