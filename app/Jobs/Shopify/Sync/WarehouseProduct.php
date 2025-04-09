<?php

namespace App\Jobs\Shopify\Sync;

use App\Models\WarehouseProduct as ModelsProduct;
use App\Models\ProductVariant as ModelsProductVariant;
use App\Traits\FunctionTrait;
use App\Traits\RequestTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WarehouseProduct implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use FunctionTrait, RequestTrait;
    public $user, $store,$warehouse;
    /**
     * Create a new job instance.
     * @return void
     */
    public function __construct($user, $store,$warehouse) {
        $this->user = $user;
        $this->store = $store;
        $this->warehouse = $warehouse;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle() {
        try {
            $since_id = 0;
            $headers = getShopifyHeadersForStore($this->store);
            $products = [];
            do {
                $endpoint = getShopifyURLForStore('locations/'.$this->user->warehouse_id.'/inventory_levels.json?since_id='.$since_id, $this->store);
                $response = $this->makeAnAPICallToShopify('GET', $endpoint, null, $headers);
                dd($response);
                $products = $response['statusCode'] == 200 ? $response['body']['inventory_levels'] ?? null : null;
                foreach($products as $product) {
                    $prod = ModelsProductVariant::where('inventory_item_id', $product['inventory_item_id'])->first();
                    if($prod)
                    {
                        $this->updateOrCreateThisVariantInDB($prod,$product['available']);
                    }
                }
            } while($products !== null && count($products) > 0);
        } catch(Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function updateOrCreateThisVariantInDB($variant,$qty)
    {
        try {
            $payload = [
                'store_id' => $this->store->table_id,
                'warehouse_id' => $this->user->warehouse_id,
                'id' => $variant['id'],
                'product_id' => $variant['product_id'],
                'title' => $variant['title'],
                'price' => $variant['price'],
                'sku' => $variant['sku'],
                'option1' => $variant['option1'],
                'option2' => $variant['option2'],
                'option3' => $variant['option3'],
                'created_at' => $variant['created_at'],
                'updated_at' => $variant['updated_at'],
                'barcode' => $variant['barcode'],
                'admin_graphql_api_id' => $variant['admin_graphql_api_id'],
                'inventory_item_id' => $variant['inventory_item_id'],
                'inventory_quantity' => $qty,
                'image_id' => $variant['image_id']
            ];
            $update_arr = [
                'store_id' => $this->store->table_id,
                'id' => $variant['id'],
                'warehouse_id' => $this->user->warehouse_id,
            ];
            \App\Models\WarehouseProduct::updateOrCreate($update_arr, $payload);
            return true;
        } catch (Exception $e) {
            dd($e);
            Log::info($e->getMessage());
        }
    }
}
