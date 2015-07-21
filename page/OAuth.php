<!DOCTYPE html>
<http>
<head>
	<title>Sardine OAuth</title>
		<?php

			require_once "twitteroauth/autoload.php";
			use Abraham\TwitterOAuth\TwitterOAuth;
			require_once "rs";

			$callback = callback;

			session_start();

			$twitter_oauth = new TwitterOAuth(
					consumer_key,
					consumer_secret
				);

			switch ($_GET['mode']) {
				case 'host':
					$_SESSION["cbpage"] = "accounts.php";
					break;
				case 'client':
					$_SESSION["cbpage"] = "index.html";
					break;
				default:
					# code...
					break;
			}

			$option = array('oauth_callback' => $callback);

			$request_token = $twitter_oauth->oauth('oauth/request_token', $option);
			$_SESSION['oauth_token'] = $request_token["oauth_token"];
			$_SESSION['oauth_token_secret'] = $request_token["oauth_token_secret"];

			$url = $twitter_oauth->url('oauth/authenticate', array('oauth_token' => $request_token["oauth_token"]));
			if($_GET["force_login"] == "true")
			{
				$url .= "&force_login=true";
			}

			header('location: '.$url);
			return;
		?>
</head>
</http>