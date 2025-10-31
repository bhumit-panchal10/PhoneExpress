<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\MailHelper;

class InquiryController extends Controller
{
    public function pending_inquirylist(Request $request)
    {
        $Inquiries = Inquiry::where('status', 0)->orderBy('inquiry_id', 'desc')->paginate(10);
        return view('admin.inquiries.index', compact('Inquiries'));
    }

    public function dealdone(Request $request, $inquiryid)
    {
        $year_master = DB::table('year_masters')->where(['iStatus' => 1, 'isDelete' => 0])->first();
        $invoic = Inquiry::select('invoiceid')->where('prefix', $year_master->prefix)
            ->orderBy('inquiry_id', 'desc')
            ->first();
        $invoicId = ($invoic->invoiceid ?? 0) + 1;
        $prefix = $year_master->prefix;
        $invoiceno =  $prefix . "_" . $invoicId;
        $Inquiries = Inquiry::where('inquiry_id', $inquiryid)->first();
        //dd($Inquiries);
        return view('admin.inquiries.dealdone', compact('Inquiries', 'invoicId', 'prefix', 'invoiceno'));
    }


    public function schedule_reschedule_inquirylist(Request $request)
    {
        $Inquiries = Inquiry::whereIn('status', [1, 2])
            ->orderBy('inquiry_id', 'desc')
            ->paginate();

        return view('admin.inquiries.scheduleReschedule', compact('Inquiries'));
    }

    public function cancel_list(Request $request)
    {
        $Inquiries = Inquiry::where('status', 3)
            ->orderBy('inquiry_id', 'desc')
            ->paginate();

        return view('admin.inquiries.cancelschedulelist', compact('Inquiries'));
    }

    public function dealdone_list(Request $request)
    {
        $Inquiries = Inquiry::where('status', 4)
            ->orderBy('inquiry_id', 'desc')
            ->paginate();

        return view('admin.inquiries.dealdone_list', compact('Inquiries'));
    }

    public function schedule_inquirylist(Request $request, $inquiry_id)
    {
        $Inquiries = Inquiry::where('inquiry_id', $inquiry_id)->first();
        return view('admin.inquiries.scheduleInquiry', compact('Inquiries'));
    }

    public function store(Request $request)
    {
        // ✅ Validate inputs
        $request->validate([
            'inquiry_id' => 'required|integer',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required'
        ]);


        // ✅ Fetch the existing record for that inquiry
        $existing = DB::table('inquiry')
            ->where('inquiry_id', $request->inquiry_id)
            ->first();

        // ✅ Determine status
        if ($existing && !empty($existing->schedule_date) && !empty($existing->schedule_time)) {
            // There’s already a schedule → mark as 2 (rescheduled)
            $status = 2;
            $mailSubject = 'Inquiry Rescheduled';
        } else {
            // First time scheduling → mark as 1
            $status = 1;
            $mailSubject = 'Inquiry Scheduled';
        }

        // ✅ Prepare data
        $data = [
            'schedule_date' => $request->date,
            'schedule_time' => $request->time,
            'status' => $status,
            'updated_at' => now(),
        ];

        // ✅ Update the record
        DB::table('inquiry')
            ->where('inquiry_id', $request->inquiry_id)
            ->update($data);

        $date = date('d-m-Y', strtotime($request->date));
        $time = date('h:i A', strtotime($request->time));

        $data = [
            'Name' => $existing->customer_name ?? 'Customer',
            'Email' => $existing->customer_email ?? 'admin@example.com',
            'Mobile' => $existing->customer_phone ?? '-',
            'Message' => "Your inquiry has been scheduled for {$date} at {$time}.",
            'Subject' => $mailSubject,
        ];

        MailHelper::sendPartnerMail($data);


        return back()->with('success', 'Schedule added successfully');
    }


    public function schedule_show(Request $request)
    {
        $inquiries = Inquiry::select('inquiry_id', 'customer_name', 'schedule_date', 'schedule_time')->get();

        $data = $inquiries->filter(function ($item) {
            return !empty($item->schedule_date) && !empty($item->schedule_time);
        })->map(function ($item) {
            $time24 = date("H:i", strtotime($item->schedule_time));
            $formattedDate = date('d-m-Y', strtotime($item->schedule_date));


            return [
                'id' => $item->inquiry_id,
                'title' => $item->customer_name . ' - ' . $formattedDate . ' (' . $item->schedule_time . ')',
                'start' => "{$item->schedule_date}T{$time24}:00", // ✅ ISO format
                'allDay' => false,
                'color' => '#4caf50',
            ];
        })->values(); // ensure proper array indexing
        return response()->json($data);
    }

    public function schedule_update(Request $request)
    {


        $existing = DB::table('inquiry')->where('inquiry_id', $request->inquiry_id)->first();

        $Data = array(
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schdule_time,
            'status' => 2,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $date = date('d-m-Y', strtotime($request->schedule_date));
        $time = date('h:i A', strtotime($request->schdule_time));
        $data = [
            'Name' => $existing->customer_name ?? 'Customer',
            'Email' => $existing->customer_email ?? 'admin@example.com',
            'Mobile' => $existing->customer_phone ?? '-',
            'Message' => "Your inquiry has been scheduled for {$date} at {$time}.",
            'Subject' => 'Inquiry Rescheduled',
        ];

        MailHelper::sendPartnerMail($data);
        DB::table('inquiry')->where('inquiry_id', $request->inquiry_id)->update($Data);

        return back()->with('success', 'Reschedule Add Successfully.');
    }

    public function dealdone_update(Request $request)
    {

        $year_master = DB::table('year_masters')->where(['iStatus' => 1, 'isDelete' => 0])->first();
        $invoic = Inquiry::select('invoiceid')->where('prefix', $year_master->prefix)
            ->orderBy('inquiry_id', 'desc')
            ->first();
        $invoicId = ($invoic->invoiceid ?? 0) + 1;
        $prefix = $year_master->prefix;
        $invoiceno =  $prefix . "_" . $invoicId;
        $Data = array(
            'invoiceno' => $invoiceno,
            'prefix' => $prefix,
            'invoiceid' => $invoicId,
            'invoiceno' => $invoiceno,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'imei_1' => $request->imei_1,
            'imei_2' => $request->imei_2 ?? '',
            'brand' => $request->brand,
            'model' => $request->model,
            'address' => $request->address,
            'actual_amount' => $request->actual_amount,
            'status' => 4,
            'updated_at' => date('Y-m-d H:i:s')
        );
        DB::table('inquiry')->where('inquiry_id', $request->inquiry_id)->update($Data);

        $deal = DB::table('inquiry')->where('inquiry_id', $request->inquiry_id)->first();

        $dealdata = [
            'Name' => $deal->customer_name ?? 'Customer',
            'Email' => $deal->customer_email ?? 'admin@example.com',
            'Mobile' => $deal->customer_phone ?? '-',
            'invoiceno' => $deal->invoiceno ?? '-',
            'Amount' => $deal->actual_amount ?? '-',
            'Brand' => $deal->brand ?? '-',
            'inquiry_id' => $deal->inquiry_id ?? '-',
            'Model' => $deal->model ?? '-',
            'Subject' => 'Deal Done',
        ];
        MailHelper::sendDealDoneMail($dealdata);

        return redirect()->route('inquiry.schedule_reschedule_inquirylist')->with('success', 'Deal Done Successfully.');
    }

    public function cancel_inquiry(Request $request)
    {

        $Data = array(
            'status' => 3
        );
        DB::table('inquiry')->where('inquiry_id', $request->inquiryid)->update($Data);

        return back()->with('success', 'Cancel Schedule Add Successfully.');
    }

    public function getScheduleTime(Request $request)
    {
        $date = $request->date;

        // Fetch the first schedule for that date
        $schedule = Inquiry::where('schedule_date', $date)
            ->select('schedule_time')
            ->first();

        if ($schedule) {
            return response()->json([
                'success' => true,
                'time' => $schedule->schedule_time
            ]);
        } else {
            return response()->json([
                'success' => false,
                'time' => null
            ]);
        }
    }

    public function delete(Request $request)
    {
        DB::table('inquiry')->where(['iStatus' => 1, 'isDelete' => 0, 'inquiry_id' => $request->inquiryid])->delete();
        return back()->with('success', 'inquiryid Deleted Successfully!.');
    }
}
