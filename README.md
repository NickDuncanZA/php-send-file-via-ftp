# php-send-file-via-ftp

##A PHP Class to send files over FTP

## Synopsis

Use this class to easily send files via FTP

## Code Example

```
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

?>
```

## Contributors

Nick Duncan

## License

This program is free software: you can redistribute it and/or modify it under the terms of the 
GNU General Public License as published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.