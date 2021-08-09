<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['namespace' => 'Api'], function () {


});


Route::group([
    'namespace' => 'Api'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');


    //CourseApiController
    Route::get('/get-all-courses', 'CourseApiController@getAllCourses');
    Route::get('/get-popular-courses', 'CourseApiController@getPopularCourses');
    Route::get('/get-course-details/{id}', 'CourseApiController@getCourseDetails');
    
    

    Route::get('/top-categories', 'CourseApiController@topCategories');
    Route::get('/get-categories', 'CourseApiController@getCategories');
    Route::get('/search-course', 'CourseApiController@searchCourse');
    
    Route::get('/payment-gateways', 'WebsiteApiController@paymentGateways');
    Route::get('/get-subjects', 'CourseApiController@getSubjects');
    Route::get('/get-grades', 'CourseApiController@getGrades');

    Route::get('/filter-class', 'CourseApiController@filterClass');
 //Route::post('quizSubmit', 'WebsiteApiController@quizSubmit');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        //with login routes
        Route::get('/filter-course', 'CourseApiController@filterCourse');
        Route::post('/pay-course', 'CourseApiController@PaymentCourse');
        Route::get('/cart-list', 'WebsiteApiController@cartList');
        Route::get('/add-to-cart/{id}', 'WebsiteApiController@addToCart');
        Route::get('/remove-to-cart/{id}', 'WebsiteApiController@removeCart');
        Route::get('/apply-coupon', 'WebsiteApiController@applyCoupon');
        Route::post('/get-course-details', 'CourseApiController@getCourseDetails');
        Route::post('/get-chapter-details', 'CourseApiController@getChapterDetails');

        //AuthController
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::get('get-user/{id}', 'AuthController@getusers');
        Route::post('change-password', 'AuthController@changePassword');
        Route::post('/update-profile', 'WebsiteApiController@updateProfile');
        Route::post('/upload-photo', 'WebsiteApiController@updatePhoto');

        Route::get('my-purchases', 'WebsiteApiController@myPurchases');

        Route::post('quizStart', 'WebsiteApiController@quizStart');
        Route::post('quizSubmit', 'WebsiteApiController@quizSubmit');


        //WebsiteApiController

        Route::get('/countries', 'WebsiteApiController@countries');
        Route::get('/cities/{country_id}', 'WebsiteApiController@cities');
        Route::get('/my-courses', 'WebsiteApiController@myCourses');
        Route::post('/submit-review', 'WebsiteApiController@submitReview');
        Route::post('/comment', 'WebsiteApiController@comment');
        Route::post('/comment-reply', 'WebsiteApiController@commentReply');
        Route::get('/payment-methods', 'WebsiteApiController@paymentMethods');

        Route::post('/make-order', 'WebsiteApiController@makeOrder');
        Route::post('/make-payment/{response}/{gateWayName}', 'WebsiteApiController@payWithGateWay');

        Route::get('/my-billing-address', 'WebsiteApiController@myBilling');

        Route::post('lession-view', 'WebsiteApiController@fullScreenView');

    });
});
