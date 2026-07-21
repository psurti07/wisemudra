<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingTransaction extends Model
{
    use HasFactory;

    protected $table = 'onboarding_transaction';

    protected $fillable = [
        'date',
        'rec_date',
        'company_code',
        'total_leads',
        'total_customers',
        'total_amount',
        'gst_amount',
        'isActive',
        'isDelete'
    ];

    public $timestamps = false;
}
