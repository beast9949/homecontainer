<?php
	  include_once('includes/functions.php');
if($_COOKIE['useid'] !='')
{
 $user_id= $_COOKIE['useid']; 
}else{
	$user_id= $_SESSION['userId']; 
}

	$timeNow=date('Y-m-d');

if($_COOKIE['times'] !=$timeNow)
{
					$ip_server= $_SERVER['REMOTE_ADDR']; 
			function get_operating_system() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $operating_system = 'Unknown Operating System';

//Get the operating_system
    if (preg_match('/linux/i', $u_agent)) {
        $operating_system = 'Linux';
    } elseif (preg_match('/macintosh|mac os x|mac_powerpc/i', $u_agent)) {
        $operating_system = 'Mac';
    } elseif (preg_match('/windows|win32|win98|win95|win16/i', $u_agent)) {
        $operating_system = 'Windows';
    } elseif (preg_match('/ubuntu/i', $u_agent)) {
        $operating_system = 'Ubuntu';
    } elseif (preg_match('/iphone/i', $u_agent)) {
        $operating_system = 'IPhone';
    } elseif (preg_match('/ipod/i', $u_agent)) {
        $operating_system = 'IPod';
    } elseif (preg_match('/ipad/i', $u_agent)) {
        $operating_system = 'IPad';
    } elseif (preg_match('/android/i', $u_agent)) {
        $operating_system = 'Android';
    } elseif (preg_match('/blackberry/i', $u_agent)) {
        $operating_system = 'Blackberry';
    } elseif (preg_match('/webos/i', $u_agent)) {
        $operating_system = 'Mobile';
    }
    
    return $operating_system;
}
$os=$operating_system;
date_default_timezone_set('Asia/Kolkata');
 $time= date("d-m-Y H:i:s") ; 
$_SESSION['time']=$time;
	//for information from profile
                  $user_id = $_SESSION['userId'];
                  $curl = curl_init();

                  curl_setopt_array($curl, array(
                    CURLOPT_URL =>  app_url()."/api3/user_profile_show",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\r\n\r\n\"operation\":\"user_profile_show\",\r\n\r\n\"userid\":\"$user_id\",\r\n\r\n\"api_key\":\"cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s\"\r\n\r\n}",
                    CURLOPT_HTTPHEADER => array(
                      "Content-Type: application/json",
                      "Postman-Token: 9f4c60f3-df44-40ac-9fb1-5f0514ed634d",
                      "cache-control: no-cache"
                    ),
                  ));

                  $response = curl_exec($curl);
				
                  curl_close($curl);

                
                    $jsondecode =  json_decode($response);
				//	print_r($jsondecode);exit;
                    $user_info = $jsondecode->data;
					$state=$user_info->state;
					$city=$user_info->city;
					$country=$user_info->country;
					$SMC_college=$user_info->college_name;
					$SMC_College_id=$user_info->college_id;
					$pref_college=$user_info->preferred_college;
					$country_code=$user_info->country_code;
					
					

						if($SMC_college=='')
						{
							$college=$pref_college;
						}
						else 
						{
							$college=$SMC_college;
						}
			$url= app_url()."/api3/logs";
$data=array( 'operation'=>'logs',
'App_Name'=>'Campus Radio',
'UserID'=>$user_id,
'Action'=>'Login', //Login,Logout,Registration,Update
'PlaylistItemID'=>'',
'CategoryID'=>'',
'ChannelID'=>'',
'ChannelCategoryName'=>'',
'ActionTime'=>$time, //28-04-2020 15:35:59
'ActionDuration'=>'',//28-04-2020 15:45:59
'DeviceName'=>'Web',
'IPAddress'=>$ip_server,
'OSVersion'=>$os,
'CountryCode'=>$country_code,
'PosLat'=>'',
'PosLong'=>'',
'college_id'=>$SMC_College_id,
'college_name'=>$college,
'city'=>$city,
'state'=>$state,
'country'=>$country,
'api_key'=>'cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s');
$res_cat = get_curl_result($url,$data);

setcookie ('times',$timeNow,time() +10 * 365 * 24 * 60 * 60);
}

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL =>  app_url()."/api2/get_category_index?api_key=cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "{\"operation\":\"Channel_Details\",\"api_key\":\"cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s\"}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json"),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$jsondecode =  json_decode($response);
$convertarray = array($jsondecode);
	echo "<div class='row'>";
foreach($convertarray as $array){
	$categories = $array->categories;


		foreach($categories as $categoriespara){
		$cid = $categoriespara->cid;
		$category_name = $categoriespara->category_name;
		$category_image1 = $categoriespara->category_image;
		$category_image =  app_url()."/upload/category/".$category_image1;
	
		
		echo "<div class='col-md-2' >";
		
		echo "<img src='$category_image' style='width:60%;height:40%;' alt='Cinque Terre'>";
            echo "<div><font color=white>$category_name</font></div>";
		echo "</div>";
		
		
	}
	
	echo "</div>";
	
}
?>