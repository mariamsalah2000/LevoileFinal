<?php if(Session::has('success')): ?>
    <div class="row">
        <div class="alert alert-primary text-center" style="color:rgb(0, 0, 0)">
            <h5><?php echo e(Session::get('success')); ?></h5>
        </div>
    </div>
<?php endif; ?>

<?php if(Session::has('error')): ?>
    <div class="row">
        <div class="alert alert-danger text-center" style="color:rgb(0, 0, 0)">
            <h5><?php echo e(Session::get('error')); ?></h5>
        </div>
    </div>
<?php endif; ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/layouts/success_message.blade.php ENDPATH**/ ?>