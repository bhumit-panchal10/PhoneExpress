<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityMaster;
use App\Models\StateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\PushNotificationController;
use App\Models\Blog;
use App\Models\Inquiry;
use Google\Service\Monitoring\Custom;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\BaseURL;

class InquiryController extends Controller
{
    public function Customer_inquiry(Request $request)
    {

        try {
            $request->validate([
                'Customer_name' => 'required',
                'Customer_email' => 'required',
                'Customer_phone' => 'required|digits:10|unique:inquiry,customer_phone',
                'brand' => 'nullable',
                'device_condition' => 'nullable',
                'imei_1' => 'required',
                'expected_amt' => 'required',
                'message' => 'required',
            ]);
            $existingCustomer = Inquiry::where('customer_phone', $request->Customer_phone)->first();
            if ($existingCustomer) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'A customer with this mobile number already exists.',
                    ],
                    409,
                ); // 409 Conflict HTTP status code
            }
            $Customerdata = [
                'customer_name' => $request->Customer_name,
                'customer_email' => $request->Customer_email,
                'customer_phone' => $request->Customer_phone,
                'brand' => $request->brand,
                'device_condition' => $request->device_condition,
                'imei_1' => $request->imei_1,
                'expected_amt' => $request->expected_amt,
                'message' => $request->message,
                'created_at' => now(),
            ];

            $Customer = Inquiry::create($Customerdata);
            DB::commit();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Inquiry Add Successfully.',
                ],
                200,
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            // Format validation errors as a single string
            $errorMessage = implode(', ', Arr::flatten($e->errors()));

            return response()->json(
                [
                    'success' => false,
                    'message' => $errorMessage,
                ],
                422,
            );
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(
                [
                    'success' => false,
                    'error' => $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function blogs(Request $request)
    {
        try {
            $blogs = Blog::get();

            $data = [];
            foreach ($blogs as $ourTeam) {
                $data[]  = array(
                    "blogId" => $ourTeam->blogId,
                    "blogTitle" => $ourTeam->strTitle,
                    "slugname" => $ourTeam->strSlug,
                    "blogDescription" => $ourTeam->strDescription,
                    // "blogDate" => $ourTeam->blogDate,
                    "blogImage" => asset('uploads/Blog/' . $ourTeam->strPhoto)
                );
            }


            return response()->json([
                'message' => 'successfully blogs fetched...',
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            // If there's an error, rollback any database transactions and return an error response.
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function blog_details(Request $request)
    {
        try {

            $request->validate(
                [
                    'slugname' => 'required'
                ]
            );

            $blog = Blog::where(['isDelete' => 0, 'iStatus' => 1, 'strSlug' => $request->slugname])->first();

            $data = array(
                "blogId" => $blog->blogId,
                "blogTitle" => $blog->strTitle,
                "slugname" => $blog->strSlug,
                "blogDescription" => $blog->strDescription,
                //"blogDate" => $blog->blogDate,
                "metaTitle" => $blog->metaTitle,
                "metaKeyword" => $blog->metaKeyword,
                "metaDescription" => $blog->metaDescription,
                "head" => $blog->head,
                "body" => $blog->body,
                "blogImage" => asset('upload/Blog/' . $blog->strPhoto)
            );

            return response()->json([
                'message' => 'successfully blog detail fetched...',
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            // If there's an error, rollback any database transactions and return an error response.
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
