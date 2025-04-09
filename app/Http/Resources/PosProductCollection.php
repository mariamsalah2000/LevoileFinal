<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PosProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $prod = Product::find($data->product_id);
                $image = asset("assets/img/logo.png");
                if(isset($data->image_id))
                {
                                
                    if($prod) {
                        $images = collect(json_decode($prod->images));

                        $product_img2 = $images->where('id', $data->image_id)->first();
                        if ($product_img2 && $product_img2->src != null && $product_img2->src != '') {
                            $image = $product_img2->src;
                        }
                    }
                }
                return [
                    'id' => $data->id,
                    'stock_id' => $data->sku,
                    'name' => $data->product->title,
                    'thumbnail_image' => $image,
                    'price' => $data->price,
                    'base_price' => $data->price,
                    'qty' => $data->inventory_quantity,
                    'variant' => $data->variant,
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
