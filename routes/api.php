<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InquiryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/Customer_inquiry', [InquiryController::class, 'Customer_inquiry']);
Route::post('/blogs', [InquiryController::class, 'blogs'])->name('blogs');
Route::post('/blog/details', [InquiryController::class, 'blog_details'])->name('blog_details');
Route::post('/faqlist', [InquiryController::class, 'faqlist'])->name('faqlist');
