<?php
	// -------------------------
	// CONFIG AND FILE CHECKING
	// -------------------------

	// Write back the config file
	function update_config(){
		global $presets, $config;
		foreach(array_keys($presets) as $name){
			$lastdl = $presets[$name]['lastdl'];
			if(!empty($lastdl)) $lastdl = "|".$presets[$name]['lastdl'];
			$updated .= ucwords($name)."|".$presets[$name]['dir'].$lastdl."\n";				
		}
		file_put_contents($config, $updated);
	}
	
	// Check if config empty
	function config_empty(){
		global $presets;
		return empty($presets);
	}
	
	// Parse config file: store in array
	
	function parse_config(){
		global $presets, $config;
		$file = file($config, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach($file as $preset){
			$preset = explode("|", $preset);
			$presets[$preset[0]] = array("dir" => $preset[1], "lastdl" => $preset[2]);
		}
		if(!config_empty()) ksort($presets); // Sort presets alphabetically
	}
	
	// File Checking
	if(is_writable("./")) {
		if(file_exists($config)) {
			if(!is_writable($config)) error_spool("config file <strong>$config</strong> is not writable");
		}
		else {
			fopen($config, 'w'); // create file
			chmod($config, 0777);
		}
	}
	else error_spool("directory <strong>".dirname($_SERVER['SCRIPT_FILENAME'])."/</strong> is not writable");
?>