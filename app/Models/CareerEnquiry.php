<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerEnquiry extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'rec_date',
        'firstname',
        'lastname',
        'mobile',
        'email',
        'applyfor',
        'resume',
        'qualifications',
        'experience',
        'city',
        'keyskills',
        'serverip',
        'isDelete' ];
}
