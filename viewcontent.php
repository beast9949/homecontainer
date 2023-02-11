 <?php   include_once('includes/functions.php');
 include('feat.php');
    //session_start();
    error_reporting(0);
	//$user_id=$_SESSION['userId'];
	if($_COOKIE['useid'] !='')
{
 $user_id= $_COOKIE['useid']; 
}
else if($_SESSION['uids'] !='' )
{
	$user_id=$_SESSION['uids'];	
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

	?>
<html>
<head>

	  <style>
	 .fixed-header{
	position: relative;
	background:rgba(0,0,0,0.7);
	color: #fff;
	height:100px;
}
.dot {
  height: 10px;
  width: 10px;
  background-color: green;
  border-radius: 50%;
  display: inline-block;
}
.dot1{
  height: 10px;
  width: 10px;
  background-color:red;
  border-radius: 50%;
  display: inline-block;
}
.work.fa{
  color: #0000ff;
  font-size:20px;
}

.nwork.fa{
  color: #0f0f0f;
  font-size:20px;
}

button {
    background-color: Transparent;
    background-repeat:no-repeat;
    border: none;
    cursor:pointer;
    overflow: hidden;
    outline:none;
}
	  </style>
</head>
<body>

	 <script>
	function likeFunction(caller) {
		var channelid= caller;
		//alert(channelid);
  var settings = {
  "url": 'https://cors-anywhere.herokuapp.com/http://app.campusradio.rocks/api3/like_recode',
  "method": "POST",
  "timeout": 0,
  "headers": {
    "Content-Type": "application/json"
  },
  "data": JSON.stringify({"operation":"like_recode","channel_id":channelid,"track_id":"","user_id":"<?=$user_id;?>","like_flag":"1","api_key":"cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s"}),
};

$.ajax(settings).done(function (response) {
	//var count=response['Result'];
	var json = $.parseJSON(response); // create an object with the key of the array
       // alert(json.Result.Total_Likes_C);
  console.log(json);
  $('#like_cnt'+caller).html(json.Result.Total_Likes_C);

});

}
 $(document).ready(function(){
	 
    $(".with-color").click(function () {    
       if($(this).hasClass("nwork"))
       {
       		$(this).addClass("work");
       		$(this).removeClass("nwork");
       }
       else{
       		$(this).addClass("nwork");
       		$(this).removeClass("work");
       }
    });


	

    $(".pushme1").click(function(){
		$(this).text(function(i, v){
		   return v === 'Unlike' ? 'Like' : 'Unlike'
		   
		});
    });
	$(".pushme2").click(function(){
		$(this).text(function(i, v){
		   return v === 'Like' ? 'Unlike' : 'Like'
		   
		});
    });
});
	 </script>
<div class="col-md-offset-0 col-md-12 " >
<div style='float:left;position:relative;top:0%;padding-right:0%'> 
    <a href='index.php' class='btn btn-info btn-sm'>
      <span class='glyphicon glyphicon-home'></span> Home
    </a>
  </div><div class='col-md-11 text-center' ><div style="font-size:20px"><?php echo "Channel List";?></div></div>
	   </div>
<?php
$id = $_GET['id']; // pass param ex. $_POST[category_id];
$user_id=$_SESSION['userId'];
	?>
<?php
$operation = "channel_feature_details";
$api_key = "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s";
$channel_cat =  '0'  ;  //'1' for featured channel and  '2' for upcoming channel
$curl = curl_init();
$request1 = array('operation' => $operation,'channel_cat'=>$channel_cat,'api_key' =>$api_key);
 $operationInput = json_encode($request1);
curl_setopt_array($curl, array(
  CURLOPT_URL =>  app_url()."/api2/logs",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $operationInput,
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json"
  ),
));


$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$jsondecode =  json_decode($response);
$convertarray = array($jsondecode);


	
echo "<div class='row'>";
foreach($convertarray as $array){
	$College = $array->College_List;

	
	$cnt=0;
	foreach($College as $College_List){
		
		$channel_id = $College_List->channel_id;
		$channel_name = $College_List->channel_name;
		$channel_image = $College_List->channel_image;
		$channel_url = $College_List->channel_url;
		$channel_description = $College_List->channel_description;
		/**current track api**/
		$api_key = "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s";
				$curl = curl_init();
				$request1 = array('operation' => 'current_track','channel_url'=>$channel_url,'api_key' =>$api_key);
				 $operationInput = json_encode($request1);
				curl_setopt_array($curl, array(
				  CURLOPT_URL =>  app_url()."/api2/logs",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => $operationInput,
				  CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					"content-type: application/json"
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);
				$jsondecode =  json_decode($response);
				$convertarray = array($jsondecode);
				foreach($convertarray as $array){
				if(array_key_exists('Track_time',$array))
				$Track_time = $array->Track_time;
				if(array_key_exists('Track_Name',$array)){
				$Track_Name = $array->Track_Name;
				
				}
				if(array_key_exists('Artist_img',$array))
				$Artist_img = $array->Artist_img;
				if(array_key_exists('Album_img',$array))
				$Album_img = $array->Album_img;
				}
			$url_like = app_url()."/api3/like_recode";
$myvars_like=array(
	'operation'=>'like_recode',
	'channel_id'=>$channel_id,
	'track_id'=>'',
	'user_id'=>$user_id,
	'like_flag'=>'0',
	'api_key'=>'cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s'
	);
	

	// print_r($myvars_like);
	$ch = curl_init($url_like); 			
		$data_string = json_encode($myvars_like);    
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");    
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
		
$res_like = json_decode(curl_exec($ch),true);
			$responce_like = $res_like["Result"]["User_Liked_C"];
			$total_like = $res_like["Result"]["Total_Likes_C"];
			// print_r($responce_like); 
if($responce_like=="false"){
	$like_btn = "<button type='button' class='fa fa-thumbs-up nwork with-color' name='linkeBtn' id='linkeBtn$channel_id' onclick='likeFunction($channel_id)'></button>&nbsp;<span id ='like_cnt$channel_id' style='color:red;font-weight:bold'>$total_like</span>";
}else{
	$like_btn = "<button type='button'  class='fa fa-thumbs-up work with-color' name='linkeBtn' id='linkeBtn$channel_id' onclick='likeFunction($channel_id)'></button> &nbsp;<span id ='like_cnt$channel_id' style='color:red;font-weight:bold'>$total_like</span>";
}	
		
	?>	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
	<?php	
		
		
		
echo"<div class='col-md-2 text-center' style='padding-top:5%;'>";
if( $_SESSION['userId']=='')
		{
			echo "<a href='login.php'>";
			echo "<div><img src='$channel_image' width='100' height='80' alt='Campus Radio' ></div>";
		echo "<div style='padding-top:5%;'><font size=2% color=black>$channel_name";
		
		if(array_key_exists('Track_Name',$array))
			echo "&nbsp;&nbsp;<span class='dot'></span>";
		else
			echo "&nbsp;&nbsp;<span class='dot1'></span>";
		echo"</font></div></a>";
		echo "</div>";
		}else {
		echo "<a href='channel_track.php?id=$channel_id' >";
		
		
		echo "<div><img src='$channel_image' width='100' height='80' alt='Campus Radio' ></div>";
		echo "<div style='padding-top:5%;'><font size=2% color=black>$channel_name";
		
		if(array_key_exists('Track_Name',$array))
			echo "&nbsp;&nbsp;<span class='dot'></span>";
		else
			echo "&nbsp;&nbsp;<span class='dot1'></span>";
		echo"</font></div></a>";
		echo $like_btn;
		echo "</div>";
		}
		
}
}
echo "</div>";
echo "</div>";
?>


</body></html>