<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepareProductList extends Model {

    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function prepare()
    {
        return $this->belongsTo(Prepare::class);
    }

    public function getReturnStatusAttribute()
    {
        $returns = ReturnedOrder::where('order_number', $this->order->order_number)
            ->get()
            ->flatMap(function ($return) {
                return $return->details->map(function ($detail) use ($return) {
                    return [
                        'line_item_id' => $detail->line_item_id,
                        'status' => $return->status,
                    ];
                });
            });
        // Check if current product has a return entry and get its status
        $return = $returns->where('line_item_id', $this->product_id)->first();
        if ($return)
            return $return['status'] == "In Progress" ? "Return In Progress" : $return['status'];
        else
            return null;
    }

    public function getIsRefundedAttribute()
    {
            // Check for refunds
        $hasRefund = Refund::where('order_name', $this->order->name)
            ->where('line_item_id', $this->product_id)
            ->exists();

        return $hasRefund;
    }

    

}
