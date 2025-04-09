<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnCollectionDetail extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function collection()
    {
        return $this->belongsTo(ReturnCollection::class,'collection_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
