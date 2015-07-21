<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Sardine - Unioned Twitter Account</title>

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
    <h2>
    </h2>
    <h1>あなたが操作できるアカウントの情報</h1>
  
  <div class="container">
    <table class="table table-striped table-bordered">
      <tbody>
      <?php
          require_once 'twitteroauth/autoload.php';
          use Abraham\TwitterOAuth\TwitterOAuth;
          require_once 'config';
          require_once 'rs';
          $twitter = new TwitterOAuth(
              consumer_key, consumer_secret, $_COOKIE['access_token'], $_COOKIE['access_token_secret']);
          $user = $twitter->get("account/verify_credentials");

          $con = mysqli_connect(dburl, dbuser, dbpass, dbname);
          //認証済みユーザーに結びついているアカウントを取得する
          $sql = "SELECT hosted_id FROM client_account WHERE user_id = $user->id";
          $res_array = mysqli_query($con, $sql)->fetch_array(MYSQLI_ASSOC);

          //結びついているアカウントがなければ、認証が必要であることを説明する文章を出力する

          //結びついているアカウントがあればユーザーデータを取得
          if($res_array['hosted_id'] != -1)
          {
              $host_id = $res_array['hosted_id'];
              $sql = "SELECT * FROM host_account WHERE id = $host_id";
              $res_array = mysqli_query($con, $sql)->fetch_array(MYSQLI_ASSOC);

              if($res_array != NULL)
              {
                  $twitter = new TwitterOAuth(
                    consumer_key, consumer_secret, $res_array["access_token"], $res_array["access_token_secret"]);
                  $host = $twitter->get("account/verify_credentials");
                  print "<tr>";
                  print "<td>";
                  print('<img src='. $host->profile_image_url_https.'>');
                  print "</td>";
                  print "<td>";
                  print "<font size = 4>";
                  print $host->screen_name. "/". $host->name;
                  print "</font>";
                  print "</td>";
                  print "</tr>";
                  return;
               }
          }
          print "<h2>まだこのアカウントにはTwitterアカウントが結びついていません</h2><br>";
          print "<h2>Twitterアカウントを結びつけるには、下のボタンをクリックしてください</h2><br>";
          print "<div id='add_acc_btn'></div>";
          
      ?>
      </tbody>
      </table>
      </div>
    <script type="text/javascript">
      document.getElementById("add_acc_btn").innerHTML = "<button type='button' class='btn btn-primary btn-lg' onclick=window.location='OAuth.php?force_login=true&mode=host' >認証</button>";
    </script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>