<http>
	<head>
		<?php
			require_once "twitteroauth/autoload.php";
			use Abraham\TwitterOAuth\TwitterOAuth;
			require_once "rs";

			$callback = callback;

			session_start();
			//cookieがないか無効ならば発行
			$twitter_oauth = new TwitterOAuth(
					consumer_key,
					consumer_secret
				);
			$request_token = $twitter_oauth->oauth('oauth/request_token', array('oauth_callback' => $callback));
			$_SESSION['oauth_token'] = $request_token["oauth_token"];
			$_SESSION['oauth_token_secret'] = $request_token["oauth_token_secret"];

			$url = $twitter_oauth->url('oauth/authenticate', array('oauth_token' => $request_token["oauth_token"]));
			header('location: '.$url);
			return;
		?>
	</head>
</http>