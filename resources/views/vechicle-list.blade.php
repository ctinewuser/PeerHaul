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
                                        <center> <h4 class="card-title"> Vehicle Type List  </h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                            <thead>
                                                <tr><th>S.No.</th>
                                                <th>Vehicle  Name</th>
                                                    <th>Date / Time</th>
                                                  
                                                  
                                                </tr>
                                            </thead>
                                           
                                            <tbody>

                                             @php $i= 0; @endphp
                                              @foreach($allVechicle as $users)   
                                               @php $i++; @endphp

                                                <tr>
                                                    <td> {{$i}}. </td>
                                                  
                                                    <td> {{$users->vehicle_name }} </td>
                                                    <td> {{date("d-M-Y", strtotime($users->created_at))}} </td>
                                                    
<!--                                      <td class="text-right">

                                                      <button class="btn btn-link btn-danger changeStatus" current-status="@if($users->status == 1) {{'0'}} @else {{'1'}} @endif" data-id="{{$users->id}}" title="Click to @if($users->status == 1){{'Activate'}} @else {{'Deactivate'}} @endif" changeFor="user" > @if($users->status == 1) {{'Active'}} @else {{'Deactive'}} @endif </button> 
                                                      
                                                      <a href="{{url('viewCustomer/'.$users->id)}}" class="btn btn-eye btn-info"  > <i class="fa fa-eye"> </i> </a>

                                                      <a href="{{url('removeUser/'.encrypt($users->id))}}" class="btn btn-link btn-danger" onclick="return confirm('Do you really want to delete');"> <i class="fa fa-times"> </i> </a>
                                                       
                                                    </td> -->

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