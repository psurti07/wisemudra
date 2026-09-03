<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;
    protected $table = 'user_documents';
    public $timestamps = false;
    protected $fillable = ['id', 'rec_date', 'userid', 'profilephoto', 'aadharcard', 'aadharcard_number', 'pancard', 'pancard_number', 'cancelcheque', 'lightbill', 'lightbill', 'bankstatement', 'formsixteen', 'salaryslip', 'businessproof', 'itreturn', 'remarks', 'isVerified'];
}
