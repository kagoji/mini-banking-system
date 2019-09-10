<header class="main-header">
    <!-- Logo -->
    <a href="{{(\Auth::check()? url('/profile/'.\Auth::user()->name_slug):'#')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>Mi</b>B</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Mini</b>Bank</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset(('assets/dist/img/profile.png'))}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{(\Auth::check())? \Auth::user()->name:'Agent'}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('assets/dist/img/profile.png')}}" class="img-circle" alt="User Image">
                            <p>
                                {{(\Auth::check())? \Auth::user()->name:'Agent'}}
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{url('/change/password')}}" class="btn btn-default btn-flat">Change Password</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{(\Auth::check())? url('/logout/'.\Auth::user()->email):''}}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>