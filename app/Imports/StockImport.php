<?php

namespace App\Imports;

use Auth;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Traits\RequestTrait;
use App\Models\BranchVariant;
use App\Models\ProductVariant;
use App\Models\BranchStockTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\WithChunkReading;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class StockImport implements ToCollection, WithHeadingRow, WithValidation, ToModel, WithChunkReading
{
    use RequestTrait;
    private $rows = 0;
    public $trx_id = null;
    public $message = null;
    public $i = 0;

    public function collection(Collection $rows)
    {
        $this->i += 1;
        // ini_set('memory_limit', '512M');
        // ini_set('memory_limit', '-1');
        $canImport = true;
        $user = Auth::user();
        $branches = Branch::all();
        $branch_stock_trx = new BranchStockTransaction();
        $branch_stock_trx -> created_at = now();
        $branch_stock_trx -> updated_at = now();
        $failed_products = 0;
        $success_products = 0;
        $success_qty = 0;
        //$insert_data = [];
        $update_data = [];
        $index = 1;
        try{
            // session()->put('message', "");
            // Cache::put('message', "");
            $rows->chunk(100)->each(function ($chunkedRows) use ($rows, &$index, $branches, &$update_data, &$failed_products, &$success_products, &$success_qty) {
                $insert_data = [];
                foreach ($chunkedRows as $key => $row) {
                    if (isset($row['no'])) {
                        $sku = $row['no'];
                        $variant = ProductVariant::where('sku', $sku)->first();

                        if ($variant) {
                            foreach ($branches as $branch) {
                                if (isset($row[$branch->name])) {
                                    $branch_variant = BranchVariant::where([
                                        'branch_id' => $branch->id,
                                        'variant_id' => $variant->id
                                    ])->first();
                                    if (!$branch_variant)
                                    {
                                        $insert_data[] = [
                                            'branch_id' => $branch->id,
                                            'variant_id' => $variant->id,
                                            'qty' =>  $row[$branch->name], 
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ];
                                    }else
                                    {
                                        $update_data[] = [
                                            'branch_id' => $branch->id,
                                            'variant_id' => $variant->id,
                                            'qty' =>  $row[$branch->name],
                                            'updated_at' => now(),
                                        ];
                                    }
                                        
                                    $success_qty += $row[$branch->name];
                                }
                            }
                            $success_products++;
                        } else {
                            $failed_products++;
                        }
                    }
                }
                BranchVariant::insert($insert_data);
                $num = round((100 * $index) / ($rows->count()) * 100);
                if($num < 100)
                {
                    $message = "Loading.. (" . $num ."% Uploaded!) Batch :".$this->i;
                    $index +=1;
                    Cache::put('message', $message);
                }
                
                //dump($message);
                sleep(1);
                
            });
            
                    // Bulk update
            foreach (array_chunk($update_data, 100) as $chunk) {
                foreach ($chunk as $update) {
                    BranchVariant::updateOrInsert(
                        ['branch_id' => $update['branch_id'], 'variant_id' => $update['variant_id']],
                        ['qty' => $update['qty'], 'updated_at' => $update['updated_at']]
                    );
                }
            }
            // BranchVariant::updateOrInsert($update_data);
        }
        catch(\Exception $e)
        {
            dd($e);
        }
        $branch_stock_trx->success_products = $success_products;
        $branch_stock_trx->success_qty = $success_qty;
        $branch_stock_trx->failed_products = $failed_products;
        $branch_stock_trx->ref = "trx-".rand(1,1000000);
        $branch_stock_trx->created_by = auth()->user()->id;
        $branch_stock_trx->save();
        
        $message = "Loading.. (100% Uploaded!)";
        Cache::put('message', $message);

        $this->trx_id = $branch_stock_trx->id;
        return $this->trx_id;
    }

    public function chunkSize(): int
    {
        return 1000; // Process 1000 rows at a time
    }

    public function model(array $row)
    {
        ++$this->rows;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function rules(): array
    {
        return [
            // Can also use callback validation rules
            'unit_price' => function ($attribute, $value, $onFailure) {
                if (!is_numeric($value)) {
                    $onFailure('Unit price is not numeric');
                }
            }
        ];
    }

    public function downloadThumbnail($url)
    {
        try {
            $upload = new Upload;
            $upload->external_link = $url;
            $upload->type = 'image';
            $upload->save();

            return $upload->id;
        } catch (\Exception $e) {
        }
        return null;
    }

    public function downloadGalleryImages($urls)
    {
        $data = array();
        foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
            $data[] = $this->downloadThumbnail($url);
        }
        return implode(',', $data);
    }
}