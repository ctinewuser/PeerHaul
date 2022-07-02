<?php echo $__env->make('header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <?php echo $__env->make('sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- End Navbar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12"> <?php if(session()->has('success_msg')): ?>
                    <div class="alert alert-info">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert"> <i class="nc-icon nc-simple-remove"></i> </button> <span> <b> Success - </b>  <?php echo e(session()->get('success_msg')); ?> </span> </div> <?php endif; ?> <?php if(session()->has('error_msg')): ?>
                    <div class="alert alert-danger">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert"> <i class="nc-icon nc-simple-remove"></i> </button> <span> <b> Fail - </b> <?php echo e(session()->get('error_msg')); ?></span> </div> <?php endif; ?> </div>
                <div class="card data-tables">
                    <div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
                        <div class="toolbar">
                            <center>
                                <h4 class="card-title">PopUp Contents</h4> </center>
                        </div>
                        <div class="fresh-datatables">
                            <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>PopUp Title</th>
                                        <th>PopUp Content</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody> <?php $i= 0; ?> <?php $__currentLoopData = $allpopup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $i++; ?>
                                    <tr>
                                        <td> <?php echo e($i); ?>. </td>
                                        <td> <?php echo e($pop->title); ?> </td>
                                        <td> <?php echo e($pop->content); ?> </td>
                                        <td class="text-right">
                                            <a href="<?php echo e(url('editContent/'.$pop->id)); ?>" class="btn btn-edit btn-info " data-toggle="modal" data-target="#myModal<?php echo e($pop->id); ?>"> <i class="fa fa-edit"> </i> </a>
                                        </td>
                                    </tr>
                                    <!--Add Modal Popup--->
                                    <!-- The Modal -->
                                    <div class="modal fade" id="myModal<?php echo e($pop->id); ?>" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <!--  <a class="close" data-dismiss="modal">×</a> -->
                                                    <h3><?php echo e($pop->title); ?></h3> </div>
                                                <div class="modal-body pt-0">
                                                    <form id="contactForm" name="contact" role="form" method="post" action="<?php echo e(url('update-content/'.$pop->id)); ?>"> <?php echo csrf_field(); ?>
                                                        <div class="form-group mb-0">
                                                           <!--  <label class="control-label pt-0"><?php echo e($pop->title); ?> </label> -->
                                                           <label class="control-label pt-0"> Content</label> 
                                                            <textarea name="content" class="form-control txtarea"><?php echo e($pop->content); ?> </textarea>
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
                                    <!-- The Modal --><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div> <?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/popup_content.blade.php ENDPATH**/ ?>