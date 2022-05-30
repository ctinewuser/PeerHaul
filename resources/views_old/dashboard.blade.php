@include('header')
<!-- // Sidebar -->
@include('sidebar')
           
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                       
                        <div class="col-lg-3 col-sm-6">
                            <div class="card card-stats">

                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center icon-warning">
                                                <i class="nc-icon nc-single-02"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <a href="{{url('getAllUsers')}}"> <div class="numbers">
                                                <p class="card-category">No.of users </p>
                                                <h4 class="card-title"> <?php echo count($users); ?> </h4>
                                            </div> </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-refresh"></i> Users
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center icon-warning">
                                                <i class="nc-icon nc-light-3 text-success"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <div class="numbers">
                                                <p class="card-category"> No. of Users </p>
                                                <h4 class="card-title"> <?php ?> </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-calendar-o"></i> Users
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center icon-warning">
                                                <i class="nc-icon nc-vector text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <div class="numbers">
                                                <p class="card-category"> Active Users </p>
                                                <h4 class="card-title">23</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-clock-o"></i> Users
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center icon-warning">
                                                <i class="nc-icon nc-favourite-28 text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <div class="numbers">
                                                <p class="card-category"> Pending Users </p>
                                                <h4 class="card-title"> 10 </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                      <i class="fa fa-refresh"></i> Users
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

@include('footer')