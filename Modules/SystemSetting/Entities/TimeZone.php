<?php

namespace Modules\SystemSetting\Entities;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class TimeZone extends Model
{
    use QueryCacheable;
    protected static $flushCacheOnUpdate = true;

    protected $fillable = [];
}
