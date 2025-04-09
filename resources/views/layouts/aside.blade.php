@php $isEmbedded = determineIfAppIsEmbedded() @endphp

<aside id="sidebar" class="sidebar" @if ($isEmbedded) style="background-color:#f1f2f4" @endif>

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item collapsed">
            <a class="nav-link " href="{{ route('home') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        @if (auth()->user()->role_id == 1 ||
                auth()->user()->role_id == 3 ||
                auth()->user()->role_id == 4 ||
                auth()->user()->role_id == 6||
                auth()->user()->role_id == 11)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#operation" data-bs-toggle="collapse" aria-expanded="false"
                    href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Operation</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="operation" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    @if (auth()->user()->role_id != 6&&auth()->user()->role_id != 11)
                        @if (auth()->user()->role_id == 7)
                            <li>
                                <a id="confirm" href="{{ route('orders.confirm') }}">
                                    <i class="bi bi-circle"></i><span>Confirm Orders</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a id="sync" href="{{ route('shopify.orders') }}">
                                <i class="bi bi-circle"></i><span>Sync & Assign Orders</span>
                            </a>
                        </li>
                        @if(auth()->user()->role_id == 6 || auth()->user()->role_id == 1 || auth()->user()->role_id == 5)
                        <li>
                            <a id="sales" href="{{ route('sales.all') }}">
                                <i class="bi bi-circle"></i><span>POS Order</span>
                            </a>
                        </li>
                        <li>
                            <a id="pending" href="{{ route('shopify.pending_payment') }}">
                                <i class="bi bi-circle"></i><span>Pending Orders</span>
                            </a>
                        </li>
                        <li>
                            <a id="shortage_orders" href="{{ route('shortage_orders') }}">
                                <i class="bi bi-circle"></i><span>Shortage Orders</span>
                            </a>
                        </li>
                        <li>
                            <a id="products" href="{{ route('shopify.products') }}">
                                <i class="bi bi-circle"></i><span>Products</span>
                            </a>
                        </li>
                        <li>
                            <a id="variants" href="{{ route('shopify.product_variants') }}">
                                <i class="bi bi-circle"></i><span>Product Variants</span>
                            </a>
                        </li>
                        <li>
                            <a id="coupons" href="{{ route('coupons') }}">
                                <i class="bi bi-circle"></i><span>Coupons</span>
                            </a>
                        </li>
                        <li>
                            <a id="product_warehouse" href="{{ route('shopify.product_warehouse') }}">
                                <i class="bi bi-circle"></i><span>Location Products</span>
                            </a>
                        </li>
                        <li>
                            <a id="locations" href="{{ route('shopify.locations') }}">
                                <i class="bi bi-circle"></i><span>Locations</span>
                            </a>
                        </li>
                        <li>
                            <a id="branch_stock" href="#" onclick="upload_stock()">
                                <i class="bi bi-circle"></i><span>Upload Branches Stock</span>
                            </a>
                        </li>
                        <li>
                            <a id="customers" href="{{ route('shopify.customers') }}">
                                <i class="bi bi-circle"></i><span>Customers</span>
                            </a>
                        </li>
                        @endif
                    @endif

                    <li>
                        <a id="prepares" href="{{ route('prepare.all') }}">
                            <i class="bi bi-circle"></i><span>All Orders</span>
                        </a>
                    </li>

                    @if (auth()->user()->role_id != 6&&auth()->user()->role_id != 11)
                        <li>
                            <a id="reviewed" href="{{ route('prepare.reviewed') }}">
                                <i class="bi bi-circle"></i><span>Ready To Ship</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a id="black" href="{{ route('blacklist') }}">
                            <i class="bi bi-circle"></i><span>Blacklist</span>
                        </a>
                    </li>


                </ul>
            </li><!-- End Components Nav -->
        @endif

        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 5)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#preparation" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-box"></i><span>Preparation</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="preparation" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li>
                        <a id="new" href="{{ route('prepare.new') }}">
                            <i class="bi bi-circle"></i><span>New Orders</span>
                        </a>
                    </li>
                    <li>
                        <a id="hold" href="{{ route('prepare.hold') }}">
                            <i class="bi bi-circle"></i><span>Hold Orders</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        @endif
        @if (auth()->user()->role_id == 1 ||
                auth()->user()->role_id == 2 ||
                auth()->user()->role_id == 8 ||
                auth()->user()->role_id == 11)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#pickups" data-bs-toggle="collapse" aria-expanded="false"
                    href="#">
                    <i class="bi bi-table"></i><span>Pickups</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="pickups" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="index" class="nav-link collapsed" href="{{ route('pickups.index') }}">
                            <i class="bi bi-files"></i>
                            <span>Daily Pickups</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returns" class="nav-link collapsed" href="{{ route('return-pickups.index') }}">
                            <i class="bi bi-files"></i>
                            <span>Returns Pickups</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        @endif
        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 6)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#edited" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-arrow-repeat"></i><span>Edited Orders</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="edited" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="resync" class="nav-link collapsed" href="#" onclick="resync()">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Re-Sync Order</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="resynced" class="nav-link collapsed" href="{{ route('prepare.resynced-orders') }}">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Re-Synced Orders</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        @endif
        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 4 || auth()->user()->role_id == 6|| auth()->user()->role_id == 11)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#returned_orders" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-arrow-down"></i><span>Returns</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="returned_orders" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="add-return" class="nav-link collapsed" href="#" onclick="addReturn()">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Confirm New Return</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returned" class="nav-link collapsed" href="{{ route('orders.returned') }}">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Ready Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="confirmed_returns" class="nav-link collapsed"
                            href="{{ route('returns.confirmed') }}">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Confirmed Returns</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="finance_return_collections"class="nav-link collapsed" href="{{ route('return_collections.finance') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>Return Collections</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a id="return_trx" class="nav-link collapsed" href="{{ route('returns.confirmed') }}">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Returns Transactions Report</span>
                        </a>
                    </li> --}}
                </ul>
            </li><!-- End Components Nav -->
        @endif
        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 6)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#warehouse" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-house"></i></i><span>Warehouse Products</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="warehouse" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="#" onclick="warehouse()">
                            <i class="bi bi-house"></i>
                            <span>Add Warehouse Transfer</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="transfers"class="nav-link collapsed" href="{{ route('inventories.index') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>Warehouse Transfers</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        @endif
        @if (auth()->user()->role_id == 1 ||
                auth()->user()->role_id == 2 ||
                auth()->user()->role_id == 11 ||
                auth()->user()->role_id == 6)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#finance" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-coin"></i></i><span>Finance</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="finance" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a id="transactions"class="nav-link collapsed" href="{{ route('shipping_trx.index') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>Shipping Transactions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="#" onclick="shipping_trx()">
                            <i class="bi bi-house"></i>
                            <span>Upload Shipping Transaction</span>
                        </a>
                    </li>
                    
                    
                    <li class="nav-item">
                        <a id="refunds"class="nav-link collapsed" href="{{ route('refunds') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>Refund Orders</span>
                        </a>
                    </li>

                </ul>
            </li><!-- End Components Nav -->
        @endif
        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#reports" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-files"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="reports" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a id="staff"class="nav-link collapsed" href="{{ route('reports.staff') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>Staff Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="cancelled" class="nav-link collapsed" href="{{ route('prepare.cancelled-orders') }}">
                            <i class="bi bi-circle"></i><span>Cancelled Orders Report</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returned_report" class="nav-link collapsed" href="{{ route('reports.returned') }}">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Returns Report</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returned_products" class="nav-link collapsed"
                            href="{{ route('products.returned') }}">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Returned Products Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="hold_products" class="nav-link collapsed"
                            href="{{ route('prepare.hold-products') }}">
                            <i class="bi bi-circle"></i><span>Hold Products Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="shortage_products" class="nav-link collapsed" href="{{ route('reports.shortage') }}">
                            <i class="bi bi-circle"></i><span>Shortage Orders Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="shipped_orders" class="nav-link collapsed" href="{{ route('reports.shipped') }}">
                            <i class="bi bi-circle"></i><span>Shipped Orders Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="sales_report" class="nav-link collapsed" href="{{ route('reports.sales') }}">
                            <i class="bi bi-circle"></i><span>Sales Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="trx" class="nav-link collapsed"
                            href="{{ route('branches_stock.transactions') }}">
                            <i class="bi bi-circle"></i><span>Branches Stock Transactions</span>
                        </a>
                    </li>
                    <li>
                        <a id="stock" class="nav-link collapsed" href="{{ route('reports.stock') }}">
                            <i class="bi bi-circle"></i><span>Stock Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="registers" class="nav-link collapsed" href="{{ route('reports.cash_registers') }}">
                            <i class="bi bi-circle"></i><span>Cash Registers Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="staff_products" class="nav-link collapsed"
                            href="{{ route('reports.staff_products') }}">
                            <i class="bi bi-circle"></i><span>Staff Product Report</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->

            <li class="nav-item" id="team">
                <a id="shipping_cost" class="nav-link collapsed" href="#" onclick="upload_shipping_cost()">
                    <i class="bi bi-upload"></i>
                    <span>Upload Shipping Cost</span>
                </a>
            </li><!-- End Contact Page Nav -->
        @endif
        @if (auth()->user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#users" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-person"></i></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="users" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="{{ route('users.create') }}">
                            <i class="bi bi-house"></i>
                            <span>Add New User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="all"class="nav-link collapsed" href="{{ route('users.all') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>All Users</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        @endif
        @if (auth()->user()->role_id != 7)
            <li class="nav-item" id="team">
                <a id="search" class="nav-link collapsed" href="#" onclick="searchh()">
                    <i class="bi bi-search"></i>
                    <span>Find Order</span>
                </a>
            </li><!-- End Contact Page Nav -->
        @endif
        @if (auth()->user()->role_id == 8 || auth()->user()->role_id == 1 || auth()->user()->role_id == 11|| auth()->user()->role_id == 4)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#shipping" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-box-seam"></i></i><span>Shipping</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="shipping" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a id="collections"class="nav-link collapsed" href="{{ route('collections.index') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>Shipping Collections</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="#" onclick="collections()">
                            <i class="bi bi-house"></i>
                            <span>Upload Collection</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returncollections"class="nav-link collapsed" href="{{ route('return_collections.index') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>Return Collections</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="#" onclick="return_collections()">
                            <i class="bi bi-house"></i>
                            <span>Upload Return Collection</span>
                        </a>
                    </li>
                    
                </ul>
            </li><!-- End Components Nav -->
        @endif
        @if (Auth::user()->getShopifyStore->isPublic() && auth()->user()->role_id != 7)
            @canany(['all-access', 'write-members', 'read-members'])
                <li class="nav-item" id="team">
                    <a class="nav-link collapsed" href="{{ route('members.index') }}">
                        <i class="bi bi-people"></i>
                        <span>My Team</span>
                    </a>
                </li><!-- End Contact Page Nav -->
            @endcanany
        @else
        @endif

        @if (Auth::user()->role->name == 'Branches' || auth()->user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#branches" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-shop"></i></i><span>Branches</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="branches" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="{{ route('stock_requests.create') }}">
                            <i class="bi bi-house"></i>
                            <span>Request Stock</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="requests"class="nav-link collapsed" href="{{ route('stock_requests.index') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>All Requests</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        @endif

        @if (auth()->user()->role_id == 1 ||
                auth()->user()->role_id == 2 ||
                auth()->user()->role_id == 3 ||
                auth()->user()->role_id == 4 ||
                auth()->user()->role_id == 5 ||
                auth()->user()->role_id == 6 ||
                auth()->user()->role_id == 7 ||
                auth()->user()->role_id == 8)

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#ticket" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-sticky"></i><span>Tickets</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
            </li>
            <ul id="ticket" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li class="nav-item">
                    <a id="all_tickets"class="nav-link collapsed" href="{{ route('tickets.index') }}">
                        <i class="bi bi-folder-minus"></i>
                        <span>All Tickets</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="new_tickets"class="nav-link collapsed" href="{{ route('tickets.new') }}">
                        <i class="bi bi-folder-minus"></i>
                        <span>Pending Tickets</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="report_tickets"class="nav-link collapsed" href="{{ route('tickets.report') }}">
                        <i class="bi bi-folder-minus"></i>
                        <span>Staff Ticket Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="reason_report"class="nav-link collapsed" href="{{ route('reason.report') }}">
                        <i class="bi bi-folder-minus"></i>
                        <span>Reason Reports</span>
                    </a>
                </li>

                @if (auth()->user()->role_id == 7 || auth()->user()->role_id == 8 || auth()->user()->role_id == 6)
                    <li class="nav-item">
                        <a id="add_ticket"class="nav-link collapsed" href="{{ route('tickets.add') }}">
                            <i class="bi bi-folder-minus"></i>
                            <span>Add Tickets</span>
                        </a>
                    </li>
                @endif
            </ul>

        @endif


        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 9 || auth()->user()->role_id == 13)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#Ad" data-bs-toggle="collapse" aria-expanded="false"
                    href="#">
                    <i class="bi bi-badge-ad"></i><span>Ads</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
            </li>
            <ul id="Ad" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li class="nav-item">
                    <a id="all_Ads"class="nav-link collapsed" href="{{ route('ads.index') }}">
                        <i class="bi bi-folder-minus"></i>
                        <span>All Ads</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a id="all_Collections"class="nav-link collapsed" href="{{ route('ads.collection') }}">
                        <i class="bi bi-folder-minus"></i>
                        <span>All Collections</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a id="add_Ad"class="nav-link collapsed" href="{{ route('ads.create') }}">
                        <i class="bi bi-folder-minus"></i>
                        <span>Add Ads</span>
                    </a>
                </li>

            </ul>
        @endif


        <li class="nav-item">
            <a class="nav-link collapsed"
                onclick="event.preventDefault(); document.getElementById('logout-user').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <form id="logout-user" action="{{ route('logout') }}" method="POST" class="d-none"
                    style="display: none">
                    @csrf
                </form>
                <span>Sign Out</span>
            </a>
        </li><!-- End Blank Page Nav -->

    </ul>

</aside>
@include('modals.aside_modals')
