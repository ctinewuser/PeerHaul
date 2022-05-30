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
                                        &nbsp; <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"> Add Subadmin </button>
                                        <center> <h4 class="card-title"> Sub Users List </h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                            <thead>
                                                <tr><th>S.No.</th>
                                                    <th>Name</th>
                                                    <th>username</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th class="disabled-sorting text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Name</th>
                                                    <th>Username</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>

                                             @php $i= 0; @endphp
                                              @foreach($allUsers as $users)   
                                               @php $i++; @endphp

                                                <tr>
                                                    <td> {{$i}}. </td>
                                                    <td> {{$users->name }} </td>
                                                    <td> {{$users->username}} </td>
                                                    <td> {{$users->email}} </td>
                                                    <td> {{$users->contact}} </td>
                                                    <td> {{date('d/m/Y',strtotime($users->created_at)) }} </td>
                                                    <td> @if($users->status == 0){{'Active'}} @else {{'Deactive'}} @endif </td>

                                                    <td class="text-right">

                                                      <button class="btn btn-link btn-danger changeStatus" current-status="@if($users->status == 1) {{'0'}} @else {{'1'}} @endif" data-id="{{$users->id}}" title="
                                                        Click to @if($users->status == 1){{'activate'}}@else{{'deactivate'}} @endif" changeFor="admin" >@if($users->status == 1){{'Activate'}}@else{{'Deactivate'}} @endif </button> 
                                                      
                                                      <a href="{{url('removeAdmin/'.encrypt($users->id))}}" class="btn btn-link btn-danger" onclick="return confirm('Do you really want to delete');" title="Remove Admin" > <i class="fa fa-times"> </i> </a> 
                                                       
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
            <!--  -->
        </div>
    </div>

 <!-- Add Modal -->

 <!-- Modal -->
 
 <div class="modal fade exampleModal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title titleHtml" id="exampleModalLabel"> Sub-Admin </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
         
         <form method="post" action="{{url('addSubAdmin')}}" >
          @csrf

          <div class="form-group row">

            <div class="col-md-6">
            <label for="fname">Name:</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Name" required >
            </div>
             
            <div class="col-md-6">
            <label for="fname">Email:</label>
            <input type="text" id="email" name="email" class="form-control" placeholder="Email" required >
            </div>

          </div>

          <div class="form-group row">

          <div class="col-md-6" >
          <label for="lname">Username:</label>
          <input type="text" id="username" name="username" class="form-control" placeholder="Username" required >
          </div>

          <div class="col-md-6" >
          <label for="lname">Password:</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Password" required >
          </div>

          </div>

          <div class="form-group row">

          <div class="col-md-6" >
          <label for="lname"> Contact:</label>
          <input type="text" id="contact" name="contact" class="form-control" placeholder="Contact" required >
          </div>

          <div class="col-md-6" > 
          <label for="lname"> Role :</label>
           <select id="role" name="role" class="form-control" required > 
             <option value=""> Select Role </option> <option value="1"> Subadmin users </option> <option value="2"> Sub editors </option> </select>
          </div>
          </div>

      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-info" >Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
        </form>
  </div>
</div>

@include('footer')