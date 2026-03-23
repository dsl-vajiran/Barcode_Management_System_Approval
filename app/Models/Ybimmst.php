<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ybimmst extends Model
{
    protected $connection = 'hana';
    protected $table = 'YBIMMST';
    protected $primaryKey = 'ITMCODE';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ITMCODE',
        'ITMNME',
        'ITMMOD',
        'ITMAMP',
        'F_WAR',
        'PA_WAR',
        'REMARK',
        'PRPHASE',
        'BRAND',
    ];

    protected $casts = [
        'F_WAR' => 'integer',
        'PA_WAR' => 'integer',
    ];

    public $timestamps = false;
}
