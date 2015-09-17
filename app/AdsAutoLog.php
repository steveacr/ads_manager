<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsAutoLog extends Model
{
    //
    protected $table = 'ads_auto_log';

    protected $fillable = [
        's_id',
        'index_id',
        'status',
        'brand',
        'product',
        'startSec',
        'endSec',
        'filename',
    ];
}
