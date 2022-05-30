@include('header')
@include('sidebar')

<!-- End Navbar -->
<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">

                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title"> Terms And Condition </h4>
                                </div>

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
                                <div class="card-body ">
                                    <form method="post" action="{{url('update-terms-condition')}}" class="form-horizontal">
                                         @csrf
                                        <fieldset>
                                            <div class="form-group">
                                                <div class="row">
                                                   <!--<label class="col-sm-2 control-label">Terms And Condition </label>-->
                                                   <div class="col-sm-12">
                                                       <textarea type="text" name="info" class="form-control ckeditor" >{{$allTerms['info']}}</textarea>
                                                   </div>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <div class="row">
                                                   <label class="col-sm-2 control-label">Payment Terms </label>
                                                   <div class="col-sm-10">
                                                       <textarea type="text" name="payment_terms" class="form-control ckeditor" >{{$allTerms['payment_terms']}}</textarea>
                                                   </div>
                                                </div>
                                            </div> -->
                                           
                                        </fieldset>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                </div>
            </div>
        </div>

         

 @include('footer')