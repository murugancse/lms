@extends('backend.master')
@php
    $table_name='batches';
@endphp
@section('table'){{$table_name}}@endsection
@section('mainContent')
    @include("backend.partials.alertMessage")

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>Exams</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('common.Dashboard')}}</a>
                    <a href="#">{{__('Batches')}}</a>
                    <a class="active" href="{{route('getAllExam')}}">Exams</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px"> @if(!isset($edit)) Add New Exam @else Update Exam @endif</h3>
                        </div>
                    </div>
                    <div class="white-box mb_30">
                        @if (isset($edit))
                            <form action="{{route('exam.update')}}" method="POST" id="batch-form"
                                  name="batch-form" enctype="multipart/form-data">
                                <input type="hidden" name="id"
                                       value="{{@$edit->id}}">
                                @else
                                    @if (permissionCheck('course.subcategory.store'))
                                        <form action="{{route('exam.store') }}" method="POST"
                                              id="batch-form" name="batch-form" enctype="multipart/form-data">
                                            @endif
                                            @endif

                                            @csrf

                                            <div class="row">
												 

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
                                                        <select class="primary_select mb-25" name="batch_id"
                                                                id="batch_id">
                                                           @foreach ($batcheslist as $key => $batch)
                                                                <option
                                                                    value="{{ @$batch->id }}" {{isset($edit)?(@$edit->batch_id == @$batch->id?'selected':''):''}} >{{ @$batch->batch_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
												<div class="col-xl-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                               for="user_id">Student <strong
                                                                class="text-danger">*</strong></label>
                                                        <select class="primary_select mb-25" multiple name="user_id[]"
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
                                                               for="exam_date">Exam Date <strong
                                                                class="text-danger">*</strong></label>
                                                        <input name="exam_date" id="exam_date"
                                                               class="primary_input_field exam_date {{ @$errors->has('exam_date') ? ' is-invalid' : '' }}"
                                                               placeholder="exam_date" type="date"
                                                               value="{{isset($edit)?@$edit->exam_date:old('exam_date')}}">
                                                        @if ($errors->has('exam_date'))
                                                            <span class="invalid-feedback d-block mb-10" role="alert">
                                            <strong>{{ @$errors->first('exam_date') }}</strong>
                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
												

                                                <div class="col-xl-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                               for="status">{{ __('courses.Status') }}</label>
                                                        <select class="primary_select mb-25" name="status" id="status"
                                                        >
                                                            <option
                                                                value="1" {{isset($edit)?(@$edit->status==1?'selected':''):''}}>{{__('common.Active') }}</option>
                                                            <option
                                                                value="0" {{isset($edit)?(@$edit->status==0?'selected':''):''}}>{{__('common.Inactive') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-center">
                                                    <div class="d-flex justify-content-center pt_20">
                                                        <button type="submit" class="primary-btn semi_large fix-gr-bg"
                                                                data-toggle="tooltip"
                                                                id="save_button_parent">
                                                            <i class="ti-check"></i>
                                                            @if(!isset($edit)) {{ __('common.Save') }} @else {{ __('common.Update') }} @endif
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0">Exams List</h3>
                        </div>
                    </div>
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">
                            <!-- table-responsive -->
                            <div class="">
                                <table id="lms_table" class="table Crm_table_active3">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('common.ID') }}</th>
                                        <th scope="col">Batch Name</th>
                                        <th scope="col">Course Name</th>
                                        <th scope="col">user</th>
                                        <th scope="col">Exam Date</th>
                                        <th scope="col">{{ __('common.Status') }}</th>
                                        <th scope="col">{{ __('common.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@php
										
									@endphp
                                    @foreach($exams as $key => $exam)
                                        <tr>
                                            <th class="m-2">{{ $key+1 }}</th>
                                            <td>{{@$exam->batch->batch_name }}</td>
                                            <td>{{@$exam->batch->course->title }}</td>
                                            <td>{{@$exam->user->name }}</td>
                                            <td>{{@$exam->exam_date }}</td>
                                            <td>{{@$exam->status==1 ? 'Active' : 'Inactive' }}</td>

                                            <td>
                                                <!-- shortby  -->
                                                <div class="dropdown CRM_dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenu1{{ @$exam->id }}"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        {{ __('common.Select') }}
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         aria-labelledby="dropdownMenu1{{ @$exam->id }}">
													
														<a class="dropdown-item edit_brand"
														   href="{{route('exam.edit',@$exam->id)}}">{{__('common.Edit')}}</a>
													
														<a onclick="confirm_modal('{{route('exam.delete', @$exam->id)}}');"
														   class="dropdown-item edit_brand">{{__('common.Delete')}}</a>
													
                                                    </div>
                                                </div>
                                                <!-- shortby  -->
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="edit_form">

    </div>
    <div id="view_details">

    </div>
    <input type="hidden" name="status_route" class="status_route"
           value="{{ route('course.subcategory.status_update') }}">

    @include('backend.partials.delete_modal')
@endsection
@push('scripts')
    <script src="{{asset('public/backend/js/category.js')}}"></script>
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
                            $("#batch_id").find("option").not(":first").remove();
                            $("#batch_id ul").find("li").not(":first").remove();
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
                            $("#batch_id").find("option").not(":first").remove();
                            $("#batch_iddiv ul").find("li").not(":first").remove();
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
