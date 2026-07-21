<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbAdsEntry extends Model
{
    use HasFactory;
    
    public $table = 'fb_ads_entry';
    public $timestamps = false;
    
    protected $fillable = [
        'id', 'rec_date', 'userid', 'fbclid', 'send_data', 'received_data'    
    ];
}
