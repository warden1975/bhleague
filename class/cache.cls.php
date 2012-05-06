<?php 

class Cache {
	private $filename;
	private $timeout;
	public $debug_clearer = "\n";
	public $debug = false;
	public $debug_log = "";
	
	function __construct($_filename, $_timeout = 120) {
//		$this->filename = "/home/wanknet/public_html/tmp/".md5($_filename).".php";
		$this->filename = "/home/golive/public_html/internal/intranet/tmp/".$_filename.".php";
		$this->timeout =  $_timeout;
	}
	
	function exists() {
		if (!file_exists($this->filename)) {
			$this->write_debug("file does not exists");
			return false;
		}
		else {
			$this->write_debug("file existing checking for validity");
			if ((time() - filemtime($this->filename)) > $this->timeout) {
				unlink($this->filename);
				$this->write_debug("file is bad, deleted");
				return false;
			}
			else {
			    $this->write_debug("file in cache is good");
				return true;
			}
		}
	}
	function read() {
	    $this->write_debug("reading and outputing".$this->filename);
		$fh = fopen($this->filename, "r");
		$content = fread($fh, filesize($this->filename));
		fclose($fh);
		return $content;
	}

	function write($content) {
		if (file_exists($this->filename)) {
			$this->write_debug("deleting file if exists");
			unlink($this->filename);
		}
		$fh = fopen($this->filename, "w");
		fwrite($fh, $content);
		fclose($fh);
		$this->write_debug("new file cache was written to disk");
	}
	
	function write_debug($message) {
		if ($debug) {
			$debug_log .= $this->message.$this->debug_clearer;
		}

	}
}
