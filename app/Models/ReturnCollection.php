<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnCollection extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    protected $table = "return_collections";
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function details()
    {
        return $this->hasMany(ReturnCollectionDetail::class,'collection_id');
    }
}
