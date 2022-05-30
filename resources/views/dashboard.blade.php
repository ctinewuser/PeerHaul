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
                                          <!--   <a href=""> --> <div class="numbers">
                                                <p class="card-category">No.of Drivers </p>
                                                <h4 class="card-title"> <?php echo count($users); ?> </h4>
                                            </div> <!-- </a> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-refresh"></i> Drivers
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
                                                <p class="card-category">No. of Customers</p>
                                                <h4 class="card-title"> <?php echo count($customer); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-calendar-o"></i> Customers
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
                                                <p class="card-category"> Active Delivery </p>
                                                <h4 class="card-title"><?php echo count($active); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-clock-o"></i> Deliveries
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
                                                <p class="card-category"> Pending Delivery </p>
                                                <h4 class="card-title"> <?php echo count($delivery); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <hr>
                                    <div class="stats">
                                      <i class="fa fa-refresh"></i> Deliveries
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

@include('footer')