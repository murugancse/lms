<?php

namespace Modules\CourseSetting\Entities;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Grade extends Model
{
    protected $fillable = ['name'];
	
}
