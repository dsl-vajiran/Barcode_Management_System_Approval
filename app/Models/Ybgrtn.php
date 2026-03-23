<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ybgrtn extends Model
{
    protected $connection = 'hana';
    protected $table = 'YBGRTN';
    protected $primaryKey = 'GBARCODE';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'gbarcode',
        'gitmcode',
        'gdte',
        'grnno',
        'gcrtusr',
        'gcrtdtme',
        'gremark',
        'gchprt',
        'gchact',
        'reason',
        'rtndtme',
        'whscode',
    ];

    protected $casts = [
        'gdte' => 'date',
        'gcrtdtme' => 'datetime',
        'rtndtme' => 'datetime',
        'gchprt' => 'integer',
        'gchact' => 'integer',
    ];

    public $timestamps = true;

    // Relationship to Item Master
    public function itemMaster()
    {
        return $this->belongsTo(Ybimmst::class, 'gitmcode', 'itmcode');
    }
}
