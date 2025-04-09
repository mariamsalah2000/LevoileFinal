<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
            <a class="nav-link nav-icon search-bar-toggle " href="#">
                <i class="bi bi-search"></i>
            </a>
        </li><!-- End Search Icon-->

        @if (auth()->user()->role_id == 7 || auth()->user()->role_id == 8)
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    @php
                        // Calculate total unread notifications
                        $totalUnread = $tickets->whereNull('read_at')->count() + $unreadCommentsCount; // Total unread notifications
                    @endphp
                    @if($totalUnread > 0)
                        <span class="badge bg-primary badge-number">{{ $totalUnread }}</span>
                    @endif
                </a><!-- End Notification Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" aria-labelledby="notificationDropdown">
                    <li class="dropdown-header">
                        You have {{ $totalUnread }} new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Display the new notification message if it exists -->
                    @if ($newNotification)
                        <li class="notification-item">
                            <i class="bi bi-info-circle text-primary"></i>
                            <div>
                                <a href="{{ route('tickets.show', $ticketUserId) }}" class="text-primary notification-link" data-id="{{ $ticketUserId }}" data-type="comment">
                                    <h4>{{ $newNotification }}</h4>
                                    <small>{{ $commentdate->diffForHumans() }}</small> <!-- Format comment date -->
                                </a>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    @endif
                    <!-- List the tickets in the notification dropdown -->
                    @foreach($tickets as $ticket)
                        <li class="notification-item">
                            <i class="bi bi-info-circle text-primary"></i>
                            <div>
                                <a  class="text-primary notification-link" data-id="{{ $ticket->id }}" data-type="ticket">
                                    <h4>New Tickets Added To <b>#{{ $ticket->order_number }}</b></h4>
                                </a>
                                <h4>
                                    <a class="text-primary notification-link" data-id="{{ $ticket->id }}" data-type="ticket">
                                        {{ ucfirst($ticket->ticket->type) }} Ticket
                                    </a>
                                </h4>
                                <p>Added by: {{ $ticket->user->name }}</p>
                                <p>{{ $ticket->created_at->diffForHumans() }}</p>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    @endforeach
                </ul><!-- End Notification Dropdown Items -->
            </li>
        @endif



        @if (auth()->user()->role_id == 11 || auth()->user()->role_id == 1)
            <li class="nav-item"><a class="btn-primary btn-sm" href="{{ route('sale.pos') }}"><i
                        class="dripicons-shopping-bag"></i><span>
                        POS</span></a></li>
        @endif
        <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                {{-- <img src="" alt="Profile" class="rounded-circle"> --}}
                <span class="d-none d-md-block dropdown-toggle ps-2">Menu</span>
            </a><!-- End Profile Iamge Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                <li class="dropdown-header">
                    <h6>
                        @if (Auth::check())
                            {{ Auth::user()->name }}
                        @endif
                    </h6>
                    {{-- <span>Shopify</span> --}}
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('my.profile') }}">
                        <i class="bi bi-person"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('settings') }}">
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
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"
                            style="display: none">
                            @csrf
                        </form>
                    </a>
                </li>

            </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

    </ul>
</nav>
