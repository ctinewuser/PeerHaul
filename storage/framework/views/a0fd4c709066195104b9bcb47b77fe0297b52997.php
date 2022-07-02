<!-- Modal -->
<div class="modal fade exampleModal" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titleHtml" id="exampleModalLabel"> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table width="100%">
          <thead>
          </thead>
          <tbody class="table putHtml">
              
          </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--   Core JS Files   -->
<script src="<?php echo e(asset('public/assets/js/core/jquery.3.2.1.min.js')); ?>" type="text/javascript" ></script>
<script src="<?php echo e(asset('public/assets/js/core/popper.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('public/assets/js/core/bootstrap.min.js')); ?>" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: https://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="<?php echo e(asset('public/assets/js/plugins/bootstrap-switch.js')); ?>"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2Yno10-YTnLjjn_Vtk0V8cdcY5lC4plU"></script>
<!--  Chartist Plugin  -->
<script src="<?php echo e(asset('public/assets/js/plugins/chartist.min.js')); ?>"></script>
<script src="<?php echo e(asset('public/assets/js/plugins/bootstrap-notify.js')); ?>"></script>
<!--  Share Plugin -->
<script src="<?php echo e(asset('public/assets/js/plugins/jquery.sharrre.js')); ?>"></script>
<!--  jVector Map  -->
<script src="<?php echo e(asset('public/assets/js/plugins/jquery-jvectormap.js')); ?>" type="text/javascript"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="<?php echo e(asset('public/assets/js/plugins/moment.min.js')); ?>"></script>
<!--  DatetimePicker   -->
<script src="<?php echo e(asset('public/assets/js/plugins/bootstrap-datetimepicker.js')); ?>"></script>
<!--  Sweet Alert  -->
<script src="<?php echo e(asset('public/assets/js/plugins/sweetalert2.min.js')); ?>" type="text/javascript"></script>
<!--  Tags Input  -->
<script src="<?php echo e(asset('public/assets/js/plugins/bootstrap-tagsinput.js')); ?>" type="text/javascript"></script>
<!--  Sliders  -->
<script src="<?php echo e(asset('public/assets/js/plugins/nouislider.js')); ?>" type="text/javascript"></script>
<!--  Bootstrap Select  -->
<script src="<?php echo e(asset('public/assets/js/plugins/bootstrap-selectpicker.js')); ?>" type="text/javascript"></script>
<!--  jQueryValidate  -->
<script src="<?php echo e(asset('public/assets/js/plugins/jquery.validate.min.js')); ?>" type="text/javascript"></script>
<!--  Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
<script src="<?php echo e(asset('public/assets/js/plugins/jquery.bootstrap-wizard.js')); ?>"></script>
<!--  Bootstrap Table Plugin -->
<script src="<?php echo e(asset('public/assets/js/plugins/bootstrap-table.js')); ?>"></script>
<!--  DataTable Plugin -->
<script src="<?php echo e(asset('public/assets/js/plugins/jquery.dataTables.min.js')); ?>"></script>
<!--  Full Calendar   -->
<script src="<?php echo e(asset('public/assets/js/plugins/fullcalendar.min.js')); ?>"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?php echo e(asset('public/assets/js/light-bootstrap-dashboard790f.js')); ?>?v=2.0.1" type="text/javascript"></script>
<!-- Light Dashboard DEMO methods, don't include it in your project! -->
<script src="<?php echo e(asset('public/assets/js/demo.js')); ?>"></script>
<script src="<?php echo e(asset('public/assets/js/plugins/jquery.bootstrap-wizard.js')); ?>"></script>
<script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        demo.checkFullPageBackgroundImage();

        setTimeout(function() {
            // after 1000 ms we add the class animated to the login/register card
            $('.card').removeClass('card-hidden');
        }, 700)
    });

        $(document).ready(function() {
        $('#datatables').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records",
            }

        });


        var table = $('#datatables').DataTable();

        // Edit record
        table.on('click', '.edit', function() {
            $tr = $(this).closest('tr');

            if ($tr.hasClass('child')) {
                $tr = $tr.prev('.parent');
            }

            var data = table.row($tr).data();
            alert('You press on Row: ' + data[0] + ' ' + data[1] + ' ' + data[2] + '\'s row.');
        });

        // Delete a record
        table.on('click', '.remove', function(e) {
            $tr = $(this).closest('tr');
            table.row($tr).remove().draw();
            e.preventDefault();
        });

        //Like record
        table.on('click', '.like', function() {
            alert('You clicked on Like button');
        });
    });
 
// Custom Functions

$('body').on('click','.changeStatus',function() {
    
  if(confirm('Do you want to change status')){

  var userID = $(this).attr('data-id') ;
  var status = $(this).attr('current-status') ;
  var changeFor = $(this).attr('changeFor') ;
 
   $.ajax({
            type: "post",
            url: "<?php echo e(url('changeUserSt')); ?>" ,
            data:{ "_token": "<?php echo e(csrf_token()); ?>",userID:userID,status:status,changeFor:changeFor},
            dataType: "json",
             success: function (response) {
               if(response.success == 1){  
                   alert(response.msg);
                   location.reload();
                 } else {
                   alert(response.msg);
                   location.reload();
               }
             }
          });
       }
   }) ;

 $('body').on('click','.followsUser',function() {
    
    var userID = $(this).attr('data-id') ;
    var forFollow = $(this).attr('forFollow') ;
 
    $.ajax({
            type: "post",
            url: "<?php echo e(url('showFollow')); ?>" ,
            data:{ "_token": "<?php echo e(csrf_token()); ?>",userID:userID,forFollow:forFollow},
            dataType: "json",
             success: function (response) {
                $('.putHtml').html(response.msg) ;
                $('.titleHtml').html(response.type) ;
             }
        });
     }) ;

   </script>

 </body>

</html><?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/footer.blade.php ENDPATH**/ ?>