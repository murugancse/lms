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
use Modules\CourseSetting\Entities\Grade;
use Modules\CourseSetting\Entities\Subject;
use App\TableList;

use App\Imports\StudentsImport;

use DB;


class StudentSettingController extends Controller
{


    public function index()
    {
        try {
            $students = User::where('role_id', 3)->latest()->get();
            $grades = Grade::where('status', 1)->orderBy('title', 'asc')->get();
           
            return view('studentsetting::student_list', compact('students','grades'));

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
            $user->grade = $request->grade;

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
                $user->grade = $request->grade;
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

    public function gradeIndex()
    {
        try{
            $grades = Grade::get();
            return view('studentsetting::grade.index', compact('grades'));
        }catch (\Exception $e) {
           Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
           return redirect()->back();
        }
    }

    public function gradeStore(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $request->validate([
            'title' => "required|unique:grades"
        ]);
        try{
            $group = new Grade();
            $group->title = $request->title;
            $group->status = 1;
            $result = $group->save();
            if ($result) {
                Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                return redirect()->back();
            } else {
                Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
           return redirect()->back();
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gradeShow($id)
    {
        try{
            $grade = Grade::find($id);

            $grades = Grade::get();
            return view('studentsetting::grade.index', compact('grades', 'grade'));
        }catch (\Exception $e) {
            // dd($e);
           Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
           return redirect()->back();
        }
    }

    public function gradeUpdate(Request $request, $id)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $request->validate([
            'title' => "required|unique:grades,title," . $request->id
        ]);
        try{

            $group = Grade::find($request->id);

            $group->title = $request->title;
            $group->status = 1;
            $result = $group->save();
            if ($result) {
                Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                return redirect('admin/grade');
            } else {
                Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
           return redirect()->back();
        }
    }
    public function gradeDestroy($id)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $tables = TableList::getTableList('grade_id', $id);

        try{
            if ($tables==null) {
                
                $group = Grade::destroy($id);

                if ($group) {
                    Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                    return redirect('admin/grade');
                } else {
                    Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                    return redirect()->back();
                }
            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }


        }catch (\Exception $e) {
           $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
           return redirect()->back();
        }
    }

    public function subjectIndex()
    {
        try{
            $subjects = Subject::get();
            return view('studentsetting::subject.index', compact('subjects'));
        }catch (\Exception $e) {
           Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
           return redirect()->back();
        }
    }

    public function subjectStore(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $request->validate([
            'title' => "required|unique:subjects"
        ]);
        try{
            $subject = new Subject();
            $subject->title = $request->title;
            $subject->status = 1;
            $result = $subject->save();
            if ($result) {
                Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                return redirect()->back();
            } else {
                Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
           return redirect()->back();
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subjectShow($id)
    {
        try{
            $subject = Subject::find($id);

            $subjects = Subject::get();
            return view('studentsetting::subject.index', compact('subjects', 'subject'));
        }catch (\Exception $e) {
            // dd($e);
           Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
           return redirect()->back();
        }
    }

    public function subjectUpdate(Request $request, $id)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $request->validate([
            'title' => "required|unique:subjects,title," . $request->id
        ]);
        try{

            $subject = Subject::find($request->id);

            $subject->title = $request->title;
            $subject->status = 1;
            $result = $subject->save();
            if ($result) {
                Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                return redirect('admin/subject');
            } else {
                Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
           return redirect()->back();
        }
    }
    public function subjectDestroy($id)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $tables = TableList::getTableList('subject_id', $id);

        try{
            if ($tables==null) {
                
                $subject = Subject::destroy($id);

                if ($subject) {
                    Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                    return redirect('admin/subject');
                } else {
                    Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                    return redirect()->back();
                }
            } else {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }


        }catch (\Exception $e) {
           $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error($msg, 'Failed');
           return redirect()->back();
        }
    }
}
