<?php echo $__env->make('header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- End Navbar -->
<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">

                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title"> Terms And Condition </h4>
                                </div>

                                <div class="col-md-12">
            <?php if(session()->has('success_msg')): ?>
                        
            <div class="alert alert-info">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span> <b> Success - </b>  <?php echo e(session()->get('success_msg')); ?> </span>
            </div>
            <?php endif; ?>

            <?php if(session()->has('error_msg')): ?>

            <div class="alert alert-danger">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span> <b> Fail - </b> <?php echo e(session()->get('error_msg')); ?></span>
            </div>

            <?php endif; ?>
           </div>  
                                <div class="card-body ">
                                    <form method="post" action="<?php echo e(url('update-terms-condition')); ?>" class="form-horizontal">
                                         <?php echo csrf_field(); ?>
                                        <fieldset>
                                            <div class="form-group">
                                                <div class="row">
                                                   <!--<label class="col-sm-2 control-label">Terms And Condition </label>-->
                                                   <div class="col-sm-12">
                                                       <textarea type="text" name="info" class="form-control ckeditor" ><?php echo e($allTerms['info']); ?></textarea>
                                                   </div>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Payment Terms </label>
                                                   <div class="col-sm-10">
                                                       <textarea type="text" name="payment_terms" class="form-control ckeditor" ><?php echo e($allTerms['payment_terms']); ?></textarea>
                                                   </div>
                                                </div>
                                            </div> -->
                                           
                                        </fieldset>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                </div>
            </div>
        </div>

         

 <?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/terms-condition.blade.php ENDPATH**/ ?>