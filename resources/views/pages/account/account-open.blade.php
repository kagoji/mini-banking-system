@extends('layout.master')
@section('content')
    <div class="col-md-8">
        <!-- Default box -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Bank Account Registration</h3>
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
                <form class="form-horizontal" action="{{url('/open-account')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" >
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">PersonalCode</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="21212121" name="personal_code" value="{{isset($user_info->personal_code)?$user_info->personal_code:''}}" {{isset($user_info->personal_code)? 'disabled':''}}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Address</label>
                        <div class="col-sm-10">
                            <textarea  placeholder="Mailing Address.." name="address" rows="2" cols="63" {{isset($user_info->address)? 'disabled':''}}>{{isset($user_info->address)?$user_info->address:''}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Account Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="accounts_name" placeholder="Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Account Type</label>
                        <div class="col-sm-10">
                            <input class="form-check-input" type="radio" name="account_type" value="personal"> Personal Account
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-check-input" type="radio" name="account_type" value="business"> Business Account
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSkills" class="col-sm-2 control-label">Opening Blanced( <i class="fa  fa-eur"></i> )</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="opening_balance" placeholder="0.0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="agreement"> I agree to the <a>terms and conditions</a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input action="action" class="btn btn-default" onclick="window.history.go(-1); return false;" type="button" value="Back" />
                            <button type="submit" class="btn btn-danger">Create account</button>
                        </div>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
@endsection
@section('JScript')
@endsection