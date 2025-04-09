@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Back Button and Header -->
        <div class="d-flex mb-4">
            <a href="{{ route('tickets.index') }}" class="btn btn-light me-2" style="font-size: 1.5rem;">
                <i class="bi bi-arrow-left" style="font-size: 1.5rem; font-weight: bold;"></i>
            </a>
            <h1>Ticket Details</h1>
        </div>

        <div class="row">
            <!-- LHS: Ticket Details -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Ticket Details</h5>
                        <p><strong>Type:</strong> {{ ucfirst($ticketUser->ticket->type) }}</p>
                        <p><strong>User:</strong> {{ $ticketUser->user->name }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($ticketUser->status) }}</p>
                        <p><strong>Created At:</strong> {{ $ticketUser->created_at->format('d M Y, H:i') }}</p>
                        <p><strong>Note:</strong> {{ str_replace('_', ' ', $ticketUser->content) }}</p>
                        @if (auth()->user()->role_id == 7 || auth()->user()->role_id == 6|| auth()->user()->role_id == 1)
                            @if ($ticketUser->is_asked_to_close == 1 && $ticketUser->status === 'in progress')
                                <p><strong>Request From Shipping:</strong> Shipping Company is asked to Close Ticket.</p>
                            @endif
                            @if ($ticketUser->is_returned == 1)
                                <p><strong>Response From Shipping:</strong> Order is Successfully Returned</p>
                            @endif
                        @endif
                        @if (auth()->user()->role_id == 8)
                            @if ($ticketUser->is_asked_to_return == 1)
                                <p><strong>Request From Levoile:</strong> Levoile asking to return order.</p>
                            @endif
                        @endif

                        <!-- Checkbox to mark as done-->
                        <!-- <div class="d-flex">
                                                                                                                                                                @if (
                                                                                                                                                                    ($ticketUser->status === 'in progress' && $ticketUser->user_id == auth()->user()->id) ||
                                                                                                                                                                        ($ticketUser->status === 'in progress' && (auth()->user()->role_id == 7 || auth()->user()->role_id == 6)))
    <form action="{{ route('tickets.checkAsDone', $ticketUser->id) }}" method="POST" class="mt-3 me-3">
                                                                                                                                                                        @csrf
                                                                                                                                                                        <div class="form-check">
                                                                                                                                                                            <input type="checkbox" name="done" class="form-check-input" id="checkAsDone" required>
                                                                                                                                                                            <label class="form-check-label" for="checkAsDone">Check as Done</label>
                                                                                                                                                                        </div>
                                                                                                                                                                        <button type="submit" class="btn btn-success mt-2">Update Status</button>
                                                                                                                                                                    </form>
    @endif
                                                                                                                                                                 Checkbox to mark as done and create return

                                                                                                                                                                @if ($ticketUser->status === 'in progress' && (auth()->user()->role_id == 7 || auth()->user()->role_id == 6))
    <form action="{{ route('tickets.checkAsDoneAndReturn', $ticketUser->id) }}" method="POST" class="mt-3">
                                                                                                                                                                        @csrf
                                                                                                                                                                        <div class="form-check">
                                                                                                                                                                            <input type="checkbox" name="done" class="form-check-input" id="checkAsDoneAndDone" required>
                                                                                                                                                                            <label class="form-check-label" for="checkAsDoneAndDone">Create Return</label>
                                                                                                                                                                        </div>
                                                                                                                                                                        <button type="submit" class="btn btn-success mt-2">Update Status</button>
                                                                                                                                                                    </form>
    @endif
                                                                                                                                                             </div> -->

                        <!-- Checkbox to mark as reopen from admin-->

                        @if ($ticketUser->status === 'done' && auth()->user()->role_id == 1)
                            <form action="{{ route('tickets.reopen', $ticketUser->id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="form-check">
                                    <input type="checkbox" name="reopen" class="form-check-input" id="reopen" required>
                                    <label class="form-check-label" for="reopen">Reopen Ticket</label>
                                </div>
                                <button type="submit" class="btn btn-success mt-2">Update Status</button>
                            </form>
                        @endif

                        <!-- start all Done Scenarios -->
                        <!-- @if ($ticketUser->status === 'done')
    is asked to return scenarios start for role 7
                                                                                                                                                                @if (
                                                                                                                                                                    (auth()->user()->role_id == 7 || auth()->user()->role_id == 6) &&
                                                                                                                                                                        $ticketUser->is_asked_to_return == 0 &&
                                                                                                                                                                        $ticketUser->is_returned == 0)
    <form action="{{ route('tickets.updateStatus', $ticketUser->id) }}" method="POST" class="mt-3">
                                                                                                                                                                        @csrf
                                                                                                                                                                        <div class="form-check">
                                                                                                                                                                            <input type="checkbox" name="create_return" class="form-check-input" id="createReturn" required>
                                                                                                                                                                            <label class="form-check-label" for="createReturn">Create Return</label>
                                                                                                                                                                        </div>
                                                                                                                                                                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                                                                                                                                    </form>
    @endif
                                                                                                                                                                @if ((auth()->user()->role_id == 7 || auth()->user()->role_id == 6) && $ticketUser->is_asked_to_return == 1)
    <form action="{{ route('tickets.updateStatus', $ticketUser->id) }}" method="POST" class="mt-3">
                                                                                                                                                                        @csrf
                                                                                                                                                                        <div class="form-check">
                                                                                                                                                                            <input type="checkbox" name="cancel_return" class="form-check-input" id="cancelReturn" required>
                                                                                                                                                                            <label class="form-check-label" for="cancelReturn">Cancel Request Return</label>
                                                                                                                                                                        </div>
                                                                                                                                                                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                                                                                                                                    </form>
    @endif
                                                                                                                                                               is asked to return scenarios end for role 7
                                                                                                                                                               confirm return scenarios end for role 8 start
                                                                                                                                                                @if (auth()->user()->role_id == 8 && $ticketUser->is_asked_to_return == 1)
    <form action="{{ route('tickets.updateStatus', $ticketUser->id) }}" method="POST" class="mt-3">
                                                                                                                                                                        @csrf
                                                                                                                                                                            <div class="form-check">
                                                                                                                                                                                <input type="checkbox" name="confirm_return" class="form-check-input" id="confirmReturn" required>
                                                                                                                                                                                <label class="form-check-label" for="confirmReturn">Confirm Return</label>
                                                                                                                                                                            </div>
                                                                                                                                                                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                                                                                                                                    </form>
    @endif
                                                                                                                                                                confirm return scenarios end for role 8 end
    @endif -->

                        <!-- start all in progress Scenarios -->

                        <!-- all asked to close scenario from 8 start -->
                        <!-- @if ($ticketUser->status === 'in progress' && auth()->user()->role_id == 8 && $ticketUser->is_asked_to_close == 0)
    <form action="{{ route('tickets.updateStatus', $ticketUser->id) }}" method="POST" class="mt-3">
                                                                                                                                                                    @csrf
                                                                                                                                                                    <div class="form-check">
                                                                                                                                                                        <input type="checkbox" name="ask_to_close" class="form-check-input" id="askToClose" required>
                                                                                                                                                                        <label class="form-check-label" for="askToClose">Ask to Close Ticket</label>
                                                                                                                                                                    </div>
                                                                                                                                                                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                                                                                                                                </form>
    @endif -->
                        <!-- @if ($ticketUser->status === 'in progress' && auth()->user()->role_id == 8 && $ticketUser->is_asked_to_close == 1)
    <form action="{{ route('tickets.updateStatus', $ticketUser->id) }}" method="POST" class="mt-3">
                                                                                                                                                                    @csrf
                                                                                                                                                                    <div class="form-check">
                                                                                                                                                                        <input type="checkbox" name="cancel_ask_to_close" class="form-check-input" id="cancelaskToClose" required>
                                                                                                                                                                        <label class="form-check-label" for="cancelaskToClose">Cancel Ask to Close Ticket</label>
                                                                                                                                                                    </div>
                                                                                                                                                                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                                                                                                                                </form>
    @endif -->
                        <!-- all asked to close scenario from 8 end -->

                        <!-- @if (
                            $ticketUser->status === 'in progress' &&
                                (auth()->user()->role_id == 7 || auth()->user()->role_id == 6) &&
                                $ticketUser->is_asked_to_close == 1)
    <form action="{{ route('tickets.updateStatus', $ticketUser->id) }}" method="POST" class="mt-3" id="closeRequestForm">
                                                                                                                                                                        @csrf
                                                                                                                                                                            <div class="form-check">
                                                                                                                                                                                <input type="checkbox" name="accept_shipping" class="form-check-input" id="acceptShipping">
                                                                                                                                                                                <label class="form-check-label" for="acceptShipping">Accept Close Request</label>
                                                                                                                                                                            </div>
                                                                                                                                                                            <div class="form-check">
                                                                                                                                                                                <input type="checkbox" name="refuse_shipping" class="form-check-input" id="refuseShipping">
                                                                                                                                                                                <label class="form-check-label" for="refuseShipping">Refuse Close Request</label>
                                                                                                                                                                            </div>
                                                                                                                                                                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                                                                                                                                    </form>
    @endif -->
                    </div>
                </div>
            </div>

            <!-- RHS: Order Details -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Order Information</h5>

                        @php
                            // Fetch the order details using the order_number in the ticketUser
                            $order = \App\Models\Order::where('order_number', $ticketUser->order_number)->first();
                            $customer = json_decode($order->customer);
                            $shipping = $order->total_shipping_price_set;
                            $shipping_address = $order['shipping_address'];
                        @endphp

                        @if ($order)
                            <p><strong>Order Date:</strong> {{ $order->created_at_date }}</p>
                            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                            <p><strong>Contact Email:</strong> {{ $order->contact_email }}</p>
                            <p><strong>Client Name:</strong>
                                {{ isset($customer->first_name) ? $customer->first_name : $shipping_address['first_name'] }}
                                {{ isset($customer->last_name) ? $customer->last_name : $shipping_address['last_name'] }}
                            </p>
                            <p><strong>Phone:</strong>
                                {{ (isset($customer->phone) ? $customer->phone : isset($shipping_address['phone'])) ? $shipping_address['phone'] : 'NA' }}
                            </p>
                            <p><strong>Address:</strong>
                                {{ $customer?->default_address->address1 ?? $shipping_address['address1'] }},
                                {{ $customer?->default_address->city ?? $shipping_address['city'] }},
                                {{ $customer?->default_address->province ?? $shipping_address['province'] }},
                                {{ $customer?->default_address->country ?? $shipping_address['country'] }}</p>
                            <p><strong>Shipping:</strong> {{ number_format($shipping['shop_money']['amount'], 2) }} LE</p>
                            <p><strong>Total Price:</strong> {{ number_format($order->total_price, 2) }} LE</p>
                        @else
                            <p>Order details not found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Comment Section -->
        <div class="card">
            <div class="card-header" style="border:none;">
                <h5>Comments</h5>
            </div>
            <div class="card-body" style="border:none;">
                @if ($ticketUser->comments->isNotEmpty())
                    <ul class="list-group" style="border:none;">
                        @foreach ($ticketUser->comments as $comment)
                            <li class="list-group-item" style="border:none;">
                                <strong>{{ $comment->user->name ?? 'Unknown User' }}
                                    {{ $comment->user->role_id == 8 ? ' (BestExpress)' : ' (Levoile)' }}:</strong>
                                {{ $comment->body }}
                                <br>
                                @if (isset($comment->img))
                                    <a href="{{ url($comment->img) }}" target="_blank">
                                        <img style="width:100px;height:100px" src="{{ url($comment->img) }}">
                                    </a>
                                @endif
                                <br><i><small><span
                                            class="float-right">{{ $comment->created_at->diffForHumans() }}</span></small></i>
                                <br><i><small class="text-black"><span class="float-right">-Date :
                                            {{ $comment->created_at->toDateTimeString() }}</span></small></i>
                            </li>
                            <hr />
                        @endforeach
                    </ul>
                @else
                    <p>No comments yet.</p>
                @endif
            </div>

            @if ($ticketUser->status != 'done')
                <!-- Single Form for Actions and Comments -->
                <form action="{{ route('tickets.updateStatus', $ticketUser->id) }}" method="POST" class="mt-3"
                    enctype="multipart/form-data" onsubmit="return validateForm()">
                    @csrf
                    <div class="form-group">
                        <div class="card-header" style="border:none;">
                            <h5>Actions</h5>
                        </div>
                        <select name="ticket_action" id="ticketActions" class="form-select mb-2" required
                            onchange="toggleCommentBox()" style="margin:auto;width:99%;">
                            <option value="">Select an action...</option>
                            <option value="add_comment">Add Comment</option>
                            @if (
                                $ticketUser->is_returned == 0 &&
                                    $ticketUser->is_asked_to_return == 0 &&
                                    (auth()->user()->role_id == 7 || auth()->user()->role_id == 6|| auth()->user()->role_id == 1))
                                <option value="ask_for_return">Ask For Return</option>
                            @endif
                            @if ($ticketUser->is_returned == 0 && $ticketUser->is_asked_to_return == 1 && auth()->user()->role_id == 8)
                                <option value="returned">Confirm Return</option>
                            @endif
                            @if ($ticketUser->is_returned == 0 && $ticketUser->is_asked_to_return == 1)
                                <option value="return_cancel">Cancel Return Request</option>
                            @endif
                            <option value="delivered">Delivered</option>
                            <option value="hold">Holding Ticket</option>
                        </select>
                    </div>

                    <!-- Textarea for Comment, initially hidden -->
                    <div id="commentBox" class="form-group mt-3" style="display: none;">
                        <textarea name="body" id="commentBody" class="form-control" rows="3" placeholder="Add your comment..."></textarea>
                        <input type="file" class="form-control" name="img">
                    </div>

                    <!-- Submit Button -->
                    <button id="submitbtn" type="submit" class="btn btn-primary mt-2"
                        style="display: none;">Submit</button>
                </form>
            @endif
        </div>


    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById('closeRequestForm').addEventListener('submit', function(event) {
            const acceptShipping = document.getElementById('acceptShipping').checked;
            const refuseShipping = document.getElementById('refuseShipping').checked;

            if (!acceptShipping && !refuseShipping) {
                event.preventDefault(); // Prevent form submission
                alert('Please select at least one option.');
            }
        });

        function disableButton() {
            // Disable the submit button
            const button = document.getElementById('submitbtn');
            button.disabled = true;
            button.innerHTML = "Processing..."; // Optionally change button text
        }

        function toggleCommentBox() {
            const ticketActions = document.getElementById("ticketActions");
            const commentBox = document.getElementById("commentBox");
            const commentBody = document.getElementById("commentBody");
            const submitButton = document.getElementById("submitbtn");

            // Show the comment box if "Add Comment" is selected or if there is a comment in the textarea
            commentBox.style.display = ticketActions.value === "add_comment" || commentBody.value.trim() ? "block" : "none";

            // Show the submit button if any action is selected
            submitButton.style.display = ticketActions.value ? "block" : "none";

            // Make comment required only if "Add Comment" is selected
            commentBody.required = ticketActions.value === "add_comment";
        }

        function validateForm() {
            console.log("here");
            // Disable the submit button
            const button = document.getElementById('submitbtn');
            button.disabled = true;
            button.innerHTML = "Processing..."; // Optionally change button text
            const ticketActions = document.getElementById("ticketActions").value;
            const commentBody = document.getElementById("commentBody").value.trim();

            // If "Add Comment" is selected, ensure the comment textarea is not empty
            if (ticketActions === "add_comment" && !commentBody) {
                alert("Please add a comment before submitting.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
@endsection
