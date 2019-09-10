<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankingController extends Controller
{
    public function __construct(Request $request)
    {
        $this->page_title = $request->route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:$this->page_title;
        \App\System::AccessLogWrite();
    }

    /********************************************
    ## FundDepositPage
     *********************************************/

    public function FundDepositPage(){
        $data['account_list'] = \Auth::check()?\App\Account::where('user_id',\Auth::user()->id)->get():'';
        $data['page_title'] = $this->page_title;
        return view('pages.transactions.deposit',$data);
    }

    /********************************************
    ## FundDepositTransaction
     *********************************************/

    public function FundDepositTransaction(Request $request){
        $now=date('Y-m-d H:i:s');

        $v = \Validator::make($request->all(), [
            'from_account' => 'Required',
            'to_account' => 'Required',
            'deposit_amount' => 'Required|numeric',
            'narration' => 'Required|max:120',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }


        \DB::beginTransaction();
        try{


            if($request->input('from_account') == $request->input('to_account'))
                throw new \Exception('Deposit Accounts are both same');


            $user_info=\App\User::where('id',\Auth::user()->id)->first();
            $from_account_info= \App\Account::where('account_id',$request->input('from_account'))->first();
            $to_account_info= \App\Account::where('account_id',$request->input('to_account'))->first();


            if(!isset($from_account_info->id) || !isset($to_account_info->id))
                throw new \Exception('Invalid Account ID for deposit');

            if(!isset($from_account_info->account_net_balance_amount) || $request->input('deposit_amount') > $from_account_info->account_net_balance_amount)
                throw new \Exception('Insufficient balance for deposit transaction');


            #TransferTransactionInsert
            $transfer['account_from_id'] = $from_account_info->account_id;
            $transfer['account_to_id'] = $to_account_info->account_id;
            $transfer['transfer_narration'] = $request->input('narration');
            $transfer['transfer_amount'] = $request->input('deposit_amount');

            $transfer_confirm = \App\TransferTransaction::firstOrCreate($transfer);
            \App\System::EventLogWrite('insert,transfer_transactions',json_encode($transfer));


            #FromTransactionWithdrawalInsert
            $transaction['transaction_account_id'] =$from_account_info->account_id;
            $transaction['transaction_user_id'] =\Auth::user()->id;
            $transaction['transaction_date'] =date('Y-m-d');
            $transaction['transaction_type'] ='withdrawal';
            $transaction['opening_credit_amount'] =$from_account_info->account_net_credit_amount;
            $transaction['closing_credit_amount'] =$from_account_info->account_net_credit_amount;
            $transaction['opening_debit_amount'] =$from_account_info->account_net_debit_amount;
            $transaction['closing_debit_amount'] =($from_account_info->account_net_debit_amount+$request->input('deposit_amount'));
            $transaction['opening_balance_amount'] =$from_account_info->account_net_balance_amount;
            $transaction['closing_balance_amount'] =($from_account_info->account_net_balance_amount - $request->input('deposit_amount'));;
            $transaction['transaction_method'] = 'debit';
            $transaction['referrence_id'] = $transfer_confirm->id;
            $transaction['transaction_amount'] = $request->input('deposit_amount');
            $transaction['transaction_narration'] = $request->input('narration');

            $transaction_confirm = \App\BankingTransaction::firstOrCreate($transaction);
            \App\System::EventLogWrite('insert,banking_transactions',json_encode($transaction));

            #fromAccountDebit
            $account_from['user_id'] = \Auth::user()->id;
            $account_from['account_id'] = $from_account_info->account_id;
            $account_from['account_net_debit_amount'] = ($from_account_info->account_net_debit_amount + $request->input('deposit_amount'));
            $account_from['account_net_balance_amount'] = ($from_account_info->account_net_balance_amount - $request->input('deposit_amount'));

            $account_from_update = \App\Account::updateorCreate(
                [
                    'user_id'=>$account_from['user_id'],
                    'account_id'=>$account_from['account_id'],
                ],
                $account_from
            );
            \App\System::EventLogWrite('update,accounts',json_encode($account_from));



            #ToTransactiondrawalInsert
            $transaction_to['transaction_account_id'] =$to_account_info->account_id;
            $transaction_to['transaction_user_id'] =\Auth::user()->id;
            $transaction_to['transaction_date'] =date('Y-m-d');
            $transaction_to['transaction_type'] ='deposit';
            $transaction_to['opening_credit_amount'] =$to_account_info->account_net_credit_amount;
            $transaction_to['closing_credit_amount'] =($to_account_info->account_net_credit_amount+$request->input('deposit_amount'));
            $transaction_to['opening_debit_amount'] =$to_account_info->account_net_debit_amount;
            $transaction_to['closing_debit_amount'] =$to_account_info->account_net_debit_amount;
            $transaction_to['opening_balance_amount'] =$to_account_info->account_net_balance_amount;
            $transaction_to['closing_balance_amount'] =($to_account_info->account_net_balance_amount+$request->input('deposit_amount'));;
            $transaction_to['transaction_method'] = 'credit';
            $transaction_to['referrence_id'] = $transfer_confirm->id;
            $transaction_to['transaction_amount'] = $request->input('deposit_amount');
            $transaction_to['transaction_narration'] = $request->input('narration');

            $transaction_to_confirm = \App\BankingTransaction::firstOrCreate($transaction_to);
            \App\System::EventLogWrite('insert,banking_transactions',json_encode($transaction_to));


            #ToAccountCredit
            $account_to['user_id'] = \Auth::user()->id;
            $account_to['account_id'] = $to_account_info->account_id;
            $account_to['account_net_credit_amount'] = ($to_account_info->account_net_credit_amount + $request->input('deposit_amount'));
            $account_to['account_net_balance_amount'] = ($to_account_info->account_net_balance_amount + $request->input('deposit_amount'));

            $account_to_update = \App\Account::updateorCreate(
                [
                    'user_id'=>$account_to['user_id'],
                    'account_id'=>$account_to['account_id'],
                ],
                $account_to
            );
            \App\System::EventLogWrite('update,accounts',json_encode($account_to));





            \DB::commit();
            return redirect('/deposit-transactions')->with('success-message',"Fund Deposit transaction successfully completed.");

        } catch(\Exception $e) {
            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('/deposit-transactions')->with('error-message',$e->getMessage());
        }
    }


    /********************************************
    ## FundTransferPage
     *********************************************/

    public function FundTransferPage(){
        $data['account_list'] = \Auth::check()?\App\Account::where('user_id',\Auth::user()->id)->get():'';
        $data['page_title'] = $this->page_title;
        return view('pages.transactions.transfer',$data);
    }

    /********************************************
    ## FundTrasnferTransactionsProcess
     *********************************************/

    public function FundTrasnferTransactionsProcess(Request $request){
        $now=date('Y-m-d H:i:s');

        $v = \Validator::make($request->all(), [
            'from_account' => 'Required',
            'to_account' => 'Required',
            'deposit_amount' => 'Required|numeric',
            'narration' => 'Required|max:120',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }


        \DB::beginTransaction();
        try{


            if($request->input('from_account') == $request->input('to_account'))
                throw new \Exception('Accounts ID are same');


            $user_info=\App\User::where('id',\Auth::user()->id)->first();
            $from_account_info= \App\Account::where('account_id',$request->input('from_account'))->first();
            $to_account_info= \App\Account::where('account_id',$request->input('to_account'))->first();


            if(!isset($from_account_info->id) || !isset($to_account_info->id))
                throw new \Exception('Invalid Account ID for deposit');

            if($to_account_info->user_id == \Auth::user()->id)
                throw new \Exception('Both Accounts owner are same');

            if(!isset($from_account_info->account_net_balance_amount) || $request->input('deposit_amount') > $from_account_info->account_net_balance_amount)
                throw new \Exception('Insufficient balance for deposit transaction');


            #TransferTransactionInsert
            $transfer['account_from_id'] = $from_account_info->account_id;
            $transfer['account_to_id'] = $to_account_info->account_id;
            $transfer['transfer_narration'] = $request->input('narration');
            $transfer['transfer_amount'] = $request->input('deposit_amount');

            $transfer_confirm = \App\TransferTransaction::firstOrCreate($transfer);
            \App\System::EventLogWrite('insert,transfer_transactions',json_encode($transfer));


            #FromTransactionWithdrawalInsert
            $transaction['transaction_account_id'] =$from_account_info->account_id;
            $transaction['transaction_user_id'] =\Auth::user()->id;
            $transaction['transaction_date'] =date('Y-m-d');
            $transaction['transaction_type'] ='withdrawal';
            $transaction['opening_credit_amount'] =$from_account_info->account_net_credit_amount;
            $transaction['closing_credit_amount'] =$from_account_info->account_net_credit_amount;
            $transaction['opening_debit_amount'] =$from_account_info->account_net_debit_amount;
            $transaction['closing_debit_amount'] =($from_account_info->account_net_debit_amount+$request->input('deposit_amount'));
            $transaction['opening_balance_amount'] =$from_account_info->account_net_balance_amount;
            $transaction['closing_balance_amount'] =($from_account_info->account_net_balance_amount - $request->input('deposit_amount'));;
            $transaction['transaction_method'] = 'debit';
            $transaction['referrence_id'] = $transfer_confirm->id;
            $transaction['transaction_amount'] = $request->input('deposit_amount');
            $transaction['transaction_narration'] = $request->input('narration');

            $transaction_confirm = \App\BankingTransaction::firstOrCreate($transaction);
            \App\System::EventLogWrite('insert,banking_transactions',json_encode($transaction));

            #fromAccountDebit
            $account_from['user_id'] = \Auth::user()->id;
            $account_from['account_id'] = $from_account_info->account_id;
            $account_from['account_net_debit_amount'] = ($from_account_info->account_net_debit_amount + $request->input('deposit_amount'));
            $account_from['account_net_balance_amount'] = ($from_account_info->account_net_balance_amount - $request->input('deposit_amount'));

            $account_from_update = \App\Account::updateorCreate(
                [
                    'user_id'=>$account_from['user_id'],
                    'account_id'=>$account_from['account_id'],
                ],
                $account_from
            );
            \App\System::EventLogWrite('update,accounts',json_encode($account_from));



            #ToTransactiondrawalInsert
            $transaction_to['transaction_account_id'] =$to_account_info->account_id;
            $transaction_to['transaction_user_id'] =$to_account_info->user_id;
            $transaction_to['transaction_date'] =date('Y-m-d');
            $transaction_to['transaction_type'] ='deposit';
            $transaction_to['opening_credit_amount'] =$to_account_info->account_net_credit_amount;
            $transaction_to['closing_credit_amount'] =($to_account_info->account_net_credit_amount+$request->input('deposit_amount'));
            $transaction_to['opening_debit_amount'] =$to_account_info->account_net_debit_amount;
            $transaction_to['closing_debit_amount'] =$to_account_info->account_net_debit_amount;
            $transaction_to['opening_balance_amount'] =$to_account_info->account_net_balance_amount;
            $transaction_to['closing_balance_amount'] =($to_account_info->account_net_balance_amount+$request->input('deposit_amount'));;
            $transaction_to['transaction_method'] = 'credit';
            $transaction_to['referrence_id'] = $transfer_confirm->id;
            $transaction_to['transaction_amount'] = $request->input('deposit_amount');
            $transaction_to['transaction_narration'] = $request->input('narration');

            $transaction_to_confirm = \App\BankingTransaction::firstOrCreate($transaction_to);
            \App\System::EventLogWrite('insert,banking_transactions',json_encode($transaction_to));


            #ToAccountCredit
            $account_to['account_id'] = $to_account_info->account_id;
            $account_to['account_net_credit_amount'] = ($to_account_info->account_net_credit_amount + $request->input('deposit_amount'));
            $account_to['account_net_balance_amount'] = ($to_account_info->account_net_balance_amount + $request->input('deposit_amount'));

            $account_to_update = \App\Account::updateorCreate(
                [
                    'account_id'=>$account_to['account_id'],
                ],
                $account_to
            );
            \App\System::EventLogWrite('update,accounts',json_encode($account_to));



            \DB::commit();
            return redirect('/fund-transfer')->with('success-message',"Fund Transfer transaction successfully completed.");

        } catch(\Exception $e) {
            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('/fund-transfer')->with('error-message',$e->getMessage());
        }
    }


    /********************************************
    ## BankingTransactionList
     *********************************************/

    public function BankingTransactionList(){


        if(isset($_GET['accountID'])  && !empty($_GET['accountID'])){
            $from= date("Y-m-d", strtotime("-30 days"));
            $to = date('Y-m-d');
            $accountID = $_GET['accountID'];
            $data['all_transaction']= \App\BankingTransaction::where('transaction_account_id',$accountID)->where('transaction_user_id',\Auth::user()->id)->whereBetween('transaction_date',[$from,$to])->paginate(2);

            $data['all_transaction']->setPath(url('/transaction/list'));
            $all_transaction_pagination = $data['all_transaction']->appends(['accountID' => ((isset($_GET['accountID'])&&!empty($_GET['accountID']))?$_GET['accountID']:''), 'action'=>'view'])->render();
            $data['all_transaction_pagination'] = $all_transaction_pagination;
            $data['perPage'] = $data['all_transaction']->perPage();


            if(isset($data['all_transaction'])&&count($data['all_transaction'])>0&&isset($_GET['referenceID'])&&!empty($_GET['referenceID'])){
                 $data['transaction_details'] = \App\TransferTransaction::where('id', $_GET['referenceID'])
                    ->where(function ($query) use($accountID) {
                        $query->where('account_from_id', $accountID)
                            ->orWhere('account_to_id', $accountID);
                    })->first();
            }
        }

        $data['account_list'] = \Auth::check()?\App\Account::where('user_id',\Auth::user()->id)->get():'';
        $data['page_title'] = $this->page_title;

        //var_dump($data['account_list']);
       return view('pages.transactions.transaction',$data);
    }
}
