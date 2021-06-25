@extends('backend.master')
@push('styles')

@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>Edit Instructor</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">Instructors List</a>
                    <a href="#">Edit Instructor</a>
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
                           <form id="instructorForm" action="{{route('instructor.update')}}" method="POST"
                                      enctype="multipart/form-data">
                                  
                                    <input type="hidden" name="id" value="{{$instructor->id}}" id="instructorId">
                                    @csrf
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.Name')}} <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" name="name" required="" value="{{$instructor->name}}" placeholder="-" id="addName"
                                                       type="text" {{$errors->first('name') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label"
                                                       for="">{{__('instructor.About')}}</label>
                                                <textarea class="lms_summernote" name="about" id="addAbout" cols="30"
                                                          rows="10">{{ $instructor->about }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">{{__('common.Date of Birth')}}
                                                </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input placeholder="{{__('common.Date')}}"
                                                                       class="primary_input_field primary-input date form-control"
                                                                       id="startDate" type="text" name="dob"
                                                                       value="{{ $instructor->dob }}"
                                                                       {{$errors->first('dob') ? 'autofocus' : ''}}
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('common.Phone')}} </label>
                                                <input class="primary_input_field" value="{{ $instructor->phone }}" name="phone" id="addPhone"
                                                       placeholder="-" {{$errors->first('phone') ? 'autofocus' : ''}}
                                                       type="text">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.Email')}} <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" required name="email" placeholder="-" id="addEmail"
                                                       value="{{ $instructor->email }}"
                                                       {{$errors->first('email') ? 'autofocus' : ''}}
                                                       type="email">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label"
                                                       for="">{{__('common.Image')}}
                                                    <small>{{__('student.Recommended size')}}
                                                        (330x400)</small></label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary-input imgName"
                                                           type="text"
                                                           id="instructorImage" value="{{ $instructor->image }}" 
                                                           readonly="">
                                                    <button class="" type="button">
                                                        <label
                                                            class="primary-btn small fix-gr-bg"
                                                            for="document_file_edit">{{__('common.Browse')}}</label>
                                                        <input type="file"
                                                               class="d-none imgBrowse"
                                                               name="image"
                                                               id="document_file_edit">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('Password')}} <strong
                                                        class="text-danger">*</strong></label>
                                                <div class="input-group mb-2 mr-sm-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i style="cursor:pointer;"
                                                                                         class="fas fa-eye-slash eye toggle-password"></i>
                                                        </div>
                                                    </div>
                                                    <input type="password" class="form-control primary_input_field"
                                                           id="addPassword" name="password"
                                                           placeholder="{{__('common.Minimum 8 characters')}}" {{$errors->first('password') ? 'autofocus' : ''}}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">{{__('Confirm Password')}} <strong
                                                        class="text-danger">*</strong></label>
                                                <div class="input-group mb-2 mr-sm-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i style="cursor:pointer;"
                                                                                         class="fas fa-eye-slash eye toggle-password"></i>
                                                        </div>
                                                    </div>
                                                    <input type="password" class="form-control primary_input_field"
                                                           {{$errors->first('password_confirmation') ? 'autofocus' : ''}}
                                                           id="addCpassword" name="password_confirmation"
                                                           placeholder="{{__('common.Minimum 8 characters')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                       
                                        <div class="col-xl-6">
                                            <label class="primary_input_label" for="grade">Grade</label>
                                            <select class="primary_select" name="grade" id="grade">
                                                <option data-display="{{__('common.Select')}} Grade"
                                                        value="">{{__('common.Select')}} Grade</option>
                                                @foreach($grades as $grade)
                                                <option @if($instructor->grade==$grade->id) selected @endif value="{{$grade->id}}">{{$grade->title}}</option>
                                                @endforeach
                                                
                                            </select>
                                        </div>
                                        <div class="col-xl-6">
                                            <label class="primary_input_label" for="subject">Subject</label>
                                            <select class="primary_select" name="subject" id="subject">
                                                <option data-display="{{__('common.Select')}} Subject"
                                                        value="">{{__('common.Select')}} Subject</option>
                                                @foreach($subjects as $subject)
                                                <option @if($instructor->subject==$subject->id) selected @endif value="{{$subject->id}}">{{$subject->title}}</option>
                                                @endforeach
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for=""> Per Hour Charge</label>
                                                <input class="primary_input_field" name="per_hour_charge" placeholder="-" id="per_hour_charge"
                                                       type="text" value="{{ $instructor->per_hour_charge }}">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row d-none">

                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for=""> {{__('common.Facebook URL')}}</label>
                                                <input class="primary_input_field" name="facebook" placeholder="-" id="addFacebook"
                                                       type="text" value="{{ old('facebook') }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for=""> {{__('common.Twitter URL')}}</label>
                                                <input class="primary_input_field" name="twitter" placeholder="-" id="addTwitter"
                                                       type="text" value="{{ old('twitter') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-none">

                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for=""> {{__('common.LinkedIn URL')}}</label>
                                                <input class="primary_input_field" name="linkedin" placeholder="-" id="addLinkedin"
                                                       type="text" value="{{ old('linkedin') }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for=""> {{__('common.Instagram URL')}}</label>
                                                <input class="primary_input_field" name="instagram" placeholder="-" id="addInstagram"
                                                       type="text" value="{{ old('instagram') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-center pt_15">
                                        <div class="d-flex justify-content-center">
                                            <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                                    type="submit"><i
                                                    class="ti-check"></i> {{__('common.Save')}} {{__('courses.Instructor')}}
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
    <script>
        $('#instructorForm').submit( function() {
            if($("#addPassword").val()!=$("#addCpassword").val()){
                alert("Password does not match");
                return false;
            }
        });
    </script>
     <script src="{{asset('public/backend/js/instructor_list.js')}}"></script>
@endpush
