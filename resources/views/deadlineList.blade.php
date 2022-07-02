
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
                                        <center> <h4 class="card-title"> Deadline List  </h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                            <thead>
                                                <tr><th>S.No.</th>
                                                    <th>Type</th>
                                                 <th>Taken Time</th>   
                                                 <th>Action</th>
                                                </tr>
                                            </thead>    
                                            <tbody>

                                             @php $i= 0; 
                                            
                                             @endphp
                                              @foreach($list as $k)   
                                               @php $i++; @endphp
                                                <tr>
                                                    <td> {{$i}}. </td>
                                                     <td>  @if($k->type == 0) {{'Normal'}} @else {{'Express'}} @endif  </td>
                                                    <td> {{$k->taken_time }} </td>
                                     
                                                     <td> <a href="{{url('editDeadline/'.$k->id)}}" class="btn btn-edit btn-info" data-toggle="modal" data-target="#deadlineModal{{$k->id}}" > <i class="fa fa-edit"> </i> </a>

                                                       <a title="delete" href="{{url('removeDeadline/'.encrypt($k->id))}}" class="btn btn-link btn-danger" onclick="return confirm('Do you really want to delete it!');"> <i class="fa fa-times"> </i> </a>
                                                          
                                                     </td>
                                                </tr>
                                        <!--Add Modal Popup--->
                                    <!-- The Modal -->
                                    <div class="modal fade" id="deadlineModal{{$k->id}}" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                              
                                                   <h4>Edit Your Deadline Time</h4>
                                                </div>

                                                <div class="modal-body pt-0">
                                                    <form id="contactForm" name="contact" role="form" method="post" action="{{url('update-deadline/'.$k->id)}}">
                                                     @csrf
                                                <div class="form-group mb-0"> 

                                                <label for="type" class="control-label">User Select</label>
                                                <select name="type" class="form-control" id="type" >

                                       <option {{$k->type}} value="0" @if($k->type == 0) {{'selected'}}@endif> Normal
                                       </option>

                                         <option {{$k->type}} value="1" @if($k->type == 1) {{'selected'}}@endif  > Express </option>
                                                </select>

                                          <label for="taken_time">Duration</label>

                                                <input type="text" class="form-control" name="taken_time" id="taken_time" value="{{$k->taken_time}}" >

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
