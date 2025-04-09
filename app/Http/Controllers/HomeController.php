<?php

namespace App\Http\Controllers;

use App\Models\OrderHistory;
use App\Models\PrepareProductList;
use App\Models\Store;
use App\Models\User;
use App\Traits\RequestTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    use RequestTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //Test commit
    }

    public function listUsers() {
        $data = User::where('email', '<>', 'superadmin@shopify.com')->get();
        return view('list_users', ['users' => $data]);
    }

    public function base(Request $request) {
        return redirect()->route('login');
    }

    public function checkPincodeAvailability(Request $request) {
        Log::info('Request received for product pincode check');
        Log::info($request->all());

        //Put the validation logic here
        $returnResp = ['status' => true, 'message' => 'Available'];

        return response()->json($returnResp, 200);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {
        $user = Auth::user();

        if($user->hasRole('SuperAdmin')) {
            $payload = $this->getSuperAdminDashboardPayload($user);
            return view('superadmin.home', $payload);
        } else {
            $data = $request->all();
            $store = $user->getShopifyStore;
            $payload = $this->getDashboardPayload($user, $store,$data);
            return view('home', $payload);
        }
    }

    public function getSuperAdminDashboardPayload($user) {
        try {
            $stores_count = Store::count();
            $private_stores = Store::where('api_key', '<>', null)->where('api_secret_key', '<>', null)->count();
            $public_stores = Store::where('api_key', null)->where('api_secret_key', null)->count();
            return [
                'stores_count' => $stores_count,
                'private_stores' => $private_stores,
                'public_stores' => $public_stores
            ];
        } catch(Exception $e) {
            return [
                'stores_count' => 0,
                'private_stores' => 0,
                'public_stores' => 0
            ];
        }
    }

    private function getDashboardPayload($user, $store,$data) {
        try {
            $orders = $store->getOrders();
            $orders = $orders->whereDate('created_at_date', now()->toDateString());
            $prepares = $store->getPrepares();
            $all_prepares = $prepares->whereDate('created_at', now()->toDateString())->get();
            $moderates = $store->getOrders()->where('channel','POS');
            $all_moderates = $moderates->whereDate('created_at', now()->toDateString());
            $prepare_users_list = [];
            $moderate_users_list = [];
            $prepare_users = User::where('role_id', '5')->get();
            $moderate_users = User::where('role_id', '6')->get();
            // $all_prepares = $all_prepares->whereDate('created_at', now()->toDateString());
            // $all_moderates = $all_moderates->whereDate('created_at', now()->toDateString());
            if(count($prepare_users)) {
                foreach ($prepare_users as $key => $prepare) {

                    $prepare_users_list['id'][$key] = $prepare->id;
                    $prepare_users_list['name'][$key] = $prepare->name;
                    $prepare_users_list['all'][$key] = $all_prepares->where('assign_to',$prepare->id)->count();
                    $prepare_users_list['hold'][$key] = $all_prepares->where('assign_to',$prepare->id)->where('delivery_status','hold')->count();
                    $prepare_users_list['fulfilled'][$key] = $all_prepares->where('assign_to',$prepare->id)->where('delivery_status','fulfilled')->count();
                }
            }
            if(count($moderate_users)) {
                foreach ($moderate_users as $key => $moderate) {

                    $moderate_users_list['id'][$key] = $moderate->id;
                    $moderate_users_list['name'][$key] = $moderate->name;
                    $moderate_users_list['all'][$key] = $all_moderates->where('created_by',$moderate->id)->count();
                    $moderate_users_list['facebook'][$key] = $all_moderates->where('created_by',$moderate->id)->where('source_name','facebook')->count();
                    $moderate_users_list['instagram'][$key] = $all_moderates->where('created_by',$moderate->id)->where('source_name','instagram')->count();
                    $moderate_users_list['whatsapp'][$key] = $all_moderates->where('created_by',$moderate->id)->where('source_name','whatsapp')->count();
                    $moderate_users_list['cancelled'][$key] = $all_moderates->where('created_by',$moderate->id)->where('fulfillment_status','cancelled')->count();


                }
            }
            $activities = OrderHistory::orderBy('created_at','desc')->limit(10)->get();
            $mostSellingProducts = PrepareProductList::select('product_name','variant_image','price', DB::raw('COUNT(*) as total'))
            ->groupBy('product_name','variant_image','price')
            ->orderBy('total', 'desc')->limit(10)
            ->get();
            // dd($prepare_users_list);
            return [
                'orders_count' => $orders->count() ?? 0,
                'orders_revenue' => $orders->sum('total_price') ?? 0,
                'customers_count' => $store->getCustomers()->count() ?? 0,
                'recent_sales' => $orders->orderBy('created_at_date','desc')->limit(10)->get(),
                'reports' => $prepare_users_list,
                'reports2' => $moderate_users_list,
                'activities' => $activities,
                'most_selling' => $mostSellingProducts,
            ];
        } catch(Exception $e) {
            return [
                'orders_count' => 0,
                'orders_revenue' => 0,
                'customers_count' => 0,
                'recent_sales' => [],
                'reports' => [],
                'reports2' => [],
                'activities'=>[],
                'most_selling'=>[]
            ];
        }
    }

    public function testDocker() {
        $endpoint = getDockerURL('ping/processor', 8010);
        $headers = getDockerHeaders();
        $response = $this->makeADockerAPICall($endpoint, $headers);
        return response()->json($response);
    }

    public function indexElasticSearch() {
        $endpoint = getDockerURL('index/elasticsearch', 8010);
        $headers = getDockerHeaders();
        $response = $this->makeADockerAPICall($endpoint, $headers);
        return back()->with('success', 'Indexing Complete. Response '.json_encode($response));
    }

    public function searchStore(Request $request) {
        if($request->ajax()) {
            if($request->has('searchTerm')) {
                $searchTerm = $request->searchTerm;
                $endpoint = getDockerURL('search/store?search='.$searchTerm, 8010);
                $headers = getDockerHeaders();
                $response = $this->makeADockerAPICall($endpoint, $headers);
                return response()->json($response);
            }
        }
        return response()->json(['status' => false, 'message' => 'Invalid Request']);
    }
    public function tickets_index(){
        return view('tickets.index');
    }
}
