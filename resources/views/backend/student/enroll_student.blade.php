@extends('backend.master')
@php
    $table_name='course_enrolleds';
@endphp
@section('table'){{$table_name}}@stop

@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{__('student.Enrolled Student')}}</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">{{__('student.Students')}}</a>
                    <a href="#">{{__('student.Enrolled Student')}}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center mt-50">
                <div class="col-lg-12">
                    <div class="white_box mb_30">
                        <div class="white_box_tittle list_header">
                            <h4>{{__('student.Filter Enroll History')}}</h4>
                            
                        </div>
                        <form action="{{route('admin.enrollFilter')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-3 col-md-3 col-lg-3">
                                    <div class="primary_input ">
                                        <label class="primary_input_label"
                                               for="courseSelect">{{__('common.Select')}} {{__('courses.Course')}}</label>
                                    </div>
                                    <select class="primary_select" name="course" id="courseSelect">
                                        <option data-display="{{__('common.Select')}} {{__('courses.Course')}}"
                                                value="">{{__('common.Select')}} {{__('courses.Course')}}</option>
                                        @foreach($courses as $course)
                                            <option
                                                value="{{$course->id}}" {{isset($courseId)?$courseId==$course->id?'selected':'':''}}>{{@$course->title}} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xl-3 col-md-3 col-lg-3">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label"
                                               for="startDate">{{__('common.Select')}} {{__('common.Start Date')}}</label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input placeholder="{{__('common.Date')}}"
                                                               class="primary_input_field primary-input date form-control"
                                                               id="startDate" type="text" name="start_date"
                                                               value="{{isset($start)?!empty($start)?date('m/d/Y', strtotime($start)):'':''}}"
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

                                <div class="col-xl-3 col-lg-3">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label"
                                               for="admissionDate">{{__('common.Select')}} {{__('common.End Date')}}</label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input placeholder="{{__('common.Date')}}"
                                                               class="primary_input_field primary-input date form-control"
                                                               id="admissionDate" type="text" name="end_date"
                                                               value="{{isset($end)?!empty($end)?date('m/d/Y', strtotime($end)):'':''}}"
                                                               autocomplete="off">
                                                    </div>
                                                </div>
                                                <button class="" type="button">
                                                    <i class="ti-calendar" id="admission-date-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xl-3 mt-30">
                                    <div class="search_course_btn text-center">
                                        <button type="submit"
                                                class="primary-btn radius_30px mr-10 fix-gr-bg">{{__('common.Filter History')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('student.Enrolled Student')}} {{__('common.List')}}</h3>
                            <ul class="d-flex">
                                <li>
                                    <a class="primary-btn radius_30px mr-10 fix-gr-bg" data-toggle="modal" id="add_student_btn"  data-target="#enrol_student" href="#"><i class="ti-plus"></i>{{__('Enrol Student')}}</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">

                            <div class="">
                                <table id="lms_table" class="table Crm_table_active3">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('common.SL')}} </th>
                                        <th scope="col">{{__('common.Image')}} </th>
                                        <th scope="col">{{__('common.Name')}} </th>
                                        <!-- <th scope="col">{{__('common.Email Address')}} </th> -->
                                        <th scope="col">{{__('courses.Courses')}} {{__('courses.Enrolled')}}</th>
                                        <th scope="col">Batch Name</th>
                                        <th scope="col">{{__('courses.Enrollment')}} {{__('common.Date')}} </th>
                                        <th scope="col">{{__('common.Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($enrolls as $key => $enroll)

                                        <tr>

                                            <th>{{$key+1}}</th>

                                            <td>
                                                <div class="profile_info">
                                                    <img src="{{asset($enroll->user->image)}}"
                                                         alt="{{@$enroll->user->name}}'s image">
                                                </div>
                                            </td>
                                            <td>{{@$enroll->user->name}}</td>
                                            <!-- <td>{{@$enroll->user->email}}</td> -->
                                            <td>{{@$enroll->course->title}}</td>
                                            <td>{{@$enroll->batch->batch_name }}</td>
                                            <td>{{@$enroll->course->dateFormat}}</td>
                                            <td>
                                                <div class="dropdown CRM_dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenu2" data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        {{__('common.Action')}}
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         aria-labelledby="dropdownMenu2">

                                                        @if (permissionCheck('admin.enrollDelete'))
                                                            <a onclick="confirm_modal('{{route('admin.enrollDelete', $enroll->id)}}');"
                                                               class="dropdown-item edit_brand">{{__('common.Delete')}}</a>

                                                        @endif


                                                    </div>
                                                </div>

                                            </td>

                                        </tr>

                                        <div class="modal fade admin-query" id="rejectEnroll{{@$enroll->id}}">
                                            <div class="modal-dialog modal_1000px modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{__('common.Reject')}} {{__('courses.Enrollment')}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal"><i
                                                                class="ti-close "></i></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form action="{{route('rejectEnroll')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{@$enroll->id}}">
                                                            <input type="hidden" name="user_id"
                                                                   value="{{@$enroll->user_id}}">
                                                            <div class="form-group">
                                                                <label
                                                                    for="">{{__('common.Reject')}} {{__('common.Reason')}}
                                                                    *</label>
                                                                <textarea name="reason" class="lms_summernote" id=""
                                                                          placeholder="{{__('student.Reject Reason')}}"
                                                                          cols="30" rows="10"></textarea>
                                                            </div>

                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">{{__('common.Cancel')}}</button>
                                                                <button class="primary-btn fix-gr-bg"
                                                                        type="submit">{{__('common.Reject')}}</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade admin-query" id="enableEnroll{{@$enroll->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{__('common.Enable')}} {{__('courses.Enrollment')}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal"><i
                                                                class="ti-close "></i></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form action="{{route('enableEnroll')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{@$enroll->id}}">
                                                            <input type="hidden" name="user_id"
                                                                   value="{{@$enroll->user_id}}">
                                                            <div class="text-center">
                                                                <h4>{{__('common.Are you sure to enable this ?')}}</h4>
                                                            </div>

                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">{{__('common.Cancel')}}</button>
                                                                <button class="primary-btn fix-gr-bg"
                                                                        type="submit">{{__('common.Enable')}}</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add Modal Item_Details -->
                <!-- Add Modal Item_Details -->
                <div class="modal fade admin-query" id="enrol_student">
                    <div class="modal-dialog modal_500px modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Enrol a student</h4>
                                <button type="button" class="close " data-dismiss="modal">
                                    <i class="ti-close "></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form action="{{route('enrol.student')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="user_id">Student <strong
                                                        class="text-danger">*</strong></label>
                                                <select class="primary_select mb-25" name="user_id"
                                                        id="user_id">
                                                    @foreach ($students as $key => $student)
                                                        <option
                                                            value="{{ @$student->id }}" {{isset($edit)?(@$edit->user_id == @$student->id?'selected':''):''}} >{{ @$student->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="course_id">Courses <strong
                                                        class="text-danger">*</strong></label>
                                                <select class="primary_select mb-25" name="course_id"
                                                        id="course_id">
                                                    <option value='' >Select</option>
                                                    @foreach ($courses as $key => $c)
                                                        <option
                                                            value="{{ @$c->id }}" {{isset($edit)?(@$edit->batch->course->id == @$c->id?'selected':''):''}} >{{ @$c->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div id="batch_iddiv" class="primary_input mb-25">
                                                <label class="primary_input_label"
                                                       for="batch_id">Batch <strong
                                                        class="text-danger">*</strong></label>
                                                <select class="primary_select mb-25" name="batch_id" id="batch_id">
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                   
                                    <div class="col-lg-12 text-center pt_15">
                                        <div class="d-flex justify-content-center">
                                            <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent"
                                                    type="submit"><i
                                                    class="ti-check"></i> {{__('common.Save')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('backend.partials.delete_modal')

@endsection

@push('scripts')
    <script>
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
                url: url + "/" + "admin/course/ajax_get_course_batch",
                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        if (item.length) {
                            $("#batch_id").find("option").remove();
                            $("#batch_id ul").find("li").remove();
                            console.log(item);
                            $("#batch_id").append(
                                    $("<option>", {
                                        value: '',
                                        text: 'Select',
                                    })
                                );
                                $("#batch_iddiv ul").append(
                                    "<li data-value='' class='option'> Select </li>"
                                );
                            $.each(item, function(i, section) {
                                $("#batch_id").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.batch_name,
                                    })
                                );

                                $("#batch_iddiv ul").append(
                                    "<li data-value='" +
                                    section.id +
                                    "' class='option'>" +
                                    section.batch_name +
                                    "</li>"
                                );
                            });
                        } else {
                            $("#batch_id").find("option").remove();
                            $("#batch_iddiv ul").find("li").remove();
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
