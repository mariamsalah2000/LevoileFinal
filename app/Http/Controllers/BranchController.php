<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ProductVariant;
use App\Models\StockRequest;
use App\Models\StockRequestDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BranchController extends Controller {

    public function __construct() {
        //$this->middleware('guest:devops')->except('logout');
    }

    public function createStockRequest()
    {
        Session::forget('pos.cart');
        Session::forget('branch');
        
        $user = auth()->user();
        if($user->branch_id == null || $user->branch == null)
            return redirect()->back()->with('error','No Branch Selected');
        $branch = $user->branch;
        Session::put('branch',$branch->name);
        return view('stock_requests.create',compact('branch'));

    }

    public function stockRequests(Request $request)
    {
        $search = $request->search;
        $daterange = $request->daterange;
        $status = $request->status;

        if($daterange)
        {
            $date = explode(' - ', $daterange);
            $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $date[0])->format('Y-m-d');
            $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $date[1])->format('Y-m-d');
        }
        $user = auth()->user();
        $branch = auth()->user()->branch;

        if($user->role->name != "Admin" && $branch == null)
            return redirect()->back()->with('error',"You're not Permitted To View This Page");

        $stockRequests = StockRequest::when($branch,fn($q)=> $q->where('branch_id',$branch->id))
                                       ->when($search,fn($q)=> $q->where('ref',$search))
                                       ->when($daterange,fn($q)=> $q->whereBetween('created_at',[$startDate,$endDate]))
                                       ->when($status,fn($q)=> $q->where('status',$status))
                                       ->orderBy('created_at','desc')->paginate(20);
        return view('stock_requests.index',get_defined_vars());

    }

    public function storeStockRequest(Request $request)
    {
        $branch_id = $request->branch_id;
        $branch = Branch::find($branch_id);
        $items = $request->session()->get('pos.cart');
        $total_products = $items->count();
        $total_qty = $items->sum('quantity');
        if(!$branch)
        {
            Session::forget('branch');
            return redirect()->back()->with('error','No Branch Selected');
        }
        $stockRequest = new StockRequest();
        $stockRequest->ref =  $branch->name . date('ymd').date('his');
        $stockRequest->branch_id = $branch->id;
        $stockRequest->total_products = $total_products;
        $stockRequest->total_quantity = $total_qty;
        $stockRequest->created_at = now();
        $stockRequest->updated_at = now();
        $stockRequest->note = $request->note;
        $stockRequest->user_id = auth()->id();
        $stockRequest->save();

        foreach($items as $key=>$item) {
            $product = ProductVariant::where('sku',$item['stock_id'])->first();
            $detail = new StockRequestDetail();
            $detail->stock_request_id = $stockRequest->id;
            $detail->sku = $product->sku;
            $detail->product_name = $product->product->title;
            $detail->needed_qty = $item['quantity'];
            $detail->img = $item['image'];
            $detail->created_at = now();
            $detail->updated_at = now();
            $detail->save();
        }
        return redirect()->route('stock_requests.index')->with('success','Request Created Successfully');

    }

    public function showStockRequest($id)
    {
        $stockRequest = StockRequest::find($id);
        if($stockRequest)
        {
            $details = $stockRequest->details;
            return view('stock_requests.show', ['stockRequest' => $stockRequest, 'details' => $details]);
        }

        return redirect()->back()->with('error', 'Stock Request Not Found');
    }

    public function editStockRequest($id)
    {

    }

    public function downloadStockRequest($id)
    {
        $stockRequest = StockRequest::find($id);
        if($stockRequest)
        {
            $stockRequest->status = "Downloaded";
            $stockRequest->downloaded_at = now();
            $stockRequest->save();
            return response()->json(['status'=>'success','message'=>'']);
        }
        return response()->json(['status'=>'error','message'=>'Stock Request Not Found']);

    }
}
