<!DOCTYPE html>
<html>
<head>
	<title>Sardine callback</title>
</head>
<body>
	<?php
		session_start();

		require_once("twitteroauth/autoload.php");
		use Abraham\TwitterOAuth\TwitterOAuth;
		require_once("rs");

		if(	isset($_GET["oauth_verifier"]) && $_GET["oauth_verifier"] != "" &&
			isset($_REQUEST["oauth_token"]) && $_REQUEST["oauth_token"] === $_SESSION["oauth_token"]
			)
		{
			$oauth_verifier = $_GET["oauth_verifier"];
			$oauth_token = $_SESSION["oauth_token"];
			$oauth_secret = $_SESSION["oauth_token_secret"];
		}
		else
		{
			print("<h1>sorry, twitter oauth error.</h1><br>");
			print("<a href='index.html>back to topmenu</a>'");
			return;
		}

		$twitter_oauth = new TwitterOAuth( 
			consumer_key, consumer_secret, $oauth_token, $oauth_secret);
		$access_token = $twitter_oauth->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));
		
		//クッキーへ書き込み
		setcookie('access_token', $access_token['oauth_token'], time() + 6200, "/Sardine/page");
		setcookie('access_token_secret', $access_token['oauth_token_secret'], time() + 6200, "/Sardine/page");
		session_regenerate_id();
		//転送
		header("location: timeline.php");
	?>
	<h2>progress...</h2>
</body>
</html>
