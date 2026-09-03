<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'rec_date',
        'productname',
        'productslug',
        'amount',
        'offeramount',
        'inOffer'];
}
