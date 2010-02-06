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
		$file = fopen($config, 'w');
		fwrite($file, $updated);
		fclose($file);
	}
	
	// Check if config empty
	function config_empty(){
		global $presets;
		return empty($presets);
	}
	
	// File Checking
	if(is_writable("./")) {
		if(file_exists($config)) {
			if(!is_writable($config)) error_spool("config file <strong>$config</strong> is not writable");
		}
		else {
			fopen($config, 'w');
			chmod($config, 0777);
		}
	}
	else error_spool("directory <strong>".dirname($_SERVER['SCRIPT_FILENAME'])."/</strong> is not writable");
?>