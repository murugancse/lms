<?php

namespace Modules\Newsletter\Http\Controllers;


use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Newsletter\Entities\NewsletterSetting;

class NewsletterController extends Controller
{
    public $mailchimp, $getResponses;

    public function __construct()
    {
        $this->mailchimp = new MailchimpController();
        $this->getResponses = new GetResponseController();
    }

    public function setting()
    {
        $setting = NewsletterSetting::first();
        $lists = $this->mailchimp->mailchimpLists();
        $responsive_lists = $this->getResponses->getResponseLists();
        return view('newsletter::setting', compact('lists', 'setting', 'responsive_lists'));
    }

    public function update(Request $request)
    {


        if ($request->home_service == "Mailchimp") {
            $request->validate([
                'home_list' => 'required',
            ]);
        }
        if ($request->home_service == "GetResponse") {
            $request->validate([
                'home_get_response_list' => 'required',
            ]);
        }


        if ($request->student_service == "Mailchimp") {
            $request->validate([
                'student_list' => 'required',
            ]);
        }
        if ($request->student_service == "GetResponse") {
            $request->validate([
                'student_get_response_list' => 'required',
            ]);
        }

        if ($request->instructor_service == "Mailchimp") {
            $request->validate([
                'instructor_list' => 'required',
            ]);
        }
        if ($request->instructor_service == "GetResponse") {
            $request->validate([
                'instructor_get_response_list' => 'required',
            ]);
        }

        try {

            $setting = NewsletterSetting::first();
            $setting->home_service = $request->home_service;
            $setting->student_service = $request->student_service;
            $setting->instructor_service = $request->instructor_service;


            if ($request->home_service == "Mailchimp") {
                $setting->home_list_id = $request->home_list;
            }
            if ($request->home_service == "GetResponse") {
                $setting->home_list_id = $request->home_get_response_list;
            }

            if ($request->student_service == "Mailchimp") {
                $setting->student_list_id = $request->student_list;
            }
            if ($request->student_service == "GetResponse") {
                $setting->student_list_id = $request->student_get_response_list;
            }

            if ($request->instructor_service == "Mailchimp") {
                $setting->instructor_list_id = $request->instructor_list;

            }
            if ($request->instructor_service == "GetResponse") {
                $setting->instructor_list_id = $request->instructor_get_response_list;
            }

            if ($request->home_status) {
                $setting->home_status = 1;
            } else {
                $setting->home_status = 0;
            }
            if ($request->student_status) {
                $setting->student_status = 1;
            } else {
                $setting->student_status = 0;
            }
            if ($request->instructor_status) {
                $setting->instructor_status = 1;
            } else {
                $setting->instructor_status = 0;
            }
            $setting->save();
            Toastr::success("Operation Successful", 'Success');
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error("Something went wrong", 'Failed');
            return redirect()->back();
        }
    }
}
