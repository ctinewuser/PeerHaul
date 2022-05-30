@include('header')
@include('sidebar')

<!-- End Navbar -->
<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">

                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title"> Driver Details </h4>
                                </div>
                                <div class="card-body ">
                                    <form method="get" action="https://demos.creative-tim.com/" class="form-horizontal">
                                         
                                        <fieldset>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Name </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getDetails->name)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Email </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getDetails->email)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> phone </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getDetails->phone)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Address </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getDetails->address)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Referral code </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getDetails->my_referral_code)}}">
                                                   </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                       
                                        
                                      
                                        <fieldset>
                                            <div class="form-group">
                                                <div class="row">
                                               
                                                    <label class="col-sm-2 control-label"> File : </label>
                                                   <div class="col-sm-5">
                                                      <!-- <video width="400" height="450" controls>
													  <source src="{{$getDetails->profile_img}}" type="video/mp4">
													  Your browser does not support HTML video.
													  </video> -->

                                                      <img src="{{url('/public/uploads/profile_image/'.$getDetails->profile_img)}}" height="170" width="170"   type="image/jpg">
													
                                              
													</div>
                                                </div>
                                            </div>
                                        </fieldset>

                                       
                                        <fieldset>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Status </label>
                                                   <div class="col-sm-4">
                                                       <input type="text" name="title" class="form-control" value="@if($getDetails->status == 1){{'Deactive'}} @else{{'Active'}}@endif " readonly >
                                                   </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                         <!-- <button type="button" class="btn @if($getDetails->status == 1){{'btn-info'}} @else{{'btn-danger'}} @endif changeStatus" current-status="@if($getDetails->status == 1) {{'0'}} @else {{'1'}} @endif" data-id="{{$getDetails->id}}" title="Click to @if($getDetails->status == 0){{'Deactive '}}@else{{'Active'}}@endif" changeFor="news" > @if($getDetails->status == 0){{'Active'}} @else{{'Deactive'}}@endif </button>  -->

                                    </form>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

         

 @include('footer')