<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\SystemSetting\Entities\Message;
use Modules\CourseSetting\Entities\Notification;
use Modules\CourseSetting\Entities\CourseComment;
use Modules\CourseSetting\Entities\Course;
use DB;
use Mail;

class CommunicationController extends Controller
{
    public function QuestionAnswer()
    {
        $comments = CourseComment::where('instructor_id', Auth::id())->with('course', 'replies', 'user')->paginate(10);
        return view('backend.communication.question_answer', compact('comments'));
    }

    public function PrivateMessage()
    {
        $users = [];
        if (Auth::user()->role_id == 1)
            $users = User::where('id', '!=', Auth::id())->where('role_id',2)->with('reciever')->get();
        elseif (Auth::user()->role_id == 2)
            $users = User::with('reciever')->where('id','!=',Auth::id())->where(function ($query){
              $query->where('role_id',1)->orWhereHas('enrollStudents');
            })->get();


        $singleMessage = Message::where('sender_id', Auth::id())->orderBy('id', 'DESC')->first();
        if ($singleMessage) {
            $messages = Message::whereIn('reciever_id', array(Auth::id(), $singleMessage->reciever_id))
                ->whereIn('sender_id', array(Auth::id(), $singleMessage->reciever_id))->get();

        } else {
            $messages = "";
        }
        // return $singleMessages;
        return view('backend.communication.private_messages', compact('messages', 'users', 'singleMessage'));
    }


    public function StudentMessage()
    {
        $users = [];
        $users = User::where('id', '!=', Auth::id())->where('role_id',3)->with('reciever')->get();
        

        $singleMessage = Message::where('sender_id', Auth::id())->orderBy('id', 'DESC')->first();
        if ($singleMessage) {
            $messages = Message::whereIn('reciever_id', array(Auth::id(), $singleMessage->reciever_id))
                ->whereIn('sender_id', array(Auth::id(), $singleMessage->reciever_id))->get();

        } else {
            $messages = "";
        }
        $courses = Course::get();
        // return $singleMessages;
        return view('backend.communication.student_messages', compact('messages', 'users', 'singleMessage','courses'));
    }

    public function StudentMails()
    {
        $users = User::where('id', '!=', Auth::id())->where('role_id',2)->get();
        
        $courses = Course::get();
        // return $singleMessages;
        return view('backend.communication.student_mails', compact('users','courses'));
    }



    public function StorePrivateMessage(Request $request)
    {

        $request->validate([
            'message' => 'required',
        ]);
        try {

            $message = new Message;
            $message->sender_id = Auth::id();
            $message->reciever_id = $request->reciever_id;
            $message->message = $request->message;
            $message->type = Auth::id() == 1 ? 1 : 2;
            $message->seen = 0;
            $message->save();

            $notification = new Notification();
            $notification->author_id = Auth::id();
            $notification->user_id = $request->reciever_id;
            $notification->message_id = $message->id;
            $notification->save();


            Toastr::success('Message has been send successfully', 'Success');

            $messages = Message::whereIn('reciever_id', array(Auth::id(), $request->reciever_id))
                ->whereIn('sender_id', array(Auth::id(), $request->reciever_id))->get();

            // return $messages;
            $output = getConversations($messages);

            return response()->json($output);

        } catch (\Exception $e) {

            Log::info($e);
            return response()->json(['error' => $e]);
        }
    }

    public function StoreStudentMessage(Request $request)
    {
        //return $request->all();

        $request->validate([
            'message' => 'required',
            'course_id' => 'required',
        ]);
        try {
            $course_id = $request->course_id;
            $student_id = $request->student_id;
            if(!isset($student_id)){
                $students = DB::table('course_enrolleds as e')
                            ->leftjoin('users as u', 'u.id', 'e.user_id')
                            ->where('e.course_id', '=', $course_id)
                            ->pluck('u.id')->toArray();
                $users = $students;
            }else{
                $users = $student_id;
            }

            foreach($users as $user){
                $message = new Message;
                $message->sender_id = Auth::id();
                $message->reciever_id = $user;
                $message->message = $request->message;
                $message->type = Auth::id() == 1 ? 1 : 2;
                $message->seen = 0;
                $message->save();

                $notification = new Notification();
                $notification->author_id = Auth::id();
                $notification->user_id = $user;
                $notification->message_id = $message->id;
                $notification->save();
            }


            Toastr::success('Message has been send successfully', 'Success');

            return redirect()->back();

        } catch (\Exception $e) {

            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function SendStudentMail(Request $request)
    {
        //return $request->all();

        $request->validate([
            'subject' => 'required',
            'message' => 'required',
            'type' => 'required',
            'instructor_id' => 'required_if:type,2',
            'course_id' => 'required_if:type,1',
        ]);
       // try {
            $type = $request->type;
            $course_id = $request->course_id;
            $student_id = $request->student_id;
            $instructor_id = $request->instructor_id;
            $messagedata = $request->message;
            $subject = $request->subject;
            if($type==1){
                if(!isset($student_id)){
                    $students = DB::table('course_enrolleds as e')
                                ->leftjoin('users as u', 'u.id', 'e.user_id')
                                ->where('e.course_id', '=', $course_id)
                                ->whereNotNull('u.id')
                                ->pluck('u.id')->toArray();
                    $users = $students;
                }else{
                    $users = $student_id;
                }
            }else{
                $users = $instructor_id;
            }
            $emails = DB::table('users')->whereIn('id',$users)->pluck('email')->toArray();
            //dd($emails);
            
            //$emails = ['muruganaccetcse@gmail.com', 'murugancse1994@gmail.com'];

            Mail::send('mail.bulk', ['data' => $messagedata], function($message) use ($emails,$subject)
            {    
                $message->to($emails)->subject($subject);    
            });
           

            // foreach($users as $user){
                
            //     Mail::to('Cloudways@Cloudways.com')->send(new BulkMail($message));
            // }


            Toastr::success('Mail has been send successfully', 'Success');

            return redirect()->back();

        // } catch (\Exception $e) {

        //     Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
        //     return redirect()->back();
        // }
    }

    public function getMessage(Request $request)
    {

        try {
            $receiver_id=$request->id;
           $messages= Message::whereIn('reciever_id',array(Auth::id(),$receiver_id))
                         ->whereIn('sender_id',array(Auth::id(),$receiver_id))->get();
            $output =getConversations($messages);
            Message::where('seen', '=', 0)->where('sender_id',$receiver_id)->where('reciever_id',Auth::id())->update(['seen' => 1]);
            $data['messages']=$output;
            $receiver=User::find($receiver_id);
            $data['receiver_name']=$receiver->name;
            $data['avatar']=url('public/'.$receiver->image);
            return response()->json($data);

        } catch (\Exception $e) {

            Log::info($e);
            return response()->json(['error' => 'error']);
        }
    }


}
