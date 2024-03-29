<?php

namespace App\Http\Controllers\Api;

use App\BillingDetails;
use App\Http\Controllers\PaymentController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Chapter;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Subject;
use Modules\CourseSetting\Entities\Grade;
use App\User;
use Carbon\Carbon;
use Modules\Payment\Entities\Cart;
use Modules\Payment\Entities\Checkout;


/**
 * @group  Course management
 *
 * APIs for managing course
 */
class CourseApiController extends Controller
{

    /**
     * Get all courses
     *
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:40 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * }
     * ],
     * "total": 11,
     * "message": "Getting Courses Data"
     * }
     *
     */
    public function getAllCourses()
    {
        $courses = Course::where('type', '1')->with('user')->latest()->get();

        $response = [
            'success' => true,
            'data' => $courses,
            'total' => count($courses),
            'message' => 'Getting Courses Data',
        ];
        return response()->json($response, 200);
    }


    /**
     * Get all quizzes
     *
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "class_id": null,
     * "user_id": 1,
     * "lang_id": 19,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/frontend/infixlmstheme/img/course/1.jpg",
     * "thumbnail": "public/frontend/infixlmstheme/img/course/1.jpg",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "3rd Mar, 2021",
     * "publishedDate": "3rd Mar, 2021",
     * "sumRev": 0,
     * "purchasePrice": 0,
     * "enrollCount": 1,
     * "enrolls": [
     * {
     * "id": 1,
     * "tracking": "K3USKPJBC5U8",
     * "user_id": 3,
     * "course_id": 1,
     * "purchase_price": 0,
     * "coupon": null,
     * "discount_amount": 0,
     * "status": 1,
     * "reveune": 0,
     * "reason": null,
     * "created_at": "2021-03-03T07:32:13.000000Z",
     * "updated_at": "2021-03-03T07:32:13.000000Z",
     * "enrolledDate": "3rd March 2021 13:13 pm"
     * }
     * ]
     * }
     * ],
     * "total": 1,
     * "message": "Getting Courses Data"
     * }
     * @return [json] user object
     */
    public function getAllQuizzes()
    {
        $courses = Course::where('type', '2')->with('user')->latest()->get();

        $response = [
            'success' => true,
            'data' => $courses,
            'total' => count($courses),
            'message' => 'Getting Quiz Data',
        ];
        return response()->json($response, 200);
    }


    /**
     * Get Course Details
     *
     * @response
     * {
     * "success": true,
     * "data": {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:41 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * },
     * "message": "Getting Course Data"
     * }
     *
     */
    public function getCourseDetails(Request $request,$id=false)
    {
        if($id==false) $id=$request->id;
        $course = Course::with('user','chapters','lessons','files','comments')->withCount('chapters','lessons')->find($id);

        $reviews = DB::table('course_reveiws')
            ->select(
                'course_reveiws.id',
                'course_reveiws.star',
                'course_reveiws.comment',
                'course_reveiws.created_at',
                'users.id as userId',
                'users.name as userName',
                'users.image as userImage',
            )
            ->join('users', 'users.id', '=', 'course_reveiws.user_id')
            ->where('course_reveiws.course_id', $id)->get();

        $course->reviews=$reviews;


        if ($course) {
            $response = [
                'success' => true,
                'data' => $course,
                'message' => 'Getting Course Data',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Course Found',
            ];
        }

        return response()->json($response, 200);
    }

    public function getChapterDetails(Request $request){
        $course_id=$request->course_id;
        $id=$request->chapter_id;
        $chapter = Chapter::with('lessons','course')->withCount('lessons')->find($id);

        $reviews = DB::table('course_reveiws')
            ->select(
                'course_reveiws.id',
                'course_reveiws.star',
                'course_reveiws.comment',
                'course_reveiws.created_at',
                'users.id as userId',
                'users.name as userName',
                'users.image as userImage',
            )
            ->join('users', 'users.id', '=', 'course_reveiws.user_id')
            ->where('course_reveiws.course_id', $course_id)->get();

        $chapter->reviews=$reviews;


        if ($chapter) {
            $response = [
                'success' => true,
                'data' => $chapter,
                'message' => 'Getting Chapter Data',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Chapter Found',
            ];
        }

        return response()->json($response, 200);
    }

    public function PaymentCourse(Request $request)
    {
        $course_id=$request->course_id;
        $user_id=Auth::user()->id;
        $user = Auth::user();

        $exist = Cart::where('user_id', $user->id)->where('course_id', $course_id)->first();
        $existCourse = CourseEnrolled::where('user_id', $user->id)->where('course_id', $course_id)->first();
        $oldCart = Cart::where('user_id', $user->id)->first();

        if (isset($exist)) {
            $message = 'Course already added in your cart';
            $success = false;
        } else if (isset($existCourse)) {
            $message = 'Course already purchased';
            $success = false;

            $response = [
                'success' => false,
                'data' => [],
                'message' => $message,
            ];
            return response()->json($response, 200);
        } else {

            if (isset($oldCart)) {
                $course = Course::find($course_id);
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->instructor_id = $course->user_id;
                $cart->course_id = $course_id;
                $cart->tracking = $oldCart->tracking;
                if ($course->discount_price != null) {
                    $cart->price = $course->discount_price;
                } else {
                    $cart->price = $course->price;
                }
                $cart->save();

            } else {

                $course = Course::find($course_id);
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->instructor_id = $course->user_id;
                $cart->course_id = $course_id;
                $cart->tracking = getTrx();
                if ($course->discount_price != null) {
                    $cart->price = $request->amount;
                } else {
                    $cart->price = $request->amount;
                }
                $cart->save();
            }

            $message = 'Course Added to your cart';
            $success = true;
        }

        $profile = Auth::user();


        $tracking = Cart::where('user_id', $user_id)->first()->tracking;
        $total = $request->amount;
        if ($profile->role_id == 3) {

            $total = Cart::where('user_id', $user_id)->sum('price');
        }

        $checkout = Checkout::where('tracking', $tracking)->where('user_id', $user_id)->latest()->first();
        if (!$checkout)
            $checkout = new Checkout();

        $checkout->discount = 0.00;
        $checkout->purchase_price = $total;
        $checkout->tracking = $tracking;
        $checkout->user_id = $user_id;
        $checkout->price = $total;
        $checkout->status = 0;
        $checkout->save();


        $bill = BillingDetails::where('tracking_id', $tracking)->first();


        if (empty($bill)) {
            $bill = new BillingDetails();
        }

        $bill->user_id = $user_id;
        $bill->tracking_id = $tracking;
        $bill->first_name = $user->name;
        $bill->last_name = $user->name;
        $bill->company_name = 'Local';
        $bill->email = $user->email;
        $bill->payment_method = null;
        $bill->save();

        $checkout_info = $checkout;

        if ($checkout_info) {
            $checkout_info->billing_detail_id = $bill->id;
            $checkout_info->save();

            if ($checkout_info->purchase_price == 0) {
                $checkout_info->payment_method = 'None';
                $bill->payment_method = 'None';
                $checkout_info->save();
                $carts = Cart::where('tracking', $checkout_info->tracking)->get();

                foreach ($carts as $cart) {

                    $payment = new PaymentController();
                    $payment->directEnroll($cart->course_id, $checkout_info->tracking);
                    $cart->delete();

                }


            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Operation Failed.'
            ];
            return response()->json($response, 500);
        }
        $response = $checkout_info;

        (new PaymentController())->payWithGateWay($response,'PayStack');

        $response = [
            'success' => true,
            'data' => [],
            'message' => 'Payment Success',
        ];
        return response()->json($response, 200);

    }


    /**
     * Get Quiz Details
     *
     * @response
     * {
     * "success": true,
     * "data": {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:41 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * },
     * "message": "Getting Quiz Data"
     * }
     */
    public function getQuizDetails($id)
    {
        $course = Course::find($id);
        if ($course) {
            $response = [
                'success' => true,
                'data' => $course,
                'message' => 'Getting Quiz Data',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Quiz Found',
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Get Top Categories
     *
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "name": "Business",
     * "status": 1,
     * "title": "Voluptas eos placeat",
     * "description": "Laboris Nam laborum voluptatibus dolor aspernatur laboriosam commodo in voluptatem Temporibus eum",
     * "url": "https://youtu.be/bG9eMa_025c",
     * "show_home": 1,
     * "position_order": 2,
     * "image": "public/demo/category/image/1.png",
     * "thumbnail": "public/demo/category/thumb/1.png",
     * "created_at": null,
     * "updated_at": null,
     * "courseCount": 2,
     * "courses": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:19 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * },
     * {
     * "id": 2,
     * "category_id": 1,
     * "subcategory_id": 2,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "An Entire MBA in 1 Course:Award Winning Course",
     * "slug": "an-entire-mba-in-1-courseaward-winning-business-school-prof",
     * "duration": "5H",
     * "image": "public/demo/course/image/2.png",
     * "thumbnail": "public/demo/course/thumb/2.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:19 am",
     * "sumRev": 2,
     * "purchasePrice": 22,
     * "enrollCount": 1
     * }
     * ]
     * },
     * {
     * "id": 2,
     * "name": "3D Modeling",
     * "status": 1,
     * "title": "Voluptas eos placeat",
     * "description": "Laboris Nam laborum voluptatibus dolor aspernatur laboriosam commodo in voluptatem Temporibus eum",
     * "url": "https://youtu.be/bG9eMa_025c",
     * "show_home": 1,
     * "position_order": 2,
     * "image": "public/demo/category/image/2.png",
     * "thumbnail": "public/demo/category/thumb/2.png",
     * "created_at": null,
     * "updated_at": null,
     * "courseCount": 2,
     * "courses": [
     * {
     * "id": 3,
     * "category_id": 2,
     * "subcategory_id": 3,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Complete Blender Creator:3D Modelling for Beginners",
     * "slug": "complete-blender-creator-learn-3d-modelling-for-beginners",
     * "duration": "5H",
     * "image": "public/demo/course/image/3.png",
     * "thumbnail": "public/demo/course/thumb/3.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:19 am",
     * "sumRev": 2,
     * "purchasePrice": 23,
     * "enrollCount": 1
     * },
     * {
     * "id": 4,
     * "category_id": 2,
     * "subcategory_id": 4,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Creating 3D environments in Blender",
     * "slug": "creating-3d-environments-in-blender",
     * "duration": "5H",
     * "image": "public/demo/course/image/4.png",
     * "thumbnail": "public/demo/course/thumb/4.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:20 am",
     * "sumRev": 2,
     * "purchasePrice": 24,
     * "enrollCount": 1
     * }
     * ]
     * },
     * {
     * "id": 3,
     * "name": "UI UX Design",
     * "status": 1,
     * "title": "Voluptas eos placeat",
     * "description": "Laboris Nam laborum voluptatibus dolor aspernatur laboriosam commodo in voluptatem Temporibus eum",
     * "url": "https://youtu.be/bG9eMa_025c",
     * "show_home": 1,
     * "position_order": 3,
     * "image": "public/demo/category/image/3.png",
     * "thumbnail": "public/demo/category/thumb/3.png",
     * "created_at": null,
     * "updated_at": null,
     * "courseCount": 2,
     * "courses": [
     * {
     * "id": 5,
     * "category_id": 3,
     * "subcategory_id": 5,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Adobe XD Design Essentials - UI UX Design",
     * "slug": "creating-3d-environments-in-blender",
     * "duration": "5H",
     * "image": "public/demo/course/image/5.png",
     * "thumbnail": "public/demo/course/thumb/5.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:20 am",
     * "sumRev": 2,
     * "purchasePrice": 25,
     * "enrollCount": 1
     * },
     * {
     * "id": 6,
     * "category_id": 3,
     * "subcategory_id": 6,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "WEB Design: Using HTML & CSS",
     * "slug": "design-rules-principles-practices-for-great-ui-design",
     * "duration": "5H",
     * "image": "public/demo/course/image/6.png",
     * "thumbnail": "public/demo/course/thumb/6.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:20 am",
     * "sumRev": 2,
     * "purchasePrice": 26,
     * "enrollCount": 1
     * }
     * ]
     * },
     * {
     * "id": 4,
     * "name": "Mobile Development",
     * "status": 1,
     * "title": "Voluptas eos placeat",
     * "description": "Laboris Nam laborum voluptatibus dolor aspernatur laboriosam commodo in voluptatem Temporibus eum",
     * "url": "https://youtu.be/bG9eMa_025c",
     * "show_home": 1,
     * "position_order": 4,
     * "image": "public/demo/category/image/4.png",
     * "thumbnail": "public/demo/category/thumb/4.png",
     * "created_at": null,
     * "updated_at": null,
     * "courseCount": 2,
     * "courses": [
     * {
     * "id": 7,
     * "category_id": 4,
     * "subcategory_id": 7,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Introduction to Programming and App Development",
     * "slug": "introduction-to-programming-and-app-development",
     * "duration": "5H",
     * "image": "public/demo/course/image/7.png",
     * "thumbnail": "public/demo/course/thumb/7.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:20 am",
     * "sumRev": 2,
     * "purchasePrice": 27,
     * "enrollCount": 1
     * },
     * {
     * "id": 8,
     * "category_id": 4,
     * "subcategory_id": 8,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "The Complete iOS 11 & Swift Developer Course",
     * "slug": "the-complete-ios-11-swift-developer-course",
     * "duration": "5H",
     * "image": "public/demo/course/image/8.png",
     * "thumbnail": "public/demo/course/thumb/8.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:20 am",
     * "sumRev": 0,
     * "purchasePrice": 0,
     * "enrollCount": 0
     * }
     * ]
     * },
     * {
     * "id": 5,
     * "name": "Software Development",
     * "status": 1,
     * "title": "Voluptas eos placeat",
     * "description": "Laboris Nam laborum voluptatibus dolor aspernatur laboriosam commodo in voluptatem Temporibus eum",
     * "url": "https://youtu.be/bG9eMa_025c",
     * "show_home": 1,
     * "position_order": 5,
     * "image": "public/demo/category/image/5.png",
     * "thumbnail": "public/demo/category/thumb/5.png",
     * "created_at": null,
     * "updated_at": null,
     * "courseCount": 2,
     * "courses": [
     * {
     * "id": 9,
     * "category_id": 5,
     * "subcategory_id": 9,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Complete Python Developer in 2020: Zero to Mastery",
     * "slug": "complete-python-developer-in-2020",
     * "duration": "5H",
     * "image": "public/demo/course/image/8.png",
     * "thumbnail": "public/demo/course/thumb/8.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:20 am",
     * "sumRev": 0,
     * "purchasePrice": 0,
     * "enrollCount": 0
     * },
     * {
     * "id": 10,
     * "category_id": 5,
     * "subcategory_id": 10,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Master Laravel PHP with basic to advanced project",
     * "slug": "master-laravel-php-with-basic-to-advanced-project",
     * "duration": "5H",
     * "image": "public/demo/course/image/9.png",
     * "thumbnail": "public/demo/course/thumb/9.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 11:20 am",
     * "sumRev": 0,
     * "purchasePrice": 0,
     * "enrollCount": 0
     * }
     * ]
     * }
     * ],
     * "message": "Getting Top Categories"
     * }
     */
    public function topCategories()
    {
        $categories = Category::with(['courses' => function ($query) {
            $query->count();
        }])->get()->take(10)->sortBy('courses')->all();

        if ($categories) {
            $response = [
                'success' => true,
                'data' => $categories,
                'message' => 'Getting Top Categories',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Category Found',
            ];
        }

        return response()->json($response, 200);
    }

    public function getCategories()
    {
        $categories = Category::all();

        if ($categories) {
            $response = [
                'success' => true,
                'data' => $categories,
                'message' => 'Getting Categories',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Category Found',
            ];
        }

        return response()->json($response, 200);
    }

    public function getSubjects()
    {
        $subjects = Subject::all();

        if ($subjects) {
            $response = [
                'success' => true,
                'data' => $subjects,
                'message' => 'Getting Subjects',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Category Found',
            ];
        }

        return response()->json($response, 200);
    }

    public function getGrades()
    {
        $subjects = Grade::all();

        if ($subjects) {
            $response = [
                'success' => true,
                'data' => $subjects,
                'message' => 'Getting Grades',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Category Found',
            ];
        }

        return response()->json($response, 200);
    }


    /**
     * Get Popular courses
     *
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:40 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * }
     * ],
     * "total": 11,
     * "message": "Getting Courses Data"
     * }
     * @return [json] user object
     */
    public function getPopularCourses()
    {
        $courses = Course::where('type', '1')->with('user')->orderBy('total_enrolled', 'desc')->get();

        $response = [
            'success' => true,
            'data' => $courses,
            'total' => count($courses),
            'message' => 'Getting Courses Data',
        ];
        return response()->json($response, 200);
    }


    /**
     * Get all quizzes
     *
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:40 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * }
     * ],
     * "total": 11,
     * "message": "Getting Courses Data"
     * }
     * @return [json] user object
     */
    public function getPopularQuizzes()
    {
        $courses = Course::where('type', '2')->with('user')->orderBy('total_enrolled', 'desc')->get();

        $response = [
            'success' => true,
            'data' => $courses,
            'total' => count($courses),
            'message' => 'Getting Quiz Data',
        ];
        return response()->json($response, 200);
    }


    /**
     * Search Course
     * @bodyParam  title string required Find course by title.
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:40 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * }
     * ],
     * "total": 11,
     * "message": "Getting Courses Data"
     * }
     *
     */
    public function searchCourse(Request $request)
    {
        $title = $request->get('title');
        $courses = Course::where('title', 'like', '%' . $title . '%')->with('user')->get();
        if ($courses) {
            $response = [
                'success' => true,
                'data' => $courses,
                'total' => count($courses),
                'message' => 'Getting Courses Data',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Course Found',
            ];
        }

        return response()->json($response, 200);
    }


    /**
     * Search Quiz
     * @bodyParam  title string required Find quiz by title.
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:40 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * }
     * ],
     * "total": 11,
     * "message": "Getting Quiz Data"
     * }
     */
    public function searchQuiz(Request $request)
    {
        $title = $request->get('title');
        $courses = Course::where('title', 'like', '%' . $title . '%')->with('user')->get();
        if ($courses) {
            $response = [
                'success' => true,
                'data' => $courses,
                'total' => count($courses),
                'message' => 'Getting Quiz Data',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Quiz Found',
            ];
        }


        return response()->json($response, 200);
    }


    /**
     * Filter Course
     * @bodyParam  category number Find course by category.
     * @bodyParam  sub_category number required Find course by sub category.
     * @bodyParam  level number  Find course by level.
     * @bodyParam  language number required Find course by language.
     * @bodyParam  min_price number  Find course by min price.
     * @bodyParam  max_price number  Find course by max price.
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:40 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * }
     * ],
     * "total": 11,
     * "message": "Getting Courses Data"
     * }
     *
     */
    public function filterCourse(Request $request)
    {
        $category = $request->get('category');
        $sub_category = $request->get('sub_category');
        $level = $request->get('level');
        $language = $request->get('language');
        $min_price = $request->get('min_price');
        $max_price = $request->get('max_price');
        $virtualclasstype = $request->get('type');
        $query = Course::where('status', 1)->where('type', 1);

        if (!empty($category)) {
            $query->where('category_id', $category);
        }
        if (!empty($sub_category)) {
            $query->where('subcategory_id', $sub_category);
        }

        if (!empty($level)) {
            $query->where('subcategory_id', $level);
        }

        if (!empty($language)) {
            $query->where('lang_id  ', $language);
        }
        if (!empty($min_price)) {
            $query->where('price  ', '<=', $min_price);
        }
        if (!empty($min_price)) {
            $query->where('price  ', '>=', $max_price);
        }
        if(!empty($virtualclasstype)){
            if($virtualclasstype==1){
                $query->where('class_type', $type);
            }
        }
        $courses = $query->get();
        if ($courses) {
            $response = [
                'success' => true,
                'data' => $courses,
                'total' => count($courses),
                'message' => 'Getting Courses Data',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Course Found',
            ];
        }

        return response()->json($response, 200);
    }
    public function upcomingCourse(Request $request)
    {
        $query = Course::whereHas('class', function($q){
                  $q->where('start_date','>=', date('Y-m-d'));
                })->where('status', 1)->where('type', 3);

        $courses = $query->get();

        if ($courses) {
            $response = [
                'success' => true,
                'data' => $courses,
                'total' => count($courses),
                'message' => 'Getting Upcoming class Data',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Course Found',
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Filter Quiz
     * @bodyParam  category number Find course by category.
     * @bodyParam  sub_category number required Find course by sub category.
     * @bodyParam  level number  Find course by level.
     * @bodyParam  language number required Find course by language.
     * @bodyParam  min_price number  Find course by min price.
     * @bodyParam  max_price number  Find course by max price.
     * @response
     * {
     * "success": true,
     * "data": [
     * {
     * "id": 1,
     * "category_id": 1,
     * "subcategory_id": 1,
     * "quiz_id": null,
     * "user_id": 2,
     * "lang_id": 1,
     * "title": "Managerial Accounting Advance Course",
     * "slug": "managerial-accounting",
     * "duration": "5H",
     * "image": "public/demo/course/image/1.png",
     * "thumbnail": "public/demo/course/thumb/1.png",
     * "price": 20,
     * "discount_price": 10,
     * "publish": 1,
     * "status": 1,
     * "level": 2,
     * "trailer_link": "https://www.youtube.com/watch?v=mlqWUqVZrHA",
     * "host": "Youtube",
     * "meta_keywords": null,
     * "meta_description": null,
     * "about": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text\r\n            ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
     * "special_commission": null,
     * "total_enrolled": 1,
     * "reveune": 50,
     * "reveiw": 0,
     * "type": 1,
     * "created_at": null,
     * "updated_at": null,
     * "dateFormat": "17th November 2020",
     * "publishedDate": "17th November 2020 10:40 am",
     * "sumRev": 2,
     * "purchasePrice": 21,
     * "enrollCount": 1
     * }
     * ],
     * "total": 11,
     * "message": "Getting Courses Data"
     * }
     *
     */
    public function filterQuiz(Request $request)
    {
        $category = $request->get('category');
        $sub_category = $request->get('sub_category');
        $level = $request->get('level');
        $language = $request->get('language');
        $min_price = $request->get('min_price');
        $max_price = $request->get('max_price');
        $query = Course::where('status', 1)->where('type', 2);

        if (!empty($category)) {
            $query->where('category_id', $category);
        }
        if (!empty($sub_category)) {
            $query->where('subcategory_id', $sub_category);
        }

        if (!empty($level)) {
            $query->where('subcategory_id', $level);
        }

        if (!empty($language)) {
            $query->where('lang_id  ', $language);
        }
        if (!empty($min_price)) {
            $query->where('price  ', '<=', $min_price);
        }
        if (!empty($min_price)) {
            $query->where('price  ', '>=', $max_price);
        }
        $courses = $query->get();
        if ($courses) {
            $response = [
                'success' => true,
                'data' => $courses,
                'total' => count($courses),
                'message' => 'Getting Quiz Data',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No Quiz Found',
            ];
        }

        return response()->json($response, 200);
    }


}
