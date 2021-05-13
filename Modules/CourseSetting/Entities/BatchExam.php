<?php

namespace Modules\CourseSetting\Entities;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BatchExam extends Model
{
    protected $fillable = ['batch_id','user_id','exam_date'];
	
	public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id')->withDefault();
    }
}
