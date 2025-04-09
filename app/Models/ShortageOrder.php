<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortageOrder extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class,'assign_to');
    }
    public function moderator()
    {
        return $this->belongsTo(User::class,'assign_to_moderator');
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
    

}
