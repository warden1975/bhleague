<?php
/*
Database config library.
GoLive! Mobile LLC

Default host/dbms and root user/password.
*/
define("MYSQL_HOSTNAME", "72.34.59.133");
define("MYSQL_DBMS", "CGS");

define("MYSQL_ROOT_USER", "root");
define("MYSQL_ROOT_PASSWORD", "wdfg4thg");

if (!defined('MYSQL_REMOTE_HOSTNAME')) define("MYSQL_REMOTE_HOSTNAME", "72.34.59.133");
if (!defined('MYSQL_REMOTE_DBMS')) define("MYSQL_REMOTE_DBMS", "CGS");

if (!defined('MYSQL_REMOTE_USER')) define("MYSQL_REMOTE_USER", "remote");
if (!defined('MYSQL_REMOTE_PASSWORD')) define("MYSQL_REMOTE_PASSWORD", "CGSRemote");

/*
DRBD Cluster remote host/dbms and root user/password.
*/
define("MYSQL_DRBD_WRITE_HOSTNAME", "67.222.99.249");
define("MYSQL_DRBD_READ1_HOSTNAME", "67.222.99.227");
define("MYSQL_DRBD_READ2_HOSTNAME", "67.222.99.228");

define("MYSQL_DRBD_WRITE_DBMS", "CGS");
define("MYSQL_DRBD_READ_DBMS", "CGS");

define("MYSQL_DRBD_WRITE_USER", "drbdremote_w");
define("MYSQL_DRBD_WRITE_PASSWORD", "KzQD8rVzQ7JXPf2gTx");

define("MYSQL_DRBD_READ_USER", "drbdremote_r");
define("MYSQL_DRBD_READ_PASSWORD", "bmYyzqb8w3b5CyNMvp");
 
/*
Unique user account and password for each back-script's.
*/
define("MYSQL_CHATBOX", "chat");
define("MYSQL_INTRANET", "intranet");
define("MYSQL_INSTACONNECT", "instaconnect");
define("MYSQL_INTERNAL", "internal");
define("MYSQL_SPASSWORD", "JBIi6rEVBDMQjq5E");
define("MYSQL_T2W", "text2win");
define("MYSQL_REBILL", "subscriptions");
define("MYSQL_DR", "index_process");
define("MYSQL_BIN", "binraw");
define("MYSQL_TM", "touchmaps");
define("MYSQL_WSM", "wsmdrraw");
define("MYSQL_MORAW", "moraw");
define("MYSQL_MO", "mget_process");
define("MYSQL_MBDRRAW", "msenddpraw");
define("MYSQL_ODRRAW", "omsendraw");
define("MYSQL_SRM", "msend_srm");
define("MYSQL_OIOO", "msend_oioo");
define("MYSQL_USC", "msend_usc");
define("MYSQL_SI", "msendsi");
define("MYSQL_PSMSREP", "PSMS_ds_report");
define("MYSQL_OFFLINE", "offlinebill");
define("MYSQL_MBDP", "msenddp");
define("MYSQL_MBPSMS", "msend");
define("MYSQL_CDR", "cdr");
define("MYSQL_GEO", "geodata");
define("MYSQL_SCHED", "scheduler");
define("MYSQL_ADC", "adc");
define("MYSQL_WAP", "msenddpwap");
define("MYSQL_PRV", "send_prv");
define("MYSQL_TTS", "send_tts");
define("MYSQL_OPPC", "msend_oppc");
define("MYSQL_MAILER", "mailer");

define("MYSQL_OMSEND", "omsend");
define("MYSQL_OMDR", "omdr");
define("MYSQL_OMDRRC", "omdrrc");

if (!defined('HTTP_CRLF')) define('HTTP_CRLF', chr(13) . chr(10));
$domain = 'admin.golivemobile.com';
$dbms = "CGS";
?>