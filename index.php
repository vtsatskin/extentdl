<?php
	require 'config.php';
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>extentdl</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" charset="utf-8">
	
</head>
<body>
	<?php
		require 'functions/error.php';
		require 'functions/config.php';
		
		// Check if no errors
		if(!error_spew()){
			//Parsing config file
			$file = file($config, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach($file as $preset){
				$preset = explode("|", $preset);
				$presets[$preset[0]] = array("dir" => $preset[1], "lastdl" => $preset[2]);
			}
			unset($file);
			if(!config_empty()) ksort($presets); // Sort presets alphabetically
			
			// Import all submitted request vars
			import_request_variables("gp", "r_");
			
			
			// Download submitions
			if($r_download){
				if(empty($r_download_preset) || empty($r_download_url)) error_spool("Please fill in all fields");
				else {
					// Debugging
					if($debug) {
						$debug_file = "output.temp.txt";
						$output = "-o $debug_file";
					}
					
					$wget_cmd = "$wget_path $output $wget_flags -P ".escapeshellarg($presets[$r_download_preset]['dir'])." ".escapeshellarg($r_download_url); // command to be executed
					
					if($debug) echo "<code>Executing: $wget_cmd<br></code>"; // Echo command to be exec
					
					exec($wget_cmd); // MONEY SHOT
					
					if($chmod) exec("chmod -R $chmod ".escapeshellarg($presets[$r_download_preset]['dir']));
					
					// More debugging
					if($debug){
						// outputs all of wget output
						$handle = fopen($debug_file, "r");
						$contents = fread($handle, filesize($debug_file));
						echo "<code>".nl2br($contents)."</code>";
						fclose($handle);
						unlink($debug_file);
					}
					
					// Record last download in config
					$presets[$r_download_preset]['lastdl'] = $r_download_url;
					update_config();
				}
			} // end download
			
			//// Preset management
			// add/update preset
			elseif($r_edit){
				if(empty($r_edit_dir) || empty($r_edit_name)) error_spool("Please fill in all fields");
				else {
					$presets[$r_edit_name]['dir'] = $r_edit_dir;
					success("Preset [b]".$r_edit_name."[/b] added/updated successfully");
					update_config();
				}
			} // end preset add/update
			
			// delete preset
			elseif($r_delete){
				if(!empty($r_delete)){
					unset($presets[$r_delete]);
					update_config();
					header("Location: ./");
				}
			} // end delete preset
			
			error_spew();
			?>
				<form action="index.php" method="post" accept-charset="utf-8">
					<fieldset>
						<legend>Download</legend>
						<?php if(!config_empty()) { ?>
						<label for="download_preset">Preset:</label>
						<select name="download_preset" id="download_preset" size="1">
						<?php
							foreach(array_keys($presets) as $name){
								echo '<option value="'.ucwords($name).'">'.ucwords($name).'</option>';
							}
						?>
						</select>
						<label for="download_url">URL:</label><input type="text" name="download_url" value="" id="download_url">
						<input type="submit" value="Download" name="download">
						<?php } else echo "<strong>No presets exist</strong>" ?>
					</fieldset>
				</form>
				<form action="index.php" method="post" accept-charset="utf-8">
					<fieldset>
						<legend>Add/Edit Preset</legend>
						<label for="edit_name">Name: </label><input type="text" name="edit_name" value="" id="edit_name">	
						<label for="edit_dir">Directory: </label><input type="text" name="edit_dir" value="<?php if($preset_default_dir) echo dirname($_SERVER['SCRIPT_FILENAME']); ?>" id="edit_dir">						
						<input type="submit" value="Add" name="edit">
					</fieldset>
				</form>
				<fieldset>
					<legend>Presets</legend>
					<?php if(!config_empty()) { ?>
					<table border="0" cellspacing="5" cellpadding="5">
						<tr><th>Name</th><th>Directory</th><th>Last Download</th></tr>
						<?php
						foreach(array_keys($presets) as $name){
							echo "<tr><td>$name <a href=\"?delete=".str_replace(" ", "%20", $name)."\">(delete)</a></td><td>".$presets[$name]['dir']."</td><td>".$presets[$name]['lastdl']."</td></tr>\n";
						}
						?>
					</table>
					<?php } else echo "<strong>No presets exist</strong>" ?>
				</fieldset>
			<?php
		}
	?>
</body>
</html>