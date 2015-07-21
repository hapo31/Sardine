<?php
	require_once 'config';

		$testId = 1154;
		$dbname = dbname;


		$con = mysqli_connect(dburl, dbuser, dbpass, $dbname);
		$sql = "SELECT * FROM client_account WHERE user_id = $testId";
		$res = mysqli_query($con, $sql);

		if($res->fetch_array(MYSQLI_ASSOC) == null)
		{
			$res = mysqli_query($con, "use sardinedb");
			$sql = "INSERT INTO client_account (user_id) VALUES ( $testId )";
			$res = mysqli_query($con, $sql);
			if($res === false)
				print("error");
		}
		$sql = "SELECT * FROM client_account WHERE user_id = $testId";
		$res = mysqli_query($con, $sql);
?>