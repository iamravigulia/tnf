<?php
use Illuminate\Support\Facades\Route;

// Route::get('greeting', function () {
//     return 'Hi, this is your awesome package! Mcqp';
// });

// Route::get('picmatch/test', 'EdgeWizz\Picmatch\Controllers\PicmatchController@test')->name('test');

Route::post('fmt/tnf/store', 'EdgeWizz\Tnf\Controllers\TnfController@store')->name('fmt.tnf.store');


Route::post('fmt/tnf/update/{id}', 'EdgeWizz\Tnf\Controllers\TnfController@update')->name('fmt.tnf.update');


Route::any('fmt/tnf/delete/{id}', 'EdgeWizz\Tnf\Controllers\TnfController@delete')->name('fmt.tnf.delete');


Route::post('fmt/tnf/csv', 'EdgeWizz\Tnf\Controllers\TnfController@csv')->name('fmt.tnf.csv');

Route::any('fmt/tnf/active/{id}',  'EdgeWizz\Tnf\Controllers\TnfController@active')->name('fmt.tnf.active');
