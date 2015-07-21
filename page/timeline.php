<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Sardine TimeLine</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

  <div class="container">
  	<table class="table table-striped table-bordered">
	  	<thead>
	  		<tr>
	  			<th> </th>
	  			<th>@</th>
	  			<th>text</th>
	  			<th>date</th>
	  		</tr>
	  	</thead>
	  	<tbody>
	  	<!-- php -->
		<?php
			require_once 'twitteroauth/autoload.php';
			use Abraham\TwitterOAuth\TwitterOAuth;
		  	require_once 'rs';

		  	if(!isset($_COOKIE["access_token"]) || $_COOKIE["access_token"] == "" ||
		  	   !isset($_COOKIE["access_token_secret"]) || $_COOKIE["access_token_secret"] == "")
		  	{
		  		header("location: OAuth.php");
		  		return;
		  	}

		  	$tw_obj = new TwitterOAuth(
		  			consumer_key,
		  			consumer_secret,
		  			$_COOKIE["access_token"],
		  			$_COOKIE["access_token_secret"]
		  		);

		  	$option = array( 'count' => 20 );

		  	$tw_request = $tw_obj->get("statuses/home_timeline", $option);
			// if(in_array('errors', $timeline_json))
			// {
			// 	print($timeline_json['errors']);
			// 	return;
			// }
			foreach ($tw_request as $key => $value) {
				print('<tr>');
				//icon
				print('	<td>');
				print('<img src='. $value->user->profile_image_url_https.'>');
				print('	</td>');
				//screen name
				print('	<td>');
				print($value->user->screen_name);
				print('	</td>');
				//text
				print('	<td>');
				print($value->text);
				print('	</td>');
				//date
				print('	<td>');
				print($value->created_at);
				print('	</td>');
				print('</tr>');
			}
		?>
		</tbody>
 	 </table>
  </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>