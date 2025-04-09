<?php $isEmbedded = determineIfAppIsEmbedded() ?>

<aside id="sidebar" class="sidebar" <?php if($isEmbedded): ?> style="background-color:#f1f2f4" <?php endif; ?>>

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item collapsed">
            <a class="nav-link " href="<?php echo e(route('home')); ?>">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <?php if(auth()->user()->role_id == 1 ||
                auth()->user()->role_id == 3 ||
                auth()->user()->role_id == 4 ||
                auth()->user()->role_id == 6): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#operation" data-bs-toggle="collapse" aria-expanded="false"
                    href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Operation</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="operation" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <?php if(auth()->user()->role_id != 6): ?>
                        <?php if(auth()->user()->role_id == 7): ?>
                            <li>
                                <a id="confirm" href="<?php echo e(route('orders.confirm')); ?>">
                                    <i class="bi bi-circle"></i><span>Confirm Orders</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a id="sync" href="<?php echo e(route('shopify.orders')); ?>">
                                <i class="bi bi-circle"></i><span>Sync & Assign Orders</span>
                            </a>
                        </li>
                        <li>
                            <a id="sales" href="<?php echo e(route('sales.all')); ?>">
                                <i class="bi bi-circle"></i><span>POS Order</span>
                            </a>
                        </li>
                        <li>
                            <a id="pending" href="<?php echo e(route('shopify.pending_payment')); ?>">
                                <i class="bi bi-circle"></i><span>Pending Orders</span>
                            </a>
                        </li>
                        <li>
                            <a id="products" href="<?php echo e(route('shopify.products')); ?>">
                                <i class="bi bi-circle"></i><span>Products</span>
                            </a>
                        </li>
                        <li>
                            <a id="variants" href="<?php echo e(route('shopify.product_variants')); ?>">
                                <i class="bi bi-circle"></i><span>Product Variants</span>
                            </a>
                        </li>
                        <li>
                            <a id="coupons" href="<?php echo e(route('coupons')); ?>">
                                <i class="bi bi-circle"></i><span>Coupons</span>
                            </a>
                        </li>
                        <li>
                            <a id="product_warehouse" href="<?php echo e(route('shopify.product_warehouse')); ?>">
                                <i class="bi bi-circle"></i><span>Location Products</span>
                            </a>
                        </li>
                        <li>
                            <a id="locations" href="<?php echo e(route('shopify.locations')); ?>">
                                <i class="bi bi-circle"></i><span>Locations</span>
                            </a>
                        </li>
                        <li>
                            <a id="branch_stock" href="#" onclick="upload_stock()">
                                <i class="bi bi-circle"></i><span>Upload Branches Stock</span>
                            </a>
                        </li>
                        <li>
                            <a id="customers" href="<?php echo e(route('shopify.customers')); ?>">
                                <i class="bi bi-circle"></i><span>Customers</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a id="prepares" href="<?php echo e(route('prepare.all')); ?>">
                            <i class="bi bi-circle"></i><span>All Orders</span>
                        </a>
                    </li>

                    <?php if(auth()->user()->role_id != 6): ?>
                        <li>
                            <a id="reviewed" href="<?php echo e(route('prepare.reviewed')); ?>">
                                <i class="bi bi-circle"></i><span>Ready To Ship</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a id="black" href="<?php echo e(route('blacklist')); ?>">
                            <i class="bi bi-circle"></i><span>Blacklist</span>
                        </a>
                    </li>


                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>

        <?php if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 5): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#preparation" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-box"></i><span>Preparation</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="preparation" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li>
                        <a id="new" href="<?php echo e(route('prepare.new')); ?>">
                            <i class="bi bi-circle"></i><span>New Orders</span>
                        </a>
                    </li>
                    <li>
                        <a id="hold" href="<?php echo e(route('prepare.hold')); ?>">
                            <i class="bi bi-circle"></i><span>Hold Orders</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>
        <?php if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 8 || auth()->user()->role_id == 11): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#pickups" data-bs-toggle="collapse" aria-expanded="false"
                    href="#">
                    <i class="bi bi-table"></i><span>Pickups</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="pickups" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="index" class="nav-link collapsed" href="<?php echo e(route('pickups.index')); ?>">
                            <i class="bi bi-files"></i>
                            <span>Daily Pickups</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returns" class="nav-link collapsed" href="<?php echo e(route('return-pickups.index')); ?>">
                            <i class="bi bi-files"></i>
                            <span>Returns Pickups</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>
        <?php if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 6): ?>
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
                        <a id="resynced" class="nav-link collapsed" href="<?php echo e(route('prepare.resynced-orders')); ?>">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Re-Synced Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returned" class="nav-link collapsed" href="<?php echo e(route('orders.returned')); ?>">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Returned Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="confirmed_returns" class="nav-link collapsed"
                            href="<?php echo e(route('returns.confirmed')); ?>">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Confirmed Returns</span>
                        </a>
                    </li>
                    
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>
        <?php if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 6): ?>
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
                        <a id="transfers"class="nav-link collapsed" href="<?php echo e(route('inventories.index')); ?>">
                            <i class="bi bi-folder-minus"></i>
                            <span>Warehouse Transfers</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>
        <?php if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 6): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#finance" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-coin"></i></i><span>Finance</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="finance" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a id="transactions"class="nav-link collapsed" href="<?php echo e(route('shipping_trx.index')); ?>">
                            <i class="bi bi-folder-minus"></i>
                            <span>Shipping Transactions</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>
        <?php if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#reports" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-files"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="reports" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a id="staff"class="nav-link collapsed" href="<?php echo e(route('reports.staff')); ?>">
                            <i class="bi bi-folder-minus"></i>
                            <span>Staff Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="cancelled" class="nav-link collapsed" href="<?php echo e(route('prepare.cancelled-orders')); ?>">
                            <i class="bi bi-circle"></i><span>Cancelled Orders Report</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returned_report" class="nav-link collapsed" href="<?php echo e(route('reports.returned')); ?>">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Returns Report</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="returned_products" class="nav-link collapsed"
                            href="<?php echo e(route('products.returned')); ?>">
                            <i class="bi bi-arrow-return-right"></i>
                            <span>Returned Products Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="hold_products" class="nav-link collapsed"
                            href="<?php echo e(route('prepare.hold-products')); ?>">
                            <i class="bi bi-circle"></i><span>Hold Products Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="shortage_products" class="nav-link collapsed" href="<?php echo e(route('reports.shortage')); ?>">
                            <i class="bi bi-circle"></i><span>Shortage Orders Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="trx" class="nav-link collapsed"
                            href="<?php echo e(route('branches_stock.transactions')); ?>">
                            <i class="bi bi-circle"></i><span>Branches Stock Transactions</span>
                        </a>
                    </li>
                    <li>
                        <a id="stock" class="nav-link collapsed" href="<?php echo e(route('reports.stock')); ?>">
                            <i class="bi bi-circle"></i><span>Stock Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="registers" class="nav-link collapsed" href="<?php echo e(route('reports.cash_registers')); ?>">
                            <i class="bi bi-circle"></i><span>Cash Registers Report</span>
                        </a>
                    </li>
                    <li>
                        <a id="staff_products" class="nav-link collapsed"
                            href="<?php echo e(route('reports.staff_products')); ?>">
                            <i class="bi bi-circle"></i><span>Staff Product Report</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>
        <?php if(auth()->user()->role_id == 1): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#users" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-person"></i></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="users" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="<?php echo e(route('users.create')); ?>">
                            <i class="bi bi-house"></i>
                            <span>Add New User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="all"class="nav-link collapsed" href="<?php echo e(route('users.all')); ?>">
                            <i class="bi bi-folder-minus"></i>
                            <span>All Users</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>
        <?php if(auth()->user()->role_id != 7): ?>
            <li class="nav-item" id="team">
                <a id="search" class="nav-link collapsed" href="#" onclick="searchh()">
                    <i class="bi bi-search"></i>
                    <span>Find Order</span>
                </a>
            </li><!-- End Contact Page Nav -->
        <?php endif; ?>
        <?php if(auth()->user()->role_id == 8 || auth()->user()->role_id == 1): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#shipping" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-box-seam"></i></i><span>Shipping</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="shipping" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="#" onclick="shipping_trx()">
                            <i class="bi bi-house"></i>
                            <span>Upload Shipping Sheet</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="transactions"class="nav-link collapsed" href="<?php echo e(route('shipping_trx.index')); ?>">
                            <i class="bi bi-folder-minus"></i>
                            <span>Shipping Sheets</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>
        <?php if(Auth::user()->getShopifyStore->isPublic() && auth()->user()->role_id != 7): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['all-access', 'write-members', 'read-members'])): ?>
                <li class="nav-item" id="team">
                    <a class="nav-link collapsed" href="<?php echo e(route('members.index')); ?>">
                        <i class="bi bi-people"></i>
                        <span>My Team</span>
                    </a>
                </li><!-- End Contact Page Nav -->
            <?php endif; ?>
        <?php else: ?>
        <?php endif; ?>

        <?php if(Auth::user()->role->name == "Branches" || auth()->user()->role_id == 1): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#branches" data-bs-toggle="collapse"
                    aria-expanded="false" href="#">
                    <i class="bi bi-shop"></i></i><span>Branches</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="branches" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a id="add" class="nav-link collapsed" href="<?php echo e(route('stock_requests.create')); ?>">
                            <i class="bi bi-house"></i>
                            <span>Request Stock</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="requests"class="nav-link collapsed" href="<?php echo e(route('stock_requests.index')); ?>">
                            <i class="bi bi-folder-minus"></i>
                            <span>All Requests</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>

        <?php if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4 || auth()->user()->role_id == 5 || auth()->user()->role_id == 6 || auth()->user()->role_id == 7 || auth()->user()->role_id == 8): ?>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#ticket" data-bs-toggle="collapse"
            aria-expanded="false" href="#">
                <i class="bi bi-sticky"></i><span>Tickets</span><i class="bi bi-chevron-down ms-auto"></i>            
            </a>
        </li>
        <ul id="ticket" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li class="nav-item">
                <a id="all_tickets"class="nav-link collapsed"  href="<?php echo e(route('tickets.index')); ?>">
                    <i class="bi bi-folder-minus"></i>
                    <span>All Tickets</span>
                </a>
            </li>
            <li class="nav-item">
                <a id="new_tickets"class="nav-link collapsed"  href="<?php echo e(route('tickets.new')); ?>">
                    <i class="bi bi-folder-minus"></i>
                    <span>Pending Tickets</span>
                </a>
            </li>
            <li class="nav-item">
                <a id="report_tickets"class="nav-link collapsed"  href="<?php echo e(route('tickets.report')); ?>">
                    <i class="bi bi-folder-minus"></i>
                    <span>Staff Ticket Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a id="reason_report"class="nav-link collapsed"  href="<?php echo e(route('reason.report')); ?>">
                    <i class="bi bi-folder-minus"></i>
                    <span>Reason Reports</span>
                </a>
            </li>
            
            <?php if(auth()->user()->role_id == 7 || auth()->user()->role_id == 8): ?>

                <li class="nav-item">
                    <a id="add_ticket"class="nav-link collapsed"  href="<?php echo e(route('tickets.add')); ?>">
                        <i class="bi bi-folder-minus"></i>
                        <span>Add Tickets</span>
                    </a>
                </li>
            <?php endif; ?>    
        </ul>

        <?php endif; ?>


        <?php if(auth()->user()->role_id == 1 || auth()->user()->role_id == 9): ?>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#Ad" data-bs-toggle="collapse"
            aria-expanded="false" href="#">
                <i class="bi bi-badge-ad"></i><span>Ads</span><i class="bi bi-chevron-down ms-auto"></i>            
            </a>
        </li>
        <ul id="Ad" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li class="nav-item">
                <a id="all_Ads"class="nav-link collapsed"  href="<?php echo e(route('ads.index')); ?>">
                    <i class="bi bi-folder-minus"></i>
                    <span>All Ads</span>
                </a>
            </li>

            <li class="nav-item">
                <a id="all_Collections"class="nav-link collapsed"  href="<?php echo e(route('ads.collection')); ?>">
                    <i class="bi bi-folder-minus"></i>
                    <span>All Collections</span>
                </a>
            </li>


            <li class="nav-item">
                <a id="add_Ad"class="nav-link collapsed"  href="<?php echo e(route('ads.create')); ?>">
                    <i class="bi bi-folder-minus"></i>
                    <span>Add Ads</span>
                </a>
            </li>
              
        </ul>

        <?php endif; ?>


        <li class="nav-item">
            <a class="nav-link collapsed"
                onclick="event.preventDefault(); document.getElementById('logout-user').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <form id="logout-user" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none"
                    style="display: none">
                    <?php echo csrf_field(); ?>
                </form>
                <span>Sign Out</span>
            </a>
        </li><!-- End Blank Page Nav -->

    </ul>

</aside>
<?php echo $__env->make('modals.aside_modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/layouts/aside.blade.php ENDPATH**/ ?>