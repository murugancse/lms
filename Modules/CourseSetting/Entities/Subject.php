<?php

namespace Modules\CourseSetting\Entities;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subject extends Model
{
    protected $fillable = ['name'];
	
}
