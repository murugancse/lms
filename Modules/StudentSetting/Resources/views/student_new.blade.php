@extends('backend.master')
@push('styles')

@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>New Student</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">Students List</a>
                    <a href="#">New Student</a>
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
                            <form action="{{route('student.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <h1>Parent Details</h1>
                                        <br>
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('Parent Name')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="parent_name" placeholder="-"
                                                   type="text" id="parent_name"
                                                   value="{{ old('parent_name') }}" {{$errors->first('parent_name') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('IC No')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="nric" placeholder="-"
                                                   type="text" id="addNRIC"
                                                   value="{{ old('nric') }}" {{$errors->first('nric') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_phone">{{__('common.Phone')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_phone') }}"
                                                   name="parent_phone" id="parent_phone"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_phone') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('Email')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="parent_email" placeholder="-"
                                                   type="text" id="parent_email"
                                                   value="{{ old('parent_email') }}" {{$errors->first('parent_email') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_state">{{__('State')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_state') }}"
                                                   name="parent_state" id="parent_state"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_state') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_district">{{__('District')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_district') }}"
                                                   name="parent_district" id="parent_district"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_district') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_city">{{__('City')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_city') }}"
                                                   name="parent_city" id="parent_city"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_city') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-8">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_home_address">{{__('House Address')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_home_address') }}"
                                                   name="parent_home_address" id="parent_home_address"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_home_address') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_city">{{__('PostCode')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_postcode') }}"
                                                   name="parent_postcode" id="parent_postcode"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_postcode') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <h1>Student Details</h1>
                                        <br>
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('common.Name')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="name" placeholder="-"
                                                   type="text" id="addName"
                                                   value="{{ old('name') }}" {{$errors->first('name') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('NRIC')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="nric" placeholder="-"
                                                   type="text" id="addNRIC"
                                                   value="{{ old('nric') }}" {{$errors->first('nric') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('Enrollment No')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="roll_number" placeholder="-"
                                                   type="text" id="roll_number" value="{{ old('roll_number') }}" {{$errors->first('roll_number') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                </div>
                                <div class="row d-none">
                                    <div class="col-xl-12">
                                        <div class="primary_input mb-35">
                                            <label class="primary_input_label" for="">{{__('common.About')}}</label>
                                            <textarea class="lms_summernote" name="about" id="addAbout" cols="30"
                                                      rows="10">{{ old('about') }}</textarea>
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
                                                            <input placeholder="Date"
                                                                   class="primary_input_field primary-input date form-control"
                                                                   id="startDate" type="text" name="dob"
                                                                   value="{{ old('dob') }}"
                                                                   autocomplete="off" {{$errors->first('dob') ? 'autofocus' : ''}}>
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
                                        <label class="primary_input_label" for="grade">Grade</label>
                                        <select class="primary_select" name="grade" id="grade">
                                            <option data-display="{{__('common.Select')}} Grade"
                                                    value="">{{__('common.Select')}} Grade</option>
                                            @foreach($grades as $grade)
                                                <option value="{{$grade->id}}">{{$grade->title}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-xl-12">

                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="grade">School Name</label>
                                            <input class="primary_input_field" name="nric" placeholder="-"
                                                   type="text" id="addNRIC"
                                                   value="{{ old('nric') }}" {{$errors->first('nric') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="">{{__('common.Phone')}} </label>
                                            <input class="primary_input_field" value="{{ old('phone') }}"
                                                   name="phone" id="addPhone"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('phone') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="primary_input mb-35">
                                            <label class="primary_input_label" for="">{{__('common.Image')}}
                                                <small>{{__('student.Recommended size')}} (330x400)</small></label>
                                            <div class="primary_file_uploader">
                                                <input class="primary-input imgName" type="text"
                                                       id="placeholderFileOneName"
                                                       placeholder="{{__('student.Browse Image file')}}"
                                                       readonly="">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                           for="document_file">{{__('common.Browse')}}</label>
                                                    <input type="file" class="d-none imgBrowse" name="image"
                                                           id="document_file">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('common.Email')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="email" placeholder="-"
                                                   value="{{ old('email') }}" id="addEmail"
                                                   {{$errors->first('email') ? 'autofocus' : ''}}
                                                   type="email">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{__('common.Password')}}
                                                <strong
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
                                                   for="">{{__('common.Confirm Password')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i style="cursor:pointer;"
                                                                                     class="fas fa-eye-slash eye toggle-password"></i>
                                                    </div>
                                                </div>
                                                <input type="password" class="form-control primary_input_field"
                                                       {{$errors->first('password_confirmation') ? 'autofocus' : ''}}
                                                       id="addCpassword" name="password_confirmation" placeholder="{{__('common.Minimum 8 characters')}}">
                                            </div>
                                            {{--                                                    <input class="primary_input_field"  name="password_confirmation" placeholder="-" type="text">--}}
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12 text-center pt_15">
                                    <div class="d-flex justify-content-center">
                                        <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                                type="submit"><i
                                                class="ti-check"></i> {{__('common.Save')}} {{__('student.Student')}}
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
