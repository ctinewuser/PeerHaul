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

                                             @php $i= 0; @endphp
                                              @foreach($allUsers as $users)   
                                               @php $i++; @endphp

                                                <tr>
                                                    <td> {{$i}}. </td>
                                                    <td> @if($users->profile_img!='')


                                                        
                                                        <img src="{{url('public/uploads/profile_image/'.$users->profile_img)}}" height="70" width="70" >
                                                         @endif </td>
                                                    <td> {{$users->name }} </td>
                                                    <td> {{$users->email }} </td>
                                                    <td> {{$users->phone }} </td>
                                                    <td> {{date('d/m/Y',strtotime($users->created_at)) }} </td>
                                                    <td> @if($users->status == 1) {{'Deactive'}} @elseif($users->status == 3) {{'Blocked'}} @else {{'Active'}} @endif </td>

                                                    <td> 

                                                    <button class="btn btn-link btn-info" title="Click to view"  > <a title="View Details" href="{{url('viewReviews/'.($users->id))}}" class="btn btn-eye btn-info" > Reviews </a>  </button> </td>
                                                    
                                                    <td class="text-right">

                                                   <a title="Block Driver" href="{{url('blockDriver/'.$users->id)}}" class="btn btn-eye btn-info"  > <i class="fa fa-ban"> </i> </a>
                                                    
                                        <a title="View Details" href="{{url('viewUser/'.($users->id))}}" class="btn btn-eye btn-info" > <i class="fa fa-eye"> </i> </a>

                                        <a title="delete" href="{{url('removeUser/'.encrypt($users->id))}}" class="btn btn-link btn-danger" onclick="return confirm('Do you really want to delete driver');"> <i class="fa fa-times"> </i> </a>
                                                       
                                        </td>

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