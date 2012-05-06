<?php
/*
Check if script already running using pid
@http://www.electrictoolbox.com/check-php-script-already-running/
revised...fixed filename 02192009
*/

class pid {

    protected $filename;
    public $already_running = false;
  
   
    function __construct($pid_filename) {
       
        $directory = "/tmp";
        $this->filename = $directory ."/". $pid_filename . '.pid';
        
        
       
        if(is_writable($this->filename) || is_writable($directory)) {
           
            if(file_exists($this->filename)) {
                $pid = (int)trim(file_get_contents($this->filename));
                if(posix_kill($pid, 0)) {
                    $this->already_running = true;
                }
            }
           
        }
        else {
            die("Cannot write to pid file '$this->filename'. Program execution halted.\n");
        }
       
        if(!$this->already_running) {
            $pid = getmypid();
            file_put_contents($this->filename, $pid);
        }
       
    }

    public function __destruct() {

        if(!$this->already_running && file_exists($this->filename) && is_writeable($this->filename)) {
            unlink($this->filename);
        }
   
    }
   
}
?>