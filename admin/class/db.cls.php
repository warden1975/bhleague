<?php
/*
Database connection module / fail-safe reconnection system.
GoLive! Mobile LLC

Default host and dbms.
*/
error_reporting(E_ALL);

require_once('/home/bhleague/public_html/admin/config/db.cnf.php');
require_once('/home/bhleague/public_html/admin/config/phpredis.cnf.php');

class DB_Connect {
 	
	protected $dbo, $dbw, $dbr, $dba;
	private $dberror, $dbwerror, $dbrerror, $dbaerror;
	private $dbr_arr;
	private $drbd_connected, $user, $drbd_log_user_w, $drbd_log_user_r;
	private $db_command;
	public $insert_id;
	public $log_type;
	public $error;	
	public $affected_rows = 0;
	
	function __construct($user, $connect_drbd = false) {
		
		$this->drbd_connected = $connect_drbd;
		$this->user = $user;

		$cgs_user = constant('MYSQL_' . strtoupper($user));
		$drbd_user_w = constant('MYSQL_' . strtoupper($user) . '_W');
		$drbd_user_r = constant('MYSQL_' . strtoupper($user) . '_R');
		
		$this->drbd_log_user_w = $drbd_user_w;
		$this->drbd_log_user_r = $drbd_user_r;

		if ($this->drbd_connected) {
			$this->connectdbw(MYSQL_DRBD_WRITE_HOSTNAME, $drbd_user_w, MYSQL_DRBD_WRITE_PASSWORD, MYSQL_DRBD_WRITE_DBMS);
			
			$this->dbr_arr = array(MYSQL_DRBD_READ1_HOSTNAME, MYSQL_DRBD_READ2_HOSTNAME, MYSQL_DRBD_READ3_HOSTNAME);			
			$dbr_host = $this->dbr_arr[mt_rand(0, 2)];
			
			$this->connectdbr($dbr_host, $drbd_user_r, MYSQL_DRBD_READ_PASSWORD, MYSQL_DRBD_READ_DBMS);
			
			$this->connectdba($dbr_host, MYSQL_DRBD_A, MYSQL_DRBD_A_PASS, MYSQL_DRBD_READ_DBMS);
		} else $this->connectdbo(MYSQL_HOSTNAME, $cgs_user, MYSQL_ROOT_PASSWORD, MYSQL_DBMS);
		
	}
	
	function reconnect($user, $connect_drbd = false) {
		
		$this->drbd_connected = $connect_drbd;
		$this->user = $user;
		
		$cgs_user = constant('MYSQL_' . strtoupper($user));
		$drbd_user_w = constant('MYSQL_' . strtoupper($user) . '_W');
		$drbd_user_r = constant('MYSQL_' . strtoupper($user) . '_R');
		
		if ($this->drbd_connected) {
			$this->connectdbw(MYSQL_DRBD_WRITE_HOSTNAME, $drbd_user_w, MYSQL_DRBD_WRITE_PASSWORD, MYSQL_DRBD_WRITE_DBMS);
			
			$this->dbr_arr = array(MYSQL_DRBD_READ1_HOSTNAME, MYSQL_DRBD_READ2_HOSTNAME, MYSQL_DRBD_READ3_HOSTNAME);
			$dbr_host = $this->dbr_arr[mt_rand(0, 2)];
			
			$this->connectdbr($dbr_host, $drbd_user_r, MYSQL_DRBD_READ_PASSWORD, MYSQL_DRBD_READ_DBMS);
			
			$this->connectdba($dbr_host, MYSQL_DRBD_A, MYSQL_DRBD_A_PASS, MYSQL_DRBD_READ_DBMS);
		} else $this->connectdbo(MYSQL_HOSTNAME, $cgs_user, MYSQL_ROOT_PASSWORD, MYSQL_DBMS);
		
	}
	
	private function connectdbo($_host, $_user, $_password, $_dbms) {
		
		$this->dbo = new mysqli($_host, $_user, $_password, $_dbms);
		
		if ($this->dbo->connect_error) {
			$this->dberror .= (sprintf('MySQL O Connect Error (%s) %s \n', $this->dbo->connect_errno, $this->dbo->connect_error));
		}
		
	}
	
	private function connectdba($_host, $_user, $_password, $_dbms) {
		
		$this->dba = new mysqli($_host, $_user, $_password, $_dbms);
		
		if ($this->dba->connect_error) {
			$this->dbaerror .= (sprintf('MySQL W Connect Error (%s) %s \n', $this->dba->connect_errno, $this->dba->connect_error));
		}
		
	}
	
	private function connectdbw($_host, $_user, $_password, $_dbms) {
		
		$this->dbw = new mysqli($_host, $_user, $_password, $_dbms);
		
		if ($this->dbw->connect_error) {
			$this->dbwerror .= (sprintf('MySQL W Connect Error (%s) %s \n', $this->dbw->connect_errno, $this->dbw->connect_error));
		}
		
	}
	
	private function connectdbr($_host, $_user, $_password, $_dbms) {
		
		$this->dbr = new mysqli($_host, $_user, $_password, $_dbms);
		
		if ($this->dbr->connect_error) {
			$this->dbrerror .= (sprintf('MySQL R Connect Error (%s) %s \n', $this->dbr->connect_errno, $this->dbr->connect_error));
		}
		
	}
	
	public function query($_sql) {
	
		$sql = explode(' ', trim($_sql));
		$cmd = $this->db_command = strtoupper(trim($sql[0]));
		
		if ($this->drbd_connected) {
			
			if (!$this->dbr->ping()) $this->reconnect($this->user, $this->drbd_connected);
			if (!$this->dbw->ping()) $this->reconnect($this->user, $this->drbd_connected);
			
			switch (strtoupper($cmd)) {
				case 'SET':
				case 'SELECT': 
					if ($this->check_slave()) {
						$rs = $this->dbr->query($_sql);
						$this->error = $this->dbr->error;
					} else {
						$rs = $this->dbw->query($_sql); 
						$this->error = $this->dbw->error;
					}
					break;
				case 'INSERT': 
					$rs = $this->dbw->query($_sql);
					$this->insert_id = $this->dbw->insert_id;
					$this->error = $this->dbw->error;
					$this->affected_rows = $this->dbw->affected_rows;
					break;
				case 'UPDATE': 
				case 'DELETE': 
				case 'CREATE':
					$rs = $this->dbw->query($_sql); 
					$this->error = $this->dbw->error;
					break;
			}			
		} else {
			if (!$this->dbo->ping()) $this->reconnect($this->user, $this->drbd_connected);
			
			$rs = $this->dbo->query($_sql);
			$this->insert_id = $this->dbo->insert_id;
			$this->error = $this->dbo->error;
		}
		
		return $rs;
		
	}
	
	function check_slave() {
	
		//return false;
				
		if (!$this->dba->ping()) $this->reconnect($this->user, $this->drbd_connected);
		
		$io_running = false;
		$sql_running = false;
		$secs_behind_master = false;
		
		$sql = "SHOW SLAVE STATUS";
		
		if ($rs = $this->dba->query($sql)) {
			$row = $rs->fetch_object();
			
			$io_running = ($row->Slave_IO_Running == 'Yes') ? true : false;
			$sql_running = ($row->Slave_SQL_Running == 'Yes') ? true : false;
			$secs_behind_master = ($row->Seconds_Behind_Master > 3) ? false : true;
			
			$rs->close();
			
			if ($io_running && $sql_running && $secs_behind_master) return true;
			else return false;
		} else {			
			$this->error = $this->dba->error;
			
			return false;
		}		
		
	}
	

	public function close() {
		if ($this->drbd_connected) {
			if ($this->dbr->ping()) $this->dbr->close();
			if ($this->dbw->ping())	$this->dbw->close();
			if ($this->dba->ping()) $this->dba->close();
									
			return true;
		} else {
			if ($this->dbo->ping()) $this->dbo->close();
			return true;
		}
	}
	
	public function real_escape_string($string) {
		if ($this->drbd_connected) {
			return $this->dbw->real_escape_string($string);
		} else {
			return $this->dbo->real_escape_string($string);
		}
	}
	
	public function logfile($_message, $_type = NULL, $_raw = false) {
		
		$_mongo_message = $_message;
		
		if (true == true) {
	
			$log_type = NULL;
		
			$dbo = new mysqli(MYSQL_HOSTNAME, $this->user, MYSQL_ROOT_PASSWORD, MYSQL_DBMS);
			
	
			
			$log_type = (empty($_type) || $_type == NULL) ? $this->log_type : $_type;
	
			
			$_message = $dbo->real_escape_string("$_message");
			
			if ($_raw) $_type = sprintf("%sraw", $log_type);
			else $_type = $log_type;
			
			$sql = "INSERT INTO `logs`.`$log_type` SET `log` = '$_message', `type` = '$_type', `datetime` = NOW()";
			$rs = $dbo->query($sql) or print("Error[".$dbo->error.", user->".$this->user."]: $sql\n");
			
			$dbo->close();
			
		}
		else {
			try {
				
				$conn = new Mongo('67.222.101.62');	
				$db_mongo = $conn->selectDB("logs"); 

				$log_type = (empty($_type) || $_type == NULL) ? $this->log_type : $_type; 
				
				if ($_raw) { $_type = sprintf("%sraw", $log_type);}
				else {$_type = $log_type;}
				
				if ($log_type == "") {
					$log_type = $this->user;
				}
				
				
				$collection = $db_mongo->selectCollection($log_type); 
				$item = array("log" => "$_mongo_message", "datetime" => date("Y-j-n G:i:s")); 

				$collection->insert($item);
				$conn->close();
			}
			catch (MongoConnectionException $e) {
			 // echo "Error connecting to MongoDB server";
			} 
			catch (MongoException $e) {
			 // echo 'Error: ' . $e->getMessage();
			}	
		}
		
	}

}

?>