<!DOCTYPE HTML>
<html>
	<head>
    	<title>HTTP ACCESS TO FACEBOOK FRIENDS</title>
		<link rel="stylesheet" type="text/css" href="./css/stylesheet.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
    	<h1>HTTP ACCESS TO FACEBOOK FRIENDS</h1>
		<p>
			<?php
				foreach($user_friends[data] as $data){
					//echo '<p>'.$num.'My friend\'s ID = '.$data['id'].'  NAME = '.$data['name'].'</p>';
					//$num++;
					echo '<div class="friends"><img src="https://graph.facebook.com/'.$data['id'].'/picture" alt="'.$data['name'].' Profile Photo"><br><a href="http://cent8ev-anf-app000.c4sa.net/index.php/result?src_id='.$GLOBALS['uid'].'&dest_id='.$data['id'].'">'.$data['name'].'</a></div>';
				}
			?>
		</p>
	</body>
</html>