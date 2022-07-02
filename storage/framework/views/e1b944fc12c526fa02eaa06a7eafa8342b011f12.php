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
                                        <center> <h4 class="card-title"> Driver Vehicle List  </h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                            <thead>
                                                <tr><th>S.No.</th>
                                                <th>Driver Name</th> 
                                                <th>Vechicle Name</th>
                                                    <th>Modal</th>
                                                    <th>Color</th> 
                                                    <th>Type</th> 
                                                    <th>License No.</th>
                                                    
                                                    <th>Front Image</th>
                                                    <th>Back Image</th>
                                                    <th>Vehicle Image</th>
                                                  
                                                </tr>
                                            </thead>
                                          
                                            <tbody>

                                             <?php $i= 0; ?>
                                              <?php $__currentLoopData = $allVehicleinfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>   
                                               <?php $i++; ?>

                                                <tr>
                                                    <td> <?php echo e($i); ?>. </td>
                                                    <td> <?php echo e($users->name); ?> </td>
                                                    <td> <?php echo e($users->vechicle_make); ?> </td>
                                                    <td> <?php echo e($users->vechicle_model); ?> </td>
                                                  
                                                    <td> <?php echo e($users->vechicle_color); ?> </td>
                                                    <td> <?php echo e($users->vechicle_type); ?> </td>
                                                <td> <?php echo e($users->vechicle_license_plate); ?> </td>

                                              <td> <?php if($users->upload_vehicle_image!=''): ?><img src="<?php echo e(URL::to('public/uploads/vehicle/'.$users->driver_license_front)); ?>" height="70" width="70" > <?php endif; ?> </td>
                                                
                                        <td> <?php if($users->upload_vehicle_image!=''): ?><img src="<?php echo e(URL::to('public/uploads/vehicle/'.$users->driver_license_back)); ?>" height="70" width="70" > <?php endif; ?> </td>



                                            
                                                
                                                  <td> <?php if($users->upload_vehicle_image!=''): ?><img src="<?php echo e(URL::to('public/uploads/vehicle/'.$users->upload_vehicle_image)); ?>" height="70" width="70" > <?php endif; ?> </td>

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
 
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/vehicle.blade.php ENDPATH**/ ?>