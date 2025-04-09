<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequestDetail extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function request()
    {
        return $this->belongsTo(StockRequest::class);
    }

}
