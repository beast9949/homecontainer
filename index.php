<?php
error_reporting(0);
include_once('includes/functions.php');
include_once('conn.php');

if (isset($_SESSION['email_google'])) {
	$a = $_SESSION['email_google'];
};

function get_operating_system()
{
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$operating_system = 'Unknown Operating System';

	//Get the operating_system
	if (preg_match('/linux/i', $u_agent)) {
		$operating_system = 'Linux';
	} elseif (preg_match('/macintosh|mac os x|mac_powerpc/i', $u_agent)) {
		return  $operating_system = 'Mac';
	} elseif (preg_match('/windows|win32|win98|win95|win16/i', $u_agent)) {
		return  $operating_system = 'Windows';
	} elseif (preg_match('/ubuntu/i', $u_agent)) {
		return  $operating_system = 'Ubuntu';
	} elseif (preg_match('/iphone/i', $u_agent)) {
		return  $operating_system = 'IPhone';
	} elseif (preg_match('/ipod/i', $u_agent)) {
		return  $operating_system = 'IPod';
	} elseif (preg_match('/ipad/i', $u_agent)) {
		return  $operating_system = 'IPad';
	} elseif (preg_match('/android/i', $u_agent)) {
		return  $operating_system = 'Android';
	} elseif (preg_match('/blackberry/i', $u_agent)) {
		return  $operating_system = 'Blackberry';
	} elseif (preg_match('/webos/i', $u_agent)) {
		return $operating_system = 'Mobile';
	} else {
		return $operating_system;
	}
}


//echo $a;
if ($a != '' && $a != NULL) {
	$q1 = "SELECT * FROM tbl_user where email='$a'";
	$query = mysqli_query($conn, $q1);
	$res = mysqli_fetch_assoc($query);
} else {
	$res['email'] == '';
}

$_SESSION['uids'] = $res['id'];

if ($_COOKIE['useid'] != '') {
	$user_id = $_COOKIE['useid'];
} else if ($res['email'] != '') {
	$user_id = $_SESSION['uids'];
} else {
	$user_id = $_SESSION['userId'];
}

$timeNow = date('Y-m-d');

if ($_COOKIE['times'] != $timeNow) {
	$ip_server = $_SERVER['REMOTE_ADDR'];
	$os = get_operating_system();
	date_default_timezone_set('Asia/Kolkata');
	$time = date("d-m-Y H:i:s");
	$_SESSION['time'] = $time;
	//for information from profile
	// $user_id = $_SESSION['userId'];
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => app_url() . "/api3/user_profile_show",
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
	$state = $user_info->state;
	$city = $user_info->city;
	$country = $user_info->country;
	$SMC_college = $user_info->college_name;
	$SMC_College_id = $user_info->college_id;
	$pref_college = $user_info->preferred_college;
	$country_code = $user_info->country_code;

	if ($SMC_college == '') {
		$college = $pref_college;
	} else {
		$college = $SMC_college;
	}
	$url = app_url() . "/api3/logs";
	$data = array(
		'operation' => 'logs',
		'App_Name' => 'Campus Radio',
		'UserID' => $user_id,
		'Action' => 'Login', //Login,Logout,Registration,Update
		'PlaylistItemID' => '',
		'CategoryID' => '',
		'ChannelID' => '',
		'ChannelCategoryName' => '',
		'ActionTime' => $time, //28-04-2020 15:35:59
		'ActionDuration' => '', //28-04-2020 15:45:59
		'DeviceName' => 'Web',
		'IPAddress' => $ip_server,
		'OSVersion' => $os,
		'CountryCode' => $country_code,
		'PosLat' => '',
		'PosLong' => '',
		'college_id' => $SMC_College_id,
		'college_name' => $college,
		'city' => $city,
		'state' => $state,
		'country' => $country,
		'api_key' => 'cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s'
	);
	$res_cat = get_curl_result($url, $data);

	setcookie('times', $timeNow, time() + 10 * 365 * 24 * 60 * 60);
}
//echo $user_id;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Campus Radio</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>
	<style>
		html {
			font-size: 100%;
		}

		body {
			/*background-image: url('back.png');*/
			background-repeat: no-repeat;
			background-attachment: fixed;
			< !--background-size: 100% 100%;
			-->height: 120px;
		}

		.fixed-header {
			position: relative;
			background-image: url('back.png');
			color: #fff;
			height: 100px;
		}

		.fixed-footer {
			position: relative;
			background: rgba(0, 0, 0, 0.5);
			padding: 0px 16px;
			color: #fff;
			height: auto;

		}

		nav a {
			color: #fff;
			text-decoration: none;
			padding: 7px 25px;
			display: inline-block;
		}

		* {
			box-sizing: border-box;
		}

		body {
			background-color: #f1f1f1;
			padding: 0px;
			font-family: Arial;
		}

		/* Slideshow container */
		.slideshow-container {
			height: 150px;
			position: relative;
			margin: auto;
			width: auto;
			padding-left: auto;
		}

		p {
			font-size: 20px;
			text-align: left;
		}

		#search_btn:hover {
			background: #ccc;
		}

		.dot {
			height: 10px;
			width: 10px;
			background-color: green;
			border-radius: 50%;
			display: inline-block;
		}

		.dot1 {
			height: 10px;
			width: 10px;
			background-color: red;
			border-radius: 50%;
			display: inline-block;
		}

		.work.fa {
			color: #0000ff;
			font-size: 20px;
		}

		.nwork.fa {
			color: #0f0f0f;
			font-size: 20px;
		}

		button {
			background-color: Transparent;
			background-repeat: no-repeat;
			border: none;
			cursor: pointer;
			overflow: hidden;
			outline: none;
		}
	</style>
	<script>
		function viewall1() {
			//alert("hey");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					//alert("in if");
					document.getElementById("up1").innerHTML = "<a href='index.php'><button style='background-color:rgba(0,0,0,0.1);float:right;'>view less</button></a>";
					document.getElementById("row").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "view_all_categories.php", true);
			xmlhttp.send();
		}

		function viewall() {
			//alert("hey");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					//alert("in if");
					document.getElementById("up3").innerHTML = "<a href='index.php'><button style='background-color:rgba(0,0,0,0.1);float:right;'>view less</button></a>";
					document.getElementById("row3").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "viewcontent.php", true);
			xmlhttp.send();
		}

		function viewall_feat() {
			//alert("hey");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					//alert("in if");
					document.getElementById("up").innerHTML = "<a href='index.php'><button style='background-color:rgba(0,0,0,0.1);float:right;'>view less</button></a>";
					document.getElementById("row_feat").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "viewcontent_feat.php", true);
			xmlhttp.send();
		}

		function func() {
			alert("hii");
		}

		function viewall_upcome() {
			//alert("hey");
			var xmlhttp;
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					//alert("in if");
					document.getElementById("up1").innerHTML = "<a href='index.php'><button style='background-color:rgba(0,0,0,0.1);float:right;'><h5>view less</h5></button></a>";
					document.getElementById("row1_upcome").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "viewcontent_upcome.php", true);
			xmlhttp.send();
		}
	</script>
</head>

<body>
	<nav class="navbar navbar-inverse navbar-static-top" style="margin-bottom: 0px;">
		<div class="container-fluid">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="#" style="padding: 10px 10px;">
					<!--<img src="logo.png" height="40" width="45" alt="logo">-->
					<img src="newlogo.png" height="45" width="80" alt="logo">
				</a>
			</div>


			<div id="navbar" class="navbar-collapse collapse navbar-right">

				<ul class="nav navbar-nav">
					<li>
						<form action="search_channel.php" method='POST' style="margin-top:7%;">
							<input type="text" placeholder="Search.." name="search">
							<button type="submit" id="search_btn"><i class="fa fa-search"></i></button>
						</form>
					</li>

					<li class="active"><a href="index.php">Home</a></li>
					<li><a href="campus_guide.php">Guide</a></li>
					<?php if ($user_id != '') { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<i class="fa fa-user" style="font-size:20px;font-color:white">
									&nbsp;&nbsp;<?php

												if ($res['username'] != '') {
													echo $res['username'];
												} else if ($_SESSION['uName'] != '') {
													echo $_SESSION['uName'];
												} else {
													echo $_COOKIE['Unames'];
												}


												?></i>
								<i class='fas fa-angle-down' style='font-size:20px'></i>
							</a>

							<ul class="dropdown-menu navbar-inverse navbar-nav" role="menu">

								<li><a href="user_profile.php">User Profile</a></li>
								<li><a href="otpForm.php">Find Reward ID</a></li>
								<li><a href="joinUsTeam.php">Join As Team Member</a></li>
								<li><a href="logout.php">Logout</a></li>
							</ul>
						</li>
					<?php } else { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<i class="fa fa-bars" style="font-size:25px"></i>
							</a>

							<ul class="dropdown-menu navbar-inverse navbar-nav" role="menu">

								<li><a href="login.php">Login</a></li>
								<li><a href="otpForm.php">Find Reward ID</a></li>

							<?php } ?>
							</ul>
						</li>

				</ul>
			</div>

		</div>
	</nav>
	<br>
	<div class="slideshow-container" align=center>
		<div align="center" class="w3-content w3-section" style="width:50%">


			<?php
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => app_url() . "/api2/logs",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "{\"operation\":\"slider_show\",\"api_key\":\"cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s\"}",
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

			foreach ($convertarray as $array) {
				$slider = $array->sliders;
				foreach ($slider as $sliderpara) {
					$slider_name = $sliderpara->slider_name;
					$slider_image = $sliderpara->slider_image;
					/*echo $slider_image."<br>";*/
					echo "<img class='mySlides' src='$slider_image'  style='width:100%;height:150px;'>";
					/*echo $slider_name."<br>";*/
				}
			}
			?>
		</div>
	</div>
	<script>
		var myIndex = 0;
		carousel();

		function carousel() {
			var i;
			var x = document.getElementsByClassName("mySlides");
			for (i = 0; i < x.length; i++) {
				x[i].style.display = "none";
			}
			myIndex++;
			if (myIndex > x.length) {
				myIndex = 1
			}
			x[myIndex - 1].style.display = "block";
			setTimeout(carousel, 2000); // Change image every 2 seconds
		}
	</script>



	<div style='width:100%;'>
		<div style='padding-right:3%;'>
			<h1>
				<font size=5 color=black style='font-family:Georgia'> CATEGORIES LIST <span id='view_div1'></span></font>
			</h1>
		</div>


		<?php
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => app_url() . "/api2/get_category_index?api_key=cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "{\"operation\":\"Channel_Details\",\"api_key\":\"cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s\"}",
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

		echo "<div class='row' id='row'>";
		foreach ($convertarray as $array) {
			$categories = $array->categories;

			$cnt1 = 0;
			$cntall = 0;
			foreach ($categories as $categoriespara) {
				$cid = $categoriespara->cid;
				$cntall++;
				echo "<div class='col-md-2 text-center'>";
				if ($cnt1 <= 5) {

					$category_name = $categoriespara->category_name;
					$category_image1 = $categoriespara->category_image;
					$category_image = app_url() . "/upload/category/" . $category_image1;

					if ($user_id == '') {
						echo "<a href='login.php'>";
					} else {
						echo "<a href='category_content.php?id=$cid&catnm=$category_name'>";
					}
					echo
					"<div><img src='$category_image'alt='Cinque Terre' style='width:60%;height:40%;'></div>";
					echo "<div style='padding-top:3%;'><font color=black>$category_name</font></div></a>";

					$cnt1++;
				}
				echo "</div>";
			}
		}
		echo "</div>";
		if ($cntall > 6) {
			echo "<script>
<a href=# onclick=viewall1(); id=up1 ><button style=\"background-color:rgba(0,0,0,0.2);float:right;\"><h5>view more</h5></button></a>
</script>";
		}
		?>

		<!--------------------->
		<script>
			function likeFunction(caller) {
				var channelid = caller;
				alert("Channel ID: "+channelid);
				var settings = {
					"url": '<?= app_url(); ?>/api3/like_recode',
					"method": "POST",
					"timeout": 0,
					"headers": {
						"Content-Type": "application/json"
					},
					"data": JSON.stringify({
						"operation": "like_recode",
						"channel_id": channelid,
						"track_id": "",
						"user_id": "<?= $user_id; ?>",
						"like_flag": "1",
						"api_key": "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s"
					}),
				};

				$.ajax(settings).done(function(response) {
					//var count=response['Result'];
					var json = $.parseJSON(response); // create an object with the key of the array
					// alert(json.Result.Total_Likes_C);
					console.log(json);
					$('#like_cnt' + caller).html(json.Result.Total_Likes_C);

				});

				


			}
			$(document).ready(function() {

				$(".with-color").click(function() {
					if ($(this).hasClass("nwork")) {
						$(this).addClass("work");
						$(this).removeClass("nwork");
					} else {
						$(this).addClass("nwork");
						$(this).removeClass("work");
					}
				});

				$(".pushme1").click(function() {
					$(this).text(function(i, v) {
						return v === 'Unlike' ? 'Like' : 'Unlike'

					});
				});
				$(".pushme2").click(function() {
					$(this).text(function(i, v) {
						return v === 'Like' ? 'Unlike' : 'Like'

					});
				});
			});
		</script>
		<div>
			<div style='padding-right:3%;'>
				<h1>
					<font size=5 color=black style='font-family:Georgia'> FEATURED LIST<span id='view_span2'></span></font>
				</h1>
			</div>
			<div style="float:right;padding-right:0%;">
			</div>
		</div>

		<?php
		$operation = "channel_feature_details";
		$api_key = "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s";
		$channel_cat =  '1';   //'1' for featured channel and  '2' for upcoming channel
		$curl = curl_init();
		$request1 = array('operation' => $operation, 'channel_cat' => $channel_cat, 'api_key' => $api_key);
		$operationInput = json_encode($request1);
		curl_setopt_array($curl, array(
			CURLOPT_URL => app_url() . "/api2/logs",
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

		echo "<div class='row' id='row_feat'>";
		foreach ($convertarray as $array) {
			$College = $array->College_List;



			$cnt = 0;
			$cntall2 = 0;
			foreach ($College as $College_List) {
				$channel_id = $College_List->channel_id;
				$cntall2++;
				$q11 = mysqli_query($tvconnect, "Select c.uid,c.cid,u.user_id,u.user_full_name from `test.campustv.rocks`.channel_user_inter c 
LEFT JOIN  `test.campustv.rocks`.users u ON u.user_id=c.uid where c.cid='$channel_id'");
				$row1 = mysqli_fetch_array($q11);
				$channel_uname = $row1['user_full_name'];
				echo "<div class='col-md-2 text-center'>";
				if ($cnt <= 5) {

					$channel_name = $College_List->channel_name;
					$channel_image = $College_List->channel_image;
					$channel_url = $College_List->channel_url;
					$channel_description = $College_List->channel_description;
					/**current track api**/
					$api_key = "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s";
					$curl = curl_init();
					$request1 = array('operation' => 'current_track', 'channel_url' => $channel_url, 'api_key' => $api_key);
					$operationInput = json_encode($request1);
					curl_setopt_array($curl, array(
						CURLOPT_URL => app_url() . "/api2/logs",
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
					foreach ($convertarray as $array) {
						if (array_key_exists('Track_time', $array))
							$Track_time = $array->Track_time;
						if (array_key_exists('Track_Name', $array)) {
							$Track_Name = $array->Track_Name;
						}
						if (array_key_exists('Artist_img', $array))
							$Artist_img = $array->Artist_img;
						if (array_key_exists('Album_img', $array))
							$Album_img = $array->Album_img;
					}
					/*******/
					$url_like = app_url() . "/api3/like_recode";
					$myvars_like = array(
						'operation' => 'like_recode',
						'channel_id' => $channel_id,
						'track_id' => '',
						'user_id' => $user_id,
						'like_flag' => '0',
						'api_key' => 'cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s'
					);


					// print_r($myvars_like);
					$ch = curl_init($url_like);
					$data_string = json_encode($myvars_like);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

					$res_like = json_decode(curl_exec($ch), true);
					$responce_like = $res_like["Result"]["User_Liked_C"];
					$total_like = $res_like["Result"]["Total_Likes_C"];
					// print_r($responce_like); 
					if ($responce_like == "false") {
						$like_btn = "<button type='button' class='fa fa-thumbs-up nwork with-color' name='linkeBtn' id='linkeBtn$channel_id' onclick='likeFunction($channel_id)'></button>&nbsp;<span id ='like_cnt$channel_id' style='color:red;font-weight:bold'>$total_like</span>";
					} else {
						$like_btn = "<button type='button'  class='fa fa-thumbs-up work with-color' name='linkeBtn' id='linkeBtn$channel_id' onclick='likeFunction($channel_id)'></button> &nbsp;<span id ='like_cnt$channel_id' style='color:red;font-weight:bold'>$total_like</span>";
					}
					if ($user_id == '') {
						echo "<a href='login.php'>";
					} else {
						echo "<a href='channel_track.php?id=$channel_id'>";
					}
					echo "<div><img src='$channel_image' style='width:60%;height:50%;' alt='Cinque Terre' ></div>";

					echo "<div style='padding-top:3%;'><font size=2% color=black>$channel_name";
					if (array_key_exists('Track_Name', $array))
						echo "&nbsp;&nbsp;<span class='dot'></span>";
					else
						echo "&nbsp;&nbsp;<span class='dot1'></span>";
					echo "</font></div></a>"; ?>
					<a href="display_team_members.php?channel_id=<?php echo $channel_id; ?>">View Team </a><br> <?php
																												if ($user_id != '') {
																													echo $like_btn;
																												}
																												$cnt++;
																											}
																											echo "</div>";
																										}
																									}
																									if ($cntall2 > 6) {
																										echo "<script>
		document.getElementById(\"view_span2\").innerHTML='<a href=viewcontent_feat.php id=up ><button style=\"background-color:rgba(0,0,0,0.1);float:right;\"><h5>view more</h5></button></a>';
		</script>";
																									}
																									echo "</div>";
																												?>



		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


		<!------------------------->
		<div>
			<div style='padding-right:3%;'>
				<h1>
					<font size=5 color=black style='font-family:Georgia'> CHANNEL LIST<span id='view_span3'></span></font>
				</h1>
			</div>

		</div>

		<?php
		$operation = "channel_feature_details";
		$api_key = "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s";
		$channel_cat =  '0';    //'1' for featured channel and  '2' for upcoming channel
		$curl = curl_init();
		$request1 = array('operation' => $operation, 'channel_cat' => $channel_cat, 'api_key' => $api_key);
		$operationInput = json_encode($request1);
		curl_setopt_array($curl, array(
			CURLOPT_URL => app_url() . "/api2/logs",
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


		echo "<div class='row' id='row3'>";
		foreach ($convertarray as $array) {
			$College = $array->College_List;


			$cnt_channels = 0;
			$cnt = 0;
			foreach ($College as $College_List) {
				$channel_id = $College_List->channel_id;
				$cnt_channels++;
				if ($cnt <= 5) {
					echo "<div class='col-md-2 text-center'>";
					$channel_name = $College_List->channel_name;
					$channel_image = $College_List->channel_image;
					$channel_url = $College_List->channel_url;
					$channel_description = $College_List->channel_description;
					/**current track api**/
					$api_key = "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s";
					$curl = curl_init();
					$request1 = array('operation' => 'current_track', 'channel_url' => $channel_url, 'api_key' => $api_key);
					$operationInput = json_encode($request1);
					curl_setopt_array($curl, array(
						CURLOPT_URL => app_url() . "/api2/logs",
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
					foreach ($convertarray as $array) {
						if (array_key_exists('Track_time', $array))
							$Track_time = $array->Track_time;
						if (array_key_exists('Track_Name', $array)) {
							$Track_Name = $array->Track_Name;
						}
						if (array_key_exists('Artist_img', $array))
							$Artist_img = $array->Artist_img;
						if (array_key_exists('Album_img', $array))
							$Album_img = $array->Album_img;
					}
					/*******/
					$url_like = app_url() . "/api3/like_recode";
					$myvars_like = array(
						'operation' => 'like_recode',
						'channel_id' => $channel_id,
						'track_id' => '',
						'user_id' => $user_id,
						'like_flag' => '0',
						'api_key' => 'cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s'
					);


					// print_r($myvars_like);
					$ch = curl_init($url_like);
					$data_string = json_encode($myvars_like);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

					$res_like = json_decode(curl_exec($ch), true);
					$responce_like = $res_like["Result"]["User_Liked_C"];
					$total_like = $res_like["Result"]["Total_Likes_C"];
					// print_r($responce_like); 
					if ($responce_like == "false") {
						$like_btn = "<button type='button' class='fa fa-thumbs-up nwork with-color' name='linkeBtn' id='linkeBtn$channel_id' onclick='likeFunction($channel_id)'></button>&nbsp;<span id ='like_cnt$channel_id' style='color:red;font-weight:bold'>$total_like</span>";
					} else {
						$like_btn = "<button type='button'  class='fa fa-thumbs-up work with-color' name='linkeBtn' id='linkeBtn$channel_id' onclick='likeFunction($channel_id)'></button> &nbsp;<span id ='like_cnt$channel_id' style='color:red;font-weight:bold'>$total_like</span>";
					}
					if ($user_id == '') {
						echo "<a href='login.php'>";
					} else {
						echo "<a href='channel_track.php?id=$channel_id'>";
					}
					echo "<div><img src='$channel_image' style='width:60%;height:40%;' alt='Cinque Terre' ></div>";

					echo "<div style='padding-top:5%;'><font size=2% color=black>$channel_name";
					if (array_key_exists('Track_Name', $array))
						echo "&nbsp;&nbsp;<span class='dot'></span>";
					else
						echo "&nbsp;&nbsp;<span class='dot1'></span>";
					echo "</font></div></a>";
		?>
					<a href="display_team_members.php?channel_id=<?php echo $channel_id; ?>">View Team </a><br> <?php
																												if ($user_id != '') {
																													echo $like_btn;
																												}
																												$cnt++;
																												echo "</div>";
																											}
																										}
																									}
																									if ($cnt_channels > 6) {
																										echo "<script>
		document.getElementById(\"view_span3\").innerHTML='<a href=viewcontent.php ; id=up3 ><button style=\" background-color:rgba(0,0,0,0.1);float:right; \"><h5>view more</h5></button></a>';;
		</script>";
																									}
																									echo "</div>";
																												?>

		<!------------------------->
		<div>
			<div style='padding-right:3%;'>
				<h1>
					<font size=5 color=black style='font-family:Georgia'> UPCOMING CHANNELS<span id='view_span4'></span></font>
				</h1>
			</div>

		</div>

		<?php
		$operation = "channel_feature_details";
		$api_key = "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s";
		$channel_cat =  '2';    //'1' for featured channel and  '2' for upcoming channel
		$curl = curl_init();
		$request1 = array('operation' => $operation, 'channel_cat' => $channel_cat, 'api_key' => $api_key);
		$operationInput = json_encode($request1);
		curl_setopt_array($curl, array(
			CURLOPT_URL => app_url() . "/api2/logs",
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



		echo "<div class='row' id='row1_upcome'>";
		foreach ($convertarray as $array) {
			$College = $array->College_List;


			$cnt_up_channels = 0;
			$cnt_up = 0;
			foreach ($College as $College_List) {
				$channel_id = $College_List->channel_id;
				$cnt_up_channels++;
				if ($cnt_up <= 5) {
					echo "<div class='col-md-2 text-center'>";
					$channel_name = $College_List->channel_name;
					$channel_image = $College_List->channel_image;
					$channel_url = $College_List->channel_url;
					$channel_description = $College_List->channel_description;
					/**current track api**/
					$api_key = "cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s";
					$curl = curl_init();
					$request1 = array('operation' => 'current_track', 'channel_url' => $channel_url, 'api_key' => $api_key);
					$operationInput = json_encode($request1);
					curl_setopt_array($curl, array(
						CURLOPT_URL => app_url() . "/api2/logs",
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
					foreach ($convertarray as $array) {
						if (array_key_exists('Track_time', $array))
							$Track_time = $array->Track_time;
						if (array_key_exists('Track_Name', $array)) {
							$Track_Name = $array->Track_Name;
						}
						if (array_key_exists('Artist_img', $array))
							$Artist_img = $array->Artist_img;
						if (array_key_exists('Album_img', $array))
							$Album_img = $array->Album_img;
					}
					/*******/
					$url_like = app_url() . "/api3/like_recode";
					$myvars_like = array(
						'operation' => 'like_recode',
						'channel_id' => $channel_id,
						'track_id' => '',
						'user_id' => $user_id,
						'like_flag' => '0',
						'api_key' => 'cda11aoip2Ry07CGWmjEqYvPguMZTkBel1V8c3XKIxwA6zQt5s'
					);


					// print_r($myvars_like);
					$ch = curl_init($url_like);
					$data_string = json_encode($myvars_like);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

					$res_like = json_decode(curl_exec($ch), true);
					$responce_like = $res_like["Result"]["User_Liked_C"];
					$total_like = $res_like["Result"]["Total_Likes_C"];
					// print_r($responce_like); 
					if ($responce_like == "false") {
						$like_btn = "<button type='button' class='fa fa-thumbs-up nwork with-color' name='linkeBtn' id='linkeBtn$channel_id' onclick='likeFunction($channel_id)'></button>&nbsp;<span id ='like_cnt$channel_id' style='color:red;font-weight:bold'>$total_like</span>";
					} else {
						$like_btn = "<button type='button'  class='fa fa-thumbs-up work with-color' name='linkeBtn' id='linkeBtn$channel_id' onclick='likeFunction($channel_id)'></button> &nbsp;<span id ='like_cnt$channel_id' style='color:red;font-weight:bold'>$total_like</span>";
					}
					if ($user_id == '') {
						echo "<a href='login.php'>";
					} else {
						echo "<a href='channel_track.php?id=$channel_id'>";
					}
					echo "<div style='padding-top:5%;'><img src='$channel_image' style='width:60%;height:40%;' alt='Cinque Terre' ></div>";

					echo "<div style='width:100%;padding-top:5%;background-color:none;'><div style='white-space: nowrap;width:200px;overflow:hidden;
  text-overflow: ellipsis; '><center><font size=2% color=black >$channel_name";
					if (array_key_exists('Track_Name', $array))
						echo "&nbsp;&nbsp;<span class='dot'></span>";
					else
						echo "&nbsp;&nbsp;<span class='dot1'></span>";
					echo "</font></div></center></div></a>";
		?>
					<a href="display_team_members.php?channel_id=<?php echo $channel_id; ?>">View Team </a><br> <?php
																												if ($user_id != '') {
																													echo $like_btn;
																												}
																												$cnt_up++;
																												echo "</div>";
																											}
																										}
																									}
																									if ($cnt_up_channels > 6) {
																										echo "<script>
		document.getElementById(\"view_span4\").innerHTML='<a href=viewcontent_upcome.php ; id=up1 ><button style=\" background-color:rgba(0,0,0,0.1);float:right; \"><h5>view more</h5></button></a>';;
		</script>";
																									}
																									echo "</div>";
																												?>



		<div>
			<div class="fixed-footer">
				<div class="container" align="center">&copy;BPSI</div>
			</div>
		</div>


		<script type="text/javascript">
			(function(w, d, s, u) {
				w.id = 7;
				w.lang = '';
				w.cName = '';
				w.cEmail = '';
				w.cMessage = '';
				w.lcjUrl = u;
				var h = d.getElementsByTagName(s)[0],
					j = d.createElement(s);
				j.async = true;
				j.src = 'https://helpdesk.smartcookie.in/js/jaklcpchat.js';
				h.parentNode.insertBefore(j, h);
			})(window, document, 'script', 'https://helpdesk.smartcookie.in/');
		</script>
		<div id="jaklcp-chat-container"></div>
</body>

</html>