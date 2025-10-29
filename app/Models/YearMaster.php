<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearMaster extends Model
{
    use HasFactory;
    public $table = 'year_masters';
    protected $fillable = [
        'year',
        'prefix',
        'iStatus',
        'isDelete'
    ];
}
