<?php

define("WEPAY_CLIENT_ID", "85533");
define("WEPAY_CLIENT_SECRET", "90aa04da9b");
define("WEPAY_REDIRECT_URI", "http://www.bhleague.com/process.php");

//define("WEPAY_DOMAIN", "https://wepayapi.com/v2/ ");// LIVE 
define("WEPAY_DOMAIN", "https://stage.wepay.com/v2/");
define("WEPAY_AUTHORIZE_URI", "oauth2/authorize");
define("WEPAY_AUTHORIZE_PARAM", "?client_id=".WEPAY_CLIENT_ID."&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=");
define("WEPAY_ACCESSTOKEN_URI", "oauth2/token");
//https://wepayapi.com/v2/ 
//https://stage.wepay.com/v2/oauth2/authorize?client_id=85533&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=http://www.bhleague.com/process.php

?>