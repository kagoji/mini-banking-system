<section class="content-header">
    <h1>
        {{isset($page_title)? $page_title:'Page'}}
        <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{(\Auth::check()? url('/profile/'.\Auth::user()->name_slug):'#')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{isset($page_title)? $page_title:'Page'}}</li>
    </ol>
</section>