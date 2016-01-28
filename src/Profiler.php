<?php
/**
 * ProfilerPHP
 *
 * This class will profile your code blocks.
 *
 * This is a standalone class you can use in any project for profiling
 *
 *
 * @author    Sanjog Dash   
 * @version   1.0.0
 * @todo Shifting this class to psr-4 standard
 */

namespace Sanjog152\profilerPHP;

class Profiler {

    	public $result;
    	public $start_time;
    	public $json_data;
    	public $log_file_name;    
	
	function __construct() {
        	$this->log_file_name="/tmp/logs.json";// default profile log file name
    	}

	public function initialize($args){
		if(array_key_exists("log_file_name")&& !empty($args["log_file_name"])){
			$this->log_file_name = $args["log_file_name"];
		}
	}
	public function start($pointer) {       
        	$this->time_calculate_start($pointer);
        	$this->mem_calculate_start($pointer);       
    	}
    	public function end($pointer) {
        	$this->time_calculate_end($pointer);
        	$this->mem_calculate_end($pointer);
        	$data = $this->result[$pointer];
        	unset($this->result[$pointer]);
        	if (!empty($data)) {
            		$json_data["pointer"] = $pointer;
            		$json_data["uid"] = uniqid();
            		$json_data["start_time"] = $data["time_start"];
            		$json_data["end_time"] = $data["time_end"];
            		$json_data["start_mem"] = $data["mem_start"];
            		$json_data["end_mem"] = $data["mem_end"];
            		$json_data["time_taken"]= $data["time_end"]-$data["time_start"];
            		$json_data["mem_taken"]= $data["mem_end"]-$data["mem_start"];
            		$this->log_message(json_encode($json_data));
        	}
       
    	}
    	private function mem_calculate_start($pointer) {
        	$this->result[$pointer]["mem_start"] = memory_get_usage();
    	}
    	private function mem_calculate_end($pointer) {
        	$this->result[$pointer]["mem_end"] = memory_get_usage();
    	}
    	private function time_calculate_start($pointer) {
        	$this->result[$pointer]["time_start"] = microtime(true);
    	}
    	private function time_calculate_end($pointer) {
        	$this->result[$pointer]["time_end"] = microtime(true);
    	}
    	private function log_message($message) {
        	$log_path = $this->log_file_name;
        	$handle = fopen($log_path, "a+");
        	fwrite($handle, $message);
        	fwrite($handle, "\n");
        	fclose($handle);
    	}
    	private function format_bytes($bytes, $precision = 2) {
        	$units = array('B', 'KB', 'MB', 'GB', 'TB');
        	$bytes = max($bytes, 0);
       	 	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        	$pow = min($pow, count($units) - 1);
        	return round($bytes, $precision) . ' ' . $units[$pow];
    	}	
}
?>
