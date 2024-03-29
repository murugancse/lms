@extends('backend.master')
@section('mainContent')
    @include("backend.partials.alertMessage")
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> Subject List</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('common.Dashboard')}}</a>
                    <a href="#">Subject</a>
                    <a class="active" href="#">Subject List</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-20">@if(isset($subject))
                                        {{__('common.Edit')}}
                                    @else
                                        {{__('common.Add')}}
                                    @endif
                                    Subject
                                </h3>
                            </div>
                            @if(isset($subject))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('subject-update',@$subject->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                            @else
                                
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subject.store',
                                    'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            @if(session()->has('message-success'))
                                                <div class="alert alert-success">
                                                    {{ session()->get('message-success') }}
                                                </div>
                                            @elseif(session()->has('message-danger'))
                                                <div class="alert alert-danger">
                                                    {{ session()->get('message-danger') }}
                                                </div>
                                            @endif
                                            <div class="input-effect">
                                                <label>Subject Name <span
                                                        class="text-danger">*</span></label>
                                                <input
                                                    class="primary_input_field{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                    type="text" name="title" autocomplete="off"
                                                    value="{{isset($subject)? $subject->title:''}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($subject)? $subject->id: ''}}">
                                                <span class="focus-border"></span>
                                                @if ($errors->has('title'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    @php
                                        $tooltip = "";
                                        
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                                    title="{{$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($subject))
                                                    {{__('common.Update')}}
                                                @else
                                                    {{__('common.Save')}}
                                                @endif

                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="main-title">
                        <h3 class="mb-20">Subject List</h3>
                    </div>

                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">
                            <!-- table-responsive -->
                            <div class="">
                                <table id="lms_table" class="table Crm_table_active3">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('common.ID') }}</th>
                                        <th scope="col">Subject Name</th>
                                        <th scope="col">{{ __('common.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($subjects as $key => $subject)
                                        <tr>
                                            <th>{{ $key+1 }}</th>

                                            <td>{{@$subject->title }}</td>
                                            <td>
                                                <!-- shortby  -->
                                                <div class="dropdown CRM_dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenu2" data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        {{ __('common.Select') }}
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         aria-labelledby="dropdownMenu2">
                                                        @if (permissionCheck('question-group.edit'))
                                                            <a class="dropdown-item edit_brand"
                                                               href="{{route('subject-edit',$subject->id)}}">{{__('common.Edit')}}</a>
                                                        @endif
                                                        @if (permissionCheck('question-group.delete'))
                                                            <a class="dropdown-item" data-toggle="modal"
                                                               data-target="#deleteQuestionGroupModal{{$subject->id}}"
                                                               href="#">{{__('common.Delete')}}</a>
                                                        @endif

                                                    </div>
                                                </div>
                                                <!-- shortby  -->
                                            </td>
                                        </tr>


                                        <div class="modal fade admin-query" id="deleteQuestionGroupModal{{$subject->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{__('common.Delete')}} {{__('quiz.Question Group')}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal"><i
                                                                class="ti-close "></i></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4> {{__('common.Are you sure to delete ?')}}</h4>
                                                        </div>

                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">{{__('common.Cancel')}}</button>
                                                            {{ Form::open(['route' => array('subject-delete',$subject->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                            <button class="primary-btn fix-gr-bg"
                                                                    type="submit">{{__('common.Delete')}}</button>
                                                            {{ Form::close() }}
                                                        </div>
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
            </div>
        </div>
    </section>
    <div id="edit_form">

    </div>
    <div id="view_details">

    </div>
    <input type="hidden" name="status_route" class="status_route" value="{{ route('coupons.status_update') }}">

    {{-- @include('coupons::create') --}}
    @include('backend.partials.delete_modal')
@endsection
@push('scripts')
    <script src="{{asset('public/backend/js/category.js')}}"></script>
@endpush
