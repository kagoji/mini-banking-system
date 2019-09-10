@extends('layout.master')
@section('content')
    <div class="col-md-5">
        <!-- Default box -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">My Bank Accounts</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>

            <div class="box-body">
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
                    @if(isset($account_list)&& count($account_list)>0)
                        @foreach($account_list as $key => $account)
                            <a style="cursor: pointer;" onclick="location.href='{{url('/my-account?account='.$account->account_id.'&view=account-balance')}}';">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <div class="info-box bg-{{($key%2)==0? 'aqua':'green'}}">
                                        <span class="info-box-icon"><i class="glyphicon glyphicon-floppy-disk"></i></span>
                                        <div class="info-box-content" style="padding-top: 16px;">
                                            <span class="info-box-text"><i class="glyphicon glyphicon-user"></i>&nbsp &nbsp Account Name: {{isset($account->accounts_name)?$account->accounts_name:''}}</span>
                                            <span class="info-box-number"><i class="glyphicon glyphicon-tag"></i>&nbsp &nbsp Account ID: {{isset($account->account_id)?$account->account_id:''}}</span>
                                        </div><!-- /.info-box-content -->
                                        <span class="progress-description"> &nbsp &nbsp &nbsp &nbsp  Check Balance  <i class="fa fa-arrow-circle-right"></i></span>
                                    </div><!-- /.info-box -->
                                </div><!-- /.col -->
                            </a>
                        @endforeach
                    @else
                        No Account available
                    @endif


            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    @if(isset($account_info->id))
    <div class="col-md-5">
        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Balance Check</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{asset('assets/dist/img/profile.png')}}" alt="User profile picture">
                <h3 class="profile-username text-center">{{isset($account_info->accounts_name)?$account_info->accounts_name:''}}</h3>
                <p class="text-muted text-center">{{isset($account_info->account_type)?ucwords($account_info->account_type):''}}</p>

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Current Balance ( <i class="fa  fa-eur"></i> )</b> <a class="pull-right">{{number_format($account_info->account_net_balance_amount,2,'.',',')}}</a>
                    </li>
                </ul>

                <a href="{{url('/my-account')}}" class="btn btn-primary btn-block"><b>Close</b></a>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    @endif
@endsection
@section('JScript')
@endsection