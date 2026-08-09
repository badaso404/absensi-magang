<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalConfiguration extends Model
{
    protected $table = "global_configurations";
    
    protected $fillable = [
        'key',
        'value',
    ];
}
