<?php
//XXXXXXXXXXXXXXXXXXXXXX STAGING/TESTING FOR Tuesday XXXXXXXXXXXXXXXXXXXXXXXX
define("WEPAY_CLIENT_ID", "85533");
define("WEPAY_CLIENT_SECRET", "90aa04da9b");
define("WEPAY_AUTHORIZE_PARAM", "?client_id=".WEPAY_CLIENT_ID."&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=");
define("WEPAY_REDIRECT_URI", "http://www.bhleague.com/process.php");
define("BHLEAGUE_ACCOUNT_DEMO", "bhleague_app");
define("BHLEAGUE_DESC_DEMO", "This application is for selling tickets to candidate players");


//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

//XXXXXXXXXXXXXXXXXXXXXX STAGING/TESTING FOR Saturday XXXXXXXXXXXXXXXXXXXXXXXX
define("WEPAY_CLIENT_ID_SAT", "179852");
define("WEPAY_CLIENT_SECRET_SAT", "c0544f5668");
define("WEPAY_AUTHORIZE_PARAM_SAT", "?client_id=".WEPAY_CLIENT_ID_SAT."&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=");
define("WEPAY_REDIRECT_URI_SAT", "http://www.bhleague.com/process_sat.php");
define("BHLEAGUE_ACCOUNT_DEMO_SAT", "bhleague_app_saturady");
define("BHLEAGUE_DESC_DEMO_SAT", "This application is for selling tickets to saturday candidate players");
//define("WEPAY_THANKYOU_URI", "http://www.bhleague.com/thankyou.php");

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

//XXXXXXXXXXXXXXXXXXXXXX STAGING/TESTING FOR Sunday XXXXXXXXXXXXXXXXXXXXXXXX
define("WEPAY_CLIENT_ID_SUN", "41186");
define("WEPAY_CLIENT_SECRET_SUN", "38a0e37287");
define("WEPAY_AUTHORIZE_PARAM_SUN", "?client_id=".WEPAY_CLIENT_ID_SUN."&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=");
define("WEPAY_REDIRECT_URI_SUN", "http://www.bhleague.com/process_sun.php");
define("BHLEAGUE_ACCOUNT_DEMO_SUN", "bhleague_app_sunday");
define("BHLEAGUE_DESC_DEMO_SUN", "This application is for selling tickets to sunday candidate players");
//define("WEPAY_THANKYOU_URI", "http://www.bhleague.com/thankyou.php");

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


//XXXXXXXXXXXXXXXXXXXXXX LIVE FOR Tuesday XXXXXXXXXXXXXXXXXXXXXXXX
define("WEPAY_CLIENT_ID_LIVE_TUE", "101313");
define("WEPAY_CLIENT_SECRET_LIVE_TUE", "e58abdc607");
define("WEPAY_AUTHORIZE_PARAM_LIVE_TUE", "?client_id=".WEPAY_CLIENT_ID_LIVE_TUE."&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=");
define("WEPAY_REDIRECT_URI_LIVE_TUE", "http://www.bhleague.com/process.php");
define("WEPAY_REDIRECT_URI_LIVE_TUE_V3", "http://www.bhleague.com/process_v3.php");
define("BHLEAGUE_ACCOUNT_LIVE_TUE", "bhleague_ticketing_tuesday");
define("BHLEAGUE_DESC_LIVE_TUE", "This application is for selling tickets to candidate players");


//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

//XXXXXXXXXXXXXXXXXXXXXX LIVE FOR Saturday XXXXXXXXXXXXXXXXXXXXXXXX
define("WEPAY_CLIENT_ID_LIVE_SAT", "187577");
define("WEPAY_CLIENT_SECRE_LIVE_SAT", "1d991c0e7c");
define("WEPAY_AUTHORIZE_PARAM_LIVE_SAT", "?client_id=".WEPAY_CLIENT_ID_LIVE_SAT."&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=");
define("WEPAY_REDIRECT_URI_LIVE_SAT", "http://www.bhleague.com/process_sat.php");
define("WEPAY_REDIRECT_URI_LIVE_SAT_V3", "http://www.bhleague.com/process_sat_v3.php");
define("BHLEAGUE_ACCOUNT_LIVE_SAT", "bhleague_ticketing_saturday");
define("BHLEAGUE_DESC_LIVE_SAT", "This is for selling tickets for candidate players");
//define("WEPAY_THANKYOU_URI", "http://www.bhleague.com/thankyou.php");

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

//XXXXXXXXXXXXXXXXXXXXXX LIVE FOR Sunday XXXXXXXXXXXXXXXXXXXXXXXX
define("WEPAY_CLIENT_ID_LIVE_SUN", "129158");
define("WEPAY_CLIENT_SECRE_LIVE_SUN", "8d3b2ee6da");
define("WEPAY_AUTHORIZE_PARAM_LIVE_SUN", "?client_id=".WEPAY_CLIENT_ID_LIVE_SUN."&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=");
define("WEPAY_REDIRECT_URI_LIVE_SUN", "http://www.bhleague.com/process_sun.php");
define("WEPAY_REDIRECT_URI_LIVE_SUN_V3", "http://www.bhleague.com/process_sun_v3.php");
define("BHLEAGUE_ACCOUNT_LIVE_SUN", "bhleague_ticketing_sunday");
define("BHLEAGUE_DESC_LIVE_SUN", "This is for selling tickets for candidate players");
//define("WEPAY_THANKYOU_URI", "http://www.bhleague.com/thankyou.php");

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX



define("WEPAY_DOMAIN", "https://stage.wepay.com/v2/");
define("WEPAY_DOMAIN2", "https://stage.wepayapi.com/v2/");



define("WEPAY_DOMAIN_LIVE", "https://www.wepay.com/v2/");
define("WEPAY_DOMAIN2_LIVE", "https://wepayapi.com/v2/");

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


define("WEPAY_AUTHORIZE_URI", "oauth2/authorize");
define("WEPAY_ACCESSTOKEN_URI", "oauth2/token");
define("WEPAY_PAYMENT_ACCOUNT_URI", "account/create");
define("WEPAY_CHECKOUT_URI", "checkout/create");

define("WEPAY_THANKYOU_URI", "http://www.bhleague.com/thankyou.php");
define("WEPAY_THANKYOU_URI_V3", "http://www.bhleague.com/thankyou_v3.php");



define("BHLEAGUE_ACCOUNT_TUE", "Bhleague Tuesday account");
define("BHLEAGUE_DESC_TUE", "This is for Bhleague Tuesday payment account.");

define("BHLEAGUE_ACCOUNT_SAT", "Bhleague Saturday account");
define("BHLEAGUE_DESC_SAT", "This is for Bhleague Saturday payment account.");

define("BHLEAGUE_ACCOUNT_SUN", "Bhleague Sunday account");
define("BHLEAGUE_DESC_SUN", "This is for Bhleague Sunday payment account.");



//https://stage.wepayapi.com/v2/account/create
//https://wepayapi.com/v2/ 
//https://stage.wepay.com/v2/oauth2/authorize?client_id=85533&scope=manage_accounts,view_balance,collect_payments,refund_payments,view_user&redirect_uri=http://www.bhleague.com/process.php
 
?>