<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model {
    use HasFactory;

    protected $table = 'collections';

    // Many-to-many relation with ads
    public function ads()
    {
        return $this->belongsToMany(Ad::class, 'ads_collections')->using(AdCollection::class);
    }


     // Define the relationship to AdCollection
     public function adCollections()
     {
         return $this->hasMany(AdCollection::class, 'collection_id');
     }
 
     protected static function boot()
     {
         parent::boot();
 
         static::deleting(function ($collection) {
             // Delete all related AdCollection records
             $collection->adCollections()->delete();
         });
     }
     
}
