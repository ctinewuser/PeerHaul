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
                                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                                        <center> <h4 class="card-title"> Unapproved Posts List </h4> </center>
                                    </div>
                                    <div class="fresh-datatables">
                                        <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                             <thead>
                                                <tr><th>S.No.</th>
                                                   <th>Title</th>
                                                    <!-- <th>Description</th> -->
                                                    <!-- <th>File</th> -->
                                                    <th>Like</th>
                                                    <th>Comments</th>
                                                    <th>Share</th>
                                                    <th>Date</th>
                                                    <th>Approve <br> Status </th>
                                                    <th class="disabled-sorting text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Title</th>
                                                   <!-- <th>Description</th> -->
                                                   <!-- <th>File</th> -->
                                                    <th>Like</th>
                                                    <th>Comments</th>
                                                    <th>Share</th>
                                                    <th>Date</th>
                                                    <th> Approve </th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>

                                             @php $i= 0; @endphp
                                              @foreach($allNews as $news)   
                                               @php $i++; @endphp

                                                <tr>
                                                    <td> {{$i}}. </td>
                                                    <td> {{ ucfirst($news->title) }} </td>
                                                   <!--  <td> {{ ucfirst($news->description) }} </td> -->
                                                  <!--   <td> {{$news->file }} </td> -->
                                                    <td> {{$news->like_count }} </td>
                                                    <td> {{$news->comment_count }} </td>
                                                    <td> {{$news->share_count }} </td>
                                                    <td> {{date('d-m-Y',strtotime($news->created_at)) }} </td>
                                                    <td> @if($news->status == 0){{'Approved'}}@else{{'Disapproved'}}@endif </td>
                                                    <td class="text-right">
                                                         <button class="btn btn-link btn-danger changeStatus" current-status="@if($news->status == 1) {{'0'}} @else {{'1'}} @endif" data-id="{{$news->id}}" title="Click to @if($news->status == 0){{'Disapprove '}}@else{{'Approve'}}@endif" changeFor="news" > @if($news->status == 0){{'Disapprove'}} @else{{'Approve'}}@endif </button> 
                                                    
                                                        <a target="_blank" href="{{url('viewSinglePost/'.encrypt($news->id))}}" class="btn btn-link btn-warning" > <i class="fa fa-eye"> </i> </a>

                                                        <a href="{{url('removePost/'.encrypt($news->id))}}" class="btn btn-link btn-danger" onclick="return confirm('Do you really want to delete');"> <i class="fa fa-times"> </i> </a>
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
 
@include('footer')