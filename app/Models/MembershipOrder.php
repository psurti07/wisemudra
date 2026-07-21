<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipOrder extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['id','rec_date','userid','registration_date','expiry_date','card_number','amount','paymentid','isActive','isDelete'];

    public function userRegistration()
    {
        return $this->belongsTo(UserRegistration::class,'userid');
    }
}
