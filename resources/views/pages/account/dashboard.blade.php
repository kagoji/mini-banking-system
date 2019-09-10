@extends('layout.master')
@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>Accounts</h3>
                </div>
                <div class="icon" style="font-size: 67px;">
                    <i class="fa fa-envelope"></i>
                </div>
                <a href="{{url('/my-account')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>Open Account</h3>
                </div>
                <div class="icon" style="font-size: 67px;">
                    <i class="fa fa-folder-open"></i>
                </div>
                <a href="{{url('/open-account')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>Fund Transfer</h3>
                </div>
                <div class="icon" style="font-size: 67px;">
                    <i class="fa  fa-exchange"></i>
                </div>
                <a href="{{url('/fund-transfer')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>Transaction</h3>
                </div>
                <div class="icon" style="font-size: 67px;">
                    <i class="fa fa-list-alt"></i>
                </div>
                <a href="{{url('/transaction/list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
    </div><!-- /.row -->
@endsection
@section('JScript')
@endsection

