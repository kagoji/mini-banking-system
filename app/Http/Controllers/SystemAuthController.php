<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
//use App\System;
//use App\Email;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;



class SystemAuthController extends Controller
{
    /**
     * Class constructor.
     * get current route name for page title.
     *
     */
    public function __construct()
    {
        $this->page_title = \Request::route()->getName();
    }

    /**
     * Show admin login page for admin
     * checked Auth user, if failed get user data according to email.
     * checked user type, if "admin" redirect to dashboard
     * or redirect to login.
     *
     * @return HTML view Response.
     */
    public function authLogin()
    {
        if (\Auth::check()) {
            \App\User::LogInStatusUpdate("login");
            return redirect('/profile/'.\Auth::user()->name_slug)->with('error-message', 'You have already loggedin !.');
        } else {
            $data['page_title'] = $this->page_title;
            return view('pages.login',$data);
        }
    }

    /**
     * Check Admin Authentication
     * checked validation, if failed redirect with error message
     * checked auth $credentials, if failed redirect with error message
     * checked user type, if "admin" change login status.
     *
     * @param  Request $request
     * @return Response.
     */
    public function authPostLogin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $credentials = [
            'email' => $request->input('email'),
            'password'=>$request->input('password'),
            'status'=> "active"
        ];

        if (\Auth::attempt($credentials)) {

            \Session::put('email', \Auth::user()->email);
            \Session::put('last_login', Auth::user()->last_login);

            \App\User::LogInStatusUpdate("login");

            if (\Session::has('pre_login_url') ) {
                $url = \Session::get('pre_login_url');
                \Session::forget('pre_login_url');
                return redirect($url);
            }else{
                return redirect('/profile/'.\Auth::user()->name_slug)->with('message', 'You have successfully loggedin !.');
            }

        } else {
            return redirect('/login')->with('error-message',"Incorrect combinations.Please try again.");
        }
    }

    /**
     * Admin logout
     * check auth login, if failed redirect with error message
     * get user data according to email
     * checked name slug, if found change login status and logout user.
     *
     * @param string $name_slug
     * @return Response.
     */
    public function authLogout($email)
    {
        if (\Auth::check()) {
            $user_info = \App\User::where('email',\Auth::user()->email)->first();
            if (!empty($user_info) && ($email==$user_info->email)) {
                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();
                //\Session::flush();
                return \Redirect::to('/login')->with('success-message',"You have successfully logged out!!");
            } else {
                return \Redirect::to('/login')->with('success-message',"Error logout");
            }
        } else {
            return \Redirect::to('/login')->with('error-message',"Error logout");
        }
    }

    /**
     * User Registration
     * checked validation, if failed redirect with message
     * data store into users table.
     *
     * @param Request $request
     * @return Response
     */
    public function RegitrationPage(){

        $data['page_title'] = $this->page_title;
        return view('pages.registration',$data);
    }

    public function authRegistration(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'retype_password' => 'required|same:password',

        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        try {

            $registration=array(
                'name' => ucwords($request->input('name')),
                'name_slug' => Str::slug($request->input('name'), '-'),
                'user_profile_image' => '',
                'login_status' => 0,
                'status' => 'active',
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            );

            $registration_confirm = \App\User::firstOrCreate($registration);

            if($registration_confirm) {
                return redirect('/login')->with('success-message',"You have successfully registered");
            }

        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('/registration')->with('error-message',"Duplicate email or something is wrong on user registration ! Please try again..");
        }
    }

    public function ProfileChangePasswordPage(){

        $data['page_title'] = $this->page_title;
        return view('pages.account.change-password',$data);
    }

    /********************************************
    ## UserProfileUpdatePassword
     *********************************************/
    public function UserProfileUpdatePassword(Request $request){

        $now=date('Y-m-d H:i:s');

        $v = \Validator::make($request->all(), [
            'new_password' => 'Required|min:6',
            'confirm_password' => 'Required|same:new_password',
            'current_password' => 'Required',

        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        try{

            $new_password=\Request::input('new_password');
            $confirm_password=\Request::input('confirm_password');

            $user_info=\App\User::where('id',\Auth::user()->id)->first();

            if (\Hash::check(\Request::input('new_password'), $user_info->password))
                throw new \Exception('Password already has been used');

            if (\Hash::check(\Request::input('current_password'), $user_info->password)){
                $update_password=array(
                    'password' => bcrypt(\Request::input('new_password')),
                    'updated_at' => $now,
                );

                \Auth::logout();
                $update=\App\User::where('id',\Auth::user()->id)->update($update_password);
                \App\System::EventLogWrite('update,users', 'password changed');

                return \Redirect::to('/login')->with('success-message',"Password and Token Updated Successfully and Try to login with new password!");

            }else return \Redirect::to('/change/password')->with('error-message',"Password Combination Doesn't Match !");

        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('/change/password')->with('error-message',$e->getMessage());
        }
    }
}
