<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*******************************
#
## System Model
#
 *******************************/

class System extends Model
{


    /********************************************
    ## CustomLogWritter
     *********************************************/

    public static function CustomLogWritter($log_dir,$file_type_name,$message){

        if (!file_exists(storage_path($log_dir)))
            mkdir(storage_path($log_dir), 0777, true);

        $logFile= storage_path($log_dir.'/'.$file_type_name)."_".date("d_m_Y").".txt";
        $time = date("d-m-Y H:i:s");
        $file = fopen($logFile, (file_exists($logFile)) ? 'a' : 'w');
        fwrite($file,"$time - $file_type_name --> $message\n");

        fclose($file);

        return true;
    }


    /********************************************
    ## AccessLogWrite
     *********************************************/
    public static function AccessLogWrite(){

        $page_title = \Request::route()->getName();
        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        $client = \App\System::getBrowser();
        if(!empty($client)) {
            $client_info = $client;
        } else {
            $client_info = [];
        }
//        $client_location  = \App\System::geolocation($client_ip);
        $client_location  = [];

        if(\Auth::check()){
            $user_id=  \Auth::user()->id;
        }else
            $user_id= 'user';

        $msisdn = \App\System::MSISDNTrack();

        if($msisdn){
            $access_user_msisdn=  $msisdn;
        }else $access_user_msisdn= 'guest';


        $access_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $access_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $access_country = isset($client_location['country']) ? $client_location['country'] : '' ;


        $now = date('Y-m-d H:i:s');
        $access_data = [

            'access_client_ip' => $client_ip,
            'access_user_id'   => $user_id,
            'access_user_msisdn'=>$access_user_msisdn,
            'access_browser'   => $client_info['userAgent'],
            'access_platform'  => $client_info['platform'],
            'access_city'      => $access_city,
            'access_division'  => $access_division,
            'access_country'   => $access_country,
            'access_message'   => $page_title.','.$page_url,
            'created_at'       => $now,
            'updated_at'       => $now


        ];

        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|' .$access_user_msisdn.'|' .$page_title.'|'.$page_url.'|'.$client_info['userAgent'].'|'.$client_info['platform'].'|'.$access_city.'|'.$access_division.'|'.$access_country;

        \App\System::CustomLogWritter("systemlog","access_log",$message);


        return true;

    }

    /********************************************
    ## EventLogWrite
     *********************************************/
    public static function EventLogWrite($event_type,$event_data){

        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();


        if(\Auth::check())
            $user_id = \Auth::user()->id;
        else
            $user_id = 'user';

        $now = date('Y-m-d H:i:s');
        $event_insert = [

            'event_client_ip' => $client_ip,
            'event_user_id'   => $user_id,
            'event_request_url' => $page_url,
            'event_type'  => $event_type,
            'event_data'  => $event_data,
            'created_at'  => $now,
            'updated_at'  => $now

        ];


        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$page_url.'|'.$event_type.'|'.$event_data;

        \App\System::CustomLogWritter("eventlog","event_log",$message);

        return true;

    }

    /********************************************
    ## ErrorLogWrite
     *********************************************/
    public static function ErrorLogWrite($error_data){

        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();


        if(\Auth::check())
            $user_id = \Auth::user()->id;
        else
            $user_id = 'user';


        $now = date('Y-m-d H:i:s');
        $error_insert = [
            'error_client_ip' => $client_ip,
            'error_user_id'   => $user_id,
            'error_request_url' => $page_url,
            'error_data'  => $error_data,
            'created_at'  => $now,
            'updated_at'  => $now

        ];


        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$page_url.'|'.$error_data;

        \App\System::CustomLogWritter("errorlog","error_log",$message);

        return true;

    }

    /********************************************
    ## AuthLogWrite
     *********************************************/
    public static function AuthLogWrite($auth_status){

        $client_ip  = \App\System::get_client_ip();
        $client_ip  = \App\System::get_client_ip();
        $client = \App\System::getBrowser();
        if(!empty($client)) {
            $client_info = $client;
        } else {
            $client_info = [];
        }
//        $client_location  = \App\System::geolocation($client_ip);
        $client_location = [];;


        if(\Auth::check())
            $user_id = \Auth::user()->id;
        else
            $user_id = 'user';

        if($auth_status==1)
            $auth_type = "Log In";
        else $auth_type = "Log Out";

        $auth_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $auth_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $auth_country = isset($client_location['country']) ? $client_location['country'] : '' ;


        $now = date('Y-m-d H:i:s');
        $auth_insert = [

            'auth_client_ip' => $client_ip,
            'auth_user_id'   => $user_id,
            'auth_browser'   => $client_info['browser'],
            'auth_platform'  => $client_info['platform'],
            'auth_city'      => $auth_city,
            'auth_division'  => $auth_division,
            'auth_country'   => $auth_country,
            'auth_type'      => $auth_type,
            'created_at'       => $now,
            'updated_at'       => $now

        ];


        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$auth_type.'|'.$client_info['browser'].'|'.$client_info['platform'].'|'.$auth_city.'|'.$auth_division.'|'.$auth_country;

        \App\System::CustomLogWritter("authlog","auth_log",$message);

        return true;

    }


    /********************************************
    ## get_client_ip
     *********************************************/
    public static function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        if($ipaddress=='::1')
            $ipaddress = getHostByName(getHostName());

        return $ipaddress;
    }



    /********************************************
    ## getBrowser
     *********************************************/

    public static function getBrowser(){

        $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT']:'Unknown';
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= '';

        //First get the platform?
        if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent)){

            preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent,$matches);

            $platform = $matches[0];

        }
        elseif (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';

        }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif(preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        } else {
            $platform = 'Unknown';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif(preg_match('/Firefox/i',$u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif(preg_match('/Chrome/i',$u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif(preg_match('/Safari/i',$u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif(preg_match('/Opera/i',$u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif(preg_match('/Netscape/i',$u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        } else {
            $bname = 'Netscape';
            $ub='Unknown';
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= isset($matches['version'][0])?$matches['version'][0]:'';
            } else {
                $version= isset($matches['version'][1]) ? $matches['version'][1]:'';
            }
        }
        else {
            $version= isset($matches['version'][0])?$matches['version'][0]:'';
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'browser'   => $bname,
            'version'   => $version,
            'platform'  => $platform,
        );
    }


    /********************************************
    ## RequestLogWrite
     *********************************************/
    public static function RequestLogWrite(){

        $page_title = \Request::route()->getName();
        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        $client = \App\System::getBrowser();
        if(!empty($client)) {
            $client_info = $client;
        } else {
            $client_info = [];
        }
//      $client_location  = \App\System::geolocation($client_ip);
        $client_location  = [];;

        if(\Auth::check()){
            $user_id=  \Auth::user()->id;
        }else
            $user_id= 'guest';


        $request_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $request_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $request_country = isset($client_location['country']) ? $client_location['country'] : '' ;


        $now = date('Y-m-d H:i:s');
        $request_data = [
            'request_client_ip' => $client_ip,
            'request_user_id'   => $user_id,
            'request_browser'   => $client_info['browser'],
            'request_platform'  => $client_info['platform'],
            'request_city'      => $request_city,
            'request_division'  => $request_division,
            'request_country'   => $request_country,
            'created_at'       => $now,
            'updated_at'       => $now

        ];


        /***********Text Log**************************/

        $message = $client_ip.'|'.$user_id.'|'.$page_title.'|'.$page_url.'|'.$client_info['browser'].'|'.$client_info['platform'].'|'.$request_city.'|'.$request_division.'|'.$request_country;

        \App\System::CustomLogWritter("requestlog","request_log",$message);

        return 0;

    }



    /********************************************
    ## APILogWrite
     *********************************************/
    public static function APILogWrite($resquest_data,$request_response){

        $page_title = \Request::route()->getName();
        $page_url   = \Request::fullUrl();
        $client_ip  = \App\System::get_client_ip();
        $client = \App\System::getBrowser();
        if(!empty($client)) {
            $client_info = $client;
        } else {
            $client_info = [];
        }
//        $client_location  = \App\System::geolocation($client_ip);
        $client_location = [];

        $msisdn = \App\System::MSISDNTrack();

        if($msisdn){
            $user_id=  $msisdn;
        }else $user_id= 'guest';

        $channel_name = (isset($_GET['sub_channel']) && !empty($_GET['sub_channel']))?$_GET['sub_channel']:'';


        $request_city = isset($client_location['city']) ? $client_location['city'] : '' ;
        $request_division = isset($client_location['division']) ? $client_location['division'] : '' ;
        $request_country = isset($client_location['country']) ? $client_location['country'] : '' ;

        $response_type = isset($request_response['success'])? 'success':'errors';

        $now = date('Y-m-d H:i:s');
        $request_data = [
            'request_client_ip' => $client_ip,
            'request_user_id'   => $user_id,
            'channel_name'   => $channel_name,
            'request_browser'   => $client_info['browser'],
            'request_platform'  => $client_info['platform'],
            'request_city'      => $request_city,
            'request_division'  => $request_division,
            'request_country'   => $request_country,
            'request_url'       => $page_url,
            'request_data'      => json_encode($resquest_data),
            'response_type'     => $response_type,
            'request_response'  => json_encode($request_response),
            'created_at'        => $now,
            'updated_at'        => $now

        ];



        /***********Text Log**************************/
        $message = PHP_EOL."Request : ".$client_ip.'|'.$user_id.'|'.$page_title.'|'.$page_url.'| '.json_encode($resquest_data).' |'.$client_info['browser'].'|'.$client_info['platform'].'|'.$request_city.'|'.$request_division.'|'.$request_country."-->>Response : ".json_encode($request_response);

        \App\System::CustomLogWritter("apilog","api_log",$message);

        // return $request_id;
        return true;

    }


    /**********************************************************
    ## MSISDNTrack
     *************************************************************/

    public static function MSISDNTrack(){

        if(isset($_SERVER['USER_IDENTITY_FORWARD_MSISDN']))
        {
            $mobile_number = trim($_SERVER['HTTP_X_UP_CALLING_LINE_ID']);
        }
        else if(isset($_SERVER['HTTP_MSISDN']))
        {
            $mobile_number = trim($_SERVER['HTTP_MSISDN']);
        }
        else if(isset($_SERVER['HTTP_X_FH_MSISDN']))
        {
            $mobile_number = trim($_SERVER['HTTP_X_FH_MSISDN']);
        }
        else if(isset($_SERVER['HTTP_X_HTS_CLID']))
        {
            $mobile_number = trim($_SERVER['HTTP_X_HTS_CLID']);
        }
        else if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID']))
        {
            $mobile_number = trim($_SERVER['HTTP_X_UP_CALLING_LINE_ID']);
        }
        else if(isset($_SERVER['HTTP-ALL-RAW']))
        {
            $mobile_number = trim($_SERVER['HTTP-ALL-RAW']);
        }
        else if(isset($_SERVER['HTTP-HOST']))
        {
            $mobile_number = trim($_SERVER['HTTP-HOST']);
        }
        else if(isset($_SERVER['x-msisdn']))
        {
            $mobile_number = trim($_SERVER['x-msisdn']);
        }
        else if(isset($_SERVER['HTTP-x-msisdn']))
        {
            $mobile_number = trim($_SERVER['HTTP-x-msisdn']);
        }
        else if(isset($_SERVER['x-h3g-msisdn']))
        {
            $mobile_number = trim($_SERVER['x-h3g-msisdn']);
        }
        else if(isset($_SERVER['HTTP-x-h3g-msisdn']))
        {
            $mobile_number = trim($_SERVER['HTTP-x-h3g-msisdn']);
        }
        else if(isset($_SERVER['HTTP-X-MSISDN-Alias']))
        {
            $mobile_number = trim($_SERVER['HTTP-X-MSISDN-Alias']);
        }
        else if(isset($_SERVER['X-MSISDN-Alias']))
        {
            $mobile_number = trim($_SERVER['X-MSISDN-Alias']);
        }
        else if(isset($_SERVER['HTTP-x-h3g-msisdn']))
        {
            $mobile_number = trim($_SERVER['HTTP-x-h3g-msisdn']);
        }
        else if(isset($_SERVER['HTTP-msisdn']))
        {
            $mobile_number = trim($_SERVER['HTTP-msisdn']);
        }
        else if(isset($_SERVER['msisdn']))
        {
            $mobile_number = trim($_SERVER['msisdn']);
        }
        else if(isset($_SERVER['MSISDN']))
        {
            $mobile_number = trim($_SERVER['MSISDN']);
        }
        else if(isset($_SERVER['X-WAP-PROFILE']))
        {
            $mobile_number = trim($_SERVER['X-WAP-PROFILE']);
        }
        else if(isset($_SERVER['X-UP-CALLING-LINE-ID ']))
        {
            $mobile_number = trim($_SERVER['X-UP-CALLING-LINE-ID ']);
        }
        else if(isset($_SERVER['X-H3G-MSISDN']))
        {
            $mobile_number = trim($_SERVER['X-H3G-MSISDN']);
        }
        else if(isset($_SERVER['X-FH-MSISDN ']))
        {
            $mobile_number = trim($_SERVER['X-FH-MSISDN ']);
        }
        else if(isset($_SERVER['X-MSP-MSISDN']))
        {
            $mobile_number = trim($_SERVER['X-MSP-MSISDN']);
        }
        else if(isset($_SERVER['X-INTERNET-MSISDN']))
        {
            $mobile_number = trim($_SERVER['X-INTERNET-MSISDN']);
        }
        else if(isset($_SERVER['X_MSISDN']))
        {
            $mobile_number = trim($_SERVER['X_MSISDN']);
        }
        else if(isset($_SERVER['HTTP_X_MSISDN']))
        {
            $mobile_number = trim($_SERVER['HTTP_X_MSISDN']);
        }

        if (isset($mobile_number)) {

            return $mobile_number;

        } else {
            return 'NO_MSISDN';
        }
    }

    /**********************************************************
    ## MobileDesktopPlatformCheck
     *************************************************************/

    public static function MobileDesktopPlatformCheck(){

        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';

        $mobile_browser = '0';

        $agent = isset($_SERVER['HTTP_USER_AGENT'])?strtolower($_SERVER['HTTP_USER_AGENT']):'';

        if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent))
            $mobile_browser++;

        if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
            $mobile_browser++;

        if (strpos(strtolower($agent),' ppc;')>0) {
            $mobile_browser++;
        }
        if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
            $mobile_browser++;

        if(isset($_SERVER['HTTP_PROFILE']))
            $mobile_browser++;

        $mobile_ua = substr($agent,0,4);
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','xda','xda-'
        );

        if(in_array($mobile_ua, $mobile_agents))
            $mobile_browser++;

        if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
            $mobile_browser++;
        //if((strpos(strtolower($_SERVER['HTTP_USER_AGENT']), ‘windows’) !== false) && (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), ‘phone’) !== true))
        // Pre-final check to reset everything if the user is on Windows
        if(strpos($agent, 'windows') !== false)
            $mobile_browser=0;

        // But WP7 is also Windows, with a slightly different characteristic
        if(strpos($agent, 'windows phone') !== false)
            $mobile_browser++;
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows ce')>0) {
            $mobile_browser++;
        }
        else if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) {
            $mobile_browser=0;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'iemobile')>0) {
            $mobile_browser++;
        }

        if($mobile_browser >0)
            return 'mobile';
        else
            return 'desktop';
    }


    /**********************************************************
    ## MultiArraySerach
     *************************************************************/

    public static function MultiArraySerach($search,$search_key,$array){

        foreach ($array as $key => $value) {
            if ($value[$search_key] == $search) {
                return $key;
            }
        }
        return -1;
    }

    /**********************************************************
    ## RandomStringNum
     *************************************************************/

    public static function RandomStringNum($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range(0, 9), range(0, 9),range(0, 9));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }


####################### End #####################################
}
