<!DOCTYPE html>
<html lang="en">
    <?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <body>
      <main>
        <div class="container">
          <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
              <div class="row justify-content-center">
                <?php if(\Session::has('success')): ?>
                <div class="alert alert-primary bg-primary text-center text-light border-0 alert-dismissible fade show" role="alert"> <?php echo e(\Session::get('success')); ?> </div>
                <?php endif; ?>
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
    
                  <div class="d-flex justify-content-center py-4">
                    <a href="index.html" class="logo d-flex align-items-center w-auto">
                      
                      <span class="d-none d-lg-block">Shopify App</span>
                    </a>
                  </div><!-- End Logo -->
                  <div class="card mb-3">
                    <div class="card-body">
                      <div class="pt-4 pb-2">
                        <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                      </div>
    
                      <form action="<?php echo e(route('login')); ?>" method="POST" class="row g-3 needs-validation" novalidate>
                        <?php echo csrf_field(); ?>
                        <div class="col-12">
                          <label for="email" class="form-label">E-mail</label>
                          <div class="input-group has-validation">
                            <input type="email" name="email" class="form-control" id="email" required>
                            <div class="invalid-feedback">Please enter your username.</div>
                          </div>
                        </div>
                        <div class="col-12">
                          <label for="password" class="form-label">Password</label>
                          <input type="password" name="password" class="form-control" id="password" required>
                          <div class="invalid-feedback">Please enter your password!</div>
                        </div>
                        <div class="col-12">
                          <button class="btn btn-primary w-100" type="submit">Login</button>
                        </div>
                      </form>
    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </main><!-- End #main -->
    
      <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    
      <?php echo $__env->make('layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </body>
    
    </html><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/auth/login.blade.php ENDPATH**/ ?>