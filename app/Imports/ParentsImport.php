<?php

namespace App\Imports;

use App\Models\StudentParent;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use DB;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Batch;

class ParentsImport implements ToCollection
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
                //$slno = $row1[0];
                $district = $row1[1];
                $city = $row1[2];
                $parent_name = $row1[3];
                $parent_ic = $row1[4];
                $student_name = $row1[5];
                $student_ic = $row1[6];
                $school_name = $row1[7];
                $house_address = $row1[8];
                $post_code = $row1[9];
                //$city = $row1[10];
                $state = $row1[11];
                $parent_phone_no = $row1[12];
                $parent_email = $row1[13];
                $password = \Hash::make('12345678');

                if($parent_name!="" && $student_name!="" && $parent_ic!=""){
                    $count = StudentParent::where('username',$parent_email)->count();
                    if($count==0){
                        $data = array(
                            'parent_name' => $parent_name,
                            'parent_ic' => $parent_ic,
                            'parent_phone_no' => $parent_phone_no,
                            'parent_email' => $parent_email,
                            'is_active' => 1,
                            'username' => $parent_email,
                            'password' => $password,
                            'state' => $state,
                            'district' => $district,
                            'city' => $city,
                            'house_address' => $house_address,
                            'student_name' => $student_name,
                            'student_ic' => $student_ic,
                            'school_name' => $school_name,
                            'post_code' => $post_code,
                            'status' => 1,
                            'country' => 101,
                            'currency_id' => 112,
                            'added_by' => 1,
                            'email_verify' => 1,
                            'email_verified_at' => now(),
                        );
                        // print_r($data);

                        $dataid = DB::table('parents')->insertGetId($data);
                    }


                }
            }
        }
    }
}
