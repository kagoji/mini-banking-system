@extends('layout.master')
@section('content')
    <div class="col-md-12">
        <!-- Default box -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Last 30 days Transaction</h3>
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
                    <form class="form-horizontal" action="{{url('/transaction/list')}}" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">My Account</label>
                                    <div class="col-sm-4">
                                        <select class="form-control accountID" name="accountID" >
                                            <option value="">Choose an account</option>
                                            @if(isset($account_list)&& count($account_list)>0)
                                                @foreach($account_list as $key => $account)
                                                    <option value="{{$account->account_id}}" {{(isset($_REQUEST['accountID'])&&!empty($_REQUEST['accountID']) && ($_REQUEST['accountID']==$account->account_id))? 'selected':''}}>{{$account->accounts_name}}-{{$account->account_id}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success">VIEW</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>


    <div class="col-md-12">
    @if(isset($_REQUEST['accountID'])&&!empty($_REQUEST['accountID']))
        <!-- Transaction start -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Transaction List</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive col-md-12 ">
                        <table class="table table-hover table-bordered table-striped nopadding text-center">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>AccountID</th>
                                <th>Description</th>
                                <th>Debit/Credit</th>
                                <th>Amount</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($all_transaction) && count($all_transaction)>0)
                                @php($total_amount=0)
                                @php($page=isset($_GET['page'])&&!empty($_GET['page'])?$_GET['page']-1:0)
                                @php($page2=isset($_GET['page'])&&!empty($_GET['page'])?$_GET['page']:1)
                                @foreach($all_transaction as $key => $transaction)
                                    @if((isset($transaction->referrence_id) && !empty($transaction->referrence_id)))
                                        <tr style="cursor: pointer; background-color: dodgerblue;" onclick="location.href='{{url('/transaction/list?accountID='.$transaction->transaction_account_id.'&view=details&page='.$page2.'&referenceID='.$transaction->referrence_id)}}';">
                                    @else
                                        <tr>
                                            @endif
                                            <td>{{($key+1+($perPage*$page))}}</td>
                                            <td>{{(isset($transaction->transaction_date) && !empty($transaction->transaction_date)) ?  date('d-M-Y',strtotime($transaction->transaction_date)):''}}</td>
                                            <td>{{(isset($transaction->transaction_account_id) && !empty($transaction->transaction_account_id)) ? $transaction->transaction_account_id:''}}</td>
                                            <td>{{(isset($transaction->transaction_narration) && !empty($transaction->transaction_narration)) ?  $transaction->transaction_narration:''}}</td>
                                            <td>{{(isset($transaction->transaction_method) && !empty($transaction->transaction_method)) ?  ucwords($transaction->transaction_method):''}}</td>
                                            <td>{{(isset($transaction->transaction_amount) && !empty($transaction->transaction_amount)) ? number_format($transaction->transaction_amount,2,'.',''):'0.0'}}</td>
                                            <td>{{(isset($transaction->closing_balance_amount) && !empty($transaction->closing_balance_amount)) ? number_format($transaction->closing_balance_amount,2,'.',''):'0.0'}}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">No Data Available</td>
                                            </tr>
                                        @endif
                            </tbody>
                        </table>
                        <?php echo isset($all_transaction_pagination)? $all_transaction_pagination:''?>
                    </div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <!-- TransactionNox End -->
        @endif
    </div>

    <!-- TransactionDeatials start -->
    <div class="col-md-12">
    @if(isset($transaction_details->id)&&!empty($transaction_details->id))

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Transaction Details</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive col-md-12 ">
                        <table class="table table-hover table-bordered table-striped nopadding text-center">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>From AccountID</th>
                                <th>To AccountID</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td>{{(isset($transaction_details->created_at) && !empty($transaction_details->created_at)) ?  date('d-M-Y',strtotime($transaction_details->created_at)):''}}</td>
                                <td>{{(isset($transaction_details->account_from_id) && !empty($transaction_details->account_from_id)) ? $transaction_details->account_from_id:''}}</td>
                                <td>{{(isset($transaction_details->account_to_id) && !empty($transaction_details->account_to_id)) ?  $transaction_details->account_to_id:''}}</td>
                                <td>{{(isset($transaction_details->transfer_narration) && !empty($transaction_details->transfer_narration)) ? $transaction_details->transfer_narration:''}}</td>
                                <td>{{(isset($transaction_details->transfer_amount) && !empty($transaction_details->transfer_amount)) ? number_format($transaction_details->transfer_amount,2,'.',''):'0.0'}}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <!-- TransactionDeatials End -->
        @endif
    </div>

@endsection
@section('JScript')
    <script>
        jQuery(function() {

            jQuery('.accountID ').on('change', function(event) {
                event.preventDefault();

                var site_url = jQuery('.site_url').val();
                var accountID = jQuery('.accountID :selected').val();

                if(accountID.length !=0)
                    window.location.href=site_url+'/transaction/list?accountID='+accountID+'&action=view';
                else
                    window.location.href=site_url+'/transaction/list';
            });
        });
    </script>
@endsection