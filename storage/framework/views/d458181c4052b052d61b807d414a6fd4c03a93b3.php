<?php echo $__env->make('header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- End Navbar -->
<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">

                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title">Job Listing</h4>
                                </div>
                                <div class="card-body ">
                                    <form method="get" action="" enctype="multipart/form-data"  class="form-horizontal">
                                         
                                        <fieldset>
                                        <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Customer Name</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e(ucfirst(($getjobDetails->name))); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                           
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> PickUp Location </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->pick_up_location)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Drop Location </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e($getjobDetails->drop_off_location); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Estimate Price </label>
                                                   <div class="col-sm-10"> 
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e($getjobDetails->estimate_price); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Parcel Size </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e($getList->size_name); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                          <!-- 0=no,1=yes --->
                                          
                                              <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Add Bonus</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e($getjobDetails->add_bonus); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                             <?php if($getjobDetails->express_listing = 0): ?>   <?php endif; ?>
                                        </fieldset>
            
                                    </form>
                                </div>
                            </div>
<!-- Finish One div -->

                                <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title">Items Information</h4>
                                </div>
                                <div class="card-body ">
                                    <form method="get" action="https://demos.creative-tim.com/" class="form-horizontal">
                                         
                                        <fieldset>
                                          
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Descriptive Title</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->descriptive_title)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                          
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Delivery Date</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->delivery_date)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Images</label>
                                            

                                                <?php if($getjobDetails->upload_photos!=''): ?>

                                               <?php $__currentLoopData = json_decode($getjobDetails->upload_photos, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $images): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                   <div class="col-sm-2">           
                                                        <img src="<?php echo e(url('public/uploads/img/'.$images)); ?>" height="70" width="70" >
                                                       
                                                   </div>
                                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?> 

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Size of Entire Delivery</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="size_of_entire_delivery" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->size_of_entire_delivery)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Public Description</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="public_item_description" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->public_item_description)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Order Reference Number</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->order_ref_number)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Quantity Items</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->quantity_items)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Width</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="width" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->width)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Height</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="height" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->height)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Length</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="length" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->length)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Weight</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="weight" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->weight)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                        </fieldset>
            
                                    </form>
                                </div>
                            </div>

                            <!-- End of second div -->

                    <!-- Finish second div -->

                    <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title"> Pickup Details</h4>
                                </div>
                                <div class="card-body ">
                                    <form method="get" action="https://demos.creative-tim.com/" class="form-horizontal">
                                         
                                        <fieldset>
                                          
                                         
                                        <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Available Person Name</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" disabled class="form-control" value="<?php echo e(ucfirst($getjobDetails->available_person_name)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Available Person Contact</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" disabled class="form-control" value="<?php echo e(ucfirst($getjobDetails->available_person_contact)); ?>">
                                                   </div>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Available Person Email</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" disabled class="form-control" value="<?php echo e(ucfirst($getjobDetails->available_person_email)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Private Information</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" disabled class="form-control" value="<?php echo e(ucfirst($getjobDetails->private_information)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Private Time Id</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title"  disabled class="form-control" value="<?php echo e(ucfirst($getjobDetails->pickup_time_id)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                        </fieldset>
            
                                    </form>
                                </div>
                            </div>

                            <!-- End of Third div -->
                         <!--Fourth Div create--->
                              <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title"> Delivery information  </h4>
                                </div>
                                <div class="card-body ">
                                    <form method="get" action="" class="form-horizontal">
                                         
                                        <fieldset>
                                          
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Driver Qualification</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="driver_qualification" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->driver_qualification)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                          
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Receiver Name</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="receiver_name" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->receiver_name)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                     
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Receiver Contact</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="receiver_contact" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->receiver_contact)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Receiver Email</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="receiver_email" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->receiver_email)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Delivery Date</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="delivery_date" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->delivery_date)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Delivery Time</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="delivery_time" class="form-control" disabled value="<?php echo e(ucfirst($getjobDetails->delivery_time)); ?>">
                                                   </div>
                                                </div>
                                            </div>
                                        </fieldset>
            
                                    </form>
                                </div>
                            </div>

                           <!-- End of Fourth div -->

                        </div>
                </div>
            </div>
        </div>

         

 <?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/view-job.blade.php ENDPATH**/ ?>