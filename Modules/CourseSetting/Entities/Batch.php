<?php

namespace Modules\CourseSetting\Entities;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Batch extends Model
{
    protected $fillable = ['course_id','batch_name','start_date','end_date'];
	
	public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->withDefault();
    }
    public function exams()
    {
        return $this->hasMany(Exam::class)->orderBy('id', 'asc');
    }
}
