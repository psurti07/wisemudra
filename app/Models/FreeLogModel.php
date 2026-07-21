<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeLogModel extends Model
{
    use HasFactory;
    protected $table = 'free_log_entry';
    public $timestamps = false;
    protected $fillable = [
        'id', 'rec_date', 'entryfor', 'userid','orderid', 'orderamount', 'ordernote'
    ];
}
