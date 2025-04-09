<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCollection extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    protected $table = "shipping_collections";
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function converted()
    {
        return $this->belongsTo(User::class,'converted_by');
    }
}
