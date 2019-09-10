<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountManageController extends Controller
{
    public function __construct(Request $request)
    {
        $this->page_title = $request->route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:$this->page_title;
        \App\System::AccessLogWrite();
    }


    /********************************************
    ## AccountDashboard
     *********************************************/

    public function AccountDashboard(){
        $data['page_title'] = $this->page_title;
        return view('pages.account.dashboard',$data);
    }

    /********************************************
    ## AccountOpenPage
     *********************************************/

    public function AccountOpenPage(){

        $data['user_info'] = \Auth::check()?\App\User::where('id',\Auth::user()->id)->first():'';
        $data['page_title'] = $this->page_title;
        return view('pages.account.account-open',$data);
    }

    /********************************************
    ## AccountCreate
     *********************************************/

    public function AccountCreate(Request $request){
        $now=date('Y-m-d H:i:s');

        $v = \Validator::make($request->all(), [
            'personal_code' => 'max:16',
            'accounts_name' => 'Required|max:30',
            'account_type' => 'Required',
            'opening_balance' => 'Required|numeric',
            'agreement' => 'Required',

        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        \DB::beginTransaction();
        try{
            $user_info=\App\User::where('id',\Auth::user()->id)->first();

            if(empty($user_info->personal_code))
                $user_data_update['personal_code']=$request->input('personal_code');

            if(empty($user_info->address))
                $user_data_update['address']=$request->input('address');

            #UserinfUpdate
            if(isset($user_data_update)&&count($user_data_update)>0){
                \App\User::where('id',\Auth::user()->id)->update($user_data_update);
                \App\System::EventLogWrite('update,users',json_encode($user_data_update));
            }

            #AccountCreagte
            $acoount_id = 'MBS'.\App\System::RandomStringNum(8);
            $account['user_id'] = \Auth::user()->id;
            $account['account_id'] = $acoount_id;
            $account['account_type'] = $request->input('account_type');
            $account['accounts_name'] = ucwords($request->input('accounts_name'));
            $account['account_net_credit_amount'] = $request->input('opening_balance');
            $account['account_net_debit_amount'] = 0;
            $account['account_net_balance_amount'] = $request->input('opening_balance');

            $registration_confirm = \App\Account::firstOrCreate($account);
            \App\System::EventLogWrite('insert,account',json_encode($account));

            #TransactionInsert
            $transaction['transaction_account_id'] =$acoount_id;
            $transaction['transaction_user_id'] =\Auth::user()->id;
            $transaction['transaction_date'] =date('Y-m-d');
            $transaction['transaction_type'] ='deposit';
            $transaction['opening_credit_amount'] =0;
            $transaction['closing_credit_amount'] =$request->input('opening_balance');
            $transaction['opening_debit_amount'] =0;
            $transaction['closing_debit_amount'] =0;
            $transaction['opening_balance_amount'] =0;
            $transaction['closing_balance_amount'] =$request->input('opening_balance');
            $transaction['transaction_method'] = 'credit';
            $transaction['transaction_amount'] = $request->input('opening_balance');
            $transaction['transaction_narration'] = "Opening balance";

            $registration_confirm = \App\BankingTransaction::firstOrCreate($transaction);
            \App\System::EventLogWrite('insert,banking_transactions',json_encode($transaction));

            \DB::commit();
            return redirect('/open-account')->with('success-message'," Your Account ID $acoount_id.");

        } catch(\Exception $e) {
            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('/open-account')->with('error-message',$e->getMessage());
        }
    }

    /********************************************
    ## MyAccountPage
     *********************************************/

    public function MyAccountPage(){

        $data['user_info'] = \Auth::check()?\App\User::where('id',\Auth::user()->id)->first():'';
        $data['account_list'] = \Auth::check()?\App\Account::where('user_id',\Auth::user()->id)->get():'';

        if(isset($_REQUEST['account'])&& !empty($_REQUEST['account']))
            $data['account_info'] = \App\Account::where('account_id',$_REQUEST['account'])->where('user_id',\Auth::user()->id)->first();


        $data['page_title'] = $this->page_title;
        return view('pages.account.my-account',$data);
    }
}
