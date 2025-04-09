<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DevOpsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WebhooksController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\LoginSecurityController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\BranchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', [HomeController::class, 'base']);

Route::get('validate-pincode', [HomeController::class, 'checkPincodeAvailability'])->name('product.check.availability');

Route::prefix('devops')->middleware(['guest:devops'])->group(function () {
    Route::get('login', [DevOpsController::class, 'devOpsLogin'])->name('devops.login');
    Route::post('login', [DevOpsController::class, 'checkLogin'])->name('devops.login.submit');
});

Route::prefix('devops')->middleware(['auth:devops'])->group(function () {
    Route::get('dashboard', [DevOpsController::class, 'dashboard'])->name('devops.home');
});

Route::middleware(['auth','super_admin'])->group(function () {
    Route::resource('stores', SuperAdminController::class);
    Route::get('notifications', [SuperAdminController::class, 'sendIndex'])->name('real.time.notifications');
    Route::post('send/message', [SuperAdminController::class, 'sendMessage'])->name('send.web.message');

    //ElasticSearch Routes
    Route::get('elasticsearch/index', [HomeController::class, 'indexElasticSearch'])->name('elasticsearch.index');
    Route::post('search/store', [HomeController::class, 'searchStore'])->name('search.store');
});

Route::middleware(['two_fa', 'auth'])->group(function () {

    Route::get('dashboard', [HomeController::class, 'index'])->name('home');

    Route::middleware(['role:Admin', 'is_public_app'])->group(function () {
        Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
        Route::get('plan/buy/{id}', [BillingController::class, 'buyThisPlan'])->name('plan.buy');
        Route::any('shopify/rac/accept', [BillingController::class, 'acceptSubscriptionCharge'])->name('plan.accept');
        Route::get('consume/credits', [BillingController::class, 'consumeCredits'])->name('consume.credits');
    });

    Route::middleware(['two_fa', 'auth', 'is_private_app'])->group(function () {
        Route::get('subscriptions', [StripeController::class, 'index'])->name('subscriptions.index');
        Route::post('add.card.user', [StripeController::class, 'addCardToUser'])->name('add.card.user');
        Route::get('purchase/subscription/{id}', [StripeController::class, 'purchaseSubscription'])->name('purchase.subscription');
        Route::get('purchase/credits/{id}', [StripeController::class, 'purchaseOneTimeCredits'])->name('purchase.credits');
        Route::get('billing-portal', [StripeController::class, 'billingPortal'])->name('billing.portal');
    });

    Route::prefix('shopify')->group(function () {
            Route::get('products', [ShopifyController::class, 'products'])->name('shopify.products');
            Route::get('locations', [ShopifyController::class, 'locations'])->name('shopify.locations');
            Route::get('products-variants', [ShopifyController::class, 'product_variants'])->name('shopify.product_variants');
            Route::get('sync/locations', [ShopifyController::class, 'syncLocations'])->name('locations.sync');
            Route::get('sync/warehouse-products', [ShopifyController::class, 'syncWarehouseProducts'])->name('shopify.warehouse_products.sync');
            Route::get('products/create', [ProductsController::class, 'create'])->name('shopify.product.create');
            Route::get('add_variant', [ProductsController::class, 'getHTMLForAddingVariant'])->name('product.add.variant');
            Route::get('sync/products', [ShopifyController::class, 'syncProducts'])->name('shopify.products.sync');
            Route::post('products/publish', [ProductsController::class, 'publishProduct'])->name('shopify.product.publish');
            Route::get('changeProductAddToCartStatus', [ProductsController::class, 'changeProductAddToCartStatus'])->name('change.product.addToCart');

            Route::get('orders', [ShopifyController::class,'orders'])->name('shopify.orders');
            Route::get('orders/pending-payment', [ShopifyController::class,'pending_payment_orders'])->name('shopify.pending_payment');
            Route::get('sync/pending-orders', [ShopifyController::class, 'sync_pending_payment_orders'])->name('orders.sync_pending');
            Route::post('order/fulfill', [ShopifyController::class, 'fulfillOrder'])->name('shopify.order.fulfill');
            Route::post('order/fulfill-items', [ShopifyController::class, 'fulfillOrderItems'])->name('shopify.order.fulfillitems');
            Route::get('order/{id}', [ShopifyController::class, 'showOrder'])->name('shopify.order.show');
            Route::get('order/{id}/sync', [ShopifyController::class, 'syncOrder'])->name('shopify.order.sync');
            Route::get('sync/orders', [ShopifyController::class, 'syncOrders'])->name('orders.sync');
            Route::get('orders/confirm', [ShopifyController::class, 'confirmOrders'])->name('orders.confirm');

            Route::get('blacklist', [ShopifyController::class, 'blacklist'])->name('blacklist');
            Route::get('blacklist/create', [ShopifyController::class, 'blacklist_create'])->name('blacklist.create');
            Route::post('blacklist/create', [ShopifyController::class, 'blacklist_store'])->name('blacklist.store');
            Route::get('blacklist/edit/{id}', [ShopifyController::class, 'blacklist_edit'])->name('blacklist.edit');
            Route::post('blacklist/edit/{id}', [ShopifyController::class, 'blacklist_update'])->name('blacklist.update');
            Route::delete('blacklist/destroy/{id}', [ShopifyController::class, 'blacklist_destroy'])->name('blacklist.destroy');

        Route::middleware('permission:write-customers|read-customers')->group(function () {
            Route::get('customers', [ShopifyController::class, 'customers'])->name('shopify.customers');
            Route::any('customerList', [ShopifyController::class, 'list'])->name('customers.list');
            Route::get('sync/customers', [ShopifyController::class, 'syncCustomers'])->name('customers.sync');
        });
        Route::get('profile', [SettingsController::class, 'profile'])->name('my.profile');
        Route::any('accept/charge', [ShopifyController::class, 'acceptCharge'])->name('accept.charge');

    });

    //coupons
    Route::get('pos/check-coupon-code', [ShopifyController::class,'searchCouponCode'])->name('pos.coupon');

    Route::get('coupon-codes', [ShopifyController::class,'coupons'])->name('coupons');
    Route::post('update-coupon-code', [ShopifyController::class,'update_coupon'])->name('update-coupon');
    Route::post('create-coupon-code', [ShopifyController::class,'create_coupon'])->name('create-coupon');

    //end coupons 
        Route::get('downloads/{id}/{type?}', [ShopifyController::class, 'downloads'])->name('downloads');

    Route::get('users/all', [ShopifyController::class, 'all_users'])->name('users.all');
    Route::get('users/create', [ShopifyController::class, 'create_user'])->name('users.create');
    Route::post('users/store', [ShopifyController::class, 'store_user'])->name('users.store');
    Route::post('cash-register/close/{cashRegister}/store', [CashRegisterController::class,'PostCloseRegister'])->name('register.close.post'); // close register
    Route::get('sales/lims_product_search', [ShopifyController::class,'limsProductSearch'])->name('product_sale.search');
    Route::get('branch-stocks/all', [ShopifyController::class,'branchStockTransactions'])->name('branches_stock.transactions');

    Route::post('/update-quantity-cart-pos', [ShopifyController::class,'updateQuantity'])->name('pos.updateQuantity');
    Route::post('/update-quantity-cart-remove', [ShopifyController::class,'decreaseCart'])->name('pos.decreaseCart');
	Route::post('/remove-from-cart-pos', [ShopifyController::class,'removeFromCart'])->name('pos.removeFromCart');
    Route::get('/upload-progress', [ShopifyController::class, 'getProgress']);
    Route::get('sales/getproduct/{id}', [ShopifyController::class,'getProduct'])->name('sale.getproduct');
    Route::post('customer/storeCustomer', [CustomerController::class,'storeCustomer'])->name('customer.createNew');
    Route::get('get_customer_sale/{phone}', [CustomerController::class,'getCustomerSale'])->name('sale.getCustomerSale');
    Route::resource('customer', CustomerController::class);
    Route::get('cash-register/close/{id}', [CashRegisterController::class,'getCloseRegister'])->name('register.close');
    Route::get('returns/confirmed', [ShopifyController::class,'confirmedReturns'])->name('returns.confirmed');
    Route::get('returns/transactions', [ShopifyController::class,'returnsTransaction'])->name('returns.trx');
    Route::post('returns/upload', [ShopifyController::class,'uploadReturnsSheet'])->name('returns.upload');
    Route::post('returns/confirm/post', [ShopifyController::class,'postConfirmReturns'])->name('returns.confirm.post');
    Route::get('returns/confirm', [ShopifyController::class,'confirmReturns'])->name('returns.confirm');
    Route::get('open-register',[CashRegisterController::class,'create'])->name('open.register'); // open register
	Route::post('open-register/store', [CashRegisterController::class,'store'])->name('open.register.store'); // open register store
    Route::get('pos', [ShopifyController::class,'pos'])->name('sale.pos');
    Route::get('pos/edit/{id}', [ShopifyController::class,'posEdit'])->name('sale.pos.edit');
    Route::get('search/order', [ShopifyController::class,'searchReadyOrders'])->name('search.order');
    Route::post('orders/store', [ShopifyController::class,'store_order'])->name('orders.store');
    Route::post('orders/update', [ShopifyController::class,'update_order'])->name('orders.update');
    Route::get('orders/search', [ShopifyController::class,'search_order'])->name('orders.search');
    Route::any('all-orders', [ShopifyController::class,'all_orders'])->name('prepare.all');
    Route::any('all-sales', [ShopifyController::class,'all_sales'])->name('sales.all');

    Route::any('shortage-orders', [ShopifyController::class,'shortage_orders'])->name('shortage_orders');

    Route::any('shipping-transactions', [ShopifyController::class,'shipping_transactions'])->name('shipping_trx.index');
    Route::any('shipping-collections', [ShopifyController::class,'shipping_collections'])->name('collections.index');
    Route::any('upload-shipping-collections', [ShopifyController::class,'upload_collections'])->name('collections.upload');
    Route::get('convert-shipping-collections/{id}', [ShopifyController::class,'convert_collections'])->name('collections.convert');
    Route::get('convert-return-collections/{id}', [ShopifyController::class,'convert_return_collections'])->name('returncollections.convert');
    Route::any('returns-shipping-collections', [ShopifyController::class,'return_collections'])->name('return_collections.index');
    Route::any('returns-shipping-collections/finance', [ShopifyController::class,'finance_return_collections'])->name('return_collections.finance');
    Route::any('shipping-collections/delete/{id}', [ShopifyController::class,'delete_collections'])->name('collections.delete');
    Route::any('returns-shipping-collections/delete/{id}', [ShopifyController::class,'delete_return_collections'])->name('returncollections.delete');
    Route::any('returns-shipping-collections/approvefailed/{id}', [ShopifyController::class,'approve_failed_return_collections'])->name('returncollection.approveFailed');

    Route::any('submit-returns-shipping-collections', [ShopifyController::class,'submit_return_collections'])->name('return_collection.submit');
    Route::any('show-returns-shipping-collections/{id}', [ShopifyController::class,'show_return_collections'])->name('return_collections.show');
    Route::any('close-returns-shipping-collections/{id}', [ShopifyController::class,'close_return_collections'])->name('return_collections.close');
    Route::any('approve-returns-shipping-collections', [ShopifyController::class,'approve_return_collections'])->name('return_collections.approve');
    Route::any('upload-returns-shipping-collections', [ShopifyController::class,'upload_returns_shipping_collections'])->name('return_collections.upload');
    Route::post('shipping-transactions/upload', [ShopifyController::class,'upload_shipping_transaction'])->name('shipping_trx.upload');
    Route::any('shipping-transactions/show/{id}', [ShopifyController::class,'show_shipping_transaction'])->name('shipping_trx.show');
    Route::any('shipping-transactions/close/{id}', [ShopifyController::class,'close_shipping_transaction'])->name('shipping_trx.close');
    Route::any('refunds',[ShopifyController::class,'refunds'])->name('refunds');

    Route::post('refunds/confirmrefund',[ShopifyController::class,'confirmRefund'])->name('refunds.confirmrefund');
    Route::any('partial-return/new/{id}',[ShopifyController::class,'newPartialReturn'])->name('partial_return.new');
    Route::post('partial-return/confirm',[ShopifyController::class,'confirmPartialReturn'])->name('partial_return.confirm');
    Route::any('shipping-transactions/approve', [ShopifyController::class,'approve_shipping_transaction'])->name('shipping_trx.approve');
    Route::any('shipping-transactions/check-failed/{id}', [ShopifyController::class,'check_shipping_transaction'])->name('shipping_trx.check_failed');
    Route::any('return_collection/check-failed/{id}', [ShopifyController::class,'check_failed_return_collection'])->name('return_collection.check_failed');
    Route::any('return-collection/export-full/{id}', [ShopifyController::class,'export_full_collection'])->name('return_collection.export_full');
    Route::any('return-collections/export-partial/{id}', [ShopifyController::class,'export_partial_collection'])->name('return_collection.export_partial');
    Route::any('return-collections/export-failed/{id}', [ShopifyController::class,'export_failed_collection'])->name('return_collection.export_failed');

    Route::post('shipping-transactions/submit', [ShopifyController::class,'submit_shipping_transaction'])->name('shipping_trx.submit');
    Route::any('shipping-transactions/export-failed/{id}', [ShopifyController::class,'export_failed_transaction'])->name('shipping_trx.export_failed');
    Route::any('shipping-transactions/export-success/{id}', [ShopifyController::class,'export_success_transaction'])->name('shipping_trx.export_success');

    Route::any('reports/staff', [ShopifyController::class,'staff_report'])->name('reports.staff');
    Route::any('reports/shipped-order', [ShopifyController::class,'shipped_orders_report'])->name('reports.shipped');
    Route::post('shortage/call', [ShopifyController::class,'make_call_post'])->name('shortage.call');
    Route::any('reports/shortage', [ShopifyController::class,'shortage_report'])->name('reports.shortage');
    Route::any('reports/cash-registers', [ShopifyController::class,'cash_register_report'])->name('reports.cash_registers');
    Route::any('reports/stock', [ShopifyController::class,'stock_report'])->name('reports.stock');
    Route::any('reports/sales', [ShopifyController::class,'sales_report'])->name('reports.sales');
    Route::any('new-orders', [ShopifyController::class,'new_orders'])->name('prepare.new');
    Route::any('hold-orders', [ShopifyController::class,'hold_orders'])->name('prepare.hold');
    Route::post('/bulk_order_assign', [ShopifyController::class,'bulk_order_assign'])->name('bulk-order-assign');
    Route::post('/bulk_shortage_assign', [ShopifyController::class,'bulk_shortage_assign'])->name('bulk-shortage-assign');
    Route::any('/staff/export', [ShopifyController::class,'export_staff_report'])->name('staff.export');
    Route::get('order/{id}/prepare', [ShopifyController::class, 'prepareOrder'])->name('shopify.order.prepare');
    Route::any('review-orders/{id}', [ShopifyController::class,'review_order'])->name('prepare.review');
    Route::any('reviewed-orders', [ShopifyController::class,'reviewed_orders'])->name('prepare.reviewed');
    Route::any('generate-invoice/{id}', [ShopifyController::class,'generate_invoice'])->name('prepare.generate-invoice');
    Route::any('generate-return-invoice/{id}', [ShopifyController::class,'generate_return_invoice'])->name('prepare.generate-return-invoice');
    Route::any('order-history/{id}', [ShopifyController::class,'order_history'])->name('prepare.order-history');
    Route::post('review-orders', [ShopifyController::class,'review_post'])->name('prepare.review.post');
    Route::post('bulk-order-shipped', [ShopifyController::class,'bulk_order_shipped'])->name('bulk-order-shipped');
    Route::get('bulk-order-review', [ShopifyController::class,'bulk_order_review'])->name('bulk-order-review');

    Route::post('bulk-returns-shipped', [ShopifyController::class,'bulk_returns_shipped'])->name('bulk-returns-shipped');

    Route::post('/pos-order-summary', [ShopifyController::class,'get_order_summary'])->name('pos.getOrderSummary');
    Route::post('/add-to-cart-pos', [ShopifyController::class,'addToCart'])->name('pos.addToCart');
    Route::post('stock/upload', [ShopifyController::class,'upload_branches_stock'])->name('stock.upload');
    Route::get('pickups', [ShopifyController::class, 'pickups'])->name('pickups.index');
    Route::get('return-pickups', [ShopifyController::class, 'return_pickups'])->name('return-pickups.index');
    Route::any('hold-products', [ShopifyController::class,'hold_products'])->name('prepare.hold-products');
    Route::post('/orders/update_delivery_status', [ShopifyController::class,'update_delivery_status'])->name('orders.update_delivery_status');
    Route::post('/orders/update_confirmation_status', [ShopifyController::class,'update_confirmation_status'])->name('orders.update_confirmation_status');
    Route::get('/orders/update_payment_status/{id?}/{status?}', [ShopifyController::class,'update_payment_status'])->name('orders.update_payment_status');
    Route::post('/orders/resync', [ShopifyController::class,'resync_order'])->name('orders.resync');
    Route::post('/inventory/import', [ShopifyController::class,'import_inventory'])->name('inventory.import');
    Route::post('/shipping-cost/import', [ShopifyController::class,'import_shipping_cost'])->name('shipping-cost.import');
    Route::post('/inventory/review', [ShopifyController::class,'import_inventory_post'])->name('inventory.review');
    Route::any('inventory-transfers', [ShopifyController::class,'inventory_transfers'])->name('inventories.index');
    Route::any('inventory-transfers/{id}', [ShopifyController::class,'show_inventory_transfers'])->name('inventories.show');
    Route::post('/orders/return', [ShopifyController::class,'return_order'])->name('orders.return');
    Route::any('returned-orders', [ShopifyController::class,'returned_orders'])->name('orders.returned');
    Route::any('returned-products-report', [ShopifyController::class,'returned_products_report'])->name('products.returned');
    Route::any('warehouse-products', [ShopifyController::class,'warehouse_products'])->name('shopify.product_warehouse');
    Route::any('returned-orders-report', [ShopifyController::class,'returned_orders_report'])->name('reports.returned');
    Route::any('staff-products-report', [ShopifyController::class,'staff_products_report'])->name('reports.staff_products');
    Route::any('review-na-product/{id}', [ShopifyController::class,'reviewNAProduct'])->name('na.review');
    Route::any('cancelled-orders', [ShopifyController::class,'cancelled_Orders'])->name('prepare.cancelled-orders');
    Route::any('resynced-orders', [ShopifyController::class,'resynced_orders'])->name('prepare.resynced-orders');
    Route::get('export-hold-products', [ShopifyController::class,'export_hold_products'])->name('export-hold-products');
    Route::get('shortage/make-call/{shortage_id}', [ShopifyController::class,'make_call'])->name('shortage.make_call');

    Route::get('settings', [SettingsController::class, 'settings'])->name('settings');
    Route::prefix('two_factor_auth')->group(function () {
        Route::get('/', [LoginSecurityController::class, 'show2faForm'])->name('show2FASettings');
        Route::post('generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
        Route::post('enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');
        Route::post('disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');
        Route::middleware('two_fa')->post('/2faVerify', function () { return redirect(URL()->previous()); })->name('2faVerify');
    });

    Route::middleware(['permission:write-members|read-members', 'is_public_app'])->group(function () {
        Route::resource('members', TeamController::class);
    });

        Route::get('stock-requests/create', [BranchController::class, 'createStockRequest'])->name('stock_requests.create');
        Route::post('stock-requests/store', [BranchController::class, 'storeStockRequest'])->name('stock_requests.store');
        Route::get('stock-requests', [BranchController::class, 'stockRequests'])->name('stock_requests.index');
        Route::get('stock-requests/{id}/show', [BranchController::class, 'showStockRequest'])->name('stock_requests.show');
        Route::any('stock-requests/{id}/edit', [BranchController::class, 'editStockRequest'])->name('stock_requests.edit');
        Route::any('stock-requests/{id}/download', [BranchController::class, 'downloadStockRequest'])->name('stock_requests.download');

        Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/export-data', [TicketController::class, 'exportData'])->name('tickets.exportData');

        Route::get('tickets/new', [TicketController::class, 'new'])->name('tickets.new');
        Route::get('tickets/report', [TicketController::class, 'report'])->name('tickets.report');
        Route::get('tickets/reason/report', [TicketController::class, 'reason'])->name('reason.report');
        Route::get('/tickets/reason/{reason}', [TicketController::class, 'showReason'])->name('tickets.showReason');
        Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.add');
        Route::get('/check-order', [TicketController::class, 'checkOrder'])->name('checkOrder');
        Route::get('/check-ticket', [TicketController::class, 'checkTicket'])->name('checkTicket');
        Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::post('tickets/{ticketUserId}/open', [TicketController::class, 'open'])->name('tickets.open');
        Route::get('tickets/{ticketUserId}', [TicketController::class, 'show'])->name('tickets.show');
        Route::delete('tickets/{ticketUserId}', [TicketController::class, 'destroy'])->name('tickets.destroy');
        Route::post('tickets/{ticketUserId}/comments', [TicketController::class, 'store_comment'])->name('comments.store');
        Route::post('/tickets/{ticketUserId}/check-as-done', [TicketController::class, 'checkAsDone'])->name('tickets.checkAsDone');
        Route::post('/tickets/{ticketUserId}/check-as-done-return', [TicketController::class, 'checkAsDoneAndReturn'])->name('tickets.checkAsDoneAndReturn');
        Route::post('/tickets/{ticketUserId}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
        Route::post('/notifications/mark-as-read/{id}', [TicketController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/comments/mark-as-read/{ticketId}', [TicketController::class, 'markAsReadcomment'])->name('comments.markAsRead');
        Route::post('tickets/{ticketUserId}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
        Route::post('/tickets/confirm-return', [TicketController::class, 'confirmReturn'])->name('tickets.confirmReturn');



        ///////////////////////////Ads////////////////////////
        Route::get('ads', [AdsController::class, 'index'])->name('ads.index');
        Route::get('collections', [AdsController::class, 'collection'])->name('ads.collection');
        Route::get('ads/create', [AdsController::class, 'create'])->name('ads.create');
        Route::get('/search-product', [AdsController::class, 'searchProductByTitle']); // For searching products by title
        Route::get('/product-details/{id}', [AdsController::class, 'getProductDetailsById']); // For fetching product details by ID
        Route::get('/get-collections', [AdsController::class, 'getCollections']);
        Route::post('/collections', [AdsController::class, 'store_collection']);
        Route::post('/ads', [AdsController::class, 'store']);
        Route::post('/ads/{adId}/update', [AdsController::class, 'updateAd']);
        Route::get('/get-product-variant-count', [AdsController::class, 'getProductVariantCount']);
        Route::post('/collections/store', [AdsController::class, 'storeCollection'])->name('collections.store');
        Route::get('/collections/show/{id}', [AdsController::class, 'showCollection'])->name('collections.show');
        Route::get('/collections/edit/{id}', [AdsController::class, 'editCollection'])->name('collections.edit');
        Route::get('/ads/{adId}/{collectionId}/edit', [AdsController::class, 'editAd'])->name('ads.edit');
        Route::put('/collections/edit/{id}', [AdsController::class, 'updateCollection'])->name('collections.update');
        Route::delete('/collections/delete/{id}', [AdsController::class, 'destroyCollection'])->name('collections.destroy');
        Route::post('/ads/{adId}/{collectionId}/start', [AdsController::class, 'start'])->name('ads.start');
        Route::post('/ads/{adId}/{collectionId}/stop', [AdsController::class, 'stop'])->name('ads.stop');
        Route::delete('/ads/{adId}/{collectionId}/destroy', [AdsController::class, 'destroy'])->name('ads.destroy');
        Route::post('/ads/{adId}/{collectionId}/ask-to-close', [AdsController::class, 'askToClose'])->name('ads.ask.to.close');
        Route::post('/ads/{adId}/{collectionId}/cancel-close', [AdsController::class, 'cancelClose'])->name('ads.cancel.close');
        Route::post('/ads/{adId}/{collectionId}/withdraw', [AdsController::class, 'withdraw'])->name('ads.withdraw.ask.to.close');
        Route::get('/ads/product/{id}/variant', [AdsController::class, 'variant'])->name('ads.product.variant');
    
    

});

// /shopify/auth
Route::prefix('shopify/auth')->group(function () {
    Route::get('/', [InstallationController::class, 'startInstallation']);
    Route::get('redirect', [InstallationController::class, 'handleRedirect'])->name('app_install_redirect');
    Route::get('complete', [InstallationController::class, 'completeInstallation'])->name('app_install_complete');
});

Route::prefix('webhook')->group(function () {
    Route::any('order/created', [WebhooksController::class, 'orderCreated']);
    Route::any('order/updated', [WebhooksController::class, 'orderUpdated']);
    Route::any('product/created', [WebhooksController::class, 'productCreated']);
    Route::any('app/uninstall', [WebhooksController::class, 'appUninstalled']);
    Route::any('shop/updated', [WebhooksController::class, 'shopUpdated']);
});

//Testing scripts
Route::get('configure/webhooks/{id}', [WebhooksController::class, 'configureWebhooks']);
Route::get('delete/webhooks/{id}', [WebhooksController::class, 'deleteWebhooks']);
Route::get('test/docker', [HomeController::class, 'testDocker']);
Route::get('listUsers', [HomeController::class, 'listUsers']);

//Fulfillment Service Routes
Route::prefix('service_callback')->group(function () {
    Route::any('/', [HomeController::class, 'service_callback'])->name('service_callback');
    Route::any('fulfillment_order_notification', [HomeController::class, 'receiveFulfillmentNotification'])->name('receive.fulfillment.notification');
    Route::any('fetch_tracking_numbers ', [HomeController::class, 'fetchTrackingNumbers'])->name('fetch.tracking.numbers');
    Route::any('fetch_stock', [HomeController::class, 'fetchStock'])->name('fetch.stock');
});

//GDPR endpoints
Route::prefix('gdpr')->group(function () {
    Route::any('webhooks/customer_data', [WebhooksController::class, 'returnCustomerData']);
    Route::any('webhooks/customer_data_delete', [WebhooksController::class, 'deleteCustomerData']);
    Route::any('webhooks/shop_data_delete', [WebhooksController::class, 'deleteShopData']);
});
