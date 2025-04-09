<td class="text-center">

    <!-- Order History Button -->
    <a class="btn btn-warning btn-sm btn-block shadow-sm text-white"
        href="{{ route('prepare.order-history', $order->order_id) }}" title="Order History">
        <i class="bi bi-clock-history"></i> History
    </a>

    <br>
    <br>

    @if ($order->delivery_status != 'shipped')
        <!-- Cancel Order Button -->
        <a class="btn btn-danger btn-sm btn-block shadow-sm text-white" onclick="cancel_order({{ $order->order_id }})"
            title="Cancel Order">
            <i class="bi bi-x-square"></i> Cancel
        </a>

        <br>
        <br>
        <!-- Edit Order Button -->
        <a class="btn btn-secondary btn-sm btn-block shadow-sm text-white"
            href="{{ url('/pos/edit/' . $order->order_id) }}" title="Edit Order">
            <i class="bi bi-pen"></i> Edit
        </a>
    @endif


    @if ($order->delivery_status == 'prepared' && auth()->user()->role_id != 6)
        <br>
        <br>
        <a class="btn btn-primary btn-sm btn-block shadow-sm text-white"
            href="{{ route('prepare.review', $order->order_id) }}" title="Review Order">
            <i class="bi bi-check-lg"></i> Review
        </a>
        <br>
        <br>
    @elseif ($order->delivery_status == 'fulfilled')
        <!-- Generate Invoice Button -->
        <a class="btn btn-dark btn-sm btn-block shadow-sm text-white"
            href="{{ route('prepare.generate-invoice', $order->order_id) }}" title="Generate Invoice">
            <i class="bi bi-printer"></i> Invoice
        </a>
    @endif
</td>
