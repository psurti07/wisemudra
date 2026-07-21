<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoPages extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['id','slug','content','rec_date'];
}
