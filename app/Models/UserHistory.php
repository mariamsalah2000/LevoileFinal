<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

        public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function product()
    {
        return $this->belongsTo(PrepareProductList::class,'product_id');
    }

}
