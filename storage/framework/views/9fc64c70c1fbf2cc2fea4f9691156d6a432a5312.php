<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
            <a class="nav-link nav-icon search-bar-toggle " href="#">
                <i class="bi bi-search"></i>
            </a>
        </li><!-- End Search Icon-->

        <?php if(auth()->user()->role_id == 7 || auth()->user()->role_id == 8): ?>
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <?php
                        // Calculate total unread notifications
                        $totalUnread = $tickets->whereNull('read_at')->count() + $unreadCommentsCount; // Total unread notifications
                    ?>
                    <?php if($totalUnread > 0): ?>
                        <span class="badge bg-primary badge-number"><?php echo e($totalUnread); ?></span>
                    <?php endif; ?>
                </a><!-- End Notification Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" aria-labelledby="notificationDropdown">
                    <li class="dropdown-header">
                        You have <?php echo e($totalUnread); ?> new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Display the new notification message if it exists -->
                    <?php if($newNotification): ?>
                        <li class="notification-item">
                            <i class="bi bi-info-circle text-primary"></i>
                            <div>
                                <a href="<?php echo e(route('tickets.show', $ticketUserId)); ?>" class="text-primary notification-link" data-id="<?php echo e($ticketUserId); ?>" data-type="comment">
                                    <h4><?php echo e($newNotification); ?></h4>
                                    <small><?php echo e($commentdate->diffForHumans()); ?></small> <!-- Format comment date -->
                                </a>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    <?php endif; ?>
                    <!-- List the tickets in the notification dropdown -->
                    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="notification-item">
                            <i class="bi bi-info-circle text-primary"></i>
                            <div>
                                <a  class="text-primary notification-link" data-id="<?php echo e($ticket->id); ?>" data-type="ticket">
                                    <h4>New Tickets Added To <b>#<?php echo e($ticket->order_number); ?></b></h4>
                                </a>
                                <h4>
                                    <a class="text-primary notification-link" data-id="<?php echo e($ticket->id); ?>" data-type="ticket">
                                        <?php echo e(ucfirst($ticket->ticket->type)); ?> Ticket
                                    </a>
                                </h4>
                                <p>Added by: <?php echo e($ticket->user->name); ?></p>
                                <p><?php echo e($ticket->created_at->diffForHumans()); ?></p>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul><!-- End Notification Dropdown Items -->
            </li>
        <?php endif; ?>



        <?php if(auth()->user()->role_id == 11 || auth()->user()->role_id == 1): ?>
            <li class="nav-item"><a class="btn-primary btn-sm" href="<?php echo e(route('sale.pos')); ?>"><i
                        class="dripicons-shopping-bag"></i><span>
                        POS</span></a></li>
        <?php endif; ?>
        <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                
                <span class="d-none d-md-block dropdown-toggle ps-2">Menu</span>
            </a><!-- End Profile Iamge Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                <li class="dropdown-header">
                    <h6>
                        <?php if(Auth::check()): ?>
                            <?php echo e(Auth::user()->name); ?>

                        <?php endif; ?>
                    </h6>
                    
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('my.profile')); ?>">
                        <i class="bi bi-person"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('settings')); ?>">
                        <i class="bi bi-gear"></i>
                        <span>Account Settings</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <i class="bi bi-question-circle"></i>
                        <span>Need Help?</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" style="cursor:pointer"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sign Out</span>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none"
                            style="display: none">
                            <?php echo csrf_field(); ?>
                        </form>
                    </a>
                </li>

            </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

    </ul>
</nav>
<?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/layouts/nav.blade.php ENDPATH**/ ?>