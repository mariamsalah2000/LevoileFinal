<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegisterTransaction extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    // public function hasOne()
    // {
    //     return $this->belongsTo('App\Sale');
    // }
}
