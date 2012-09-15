$src, $dest<!DOCTYPE HTML>
<html>
	<head>
    	<title>HTTP ACCESS TO FACEBOOK FRIENDS</title>
		<link rel="stylesheet" type="text/css" href="./css/stylesheet.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
    	<h1>HTTP ACCESS TO FACEBOOK FRIENDS</h1>
		<p>
			You Access to <img src="https://graph.facebook.com/<?php echo $dest['id'] ?>/picture" alt="<?php echo $dest['id'] ?> Profile Photo"><br>
			<?php echo $result['cord'].':'.$result['status'] ?>
		</p>
	</body>
</html>