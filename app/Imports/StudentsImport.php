<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use DB;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Batch;

class StudentsImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function collection(Collection $rows)
    {
        
        $totalcount=count($rows);      
        $count=0;
        
        foreach($rows as $key=>$row1){
            
            if($rows[$key] != $rows[0]){
                $name = $row1[0];
                $email = $row1[1];
                $username = $row1[2];
                $phone = $row1[3];
                $nric = $row1[4];
                $roll_number = $row1[5];
                $dobstring = $row1[6];
                $password = \Hash::make($row1[7]);

                $coursename = trim($row1[8]);
                $batchname = trim($row1[9]);

                if($dobstring!=''){
                  $UNIX_DATE = ($dobstring - 25569) * 86400;
                  $dob = gmdate("d/m/Y", $UNIX_DATE);
                }

                
                if($name!="" && $email!="" && $username!=""){
                    $data = array(
                        'name' => $name,
                        'email' => $email,
                        'username' => $username,
                        'phone' => $phone,
                        'roll_number' => $roll_number,
                        'dob' => $dob,
                        'password' => $password,
                        'language_id' => getSetting()->language_id,
                        'language_code' => getSetting()->language->code,
                        'language_name' => getSetting()->language->name,
                        'added_by' => 1,
                        'email_verify' => 1,
                        'email_verified_at' => now(),
                        'referral' => Str::random(10),
                        'role_id' => 3,
                     );
                   // print_r($data);
                    
                    $dataid = DB::table('users')->insertGetId($data);
                    //dd($datas);
                    $course = Course::where('title',$coursename)->first();
                    if(!empty($course)){
                       // dd($course);
                        $enrolled = $course->total_enrolled;

                        $course->total_enrolled = ($enrolled + 1);

                        $batch = Batch::where('batch_name',$batchname)->first();
                        $enroll = new CourseEnrolled();

                        if(!empty($batch)){
                            $enroll->batch_id = $batch->id;
                        }
                      
                        
                        //$instractor = User::find($cart->instructor_id);
                        $enroll->user_id = $dataid;
                        $enroll->tracking = getTrx();
                        $enroll->course_id = $course->id;
                        
                        $enroll->purchase_price = $course->price;
                        $enroll->coupon = null;
                        $enroll->discount_amount = $course->discount_price=='' ? 0 : $course->discount_price;
                        $enroll->status = 1;
                        $enroll->reveune = 0;
                        $enroll->save();
                        //dd($enroll);
                        $course->save();
                    }
                    
                }
            }
        }
    }
}
