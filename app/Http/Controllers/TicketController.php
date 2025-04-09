<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Prepare;
use App\Models\TicketUser;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\ReturnedOrder;
use App\Models\TicketHistory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Traits\RequestTrait;
use Illuminate\Support\Facades\Auth;
use App\Exports\TicketsExport;


class TicketController extends Controller
{
    use RequestTrait;
    // Display all tickets
    public function index(Request $request)
    {
        // Base query with relations
        $query = TicketUser::with(['ticket', 'user']);
        
        // Apply filters for the tickets
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('order_number')) {
            $query->where('order_number', $request->order_number);
        }

        if ($request->filled('reason')) {
            $query->where('content', $request->reason);
        }
        

        if ($request->filled('ticket_type')) {
            $query->whereHas('ticket', function ($q) use ($request) {
                $q->where('type', $request->ticket_type);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->input('state') === 'failed') {
            $query->where('is_asked_to_return', 0)->where('is_returned',1)->where('status','done');
        }
        if ($request->input('state') === 'pending') {
            $query->where('is_asked_to_return', 1)->where('is_returned',0)->where('status','in progress');
        }
        if ($request->input('state') === 'success') {
            $query->where('is_asked_to_return', 0)->where('is_returned',0)->where('status','done');
        }


        if ($request->input('response') === 'lev') {

            $query->where(function ($q) {
                $q->whereIn('status', ['in progress','open'])->where('is_hold',0);
            })->whereHas('comments', function($q) {
                $q->whereHas('user', function($q2) {
                    $q2->where('role_id', 8);  // Check the user's role_id
                })
                ->orderBy('created_at', 'desc') // Order comments by latest
                ->limit(1);  // Ensure only the latest comment is checked
            });
        }
        
        // Filtering tickets by latest comment role_id == 7
        if ($request->input('response') === 'best') {
            $query->where(function ($q) {
                $q->whereIn('status', ['in progress','open'])
                  ->where('is_hold',0);
            })->whereHas('comments', function($q) {
                $q->whereHas('user', function($q2) {
                    $q2->where('role_id', 7)->orWhere('role_id', 6);  // Check the user's role_id
                })
                ->orderBy('created_at', 'desc') // Order comments by latest
                ->limit(1);  // Ensure only the latest comment is checked
            });
        }

        // Filtering tickets by latest comment role_id == 7
        if ($request->input('response') === 'hold') {
            $query->where(function ($q) {
                $q->whereIn('status', ['in progress','open'])
                  ->where('is_hold',1);
            })->whereHas('comments', function($q) {
                $q->whereHas('user', function($q2) {
                    $q2->where('role_id', 7)->orWhere('role_id', 6);  // Check the user's role_id
                })
                ->orderBy('created_at', 'desc') // Order comments by latest
                ->limit(1);  // Ensure only the latest comment is checked
            });
        }
        

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Get filtered tickets
        $tickets = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());

        // Count of total tickets
        $totalTickets = $query->count();

        // Clone query to reset for each status count
        $openCount = (clone $query)->where('status', 'open')->count();
        $inProgressCount = (clone $query)->where('status', 'in progress')->count();
        $doneCount = (clone $query)->where('status', 'done')->count();

        // Clone query to reset for each ticket type count
        $requestCount = (clone $query)->whereHas('ticket', function ($q) {
            $q->where('type', 'request');
        })->count();

        $complaintCount = (clone $query)->whereHas('ticket', function ($q) {
            $q->where('type', 'complaint');
        })->count();

        $users = User::whereIn('role_id', [6, 7, 8])->get();

        // Pass all data to the view
        return view('tickets.index', compact(
            'tickets', 
            'totalTickets', 
            'openCount', 
            'users',
            'inProgressCount', 
            'doneCount', 
            'requestCount', 
            'complaintCount'
        ));
    }


    public function exportData(Request $request)
{
    // Query with required relationships
    $query = TicketUser::with(['ticket', 'user', 'comments.user']); // Ensure comments and user relationships are loaded

    // Apply filters based on request inputs
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }
    if ($request->filled('order_number')) {
        $query->where('order_number', $request->order_number);
    }
    if ($request->filled('reason')) {
        $query->where('content', $request->reason);
    }
    if ($request->filled('ticket_type')) {
        $query->whereHas('ticket', function ($q) use ($request) {
            $q->where('type', $request->ticket_type);
        });
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('start_date')) {
        $query->where('created_at', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->where('created_at', '<=', $request->end_date);
    }
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    // Fetch all tickets and map the data to include necessary fields
    $data = $query->get()->map(function ($ticketUser) {
        // Get the latest comment for this ticket user
        $latestComment = $ticketUser->comments->last(); // Retrieves the most recent comment
        $commentUser = $latestComment ? $latestComment->user : null;

        // Determine the "Pending Response" status
        $pendingResponse = '--';
        if ($commentUser) {
            if (($commentUser->role_id == 7 || $commentUser->role_id == 6) && $ticketUser->is_hold == 0 && 
                (ucfirst($ticketUser->status) === 'In progress' || ucfirst($ticketUser->status) === 'Open')) {
                $pendingResponse = 'Pending Shipping';
            } elseif ($commentUser->role_id == 8 && $ticketUser->is_hold == 0 && 
                (ucfirst($ticketUser->status) === 'In progress' || ucfirst($ticketUser->status) === 'Open')) {
                $pendingResponse = 'Pending Levoile';
            } elseif (ucfirst($ticketUser->status) === 'In progress' && $ticketUser->is_hold == 1) {
                $pendingResponse = 'Holding Ticket';
            }
        }

        return [
            'created_at' => $ticketUser->created_at ? $ticketUser->created_at->format('d M Y, H:i') : 'N/A',
            'order_number' => $ticketUser->order_number ?? 'N/A',
            'ticket_type' => $ticketUser->ticket ? ucfirst($ticketUser->ticket->type) : 'N/A',
            'user' => $ticketUser->user ? $ticketUser->user->name : 'N/A',
            'status' => ucfirst($ticketUser->status ?? 'N/A'),
            'reason' => $ticketUser->content ?? 'N/A',
            'messages' => $latestComment ? $latestComment->body : 'No comments yet',
            'pending_response' => $pendingResponse,
        ];
    });

    // Return the processed data as a JSON response for the front-end export logic
    return response()->json($data);
}

    

    


    public function new(Request $request)
    {
        // Fetch only tickets with 'open' or 'in progress' status
        $query = TicketUser::with(['ticket', 'user'])
                    ->whereIn('status', ['open', 'in progress']);

                    // Apply filters for the tickets
        if ($request->filled('order_number')) {
            $query->where('order_number', $request->order_number);
        }

        if ($request->filled('reason')) {
            $query->where('content', $request->reason);
        }

        if ($request->filled('ticket_type')) {
            $query->whereHas('ticket', function ($q) use ($request) {
                $q->where('type', $request->ticket_type);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Get filtered tickets
        $tickets = $query->orderBy('created_at', 'desc')->get();
        // Pass tickets to the view
        return view('tickets.new', compact('tickets'));
    }

    
    public function create(){
        $roleId = auth()->user()->role_id; // Get the authenticated user's role ID

        return view('tickets.create',compact('roleId'));
    }

    public function checkOrder(Request $request)
    {
        $orderNumber = $request->query('order_number');

        $orderNumber = str_replace('Lvs','',$orderNumber);
        $orderNumber = str_replace('lvs','',$orderNumber);
        
        // Check if the order exists in the database
        $orderExists = Order::where('order_number', $orderNumber)->exists();

        // Return the response as JSON
        return response()->json(['exists' => $orderExists]);
    }

    public function checkTicket(Request $request)
    {
        $orderNumber = $request->query('order_number');

        $orderNumber = str_replace('Lvs','',$orderNumber);
        $orderNumber = str_replace('lvs','',$orderNumber);
        
        // Check if a ticket exists for the given order number
        $ticket = TicketUser::where('order_number', $orderNumber)->first();

        if ($ticket) {
            // If a ticket exists, return the ticket ID
            return response()->json(['exists' => true, 'ticket_id' => $ticket->id]);
        }

        // If no ticket exists, return a response indicating it
        return response()->json(['exists' => false]);
    }


    
    

    public function open($ticketUserId)
    {
        // Find the specific record in the pivot table
        $ticketUser = TicketUser::find($ticketUserId);
    
        if ($ticketUser) {
            // Change status to 'in_progress'
            $ticketUser->status = 'in progress';
            $ticketUser->save();
    
            $ticketHistory = new TicketHistory();
            $ticketHistory->user_id = auth()->id();
            $ticketHistory->ticket_id = $ticketUser->id;
            $ticketHistory->order_id = $ticketUser->order_number;
            $ticketHistory->action = "Open";
            $ticketHistory->note= "Ticket has been Opened";
            $ticketHistory->save();

            // Redirect to the ticket details page after status update
            return redirect()->route('tickets.show', $ticketUser->id);
        }
    
        return redirect()->route('tickets.index')->with('error', 'No associated user found for this ticket.');
    }
    
    // Show the ticket details
    public function show($ticketUserId)
    {
        // Find the specific ticket user
        $ticketUser = TicketUser::with('comments.user')->find($ticketUserId);
    
        // Return the 'show' view with ticket user details
        return view('tickets.show', compact('ticketUser'));
    }
    
    public function updateStatus(Request $request, $ticketUserId)
{
    $ticketUser = TicketUser::findOrFail($ticketUserId);

    // if ($request->has('accept_shipping')) {
    //     $ticketUser->is_closed = 1;
    //     $ticketUser->is_asked_to_close = 0;
    //     $ticketUser->status = "done";
    // }
    // if ($request->has('refuse_shipping')) {
    //     $ticketUser->is_closed = 0;
    //     $ticketUser->is_asked_to_close = 0;
    // }
 
    // ///////////////////////
    // if ($request->has('ask_to_close')) {
    //     $ticketUser->is_closed = 0;
    //     $ticketUser->is_asked_to_close = 1;
    // }
    // if ($request->has('cancel_ask_to_close')) {
    //     $ticketUser->is_closed = 0;
    //     $ticketUser->is_asked_to_close = 0;
    // }

    // ////////////////////
    // if ($request->has('create_return')) {
    //     $ticketUser->is_returned = 0;
    //     $ticketUser->is_asked_to_return = 1;

    // }
    // if ($request->has('cancel_return')) {
    //     $ticketUser->is_returned = 0;
    //     $ticketUser->is_asked_to_return = 0;

    // }
    // if ($request->has('confirm_return')) {
    //     $ticketUser->is_returned = 1;
    //     $ticketUser->is_asked_to_return = 0;

    // }
    ////////////////////

// Check the ticket_action value in the request
$action = $request->input('ticket_action');
$commentBody = $request->input('body');

// Initialize comment text if specific action is present
if ($action === 'returned') {

    $ticketUser->is_returned = 1;
    $ticketUser->status = 'done';
    $ticketUser->is_asked_to_return = 0;

    $ticketHistory = new TicketHistory();
    $ticketHistory->user_id = auth()->id();
    $ticketHistory->ticket_id = $ticketUser->id;
    $ticketHistory->order_id = $ticketUser->order_number;
    $ticketHistory->action = "Done and Return";
    $ticketHistory->note= "Return Request has been Confirmed By Shipping on This Order and Ticket Marked Done";
    $ticketHistory->save();

    $commentText = "تم الاسترجاع بنجاح";

} elseif ($action === 'delivered') {
    $ticketUser->is_closed = 1;
    $ticketUser->status = 'done';
    $ticketUser->is_hold = 0;
    $commentText = $commentBody ?: "تم التسليم بنجاح";
}elseif ($action === 'return_cancel') {
    
    $ticketUser->is_closed = 0;
    $ticketUser->is_returned = 0;
    $ticketUser->is_asked_to_return = 0;
    $commentText = "تم رفض طلب الاسترجاع ";

    $ticketHistory = new TicketHistory();
    $ticketHistory->user_id = auth()->id();
    $ticketHistory->ticket_id = $ticketUser->id;
    $ticketHistory->order_id = $ticketUser->order_number;
    $ticketHistory->action = "Done and Return";
    $ticketHistory->note= "Return Request has been Cancelled By Shipping on This Order and Return has been deleted";
    $ticketHistory->save();

} elseif ($action === 'ask_for_return') {

    $data = ['ticket_id'=>$ticketUser->id];
    $return_request = new Request($data);
        return $this->confirmReturn($return_request,true);

    
} elseif ($action === 'hold') {
    $ticketUser->is_hold = 1;
    $commentText = $commentBody ?: "تم تعليق التيكت";
}else {
    $commentText = $commentBody;
    $ticketUser->is_hold = 0;

}

// Only save if a valid action was provided
if (isset($commentText)) {
    // Save the ticket status update
    $ticketUser->save();

    $comment = new Comment;
    $comment->body = $commentText;
    $comment->user_id = auth()->id();
    $comment->ticket_user_id = $ticketUser->id;
    

    if($request->hasFile('img')) {
        $validator = Validator::make($request->all(), [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Handle the failed validation
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $img = $request->file('img');
        $imgName = $img->getClientOriginalName();

        // Store the file in the 'uploads' directory within storage/app
        $path = $img->storeAs('public/uploads', $imgName);

        // Get the URL to the stored file
        $storagePath = Storage::url($path);
        $comment->img = $storagePath;
    }
    $comment->save();
}

// Redirect with success message
return redirect()->back()->with('success', 'Ticket updated successfully.');
}


public function confirmReturn(Request $request,$return = false)
{
    $ticket = TicketUser::find($request->ticket_id);

    if ($ticket) {

        if($request->status == "cancel")
        {
            $ticket->is_closed = 0;
            $ticket->is_returned = 0;
            $ticket->is_asked_to_return = 0;
            $commentText = "تم رفض طلب الاسترجاع ";
            $ticket->save();

            $comment = new Comment;
            $comment->body = $commentText;
            $comment->user_id = auth()->id();
            $comment->ticket_user_id = $ticket->id;
            $comment->save();

            $ticketHistory = new TicketHistory();
            $ticketHistory->user_id = auth()->id();
            $ticketHistory->ticket_id = $ticket->id;
            $ticketHistory->order_id = $ticket->order_number;
            $ticketHistory->action = "Done and Return";
            $ticketHistory->note= "Return Request has been Cancelled By Shipping on This Order and Return has been deleted";
            $ticketHistory->save();

            return response()->json(['success' => true,'message'=>'']);
        }
        elseif($request->status == "confirm")
        {
    
            $ticket->status = "done";
            $ticket->is_returned = 1;
            $ticket->is_asked_to_return = 0;
            $commentText = "تم الاسترجاع بنجاح";
            $ticket->save();

            $comment = new Comment;
            $comment->body = $commentText;
            $comment->user_id = auth()->id();
            $comment->ticket_user_id = $ticket->id;
            $comment->save();

            $ticketHistory = new TicketHistory();
            $ticketHistory->user_id = auth()->id();
            $ticketHistory->ticket_id = $ticket->id;
            $ticketHistory->order_id = $ticket->order_number;
            $ticketHistory->action = "Done and Return";
            $ticketHistory->note= "Return Request has been Confirmed By Shipping on This Order and Ticket Marked Done";
            $ticketHistory->save();

            return response()->json(['success' => true,'message'=>'']);
        }
        
        $order_number = $ticket->order_number;
        
        $order = Order::where('order_number',$order_number)->first();
        if($order && $order->fulfillment_status == "shipped")
        {

            $data = ['all' => 'all','order'=>$order , 'source' => 'shipping'];
            $return_request = new Request($data);
    
            $message = $this->return_order($return_request);

            if($message == "")
            {
                DB::beginTransaction();

                DB::table('ticket_user')
                    ->where('id', $ticket->id)
                    ->update([
                        'status' => 'done', // Update status to 'done'
                        'is_asked_to_return' => 1, // Update is_asked_to_return to 1
                        'is_returned' => 0, // Ensure the updated_at timestamp is also updated
                    ]);

                // Commit the transaction if everything is successful
                DB::commit();

                $ticketHistory = new TicketHistory();
                $ticketHistory->user_id = auth()->id();
                $ticketHistory->ticket_id = $ticket->id;
                $ticketHistory->order_id = $ticket->order_number;
                $ticketHistory->action = "Ask to Return";
                $ticketHistory->note= "Return Request has been Created By Levoile on This Order and Pending Shipping Confirmation";
                $ticketHistory->save();

                $commentText =  "برجاء استرجاع الطلب";
                $comment = new Comment;
                $comment->body = $commentText;
                $comment->user_id = auth()->id();
                $comment->ticket_user_id = $ticket->id;
                $comment->save();

                if($return)
                    return redirect()->back()->with('success', 'Ticket updated successfully.');

                return response()->json(['success' => true,'message'=>'']);

            }
            else{
                DB::rollBack();
                if($return)
                    return redirect()->back()->with('error', $message);

                return response()->json(['success' => false,'message'=>$message]);
            }
            
            
        }
        else{
            DB::rollBack();
            if($return)
                return redirect()->back()->with('error', 'Order Not Found or Not Shipped Yet.');

            return response()->json(['success' => false,'message'=>'Order Not Found or Not Shipped Yet.']);
        }

        if($return)
            return redirect()->back()->with('success', 'Ticket Updated Successfully');

        return response()->json(['success' => true,'message'=>'']);
    }

    if($return)
        return redirect()->back()->with('error', 'Ticket Not Found.');

    return response()->json(['success' => false,'message'=>'Ticket Not Found']);
}
public function createPrepare($order,$prepare_emp=1)
    {
        $user = Auth::user();
        $store = $user->getShopifyStore;

        $add_to_prepare = Prepare::where('order_id', $order->id)->first();
        if(!$add_to_prepare)
        $add_to_prepare = new Prepare();

        $add_to_prepare->order_id  = $order->id;
        $add_to_prepare->store_id  = $order->store_id;
        $add_to_prepare->table_id  = $order->table_id;
        $add_to_prepare->assign_by  = Auth::user()->id;
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
            
                $product = Product2::find($item['product_id']);
            }
            else
            {
                $product = Product2::where('variants','like','%'.$item['sku'].'%')->first();
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
            $prepare_product->user_id = Auth::user()->id;
            $prepare_product->product_id = $item['id'];
            $prepare_product->product_sku = $item['sku'];
            $prepare_product->variation_id = $item['variant_title'];
            $prepare_product->variant_image = $product_img;
            $prepare_product->order_qty = $item['quantity'];
            $prepare_product->product_status= $item['fulfillment_status']??"unfulfilled";
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

    public function return_order($request)
    {
        $return = new ReturnedOrder();
        $order = $request->order;
        if($order) {
            if($order->note=="Point of Sale" || $order->note=="Point of Sale")
            $return->type = "pos";
            else
            $return->type = "online";
            $return->order_id = $order->id;
            $return->order_number = $order->order_number;
            $return->note = "Redirect From Ticket";
            $return->shipping_on = "client";
            $return->status = "In Progress";
            $return->user_id = auth()->user()->id;
            $return->created_at = now();
            $return->updated_at = now();
            $return->save();
            $return->return_number = 1000+$return->id;
            if(isset($request->source) && $request->source == "shipping")
            {
                $return->status = "Shipped";
                $return->shipping_status = "shipped";
                $return->save();
            }
                
            $qty = 0;
            $amount = 0;
            $line_items = [];

            $user = auth()->user();
            $store = $user->getShopifyStore;

            $payload = $this->getFulfillmentItemForReturn($return->order_id);

            $api_endpoint = 'graphql.json';
            

            $endpoint = getShopifyURLForStore($api_endpoint, $store);
            $headers = getShopifyHeadersForStore($store);
            
            $response = $this->makeAnAPICallToShopify('POST', $endpoint, null, $headers, $payload);
            $items = [];
            if($response['statusCode'] === 201 || $response['statusCode'] === 200)
            {
                if (isset($response['body']['data']['returnableFulfillments']['edges'])) {
                    foreach($response['body']['data']['returnableFulfillments']['edges'] as $edge)
                    {
                        if(isset($edge['node']['returnableFulfillmentLineItems']['edges']))
                        {
                            foreach($edge['node']['returnableFulfillmentLineItems']['edges'] as $mini)
                            {
                                 $items[] = $mini;
                            }
                        }
                    }
                   
                }
            }
            $return_items = $request->items;
            if(isset($request->all) && $request->all == "all")
            {
                $prepare = Prepare::where('order_id', $order->id)->first();
                if (!$prepare)
                    $prepare = $this->createPrepare($order);

                $return_items = $prepare->products;
            }
            foreach($return_items as $key=>$item) {
                if (!$item)
                    continue;
                $detail = new ReturnDetail();
                $detail->return_id = $return->id;
                $detail->line_item_id = $item->product_id;
                $detail->qty = $item->order_qty;
                $detail->amount = $item->price;
                $detail->reason = "OTHER";
                $detail->prepare_product_id = $item->id;
                $detail->created_at = now();
                $detail->updated_at = now();
                $detail->save();
                $qty += $item->order_qty;
                $amount += $item->price;

                if(isset($items[$key]))
                {
                    $line_items[] = '
                    {
                    fulfillmentLineItemId: "'.$items[$key]['node']['fulfillmentLineItem']['id'].'",
                    quantity: '.$items[$key]['node']['quantity'].',
                    returnReason: '.$detail->reason.'
                    returnReasonNote: "'.$return->note.'"
                    }
                    ';
                }
                

            }

            $return->qty = $qty;
            $return->amount = $amount;
            $return->save();

            $payload = $this->createReturnMutation($return->order_id,$line_items);
            $api_endpoint = 'graphql.json';
            

            $endpoint = getShopifyURLForStore($api_endpoint, $store);
            $headers = getShopifyHeadersForStore($store);
            
            $response = $this->makeAnAPICallToShopify('POST', $endpoint, null, $headers, $payload);
            
            if($response['statusCode'] === 201 || $response['statusCode'] === 200)
            {
                if(isset($response['body']['data']['returnCreate']['return']))
                {
                    $return->return_id = $response['body']['data']['returnCreate']['return']['id'];
                    $return->save();
                    return '';

                }
                dd($response,$payload);
                return 'Something Went Wrong.';

            }
        }
        return 'Order Not Found or Not Shipped Yet.';
    }

    public function createReturnMutation($order_id,$line_items)
    {
        $fulfillmentV2Mutation = 'returnCreate (

            returnInput: {
            orderId: "gid://shopify/Order/'.$order_id.'",
            returnLineItems: ['.implode(',',$line_items).'],
            requestedAt: "2022-05-04T00:00:00Z",
            	notifyCustomer: false,
        }
        )
        {
            return {
            id
            }
            userErrors {
            field
            message
            }
        }';
        $mutation = 'mutation returnCreateMutation{ '.$fulfillmentV2Mutation.' }';
        // dd($mutation);
        return ['query' => $mutation];

    }
    public function getFulfillmentItemForReturn($order_id)
    {
        $query = '
        
        query returnableFulfillmentsQuery {
            returnableFulfillments(orderId: "gid://shopify/Order/'.$order_id.'", first: 50) {
                edges {
                node {
                    id
                    fulfillment {
                    id
                    }
                    returnableFulfillmentLineItems(first: 50) {
                    edges {
                        node {
                        fulfillmentLineItem {
                            id
                        }
                        quantity
                        }
                    }
                    }
                }
                }
            }
            }

        ';
        return ['query' => $query];
    }


    public function report(Request $request) {
        // Query to get the tickets, with eager loading of the ticket and user relationships
        $query = TicketUser::with(['ticket', 'user']);
    
        // Apply date range filter if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
    
        // Fetch the tickets after applying the filters
        $tickets = $query->get();
    
        return view('tickets.report', compact('tickets'));
    }
    
    
    

    // Store a new ticket
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'order' => 'required',
            'ticket_type' => 'required',
            'reason' => 'required',
            'content' => 'required'
        ]);
        // Format the reason and combine it with the content
        $reasonFormatted = str_replace('_', ' ', $validated['reason']);
        // $combinedContent = $reasonFormatted . ' - ' . $validated['content'];
        $user = auth()->user(); // Get the authenticated user
    
        // Fetch the ticket using the first() method instead of pluck()
        $ticket = Ticket::where('type', $validated['ticket_type'])->first();

        $orderNumber = str_replace('Lvs','',$validated['order']);
        $orderNumber = str_replace('lvs','',$orderNumber);
    
        // Check if the ticket exists before attaching
        if ($ticket) {
            // Attach the user to the ticket in the pivot table
            $ticketUser = TicketUser::create([
                'user_id' => $user->id,
                'ticket_id' => $ticket->id,
                'status' => 'open',           // Status for the pivot record
                'order_number' => $orderNumber, // Order number from the request
                'content' => $validated['reason']  // Combined content for the pivot record
            ]);

              // Get the ticket_user_id from the pivot table
              $ticketUserId = $ticketUser->id;

            // Create a new comment
            $comment = new Comment;
            $comment->ticket_user_id = $ticketUserId; // Use the correct ticket_user_id
            $comment->body = $validated['content'];
            $comment->user_id = $user->id;
            $comment->save();

            $ticketHistory = new TicketHistory();
            $ticketHistory->user_id = auth()->id();
            $ticketHistory->ticket_id = $ticketUser->id;
            $ticketHistory->order_id = $ticketUser->order_number;
            $ticketHistory->action = "Create";
            $ticketHistory->note= "a new Ticket has been Added to this Order";
            $ticketHistory->save();

            // Redirect back to the tickets index with a success message
            return redirect()->route('tickets.index')->with('success', 'Ticket added successfully.');
        }
    
        // Handle the case where the ticket was not found
        return redirect()->route('tickets.index')->with('error', 'Ticket type not found.');
    }


    public function destroy($ticketUserId)
{
    // Find the ticket_user entry by the unique identifier
    $ticketUser = TicketUser::find($ticketUserId); // Ensure to use the appropriate model

    if ($ticketUser) {
        // Delete the specific ticket_user entry
        $ticketUser->delete();

        return redirect()->back()->with('success', 'Ticket deleted successfully from the user.');
    }

    return redirect()->back()->with('error', 'Ticket not found.');
}



public function store_comment(Request $request, $ticketUserId)
{
    $request->validate([
        'body' => 'required|string|max:255',
    ]);

    $ticketUser = TicketUser::findOrFail($ticketUserId);

    // Create a new comment related to the ticket user
    $comment = Comment::create([
        'body' => $request->input('body'), // Corrected from 'content' to 'body'
        'ticket_user_id' => $ticketUser->id,
        'user_id' => auth()->id(),
    ]);
    
    session()->put('new_comment', $comment);


    return redirect()->back()->with('success', 'Comment added successfully!');
}

public function checkAsDone(Request $request, $ticketUserId)
{
    // Find the specific record in the pivot table
    $ticketUser = TicketUser::findOrFail($ticketUserId);

    // Check if the checkbox is checked
    if ($request->has('done')) {
        // Change status to 'done'
        $ticketUser->status = 'done';
        $ticketUser->save();

        $ticketHistory = new TicketHistory();
        $ticketHistory->user_id = auth()->id();
        $ticketHistory->ticket_id = $ticketUser->id;
        $ticketHistory->order_id = $ticketUser->order_number;
        $ticketHistory->action = "Done";
        $ticketHistory->note= "Ticket has been Marked as Done on this Order";
        $ticketHistory->save();

        return redirect()->back()->with('success', 'Ticket status updated to done!');
    }

    return redirect()->back()->with('info', 'Checkbox not checked. Status remains unchanged.');
}
public function checkAsDoneAndReturn(Request $request, $ticketUserId)
{
    try {
        // Start a DB transaction
        DB::beginTransaction();
        
        // Insert into comments table using raw query
        DB::table('comments')->insert([
            'user_id' => auth()->id(), // User ID from authenticated user
            'ticket_user_id' => $ticketUserId, // Ticket user ID from request
            'body' => 'برجاء استرجاع الطلب', // The comment body text
            'created_at' => now(), // Add current timestamp
            'updated_at' => now(),
        ]);

        // Update the ticket_user table with the new status and is_asked_to_return
        DB::table('ticket_user')
            ->where('id', $ticketUserId)
            ->update([
                'status' => 'done', // Update status to 'done'
                'is_asked_to_return' => 1, // Update is_asked_to_return to 1
                'updated_at' => now(), // Ensure the updated_at timestamp is also updated
            ]);

        // Commit the transaction if everything is successful
        DB::commit();
        
        $ticketUser = TicketUser::findOrFail($ticketUserId);
        $ticketHistory = new TicketHistory();
        $ticketHistory->user_id = auth()->id();
        $ticketHistory->ticket_id = $ticketUser->id;
        $ticketHistory->order_id = $ticketUser->order_number;
        $ticketHistory->action = "Done and Return";
        $ticketHistory->note= "Ticket marked as Done and a Return has been Requested on This Order";
        $ticketHistory->save();

        // Optionally, return a success response
        return back()->with('success', 'Ticket status updated and comment added successfully.');
    } catch (\Exception $e) {
        // Rollback the transaction if something goes wrong
        DB::rollBack();

        // Log the error for debugging purposes
        \Log::error('Error updating ticket: ' . $e->getMessage());

        // Optionally, return an error response
        return back()->with('error', 'An error occurred while updating the ticket.');
    }
}


public function reopen(Request $request, $ticketUserId)
    {
        $ticketUser = TicketUser::findOrFail($ticketUserId);

        // Update the ticket status to "in progress" (or whatever status you prefer)
        $ticketUser->status = 'in progress';
        $ticketUser->save();
        DB::table('comments')->insert([
            'user_id' => auth()->id(), // User ID from authenticated user
            'ticket_user_id' => $ticketUser->id, // Ticket user ID from request
            'body' => 'تم اعاده فتح التيكت', // The comment body text
            'created_at' => now(), // Add current timestamp
            'updated_at' => now(),
        ]);

        $ticketHistory = new TicketHistory();
        $ticketHistory->user_id = auth()->id();
        $ticketHistory->ticket_id = $ticketUser->id;
        $ticketHistory->order_id = $ticketUser->order_number;
        $ticketHistory->action = "Re-Open";
        $ticketHistory->note= "Ticket Has Been Reopened";
        $ticketHistory->save();

        return redirect()->back()->with('success', 'Ticket has been reopened.');
    }


    public function markAsRead($id)
    {
        // Find the ticket user and mark it as read
        $ticketUser = TicketUser::find($id);
    
        if ($ticketUser) {
            $ticketUser->read_at = now(); // Mark as read
            $ticketUser->save();
            
            // Call the open method to change the status to 'in progress'
            $this->openInNotification($id);
    
            // Send a JSON response back with the ticket ID for the front-end
            return response()->json([
                'success' => true,
                'ticket_id' => $ticketUser->id
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Ticket not found'], 404);
    }

    public function openInNotification($id){
          // Find the specific record in the pivot table
        $ticketUser = TicketUser::find($id);

        if ($ticketUser) {
            // Change status to 'in progress'
            $ticketUser->status = 'in progress';
            $ticketUser->save();
        }
    }

    public function markAsReadcomment($ticketId)
    {
        // Find the comment by the ticket ID (assuming ticketId corresponds to comment ID)
        $comment = Comment::where('ticket_user_id',$ticketId)->first();

        if ($comment) {
            // Set the read_at timestamp to now
            $comment->read_at = now();
            $comment->save(); // Save the changes

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Comment not found'], 404);
    }


  
    public function reason(Request $request)
    {
        // Define the reasons
        $reasons = [
            'change_delivery_time',
            'change_delivery_location',
            'change_consignee_data',
            'other',
            'shipment_not_delivered',
            'delivery_delay',
            'mistreatment',
            'no_answer',
            'refuse_receiving',
            'reschedules_delivery',
            'wrong_number',
            'wrong_address',
            'wrong_total_price',
            'payment_issues',
        ];
    
        // Fetch the count of each reason from ticket_user table with date filtering
        $reasonCounts = [];
        foreach ($reasons as $reason) {
            // Initialize the query
            $query = DB::table('ticket_user')->where('content', $reason);
    
            // Apply date range filter if both start_date and end_date are filled
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
            
    
            // Get the count for this reason
            $count = $query->count();
            $reasonCounts[$reason] = $count;

            if ($request->filled('sort_by')) {
                if ($request->sort_by == "asc")
                    sort($reasonCounts);
                else
                    rsort($reasonCounts);
            }
        }
    
        // Return the view with the reason counts
        return view('tickets.reason', compact('reasonCounts'));
    }
    

public function showReason($reason)
{
    $tickets = DB::table('ticket_user')->where('content', $reason)->get();

    return view('tickets.reason-show', compact('tickets','reason'));
}



}
