<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use DB;

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
                $roll_number = $row1[4];
                $dobstring = $row1[5];
                $password = \Hash::make($row1[6]);

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
                    
                    $datas = DB::table('users')->insertGetId($data);
                    //dd($datas);
                }
            }
        }
    }
}
