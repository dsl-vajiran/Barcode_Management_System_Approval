<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ybismanu extends Model
{
    protected $connection = 'hana';
    protected $table = 'YBISMANU';
    protected $primaryKey = 'IBARCODE';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ibarcode',
        'invno',
        'itmnme',
        'itmmod',
        'itmamp',
        'f_war',
        'pa_war',
        'remark',
        'prphase',
        'brand',
        'isudtme',
        'iremark',
        'ichsale',
        'saledtme',
        'ichapr',
        'iaprdte',
        'fncusnm',
        'fncustp',
        'location',
    ];

    protected $casts = [
        'f_war' => 'integer',
        'pa_war' => 'integer',
        'isudtme' => 'datetime',
        'ichsale' => 'integer',
        'saledtme' => 'datetime',
        'ichapr' => 'integer',
        'iaprdte' => 'datetime',
    ];

    public $timestamps = true;
}
