<?php

Route::prefix('payeer')->group(function () {
    Route::get('/callback-success', 'PayeerController@paymentSuccess')->name('payeerPaymentSuccess');
    Route::get('/callback-failed', 'PayeerController@paymentFailed')->name('payeerPaymentfailed');
});
