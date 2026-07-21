<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['id','rec_date','cardid','userid','inv_prefix','inv_number','inv_date','inv_price','inv_cgst','inv_sgst','inv_igst','inv_grandtotal','remarks','isdelete','is_refund'];
}
