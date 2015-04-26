<?php
	header('Content-type: application/json');
	if(isset($response)){
		echo json_encode($response);
	}
	else{
		echo json_encode(array("success"=>false,"error_code"=>-9,"message"=>"Invalid ajax call"));
	}
?>