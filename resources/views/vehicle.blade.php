@include('header')
  @include('sidebar')

            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

         <div class="col-md-12">
            @if(session()->has('success_msg'))
                        
            <div class="alert alert-info">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span> <b> Success - </b>  {{ session()->get('success_msg') }} </span>
            </div>

            @endif

            @if(session()->has('error_msg'))

            <div class="alert alert-danger">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert">
                  <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span> <b> Fail - </b> {{ session()->get('error_msg') }}</span>
            </div>

            @endif
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
                                                <th>Vechicle Make</th>
                                                    <th>Vehicle Modal</th>
                                                    <th>Vehicle Color</th> 
                                                    <th>Vehicle Type</th> 
                                                    <th>License No.</th>
                                                  <th>License Image</th>
                                                    <th>Vehicle Image</th>
                                                  
                                                </tr>
                                            </thead>
                                          
                                            <tbody>

                                             @php $i= 0; @endphp
                                              @foreach($allVehicleinfo as $users)   
                                               @php $i++; @endphp

                                                <tr>
                                                    <td> {{$i}}. </td>
                                                    <td> {{$users->name }} </td>
                                                    <td> {{$users->vechicle_make }} </td>
                                                    <td> {{$users->vechicle_model }} </td>
                                                  
                                                    <td> {{$users->vechicle_color }} </td>
                                                    <td> {{$users->vechicle_type }} </td>
                                                <td> {{$users->vechicle_license_plate }} </td>
                                                <td> @if($users->upload_vehicle_image!='')<img src="{{URL::to('public/uploads/vehicle/'.$users->driver_license_front)}}" height="70" width="70" > @endif </td>
                                                
                                                  <td> @if($users->upload_vehicle_image!='')<img src="{{URL::to('public/uploads/vehicle/'.$users->upload_vehicle_image)}}" height="70" width="70" > @endif </td>

                                                </tr>

                                               @endforeach  
                                                
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
 
@include('footer')