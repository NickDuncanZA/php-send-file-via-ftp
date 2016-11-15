<?php
/**
 * Send file via FTP PHP Class
 *
 * @version  1.0
 * @author   Nick Duncan <nick@codecabin.co.za>
 *
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */

class SendviaFTP {

	private $host;
	private $port;
	private $user;
	private $pass;
	private $dir;
	private $passive_mode;
	private $transfer_mode;

	private $conn;

	/**
	 * Set the defaults
	 */
	public function __construct() {
		$this->passive_mode = false;
		$this->transfer_mode = FTP_ASCII;
		$this->conn = false;
		$this->dir = false;
	}

	/**
	 * Connect to the FTP server
	 * @return array or boolean
	 */
	public function connect() {
		if (isset($this->host) && $this->host !== "") {
			if (isset($this->user) && $this->user !== "") {
				if (isset($this->pass) && $this->pass !== "") {
					$this->conn = @ftp_connect($this->host);
					if (!$this->conn) {
						return array('e'=>'Could not connect to '.$this->host);
					} else {
						$login_check = @ftp_login($this->conn,$this->user,$this->pass);
						if (!$login_check) {
							$this->conn = false;
							return array('e'=>'Could not log in with the set credentials');
						} else {

							// set passive mode if needed
							if ($this->passive_mode) {
								@ftp_pasv($this->conn, true);
							}

							// successful connection and log in
							return true;
						}
					}

				} else { return array('e'=>'Please set the PASSWORD'); }
			} else { return array('e'=>'Please set the USER'); }
		} else { return array('e'=>'Please set the HOST'); }
	}


	/**
	 * Send the file over FTP
	 * @param  $filelocation        The path to the file
	 * @param  $file_name_on_server The name of the file on the server
	 */
	public function send_file($filelocation = false,$file_name_on_server = false) {
		if (!$filelocation) {
			return array("e"=>"File parameter missing. Usage: $SendviaFTP->send_file('path/to/file/filename.ext').");
		}
		if (@!file_exists($filelocation)) {
			return array("e"=>"File ($filelocation) does not exist.");
		}
		if (!$file_name_on_server) {
			// Set the file name to the filename of the file we are trying to send
			$file_name_on_server = basename($filelocation);
		}
		if (!$this->conn) {
			return array("e"=>"You need to connect to the server before trying to send a file.");	
		}

		if ($this->dir) {
			// lets check it exists first
			$folder_exists = @is_dir("ftp://".$this->user.":".$this->pass."@".$this->host.$this->dir);
			if ($folder_exists) {

				ftp_chdir($this->conn, $this->dir);
			} else {
				// lets make the directory
				$parts = explode('/',$this->dir);
				foreach ($parts as $part) {
					if(!@ftp_chdir($this->conn, $part)){
				      	if (!ftp_mkdir($this->conn, $part)) {
							return array("e"=>"The directory cannot be created on the server (".$this->dir.")");		
						} 
				      ftp_chdir($this->conn, $part);
				    }
				}
				
			}
		} else {
			return array("e"=>"You need to set a working directory on the server using $SendviaFTP->set_dir('/public_html/your/path/')");		
		}


		// Upload a file
		if (ftp_put($this->conn, $this->dir.$file_name_on_server, $filelocation, $this->transfer_mode)) {
			return true;
		} else {
		 	return array("e"=>"File $".file_name_on_server." could not be uploaded.");		
		}



	}




	/**
	 * Set the host (server address)
	 * @param string $host FTP server address
	 */
	public function set_host($host) {
		$this->host = $host;
	}

	/**
	 * Set the username
	 * @param string $user
	 */
	public function set_user($user) {
		$this->user = $user;
	}

	/**
	 * Set the password
	 * @param string $pass
	 */
	public function set_pass($pass) {
		$this->pass = $pass;
	}

	/**
	 * Set the working directory
	 * @param string $dir
	 */
	public function set_dir($dir) {
		$this->dir = $dir;
	}

	/**
	 * Enable or disable passive mode
	 * @param string $bool
	 */
	public function set_passive_mode($bool) {
		if ($bool) { $this->passive_mode = true; }
	}	

	/**
	 * Set the tranfer mode.
	 *
	 * Defaults to FTP_ASCII
	 * 
	 * @param string $mode FTP_ASCII or FTP_BINARY
	 */
	public function set_transfer_mode($mode) {
		if ($mode == "FTP_ASCII") { $this->transfer_mode = FTP_ASCII; }
		else if ($mode == "FTP_BINARY") { $this->transfer_mode = FTP_BINARY; }
		else { $this->transfer_mode = FTP_ASCII; }
	}	
}