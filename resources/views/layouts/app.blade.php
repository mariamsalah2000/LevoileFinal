@php
    $sidebar_key = Auth::user()->getSidebarKey();
    $show_sidebar = Session::get($sidebar_key);
@endphp
<!DOCTYPE html>
<html lang="en">
@include('layouts.head')

<body>
    @include('layouts.header')
    <!-- ======= Sidebar ======= -->
    @if (auth()->user()->role_id == 2)
        getOrders
        @include('superadmin.aside')
    @elseif ($show_sidebar)
        @include('layouts.aside')
    @endif
    <main id="main" class="main">
        @if (Auth::check())
            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
        @endif
        @include('layouts.success_message')
        <!-- End Sidebar-->
        @yield('content')
    </main>
    @include('layouts.footer')
    @include('layouts.scripts')
    @yield('scripts')


    <script>
        let count = 0;

        function searchh() {
            $('#search-modal').modal('show');
        }

        function upload_shipping_cost() {
            $('#upload-shipping-cost').modal('show');
        }


        function resync() {
            $('#resync-modal').modal('show');
        }

        function warehouse() {
            $('#inventory-modal').modal('show');
        }

        function upload_stock() {
            $("#upload-stock-modal").modal("show");
        }
        function disableButton() {
            // Disable the submit button
            const button = document.getElementById('submit-shipping-btn');
            button.disabled = true;
            button.innerHTML = "Uploading..."; // Optionally change button text
        }


        function shipping_trx() {
            $("#shipping-trx-upload-modal").modal("show");
        }

        function collections() {
            $("#collection-upload-modal").modal("show");
        }
        function return_collections() {
            $("#return-collection-upload-modal").modal("show");
        }



        function returnn(id) {
            // Get the selected value from the dropdown
            var selectedValue = $("#return_order" + id).find("option:selected").val();

            console.log("Selected value:", selectedValue, "Product ID:", id);

            // Check if an option was selected
            if (selectedValue === "") {
                // Decrease count and hide the hidden div
                count = Math.max(0, count - 1); // Ensure count doesn't go below zero
                $("#" + id).hide(); // Hide the div
                $("#items" + id).val(""); // Clear the hidden input value

                console.log("Count after hiding:", count);

                // Adjust button visibility based on count
                if (count < 1) {
                    $("#return_order_submit").hide();
                    $("#return_all_submit").show();
                }
            } else {
                // Increase count and show the hidden div
                count++;
                $("#" + id).show(); // Show the div
                $("#items" + id).val(id); // Set the hidden input value to the product ID

                console.log("Count after showing:", count);

                // Adjust button visibility
                $("#return_order_submit").show();
                $("#return_all_submit").hide();
            }
        }


        function respond() {

            var selected_name = $("#call_response").find("option:selected").val();
            $("#submit_box").show();
            if (selected_name == "answered") {
                $("#reschedule_call").hide();
                $("#answered").show();

            } else if (selected_name == "no_answer" || selected_name == "phone_off") {
                $("#reschedule_call").show();
                $("#answered").hide();
            } else {
                $("#reschedule_call").hide();
                $("#answered").hide();
            }
        }

        function updateProgress() {
            console.log("hi");
            $.ajax({
                url: "/upload-progress",
                type: 'GET',
                contentType: false,
                processData: false,
                success: function(data) {
                    var progressMessage = data.message;
                    console.log(progressMessage);
                    if (progressMessage !== "") {
                        document.getElementById('progressMessage').textContent = "";
                        document.getElementById('progressMessage').textContent = progressMessage;
                    }

                    setTimeout(updateProgress, 3000);
                }
            });
        }

        function addReturn() {
            $('#confirm-modal').modal('show');
        }

        $('#get-progress').on('submit', function(event) {
            updateProgress();

        });
    </script>
    @if (!$show_sidebar)
        <script>
            $(document).ready(function() {
                $('.toggle-sidebar-btn').click();
            })
        </script>
    @endif
</body>

</html>
