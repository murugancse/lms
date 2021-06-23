@extends('backend.master')
@push('styles')

@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>New Course</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">Courses List</a>
                    <a href="#">New Course</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid plr_30">
            <div class="row justify-content-center">
                <div class="col-lg-12 p-0">
                    <div class="">
                        
                        <div class="white-box  ">
                            <form action="{{route('AdminSaveCourse')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-xl-6 ">
                                            <div class="primary_input mb-25">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="primary_input_label"
                                                               for=""> {{__('courses.Type')}} * </label>
                                                    </div>
                                                    <div class="col-md-6 ">

                                                        <input type="radio" class="common-radio" id="type1"
                                                               name="type"
                                                               value="1"
                                                               @if(empty(old('type')))checked @else {{old('type')==1?"checked":""}} @endif>
                                                        <label for="type1">{{__('courses.Course')}}</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="radio" class="common-radio" id="type2"
                                                               name="type"
                                                               value="2" {{old('type')==2?"checked":""}}>
                                                        <label for="type2">{{__('quiz.Quiz')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 " id="dripCheck">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label mt-1"
                                                       for=""> {{__('common.Drip Content')}}</label>
                                                <div class="row">
                                                    <div class="col-md-6">

                                                        <input type="radio" class="common-radio drip0"
                                                               id="drip0" name="drip"
                                                               value="0" checked>
                                                        <label
                                                            for="drip0">{{__('common.No')}}</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="radio" class="common-radio drip1"
                                                               id="drip1" name="drip"
                                                               value="1">
                                                        <label
                                                            for="drip1">{{__('common.Yes')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('quiz.Topic')}} {{__('common.Title')}} *</label>
                                                <input class="primary_input_field" required="" name="title" placeholder="-"
                                                       id="addTitle"
                                                       type="text" {{$errors->has('title') ? 'autofocus' : ''}}
                                                       value="{{old('title')}}">
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label"
                                                       for="">{{__('courses.Course')}} {{__('courses.Requirements')}}
                                                </label>
                                                <textarea class="lms_summernote" name="requirements"
                                                          id="addRequirements" cols="30"
                                                          rows="10">{{old('requirements')}}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label"
                                                       for="">{{__('courses.Course')}} {{__('courses.Description')}}
                                                </label>
                                                <textarea class="lms_summernote" name="about" id="addAbout" cols="30"
                                                          rows="10">{{old('about')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label"
                                                       for="">{{__('courses.Course')}} {{__('courses.Outcomes')}}
                                                </label>
                                                <textarea class="lms_summernote" name="outcomes" id="addOutcomes"
                                                          cols="30"
                                                          rows="10">{{old('outcomes')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 courseBox mb_30">
                                            <select class="primary_select category_id" name="category"
                                                    id="category_id" {{$errors->has('category') ? 'autofocus' : ''}}>
                                                <option data-display="{{__('common.Select')}} {{__('quiz.Category')}} *"
                                                        value="">{{__('common.Select')}} {{__('quiz.Category')}} </option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}">{{@$category->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-6 courseBox" id="subCategoryDiv">
                                            <select class="primary_select" name="sub_category"
                                                    id="subcategory_id" {{$errors->has('sub_category') ? 'autofocus' : ''}}>
                                                <option
                                                    data-display="{{ __('common.Select') }} {{ __('courses.Sub Category') }}  "
                                                    value="">{{ __('common.Select') }} {{ __('courses.Sub Category') }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-xl-6 mt-30 quizBox" style="display: none">
                                            <select class="primary_select" name="quiz"
                                                    id="quiz_id" {{$errors->has('quiz') ? 'autofocus' : ''}}>
                                                <option data-display="{{__('common.Select')}} {{__('quiz.Quiz')}} *"
                                                        value="">{{__('common.Select')}} {{__('quiz.Quiz')}} </option>
                                                @foreach($quizzes as $quiz)
                                                    <option value="{{$quiz->id}}">{{@$quiz->title}} </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-xl-4 makeResize mt-30">
                                            <select class="primary_select" name="level">
                                                <option
                                                    data-display="{{ __('common.Select') }} {{ __('courses.Level') }} *"
                                                    value="">{{ __('common.Select') }} {{ __('courses.Level') }}
                                                </option>
                                                @foreach($levels as $level)
                                                    <option
                                                        value="{{$level->id}}" {{old('level')==$level->id?"selected":""}} >{{$level->title}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-xl-4 mt-30 makeResize" id="">
                                            <select class="primary_select mb-25" name="language"
                                                    id="" {{$errors->has('language') ? 'autofocus' : ''}}>
                                                <option
                                                    data-display="{{ __('common.Select') }} {{ __('common.Language') }} *"
                                                    value="">{{ __('common.Select') }} {{ __('common.Language') }}</option>
                                                @foreach ($languages as $language)
                                                    <option
                                                        value="{{$language->id}}" {{old('language')==$language->id?"selected":""}}>{{$language->native}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-4 makeResize" id="durationBox">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{ __('common.Duration') }} *</label>
                                                <input class="primary_input_field" name="duration" placeholder="-"
                                                       id="addDuration"
                                                       type="text"
                                                       value="{{old('duration')}}" {{$errors->has('duration') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-none">
                                        <div class="col-lg-6">
                                            <div class="checkbox_wrap d-flex align-items-center">
                                                <label for="course_1" class="switch_toggle mr-2">
                                                    <input type="checkbox" id="course_1">
                                                    <i class="slider round"></i>
                                                </label>
                                                <label
                                                    class="mb-0">{{ __('courses.This course is a top course') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-lg-6">
                                            <div class="checkbox_wrap d-flex align-items-center mt-40">
                                                <label for="course_2" class="switch_toggle mr-2">
                                                    <input type="checkbox" id="course_2">
                                                    <i class="slider round"></i>
                                                </label>
                                                <label
                                                    class="mb-0">{{ __('courses.This course is a free course') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-6" id="price_div">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{ __('courses.Price') }}</label>
                                                <input class="primary_input_field" name="price" placeholder="-"
                                                       id="addPrice"
                                                       type="text" value="{{old('price')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20" id="discountDiv">
                                        <div class="col-lg-6">
                                            <div class="checkbox_wrap d-flex align-items-center mt-40">
                                                <label for="course_3" class="switch_toggle mr-2">
                                                    <input type="checkbox" id="course_3">
                                                    <i class="slider round"></i>
                                                </label>
                                                <label
                                                    class="mb-0">{{ __('courses.This course has discounted price') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-xl-4" id="discount_price_div" style="display: none">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{ __('courses.Discount') }} {{ __('courses.Price') }}</label>
                                                <input class="primary_input_field" name="discount_price" placeholder="-"
                                                       id="addDiscount"
                                                       type="text" value="{{old('discount_price')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-20 videoOption">
                                        <div class="col-xl-4 mt-25">
                                            <select class="primary_select category_id " name="host"
                                                    id="">
                                                <option
                                                    data-display="{{__('courses.Course overview host')}} *"
                                                    value="">{{__('courses.Course overview host')}}
                                                </option>

                                                <option {{@$course->host=="Youtube"?'selected':''}} value="Youtube">
                                                    {{__('courses.Youtube')}}
                                                </option>

                                                <option value="Vimeo" {{@$course->host=="Vimeo"?'selected':''}}>
                                                    {{__('courses.Vimeo')}}
                                                </option>
                                                @if(moduleStatusCheck("AmazonS3"))
                                                    <option
                                                        value="AmazonS3" {{@$course->host=="AmazonS3"?'selected':''}}>
                                                        {{__('courses.Amazon S3')}}
                                                    </option>
                                                @endif

                                                <option value="Self" {{@$course->host=="Self"?'selected':''}}>
                                                    {{__('courses.Self')}}
                                                </option>


                                            </select>
                                        </div>
                                        <div class="col-xl-8 ">
                                            <div class="input-effect videoUrl"
                                                 style="display:@if((isset($course) && (@$course->host!="Youtube")) || !isset($course)) none  @endif">
                                                <label>{{__('courses.Video URL')}}
                                                    <span>*</span></label>
                                                <input
                                                    id=""
                                                    class="primary_input_field youtubeVideo name{{ $errors->has('trailer_link') ? ' is-invalid' : '' }}"
                                                    type="text" name="trailer_link"
                                                    placeholder="{{__('courses.Video URL')}}"
                                                    autocomplete="off"
                                                    value="" {{$errors->has('trailer_link') ? 'autofocus' : ''}}>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('trailer_link'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('trailer_link') }}</strong>
                                            </span>
                                                @endif
                                            </div>

                                            <div class="row  vimeoUrl" id=""
                                                 style="display: @if((isset($course) && (@$course->host!="Vimeo")) || !isset($course)) none  @endif">
                                                <div class="col-lg-12" id="">
                                                    <label class="primary_input_label"
                                                           for="">{{__('courses.Vimeo Video')}}</label>
                                                    <select class="primary_select vimeoVideo"
                                                            name="vimeo"
                                                            id="">
                                                        <option
                                                            data-display="{{__('common.Select')}} {{__('courses.Video')}}"
                                                            value="">{{__('common.Select')}} {{__('courses.Video')}}
                                                        </option>
                                                        @foreach ($video_list as $video)
                                                            @if(isset($course))
                                                                <option
                                                                    value="{{@$video['uri']}}" {{$video['uri']==$course->trailer_link?'selected':''}}>{{@$video['name']}}</option>
                                                            @else
                                                                <option
                                                                    value="{{@$video['uri']}}">{{@$video['name']}}</option>
                                                            @endif


                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('vimeo'))
                                                        <span
                                                            class="invalid-feedback invalid-select"
                                                            role="alert">
                                            <strong>{{ $errors->first('vimeo') }}</strong>
                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row  videofileupload" id=""
                                                 style="display: @if((isset($course) && ((@$course->host=="Vimeo") ||  (@$course->host=="Youtube")) ) || !isset($course)) none  @endif">

                                                <div class="col-xl-12">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label"
                                                               for="">{{__('courses.Video File')}}</label>
                                                        <div class="primary_file_uploader">
                                                            <input class="primary-input filePlaceholder" type="text"
                                                                   id=" "
                                                                   placeholder="{{__('courses.Browse Video file')}}"
                                                                   readonly="">
                                                            <button class="" type="button">
                                                                <label
                                                                    class="primary-btn small fix-gr-bg"
                                                                    for="document_file_for_add">{{__('common.Browse') }}</label>
                                                                <input type="file" class="d-none fileUpload"
                                                                       name="file"
                                                                       id="document_file_for_add">
                                                            </button>

                                                            @if ($errors->has('file'))
                                                                <span
                                                                    class="invalid-feedback invalid-select"
                                                                    role="alert">
                                            <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-20">
                                        <div class="col-xl-6">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label"
                                                       for="">{{__('courses.Course Thumbnail') }} *</label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary-input filePlaceholder" type="text"
                                                           id=""
                                                           {{$errors->has('image') ? 'autofocus' : ''}}
                                                           placeholder="{{__('courses.Browse Image file')}}"
                                                           readonly="">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                               for="document_file_thumb_2">{{__('common.Browse') }}</label>
                                                        <input type="file" class="d-none fileUpload" name="image"
                                                               id="document_file_thumb_2">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <label class="primary_input_label"
                                                   for="">{{__('newsletter.Subscription List')}}
                                            </label>
                                            <select class="primary_select"
                                                    name="subscription_list" {{$errors->has('subscription_list') ? 'autofocus' : ''}}>
                                                <option
                                                    data-display="{{__('common.Select')}} {{__('newsletter.Subscription List')}}"
                                                    value="">{{__('common.Select')}} {{__('newsletter.Subscription List')}}

                                                </option>
                                                @foreach($sub_lists as $list)
                                                    <option value="{{$list['id']}}">
                                                        {{$list['name']}}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('courses.Meta keywords') }}</label>
                                                <input class="primary_input_field" name="meta_keywords" placeholder="-"
                                                       id="addMeta"
                                                       type="text" value="{{old('meta_keywords')}}">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('courses.Meta description') }}</label>
                                                <textarea id="my-textarea" class="primary_input_field" id
                                                          name="meta_description" style="height: 200px"
                                                          rows="3">{{old('meta_keywords')}}</textarea>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-12 text-center pt_15">
                                        <div class="d-flex justify-content-center">
                                            <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                                    type="submit"><i
                                                    class="ti-check"></i> {{__('common.Add') }} {{__('courses.Course') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
@push('scripts')
    @if ($errors->any())
        <script>
            @if(Session::has('type'))
            @if(Session::get('type')=="store")
            $('#add_course').modal('show');
            @else
            let id = '{{Session::get('id')}}';
            $('#editCourse' + id).modal('show');
            @endif
            @endif
        </script>
    @endif
    <script src="{{asset('/')}}/Modules/CourseSetting/Resources/assets/js/course.js"></script>
@endpush
