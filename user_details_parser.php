<?php
//*********************************************************************
//Title: CREATE MySQL TABLE FROM .CSV FILE & IMPORT DATA FROM .CSV FILE.
//Author: Arafat Hossain
//Version: 1.0
//Date: 11 Apr 2017
//**********************************************************************/

// Connecting to database using OOP approach
$mysqli  =  new mysqli('localhost','<username>','<db_password>','nocportal');

// check connection 
if (mysqli_connect_errno()) {
    printf("Connection Failed: %s\n", mysqli_connect_error());
    exit();
}

	// Define table name and Data File location.
	$table_name = 'nsas_ad_user_details';
	$file_location = '/home/<username>/public_html/enocsyd/raw_files/ADUserAudit/AllUserList.csv';
	// *** N.B: To use 'LOAD DATA INFILE' files must go to 'C:/wamp/tmp' directory due to security reason. Otherwise need to specifiy file location and set that in .ini file.

	$lines = file($file_location);
	$lines = array_map('trim', $lines);


	$col_header = explode(",", $lines[0]);
	$col_header = str_replace('"', '', $col_header);

	var_dump($col_header);

        // Truncating table before inserting
        $sql_truncate_tbl = "TRUNCATE TABLE nsas_ad_user_details";
        if (!($stmt = $mysqli->query($sql_truncate_tbl))) {
            echo "\nQuery execute failed: ERRNO: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        else{
            echo "nsas_ad_user_details Truncated Successfully. \n";
        }	
	
	// Dropping Table
	$sql_drop_tbl = "DROP TABLE nsas_ad_user_details";
        if (!($stmt = $mysqli->query($sql_drop_tbl))) {
            echo "\nQuery execute failed: ERRNO: (" . $mysqli->errno . ") " . $mysqli->error;
        }

	// Table creation as per the first line/header names.
	$create_query = "CREATE TABLE IF NOT EXISTS {$table_name} (\n";
	foreach ($col_header as $col_num => $col_name){
	    $create_query .= " `{$col_name}` VARCHAR(200) ,\n";
	    // echo $create_query;
	}

	$sql_tbl_creation = substr($create_query, 0, -2).");";
	echo $sql_tbl_creation;

	if (!($stmt = $mysqli->query($sql_tbl_creation))) {
	    echo "\nQuery execute failed: ERRNO: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	// Inserting data from CSV file to MySQL table.
	$sql_data_insert = "LOAD DATA LOCAL INFILE '$file_location' INTO TABLE nsas_ad_user_details FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES";
	echo $sql_data_insert ."\n";

	if (!($stmt = $mysqli->query($sql_data_insert))) {
	    echo "\nQuery execute failed: ERRNO: (" . $mysqli->errno . ") " . $mysqli->error;
	}
        else{
	    // echo "Data Inserted Successfully !!!";
	    $sql_update_status = "UPDATE update_status SET last_update=NOW() WHERE table_name='nsas_ad_user_details'";
            if (!($stmt = $mysqli->query($sql_update_status))) {
		echo "Time update failed";
	    }	
	}
?>
