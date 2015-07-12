<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Hello World Page</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

  <div class="container">
  	<table class="table">
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
			require_once 'twitteroauth/src/TwitterOAuth.php';
		  	require_once 'rs';

		  	$tw_obj = new TwitterOAuth(
		  			$consumer_key,
		  			$consumer_secret,
		  			$access_token,
		  			$access_token_secret
		  		);
		  	$api_url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
			$method = 'GET';
		  	$option = array( 'count' => 20 );

		  	$tw_request = $tw_obj->OAuth_Request(
		  			$api_url,
		  			$method,
		  			$option
			);
			$timeline_json = json_decode($tw_request, true);
			foreach ($timeline_json as $key => $value) {
				print('<tr>');
				//icon
				print('	<td>');
				print('	</td>');
				//screen name
				print('	<td>');
				print('	</td>');
				//text
				print('	<td>');
				print($value["text"]);
				print('	</td>');
				//date
				print('	<td>');
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
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>