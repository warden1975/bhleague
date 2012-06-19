<?php

	require_once('/home/bhleague/public_html/admin/class/db.cls.php');
	$db = new DB_Connect(MYSQL_INTRANET, true);
	require_once('wepay_config.php');

	$fp3 = fopen('log.txt','a+');						###### REMOVE THIS AFTER DEBUG ######
	fwrite($fp3, "\n NEW ACCOUNT \n");	###### REMOVE THIS AFTER DEBUG ######
		
	$code = $user_id = $access_token = $token_type = '';
	$code = (isset($_REQUEST['code']))?trim($_REQUEST['code']):'';
	
	$user_id = (isset($_REQUEST['user_id']))?trim($_REQUEST['user_id']):'';
	$access_token = (isset($_REQUEST['access_token']))?trim($_REQUEST['access_token']):'';
	$token_type = (isset($_REQUEST['token_type']))?trim($_REQUEST['token_type']):'';
	

	$ch = curl_init(WEPAY_DOMAIN2."user");
	//$arguments = array('account_id'=>$jsonReturn_payment->account_id, 'amount'=>'150.00', 'short_description'=>BHLEAGUE_DESC_DEMO, 'type'=>'DONATION', 'redirect_uri'=>WEPAY_THANKYOU_URI);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer 148d5120990ed8e580d1beae306383392b00db454742b0a5ab86a171dba22100"));
	curl_setopt($ch, CURLOPT_CAINFO,  'cacert.pem');
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);
	$result = curl_exec($ch);
	if (!$result) {
		$err = "cURL error number:" .curl_errno($ch)."\n cURL error:" . curl_error($ch)."\n";
		fwrite($fp3, "error --> " . $err ."\n");
	}				
	$jsonReturn_user = json_decode($result);		

	###### REMOVE THIS AFTER DEBUG ######		
		fwrite($fp3, "USER SEARCH result --> " . $result ."\n\n");
		foreach($jsonReturn_user as $key => $value)
		{
			fwrite($fp3, "key --> " . $key . "=>" . $value ."\n");	
		}		

?>