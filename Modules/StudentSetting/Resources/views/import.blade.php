@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/student_list.css')}}"/>
@endpush
@php
    $table_name='users';
@endphp
@section('table'){{$table_name}}@endsection

@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{__('student.Students')}}</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">{{__('dashboard.Dashboard')}}</a>
                    <a href="#">{{__('student.Students')}}</a>
                    <a href="#">Import</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
               
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">Import</h3>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="white_box mb_30">
                        
                        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                
                                <div class="col-xl-3 col-md-3 col-lg-3">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label"
                                               for="startDate">File</label>
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3 col-xl-3 mt-30">
                                    <div class="search_course_btn text-center">
                                        <button type="submit"
                                                class="primary-btn radius_30px mr-10 fix-gr-bg">Import</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Add Modal Item_Details -->
                <div class="modal fade admin-query" id="add_student">
                    <div class="modal-dialog modal_1000px modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{__('student.Add New Student')}}</h4>
                                <button type="button" class="close " data-dismiss="modal">
                                    <i class="ti-close "></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form action="{{route('student.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.Name')}} <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" name="name" placeholder="-"
                                                       type="text" id="addName"
                                                       value="{{ old('name') }}" {{$errors->first('name') ? 'autofocus' : ''}}>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{__('common.RollNo')}} <strong
                                                        class="text-danger">*</strong></label>
                                                <input class="primary_input_field" name="roll_number" placeholder="-"
                                                       type="text" id="roll_number" value="{{ old('roll_number') }}" {{$errors->first('roll_number') ? 'autofocus' : ''}}>
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
