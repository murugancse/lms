<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StudentParent extends Authenticatable
{
    protected $table = 'parents';

    protected $fillable = ['parent_name','parent_ic','parent_phone_no','parent_email','country','state','district','city','post_code','house_address','student_name','password','username'];
    //
}
