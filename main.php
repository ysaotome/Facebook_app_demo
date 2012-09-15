<?php

define('DB_NAME', getenv('C4SA_MYSQL_DB'));
define('DB_USER', getenv('C4SA_MYSQL_USER'));
define('DB_PASSWORD', getenv('C4SA_MYSQL_PASSWORD'));
define('DB_HOST', getenv('C4SA_MYSQL_HOST'));

	mb_language("uni");
	mb_internal_encoding("utf-8");
	mb_http_input("auto");
	mb_http_output("utf-8");
//facebookオブジェクト作成
require_once("php_sdk/facebook.php");

$fbid = !is_null($_GET['fbid']) ? $_GET['fbid'] : NULL;
$config = array();
$config['appId'] = 'YOUR APP ID';
$config['secret'] = 'YOUR APP SECRET';
$config['fileUpload'] = false; // optional

//DBへの接続
$dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8;';
try{
	$db = new PDO($dsn, DB_USER, DB_PASSWORD);
}catch (PDOExeption $e){
	print('Error:'.$e->getMessage());
	die();
}


$facebook = new Facebook($config);
$uid = $facebook->getUser();
?>
<html>
	<head>
      <title>HTTP ACCESS TO FACEBOOK FRIENDS</title>
		<link rel="stylesheet" type="text/css" href="./css/stylesheet.css" />
	</head>
	<body>
      <h1>HTTP ACCESS TO FACEBOOK FRIENDS</h1>
		<p>
<?php
	if (!is_null($fbid)){	//友達にHTTPアクセスしたときの処理
      //echo $fbid.'<br>';
		echo 'You Access to <img src="https://graph.facebook.com/'.$fbid.'/picture" alt="'.$fbid.' Profile Photo">';

		$user = $facebook->api("/me", 'GET');
		$friend = $facebook->api("/${fbid}", 'GET');
		$installed = $facebook->api("/${fbid}?fields=installed", 'GET');
		if ($friend['relationship_status'] == 'In a relationship' && $user['relationship_status'] == 'Single'){
			//echo '404error<br>';
			$sql = 'SELECT * FROM err_msg WHERE cord = 404';
			$exec = $db->query($sql);
			$result = $exec->fetch(PDO::FETCH_ASSOC);
			echo $result['cord'].':'.$result['status'];
		}
		else if ($friend['relationship_status'] == 'Married' && $user['relationship_status'] == 'Single'){
			//echo '401error<br>';
			$sql = 'SELECT * FROM err_msg WHERE cord = 401';
			$exec = $db->query($sql);
			$result = $exec->fetch(PDO::FETCH_ASSOC);
			echo $result['cord'].':'.$result['status'];
		}
		else if ($installed['installed'] == 'true'){
			//echo '200<br>';
			$sql = 'SELECT * FROM err_msg WHERE cord = 200';
			$exec = $db->query($sql);
			$result = $exec->fetch(PDO::FETCH_ASSOC);
			echo $result['cord'].':'.$result['status'];
		}
		else {
			//echo '403error<br>';
			$sql = 'SELECT * FROM err_msg WHERE cord = 403';
			$exec = $db->query($sql);
			$result = $exec->fetch(PDO::FETCH_ASSOC);
			echo $result['cord'].':'.$result['status'];
		}

	}
	else {	//アプリの初期画面
		$param = array(
			'scope' => 'user_about_me,friends_about_me,user_relationships,friends_relationships,friends_birthday,publish_stream',
			'redirect_uri' => 'http://cent8ev-anf-app000.c4sa.net/main.php'
		);
		if($uid){
			try {
				//友達一覧取得
				$user_friends = $facebook->api('/me/friends', 'GET');
				//友達一覧表示処理
				//var_dump($user_friends);
	            //$num = 1;
				foreach($user_friends[data] as $data){
					//echo '<p>'.$num.'My friend\'s ID = '.$data['id'].'  NAME = '.$data['name'].'</p>';
					//$num++;
					echo '<div class="friends"><img src="https://graph.facebook.com/'.$data['id'].'/picture" alt="'.$data['name'].' Profile Photo"><br><a href="http://cent8ev-anf-app000.c4sa.net/main.php?fbid='.$data['id'].'">'.$data['name'].'</a></div>';
				}

			} catch(FacebookApiException $e){
	       		$login_url = $facebook->getLoginUrl($param); 
	        	echo 'Please exeption <a href="' . $login_url . '">login.</a>';
		        error_log($e->getType());
	        	error_log($e->getMessage());
	      		}   
	    	} else {
	      	$login_url = $facebook->getLoginUrl($param);
	      	echo 'Please <a href="' . $login_url . '">login.</a>';

	    }
	}
?>
         </p>
	</body>
</html>