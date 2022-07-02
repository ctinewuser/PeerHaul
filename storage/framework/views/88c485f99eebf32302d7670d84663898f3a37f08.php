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
                                        <center> <h4 class="card-title">Job List</h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                            <thead>
                                                <tr><th>S.No.</th>
                                                    <th>Customer Name</th>
                                                    <th>Pickup Location</th>
                                                    <th>Drop Location</th>
                                                    <th>Parcel size</th>
                                                    <th>Estimate Price</th>
                                                    <th>Express Listing</th>
                                                    <th>Applied Bids</th>
                                                    <th>Block Job</th>
                                                    <th class="disabled-sorting text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>

                                             <?php $i= 0; ?>
                                              <?php $__currentLoopData = $jobDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>   
                                               <?php $i++; ?>

                                                <tr>
                                                    <td> <?php echo e($i); ?>. </td>
                                                    <td> <?php echo e($users->name); ?> </td>
                                                    <td> <?php echo e($users->pick_up_location); ?> </td>
                                                    <td> <?php echo e($users->drop_off_location); ?> </td>
                                                    <td> <?php echo e($users->parcel_name); ?> </td>
                                                    <td> <?php echo e($users->estimate_price); ?> </td>
                                                    <td> 
                                                          <?php if($users->express_listing == '0'): ?>
                                                         Normal
                                                          <?php else: ?>
                                                          Express
                                                          <?php endif; ?>
                                                        
                                                    </td>
                                                   
                                                     <td> <button class="btn btn-link btn-info" title="Click to view"  > <a title="View Bids" href="<?php echo e(url('jobBid-List/'.($users->id))); ?>" class="btn btn-eye btn-info" >  Bids </a>  </button> </td>

                                                     <td> <button class="btn btn-link btn-info" title="Click to view"  > <a title="Block Job" href="<?php echo e(url('blockJob/'.$users->id)); ?>" class="btn btn-eye btn-danger" >Block</a></button>
                                                      </td>
                                                    <td class="text-right">

                                                   
                                                      <a href="<?php echo e(url('viewJob/'.$users->id)); ?>" class="btn btn-eye btn-info"  > <i class="fa fa-eye"> </i> </a>
                                                       
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
 
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/jobdetail.blade.php ENDPATH**/ ?>