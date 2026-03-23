<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ybissue extends Model
{
    protected $connection = 'hana';
    protected $table = 'YBISSUE';
    protected $primaryKey = 'IBARCODE';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IBARCODE',
        'INVNO',
        'ITMNME',
        'ITMMOD',
        'ITMAMP',
        'F_WAR',
        'PA_WAR',
        'REMARK',
        'PRPHASE',
        'BRAND',
        'ISUDTME',
        'IREMARK',
        'ICHSALE',
        'SALEDTME',
        'ICHAPR',
        'IAPRDTE',
        'FNCUSNM',
        'FNCUSTP',
        'LOCATION',
    ];

    protected $casts = [
        'f_war' => 'integer',
        'pa_war' => 'integer',
        'ichsale' => 'integer',
        'ichapr' => 'integer',
    ];

    public function getIsudtmeAttribute($value)
    {
        return $this->parseHanaDate($value);
    }

    public function getSaledtmeAttribute($value)
    {
        return $this->parseHanaDate($value);
    }

    public function getIaprdteAttribute($value)
    {
        return $this->parseHanaDate($value);
    }

    private function parseHanaDate($value)
    {
        if (!$value) return null;
        // Remove null bytes and trim
        $clean = trim(str_replace("\0", "", $value));
        try {
            return \Carbon\Carbon::parse($clean);
        } catch (\Exception $e) {
            \Log::error("Failed to parse HANA date: " . bin2hex($value));
            return null;
        }
    }

    public $timestamps = false;
}
