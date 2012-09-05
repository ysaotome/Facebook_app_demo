<!DOCTYPE HTML>
<html>
	<head>
    	<title>HTTP ACCESS TO FACEBOOK FRIENDS</title>
		<link rel="stylesheet" type="text/css" href="./css/stylesheet.css" />
	</head>
	<body>
    	<h1>HTTP ACCESS TO FACEBOOK FRIENDS</h1>
		<p>
			You Access to <img src="https://graph.facebook.com/<?php echo $dest_id ?>/picture" alt="<?php echo $dest_id ?> Profile Photo"><br>
			<? echo echo $result['cord'].':'.$result['status'] ?>
		</p>
	</body>
</html>