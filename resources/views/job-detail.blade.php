@include('header')
@include('sidebar')

<!-- End Navbar -->
<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">

                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title"> Details of Vechicle Detail List  </h4>
                                </div>
                                <div class="card-body ">
                                    <form method="get" action="https://demos.creative-tim.com/" class="form-horizontal">
                                         
                                        <fieldset>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> PickUp Location </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->pick_up_location)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> Drop Location </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->drop_off_location)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> estimate_price </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->estimate_price)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label"> parcel_size </label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->parcel_size)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Customer Name</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->name)}}">
                                                   </div>
                                                </div>
                                            </div>
                                           
                                           
                                        </fieldset>
            
                                    </form>
                                </div>
                            </div>
<!-- Finish One div -->

                                <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title"> Details of Item List  </h4>
                                </div>
                                <div class="card-body ">
                                    <form method="get" action="https://demos.creative-tim.com/" class="form-horizontal">
                                         
                                        <fieldset>
                                          
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Descriptive Title</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->descriptive_title)}}">
                                                   </div>
                                                </div>
                                            </div>
                                      
                                          
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Receiver Name</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->receiver_name)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Receiver Contact</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->receiver_contact)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Delivery Date</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->delivery_date)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Order Reference Number</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->order_ref_number)}}">
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Quantity Items</label>
                                                   <div class="col-sm-10">
                                                       <input type="text" name="title" class="form-control" value="{{ucfirst($getjobDetails->quantity_items)}}">
                                                   </div>
                                                </div>
                                            </div>
                                        </fieldset>
            
                                    </form>
                                </div>
                            </div>

                            <!-- End of second div -->




                        </div>
                </div>
            </div>
        </div>

         

 @include('footer')