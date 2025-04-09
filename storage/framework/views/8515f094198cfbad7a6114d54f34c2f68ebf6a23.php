<?php $isEmbedded = determineIfAppIsEmbedded() ?>

<header id="header" class="header fixed-top d-flex align-items-center"  <?php if($isEmbedded): ?> style="background-color:#f1f2f4" <?php endif; ?>>
  <div class="d-flex align-items-center justify-content-between">
    <a href="<?php echo e(route('home')); ?>" class="logo d-flex align-items-center">
      <img src="<?php echo e(asset('assets/img/logo.png')); ?>" alt="">
      <span class="d-none d-lg-block"><?php echo e(config('app.name')); ?></span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <div class="search-bar">
    
  </div><!-- End Search Bar -->

  <?php echo $__env->make('layouts.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><!-- End Icons Navigation -->

</header><!-- End Header -->
<?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/layouts/header.blade.php ENDPATH**/ ?>