<?php echo $__env->make('header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

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
                            <div class="card data-tables">
                                <div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
                                    <div class="toolbar">
                                        <center> <h4 class="card-title"> All Drivers List </h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                            <thead>
                                                <tr><th>S.No.</th>
                                                    <th>Profile Img</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Reviews</th>
                                                    <th class="disabled-sorting text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>

                                             <?php $i= 0; ?>
                                              <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>   
                                               <?php $i++; ?>

                                                <tr>
                                                    <td> <?php echo e($i); ?>. </td>
                                                    <td> <?php if($users->profile_img!=''): ?>


                                                        
                                                        <img src="<?php echo e(url('public/uploads/profile_image/'.$users->profile_img)); ?>" height="70" width="70" >
                                                         <?php endif; ?> </td>
                                                    <td> <?php echo e($users->name); ?> </td>
                                                    <td> <?php echo e($users->email); ?> </td>
                                                    <td> <?php echo e($users->phone); ?> </td>
                                                    <td> <?php echo e(date('d/m/Y',strtotime($users->created_at))); ?> </td>
                                                    <td> <?php if($users->status == 1): ?> <?php echo e('Deactive'); ?> <?php elseif($users->status == 3): ?> <?php echo e('Blocked'); ?> <?php else: ?> <?php echo e('Active'); ?> <?php endif; ?> </td>

                                                    <td> 

                                                    <button class="btn btn-link btn-info" title="Click to view"  > <a title="View Details" href="<?php echo e(url('viewReviews/'.($users->id))); ?>" class="btn btn-eye btn-info" > Reviews </a>  </button> </td>
                                                    
                                                    <td class="text-right">

                                                   <a title="Block Driver" href="<?php echo e(url('blockDriver/'.$users->id)); ?>" class="btn btn-eye btn-info"  > <i class="fa fa-ban"> </i> </a>
                                                    
                                        <a title="View Details" href="<?php echo e(url('viewUser/'.($users->id))); ?>" class="btn btn-eye btn-info" > <i class="fa fa-eye"> </i> </a>

                                        <a title="delete" href="<?php echo e(url('removeUser/'.encrypt($users->id))); ?>" class="btn btn-link btn-danger" onclick="return confirm('Do you really want to delete driver');"> <i class="fa fa-times"> </i> </a>
                                                       
                                        </td>

                                    </tr>

                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
 
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/users.blade.php ENDPATH**/ ?>