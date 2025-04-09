<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnedOrder extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function details()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
    public function user()
    {
        return $this->BelongsTo(User::class, 'user_id');
    }

}
