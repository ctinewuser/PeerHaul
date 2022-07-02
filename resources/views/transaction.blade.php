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
                                        <center> <h4 class="card-title"> Transaction History List  </h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                            <thead>
                                                <tr><th>S.No.</th>
                                                 <th>Driver Name</th>  
                                                 <th>Paid Amount</th>
                                                
                                                 <th>Transaction ID</th>
                                                 <th>Status</th>
                                                   <th>Date / Time</th> 
                                                </tr>
                                            </thead>    
                                            <tbody>

                                             @php $i= 0; @endphp
                                              @foreach($trans as $list)   
                                               @php $i++; @endphp
                                                <tr>
                                            <td> {{$i}}. </td>
                                            <td> {{$list->name }} </td>
                                            <td> ${{$list->amount }} </td>
                                            
                                           
                                             <td> {{$list->transaction_id}}</td>
                                            <td> 
                                           @if($list->payment_status == 0) {{'Recieved '}} @else {{'Pending'}} @endif 
                                            </td>
                                            <td> {{date("d-M-Y", strtotime($list->created_at))}} </td>
                                                    
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
