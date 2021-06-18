<?php

Route::group(['prefix' => 'admin/student', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/allStudent', 'StudentSettingController@index')->name('student.student_list')->middleware('RoutePermissionCheck:student.student_list');
    Route::post('/store', 'StudentSettingController@store')->name('student.store')->middleware('RoutePermissionCheck:student.store');
    Route::get('/edit/{id}', 'StudentSettingController@edit')->middleware('RoutePermissionCheck:student.edit');
    Route::post('/update', 'StudentSettingController@update')->name('student.update')->middleware('RoutePermissionCheck:student.edit');
    Route::post('/destroy', 'StudentSettingController@destroy')->name('student.delete')->middleware('RoutePermissionCheck:student.delete');
    Route::get('/status/{id}', 'StudentSettingController@status')->name('student.change_status')->middleware('RoutePermissionCheck:student.enable_disable');
    Route::post('/enrol_store', 'StudentSettingController@Enrolstore')->name('enrol.student');
    Route::post('/enrol_update', 'StudentSettingController@EnrolUpdate')->name('enrol.update');

    Route::get('importExportView', 'StudentSettingController@importExportView')->name('student.importview');
	Route::post('import', 'StudentSettingController@import')->name('import');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('grade', 'StudentSettingController@gradeIndex')->name('grade');
    Route::post('grade', 'StudentSettingController@gradeStore')->name('grade.store');
    Route::get('grade/{id}', 'StudentSettingController@gradeShow')->name('grade-edit');
    Route::put('grade/{id}', 'StudentSettingController@gradeUpdate')->name('grade-update');
    Route::delete('grade/{id}', 'StudentSettingController@gradeDestroy')->name('grade-delete');

    Route::get('subject', 'StudentSettingController@subjectIndex')->name('subject');
    Route::post('subject', 'StudentSettingController@subjectStore')->name('subject.store');
    Route::get('subject/{id}', 'StudentSettingController@subjectShow')->name('subject-edit');
    Route::put('subject/{id}', 'StudentSettingController@subjectUpdate')->name('subject-update');
    Route::delete('subject/{id}', 'StudentSettingController@subjectDestroy')->name('subject-delete');
});

Route::group(['prefix' => 'student/dashboard', 'middleware' => ['auth', 'student']], function () {


    Route::get('/bookmarkSave/{id}', 'BookmarkController@bookmarkSave')->name('bookmarkSave');
    Route::get('/bookmarksDelete/{id}', 'BookmarkController@bookmarksDelete');
    Route::get('/bookmarks/show/{id}', 'BookmarkController@show');


});
