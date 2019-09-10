@extends('layout.master')
@section('content')
    <div class="col-md-8">
        <!-- Default box -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Fund Deposit Between My Account</h3>
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
                <form class="form-horizontal" action="{{url('/deposit-transactions')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" >
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">From Account</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="from_account" >
                                <option>Choose an account</option>
                                @if(isset($account_list)&& count($account_list)>0)
                                    @foreach($account_list as $key => $account)
                                        <option value="{{$account->account_id}}">{{$account->accounts_name}}-{{$account->account_id}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">To Account</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="to_account" >
                                <option>Choose an account</option>
                                @if(isset($account_list)&& count($account_list)>0)
                                    @foreach($account_list as $key => $account)
                                        <option value="{{$account->account_id}}">{{$account->accounts_name}}-{{$account->account_id}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSkills" class="col-sm-2 control-label">Deposit Amount( <i class="fa  fa-eur"></i> )</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="deposit_amount" placeholder="0.0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Narration</label>
                        <div class="col-sm-10">
                            <textarea  placeholder="Narration.." name="narration" rows="2" cols="63" ></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input action="action" class="btn btn-default" onclick="window.history.go(-1); return false;" type="button" value="Back" />
                            <button type="submit" class="btn btn-success">Deposit</button>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">

                        </div>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
@endsection
@section('JScript')
@endsection