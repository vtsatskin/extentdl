<?php
	// Stuff for user to edit!

	$wget_path = "wget"; // self expanatory, path or cmd name of wget
	$wget_flags = "-r -nH -np --exclude-directories=icons"; 
	
	// script options
	$config = "presets.txt"; // config file location (stores only presets atm)
	$preset_default_dir = "1"; // if true: preset directory is prefilled to script path, if other: prefilled to specified value
	$chmod = ""; // if set: chmods all files after download to specified mode (i.e 777, 755, etc)
	$debug = "0";  // if true: all wget messages are outputted
?>