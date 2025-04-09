<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundableReturn extends Model {

    use HasFactory;
    protected $guarded = [];
    protected $table = "refundable_returns";
    public $timestamps = false;

    

}
