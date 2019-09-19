<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$user->name}} | Portfolio</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{asset('client/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="{{asset('client/vendor/font-awesome/css/font-awesome.min.css')}}">
    <!-- Google fonts - Roboto + Roboto Slab-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,700%7CRoboto:400,700,300">
    <!-- owl carousel-->
    <link rel="stylesheet" href="{{asset('client/vendor/owl.carousel/assets/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('client/vendor/owl.carousel/assets/owl.theme.default.css')}}">
    <!-- animate.css-->
    <link rel="stylesheet" href="{{asset('client/vendor/animate.css/animate.css')}}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{asset('client/css/style.default.css')}}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{asset('client/css/custom.css')}}">
    <!-- Leaflet CSS - For the map-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.4.0/leaflet.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('client/img/favicon.png')}}">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    </head>
    <body>
        <!-- Reference item-->
        <!-- navbar-->
        <header class="header">
            <nav class="navbar navbar-expand-lg fixed-top">
                <div class="container"><a href="#intro" class="navbar-brand scrollTo">{{$user->name}}</a>
                    <button type="button" data-toggle="collapse" data-target="#navbarcollapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right"><span class="fa fa-bars"></span></button>
                    <div id="navbarcollapse" class="collapse navbar-collapse">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item"><a href="#intro" class="nav-link link-scroll">Intro</a></li>
                            <li class="nav-item"><a href="#about" class="nav-link link-scroll">About</a></li>
                            <li class="nav-item"><a href="#services" class="nav-link link-scroll">Services</a></li>
                            <li class="nav-item"><a href="#testimonials" class="nav-link link-scroll">Experiences</a></li>
                            <li class="nav-item"><a href="#references" class="nav-link link-scroll">My work</a></li>
                            <li class="nav-item"><a href="#customers" class="nav-link link-scroll">Technology</a></li>
                            <li class="nav-item"><a href="#contact" class="nav-link link-scroll">Contact</a></li>
                            {{-- <li class="nav-item"><a target="_blank" href="{{asset('storage/images/cv/cv.pdf')}}" class="nav-link link-scroll">My CV</a></li> --}}

                            {{-- Hosting --}}
                            <li class="nav-item"><a target="_blank" href="{{asset('images/cv/cv.pdf')}}" class="nav-link link-scroll">My CV</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <!-- Intro Image-->
        <section id="intro" style="background: url({{asset('client/img/home2.jpg')}}) center center no-repeat; background-size: cover;" class="intro-section pb-2">
            <div class="container text-center">
                <div data-animate="fadeInDown" class="logo"><img src="{{asset('client/img/favicon.png')}}" alt="logo" width="130"></div>
                <h1 data-animate="fadeInDown" class="text-shadow mb-5">Hello, Welcome!!!</h1>
                <p data-animate="slideInUp" class="h3 text-shadow text-400">I'm a Web Developer and i make awesome stuff!</p>
            </div>
        </section>
        <!-- About-->
        <section id="about" class="about-section">
            <div class="container">
                <header class="text-center">
                    <h2 data-animate="fadeInDown" class="title">About me</h2>
                </header>
                <div class="row">
                    <div data-animate="fadeInUp" class="col-lg-6">
                        <p>{{$profile->about}}</p>
                    </div>
                    <div data-animate="fadeInUp" class="col-lg-6">
                        @foreach ($skill as $item)
                            <div class="skill-item">
                                <div class="progress-title">{{$item->name}}</div>
                                <div class="progress">
                                    <div role="progressbar" style="width: {{$item->percentage}}%" aria-valuenow="0" aria-valuemin="{{$item->percentage}}" aria-valuemax="100" class="progress-bar progress-bar-skill1"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Hosting --}}
                    <div data-animate="fadeInUp" class="col-sm-6 mx-auto mt-5"><img src="{{asset('images/avatar/'.$profile->avatar)}}" alt="This is me - IT worker" class="image rounded-circle img-fluid"></div>

                    {{-- <div data-animate="fadeInUp" class="col-sm-6 mx-auto mt-5"><img src="{{asset('storage/images/avatar/'.$profile->avatar)}}" alt="This is me - IT worker" class="image rounded-circle img-fluid"></div> --}}
                </div>
            </div>
        </section>
        <!-- Service-->
        <section id="services" class="bg-gradient services-section">
            <div class="container">
                <header class="text-center">
                    <h2 data-animate="fadeInDown" class="title">Services</h2>
                </header>
                <div class="row services text-center">
                    @foreach ($service as $item)
                        <div data-animate="fadeInUp" class="col-lg-4">
                            <div class="icon">{!! $item->icon !!}</div>
                            <h3 class="heading mb-3 text-400">{{$item->name}}</h3>
                            <p class="text-left description">{{$item->desc}}</p>
                        </div>
                    @endforeach
                </div>
                <hr data-animate="fadeInUp">
                <div data-animate="fadeInUp" class="text-center">
                    <p class="lead">Would you like to know more or just discuss something?</p>
                    <p><a href="#contact" class="btn btn-outline-light link-scroll">Contact me</a></p>
                </div>
            </div>
        </section>
        <!-- Testimonials-->
        <section id="testimonials" class="testimonials-section bg-gray">
            <div class="container">
                <header class="text-center mb-2">
                    <h2 data-animate="fadeInUp" class="title">My Experiences</h2>
                    <p data-animate="fadeInUp" class="lead">This is all of my experiences work in amazing places</p>
                </header>
                <ul data-animate="fadeInUp" class="owl-carousel owl-theme testimonials equalize-height">
                    @foreach ($experience as $item)
                        <li class="item">
                            <div class="testimonial full-height">
                                <div class="text">
                                    <h3>{{$item->company}}</h3>
                                    <h6><i class="fas fa-user"></i> {{$item->position}}</h6>
                                </div>
                                <div class="bottom">
                                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                    <div class="name-picture">
                                        <p>From {{$item->start_date}}</p>
                                        <p>
                                            Until 
                                            @if ($item->current_job == 1)
                                                Now
                                            @else
                                                {{$item->end_date}}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
        <!-- Statistics-->
        <section id="statistics" data-dir="up" style="background: url(&quot;client/img/parallax2.jpg&quot;);" class="statistics-section text-white parallax parallax">
            <div class="container">
                <div class="row showcase text-center"> 
                    <div data-animate="fadeInUp" class="col-lg-3 col-md-6">
                        <div class="item">
                            <div class="icon"><i class="fa fa-user"></i></div>
                            <h5 class="text-400 mt-4 text-uppercase"><span class="counter">{{$count_personal}}</span><br>Personal Project</h5>
                        </div>
                    </div>
                    <div data-animate="fadeInUp" class="col-lg-3 col-md-6">
                        <div class="item">
                            <div class="icon"><i class="fa fa-users"></i></div>
                            <h5 class="text-400 mt-4 text-uppercase"><span class="counter">{{$count_client}}</span><br>Client Project</h5>
                        </div>
                    </div>
                    <div data-animate="fadeInUp" class="col-lg-3 col-md-6">
                        <div class="item">
                            <div class="icon"><i class="fa fa-cogs"></i></div>
                            <h5 class="text-400 mt-4 text-uppercase"><span class="counter">{{$count_tech}}</span><br>Technology Mastered</h5>
                        </div>
                    </div>
                    <div data-animate="fadeInUp" class="col-lg-3 col-md-6">
                        <div class="item">
                            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                            <h5 class="text-400 mt-4 text-uppercase"><span class="counter">{{$count_month}}</span><br>Month Experience</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dark-mask"></div>
        </section>
        <!--
            *** REFERENCES IMAGE ***
            _________________________________________________________
        -->
        <section id="references">
            <div class="container">
                <div class="col-sm-12">
                    <div class="mb-5 text-center">
                        <h2 data-animate="fadeInUp" class="title">My work</h2>
                        <p data-animate="fadeInUp" class="lead">I have worked on dozens of projects so I have picked only the latest for you.</p>
                    </div>
                    <ul id="filter" data-animate="fadeInUp">
                        <li class="active"><a href="#" data-filter="all">All</a></li>
                        <li><a href="#" data-filter="personal">Personal Project</a></li>
                        <li><a href="#" data-filter="client">Client Project</a></li>
                    </ul>
                    <div id="detail">
                        <div class="row">
                            <div class="col-lg-10 mx-auto"><span class="close">×</span>
                                <div id="detail-slider" class="owl-carousel owl-theme"></div>
                                <div class="text-center">
                                    <h1 id="detail-title" class="title"></h1>
                                </div>
                                <div id="detail-content"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Reference detail-->
                    <div id="references-masonry" data-animate="fadeInUp">
                        <div class="row">
                            @foreach ($portfolio as $item)
                                <div data-category="{{$item->type == 1 ? 'personal' : 'client'}}" class="reference-item col-lg-3 col-md-6">

                                    {{-- Hosting --}}
                                    <div class="reference"><a href="#"><img src="{{asset('images/portfolio/'.\Illuminate\Support\Str::slug($item->name,'-').'/'.$item->thumbnail)}}" alt="" class="img-fluid">

                                    {{-- <div class="reference"><a href="#"><img src="{{asset('storage/images/portfolio/'.\Illuminate\Support\Str::slug($item->name,'-').'/'.$item->thumbnail)}}" alt="" class="img-fluid"> --}}
                                        <div class="overlay">
                                            <div class="inner">
                                                <h5 class="h5 reference-title">{{$item->name}}</h5>
                                                <p>
                                                    @if ($item->type == 1)
                                                        Personal Project
                                                    @else
                                                        Client Project
                                                    @endif
                                                </p>
                                            </div>
                                        </div></a>
                                        <div data-images=" 
                                        @foreach ($item->pic as $index => $value)
                                            @if ($index == $item->pic()->count() - 1)

                                                {{-- Hosting --}}
                                                {{asset('images/portfolio/'.\Illuminate\Support\Str::slug($item->name,'-').'/'.$value->pic)}}

                                                {{-- {{asset('storage/images/portfolio/'.\Illuminate\Support\Str::slug($item->name,'-').'/'.$value->pic)}} --}}
                                            @else

                                                {{-- Hosting --}}
                                                {{asset('images/portfolio/'.\Illuminate\Support\Str::slug($item->name,'-').'/'.$value->pic)}},

                                                {{-- {{asset('storage/images/portfolio/'.\Illuminate\Support\Str::slug($item->name,'-').'/'.$value->pic)}}, --}}
                                            @endif
                                        @endforeach
                                        " class="sr-only reference-description">
                                            <p>
                                                {{$item->desc}}
                                            </p>
                                            <p class="buttons text-center">
                                                <a target="_blank" href="
                                                @if (!empty($item->url))
                                                    {{$item->url}}
                                                @else
                                                    #
                                                @endif
                                                " class="btn btn-outline-primary">
                                                    <i class="fa fa-globe"></i> Visit website
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Customers-->
        <section id="customers" class="customers-section bg-gray">
            <div class="container mt-3">
                <header class="text-center">
                    <h2 class="title">Technology That I Use</h2>
                </header>
                <div class="col-md-12">
                    <div class="row align-items-center">
                        @foreach ($tech as $item)
                            <div class="col-lg-2 col-md-4 col-sm-6">

                                {{-- Hosting --}}
                                <div class="customer"><img src="{{asset('images/tech/'.$item->pic)}}" title="{{$item->name}}" data-placement="bottom" data-toggle="tooltip" alt="" class="img-fluid d-block mx-auto"></div>

                                {{-- <div class="customer"><img src="{{asset('storage/images/tech/'.$item->pic)}}" title="{{$item->name}}" data-placement="bottom" data-toggle="tooltip" alt="" class="img-fluid d-block mx-auto"></div> --}}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact-->
        <section id="contact" data-animate="bounceIn" class="contact-section contact">
            <div class="container">
                <header class="text-center">
                    <h2 class="title">Contact me</h2>
                </header>
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <form id="contact-form" method="post" action="{{route('message')}}">
                            @csrf
                            <div class="messages"></div>
                            <div class="controls">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="first_name" placeholder="Your firstname *" required="required" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="last_name" placeholder="Your lastname *" required="required" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="email" placeholder="Your email *" required="required" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="phone" placeholder="Your phone" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <textarea name="message" placeholder="Message for me *" rows="4" required="required" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-outline-primary">Send message</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <footer class="main-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-lg-left">
                        <p class="social">
                            <a target="_blank" href="{{$profile->facebook}}" class="external facebook wow fadeInUp">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a target="_blank" href="{{$profile->instagram}}" data-wow-delay="0.1s" class="external instagram wow fadeInUp">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a target="_blank" href="{{$profile->twitter}}" data-wow-delay="0.3s" class="external twitter wow fadeInUp">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a target="_blank" href="{{$profile->github}}" data-wow-delay="0.5s" class="external github wow fadeInUp">
                                <i class="fab fa-github"></i>
                            </a>
                            <a target="_blank" href="{{$profile->linkedin}}" data-wow-delay="0.7s" class="external linkedin wow fadeInUp">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a target="_blank" href="{{$profile->youtube}}" data-wow-delay="0.9s" class="external youtube wow fadeInUp">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="mailto:{{$user->email}}" data-wow-delay="1.1s" class="email wow fadeInUp">
                                <i class="fa fa-envelope"></i>
                            </a>
                        </p>
                    </div>
                    <!-- /.6-->
                    <div class="col-md-6 text-center text-lg-right mt-4 mt-lg-0">
                        <p>© 2019 Granite Bagas. All rights reserved.</p>
                    </div>
                    <div class="col-12 mt-4">
                        <p class="template-bootstrapious">Template by <a href='https://bootstrapious.com/p/bootstrap-carousel'>Bootstrapious</a> & <a href="https://fity.cz/ostrava">Fity</a></p>
                        <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                    </div>
                </div>
            </div>
        </footer>

        <!-- JavaScript files-->
        <script src="{{asset('client/vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('client/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('client/vendor/jquery.cookie/jquery.cookie.js')}}"></script>
        <script src="{{asset('client/vendor/owl.carousel/owl.carousel.min.js')}}"></script>
        <script src="{{asset('client/vendor/waypoints/lib/jquery.waypoints.min.js')}}"></script>
        <script src="{{asset('client/vendor/jquery.counterup/jquery.counterup.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.4.0/leaflet.js"> </script>
        <script src="{{asset('client/js/front.js')}}"></script>
        <script src="https://kit.fontawesome.com/f09f131f5f.js"></script>
        <!-- Toastr -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    </body>
    </html>