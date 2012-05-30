<?php
	require_once('wepay_config.php');

	$fp3 = fopen('log.txt','a+');
		
	$code = $user_id = $access_token = $token_type = '';
	$code = (isset($_REQUEST['code']))?trim($_REQUEST['code']):'';
	
	$user_id = (isset($_REQUEST['user_id']))?trim($_REQUEST['user_id']):'';
	$access_token = (isset($_REQUEST['access_token']))?trim($_REQUEST['access_token']):'';
	$token_type = (isset($_REQUEST['token_type']))?trim($_REQUEST['token_type']):'';
	
	
	
	if(isset($code) && trim($code) != '' ){

		$url = WEPAY_ACCESSTOKEN_URI."?client_id=".WEPAY_CLIENT_ID."&redirect_uri=".WEPAY_REDIRECT_URI."&client_secret=".WEPAY_CLIENT_SECRET."&code=".$code;
	
		/*
		###### NOT WORKING ON CURL ######
		
		$ch = curl_init(url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		$result = curl_exec($ch);
		fwrite($fp3, " url --> " . $url ."\n\n");		
		
		if (!$result) {
			$err = "cURL error number:" .curl_errno($ch)."\n cURL error:" . curl_error($ch)."\n";
			fwrite($fp3, " error --> " . $err ."\n\n");
		}	else{	
			$jsonReturn = json_decode($result);		
		}*/
	
		$jsonraw = file_get_contents($url);
		$jsonReturn = json_decode($jsonraw);
		
		fwrite($fp3, " result2 --> " . $jsonraw ."\n\n");
		
		foreach($jsonReturn as $key => $value)
		{
			fwrite($fp3, "key --> " . $key . "=>" . $value ."\n");	
		}			
		
	}

	fclose($fp3);
?>