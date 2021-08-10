@extends('frontend.infixlmstheme.auth.layouts.app')
<style>
    .login_wrapper {
        display: grid;
        grid-template-columns: 500px auto;
        justify-content: center;
    }
</style>
@section('content')

    <div class="login_wrapper">
        <div class="login_wrapper_left">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img style="width: 75px" src="{{asset(getSetting()->logo)}} " alt="">
                </a>
            </div>
            <div class="login_wrapper_content">
                <h4>{{__('Parent Registration Details')}}</h4>
                <form id="ParentRegisterForm" action="{{route('parent.create')}}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="parent_name">Parent Name *</label>
                                <input type="text" class="form-control" id="parent_name" name="parent_name" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="parent_ic">Parent IC No *</label>
                                <input type="text" class="form-control" id="parent_ic" name="parent_ic" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="parent_phone_no">Parent Phone Number *</label>
                                <input type="text" class="form-control" id="parent_phone_no" name="parent_phone_no" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="parent_email">Parent Email *</label>
                                <input type="email" class="form-control" id="parent_email" name="parent_email" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="state" name="state" aria-describedby="stateHelp" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="district">District</label>
                                <input type="text" class="form-control" id="district" name="district" aria-describedby="districtHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" aria-describedby="cityHelp" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="post_code">PostCode</label>
                                <input type="text" class="form-control" id="post_code" name="post_code" aria-describedby="post_codeHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="house_address">House Address</label>
                                <input type="text" class="form-control" id="house_address" name="house_address" aria-describedby="cityHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="student_name">Student Name *</label>
                                <input type="text" class="form-control" id="student_name" name="student_name" aria-describedby="cityHelp" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="student_ic">Student IC No *</label>
                                <input type="text" class="form-control" id="student_ic" name="student_ic" aria-describedby="student_icHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="school_name">School Name *</label>
                                <input type="text" class="form-control" id="school_name" name="school_name" aria-describedby="cityHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="password">{{__('frontend.Enter Password')}} *</label>
                                <input type="password" class="form-control" id="password" name="password" aria-describedby="" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="password_confirmation">{{__('common.Enter Confirm Password')}} *</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" aria-describedby="" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12 mt_20">
                            <div class="remember_forgot_pass d-flex align-items-center">
                                <label class="primary_checkbox d-flex" for="checkbox">
                                    <input checked="" type="checkbox" id="checkbox" required>
                                    <span class="checkmark mr_15"></span>
                                    <p>{{__('frontend.By signing up, you agree to')}} <a target="_blank"
                                                                                         href="{{url('privacy')}}">{{__('frontend.Terms of Service')}}</a> {{__('frontend.and')}}
                                        <a href="{{url('privacy')}}">{{__('frontend.Privacy Policy')}}.</a></p>
                                </label>

                            </div>
                        </div>


                        <div class="col-12">
                            <button type="submit" class="theme_btn text-center w-100" id="submitBtn">
                                {{__('common.Register')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>


            <h5 class="shitch_text">
                {{__('common.You have already an account?')}} <a href="{{route('login')}}"> {{__('common.Login')}}</a>

            </h5>
        </div>

        @include('frontend.infixlmstheme.auth.login_wrapper_right')

    </div>
    <script>
        $(function () {
            $('#ParentRegisterForm').submit( function() {
                if($("#password").val()!=$("#password_confirmation").val()){
                    alert("Password does not match");
                    return false;
                }else{
                    $("#submitBtn").prop('disabled',true);
                }
            });
            $('#checkbox').click(function () {

                if ($(this).is(':checked')) {
                    $('#submitBtn   ').removeClass('disable_btn');
                    $('#submitBtn   ').removeAttr('disabled');

                } else {
                    $('#submitBtn   ').addClass('disable_btn');
                    $('#submitBtn').attr('disabled', 'disabled');

                }
            });
        });
    </script>


@endsection
