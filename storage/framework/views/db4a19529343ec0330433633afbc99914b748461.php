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
                                        <center> <h4 class="card-title"> Fees Structure </h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                            <thead>
                                                <tr><th>S.No.</th>
                                                    <th>Size name</th>
                                                    <th>Parcel fees</th>
                                                  <!--   <th>Mileage fees</th> -->
                                                    <th>Route</th>
                                                    <th>Route (in km)</th>
                                                    <th>Service Fees</th>
                                                    <th>PeerHaul Fees</th> 
                                                    <th>Fees (Per km)</th> 
                                                    <th>Fees (Per hr)</th> 
                                                   <th>Action</th>     
                                                </tr>
                                            </thead>    
                                            <tbody>

                                             <?php $i= 0; ?>
                                              <?php $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>   
                                               <?php $i++;
                                               ?>
                                                    <tr>
                                                    <td> <?php echo e($i); ?>. </td>
                                                    <td> <?php echo e($list->name); ?> </td>
                                                    <td> $<?php echo e($list->parcel_fees); ?> </td>
                                                    <td> <?php if($list->route == 1): ?> <?php echo e('Short Route '); ?> <?php else: ?> <?php echo e('Long Route'); ?> <?php endif; ?> </td>
                                                    <td> <?php echo e($list->route_per_km); ?> km </td>
                                                    <td> $<?php echo e($list->service_fee); ?> </td>
                                                    <td> $<?php echo e($list->peerHaul_fee); ?> </td>
                                                    <td> $<?php echo e($list->fees_per_km); ?> </td>
                                                    <td> $<?php echo e($list->fees_per_hr); ?> </td>
                                                 

                                  
                                          <td> <a href="<?php echo e(url('editFees/'.$list->id)); ?>" class="btn btn-edit btn-info" data-toggle="modal" data-target="#feesModal<?php echo e($list->id); ?>" > <i class="fa fa-edit"> </i> </a></td>

                                                </tr>
                                               <!--Add Modal Popup--->
                                    <!-- The Modal -->
                                    <div class="modal fade" id="feesModal<?php echo e($list->id); ?>" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                
                                                   <h4>Edit Your Fees Detail</h4>
                                                </div>

                                                <div class="modal-body pt-0">
                                                    <form id="contactForm" name="contact" role="form" method="post" action="<?php echo e(url('update-fees/'.$list->id)); ?>">
                                                     <?php echo csrf_field(); ?>
                                                <div class="form-group mb-0"> 

                                            <label for="service_fee">Service Fees</label>

                                             <input type="text" class="form-control" name="service_fee" id="service_fee" value="<?php echo e($list->service_fee); ?>" >

                                            <label for="peerHaul_fee">Peerhaul fees</label>

                                             <input type="text" class="form-control" name="peerHaul_fee" id="peerHaul_fee" value="<?php echo e($list->peerHaul_fee); ?>" >

                                              <label for="parcel_fees"> Parcel Fees</label>

                                             <input type="text" class="form-control" name="parcel_fees" id="parcel_fees" value="<?php echo e($list->parcel_fees); ?>" >

                                               <label for="fees_per_km">Fees(per km)</label>

                                             <input type="text" class="form-control" name="fees_per_km" id="fees_per_km" value="<?php echo e($list->fees_per_km); ?>" >

                                             <label for="fees_per_hr">Fees(per hr)</label>

                                             <input type="text" class="form-control" name="fees_per_hr" id="fees_per_hr" value="<?php echo e($list->fees_per_hr); ?>" >
                                          

                                                 </div>
                                                      
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- The Modal -->
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
 
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/feesList.blade.php ENDPATH**/ ?>