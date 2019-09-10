<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('assets/dist/img/profile.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{(\Auth::check())? \Auth::user()->name:'Agent'}}</p>
                <a ><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">


            <li><a href="{{(\Auth::check()? url('/profile/'.\Auth::user()->name_slug):'#')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

            <li class="treeview {{(isset($page_title) && (strpos($page_title,'Account')!== false )) ? 'active' : ''}}">
                <a href="#">
                    <i class="fa fa-archive"></i>
                    <span>Accounts</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{isset($page_title) && ($page_title=='Account Open') ? 'active' : ''}}"><a href="{{url('/open-account')}}"><i class="fa fa-plus-square-o"></i> Open an Account</a></li>
                    <li class="{{isset($page_title) && ($page_title=='My Account') ? 'active' : ''}}"><a href="{{url('/my-account')}}"><i class="fa fa-circle-o"></i>My Accounts</a></li>
                </ul>
            </li>

            <li class="treeview {{(isset($page_title) && (strpos($page_title,'Transaction')!== false )) ? 'active' : ''}}">
                <a href="#">
                    <i class="fa fa-bank"></i>
                    <span>Bannking</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{isset($page_title) && ($page_title=='Deposit Transaction') ? 'active' : ''}}"><a href="{{url('/deposit-transactions')}}"><i class="fa fa-share-square"></i> Deposit</a></li>
                    <li class="{{isset($page_title) && ($page_title=='Fund Transfer Transaction') ? 'active' : ''}}"><a href="{{url('/fund-transfer')}}"><i class="fa fa-exchange"></i>Fund Transfer</a></li>
                    <li class="{{isset($page_title) && ($page_title=='Banking Transaction List') ? 'active' : ''}}"><a href="{{url('/transaction/list')}}"><i class="glyphicon glyphicon-th-list"></i>Transaction List </a></li>
                </ul>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>