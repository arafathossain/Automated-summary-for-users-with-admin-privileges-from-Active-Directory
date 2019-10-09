<?php
/*********************************************************************
Title: Parsing AD Group members into the MySQL table. 
There are group files which contain all the members belong to that particular AD Group.
Author: Arafat Hossain
Version: 1.0
Date: 11 Apr 2017
**********************************************************************/

// Connecting to database using OOP approach
	$mysqli  =  new mysqli('<db_host_name or IP>','<db_user>','<db_password>','<db_name>');

// Check database connection 
	if (mysqli_connect_errno()) {
		printf("Connection Failed: %s\n", mysqli_connect_error());
		exit();
	}

	$table_name = "<table_name>";
	$input_dir = "<path-to-directory>";
	// Example: $input_dir = "/home/<username>/public_html/enocsyd/raw_files/ADUserAudit/Users";
	foreach(glob($input_dir.'/*.*') as $file) {
		// echo $file."<br/>";
		$input_users = $file;
		//echo $input_users."\n";

		// Skip first 6 & last 4 characters... to avoid the directory_location and .csv from the group name.
		$group_name = substr($input_users, 62, -4);

		//echo "File Name: " .$input_users. "<br/>". "<br/>". "<br/>";
		//echo $group_name."\n";

		$lines = file("$input_users");
		print_r($lines);
		foreach ($lines as $line_num => $line) {
			if($line_num > 0) {
				$arr = explode(",", $line);

				$user[0] = str_replace('"', '', $arr[0]);
				$user[1] = str_replace('"', '', $arr[1]);
				$user[2] = substr($arr[3], 3);
				$user[3] = $group_name;

				$sql = sprintf("INSERT INTO '$table_name' (fullname, username, company, groupname) VALUES ('$user[0]', '$user[1]', '$user[2]', '$user[3]')");
				echo $sql;
				//$result = mysqli_query($con, $sql);

				if (!($sql = $mysqli->query($sql))) {
					echo "\nQuery execute failed: ERRNO: (" . $mysqli->errno . ") " . $mysqli->error;
				}
			//echo "Added user data for: ". $user[0]. "<br/>";
			}
		}
    }

// TO KEEP TRACK OF SCRIPT SUCCESS/FAILURE STATUS.
// mysqli_query($con, "UPDATE update_status SET last_update=NOW() WHERE table_name='$table_name'");

//mysqli_close($con);
?>

