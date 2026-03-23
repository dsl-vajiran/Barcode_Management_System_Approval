<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ybgrn extends Model
{
    protected $connection = 'hana';
    // Use unqualified table name and rely on configured schema
    protected $table = 'YBGRN';
    protected $primaryKey = 'GBARCODE';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'GBARCODE',
        'GITMCODE',
        'GDTE',
        'GRNNO',
        'GCRTUSR',
        'GCRTDTME',
        'GREMARK',
        'GCHPRT',
        'GCHACT',
        'WHSCODE',
        'CHWHSPRT',
    ];

    protected $casts = [
        'GDTE' => 'date',
        'GCRTDTME' => 'datetime',
        'GCHPRT' => 'integer',
        'GCHACT' => 'integer',
        'CHWHSPRT' => 'integer',
    ];

    public $timestamps = false;

    // Relationship to Item Master
    public function itemMaster()
    {
        return $this->belongsTo(Ybimmst::class, 'GITMCODE', 'ITMCODE');
    }
}
