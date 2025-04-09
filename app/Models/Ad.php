<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model {
    use HasFactory;

    protected $table = 'ads';

    // Belongs to Product
    public function product() {
        return $this->belongsTo(Product::class, 'product_id'); // Ensure the correct foreign key
    }

    // Many-to-many relation with collections through the pivot table
    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'ads_collections', 'ad_id', 'collection_id')->using(AdCollection::class);
    }

}
