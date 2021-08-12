@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/student_list.css')}}"/>
@endpush
@php
    $table_name='parents';
@endphp
@section('table'){{$table_name}}@endsection

@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>Parents</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">Parents</a>
                    <a href="#">Parents List</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <ul class="d-flex float-right">
                        <li><a class="primary-btn radius_30px mr-10 fix-gr-bg" id="add_student_btn" href="{{asset('storage/app/parents/sample.xlsx')}}"><i
                                                class="ti-arrow-down"></i>Download Sample</a></li>
                        <li><a class="primary-btn radius_30px mr-10 fix-gr-bg" id="add_student_btn" href="{{route('parent.importview')}}"><i
                                                class="ti-arrow-up"></i>Upload</a></li>
                    </ul>
                    <br>
                    <br>
                </div>

                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">Parents List</h3>
                            @if (permissionCheck('student.store'))
                                <ul class="d-flex">
                                    <li><a href="{{route('AddParent')}}" class="primary-btn radius_30px mr-10 fix-gr-bg"><i
                                                class="ti-plus"></i>Add Parent</a></li>

                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">
                            <!-- table-responsive -->
                            <div class="">
                                <table id="lms_table" class="table Crm_table_active3">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('common.SL')}}</th>
                                        <th scope="col">Parent Name*</th>
                                        <th scope="col">Parent IC*</th>
                                        <th scope="col">Parent Email*</th>
                                        <th scope="col">Student Name*</th>
                                        <th scope="col">Student IC*</th>
                                        <th scope="col">{{__('common.Status')}}</th>
                                        <th scope="col">{{__('common.Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($parents as $key => $parent)
                                        @php

                                        @endphp
                                        <tr>
                                            <th>{{$key+1}}</th>

                                            <td>{{@$parent->parent_name}}</td>
                                            <td>{{@$parent->parent_ic}}</td>
                                            <td>{{@$parent->parent_email}}</td>
                                            <td>{{@$parent->student_name}}</td>
                                            <td>{{@$parent->student_ic}}</td>
                                            <td class="nowrap">
                                                <span id="spanstatus{{@$parent->id }}">
                                                    @if (@$parent->status == 1) Active @else Inactive @endif
                                                </span>
                                                <br>
                                                <label class="switch_toggle" for="active_checkbox{{@$parent->id }}">
                                                    <input type="checkbox"
                                                           class="@if (permissionCheck('student.change_status')) status_enable_disable @endif "
                                                           id="active_checkbox{{@$parent->id }}"
                                                           @if (@$parent->status == 1) checked
                                                           @endif value="{{@$parent->id }}">
                                                    <i class="slider round"></i>
                                                </label>
                                            </td>

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
                                                        @if (permissionCheck('student.edit'))
                                                            <button data-item="{{$parent}}"
                                                                    class="dropdown-item editStudent"
                                                                    type="button">{{__('common.Edit')}}</button>
                                                        @endif

                                                        @if (permissionCheck('student.delete'))
                                                            <button class="dropdown-item deleteStudent"
                                                                    data-id="{{$parent->id}}"
                                                                    type="button">{{__('common.Delete')}}</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add Modal Item_Details -->
                 <div class="modal fade admin-query" id="deleteStudent">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{__('common.Delete')}} {{__('student.Student')}} </h4>
                                <button type="button" class="close" data-dismiss="modal"><i
                                        class="ti-close "></i></button>
                            </div>

                            <div class="modal-body">
                                <form action="{{route('student.delete')}}" method="post">
                                    @csrf

                                    <div class="text-center">

                                        <h4>{{__('common.Are you sure to delete ?')}} </h4>
                                    </div>
                                    <input type="hidden" name="id" value="" id="studentDeleteId">
                                    <div class="mt-40 d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg"
                                                data-dismiss="modal">{{__('common.Cancel')}}</button>

                                        <button class="primary-btn fix-gr-bg"
                                                type="submit">{{__('common.Delete')}}</button>

                                    </div>
                                </form>
                            </div>

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
            $('#add_student').modal('show');
            @else
            $('#editStudent').modal('show');
            @endif
            @endif
        </script>
    @endif
    <script src="{{asset('public/backend/js/student_list.js')}}"></script>
@endpush
