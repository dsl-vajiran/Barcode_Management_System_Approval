<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ybisrtn extends Model
{
    protected $connection = 'hana';
    protected $table = 'YBISRTN';
    public $timestamps = false;
}
