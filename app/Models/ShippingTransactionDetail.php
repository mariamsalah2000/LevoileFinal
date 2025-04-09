<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingTransactionDetail extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function transaction()
    {
        return $this->belongsTo(ShippingTransaction::class,'transaction_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
