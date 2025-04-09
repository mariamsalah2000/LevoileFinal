@php
    $total_shipping = 0;
    foreach ($order['shipping_lines'] as $ship) {
        $total_shipping = $ship['price'];
    }
@endphp
<tr>
    <td>
        <div class="form-group">
            <div class="aiz-checkbox-inline">
                <label class="aiz-checkbox">
                    <input type="checkbox" class="check-one" name="id[]" value="{{ $order->id }}">
                    <span class="aiz-square-check"></span>
                </label>
            </div>
        </div>
    </td>
    <td><a class="btn-link"
            href="{{ route('shopify.order.prepare', $order->order_number) }}">Lvs-{{ $order->order_number }}</a>
    </td>
    @php
        $shipping_address = $order['shipping_address'];

        if (!is_array($order['shipping_address'])) {
            $shipping_address = json_decode($order['shipping_address']);
            $shipping_address = $shipping_address
                ? $shipping_address
                : (is_array($order['customer'])
                    ? $order['customer']
                    : json_decode($order['customer']));
        }

    @endphp

    <td>{{ is_array($shipping_address) && isset($shipping_address['name']) ? $shipping_address['name'] : '' }}
    </td>
    <td class="text-center">{{ $order->getPaymentStatus() }}</td>
    <td class="text-center">{{ $order->subtotal_price }}</td>
    <td class="text-center">{{ $total_shipping }}</td>
    <td class="text-center">{{ $order->total_price }}</td>
    <td>{{ is_array($shipping_address) && isset($shipping_address['phone']) ? $shipping_address['phone'] : '' }}
    </td>
    <td>
        @if ($order->fulfillment_status == 'processing')
            <span class="badge badge-inline badge-danger">{{ $order->fulfillment_status }}</span>
        @elseif ($order->fulfillment_status == 'distributed')
            <span class="badge badge-inline badge-info">{{ $order->fulfillment_status }}</span>
        @elseif ($order->fulfillment_status == 'prepared')
            <span class="badge badge-inline badge-success">{{ $order->fulfillment_status }}</span>
        @elseif ($order->fulfillment_status == 'shipped')
            <span class="badge badge-inline badge-primary">{{ $order->fulfillment_status }}</span>
        @elseif ($order->fulfillment_status == 'hold')
            <span class="badge badge-inline badge-warning">{{ $order->fulfillment_status }}</span>
        @elseif ($order->fulfillment_status == 'reviewed')
            <span class="badge badge-inline badge-secondary">{{ $order->fulfillment_status }}</span>
        @elseif ($order->fulfillment_status == 'cancelled')
            <span class="badge badge-inline badge-danger">{{ $order->fulfillment_status }}</span>
        @elseif ($order->fulfillment_status == 'fulfilled')
            <span class="badge badge-inline badge-dark">{{ $order->fulfillment_status }}</span>
        @endif
    </td>
    <td>{{ date('Y-m-d h:i:s', strtotime($order->created_at_date)) }}</td>
    <td>
        <div class="row">
            @if ($order->fulfillment_status == 'prepared')
                <div class="col-5">
                    <div class="row">
                        <a class="btn btn-primary" href="{{ route('prepare.review', $order->id) }}"
                            title="Review Order">
                            <i class="bi bi-check-lg"></i>
                        </a>
                        Review
                    </div>
                </div>
                <div class="col-1"></div>
            @elseif ($order->fulfillment_status == 'fulfilled')
                <div class="col-5  mr-2 ml-2">
                    <div class="row mb-1">
                        <a class="btn btn-dark" href="{{ route('prepare.generate-invoice', $order->id) }}"
                            title="Generate Invoice">
                            <i class="bi bi-printer"></i>
                        </a>
                        Invoice
                    </div>
                </div>
            @endif
        </div>
    </td>
</tr>
