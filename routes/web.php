<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/',array('as'=>'Sign in', 'uses' =>'SystemAuthController@authLogin'));
Route::get('/login',array('as'=>'Sign in', 'uses' =>'SystemAuthController@authLogin'));
Route::get('/registration',array('as'=>'Sign Up', 'uses' =>'SystemAuthController@RegitrationPage'));
Route::post('/login',array('as'=>'Sign in' , 'uses' =>'SystemAuthController@authPostLogin'));
Route::post('/registration',array('as'=>'Registration' , 'uses' =>'SystemAuthController@authRegistration'));

Route::group(['middleware' => ['minibank_auth']], function () {

    #LogOut
    Route::get('/logout/{email}',array('as'=>'Logout' , 'uses' =>'SystemAuthController@authLogout'));
    #ChangePassword
    Route::get('/change/password',array('as'=>'Change Password' , 'uses' =>'SystemAuthController@ProfileChangePasswordPage'));
    Route::post('/change/password',array('as'=>'Change Password' , 'uses' =>'SystemAuthController@UserProfileUpdatePassword'));


    #Dashboard
    Route::get('/profile/{name_slug}',array('as'=>'Dashboard', 'uses' =>'AccountManageController@AccountDashboard'));
    #Open Account
    Route::get('/open-account',array('as'=>'Account Open' , 'uses' =>'AccountManageController@AccountOpenPage'));
    Route::post('/open-account',array('as'=>'Account Open' , 'uses' =>'AccountManageController@AccountCreate'));
    Route::get('/my-account',array('as'=>'My Account' , 'uses' =>'AccountManageController@MyAccountPage'));

    #Transactions
    Route::get('/deposit-transactions',array('as'=>'Deposit Transaction' , 'uses' =>'BankingController@FundDepositPage'));
    Route::post('/deposit-transactions',array('as'=>'Deposit Transaction' , 'uses' =>'BankingController@FundDepositTransaction'));

    Route::get('/fund-transfer',array('as'=>'Fund Transfer Transaction' , 'uses' =>'BankingController@FundTransferPage'));
    Route::post('/fund-transfer',array('as'=>'Fund Transfer Transaction' , 'uses' =>'BankingController@FundTrasnferTransactionsProcess'));


    #List
    Route::get('/transaction/list',array('as'=>'Banking Transaction List' , 'uses' =>'BankingController@BankingTransactionList'));



});