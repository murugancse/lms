@extends('backend.master')
@push('styles')

@endpush
@php
    $table_name='parents';
@endphp
@section('table'){{$table_name}}@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>New Parent</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">Parents List</a>
                    <a href="#">New Parent</a>
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
                            <form id="ParentRegisterForm" action="{{route('parent.store')}}" method="POST" enctype="multipart/form-data">
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
                                            <label class="primary_input_label" for="">Parent {{__('IC No')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="parent_ic" placeholder="-"
                                                   type="text" id="addNRIC"
                                                   value="{{ old('nric') }}" {{$errors->first('nric') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_phone">Parent {{__('common.Phone')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_phone') }}"
                                                   name="parent_phone_no" id="parent_phone"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_phone') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">Parent {{__('Email')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="parent_email" placeholder="-"
                                                   type="email" id="parent_email"
                                                   value="{{ old('parent_email') }}" {{$errors->first('parent_email') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_state">{{__('State')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_state') }}"
                                                   name="state" id="parent_state"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_state') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_district">{{__('District')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_district') }}"
                                                   name="district" id="parent_district"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_district') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_city">{{__('City')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_city') }}"
                                                   name="city" id="parent_city"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_city') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-8">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_home_address">{{__('House Address')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_home_address') }}"
                                                   name="house_address" id="parent_home_address"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_home_address') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label"
                                                   for="parent_city">{{__('PostCode')}} </label>
                                            <input class="primary_input_field" value="{{ old('parent_postcode') }}"
                                                   name="post_code" id="parent_postcode"
                                                   placeholder="-"
                                                   type="text" {{$errors->first('parent_postcode') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <h1>Student Details</h1>
                                        <br>
                                    </div>
                                    <div class="col-xl-6">

                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">Student {{__('common.Name')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="student_name" placeholder="-"
                                                   type="text" id="addName"
                                                   value="{{ old('name') }}" {{$errors->first('name') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">Student {{__('NRIC')}} <strong
                                                    class="text-danger">*</strong></label>
                                            <input class="primary_input_field" name="student_ic" placeholder="-"
                                                   type="text" id="addNRIC"
                                                   value="{{ old('nric') }}" {{$errors->first('nric') ? 'autofocus' : ''}}>
                                        </div>
                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-xl-12">

                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="grade">School Name</label>
                                            <input class="primary_input_field" name="school_name" placeholder="-"
                                                   type="text" id="addNRIC"
                                                   value="{{ old('nric') }}" {{$errors->first('nric') ? 'autofocus' : ''}}>
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
                                                class="ti-check"></i> {{__('common.Save')}} Parent
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
        $("#parent_list").addClass('active');
        $("#parentlistul").addClass('mm-collapse mm-show');
        $('#ParentRegisterForm').submit( function() {
            if($("#password").val()!=$("#password_confirmation").val()){
                alert("Password does not match");
                return false;
            }else{
                $("#save_button_parent").prop('disabled',true);
            }
        });
    </script>
     <script src="{{asset('public/backend/js/instructor_list.js')}}"></script>
@endpush
