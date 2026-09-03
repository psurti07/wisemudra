<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyLink extends Model
{
    use HasFactory;
    public $table =  'bankapplylink';
    public $timestamps =  false;
    protected $fillable = ['id','rec_date','bankid','title','applyurl','roi','tenures','status_type','option1','option2','option3','option4','option5','is_recommended','isDelete'];

    public function bank(){
        return $this->belongsTo(Bank::class,'bankid', 'id');
    }
    
}
