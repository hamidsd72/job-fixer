<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Access
Route::get('/', 'HomeController@index')->name('index');
Route::get('/delete-file/{type}/{id}', 'HomeController@delete_file')->name('delete.file');
Route::get('/report/site', 'Report\ActivityController@index')->name('activity.index');
Route::resource('permissionCat', Access\PermissionCatController::class);
Route::resource('permission', Access\PermissionController::class);
Route::resource('role', Access\RoleController::class);
//User
Route::resource('user', User\UserController::class);
Route::get('user-permission/{id}', 'User\UserController@permission')->name('user.permission');
Route::post('user-permission/{id}/update', 'User\UserController@permission_update')->name('user.update.permission');
//profile
Route::get('profile','User\UserController@profilePage')->name('profile');
//JobFixer
Route::resource('job', JobFixer\JobController::class);
Route::resource('job-work', JobFixer\JobWorkController::class);
Route::resource('job-level-assessment', JobFixer\JobLevelAssessmentController::class);
Route::post('job-level-assessment/{id}/sort', 'JobFixer\JobLevelAssessmentController@sort')->name('job-level-assessment.sort');
Route::post('job-level-assessment/{id}/{type}/set-level', 'JobFixer\JobLevelAssessmentController@set_level')->name('job-level-assessment.set-level');
Route::resource('job-level-package', JobFixer\JobLevelPackageController::class);

//JobFixer > Email

//send email
Route::get('job-send-email/create','JobFixer\Email\JobSendEmailController@create')->name('job-send-email.create');

Route::post('job-send-email/send','JobFixer\Email\JobSendEmailController@send')->name('job-send-email.send');
//Route::get('job-send-email/create',[JobFixer\Email\JobSendEmailController::class,'create'])->name('job-send-email-create');


Route::resource('job-email-package', JobFixer\Email\JobEmailPackageController::class);
Route::post('job-email-package/{id}/sort', 'JobFixer\Email\JobEmailPackageController@sort')->name('job-email-package.sort');
Route::get('job-email-package/{id}/{type}/vip', 'JobFixer\Email\JobEmailPackageController@vip_set')->name('job-email-package.vip');

//setting
Route::resource('off-code', Setting\OffCodeController::class);
Route::get('off-code/{id}/{type}/status', 'Setting\OffCodeController@status')->name('off-code.status');
Route::resource('country', Setting\CountryController::class);




