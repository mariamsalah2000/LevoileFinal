<?php
namespace App\Jobs;

use App\Models\Prepare;
use App\Models\User;
use App\Models\Product;
use App\Models\ReturnedOrder;
use App\Models\Order as order2;
use App\Models\PrepareProductList;
use App\Models\ShippingTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ShippingTransactionDetail;
use App\Imports\ShippingTransactionImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessShippingTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $file;
    public $rows;
    public $userId;
    public $shipment_date;
    public $note;
    public $order_count;

    /**
     * Create a new job instance.
     */
    public function __construct($file, $order_count,array $rows, $userId, $shipment_date, $note = null)
    {
        $this->file = $file;
        $this->rows = $rows;
        $this->userId = $userId;
        $this->shipment_date = $shipment_date;
        $this->note = $note;
        $this->order_count = $order_count;
    }

    /**
     * Execute the job.
     */
public function handle()
{
    try {
            $rows = $this->rows;
            $publicPath = $this->file;
            // Filter rows that meet the criteria
            $filteredRows = array_filter($rows, function ($row) {
                return is_array($row) && count($row) == 10 && isset($row[0]) && $row[0];
            });

            // Count total filtered rows
            $total_orders = $this->order_count;

            // // Calculate total COD
            // $total_cod = array_reduce($rows, function ($carry, $row) {
            //     $row = array_values($row); // Ensure $row is an indexed array
            //     return $carry + (isset($row[7]) ? (int)$row[7] : 0);
            // }, 0);

            // // Calculate total shipping
            // $total_shipping = array_reduce($rows, function ($carry, $row) {
            //     $row = array_values($row); // Ensure $row is an indexed array
            //     return $carry + (isset($row[8]) ? (int)$row[8] : 0);
            // }, 0);

            // // Calculate total net
            // $total_net = array_reduce($rows, function ($carry, $row) {
            //     $row = array_values($row); // Ensure $row is an indexed array
            //     return $carry + (isset($row[9]) ? (int)$row[9] : 0);
            // }, 0);

            $total_cod = $rows->sum(function ($row) {
                $row = array_values($row->toArray());
                return isset($row[7]) ? (int)$row[7] : 0;
            });
            $total_shipping = $rows->sum(function ($row) {
                $row = array_values($row->toArray());
                return isset($row[8]) ? (int)$row[8] : 0;
            });
            $total_net = $rows->sum(function ($row) {
                $row = array_values($row->toArray());
                return isset($row[9]) ? (int)$row[9] : 0;
            });


            // $total_net = $total_cod - $total_shipping;

            $trx = new ShippingTransaction();
            $trx->shipment_date = $this->shipment_date;
            $trx->transaction_number = "cod" . $this->shipment_date;
            $trx->total_orders = $total_orders;
            $trx->total_cod = $total_cod;
            $trx->total_shipping = $total_shipping;
            $trx->total_net = $total_net;
            $trx->note = $this->note ?? null;
            $trx->sheet = $publicPath;
            $trx->user_id = $this->userId;
            $trx->created_at = now();
            $trx->updated_at = now();
            $trx->save();
            foreach ($rows as $key => $row) {
                if(!is_array($row))
                    $row = array_values($row->toArray());
                else
                    $row = array_values($row);
                
                if (count($row) < 10) {
                    continue;
                }

                if (!isset($row[0]) || $row[0] == null || $row[0] == "") {
                    continue;
                }

                $row_id = str_replace(['Lvs', 'lvs'], '', $row[0]);
                $order = order2::where('order_number', $row_id)->first();

                

                if (!$order) {
                    $detail = new ShippingTransactionDetail();
                    $detail->transaction_id = $trx->id;
                    $detail->order_id = $row[0];
                    $detail->cod = (int)$row[7];
                    $detail->order_price = (int)$row[7];
                    $detail->shipping = (int)$row[8];
                    $detail->net = (int)$row[9];
                    $detail->order_shipping = (int)$row[8];
                    $detail->status = "failed";
                    $detail->reason = "Order Not Found";
                    $detail->created_at = now();
                    $detail->updated_at = now();
                    $detail->save();
                } else {
                    $prepare = Prepare::where('order_id', $order->id)->first();
                    if (!$prepare) {
                        $prepare = $this->createPrepare($order);
                    }
                    $shipping = (int)$row[8];
                    if(isset($order->shipping_lines) && is_array($order->shipping_lines) && isset($order->shipping_lines[0]) && isset($order->shipping_lines[0]['price']) )
                    {
                        $shipping = $order->shipping_lines[0]['price'];
                    }
                    $detail = new ShippingTransactionDetail();
                    $detail->transaction_id = $trx->id;
                    $detail->order_id = $row[0];
                    $detail->cod = (int)$row[7];
                    $detail->order_price = (int)$order->total_price;
                    $detail->shipping = (int)$row[8];
                    $detail->order_shipping = (int)$shipping;
                    $detail->net = (int)$row[9];
                    $detail->created_at = now();
                    $detail->updated_at = now();
                    
                    if ($order->fulfillment_status == "delivered") {
                        $detail->status = "failed";
                        $detail->reason = "Order Already Delivered";
                        $detail->save();
                    } elseif (in_array($order->fulfillment_status, ["prepared", "fulfilled", "distributed", "processing"])) {
                        $detail->status = "failed";
                        $detail->reason = "Order Not Shipped Yet";
                        $detail->save();
                    } else {
                        $detail->reason = $row[5];

                        if ((int)$row[7] == 0) {
                            $paid_by_visa = 0;
                            if (is_array($order->payment_gateway_names) && isset($order->payment_gateway_names[0]) &&
                                ($order->payment_gateway_names[0] == "fawrypay (pay by card or at any fawry location)" || $order->payment_gateway_names[0] == "Paymob")) {
                                $detail->status = "success";
                                $detail->order_status = "delivered";
                                $detail->reason = "Paid By Visa";
                                $paid_by_visa = 1;
                            } 
                                
                            $return = ReturnedOrder::where('order_number', $order->order_number)
                                ->where('status', 'Returned')
                                ->with(['details' => function ($query) {
                                    $query->selectRaw('return_id, SUM(amount * qty) as total_price')
                                        ->groupBy('return_id');
                                }])->first();

                            $returns = $return?->details->sum('total_price');

                            $order_total = $prepare->products->sum(function ($product) {
                                return $product->price * $product->order_qty;
                            });
                            
                            if ($returns && ($returns == $order_total)) {
                                if($paid_by_visa == 1)
                                $detail->reason = "Paid By Visa and Returned";
                                else
                                $detail->reason = "Returned";

                                $detail->status = "success";
                                $detail->order_status = "returned";
                            } else{
                                if($paid_by_visa == 0) {
                                    $detail->status = "failed";
                                    $detail->reason = "Zero COD";
                                }
                            }
                            
                        } else if ((int)$row[7] == (int)$order->total_price) {
                            $detail->status = "success";
                            $detail->reason = "Delivered";
                            $detail->order_status = "delivered";
                        } else {
                            $return = ReturnedOrder::where('order_number', $order->order_number)
                                ->where('status', 'Returned')
                                ->with(['details' => function ($query) {
                                    $query->selectRaw('return_id, SUM(amount * qty) as total_price')
                                        ->groupBy('return_id');
                                }])->first();

                            $returns = $return?->details->sum('total_price');

                            if ($returns && ($returns == ((int)$order->total_price - (int)$row[7]))) {
                                $detail->status = "success";
                                $detail->reason = "Returned";
                                $detail->order_status = "returned";
                            } else {
                                $detail->status = "failed";
                                $detail->reason = "Invalid COD";
                            }
                        }
                    }
                }

                $detail->save();
            }

            $trx->success_orders = $trx->details->where('status', 'success')->count();
            $trx->failed_orders = $trx->details->where('status', 'failed')->count();
            $trx->save();
    } catch (\Exception $e) {
        \Log::info('Error in Processing Shipping Transaction: ' . $e->getMessage());
    }
}

    public function createPrepare($order,$prepare_emp=1)
    {
        $user = User::where('id',$this->userId)->first();
        $store = $user->getShopifyStore;

        $add_to_prepare = Prepare::where('order_id', $order->id)->first();
        if(!$add_to_prepare)
        $add_to_prepare = new Prepare();

        $add_to_prepare->order_id  = $order->id;
        $add_to_prepare->store_id  = $order->store_id;
        $add_to_prepare->table_id  = $order->table_id;
        $add_to_prepare->assign_by  = $this->userId;
        $add_to_prepare->assign_to  = $prepare_emp;
        $add_to_prepare->status  = "3";
        $add_to_prepare->delivery_status  = $order->fulfillment_status;
        $add_to_prepare->sale_created_at  = $order->created_at_date;
        $add_to_prepare->created_at  = now();
        $add_to_prepare->updated_at  = now();
        $add_to_prepare->save();
        $prepare_product = PrepareProductList::where('order_id',$order->id)->delete();
        $product_images = $store->getProductImagesForOrder($order);
    
        foreach($order->line_items as $item)
        {
            $product_img = "";
            $prepare_product = new PrepareProductList();
            if(isset($item['product_id']) && $item['product_id'] != null)
            {
                if(isset($product_images[$item['product_id']]))
                {
                    $product_imgs = is_array($product_images[$item['product_id']]) ? $product_images[$item['product_id']] : json_decode(str_replace('\\','/',$product_images[$item['product_id']]),true);
                    if ($product_imgs && !is_array($product_imgs))
                        $product_imgs = $product_imgs->toArray();

                    $product_img = is_array($product_imgs) && isset($product_imgs[0]) && isset($product_imgs[0]['src']) ? $product_imgs[0]['src'] : null;
                }
            
                $product = Product::find($item['product_id']);
            }
            else
            {
                $product = Product::where('variants','like','%'.$item['sku'].'%')->first();
            }
            if($product) {

                $variants = collect(json_decode($product->variants));
                $variant = $variants->where('id',$item['variant_id'])->first();
                $images = collect(json_decode($product->images));
                if(!$variant)
                {
                    $variant = $variants->where('sku',$item['sku'])->first();
                }

                if($variant)
                {
                    $product_img2 = $images->where('id', $variant->image_id)->first();
                    if ($product_img2 && $product_img2->src != null && $product_img2->src != '')
                        $product_img = $product_img2->src;
                }
            }
            



            $prepare_product->order_id = $order->id;
            $prepare_product->table_id = $order->table_id;
            $prepare_product->store_id = $order->store_id;
            $prepare_product->prepare_id = $add_to_prepare->id;
            $prepare_product->user_id = $this->userId;
            $prepare_product->product_id = $item['id'];
            $prepare_product->product_sku = $item['sku'];
            $prepare_product->variation_id = $item['variant_title'];
            $prepare_product->variant_image = $product_img;
            $prepare_product->order_qty = $item['quantity'];
            $prepare_product->product_status= $order->fulfillment_status == "hold"? "hold" : ($order->fulfillment_status == "distributed" ? "unfulfilled" : "prepared") ;
            $prepare_product->prepared_qty = 0;
            $prepare_product->needed_qty = $item['quantity'];
            $prepare_product->product_name = $item['title'];
            $prepare_product->price = $item['price'];
            $prepare_product->created_at = now();
            $prepare_product->updated_at = now();
            $prepare_product->save();

        }

        return $add_to_prepare;
    }


}
