<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection; 
use App\Models\Ad; 
use App\Models\AdCollection; 
use App\Models\Product; 
use App\Models\ProductVariant; 
use Illuminate\Support\Facades\DB;


class AdsController extends Controller
{
    // Show list of ads
    public function index(Request $request)
    {
        $query = AdCollection::with(['ad', 'collection']); // Eager load the related models
            

        if ($request->filled('name') && $request->input('name') !== '') {
            $query->whereHas('ad.product', function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->input('name') . '%');
            });
        }
        
        // Checking for status and collection_id
        if ($request->filled('status') && $request->input('status') !== '') {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('stock_status') && $request->input('stock_status') !== '') {
            $query->where('stock_status', $request->input('stock_status'));
        }
        
        if ($request->filled('collection_id') && $request->input('collection_id') !== '') {
            $query->where('collection_id', $request->input('collection_id'));
        }
        
        // Date range check
        if ($request->filled('from_date') && $request->filled('to_date') && 
            $request->input('from_date') !== '' && $request->input('to_date') !== '') {
            $query->whereBetween('created_at', [$request->input('from_date'), $request->input('to_date')]);
        }
        
        $query->orderBy('created_at', 'desc');
        $adsCollections = $query->get();
        $collections = Collection::all();
    
        return view('ads.index', compact('adsCollections', 'collections'));
    }

    public function editAd($adId,$collectionId)
    {
        $collection = Collection::findOrFail($collectionId);
        $ad = Ad::findOrFail($adId);
        $product = Product::find($ad->product_id);

        if ($product) {
            // Convert images to an array for the product
            $product->images = $product->getImages();
           
            // If the variants are stored as a JSON string, decode it
            $product->variants = json_decode($product->variants, true);

            return view('ads.edit', get_defined_vars());
        }
    }

    public function updateAd(Request $request, $adId)
    {     // Validate the incoming request data
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'drive_links' => 'nullable|string',
            'ig_links' => 'nullable|string',
            'content' => 'nullable|string',
            'collections' => 'required|array',
            'collections.*' => 'exists:collections,id',  // Ensure the collection IDs exist
        ]);
        
        // Use a database transaction
        DB::beginTransaction();
    
        try {
            // Create the ad without mass assignment
            $ad = Ad::findOrFail($adId);
            $ad->product_id = $validatedData['product_id'];
            $ad->drive_links = $validatedData['drive_links'];
            $ad->ig_links = $validatedData['ig_links'];
            $ad->content = $validatedData['content'];
            $ad->save(); // Save the Ad instance
    
            // Prepare collection data for attachment
            $collectionData = [];
            foreach ($validatedData['collections'] as $collection_id) {
                $collectionData[] = [
                    'collection_id' => $collection_id,
                    'status' => 'Not Started',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $ad->collections()->detach();
    
            // Use the attach method to insert multiple records
            foreach ($collectionData as $data) {
                $ad->collections()->attach($data['collection_id'], [
                    'status' => $data['status'],
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['updated_at'],
                ]);
            }
            
            // Commit the transaction
            DB::commit();
            
            // Return success response
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            
            // Return error response
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    

    public function collection(){

        $collections = Collection::all();
    
        return view('ads.collection', compact('collections'));
    }

    public function showCollection($id){
        $allCollectionDetails = AdCollection::with(['ad', 'collection'])->where('collection_id',$id)->get();
        $collection = Collection::find($id); 
        return view('ads.show-collection' , compact('allCollectionDetails','collection'));
    }
    

    // Show create ad form
    public function create(){
        // Load collections to be used in the form
        $collections = Collection::all();
        return view('ads.create', compact('collections'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'drive_links' => 'nullable|string',
            'ig_links' => 'nullable|string',
            'content' => 'nullable|string',
            'collections' => 'required|array',
            'collections.*' => 'exists:collections,id',  // Ensure the collection IDs exist
        ]);
        
        // Use a database transaction
        DB::beginTransaction();
    
        try {
            // Create the ad without mass assignment
            $ad = new Ad();
            $ad->product_id = $validatedData['product_id'];
            $ad->drive_links = $validatedData['drive_links'];
            $ad->ig_links = $validatedData['ig_links'];
            $ad->content = $validatedData['content'];
            $ad->save(); // Save the Ad instance
    
            // Prepare collection data for attachment
            $collectionData = [];
            foreach ($validatedData['collections'] as $collection_id) {
                $collectionData[] = [
                    'collection_id' => $collection_id,
                    'status' => 'Not Started',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
    
            // Use the attach method to insert multiple records
            foreach ($collectionData as $data) {
                $ad->collections()->attach($data['collection_id'], [
                    'status' => $data['status'],
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['updated_at'],
                ]);
            }
            
            // Commit the transaction
            DB::commit();
            
            // Return success response
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            
            // Return error response
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
    public function storeCollection(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $collection = new Collection();
        $collection->name = $request->name;
        $collection->save();

        return redirect()->back();
    }

    public function editCollection($id)
    {
        $collection = Collection::findOrFail($id);
        return response()->json($collection);
    }
    

public function updateCollection(Request $request, $id)
{
    $request->validate([
        'collection_name_edit' => 'required|string|max:255',
    ]);

    $collection = Collection::findOrFail($id);
    $collection->name = $request->collection_name_edit;
    $collection->save();

    return redirect()->back()->with('success', 'Collection updated successfully.');
}

public function destroyCollection($id){
    $collection = Collection::find($id);
    $collection->delete();
    return redirect()->back();
}

public function start(Request $request, $adId, $collectionId)
{
    // Find the ad collection based on the provided ad ID and collection ID
    $adCollection = AdCollection::where('ad_id', $adId)
                                ->where('collection_id', $collectionId)
                                ->first();

    // Check if the ad collection exists
    if (!$adCollection) {
        return redirect()->back()->with('error', 'Ad collection not found.');
    }

    // Update the status
    $adCollection->status = 'Activated';
    $adCollection->updated_at = now(); // Update the timestamp
    $adCollection->activated_at = now(); // Update the timestamp
    $adCollection->save();

    return redirect()->back()->with('success', 'Ad activated successfully.');
}

public function stop(Request $request, $adId, $collectionId)
{
    // Find the ad collection based on the provided ad ID and collection ID
    $adCollection = AdCollection::where('ad_id', $adId)
                                ->where('collection_id', $collectionId)
                                ->first();

    // Check if the ad collection exists
    if (!$adCollection) {
        return redirect()->back()->with('error', 'Ad collection not found.');
    }

    // Update the status
    $adCollection->status = 'Done';
    $adCollection->updated_at = now(); // Update the timestamp
    $adCollection->deactivated_at = now(); // Update the timestamp
    $adCollection->save();

    return redirect()->back()->with('success', 'Ad Done successfully.');
}

public function askToClose(Request $request, $adId, $collectionId)
{
    // Find the ad collection based on the provided ad ID and collection ID
    $adCollection = AdCollection::where('ad_id', $adId)
                                ->where('collection_id', $collectionId)
                                ->first();

    // Check if the ad collection exists
    if (!$adCollection) {
        return redirect()->back()->with('error', 'Ad collection not found.');
    }

    // Update the status
    $adCollection->stock_status = 'Out Of Stock';
    $adCollection->save();

    return redirect()->back()->with('success', 'Request sent successfully.');
}

public function withdraw(Request $request, $adId, $collectionId)
{
    // Find the ad collection based on the provided ad ID and collection ID
    $adCollection = AdCollection::where('ad_id', $adId)
                                ->where('collection_id', $collectionId)
                                ->first();

    // Check if the ad collection exists
    if (!$adCollection) {
        return redirect()->back()->with('error', 'Ad collection not found.');
    }

    // Update the status
    $adCollection->status = 'Not Started';
    $adCollection->stock_status = 'Stocked';

    $adCollection->save();

    return redirect()->back()->with('success', 'Request Republished successfully.');
}

public function cancelClose(Request $request, $adId, $collectionId)
{
    // Find the ad collection based on the provided ad ID and collection ID
    $adCollection = AdCollection::where('ad_id', $adId)
                                ->where('collection_id', $collectionId)
                                ->first();

    // Check if the ad collection exists
    if (!$adCollection) {
        return redirect()->back()->with('error', 'Ad collection not found.');
    }

    // Update the status
    $adCollection->stock_status = 'Stocked';
    $adCollection->save();

    return redirect()->back()->with('success', 'Request Cancelled successfully.');
}



public function destroy(Request $request, $adId, $collectionId)
{
    // Find the ad collection based on the provided ad ID and collection ID
    $adCollection = AdCollection::where('ad_id', $adId)
                                ->where('collection_id', $collectionId)
                                ->first();

    // Check if the ad collection exists
    if (!$adCollection) {
        return redirect()->back()->with('error', 'Ad collection not found.');
    }


    $adCollection->delete();

    return redirect()->back()->with('success', 'Ad Deleted successfully.');
}

public function searchProductByTitle(Request $request)
{
    $searchTerm = $request->query('query');

    // Fetch products where the title matches the search term
    $products = Product::where('title', 'LIKE', '%' . $searchTerm . '%')->get();

    // Return only necessary data for the frontend
    return response()->json($products->map(function ($product) {
        // Fetch the variant count for this product
        $variantCount = DB::table('product_variants')
                          ->where('product_id', $product->id)
                          ->count();

        return [
            'id' => $product->id,
            'title' => $product->title,
            'images' => $product->getImages(),  // Get images from the JSON column
            'variant_count' => $variantCount,   // Variant count for the product
            'variants' => $product->variants ? json_decode($product->variants, true) : [],
        ];
    }));
}

public function getProductVariantCount(Request $request)
{
    $productId = $request->query('product_id');
    
    // Fetch the count of variants for the product
    $variantCount = DB::table('product_variants')
                      ->where('product_id', $productId)
                      ->count();
    
    return response()->json($variantCount);
}



public function getProductDetailsById($id)
{
    $product = Product::find($id);

    if ($product) {
        // Convert images to an array for the product
        $product->images = $product->getImages();

        // If the variants are stored as a JSON string, decode it
        $product->variants = json_decode($product->variants, true);

        return response()->json($product); // Return product details
    }

    return response()->json(['message' => 'Product not found'], 404);
}

        

    

    // Fetch collections via AJAX
    public function getCollections()
    {
        $collections = Collection::all();
        return response()->json(['collections' => $collections]);
    }


    // Store collection (for adding new collections via AJAX)
    public function store_collection(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create the collection without mass assignment
        $collection = new Collection();
        $collection->name = $validatedData['name'];
        $collection->save();

        return response()->json(['success' => true, 'collection' => $collection]);
    }


    public function variant($id){
        $variants = ProductVariant::where('product_id',$id)->get();
        $product = Product::find($id);
        $productName = $product->title;
        return view('ads.variant', compact('variants','productName','id'));
    }

}
