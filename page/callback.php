<html>
<head>
	<title>Sardine callback</title>
	<?php
		session_start();

		require_once "twitteroauth/autoload.php";
		use Abraham\TwitterOAuth\TwitterOAuth;
		require_once "rs";
		require_once "config";

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
			print("<a href='index.html'>back to topmenu</a>");
			return;
		}

		$twitter_oauth = new TwitterOAuth( 
			consumer_key, consumer_secret, $oauth_token, $oauth_secret);
		$access_token = $twitter_oauth->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));

		//アカウント情報の取得
		$twitter_oauth = new TwitterOAuth(
			consumer_key, consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		$user = $twitter_oauth->get("account/verify_credentials");

		//データベースへ接続する
		$con = mysqli_connect(dburl, dbuser, dbpass, dbname);
		
		//callback先がホストアカウント設定画面ならここで結びつけを行う
		if($_SESSION["cbpage"] == "accounts.php")
		{
			//クッキーからデータを取るためにデータが存在しているか確認する
			if($_COOKIE["id"] == "")
			{
				//していなかったらトップページへジャンプ
				print "<META http-equiv='refresh' content=\"5; url='index.html'\">";
				print "<h2>Cookieの有効期限が切れています。もう一度ログインしてください。</h2>";
				return;
			}
			$client_id = $_COOKIE["id"];

			//クライアントユーザーの登録情報を取得
			$sql = "SELECT hosted_id FROM client_account WHERE user_id = $client_id";
			$res = mysqli_query($con, $sql);

			//ここでの$idは、「ホストアカウントデータベース」に登録されているID
			$id = $res->fetch_array(MYSQLI_ASSOC)['hosted_id'];

			//認証中のユーザーのアカウントにホストアカウントが結びついていなければデータベースへ登録
			if($id == -1)
			{
				$atoken = $access_token['oauth_token'];
				$atokens = $access_token['oauth_token_secret'];

				$sql = "INSERT INTO host_account (access_token, access_token_secret) VALUES ( '".$atoken."', '".$atokens."' )";
				$res = mysqli_query($con, $sql);

				$sql = "SELECT last_insert_id() FROM host_account";
				$res = mysqli_query($con, $sql);

				$res_array = $res->fetch_array(MYSQLI_ASSOC);

				$host_id = $res_array['last_insert_id()'];

				//クライアント側データベースの情報を更新する
				$sql = "UPDATE client_account SET hosted_id = $host_id WHERE user_id = $client_id";
				$res = mysqli_query($con, $sql);
			}
			else
			{
				//登録されているIDのデータを取ってくる
				$sql = "SELECT id FROM host_account WHERE id = $id";
				$res_array = mysqli_query($con, $sql)->fetch_array(MYSQLI_ASSOC);

				//取ってきたデータのアクセストークンが、新しいアクセストークンと一致しているか調べる
				if(	$res_array["access_token"] !== $access_token["oauth_token"])
				{
					//一致しない場合は古いものに削除フラグを立てる
					$sql = "UPDATE host_account SET removed = true WHERE id = $id";
					mysqli_query($con, $sql);

					$atoken = $access_token['oauth_token'];
					$atokens = $access_token['oauth_token_secret'];

					//データベースへ登録
					$sql = "INSERT INTO host_account (access_token, access_token_secret) VALUES ( '".$atoken."', '".$atokens."' )";
					$res_array = mysqli_query($con, $sql)->fetch_array(MYSQLI_ASSOC);

					$host_id = $res_array['id'];
					//クライアント側データベースの情報を更新する
					$sql = "UPDATE client_account SET hosted_id = $host_id WHERE user_id = $client_id";
					$res = mysqli_query($con, $sql);
				}
				//アクセストークンが一致すれば何もしない
			}
		}
		//callback先がこのサービスへのログインのための認証なら、データベースへのユーザー登録を行う
		else
		{
			//今認証したユーザーが登録済みかどうかを調べる
			$sql = "SELECT user_id FROM client_account WHERE user_id = $user->id";
			$res = mysqli_query($con, $sql);
			//もし新しいユーザーならIDを保存する
			if($res->fetch_array(MYSQLI_ASSOC) == null)
			{
				$res = mysqli_query($con, "use sardinedb");
				$sql = "INSERT INTO client_account (user_id) VALUES ( $user->id )";
				$res = mysqli_query($con, $sql);
			}

			//クッキーへ書き込み
			setcookie('user', $user->name , time() + 6200, "/Sardine/page");
			setcookie('id', $user->id, time() + 6200, "/Sardine/page");
			setcookie('access_token', $access_token['oauth_token'], time() + 6200, "/Sardine/page");
			setcookie('access_token_secret', $access_token['oauth_token_secret'], time() + 6200, "/Sardine/page");
		}
		session_regenerate_id();

		//転送
		header("location: ". $_SESSION["cbpage"]);
		//print "<a href=". $_SESSION["cbpage"]. "> back </a>";
	?>
	</head>
<body>
	<h2>progress...</h2>
</body>
</html>
