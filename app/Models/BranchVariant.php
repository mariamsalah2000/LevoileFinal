<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchVariant extends Model {

    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['branch_id','variant_id','qty','created_at','updated_at'];
    public $timestamps = false;
    protected $table = "branch_variants";

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

}
