@extends('layout.master')
@section('content')
    <div class="col-md-8">
    <!-- Default box -->

        <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Change Password</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div id="change_password" class="tab-pane ">
                <div class="row">
                    @if(\Illuminate\Support\Facades\Session::has('success-message'))
                        <div class="alert alert-card alert-success" role="alert">
                            <strong class="text-capitalize">Success! </strong>
                            {{\Illuminate\Support\Facades\Session::get('success-message')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(\Illuminate\Support\Facades\Session::has('error-message'))
                        <div class="alert alert-card alert-danger" role="alert">
                            <strong class="text-capitalize">Error! </strong>
                            {{\Illuminate\Support\Facades\Session::get('error-message')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if($errors->count() > 0 )
                        <div class="alert alert-danger" role="alert">
                            <strong class="text-capitalize">The following errors have occurred:</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <ul>
                                @foreach( $errors->all() as $message )
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class=" change_password">
                        <div class="col-md-4">
                            @if (!empty(\Auth::user()->user_profile_image))
                                <img src="{{asset('assets/images/user/admin/'.\Auth::user()->user_profile_image)}}" alt="User Profile Photo">
                            @else
                                <img src="{{asset('assets/dist/img/profile.png')}}" alt="User Profile Photo">
                            @endif
                        </div>
                        <div class="col-md-8 info">
                            <h1><i class="fa fa-lock"></i> {{isset(\Auth::user()->name) ? \Auth::user()->name : ''}}</h1>
                            <form action="{{url('/change/password')}}" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{csrf_token()}}" >
                                <div class="row">
                                    <div class="col-md-6" style="padding-right:0">
                                        <span><i>New Password</i></span>
                                        <input type="password" name="new_password" placeholder="New Password" class="form-control" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <span><i>Confirm Password</i></span>
                                        <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="input-group" style="margin-top:7px">
                                    <input type="password" name="current_password" placeholder="Current Password" class="form-control" value="">
                                    <span class="input-group-btn">
												<button class="btn btn-blue" type="submit">
													<i class="fa fa-chevron-right"></i>
												</button>
											</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    </div>
@endsection
@section('JScript')
@endsection