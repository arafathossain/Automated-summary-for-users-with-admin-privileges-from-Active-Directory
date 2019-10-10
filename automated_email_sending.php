<?php
/*********************************************************************
Title: PHP Script to send emails with Users deatils who have currently admin privileges in Active Directory. 
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

	$table_name = "<MySQL table name>";
// MySQL query to get desired output.
	$sql = "SELECT * FROM '$table_name' WHERE groupname LIKE '%admin%' ORDER BY fullname";
	if (!($result = $mysqli->query($sql))) {
		echo "\nQuery execute failed: ERRNO: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	$sql2 = "SELECT DISTINCT fullname, username, company FROM '$table_name' WHERE groupname LIKE '%admin%' ORDER BY company";
	if (!($result2 = $mysqli->query($sql2))) {
		echo "\nQuery execute failed: ERRNO: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	
	$date = date("d/m/y", time());	
	
//=================================================== E-mailing ===============================================================================================================================					
// Defining E-mail Subject
	$Subject = "Active Directory Users with Admin Privileges - ".$date;

// Defining E-mail body:			
	$EmailBody = '<html>';
	$EmailBody .= '<body>';
	$EmailBody .= 'Automated Summary for Admin Privileges in Active Directory.'."<br/><br/>".'Below users are currently holding the admin privileges:'."<br/><br/>";			
	$EmailBody .= '<table cellpadding="5">';
			

	$EmailBody .= "<table><tr><td valign='top'>";							
	$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td>Full Name</td><td>Username</td><td>Company</td></tr>";
		while($rowsql2 = $result2->fetch_assoc()){
			$EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td>".$rowsql2['fullname']."</td><td>".$rowsql2['username']."</td><td>".$rowsql2['company']."</td></tr>";	
		}
		; 
	
	$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td>Full Name</td><td>Username</td><td>AD Groups</td><tr>";			
		while($rowsql = $result->fetch_assoc()){
			$EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td>".$rowsql['fullname']."</td><td>".$rowsql['username']."</td><td>".$rowsql['groupname']."</td></tr>";	
		}
		; 					
	$EmailBody .= "</table>";		
	$EmailBody .= "</td></tr></table>";
	$EmailBody .= "<br/><br/>"."Best Regards,"."<br/>"."NOC SYDNEY OSS";			
	$EmailBody .= "</body></html>";
//==================================================================================================================================================================================		
	include "<path-to=> class.phpmailer.php >";	
	// Example: include "/home/emdarho/public_html/enocsyd/Emailing/mail2/mail/class.phpmailer.php";	
	//include("./mail/class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->SetLanguage("en", "/home/emdarho/public_html/enocsyd/Emailing/mail2/mail/"); 
	// $mail->SetLanguage("en", 'includes/phpMailer/language/');
	$mail->IsSMTP();   // set mailer to use SMTP
	$mail->Host = "<IP address of Mail Server>";  // specify main and backup server
	$mail->SMTPAuth = false; // turn on SMTP authentication
	$mail->Username = "";   // SMTP username
	$mail->Password = "";  // SMTP password


	$mail->From = "<senders address>";
	$mail->FromName = "<Display Name Here>";

	$email_to="<Recipients Address>";	
	//$email_to1="";	
	//$email_to2="";	
	//$email_to3="";	
	
	$mail->AddAddress( $email_to, "");
	//$mail->AddAddress( $email_to1, "");
	//$mail->AddAddress( $email_to2, "");
	//$mail->AddAddress( $email_to3, "");
	//if ( $email_to != '') { $mail->AddAddress( $email_to, "");}

	$mail->WordWrap = 50;  // set word wrap to 50 characters
	
	// $mail->AddAttachment($path);    // optional name
	
	$mail->IsHTML(true);   // set email format to HTML
	$mail->Subject = $Subject;
	$mail->Body    = $EmailBody."<br>";
	//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
	if(!$mail->Send())
	{
	   echo "E-mail could not be sent. <p>";
	   echo "Mailer Error: " . $mail->ErrorInfo;
	   exit;
	}

	else
	{
		echo "<h1>Please fill up the EmailBody field !!!</h1>";
	}
//==================================================================================================================================================================================		
//E-mailing ends here....	

//session timeout command test end code	

?>
