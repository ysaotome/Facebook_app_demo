<?php
config_init();
$config = array();
$config['appId'] = '345563218861324';
$config['secret'] = '75a5d777cd13c2b60c2393ec00e5c570';
$config['fileUpload'] = false; // optional
$facebook = new Facebook($config);
$db;

$uid = $facebook->getUser();
//コントローラ式に変更（あとで）
//$src_id = !is_null($_GET['src_id']) ? $_GET['src_id'] : NULL;
//$dest_id = !is_null($_GET['dest_id']) ? $_GET['dest_id'] : NULL;

function config_init(){
	mb_language("uni");
	mb_internal_encoding("utf-8");
	mb_http_input("auto");
	mb_http_output("utf-8");
	require_once("php_sdk/facebook.php");
}

//DBへの接続
function connect_database(){
	define('DB_NAME', getenv('C4SA_MYSQL_DB'));
	define('DB_USER', getenv('C4SA_MYSQL_USER'));
	define('DB_PASSWORD', getenv('C4SA_MYSQL_PASSWORD'));
	define('DB_HOST', getenv('C4SA_MYSQL_HOST'));

	$dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8;';
	try{
		$db = new PDO($dsn, DB_USER, DB_PASSWORD);
	}catch (PDOExeption $e){
		print('Error:'.$e->getMessage());
		die();
	}
}

function get_fb_user_info($fbid){
	return $facebook->api("/${fbid}", 'GET');
}

function http_to_friends($src_id, $dest_id){
	if (!is_null($dest_id) && !is_null($src_id)){
		connect_database();
		$src = get_fb_user_info($src_id);
		$dest = get_fb_user_info($dest_id);
		$installed = $facebook->api("/${dest_id}?fields=installed", 'GET');
		if ($dest['relationship_status'] == 'In a relationship' && $src['relationship_status'] == 'Single'){
			select_err_msg(404);
		}
		else if ($dest['relationship_status'] == 'Married' && $src['relationship_status'] == 'Single'){
			//echo '401error<br>';
			select_err_msg(401);
		}
		else if ($installed['installed'] == 'true'){
			//echo '200<br>';
			select_err_msg(200);
		}
		else if ($src_id == $dest_id){
			select_err_msg(500);
		}
		else if ($dest['first_name'] == $src['first_name']){
			select_err_msg(501);
		}
		else {
			//echo '403error<br>';
			select_err_msg(403);
		}
	}
	else {
		echo '接続先の友人が指定されていません';
	}
}

function select_err_msg($cord){
	$sql = 'SELECT * FROM err_msg WHERE cord = '.$cord;
	$exec = $db->query($sql);
	$result = $exec->fetch(PDO::FETCH_ASSOC);
	require 'templates/result.php';
	//
}

function list_friends(){
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
            require 'templates/list.php';

		} catch(FacebookApiException $e){
       		login_to_fb($param);
	        error_log($e->getType());
        	error_log($e->getMessage());
   		}   
   	} else {
      	login_to_fb($param);
    }
}

function login_to_fb($param){
	$login_url = $facebook->getLoginUrl($param);
	//login.phpも作成(あとで)
	echo 'Please <a href="' . $login_url . '">login.</a>';
}