<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrations extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['id','rec_date','fullname','mobile','emailid','password','staff_code','role','isActive','isDelete'];
}
