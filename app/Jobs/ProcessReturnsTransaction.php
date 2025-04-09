<?php
namespace App\Jobs;

use App\Models\ReturnCollectionDetail;
use App\Models\Order as order2;
use App\Models\ReturnCollection;
use App\Models\ReturnedOrder;
use App\Models\TicketUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessReturnsTransaction implements ShouldQueue
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
                return is_array($row) && count($row) == 9 && isset($row[0]) && $row[0];
            });
            // Count total filtered rows
            $total_orders = $this->order_count;

            $trx = new ReturnCollection();
            $trx->shipment_date = $this->shipment_date;
            $trx->reference = "ret" . $this->shipment_date; 
            $trx->note = $this->note ?? null;
            $trx->sheet = $publicPath;
            $trx->user_id = $this->userId;
            $trx->created_at = now();
            $trx->updated_at = now();
            $trx->total_orders = $total_orders;
            $trx->save();
            foreach ($rows as $key => $row) {
                if(!is_array($row))
                    $row = array_values($row->toArray());
                else
                    $row = array_values($row);
                
                if (count($row) < 9) {
                    continue;
                }

                if (!isset($row[0]) || $row[0] == null || $row[0] == "") {
                    continue;
                }

                $row_id = str_replace(['Lvs', 'lvs'], '', $row[0]);
                $order = order2::where('order_number', $row_id)->first();
                
                $detail = new ReturnCollectionDetail();
                $detail->collection_id = $trx->id;
                $detail->order_id = $row[0];
                $detail->cod = (int)$row[8];
                $detail->created_at = now();
                $detail->updated_at = now();

                if (!$order) {
                    $row_id = str_replace(['RTLvs','RTLvr'], '', $row[0]);
                    $order = order2::where('order_number', $row_id)->first();
                    if($order)
                    {
                        $detail->status = "partial";
                    }
                    else{
                        $detail->status = "failed";
                        $detail->reason = "Order not found";
                    }
                } else {
                    $ticket = TicketUser::where('order_number', $row_id)->first();
                    $return = ReturnedOrder::where('order_number', $row_id)->first();
                    if($ticket && $return && $return->return_id)
                    {
                        $detail->status = "full";
                    }
                    else
                    {
                        $detail->status = "failed";
                        $detail->reason = "Return or Ticket not Found on System";
                    }
                    
                    
                }

                $detail->save();
            }

            $trx->partial_returns = $trx->details->where('status', 'partial')->count();
            $trx->full_returns = $trx->details->where('status', 'full')->count();
            $trx->save();
    } catch (\Exception $e) {
        dd($e);
        \Log::info('Error in Processing Return Collection: ' . $e->getMessage());
    }
}

}
