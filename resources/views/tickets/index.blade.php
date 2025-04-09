@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 1800px;">
        <h1>All Tickets</h1>
        <!-- Report Cards -->
        <!-- Report Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('tickets.index', ['status' => '']) }}" class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title" style="color:white;">Total Tickets</h5>
                        <p class="card-text">{{ $totalTickets }}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('tickets.index', ['status' => 'open']) }}" class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Open</h5>
                        <p class="card-text">{{ $openCount }}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('tickets.index', ['status' => 'in progress']) }}" class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">In Progress</h5>
                        <p class="card-text">{{ $inProgressCount }}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('tickets.index', ['status' => 'done']) }}" class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title" style="color:white;">Done</h5>
                        <p class="card-text">{{ $doneCount }}</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Report Cards for Ticket Types -->
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="{{ route('tickets.index', ['ticket_type' => 'request']) }}"
                    class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title" style="color:white;">Request Tickets</h5>
                        <p class="card-text">{{ $requestCount }}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('tickets.index', ['ticket_type' => 'complaint']) }}"
                    class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title" style="color:white;">Complaint Tickets</h5>
                        <p class="card-text">{{ $complaintCount }}</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('tickets.index') }}" method="GET" class="mb-4 border border p-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="order_number">Order Number</label>
                    <input type="text" name="order_number" id="order_number" class="form-control"
                        value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="ticket_type">Ticket Type</label>
                    <select name="ticket_type" id="ticket_type" class="form-control">
                        <option value="">All</option>
                        <option value="request" {{ request('ticket_type') == 'request' ? 'selected' : '' }}>Request - طلب
                        </option>
                        <option value="complaint" {{ request('ticket_type') == 'complaint' ? 'selected' : '' }}>Complaint -
                            شكوي</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open - مفتوح</option>
                        <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>In Progress
                            - جاري العمل به</option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done - تم الانتهاء
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status">Final State</label>
                    <select name="state" id="status" class="form-control">
                        <option value="">All</option>
                        <option value="failed" {{ request('state') == 'failed' ? 'selected' : '' }}>Failed - فشل</option>
                        <option value="pending" {{ request('state') == 'pending' ? 'selected' : '' }}>Pending Return - في
                            انتظار الاسترجاع</option>
                        <option value="success" {{ request('state') == 'success' ? 'selected' : '' }}>Success - نجح
                        </option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                        value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="reason">Reason</label>
                    <select name="reason" id="reason" class="form-select">
                        <option value="">Select Reason</option>

                        <!-- Role 7 - Request -->

                        <option value="change_delivery_time">Change Delivery Time - نغير معاد التوصيل</option>
                        <option value="change_delivery_location">Change Delivery Location - تغير مكان التوصيل</option>
                        <option value="change_consignee_data">Change Consignee Data - تغيير بيانات المرسل إليه</option>
                        <option value="cancel_and_return">Cancel & Return Order - الغاء الاوردر ورده الينا</option>
                        <option value="speed_delivery">Speed Delivery - سرعة تواصل و تسليم الاوردر</option>
                        <option value="payment_issues">Paid Order - مشكلة في الدفع</option>
                        <option value="other">Other - اخري</option>

                        <!-- Role 7 - Complaint -->
                        <option value="shipment_not_delivered">Shipment Not Delivered - الشحنه لم تصل</option>
                        <option value="delivery_delay">Delivery Delay - تأخير التوصيل</option>
                        <option value="mistreatment">Mistreatment - سوء معامله</option>

                        <!-- Role 8 - Request -->

                        <option value="client_need_call">Client Needs Call - العميل محتاج نكلمه</option>
                        <option value="client_forgot_order">Client Forgot Order - العميل مش فاكر الأوردر</option>
                        <option value="duplicated_order">Duplicated Order - أوردر مكرر</option>
                        <option value="no_answer">No Answer - لا احد يجيب</option>
                        <option value="refuse_receiving">Refuse Receiving - رفض الاستلام</option>
                        <option value="reschedules_delivery">Reschedules Delivery - إعادة جدولة التسليم</option>
                        <option value="other">Other - اخري</option>

                        <!-- Role 8 - Complaint -->
                        <option value="wrong_number">Wrong Number - رقم خاطئ</option>
                        <option value="wrong_address">Wrong Address - عنوان خاطئ</option>
                        <option value="wrong_total_price">Wrong Total Price - اجمالي المبلغ خاطئ</option>
                        <option value="payment_issues">Payment Issues - مشاكل الدفع</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status">Pending Response</label>
                    <select name="response" id="response" class="form-select">
                        <option value="">All</option>
                        <option value="lev" {{ request('response') === 'lev' ? 'selected' : '' }}>Pending Levoile - في
                            انتظار لفوال</option>
                        <option value="best" {{ request('response') === 'best' ? 'selected' : '' }}>Pending Shipping -
                            في انتظار شركه الشحن</option>
                        <option value="hold" {{ request('response') === 'hold' ? 'selected' : '' }}>Holding Ticket - تم
                            تعليق التيكت</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="status">Staff</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">All</option>
                        @foreach ($users->where('role_id', auth()->user()->role_id) as $key => $user)
                            <option value="{{ $user->id }}" {{ request('user_id') === $user->id ? 'selected' : '' }}>
                                {{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <button type="submit" class="btn btn-secondary mt-3" onclick="resetPendingResponse()">Filter</button>
            <button type="button" class="btn btn-success mt-3"
                onclick="window.location.href='{{ route('tickets.index') }}';">Reset</button>

        </form>

        <button id="exportButton" class="btn btn-success mb-2" style="float:right;">
            Export
        </button>
        

        <table id="ticketsTable" class="table table-bordered table-hover">
            <thead class="thead-dark" style="background-color:#4154f1;color:white;">
                <tr>
                    <th>Created At</th>
                    <th>Order</th>
                    <th>Type</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Final State</th>
                    <th>Reason</th>
                    <th>Messages</th>
                    <th>pending Response</th>
                    @if (auth()->user()->role_id == 8)
                        <th>Confirm Return</th>
                    @endif
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>
                            {{ $ticket->created_at ? $ticket->created_at->format('d M Y, H:i') : 'N/A' }}
                        </td>
                        <td>
                            <b>{{ $ticket->order_number ?? 'N/A' }}</b>
                        </td>
                        <td>{{ ucfirst($ticket->ticket->type) }}</td>
                        <td>
                            {{ $ticket->user->name ?? 'N/A' }} - @if ($ticket->user->role_id == 7 || $ticket->user->role_id == 6)
                                LV
                            @elseif($ticket->user->role_id == 8)
                                BE
                            @else
                                'N/A'
                            @endif
                        </td>
                        <td>
                            <span
                                class="badge 
                @if (ucfirst($ticket->status) === 'Done') bg-success @endif 
                @if (ucfirst($ticket->status) === 'In progress') bg-secondary @endif
                @if (ucfirst($ticket->status) === 'Open') bg-warning @endif">{{ ucfirst($ticket->status) ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if (ucfirst($ticket->status) === 'Done' && $ticket->is_asked_to_return == 0 && $ticket->is_returned == 0)
                                <span class="badge bg-success">Success</span>
                            @elseif(ucfirst($ticket->status) === 'In progress' && $ticket->is_asked_to_return == 1 && $ticket->is_returned == 0)
                                <span class="badge bg-warning">Pending Return</span>
                            @elseif(ucfirst($ticket->status) === 'Done' && $ticket->is_returned == 1 && $ticket->is_asked_to_return == 0)
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span>--</span>
                            @endif
                        </td>
                        <td>
                            {{ str_replace('_', ' ', $ticket->content) ?? 'N/A' }}
                        </td>
                        <td>
                            @php
                                $latestComment = DB::table('comments')
                                    ->where('ticket_user_id', $ticket->id)
                                    ->latest()
                                    ->first();
                                $commentUser = $latestComment
                                    ? DB::table('users')
                                        ->where('id', $latestComment->user_id)
                                        ->first()
                                    : null;

                            @endphp

                            {{ $latestComment->body ?? 'No comments yet' }}<br>
                            @if ($commentUser)
                                <small><i> by: {{ $commentUser->name }} </i></small>
                            @endif
                        </td>
                        <td>
                            @if (
                                ($commentUser->role_id == 7 || $commentUser->role_id == 6) &&
                                    $ticket->is_hold == 0 &&
                                    (ucfirst($ticket->status) === 'In progress' || ucfirst($ticket->status) === 'Open'))
                                <span class="badge bg-success">Pending Shipping</span>
                            @elseif(
                                $commentUser->role_id == 8 &&
                                    $ticket->is_hold == 0 &&
                                    (ucfirst($ticket->status) === 'In progress' || ucfirst($ticket->status) === 'Open'))
                                <span class="badge bg-success">Pending Levoile</span>
                            @elseif(
                                $commentUser->role_id == 8 &&
                                    $ticket->is_hold == 0 &&
                                    (ucfirst($ticket->status) === 'In progress' || ucfirst($ticket->status) === 'Open'))
                                <span class="badge bg-success">Pending Levoile</span>
                            @elseif(ucfirst($ticket->status) === 'In progress' && $ticket->is_hold == 1)
                                <span class="badge bg-danger">Holding Ticket</span>
                            @else
                                <span>--</span>
                            @endif
                        </td>
                        @if (auth()->user()->role_id == 8)
                            <td>
                                <div class="">
                                    @if ($ticket->is_asked_to_return == 1 && $ticket->is_returned == 0)
                                        <form action="{{ route('tickets.confirmReturn') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                            <select class="form-select" name="status" required style="border: none"
                                                id="confirm-return-checkbox">
                                                <option value="">Select Status</option>
                                                <option value="confirm">Confirm Return</option>
                                                <option value="cancel">Cancel Return</option>
                                            </select>

                                            <br>

                                            <input type="submit" class="btn btn-danger d-none" value="Submit"
                                                id="submit_return_btn">
                                        </form>
                                    @endif
                                </div>
                            </td>
                        @endif
                        <td>
                            @if (
                                ($ticket->user->role_id == 7 || $ticket->user->role_id == 6) &&
                                    auth()->user()->role_id == 8 &&
                                    ucfirst($ticket->status) !== 'Done')
                                <!-- Role 8 can open tickets created by Role 7 -->
                                <form action="{{ route('tickets.open', $ticket->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-info">Open</button>
                                </form>
                            @elseif(
                                $ticket->user->role_id == 8 &&
                                    (auth()->user()->role_id == 7 || auth()->user()->role_id == 6) &&
                                    ucfirst($ticket->status) !== 'Done')
                                <!-- Role 7 can open tickets created by Role 8 -->
                                <form action="{{ route('tickets.open', $ticket->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-info">Open</button>
                                </form>
                            @else
                                <!-- Show button for all other scenarios -->
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-success">Show</a>
                            @endif


                            <!-- <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline-block;">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete</button>
                                                                                            </form> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
            {{ $tickets->links() }}
        </table>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>


    <script>
        $("ul#ticket").siblings('a').attr('aria-expanded', 'true');
        $("ul#ticket").addClass("show");
        $("#all_tickets").addClass("active");
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const responseDropdown = document.getElementById('response');
            const ticketsTable = document.getElementById('ticketsTable');
            const rows = ticketsTable.getElementsByTagName('tr');

            {{--  responseDropdown.addEventListener('change', function() {
                filterTable(this.value);
            });  --}}

            {{--  function filterTable(selectedValue) {
                for (let i = 1; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    const pendingResponseCell = cells[8]; // Adjust the index if necessary

                    if (pendingResponseCell) {
                        const cellText = pendingResponseCell.textContent || pendingResponseCell.innerText;

                        // Determine visibility based on selected filter
                        const shouldShow = (
                            selectedValue === '' || // Show all
                            (selectedValue === 'lev' && cellText.includes('Pending Levoile')) ||
                            (selectedValue === 'best' && cellText.includes('Pending Shipping')) ||
                            (selectedValue === 'hold' && cellText.includes('Holding Ticket'))
                        );

                        rows[i].style.display = shouldShow ? '' : 'none';
                    }
                }
            }  --}}

            {{--  window.resetPendingResponse = function() {
                responseDropdown.value = ''; // Reset to default
                filterTable(''); // Call filter function to show all tickets
            };  --}}
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectt = document.getElementById('confirm-return-checkbox');

            selectt.addEventListener('change', function() {
                var message = this.value;
                console.log(this.value, this, selectt);
                if (message != "")
                    $('#submit_return_btn').removeClass('d-none');
                else
                    $('#submit_return_btn').addClass('d-none');

            });
        });
    </script>

<script>
document.getElementById('exportButton').addEventListener('click', async function () {
    try {
        const response = await fetch('{{ route('tickets.exportData', request()->query()) }}');
        if (!response.ok) throw new Error('Failed to fetch ticket data');

        const data = await response.json();
        if (data.length === 0) {
            alert('No data available for export.');
            return;
        }

        const exportData = data.map(ticket => ({
            'Created At': ticket.created_at,
            'Order Number': ticket.order_number,
            'Ticket Type': ticket.ticket_type,
            'User': ticket.user,
            'Status': ticket.status,
            'Reason': ticket.reason,
            'Messages': ticket.messages,
            'Pending Response': ticket.pending_response,
        }));

        const headers = ['Created At', 'Order Number', 'Ticket Type', 'User', 'Status', 'Reason', 'Messages', 'Pending Response'];
        const worksheet = XLSX.utils.json_to_sheet(exportData, { header: headers });
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Tickets');

        const filename = 'tickets_' + new Date().toISOString().slice(0, 10) + '.xlsx';
        XLSX.writeFile(workbook, filename);
    } catch (error) {
        console.error('Export failed:', error);
        alert('Failed to export tickets. Please try again.');
    }
});


</script>

@endsection
