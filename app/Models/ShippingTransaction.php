<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingTransaction extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function details()
    {
        return $this->hasMany(ShippingTransactionDetail::class,'transaction_id');
    }
}
