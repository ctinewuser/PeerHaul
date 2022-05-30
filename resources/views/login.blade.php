<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title> Peer Haul APP ADMIN </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
     
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('public/assets/css/font-awesome.min.css') }}" />
    <!-- CSS Files -->
    <link href="{{asset('public/assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('public/assets/css/light-bootstrap-dashboard790f.css?v=2.0.1')}}" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{asset('public/assets/css/demo.css')}}" rel="stylesheet" />
    </head>
    <body>
     
    <div class="wrapper wrapper-full-page">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute">
            <div class="container">
                <div class="navbar-wrapper">
                    <a class="navbar-brand" href="#pablo"> PEER HAUL ADMIN LOGIN </a>
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="full-page  section-image" data-color="black" data-image="{{asset('public/assets/img/full-screen-image-2.jpg')}}" ;>
             <div class="content">
                <div class="container">
                    <div class="col-md-4 col-sm-6 ml-auto mr-auto">
                        <form class="form" method="get" action="{{url('adminLogin')}}" >
                            @csrf
                            <div class="card card-login card-hidden">
                                <div class="card-header ">
                                    <h3 class="header text-center">Login</h3>
                                </div>
                                @if (Session::has('error_msg'))
                                <div class="alert alert-danger">{{ Session::get('error_msg') }}</div>
                                @endif
                                <div class="card-body ">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label> Email </label>
                                            <input type="email" placeholder="Enter Email" class="form-control" name="email" required >
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" placeholder="Enter Password" class="form-control" name="password" required >
                                        </div>
                                         
                                    </div>
                                </div>
                                <div class="card-footer ml-auto mr-auto">
                                    <button type="submit" class="btn btn-warning btn-wd">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer"> 
        </footer>
    </div> 
</body>
@include('footer')