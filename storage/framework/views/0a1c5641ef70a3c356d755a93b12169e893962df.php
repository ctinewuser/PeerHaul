<body>
     
    <div class="wrapper">
        <div class="sidebar" data-color="orange" data-image="<?php echo e(asset('public/assets/img/sidebar-5.jpg')); ?>">
          
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="javascript:void(0);" class="simple-text logo-mini">
                        PH
                    </a>
                    <a href="javascript:void(0);" class="simple-text logo-normal">
                        PEER HAUL APP
                    </a>
                </div>
                <div class="user">
                    <div class="photo">
                        <img src="<?php echo e(asset('public/assets/img/default-avatar.png')); ?>" />
                    </div>
                    <div class="info ">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            <span> Admin
                               <!-- <b class="caret"></b> -->
                            </span>
                        </a>
                         
                    </div>
                </div>

                <ul class="nav">
                  <?php $segment = request()->segment(1); ?>

                    <li class="nav-item <?php if($segment == 'dashboard'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('dashboard')); ?>">
                            <i class="nc-icon nc-grid-45"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item <?php if($segment == 'driver-list'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('driver-list')); ?>">
                            <i class="nc-icon nc-circle-09"></i>
                            <p> All Driver List</p>
                        </a>
                    </li>

                     <li class="nav-item <?php if($segment == 'customer-list'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('customer-list')); ?>">
                            <i class="nc-icon nc-single-02"></i>
                            <p>All Customer List </p>
                        </a>
                    </li>

                    <li class="nav-item <?php if($segment == 'job-details'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('job-details')); ?>">
                            <i class="nc-icon nc-bag"></i>
                            <p> Job List </p>
                        </a>
                    </li>

                     
                    <li class="nav-item <?php if($segment == 'vechicle-list'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('vechicle-list')); ?>">
                            <i class="nc-icon nc-delivery-fast"> </i>
                            <p> Vehicle Type List</p>
                        </a>
                    </li>
                    <li class="nav-item <?php if($segment == 'vehicle-info'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('vehicle-info')); ?>">
                            <i class="nc-icon nc-bus-front-12"> </i>
                            <p> Vehicle List</p>
                        </a>
                    </li>
                     <li class="nav-item <?php if($segment == 'parcel-list'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('parcel-list')); ?>">
                            <i class="nc-icon nc-backpack"> </i>
                            <p>Parcel Size List</p>
                        </a>
                    </li>
                     <li class="nav-item <?php if($segment == 'fees-list'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('fees-list')); ?>">
                            <i class="nc-icon nc-money-coins"> </i>
                            <p>PeerHaul Fee</p>
                        </a>
                    </li>
                     <li class="nav-item <?php if($segment == 'transaction-list'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('transaction-list')); ?>">
                            <i class="nc-icon nc-caps-small"> </i>
                            <p>Transaction History</p>
                        </a>
                    </li>
                      <li class="nav-item <?php if($segment == 'deadline-List'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('deadline-List')); ?>">
                            <i class="nc-icon nc-watch-time"> </i>
                            <p>Deadline List</p>
                        </a>
                    </li>
                    <!--  <li class="nav-item <?php if($segment == 'jobBid-List'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('jobBid-List')); ?>">
                            <i class="nc-icon nc-badge"> </i>
                            <p>Job Bid List</p>
                        </a>
                    </li> -->
                      <li class="nav-item <?php if($segment == 'popup-content-list'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('popup-content-list')); ?>">
                            <i class="nc-icon nc-chat-round"> </i>
                            <p>Popup Content</p>
                        </a>
                    </li> 
               <!--    <li class="nav-item <?php if($segment == 'review'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                     <a class="nav-link" href="<?php echo e(url('review')); ?>">
                            <i class="nc-icon nc-satisfied"> </i>
                            <p> Review List</p>
                        </a>
                 </li> -->
                 
                  <li class="nav-item <?php if($segment == 'terms-condition'): ?><?php echo e('active'); ?> <?php endif; ?> ">
                        <a class="nav-link" href="<?php echo e(url('terms-condition')); ?>">
                            <i class="nc-icon nc-chart"> </i>
                            <p>Terms And Condition</p>
                        </a>
                    </li> 
                  

                </ul>

                 </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->

            <nav class="navbar navbar-expand-lg ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-minimize">
                            <button id="minimizeSidebar" class="btn btn-warning btn-fill btn-round btn-icon d-none d-lg-block">
                                <i class="fa fa-ellipsis-v visible-on-sidebar-regular"></i>
                                <i class="fa fa-navicon visible-on-sidebar-mini"></i>
                            </button>
                        </div>

                        <a class="navbar-brand" href="#pablo"> Welcome Admin!  </a>
                    </div>
                    
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="nav navbar-nav mr-auto">
                             
                        </ul>
                        <ul class="navbar-nav">
                             
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="https://example.com/" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="nc-icon nc-bullet-list-67"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                    <a href="<?php echo e(url('logout')); ?>" class="dropdown-item text-danger">
                                        <i class="nc-icon nc-button-power"></i> Log out
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav><?php /**PATH /home3/ctinf0eg/public_html/CTIS/peerHaulApp/resources/views/sidebar.blade.php ENDPATH**/ ?>