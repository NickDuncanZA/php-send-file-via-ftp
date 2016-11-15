<?php

include("send-file-to-ftp.class.php");

$SendviaFTP = new SendviaFTP();
$SendviaFTP->set_host("HOSTNAME");
$SendviaFTP->set_user("USERNAME");
$SendviaFTP->set_pass("PASSWORD");
$SendviaFTP->set_dir("/working/directory/on/the/server/");


// try connect to the server with the above credentials
$check = $SendviaFTP->connect();

if (!is_array($check)) {

	// send a file now that we have connected
	$check_file = $SendviaFTP->send_file("testfile.txt","testfile.txt");
	
	if (!is_array($check_file)) {
		echo "Success!";

	} else {
		echo "Error: " . $check_file['e'];
	}

} else {
	echo "Error: " . $check['e'];
}

