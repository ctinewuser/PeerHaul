<body>
     
    <div class="wrapper">
        <div class="sidebar" data-color="orange" data-image="{{ asset('public/assets/img/sidebar-5.jpg')}}">
          
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
                        <img src="{{ asset('public/assets/img/default-avatar.png')}}" />
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

                    <li class="nav-item @if($segment == 'dashboard'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('dashboard')}}">
                            <i class="nc-icon nc-grid-45"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item @if($segment == 'driver-list'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('driver-list')}}">
                            <i class="nc-icon nc-circle-09"></i>
                            <p> All Driver List</p>
                        </a>
                    </li>

                     <li class="nav-item @if($segment == 'customer-list'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('customer-list')}}">
                            <i class="nc-icon nc-single-02"></i>
                            <p>All Customer List </p>
                        </a>
                    </li>

                    <li class="nav-item @if($segment == 'job-details'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('job-details')}}">
                            <i class="nc-icon nc-bag"></i>
                            <p> Job Details </p>
                        </a>
                    </li>

                     
                    <li class="nav-item @if($segment == 'vechicle-list'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('vechicle-list')}}">
                            <i class="nc-icon nc-delivery-fast"> </i>
                            <p> Vehicle Type List</p>
                        </a>
                    </li>
                    <li class="nav-item @if($segment == 'vehicle-info'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('vehicle-info')}}">
                            <i class="nc-icon nc-bus-front-12"> </i>
                            <p> Vehicle List</p>
                        </a>
                    </li>
                     <li class="nav-item @if($segment == 'parcel-list'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('parcel-list')}}">
                            <i class="nc-icon nc-backpack"> </i>
                            <p>Parcel List</p>
                        </a>
                    </li>
                      <li class="nav-item @if($segment == 'deadline-List'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('deadline-List')}}">
                            <i class="nc-icon nc-watch-time"> </i>
                            <p>Deadline List</p>
                        </a>
                    </li>
                     <li class="nav-item @if($segment == 'jobBid-List'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('jobBid-List')}}">
                            <i class="nc-icon nc-badge"> </i>
                            <p>Job Bid List</p>
                        </a>
                    </li>
                      <li class="nav-item @if($segment == 'popup-content-list'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('popup-content-list')}}">
                            <i class="nc-icon nc-chat-round"> </i>
                            <p>Popup Content</p>
                        </a>
                    </li> 
                    <li class="nav-item @if($segment == 'review'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('review')}}">
                            <i class="nc-icon nc-satisfied"> </i>
                            <p> Review</p>
                        </a>
                    </li>
                 
                  <li class="nav-item @if($segment == 'terms-condition'){{'active'}} @endif ">
                        <a class="nav-link" href="{{url('terms-condition')}}">
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
                                    <a href="{{url('logout')}}" class="dropdown-item text-danger">
                                        <i class="nc-icon nc-button-power"></i> Log out
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>