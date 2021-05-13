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
                <h1>Batches</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('common.Dashboard')}}</a>
                    <a href="#">{{__('courses.Course')}}</a>
                    <a class="active" href="{{route('getAllBatch')}}">Batches</a>
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
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px"> @if(!isset($edit)) Add New Batch @else Update Batch @endif</h3>
                        </div>
                    </div>
                    <div class="white-box mb_30">
                        @if (isset($edit))
                            <form action="{{route('batch.update')}}" method="POST" id="batch-form"
                                  name="batch-form" enctype="multipart/form-data">
                                <input type="hidden" name="id"
                                       value="{{@$edit->id}}">
                                @else
                                    @if (permissionCheck('course.subcategory.store'))
                                        <form action="{{route('batch.store') }}" method="POST"
                                              id="batch-form" name="batch-form" enctype="multipart/form-data">
                                            @endif
                                            @endif

                                            @csrf

                                            <div class="row">
												 <div class="col-xl-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                               for="batch_name">Batch Name <strong
                                                                class="text-danger">*</strong></label>
                                                        <input name="batch_name" id="batch_name"
                                                               class="primary_input_field batch_name {{ @$errors->has('batch_name') ? ' is-invalid' : '' }}"
                                                               placeholder="{{ __('common.Name') }}" type="text"
                                                               value="{{isset($edit)?@$edit->batch_name:old('batch_name')}}">
                                                        @if ($errors->has('batch_name'))
                                                            <span class="invalid-feedback d-block mb-10" role="alert">
                                            <strong>{{ @$errors->first('batch_name') }}</strong>
                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-xl-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                               for="course_id">Courses <strong
                                                                class="text-danger">*</strong></label>
                                                        <select class="primary_select mb-25" name="course_id"
                                                                id="course_id">
                                                            @foreach ($courses as $key => $c)
                                                                <option
                                                                    value="{{ @$c->id }}" {{isset($edit)?(@$edit->course->id == @$c->id?'selected':''):''}} >{{ @$c->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
												<div class="col-xl-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                               for="start_date">Start Date <strong
                                                                class="text-danger">*</strong></label>
                                                        <input name="start_date" id="start_date"
                                                               class="primary_input_field start_date {{ @$errors->has('start_date') ? ' is-invalid' : '' }}"
                                                               placeholder="start_date" type="date"
                                                               value="{{isset($edit)?@$edit->start_date:old('start_date')}}">
                                                        @if ($errors->has('start_date'))
                                                            <span class="invalid-feedback d-block mb-10" role="alert">
                                            <strong>{{ @$errors->first('start_date') }}</strong>
                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
												
												<div class="col-xl-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                               for="end_date">End Date <strong
                                                                class="text-danger">*</strong></label>
                                                        <input name="end_date" id="end_date"
                                                               class="primary_input_field end_date {{ @$errors->has('end_date') ? ' is-invalid' : '' }}"
                                                               placeholder="end_date" type="date"
                                                               value="{{isset($edit)?@$edit->end_date:old('end_date')}}">
                                                        @if ($errors->has('end_date'))
                                                            <span class="invalid-feedback d-block mb-10" role="alert">
                                            <strong>{{ @$errors->first('end_date') }}</strong>
                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
												<div class="col-xl-12">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label"
                                                               for="batch_location">Batch Location </label>
                                                        <input name="batch_location" id="batch_location"
                                                               class="primary_input_field batch_location {{ @$errors->has('batch_location') ? ' is-invalid' : '' }}"
                                                               placeholder="{{ __('common.Name') }}" type="text"
                                                               value="{{isset($edit)?@$edit->batch_location:old('batch_location')}}">
                                                        @if ($errors->has('batch_location'))
                                                            <span class="invalid-feedback d-block mb-10" role="alert">
                                            <strong>{{ @$errors->first('batch_location') }}</strong>
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
                            <h3 class="mb-0">Batch List</h3>
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
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">{{ __('common.Status') }}</th>
                                        <th scope="col">{{ __('common.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($batches as $key => $batch)
                                        <tr>
                                            <th class="m-2">{{ $key+1 }}</th>
                                            <td>{{@$batch->batch_name }}</td>
                                            <td>{{@$batch->course->title }}</td>
                                            <td>{{@$batch->start_date }}</td>
                                            <td>{{@$batch->end_date }}</td>
                                            <td>{{@$batch->status==1 ? 'Active' : 'Inactive' }}</td>

                                            <td>
                                                <!-- shortby  -->
                                                <div class="dropdown CRM_dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenu1{{ @$batch->id }}"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        {{ __('common.Select') }}
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         aria-labelledby="dropdownMenu1{{ @$batch->id }}">
													
														<a class="dropdown-item edit_brand"
														   href="{{route('batch.edit',@$batch->id)}}">{{__('common.Edit')}}</a>
													
														<a onclick="confirm_modal('{{route('batch.delete', @$batch->id)}}');"
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
@endpush
