<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\FaqController;
use App\Http\Controllers\TestimonialController;

use App\Http\Controllers\SettingController;
use App\Http\Controllers\MetaDataController;
use App\Http\Controllers\BlogController;

use App\Http\Controllers\InquiryController;


use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YearController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::fallback(function () {
//     return view('errors.404'); // Make sure the view path matches your custom 404 page
// });

Route::get('login', fn() => redirect()->route('admin.login'))->name('login');

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin-login', [AdminLoginController::class, 'adminLogin'])->name('admin.login.post');
    Route::get('/admin-logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});
// Route::get('/login', function () {
//     return redirect()->route('login');
// });

// Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    return 'Cache is cleared';
});

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::get('/edit', [HomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Users
Route::middleware('auth')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id?}', [UserController::class, 'edit'])->name('edit');
    Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');
    Route::post('/password-update/{Id?}', [UserController::class, 'passwordupdate'])->name('passwordupdate');
    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');
    Route::get('export/', [UserController::class, 'export'])->name('export');
});

//Faq Master
Route::prefix('admin')->name('faq.')->middleware('auth')->group(function () {
    Route::get('/faq/index', [FaqController::class, 'index'])->name('index');
    Route::post('/faq/store', [FaqController::class, 'create'])->name('store');
    Route::get('/faq/edit/{id?}', [FaqController::class, 'editview'])->name('edit');
    Route::post('/faq/update', [FaqController::class, 'update'])->name('update');
    Route::delete('/faq/delete', [FaqController::class, 'delete'])->name('delete');
});

//Inquiry list
Route::prefix('admin')->name('inquiry.')->middleware('auth')->group(function () {
    Route::get('/pending_inquirylist', [InquiryController::class, 'pending_inquirylist'])->name('pending_inquirylist');
    Route::get('/schedule_inquirylist/{inquiry_id?}', [InquiryController::class, 'schedule_inquirylist'])->name('schedule_inquirylist');
    Route::get('/schedule_reschedule_inquirylist', [InquiryController::class, 'schedule_reschedule_inquirylist'])->name('schedule_reschedule_inquirylist');
    Route::delete('/schedule/delete', [InquiryController::class, 'delete'])->name('delete');

    Route::post('/schedule_inquiry/store', [InquiryController::class, 'store'])->name('schedule_store');
    Route::get('/schedule/show', [InquiryController::class, 'schedule_show'])->name('schedule_show');
    Route::get('/inquiry/get-schedule-time', [InquiryController::class, 'getScheduleTime'])
        ->name('getScheduleTime');
    Route::post('/schedule_inquiry/update', [InquiryController::class, 'schedule_update'])->name('schedule_update');
    Route::post('/cancel_inquiry', [InquiryController::class, 'cancel_inquiry'])->name('cancel_inquiry');
    Route::get('/cancel_list', [InquiryController::class, 'cancel_list'])->name('cancel_list');
    Route::get('/dealdone/{inquiryid?}', [InquiryController::class, 'dealdone'])->name('dealdone');
    Route::post('/dealdone/update', [InquiryController::class, 'dealdone_update'])->name('dealdone_update');
    Route::get('/dealdone_list', [InquiryController::class, 'dealdone_list'])->name('dealdone_list');
});

//Testimonial Master
Route::prefix('admin')->name('testimonial.')->middleware('auth')->group(function () {
    Route::get('/testimonial/index', [TestimonialController::class, 'index'])->name('index');
    Route::post('/testimonial/store', [TestimonialController::class, 'create'])->name('store');
    Route::get('/testimonial/edit/{id?}', [TestimonialController::class, 'editview'])->name('edit');
    Route::post('/testimonial/update', [TestimonialController::class, 'update'])->name('update');
    Route::delete('/testimonial/delete', [TestimonialController::class, 'delete'])->name('delete');
});

Route::prefix('admin')->name('metaData.')->middleware('auth')->group(function () {
    Route::get('/seo/index', [MetaDataController::class, 'index'])->name('index');
    Route::get('seo/{id}/edit', [MetaDataController::class, 'edit'])->name('edit');
    Route::put('seo/{id}', [MetaDataController::class, 'update'])->name('update');
});

//Reports
Route::prefix('admin')->name('report.')->middleware('auth')->group(function () {
    Route::any('/Payment/Report', [ReportController::class, 'paymentReport'])->name('paymentReport');
    Route::any('Order/Tracking/', [ReportController::class, 'orderTracking'])->name('orderTracking');

    Route::any('Search/Customer/', [ReportController::class, 'searchCustomer'])->name('searchCustomer');
});

//Setting
Route::prefix('admin')->name('setting.')->middleware('auth')->group(function () {
    Route::get('/setting/index', [SettingController::class, 'index'])->name('index');
    Route::post('/setting/store', [SettingController::class, 'create'])->name('store');
    Route::get('/setting/edit/{id?}', [SettingController::class, 'editview'])->name('edit');
    Route::post('/setting/update', [SettingController::class, 'update'])->name('update');
    Route::delete('/setting/delete', [SettingController::class, 'delete'])->name('delete');
});


//Blog Master
Route::prefix('admin')->name('blog.')->middleware('auth')->group(function () {
    Route::any('/blog/index', [BlogController::class, 'index'])->name('index');
    Route::get('/blog/create', [BlogController::class, 'createview'])->name('create');
    Route::post('/blog/store', [BlogController::class, 'store'])->name('store');
    Route::get('/blog/edit/{id?}', [BlogController::class, 'editview'])->name('edit');
    Route::post('/blog/update/{id?}', [BlogController::class, 'update'])->name('update');
    Route::delete('/blog/delete', [BlogController::class, 'delete'])->name('delete');
});

//Testimonial Master
Route::prefix('admin')->name('testimonial.')->middleware('auth')->group(function () {
    Route::get('/testimonial/index', [TestimonialController::class, 'index'])->name('index');
    Route::get('/testimonial/create', [TestimonialController::class, 'create'])->name('create');
    Route::post('/testimonial/store', [TestimonialController::class, 'store'])->name('store');
    Route::get('/testimonial/edit/{id?}', [TestimonialController::class, 'editview'])->name('edit');
    Route::post('/testimonial/update', [TestimonialController::class, 'update'])->name('update');
    Route::delete('/testimonial/delete', [TestimonialController::class, 'delete'])->name('delete');
});
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::resource('year', YearController::class);
    Route::get('year-status/{id}', [\App\Http\Controllers\YearController::class, 'toggleStatus'])
         ->name('year.toggleStatus');
    Route::get('year/get/{id}', [\App\Http\Controllers\YearController::class, 'getYear'])->name('year.get');
});