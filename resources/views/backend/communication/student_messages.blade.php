@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/communication.css')}}"/>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{__('communication.Private Messages')}}</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">{{__('communication.Communication')}}</a>
                    <a href="#">Student Messages</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid plr_30">
            <div class="row justify-content-center">
                <div class="col-lg-12 p-0">
                    <div class="messages_box_area">
                        <div class="messages_list">
                            <div class="white_box ">
                                <div class="white_box_tittle list_header">
                                    <h4>{{__('communication.Message List')}}<button class="btn btn-success" onclick="$('#groupMessage').show();$('#seperateMessage').hide();">Bulk Message </button></h4>
                                </div>
                                <div class="serach_field_2">
                                    <div class="search_inner">
                                        <form active="#">
                                            <div class="search_field">
                                                <input type="text" id="search_input" onkeyup="searchReceiver()"
                                                       placeholder="{{__('communication.Search content here')}}...">
                                            </div>
                                            <button type="submit" disabled ><i class="ti-search"></i></button>
                                        </form>
                                    </div>
                                </div>
                                <ul id="receiver_list">
                                    @foreach ($users as $key => $user)
										@if($key<10)
                                        <li>
                                            <a href="#" id="user{{$user->id}}" class="user_list"
                                               onClick="getStudentMessage({{$user->id}})">
                                                <div class="message_pre_left">
                                                    <div class="message_preview_thumb profile_info">
                                                        <div class="profileThumb"
                                                             style="background-image: url('{{getProfileImage($user->image)}}')">

                                                        </div>
{{--                                                        <img src="{{url($user->image)}}" alt="">--}}
                                                    </div>
                                                    <div class="messges_info">
                                                        <h4 id="receiver_name{{$user->id}}">{{$user->name}}</h4>
                                                        <p id="last_mesg{{$user->id}}">{{@$user->reciever->message}}</p>
                                                    </div>
                                                </div>
                                                <div class="messge_time">
                                                    <span> {{@$user->reciever->messageFormat}} </span>
                                                </div>
                                            </a>
                                        </li>
										
										@endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="messages_chat ">
                            <div id="seperateMessage" class="white_box " style="display:none;">
                                <div class="message_box_heading"><h3
                                        id="receiver_name">{{@$singleMessage->reciever->name}} </h3></div>
                                <div id="all_massages">{!! getConversations($messages ) !!}</div>

                                <div class="message_send_field">
                                    @if (permissionCheck('communication.send'))
                                        <form action="{{route('communication.StorePrivateMessage')}}" name="submitForm"
                                              id="submitForm" method="POST" style="display: contents;">
                                            @endif
                                            @csrf
                                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                            <input type="hidden" name="reciever_id" id="reciever_id"
                                                   value="{{@$singleMessage->reciever_id}}">
                                            <input type="text" name="message"
                                                   placeholder="{{__('communication.Write your message')}}" value=""
                                                   id="message">
                                            @php
                                                $tooltip = "";
                                                if(permissionCheck('communication.send')){
                                                      $tooltip = "";
                                                  }else{
                                                      $tooltip = "You have no permission to Send";
                                                  }
                                            @endphp
                                            <button class="btn_1" type="submit" id="submitMessage" data-toggle="tooltip"
                                                    title="{{$tooltip}}">{{__('common.Send')}}</button>
                                        </form>
                                </div>
                            </div>
                            <form action="{{route('communication.StoreStudentMessage')}}" name="submitForm"
                                          id="submitForm" method="POST" style="display: contents;">
                                
                                @csrf
                            <div id="groupMessage" class="white_box " >
                                <div class="message_box_heading"><h3
                                        id="receiver_name">Bulk Message</h3></div>
                                    <div class="row">
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
                                
                                    <div class="clearfix"/>

                                    <div class="message_send_field">
                                    
                                                                                
                                            <textarea class="form-control" name="message" id="message" rows="8" placeholder="{{__('communication.Write your message')}}">
                                               
                                            </textarea>
                                            

                                            <button class="btn_1" type="submit" id="submitMessage" data-toggle="tooltip"
                                                    title="{{$tooltip}}">{{__('common.Send')}}</button>
                                       
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
        function getStudentMessage(id) {
            event.preventDefault();
            $("#groupMessage").hide();

            $('#reciever_id').val(id);
            let url = $('.get_messages').val();
            var formData = {
                id: id
            };
            $.ajax({
                type: "POST",
                data: formData,
                dataType: "json",
                url: url,
                success: function (data) {

                    $('#all_massages').empty();
                    $("#seperateMessage").show();
                    $('#all_massages').html(data['messages']);
                    $('#receiver_name').html(data['receiver_name']);
                },
                error: function (data) {
                    console.log("Error:", data);
                }
            });
        }
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
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "admin/course/ajax_get_course_students",
                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#student_id").find("option").remove();
                            $("#student_iddiv ul").find("li").remove();
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
    </script>
@endpush
