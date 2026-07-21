<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Careers extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'rec_date',
        'title',
        'descriptions',
        'slug',
        'isActive',
        'isDelete'];
}
