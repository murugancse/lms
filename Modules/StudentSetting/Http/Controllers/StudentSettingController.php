<?php

namespace Modules\StudentSetting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Course;
use Illuminate\Support\Facades\Validator;
use Image;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\StudentsImport;

use DB;


class StudentSettingController extends Controller
{


    public function index()
    {
        try {
            $students = User::where('role_id', 3)->latest()->get();
            // $courses = $students[0]->enrollCourse;
            // foreach ($courses as $key => $course) {
            //     print_r($course->title);
            //     echo '<br>';
            // }
            // dd('ok');

            return view('studentsetting::student_list', compact('students'));

        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Session::flash('type', 'store');

        if (demoCheck()) {
            return redirect()->back();
        }
        $request->validate([
            'name' => 'required',
            'roll_number' => 'required|unique:users,roll_number',
            'nric' => 'required|unique:users,nric',
            'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {

            $success = trans('lang.Student') . ' ' . trans('lang.Added') . ' ' . trans('lang.Successfully');


            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->nric = $request->nric;
            $user->roll_number = $request->roll_number;
            $user->username = $request->email;
            $user->password = bcrypt($request->password);
            $user->about = $request->about;

            if (empty($request->phone)) {
                $user->phone = null;
            } else {
                $user->phone = $request->phone;
            }

            $user->dob = $request->dob;
            $user->facebook = $request->facebook;
            $user->twitter = $request->twitter;
            $user->linkedin = $request->linkedin;
            $user->youtube = $request->youtube;
            $user->language_id = getSetting()->language_id;
            $user->language_code = getSetting()->language->code;
            $user->language_name = getSetting()->language->name;
            $user->added_by = 1;
            $user->email_verify = 1;
            $user->email_verified_at = now();
            $user->referral = Str::random(10);


            if ($request->file('image') != "") {
                $file = $request->file('image');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/students/', $fileName);
                $fileName = 'public/uploads/students/' . $fileName;
                $user->image = $fileName;
            }

            $user->role_id = 3;

            $user->save();


            Toastr::success($success, 'Success');
            return redirect()->back();

        } catch (\Exception $e) {

            Toastr::error(trans("lang.Oops, Something Went Wrong"), trans('common.Failed'));
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('studentsetting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        Session::flash('type', 'update');

        if (demoCheck()) {
            return redirect()->back();
        }
        $request->validate([
            'name' => 'required',
            'nric' => 'required',
            'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|unique:users,phone,' . $request->id,
            'roll_number' => 'required|unique:users,roll_number,' . $request->id,
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'bail|nullable|min:8|confirmed',

        ]);


        try {
            if (Config::get('app.app_sync')) {
                Toastr::error('For demo version you can not change this !', 'Failed');
                return redirect()->back();
            } else {
                // $success = trans('lang.Student') .' '.trans('lang.Updated').' '.trans('lang.Successfully');

                $user = User::find($request->id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->nric = $request->nric;
                $user->roll_number = $request->roll_number;
                $user->username = $request->email;
                $user->phone = $request->phone;
                $user->dob = $request->dob;
                $user->facebook = $request->facebook;
                $user->twitter = $request->twitter;
                $user->linkedin = $request->linkedin;
                $user->youtube = $request->youtube;
                $user->about = $request->about;
                $user->email_verify = 1;

                if ($request->password) {
                    $user->password = bcrypt($request->password);
                }


                if ($request->file('image') != "") {
                    $file = $request->file('image');
                    $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('public/uploads/students/', $fileName);
                    $fileName = 'public/uploads/students/' . $fileName;
                    $user->image = $fileName;
                }

                $user->role_id = 3;
                $user->save();


            }


            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();

        } catch (\Exception $e) {

            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $success = trans('lang.Student') . ' ' . trans('lang.Deleted') . ' ' . trans('lang.Successfully');

            $user = User::findOrFail($request->id);
            $user->delete();

            Toastr::success($success, 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function Enrolstore(Request $request){
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'batch_id' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $course = Course::find($request->course_id);
            $enrolled = $course->total_enrolled;
            $course->total_enrolled = ($enrolled + 1);
           // dd($course);

            $enroll = new CourseEnrolled();
            //$instractor = User::find($cart->instructor_id);
            $enroll->user_id = $request->user_id;
            $enroll->tracking = getTrx();
            $enroll->course_id = $course->id;
            $enroll->batch_id = $request->batch_id;
            $enroll->purchase_price = $course->price;
            $enroll->coupon = null;
            $enroll->discount_amount = $course->discount_price;
            $enroll->status = 1;
            $enroll->reveune = 0;
            $enroll->save();
            //dd($enroll);
            $course->save();

            DB::commit();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function EnrolUpdate(Request $request){

        $validator = Validator::make($request->all(), [
            'editbatch_id' => 'required',
            'editcourse_id' => 'required',
            'edituser_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $course = Course::find($request->course_id);
           
            $enroll = CourseEnrolled::find($request->auto_id);
            //$instractor = User::find($cart->instructor_id);
            $enroll->user_id = $request->edituser_id;
            $enroll->course_id = $request->editcourse_id;
            $enroll->batch_id = $request->editbatch_id;
            $enroll->status = 1;
            $enroll->save();

            DB::commit();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function importExportView()
    {
       return view('studentsetting::import');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() 
    {
        Excel::import(new StudentsImport,request()->file('file'));
         
        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }
}
