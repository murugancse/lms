@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/communication.css')}}"/>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>Send Mail</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">{{__('communication.Communication')}}</a>
                    <a href="#">Student Mails</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid plr_30">
            <div class="row justify-content-center">
                <div class="col-lg-12 p-0">
                    <div class="">
                        
                        <div class="messages_chat ">
                            
                            <form action="{{route('communication.SendStudentMail')}}" name="sendSubmitForm"
                                          id="sendSubmitForm" method="POST" style="display: contents;">
                                
                                @csrf
                            <div id="groupMessage" class="white_box " >
                                <div class="message_box_heading"><h3
                                        id="receiver_name">Bulk Mail</h3></div>
                                    <div id="" class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="type">Type <strong
                                                        class="text-danger">*</strong></label>
                                                <select class="primary_select mb-25" required="" onchange="return EnableSection(this.value);" name="type"
                                                        id="type">

                                                        <option value="1" >Student</option> 
                                                        <option value="2" >Instructor</option> 
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="studentSection" class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="course_id">Courses <strong
                                                        class="text-danger">*</strong></label>
                                                <select class="primary_select mb-25" name="course_id"
                                                        id="course_id">

                                                        <option value="" >Select</option> 
                                                    @foreach ($courses as $key => $c)
                                                        <option
                                                            value="{{ @$c->id }}" {{isset($edit)?(@$edit->course->id == @$c->id?'selected':''):''}} >{{ @$c->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div id="student_iddiv" class="primary_input mb-25">
                                                <label class="primary_input_label" for="student_id">Student</label>
                                                <select class="primary_select mb-25" multiple name="student_id[]"
                                                        id="student_id">
                                                    <option value="" >Select</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="InstructorSection" style="display: none;" class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="instructor_id">Instructor <strong class="text-danger">*</strong></label>
                                                <select class="primary_select mb-25" multiple name="instructor_id[]"
                                                        id="instructor_id">

                                                    <option value="" >Select</option> 
                                                    @foreach ($users as $key => $u)
                                                        <option value="{{ @$u->id }}">{{ @$u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="">Subject
                                                    <strong
                                                        class="text-danger">*</strong>
                                                </label>
                                                <input class="primary_input_field" name="subject"
                                                       id="subject" required
                                                       value="" placeholder="-"
                                                       type="text">
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-35">
                                                <label class="primary_input_label"
                                                       for="">Message
                                                    <strong
                                                        class="text-danger">*</strong>
                                                </label>
                                                <textarea class="lms_summernote"
                                                          name="message" required
                                                          id="message" cols="30"
                                                          rows="10"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                
                                    <div class="clearfix"/>

                                    <div class="message_send_field">
                                    
                                            <button class="btn_1" type="submit" id="submitMessage">{{__('common.Send')}}</button>
                                       
                                    </div>
                                </div>

                                 </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" name="store_message" class="store_message"
           value="{{route('communication.StorePrivateMessage')}}">
    <input type="hidden" name="get_messages" class="get_messages"
           value="{{route('communication.getMessage')}}">

@endsection
@push('scripts')
    <script src="{{asset('public/backend/js/communication.js')}}"></script>
    <script type="text/javascript">
       
        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });
        $("#course_id").on("change", function() {
            var url = "{{url('/')}}";
            // console.log(url);

            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $("#student_id").find("option").remove();
            $("#student_iddiv ul").find("li").remove();
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "admin/course/ajax_get_course_students",
                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            
                            console.log(item);
                            $("#student_id").append(
                                    $("<option>", {
                                        value: '',
                                        text: 'Select',
                                    })
                                );
                                $("#student_iddiv ul").append(
                                    "<li data-value='' class='option'> Select </li>"
                                );
                            $.each(item, function(i, section) {
                                $("#student_id").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.name,
                                    })
                                );

                                $("#student_iddiv ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#student_id").find("option").not(":first").remove();
                            $("#student_iddiv ul").find("li").not(":first").remove();
                        }
                    });
                    console.log(a);
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
        function EnableSection(type) {
            if(type==1){
                $("#studentSection").show();
                $("#InstructorSection").hide();
            }else{
                $("#studentSection").hide();
                $("#InstructorSection").show();
            }
        }
    </script>
@endpush
