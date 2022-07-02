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

                                             @php $i= 0; @endphp
                                              @foreach($fees as $list)   
                                               @php $i++;
                                               @endphp
                                                    <tr>
                                                    <td> {{$i}}. </td>
                                                    <td> {{$list->name }} </td>
                                                    <td> ${{$list->parcel_fees }} </td>
                                                    <td> @if($list->route == 1) {{'Short Route '}} @else {{'Long Route'}} @endif </td>
                                                    <td> {{$list->route_per_km }} km </td>
                                                    <td> ${{$list->service_fee }} </td>
                                                    <td> ${{$list->peerHaul_fee }} </td>
                                                    <td> ${{$list->fees_per_km }} </td>
                                                    <td> ${{$list->fees_per_hr }} </td>
                                                 

                                  
                                          <td> <a href="{{url('editFees/'.$list->id)}}" class="btn btn-edit btn-info" data-toggle="modal" data-target="#feesModal{{$list->id}}" > <i class="fa fa-edit"> </i> </a></td>

                                                </tr>
                                               <!--Add Modal Popup--->
                                    <!-- The Modal -->
                                    <div class="modal fade" id="feesModal{{$list->id}}" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                
                                                   <h4>Edit Your Fees Detail</h4>
                                                </div>

                                                <div class="modal-body pt-0">
                                                    <form id="contactForm" name="contact" role="form" method="post" action="{{url('update-fees/'.$list->id)}}">
                                                     @csrf
                                                <div class="form-group mb-0"> 

                                            <label for="service_fee">Service Fees</label>

                                             <input type="text" class="form-control" name="service_fee" id="service_fee" value="{{$list->service_fee}}" >

                                            <label for="peerHaul_fee">Peerhaul fees</label>

                                             <input type="text" class="form-control" name="peerHaul_fee" id="peerHaul_fee" value="{{$list->peerHaul_fee}}" >

                                              <label for="parcel_fees"> Parcel Fees</label>

                                             <input type="text" class="form-control" name="parcel_fees" id="parcel_fees" value="{{$list->parcel_fees}}" >

                                               <label for="fees_per_km">Fees(per km)</label>

                                             <input type="text" class="form-control" name="fees_per_km" id="fees_per_km" value="{{$list->fees_per_km}}" >

                                             <label for="fees_per_hr">Fees(per hr)</label>

                                             <input type="text" class="form-control" name="fees_per_hr" id="fees_per_hr" value="{{$list->fees_per_hr}}" >
                                          

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
