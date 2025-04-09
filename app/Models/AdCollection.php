<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AdCollection extends Pivot
{
    protected $table = 'ads_collections'; // Define the pivot table name

    protected $fillable = [
        'ad_id',
        'collection_id',
        'status',
    ];

    // Define the relationships (if needed)
    public function ad()
    {
        return $this->belongsTo(Ad::class, 'ad_id'); // Ensure the correct foreign key
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id'); // Ensure the correct foreign key
    }
}
