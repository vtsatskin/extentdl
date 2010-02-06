<?php
	// -------------------------
	// ERROR REPORTING FUNCTIONS
	// -------------------------
	
	
	$error_spool = array();
	
	// spools errors for later reporting
	function error_spool($msg){
		global $error_spool;
		if(is_string($msg)){
			$error_spool[count($error_spool)+1] = $msg;
		}
	}
	// outputs errors spooled
	function error_spew(){
		global $error_spool;
		$count = count($error_spool);
		foreach($error_spool as $error){
			echo '<div class="error">'.$error.'</div>';
		}
		$error_spool = array();
		return $count;
	}
	
	function success($msg){
		$search = array("[b]", "[/b]");
		$replace = array("<strong>", "</strong>");
		
		$msg = str_replace($search, $replace, $msg);
		echo '<div class="success">'.$msg.'</div>';
	}
?>