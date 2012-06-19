<?php
	require_once('/home/bhleague/public_html/admin/class/db.cls.php');
	$db = new DB_Connect(MYSQL_INTRANET, true);
	require_once('wepay_config.php');
	
	function file_get_contents_curl($url, $curlopt = array()){
		$ch = curl_init();
		$default_curlopt = array(
		CURLOPT_TIMEOUT => 2,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_FOLLOWLOCATION => 1,
		CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 AlexaToolbar/alxf-1.54 Firefox/3.6.13 GTB7.1"
		);
		$curlopt = array(CURLOPT_URL => $url) + $curlopt + $default_curlopt;
		curl_setopt_array($ch, $curlopt);
		$response = curl_exec($ch);
		if($response === false)
		trigger_error(curl_error($ch));
		curl_close($ch);
		return $response;
	}

	$fp3 = fopen('log.txt','a+');						###### REMOVE THIS AFTER DEBUG ######
	fwrite($fp3, "\n NEW ACCOUNT \n");	###### REMOVE THIS AFTER DEBUG ######
		
	$code = $user_id = $access_token = $token_type = '';
	$code = (isset($_REQUEST['code']))?trim($_REQUEST['code']):'';
	
	$user_id = (isset($_REQUEST['user_id']))?trim($_REQUEST['user_id']):'';
	$access_token = (isset($_REQUEST['access_token']))?trim($_REQUEST['access_token']):'';
	$token_type = (isset($_REQUEST['token_type']))?trim($_REQUEST['token_type']):'';
	
	
	
	if(isset($code) && trim($code) != '' ){

		//$url = WEPAY_DOMAIN.WEPAY_ACCESSTOKEN_URI."?client_id=".WEPAY_CLIENT_ID_SAT."&redirect_uri=".WEPAY_REDIRECT_URI_SAT."&client_secret=".WEPAY_CLIENT_SECRET_SAT."&code=".$code;
		$url = WEPAY_DOMAIN_LIVE.WEPAY_ACCESSTOKEN_URI."?client_id=".WEPAY_CLIENT_ID_LIVE_SAT."&redirect_uri=".WEPAY_REDIRECT_URI_LIVE_SAT."&client_secret=".WEPAY_CLIENT_SECRE_LIVE_SAT."&code=".$code;//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

		//$jsonraw = file_get_contents($url);
		$jsonraw = file_get_contents_curl($urli);
		$jsonReturn = json_decode($jsonraw);

		###### REMOVE THIS AFTER DEBUG ######		
			fwrite($fp3, "Authorize result --> " . $jsonraw ."\n\n");
			foreach($jsonReturn as $key => $value)
			{
				fwrite($fp3, "key --> " . $key . "=>" . $value ."\n");	
			}			
		###### REMOVE THIS AFTER DEBUG ######	
		
		/*
		sample result:
		
		key --> user_id=>668534
		key --> access_token=>916fca1c599a077ce865c783d36afa8b52ad5a7bf035b901f18a7aa073c7ec28
		key --> token_type=>BEARER		
		
		*/
		
		# CREATE PAYMENT ACCOUNT FOR USER 
		if(isset($jsonReturn->user_id) && isset($jsonReturn->access_token) && isset($jsonReturn->token_type))		{
		
			/*
			* FOR CREATE PAYMENT ACCOUNT
			*/		
			fwrite($fp3, $jsonReturn->user_id." => ".$jsonReturn->access_token." => ".$jsonReturn->token_type."\n");	
			//fwrite($fp3, WEPAY_DOMAIN2.WEPAY_PAYMENT_ACCOUNT_URI."\n");
			fwrite($fp3, WEPAY_DOMAIN2_LIVE.WEPAY_PAYMENT_ACCOUNT_URI."\n");//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
			
			//$ch = curl_init(WEPAY_DOMAIN2.WEPAY_PAYMENT_ACCOUNT_URI);
			$ch = curl_init(WEPAY_DOMAIN2_LIVE.WEPAY_PAYMENT_ACCOUNT_URI); ///XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
						
			//$arguments = (array('name' => BHLEAGUE_ACCOUNT_DEMO_SAT, 'description' => BHLEAGUE_DESC_DEMO_SAT));
			$arguments = (array('name' => BHLEAGUE_ACCOUNT_SAT, 'description' => BHLEAGUE_DESC_SAT));//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
			curl_setopt($ch, CURLOPT_USERAGENT, 'WePay v2 PHP SDK v0.0.9');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_POST, true);
			#curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: ".$jsonReturn->token_type." ".$jsonReturn->access_token));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$jsonReturn->access_token));//	, "Content-Type: application/json"		
			curl_setopt($ch, CURLOPT_CAINFO,  'cacert.pem');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);
			
			##############
			
			#curl_setopt($this->ch, CURLOPT_USERAGENT, 'WePay v2 PHP SDK v' . self::VERSION);
			#curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $this->token", "Content-Type: application/json"));
			#curl_setopt($this->ch, CURLOPT_CAINFO,  'cacert.pem');
			
			##############
			
			
			
			$result = curl_exec($ch);
			if (!$result) {
				$err = "cURL error number:" .curl_errno($ch)."\n cURL error:" . curl_error($ch)."\n";
				fwrite($fp3, "error --> " . $err ."\n");
			}			
			$jsonReturn_payment = json_decode($result);		

			###### REMOVE THIS AFTER DEBUG ######		
				fwrite($fp3, "PAYMENT result --> " . $result ."\n\n");
				foreach($jsonReturn_payment as $key => $value)
				{
					fwrite($fp3, "key --> " . $key . "=>" . $value ."\n");	
				}			
			###### REMOVE THIS AFTER DEBUG ######	

			/*
			* FOR CREATE CHECKOUT OBJECT
			*/			
			
			//$ch = curl_init(WEPAY_DOMAIN2.WEPAY_CHECKOUT_URI);
			$ch = curl_init(WEPAY_DOMAIN2_LIVE.WEPAY_CHECKOUT_URI);//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
			//$arguments = array('account_id'=>$jsonReturn_payment->account_id, 'amount'=>'150.00', 'short_description'=>BHLEAGUE_ACCOUNT_DEMO_SAT, 'type'=>'DONATION', 'redirect_uri'=>WEPAY_THANKYOU_URI);
			$arguments = array('account_id'=>$jsonReturn_payment->account_id, 'amount'=>'150.00', 'short_description'=>BHLEAGUE_ACCOUNT_SAT, 'type'=>'DONATION', 'redirect_uri'=>WEPAY_THANKYOU_URI_V3);//XXXXXXXXXXXXXXXXXXXXXXXXXXXX
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$jsonReturn->access_token));
			curl_setopt($ch, CURLOPT_CAINFO,  'cacert.pem');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);
			$result = curl_exec($ch);
			if (!$result) {
				$err = "cURL error number:" .curl_errno($ch)."\n cURL error:" . curl_error($ch)."\n";
				fwrite($fp3, "error --> " . $err ."\n");
			}				
			$jsonReturn_checkout = json_decode($result);		
		
			###### REMOVE THIS AFTER DEBUG ######		
				fwrite($fp3, "CHECKOUT result --> " . $result ."\n\n");
				foreach($jsonReturn_checkout as $key => $value)
				{
					fwrite($fp3, "key --> " . $key . "=>" . $value ."\n");	
				}	
			//$ch = curl_init(WEPAY_DOMAIN2."user");
			$ch = curl_init(WEPAY_DOMAIN2_LIVE."user");//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$jsonReturn->access_token));
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
			###### REMOVE THIS AFTER DEBUG ######			
			
			# TODO INSERT INTO wepay_accounts
			# $jsonReturn->user_id	
			# $jsonReturn->access_token	
			# $jsonReturn->token_type				
			# $jsonReturn_payment->account_id
			# $jsonReturn_payment->account_uri	
			
			# $jsonReturn_checkout->checkout_id	
			# $jsonReturn_checkout->checkout_uri	
			# $jsonReturn_user->first_name
			# $jsonReturn_user->last_name						
			# check if user_id exist if yes update account if no insert details	
			
			
			//$sql = "SELECT wepay_user_id from `bhleague`.`wepay_accounts` where wepay_user_id ='".$jsonReturn->user_id."'";
//			fwrite($fp3, "USER_ID QUERY --> " . $sql ."\n\n");
//			$rs = $db->query($sql);
//			if($rs->num_rows==0)
//			{
				$sqlx ="INSERT INTO `bhleague`.`wepay_accounts` SET wepay_user_id='".$jsonReturn->user_id."',wepay_access_token='".$jsonReturn->access_token."',wepay_token_type='".$jsonReturn->token_type."',
				wepay_account_id='".$jsonReturn_payment->account_id."',wepay_account_uri='".$jsonReturn_payment->account_uri."',wepay_checkout_id='".$jsonReturn_checkout->checkout_id."',wepay_checkout_uri='".$jsonReturn_checkout->checkout_uri."',first_name='".$jsonReturn_user->first_name."',last_name='".$jsonReturn_user->last_name."',wepay_email='".$jsonReturn_user->email."'";
				fwrite($fp3, "INSERT WEPAY QUERY --> " . $sqlx ."\n\n");
				
				$rx =$db->query($sqlx);
				

			//}
//			else if($rs->num_rows>0)
//			{
//				
//				$sqlx ="UPDATE `bhleague`.`wepay_accounts` SET wepay_access_token='".$jsonReturn->access_token."',wepay_token_type='".$jsonReturn->token_type."',
//				wepay_account_id='".$jsonReturn_payment->account_id."',wepay_account_uri='".$jsonReturn_payment->account_uri."',wepay_checkout_id='".$jsonReturn_checkout->checkout_id."',wepay_checkout_uri='".$jsonReturn_checkout->checkout_uri."',first_name='".$jsonReturn_user->first_name."',last_name='".$jsonReturn_user->last_name."',wepay_email='".$jsonReturn_user->email."'
//				WHERE wepay_user_id='".$jsonReturn->user_id."'";
//				
//				fwrite($fp3, "UPDATE WEPAY QUERY --> " . $sqlx ."\n\n");
//				
//				$rx =$db->query($sqlx)
//			}
			if(isset($jsonReturn_checkout->checkout_id) && trim($jsonReturn_checkout->checkout_id)!='')
			{
			header("location: $jsonReturn_checkout->checkout_uri");
			}
			else
			{
			header("location: index.php");
			}
		}
	}

	fclose($fp3);		###### REMOVE THIS AFTER DEBUG ######
	
	#### AFTER CHECK
?>