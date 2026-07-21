<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    public $table = 'banks';
    public $timestamps = false;
    protected $fillable = [
        'id', 'rec_date','bank_name','bank_image', 'order_no','isActive','isDelete'
    ];

    public function applylinks(){
        return $this->hasOne(ApplyLink::class,'bankid','id');
    }
}
