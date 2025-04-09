<?php
    $sidebar_key = Auth::user()->getSidebarKey();
    $show_sidebar = Session::get($sidebar_key);
?>
<!DOCTYPE html>
<html lang="en">
<?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body>
    <?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- ======= Sidebar ======= -->
    <?php if(auth()->user()->role_id == 2): ?>
        getOrders
        <?php echo $__env->make('superadmin.aside', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif($show_sidebar): ?>
        <?php echo $__env->make('layouts.aside', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <main id="main" class="main">
        <?php if(Auth::check()): ?>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo e(Auth::user()->id); ?>">
        <?php endif; ?>
        <?php echo $__env->make('layouts.success_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- End Sidebar-->
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('scripts'); ?>


    <script>
        var count = 0;

        function searchh() {
            $('#search-modal').modal('show');
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

        function shipping_trx() {
            $("#shipping-trx-upload-modal").modal("show");
        }



        function returnn(id) {

            var selected_name = $("#return_order" + id).find("option:selected").val();
            console.log(selected_name, id);

            if (selected_name == "") {
                count = count - 1;
                $("#" + id).hide();
                $("#items" + id).val("");
                if (count < 1) {

                    $("#return_order_submit").hide();
                    $("#return_all_submit").show();
                }

            } else {
                count = count + 1;
                $("#items" + id).val(id);
                console.log(selected_name);
                $("#" + id).show();
                $("#return_order_submit").show();
                $("#return_all_submit").hide();
            }
        }

        function cancell() {

            var selected_name = $("#options").find("option:selected").val();
            if (selected_name == "adjust") {
                $("#cancel_order").hide();
                $("#adjust_order").show();
                $("#reschedule_call").hide();

            } else if (selected_name == "cancel") {
                $("#cancel_order").show();
                $("#adjust_order").hide();
                $("#reschedule_call").hide();
            } else {
                $("#cancel_order").hide();
                $("#adjust_order").hide();
                $("#reschedule_call").show();
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

        $('#get-progress').on('submit', function(event) {
            updateProgress();

        });
    </script>
    <?php if(!$show_sidebar): ?>
        <script>
            $(document).ready(function() {
                $('.toggle-sidebar-btn').click();
            })
        </script>
    <?php endif; ?>
</body>

</html>
<?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/layouts/app.blade.php ENDPATH**/ ?>