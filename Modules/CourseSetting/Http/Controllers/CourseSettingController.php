<?php

namespace Modules\CourseSetting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Modules\AmazonS3\Http\Controllers\AmazonS3Controller;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Chapter;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\CourseExercise;
use Modules\CourseSetting\Entities\CourseLevel;
use Modules\CourseSetting\Entities\Lesson;
use Modules\CourseSetting\Entities\SubCategory;
use Modules\CourseSetting\Entities\Batch;
use Modules\CourseSetting\Entities\BatchExam;
use Modules\Localization\Entities\Language;
use Modules\Newsletter\Http\Controllers\GetResponseController;
use Modules\Newsletter\Http\Controllers\MailchimpController;
use Modules\Payment\Entities\Cart;
use Modules\Quiz\Entities\OnlineQuiz;
use Modules\Setting\Model\GeneralSetting;
use Modules\SystemSetting\Entities\GeneralSettings;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\Validator;

use Modules\CourseSetting\Entities\Grade;
use Modules\CourseSetting\Entities\Subject;


class CourseSettingController extends Controller
{
    public function getSubscriptionList()
    {
        $list = [];

        try {
            $user = Auth::user();
            if ($user->subscription_method == "Mailchimp" && $user->subscription_api_status == 1) {
                $mailchimp = new MailchimpController();
                $mailchimp->mailchimp($user->subscription_api_key);
                $getlists = $mailchimp->mailchimpLists();
                foreach ($getlists as $key => $l) {
                    $list[$key]['name'] = $l['name'];
                    $list[$key]['id'] = $l['id'];
                }

            } elseif ($user->subscription_method == "GetResponse" && $user->subscription_api_status == 1) {
                $getResponse = new GetResponseController();
                $getResponse->getResponseApi($user->subscription_api_key);
                $getlists = $getResponse->getResponseLists();
                foreach ($getlists as $key => $l) {
                    $list[$key]['name'] = $l->name;
                    $list[$key]['id'] = $l->campaignId;
                }
            }
        } catch (\Exception $exception) {

        }
        return $list;

    }

    public function ajaxGetCourseSubCategory(Request $request)
    {
        try {
            $sub_categories = SubCategory::where('category_id', '=', $request->id)->get();

            return response()->json([$sub_categories]);
        } catch (Exception $e) {
            return response()->json("", 404);
        }
    }

    public function courseSortByCat($id)
    {
        try {
            if (!empty($id))
                $courses = Course::whereHas('enrolls')
                    ->where('category_id', $id)->with('user', 'category', 'subCategory', 'enrolls', 'comments', 'reviews', 'lessons')->paginate(15);
            else
                $courses = Course::whereHas('enrolls')->with('user', 'category', 'subCategory', 'enrolls', 'comments', 'reviews', 'lessons')->paginate(15);

            return response()->json([
                'courses' => $courses
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Oops, Something Went Wrong")]);
        }
    }

    public function getAllCourse()
    {
        try {
            try {
                $vimeo_video_list = Vimeo::request('/me/videos', ['per_page' => 10], 'GET');
            } catch (\Exception $e) {
                $vimeo_video_list = [];
            }
            if (isset($vimeo_video_list['body']['data'])) {
                $video_list = $vimeo_video_list['body']['data'];
            } else {
                $video_list = [];
            }
            $query = Course::with('user', 'category', 'subCategory', 'quiz', 'enrolls', 'lessons')->whereIn('type', [1, 2]);
            if (isInstructor()) {
                $query->where('user_id', '=', Auth::id());
            }
            $courses = $query->orderBy('id', 'desc')->get();
            $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
            $categories = Category::get();
            $quizzes = OnlineQuiz::all();
            $instructors = User::where('role_id', 2)->get();
            $languages = Language::select('id', 'native', 'code')
                ->where('status', '=', 1)
                ->get();
            $levels = CourseLevel::where('status', 1)->get();
            $title = trans('courses.All');

            $sub_lists = $this->getSubscriptionList();
            return view('coursesetting::courses', compact('sub_lists', 'levels', 'video_list', 'title', 'quizzes', 'courses', 'getsmSetting', 'categories', 'languages', 'instructors'));
        } catch (Exception $e) {

            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();

        }
    }

    public function AddCourse(){
        $categories = Category::get();
        $quizzes = OnlineQuiz::all();
        $levels = CourseLevel::where('status', 1)->get();
        $languages = Language::select('id', 'native', 'code')
                ->where('status', '=', 1)
                ->get();

        $subjects = Subject::get();
        $grades = Grade::get();

        try {
            $vimeo_video_list = Vimeo::request('/me/videos', ['per_page' => 10], 'GET');
        } catch (\Exception $e) {
            $vimeo_video_list = [];
        }
        if (isset($vimeo_video_list['body']['data'])) {
            $video_list = $vimeo_video_list['body']['data'];
        } else {
            $video_list = [];
        }
        $sub_lists = $this->getSubscriptionList();
        $title = 'New Course';
        // return $singleMessages;
        return view('coursesetting::courses_new', compact('categories','quizzes','levels','languages','video_list','sub_lists','grades','subjects'));
    }

    public function courseSortBy(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            try {
                $vimeo_video_list = Vimeo::request('/me/videos', ['per_page' => 10], 'GET');
            } catch (\Exception $e) {
                $vimeo_video_list = [];
            }
            if (isset($vimeo_video_list['body']['data'])) {
                $video_list = $vimeo_video_list['body']['data'];
            } else {
                $video_list = [];
            }
            $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
            $categories = Category::get();
            $instructors = User::where('role_id', 2)->get();
            $quizzes = OnlineQuiz::all();
            $languages = Language::select('id', 'native', 'code')
                ->where('status', '=', 1)
                ->get();


            $courses = Course::query();
            // $courses->where('active_status', 1);
            if ($request->category != "") {
                $courses->where('category_id', $request->category);
            }
            if ($request->type != "") {
                $courses->where('type', $request->type);
            } else {
                $courses->whereIn('type', [1, 2]);
            }
            if ($request->instructor != "") {
                $courses->where('user_id', $request->instructor);
            }
            if ($request->publish != "") {
                $courses->where('publish', $request->publish);
            }

            if ($request->category) {
                $category_search = $request->category;
            } else {
                $category_search = '';

            }

            if ($request->type) {
                $category_type = $request->type;
            } else {
                $category_type = '';

            }

            if ($request->instructor) {
                $category_instructor = $request->instructor;
            } else {
                $category_instructor = '';

            }

            if ($request->publish) {
                $category_publish = $request->publish;
            } else {
                $category_publish = '';

            }


            $courses = $courses->with('user', 'category', 'subCategory', 'enrolls', 'lessons')->orderBy('id', 'desc')->get();

            $levels = CourseLevel::where('status', 1)->get();
            $sub_lists = $this->getSubscriptionList();
            return view('coursesetting::courses', compact('sub_lists','levels', 'category_search', 'category_instructor', 'category_type', 'category_publish', 'video_list', 'quizzes', 'courses', 'getsmSetting', 'categories', 'languages', 'instructors'));

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function getActiveCourse()
    {
        try {
            try {
                $vimeo_video_list = Vimeo::request('/me/videos', ['per_page' => 10], 'GET');
            } catch (\Exception $e) {
                $vimeo_video_list = [];
            }
            if (isset($vimeo_video_list['body']['data'])) {
                $video_list = $vimeo_video_list['body']['data'];
            } else {
                $video_list = [];
            }
            $query = Course::with('user', 'category', 'subCategory', 'quiz', 'enrolls', 'lessons')->where('status', 1)->whereIn('type', [1, 2]);
            if (isInstructor()) {
                $query->where('user_id', '=', Auth::id());
            }
            $courses = $query->orderBy('id', 'desc')->get();
            $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
            $categories = Category::get();
            $instructors = User::whereIn('role_id', [1, 2])->get();
            $languages = Language::select('id', 'native', 'code')
                ->where('status', '=', 1)
                ->get();
            $quizzes = OnlineQuiz::all();
            // return $categories ;
            $title = trans('courses.Active');
            $levels = CourseLevel::where('status', 1)->get();
            $sub_lists = $this->getSubscriptionList();
            return view('coursesetting::courses', compact('sub_lists','levels', 'video_list', 'title', 'quizzes', 'courses', 'getsmSetting', 'categories', 'languages', 'instructors'));

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();

        }

    }

    public function getPendingCourse()
    {
        try {
            try {
                $vimeo_video_list = Vimeo::request('/me/videos', ['per_page' => 10], 'GET');
            } catch (\Exception $e) {
                $vimeo_video_list = [];
            }
            if (isset($vimeo_video_list['body']['data'])) {
                $video_list = $vimeo_video_list['body']['data'];
            } else {
                $video_list = [];
            }
            $query = Course::with('user', 'category', 'subCategory', 'quiz', 'enrolls', 'lessons')->where('status', 0)->whereIn('type', [1, 2]);
            if (isInstructor()) {
                $query->where('user_id', '=', Auth::id());
            }
            $courses = $query->orderBy('id', 'desc')->get();
            $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
            $categories = Category::get();
            $instructors = User::whereIn('role_id', [1, 2])->get();
            $languages = Language::select('id', 'native', 'code')
                ->where('status', '=', 1)
                ->get();
            $quizzes = OnlineQuiz::all();
            // return $categories ;
            $title = trans('courses.Pending');
            $levels = CourseLevel::where('status', 1)->get();
            $sub_lists = $this->getSubscriptionList();
            return view('coursesetting::courses', compact('sub_lists','levels', 'video_list', 'title', 'quizzes', 'courses', 'getsmSetting', 'categories', 'languages', 'instructors'));

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();

        }

    }


    public function saveCourse(Request $request)
    {

        Session::flash('type', 'store');

        $this->validate($request, [
            'type' => 'required',
            'language' => 'required',
            'title' => 'required',
            'image' => 'required',

        ]);


        if ($request->type == 1) {
            $request->validate([
                'duration' => 'required',
                'level' => 'required',
                'host' => 'required',

            ]);
            if ($request->get('host') == "Vimeo") {
                $request->validate([
                    'vimeo' => 'required',
                ]);
            } elseif ($request->get('host') == "Youtube") {
                $request->validate([
                    'trailer_link' => 'required',
                ]);
            } else {
                $request->validate([
                    'file' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
                ]);
            }
        }


        try {

            if (!empty($request->image)) {
                $course = new Course();
                $fileName = "";
                if ($request->hasFile('image')) {

                    $strpos = strpos($request->image, ';');
                    $sub = substr($request->image, 0, $strpos);
                    $name = md5($request->title . rand(0, 1000)) . '.' . 'png';
                    $img = Image::make($request->image);
//                    $img->resize(800, 500);
                    $upload_path = 'public/uploads/courses/';
                    $img->save($upload_path . $name);
                    $course->image = 'public/uploads/courses/' . $name;

                    $strpos = strpos($request->image, ';');
                    $sub = substr($request->image, 0, $strpos);
                    $name = md5($request->title . rand(0, 1000)) . '.' . 'png';
                    $img = Image::make($request->image);
//                    $img->resize(270, 181);
                    $upload_path = 'public/uploads/courses/';
                    $img->save($upload_path . $name);
                    $course->thumbnail = 'public/uploads/courses/' . $name;
                }

                $course->user_id = Auth::id();
                if ($request->type == 1) {
                    $course->quiz_id = null;
                    $course->category_id = $request->category;
                    $course->subcategory_id = $request->sub_category;
                } elseif ($request->type == 2) {
                    $course->quiz_id = $request->quiz;
                    $course->category_id = null;
                    $course->subcategory_id = null;
                }

                $course->lang_id = $request->language;
                $course->title = $request->title;
                $course->slug = Str::slug($request->title) == "" ? str_replace(' ', '-', $request->title) : Str::slug($request->title);
                $course->duration = $request->duration;
                if ($request->price) {
                    $course->price = $request->price ? $request->price / getSetting()->currency->conversion_rate : 0;
                    $course->discount_price = $request->discount_price ? $request->discount_price / getSetting()->currency->conversion_rate : 0;
                }

                $course->publish = 0;
                $course->status = 0;
                $course->level = $request->level;
                $course->host = $request->host;
                $course->subscription_list = $request->subscription_list;

                $course->grade = $request->grade;
                $course->subject = $request->subject;


                if ($request->get('host') == "Vimeo") {
                    $course->trailer_link = $request->vimeo;
                } elseif ($request->get('host') == "Youtube") {
                    $course->trailer_link = $request->trailer_link;
                } elseif ($request->get('host') == "Self") {
//                        dd('self ok');
                    if ($request->file('file') != "") {
                        $file = $request->file('file');
                        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                        $file->move('public/uploads/courses/videos/', $fileName);
                        $fileName = 'public/uploads/courses/videos/' . $fileName;
                        $course->trailer_link = $fileName;
                    }

                } elseif ($request->get('host') == "AmazonS3") {

                    if ($request->file('file') != "") {
                        $aws = new AmazonS3Controller();
                        $url = $aws->storeFile($request->file('file'));
                        $course->trailer_link = $url;
                    }

                } else {
                    $course->trailer_link = null;
                }


                $course->meta_keywords = $request->meta_keywords;
                $course->meta_description = $request->meta_description;
                $course->about = $request->about;
                $course->requirements = $request->requirements;
                $course->outcomes = $request->outcomes;
                $course->type = $request->type;
                $course->drip = $request->drip;
                $course->save();
            }
            $user = Auth::user();


            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect(route('getAllCourse'));

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function AdminUpdateCourse(Request $request)
    {
        Session::flash('type', 'update');
        Session::flash('id', $request->id);

        Session::flash('type', 'courseDetails');

        $this->validate($request, [
            'type' => 'required',
            'language' => 'required',
            'title' => 'required',

        ]);


        if ($request->type == 1) {
            $request->validate([
                'hours' => 'required',
                'minutes' => 'required',
                'level' => 'required',
                'host' => 'required',

            ]);
            if ($request->get('host') == "Vimeo") {
                $request->validate([
                    'vimeo' => 'required',
                ]);
            } elseif ($request->get('host') == "Youtube") {
                $request->validate([
                    'trailer_link' => 'required',
                ]);
            } else {
                $request->validate([
                    'file' => 'mimes:mp4,ogx,oga,ogv,ogg,webm',
                ]);
            }
        }

        try {
            $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
            $course = Course::find($request->id);
            $fileName = "";
            if ($request->file('image') != "") {
                $strpos = strpos($request->image, ';');
                $sub = substr($request->image, 0, $strpos);
                $name = md5($request->title . rand(0, 1000)) . '.' . 'png';
                $img = Image::make($request->image);
//                $img->resize(800, 500);
                $upload_path = 'public/uploads/courses/';
                $img->save($upload_path . $name);
                $course->image = 'public/uploads/courses/' . $name;

                $strpos = strpos($request->image, ';');
                $sub = substr($request->image, 0, $strpos);
                $name = md5($request->title . rand(0, 1000)) . '.' . 'png';
                $img = Image::make($request->image);
//                $img->resize(270, 181);
                $upload_path = 'public/uploads/courses/';
                $img->save($upload_path . $name);
                $course->thumbnail = 'public/uploads/courses/' . $name;
            }

            $duration = ($request->hours*60)+$request->minutes;
            $course->user_id = Auth::id();
            $course->drip = $request->drip;
            $course->lang_id = $request->language;
            $course->title = $request->title;
            $course->slug = Str::slug($request->title) == "" ? str_replace(' ', '-', $request->title) : Str::slug($request->title);
            $course->duration = $duration;
            $course->subscription_list = $request->subscription_list;
            $course->grade = $request->grade;
            $course->subject = $request->subject;


            if ($request->price) {
                $course->price = $request->price ? $request->price / $getsmSetting->conversion_rate : 0;
                $course->discount_price = $request->discount_price ? $request->discount_price / $getsmSetting->conversion_rate : 0;
            }
            if (isset($request->discount_price)) {
                $course->discount_price = $request->discount_price ? $request->discount_price / $getsmSetting->conversion_rate : 0;
            }
            $course->level = $request->level;
            if ($request->get('host') == "Vimeo") {
                $course->trailer_link = $request->vimeo;
            } elseif ($request->get('host') == "Youtube") {
                $course->trailer_link = $request->trailer_link;
            } elseif ($request->get('host') == "Self") {
                if ($request->file('file') != "") {
                    $file = $request->file('file');
                    $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('public/uploads/courses/videos/', $fileName);
                    $fileName = 'public/uploads/courses/videos/' . $fileName;
                    $course->trailer_link = $fileName;
                }

            } elseif ($request->get('host') == "AmazonS3") {
                $filePath = '/';


                if ($request->file('file') != "") {
                    $path = $request->file('file')->store($filePath, 's3');

                    $link = Storage::disk('s3')->url($path);

                    $course->trailer_link = $link;
                }


            } else {
                $course->trailer_link = null;
            }
            $course->host = $request->host;
            $course->meta_keywords = $request->meta_keywords;
            $course->meta_description = $request->meta_description;
            $course->about = $request->about;
            $course->type = $request->type;
            $course->requirements = $request->requirements;
            $course->outcomes = $request->outcomes;
            if ($request->type == 1) {
                $course->quiz_id = null;
                $course->category_id = $request->category;
                $course->subcategory_id = $request->sub_category;
            } elseif ($request->type == 2) {
                $course->quiz_id = $request->quiz;
                $course->category_id = null;
                $course->subcategory_id = null;
            }
            $course->save();

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();

        } catch (Exception $e) {
            //  dd($e);
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function updateCourse(Request $request)
    {


        $this->validate($request, [
            'type' => 'required',
            'language' => 'required',
            'title' => 'required',
            'image' => 'required',

        ]);


        if ($request->type == 1) {
            $request->validate([
                'duration' => 'required',
                'level' => 'required',
                'host' => 'required',
                'about' => 'required',
            ]);
            if ($request->get('host') == "Vimeo") {
                $request->validate([
                    'vimeo' => 'required',
                ]);
            } elseif ($request->get('host') == "Youtube") {
                $request->validate([
                    'trailer_link' => 'required',
                ]);
            } else {
                $request->validate([
                    'file' => 'mimes:mp4,ogx,oga,ogv,ogg,webm',
                ]);
            }
        }

        try {

            if (appMode() == true) {
                return response()->json(['error' => trans('common.For the demo version, you cannot change this')]);
            } else {

                $currency = GeneralSettings::first()->currency;
                $success = trans('lang.Course') . ' ' . trans('lang.Update') . ' ' . trans('lang.Successfully');


                if ($request->image && (strpos($request->image, "uploads") == 1)) {

                    $course = Course::find($request->id);
                    $course->user_id = Auth::id();
                    $course->category_id = $request->category_id;
                    $course->subcategory_id = $request->subcategory_id;
                    $course->lang_id = $request->lang_id;
                    $course->title = $request->title;
                    $course->slug = Str::slug($request->title) == "" ? str_replace(' ', '-', $request->title) : Str::slug($request->title);
                    $course->duration = $request->duration;

                    if ($request->price) {
                        $course->price = $request->price ? $request->price / $currency->conversion_rate : 0;
                        $course->discount_price = $request->discount_price ? $request->discount_price / $currency->conversion_rate : 0;
                    }

                    $course->publish = 1;
                    $course->status = 1;
                    $course->level = 1;
                    $course->trailer_link = $request->trailer_link;
                    $course->host = $request->host;
                    $course->meta_keywords = $request->meta_keywords;
                    $course->meta_description = $request->meta_description;
                    $course->about = $request->about;
                    $course->save();
                } elseif ($request->image && strpos($request->image, "uploads") == 0) {

                    $course = Course::find($request->id);
                    $strpos = strpos($request->image, ';');
                    $sub = substr($request->image, 0, $strpos);
                    $name = md5($request->title . rand(0, 1000)) . '.' . 'png';
                    $img = Image::make($request->image);
//                    $img->resize(624, 490);
                    $upload_path = public_path() . "/uploads/courses/images/";
                    $img->save($upload_path . $name);
                    $course->image = '/uploads/courses/images/' . $name;

                    $strpos = strpos($request->image, ';');
                    $sub = substr($request->image, 0, $strpos);
                    $name = md5($request->title . rand(0, 1000)) . '.' . 'png';
                    $img = Image::make($request->image);
//                    $img->resize(379.8, 292.76);
                    $upload_path = public_path() . "/uploads/courses/thumbnails/";
                    $img->save($upload_path . $name);
                    $course->thumbnail = '/uploads/courses/thumbnails/' . $name;

                    $course->user_id = Auth::id();
                    $course->category_id = $request->category_id;
                    $course->subcategory_id = $request->subcategory_id;
                    $course->lang_id = $request->lang_id;
                    $course->title = $request->title;
                    $course->slug = Str::slug($request->title) == "" ? str_replace(' ', '-', $request->title) : Str::slug($request->title);
                    $course->duration = $request->duration;

                    if ($request->price) {
                        $course->price = $request->price;
                        $course->discount_price = $request->discount_price;
                    }

                    $course->publish = 1;
                    $course->status = 1;
                    $course->level = 1;
                    if ($request->get('host') == "Vimeo") {
                        $course->trailer_link = $request->vimeo;
                    } elseif ($request->get('host') == "Youtube") {
                        $course->trailer_link = $request->trailer_link;
                    } elseif ($request->get('host') == "Self") {
//                        dd('self ok');
                        if ($request->file('file') != "") {
                            $file = $request->file('file');
                            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                            $file->move('public/uploads/courses/videos/', $fileName);
                            $fileName = 'public/uploads/courses/videos/' . $fileName;
                            $course->trailer_link = $fileName;
                        }

                    } elseif ($request->get('host') == "AmazonS3") {
                        $filePath = '/';

                        if ($request->file('file') != "") {
                            $path = $request->file('file')->store($filePath, 's3');

                            $link = Storage::disk('s3')->url($path);

                            $course->trailer_link = $link;
                        }

                    } else {
                        $course->trailer_link = null;
                    }
                    $course->host = $request->host;
                    $course->meta_keywords = $request->meta_keywords;
                    $course->meta_description = $request->meta_description;
                    $course->about = $request->about;
                    $course->save();
                }

                return \response()->json(['success' => $success
                ], 200);
            }


        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Operation Failed")]);
        }
    }

    public function courseStatus(Request $request)
    {

        try {

            if (Config::get('app.app_sync')) {
                Toastr::error(trans('common.For the demo version, you cannot change this'), trans('common.Failed'));
                return redirect()->back();
            } else {

                $course = Course::find($request->id);
                $success = 'Status has been changed';
                if ($course->status == 0 || $course->publish == 0) {
                    $course->publish = 1;
                    $course->status = 1;
                    $course->save();

                    return \response()->json(['success' => $success
                    ], 200);
                } else {
                    $course->publish = 0;
                    $course->status = 0;
                    $course->save();

                    return \response()->json(['success' => $success
                    ], 200);

                }
            }

        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Operation Failed")]);
        }
    }


    public function rejectEnroll(Request $request)
    {

        $this->validate($request, [
            'reason' => 'required',
        ]);

        try {

            if (Config::get('app.app_sync')) {
                Toastr::error(trans('common.For the demo version, you cannot change this'), trans('common.Failed'));
                return redirect()->back();
            } else {
                $success = trans('lang.Enroll Rejected');

                $enroll = CourseEnrolled::find($request->id);
                $user = User::find($enroll->user_id);
                $courseTitle = Course::find($enroll->course_id)->title;
                $enroll->status = 0;
                $enroll->reason = $request->reason;
                $enroll->save();

                send_email($user, 'Enroll_Rejected', [
                    'course' => $courseTitle,
                    'time' => Carbon::now()->format('d/m/Y H:i:s A'),
                    'reason' => $enroll->reason,
                ]);
            }

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function enableEnroll(Request $request)
    {
        try {

            if (Config::get('app.app_sync')) {
                Toastr::error('For demo version you can not change this !', 'Failed');
                return redirect()->back();
            } else {
                $success = trans('lang.Enrolled') . ' ' . trans('lang.Successfully');

                $enroll = CourseEnrolled::find($request->id);
                $user = User::find($enroll->user_id);
                $courseTitle = Course::find($enroll->course_id)->title;
                $enroll->status = 1;
                $enroll->reason = null;
                $enroll->save();
                send_email($user, 'Enroll_Enabled', [
                    'course' => $courseTitle,
                    'time' => Carbon::now()->format('d/m/Y H:i:s A'),
                ]);
            }

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }


    public function courseDetails($id)
    {
        $course = Course::findOrFail($id);
        if ($course->type == 1) {
            $quizzes = OnlineQuiz::where('category_id', $course->category_id)->get();
        } else {
            $quizzes = OnlineQuiz::where('active_status', 1)->get();
        }

        $chapters = Chapter::where('course_id', $id)->orderBy('position', 'asc')->with('lessons')->get();

        $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
        $categories = Category::get();
        $instructors = User::where('role_id', 2)->get();
        $languages = Language::select('id', 'native', 'code')
            ->where('status', '=', 1)
            ->get();
        $course_exercises = CourseExercise::where('course_id', $id)->get();

        try {
            $vimeo_video_list = Vimeo::request('/me/videos', ['per_page' => 10], 'GET');
        } catch (\Exception $e) {
            $vimeo_video_list = [];
        }
        if (isset($vimeo_video_list['body']['data'])) {
            $video_list = $vimeo_video_list['body']['data'];
        } else {
            $video_list = [];
        }
        $levels = CourseLevel::where('status', 1)->get();
        $grades = Grade::get();
        $subjects = Subject::get();
        // return $quizzes;
        return view('coursesetting::course_details', compact('levels', 'video_list', 'vimeo_video_list', 'course', 'chapters', 'categories', 'getsmSetting', 'instructors', 'languages', 'course_exercises', 'quizzes','grades','subjects'));
    }

    public function setCourseDripContent(Request $request)
    {

        Session::flash('type', 'drip');
        $course_id = $request->get('course_id');

        /*   $chapter_id = $request->get('chapter_id');
           $chapter_date = $request->get('chapter_date');
           $chapter_day = $request->get('chapter_day');*/

        $lesson_id = $request->get('lesson_id');
        $lesson_date = $request->get('lesson_date');
        $lesson_day = $request->get('lesson_day');
        $drip_type = $request->get('drip_type');

        /*        if (!empty($chapter_id) && is_array($chapter_id)) {
                    foreach ($chapter_id as $c_key => $c_id) {
                        $chapter = Chapter::find($c_id);
                        if ($chapter) {
                            if (!empty($chapter_date[$c_key])) {
                                $chapter->unlock_date = date('Y-m-d', strtotime($chapter_date[$c_key]));

                            } else {
                                $chapter->unlock_date = null;
                            }
                            if (!empty($chapter_day[$c_key])) {
                                $chapter->unlock_days = $chapter_day[$c_key];
                            } else {
                                $chapter->unlock_days = null;
                            }
                            $chapter->save();
                        }
                    }
                }*/
        if (!empty($lesson_id) && is_array($lesson_id)) {
            foreach ($lesson_id as $l_key => $l_id) {
                $lesson = Lesson::find($l_id);

                if ($lesson) {

                    $checkType = $drip_type[$l_key];

                    if ($checkType == 1) {
                        $lesson->unlock_days = null;

                        if (!empty($lesson_date[$l_key])) {
                            $lesson->unlock_date = date('Y-m-d', strtotime($lesson_date[$l_key]));
                        } else {
                            $lesson->unlock_date = null;
                        }
                    } else {
                        $lesson->unlock_date = null;
                        if (!empty($lesson_day[$l_key])) {
                            $lesson->unlock_days = $lesson_day[$l_key];
                        } else {
                            $lesson->unlock_days = null;
                        }
                    }


                    $lesson->save();
                }
            }

        }
        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }


    public function changeChapterPosition(Request $request)
    {
        $ids = $request->get('ids');

        if (count($ids) != 0) {
            foreach ($ids as $key => $id) {

                $chapter = Chapter::find($id);
                if ($chapter) {
                    $chapter->position = $key + 1;
                    $chapter->save();
                }
            }
        }
        return true;
    }

    public function changeLessonPosition(Request $request)
    {
        $ids = $request->get('ids');
        if (count($ids) != 0) {
            foreach ($ids as $key => $id) {
                $lesson = Lesson::find($id);
                if ($lesson) {
                    $lesson->position = $key + 1;
                    $lesson->save();
                }
            }
        }
        return true;
    }


    public function courseDelete($id)
    {
        if (demoCheck()) {
            return redirect()->back();
        }

        $hasCourse = CourseEnrolled::where('course_id', $id)->count();
        if ($hasCourse != 0) {
            Toastr::error('Course Already Enrolled By ' . $hasCourse . ' Student', trans('common.Failed'));
            return redirect()->back();
        }

        $carts = Cart::where('course_id', $id)->get();
        foreach ($carts as $cart) {
            $cart->delete();
        }

        $course = Course::findOrFail($id);
        $course->delete();


        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }
    public function getAllBatch()
    {
        $query = Batch::with('course');
        // if (isInstructor()) {
        //     $query->where('user_id', '=', Auth::id());
        // }
        $batches = $query->orderBy('id', 'desc')->get();
        $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
        $courses = Course::get();
        $languages = Language::select('id', 'native', 'code')
            ->where('status', '=', 1)
            ->get();
        $title = 'All Batches';

        $sub_lists = $this->getSubscriptionList();
        return view('coursesetting::batches', compact('sub_lists', 'title', 'batches', 'courses', 'getsmSetting', 'languages'));
    }
    public function BatchStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_name' => 'required|max:255',
            'course_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $is_exist = Batch::where('course_id', $request->course_id)->where('batch_name', $request->batch_name)->first();
        if ($is_exist) {
            Toastr::error('This name has been already taken', 'Failed');
            return redirect()->back()->withInput()->withErrors($validator);
        }


        try {
            DB::beginTransaction();
            $batch = new Batch;
            $batch->batch_name = $request->batch_name;
            $batch->status = $request->status;
            $batch->course_id = $request->course_id;
            $batch->start_date = $request->start_date;
            $batch->end_date = $request->end_date;
            $batch->batch_location = $request->batch_location;
            $batch->save();
            DB::commit();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function BatchEdit($id)
    {
        try {
            $batches = Batch::all();
            $edit = Batch::where('id', $id)->with('course')->first();
            $courses = Course::orderBy('id', 'asc')->get();
            $sub_lists = $this->getSubscriptionList();
            $title = 'Edit Batch';
            return view('coursesetting::batches', compact('sub_lists', 'title', 'batches', 'courses', 'edit'));
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function BatchUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_name' => 'required|max:255',
            'course_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $is_exist = Batch::where('course_id', $request->course_id)->where('batch_name', $request->batch_name)->where('id', '!=', $request->id)->first();
        if ($is_exist) {
            Toastr::error('This name has been already taken', 'Failed');
            return redirect()->back()->withInput()->withErrors($validator);
        }


        try {
            $batch = Batch::find($request->id);
            $batch->batch_name = $request->batch_name;
            $batch->status = $request->status;
            $batch->course_id = $request->course_id;
            $batch->start_date = $request->start_date;
            $batch->end_date = $request->end_date;
            $batch->batch_location = $request->batch_location;
            $results = $batch->save();
            if ($results) {
                Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                return redirect()->back();
            } else {
                Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                return redirect()->back();
            }

        } catch (\Exception $e) {
            //  dd($e->getMessage());
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }
    public function BatchDelete($id)
    {
        try {
            $result = Batch::find($id)->delete();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }

    }
    public function getAllExam()
    {
        $query = BatchExam::with('batch');
        
        $exams = $query->orderBy('id', 'desc')->get();
        $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
        $batches = Batch::with('course')->get();
        $courses = Course::get();
        $languages = Language::select('id', 'native', 'code')
            ->where('status', '=', 1)
            ->get();
        $title = 'All Exams';
        $students = User::where('role_id', 3)->get();
        $batcheslist = [];

        $sub_lists = $this->getSubscriptionList();
        return view('coursesetting::exams', compact('sub_lists','exams', 'title', 'batcheslist', 'courses', 'getsmSetting', 'languages','students'));
    }
    public function ajaxGetCourseBatch(Request $request)
    {
        try {
            $batches = Batch::where('course_id', '=', $request->id)->get();

            return response()->json([$batches]);
        } catch (Exception $e) {
            return response()->json("", 404);
        }
    }
    public function ajaxGetCourseStudents(Request $request)
    {
        try {
            $students = DB::table('course_enrolleds as e')
                        ->leftjoin('users as u', 'u.id', 'e.user_id')
                        ->where('e.course_id', '=', $request->id)
                        ->whereNotNull('u.id')
                        ->select('u.*')
                        ->get();

            return response()->json([$students]);
        } catch (Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ExamStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'batch_id' => 'required',
            'user_id' => 'required',
            'exam_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            foreach ($request->user_id as $key => $user_id) {
                $is_exist = BatchExam::where('batch_id', $request->batch_id)->where('user_id', $user_id)->first();
                if (!$is_exist) {
                    $batch = new BatchExam;
                    $batch->batch_id = $request->batch_id;
                    $batch->user_id = $user_id;
                    $batch->status = $request->status;
                    $batch->exam_date = $request->exam_date;

                    $batch->save();
                }
            }
            
            DB::commit();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function ExamEdit($id)
    {
        try {
            $query = BatchExam::with('batch');
        
            $exams = $query->orderBy('id', 'desc')->get();
            $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
            //$batches = Batch::with('course')->where('')->get();
            
            $languages = Language::select('id', 'native', 'code')
                ->where('status', '=', 1)
                ->get();
            $students = User::where('role_id', 3)->get();

            $edit = BatchExam::where('id', $id)->with('batch')->first();
            $batcheslist = Batch::with('course')->where('course_id',$edit->batch->course_id)->get();
            $courses = Course::get();
            $sub_lists = $this->getSubscriptionList();
            $title = 'Edit Exams';
            return view('coursesetting::exams', compact('sub_lists','exams', 'title', 'courses', 'edit','students','batcheslist'));
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function ExamUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'batch_id' => 'required',
            'user_id' => 'required',
            'exam_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $is_exist = BatchExam::where('batch_id', $request->batch_id)->where('user_id', $request->user_id)->where('id', '!=', $request->id)->first();
        if ($is_exist) {
            Toastr::error('This user has been already taken for this course', 'Failed');
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $batch = BatchExam::find($request->id);
            $batch->batch_id = $request->batch_id;
            $batch->user_id = $request->user_id[0];
            $batch->status = $request->status;
            $batch->exam_date = $request->exam_date;
            $results = $batch->save();
            if ($results) {
                Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                return redirect()->back();
            } else {
                Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                return redirect()->back();
            }

        } catch (\Exception $e) {
            //  dd($e->getMessage());
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }
    public function ExamDelete($id)
    {
        try {
            $result = BatchExam::find($id)->delete();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }

    }

    public function EditCourse($id)
    {
        $course = Course::findOrFail($id);
        if ($course->type == 1) {
            $quizzes = OnlineQuiz::where('category_id', $course->category_id)->get();
        } else {
            $quizzes = OnlineQuiz::where('active_status', 1)->get();
        }

        $chapters = Chapter::where('course_id', $id)->orderBy('position', 'asc')->with('lessons')->get();

        $getsmSetting = GeneralSetting::leftjoin('currencies', 'currencies.id', '=', 'general_settings.currency_id')->first();
        $categories = Category::get();
        $instructors = User::where('role_id', 2)->get();
        $languages = Language::select('id', 'native', 'code')
            ->where('status', '=', 1)
            ->get();
        $course_exercises = CourseExercise::where('course_id', $id)->get();

        try {
            $vimeo_video_list = Vimeo::request('/me/videos', ['per_page' => 10], 'GET');
        } catch (\Exception $e) {
            $vimeo_video_list = [];
        }
        if (isset($vimeo_video_list['body']['data'])) {
            $video_list = $vimeo_video_list['body']['data'];
        } else {
            $video_list = [];
        }
        $levels = CourseLevel::where('status', 1)->get();
        $grades = Grade::get();
        $subjects = Subject::get();
        // return $quizzes;
        return view('coursesetting::course_details', compact('levels', 'video_list', 'vimeo_video_list', 'course', 'chapters', 'categories', 'getsmSetting', 'instructors', 'languages', 'course_exercises', 'quizzes','grades','subjects'));
    }
}
