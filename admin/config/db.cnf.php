<?php
/*
Database config library.
GoLive! Mobile LLC

Default host/dbms and root user/password.
*/
define("MYSQL_HOSTNAME", "10.10.28.25");
define("MYSQL_DBMS", "logs");

define("MYSQL_REMOTE", "remote");
#define("MYSQL_ROOT_PASSWORD", "JBIi6rEVBDMQjq5E");
define("MYSQL_ROOT_PASSWORD", "N9ZPtpUSEkpCk4Q221Bb");

/*
DRBD Cluster remote host/dbms and root user/password.
*/
/*define("MYSQL_DRBD_WRITE_HOSTNAME", "67.222.99.249");
define("MYSQL_DRBD_READ1_HOSTNAME", "67.222.99.227");
define("MYSQL_DRBD_READ2_HOSTNAME", "67.222.99.228");
define("MYSQL_DRBD_READ3_HOSTNAME", "67.222.104.104");*/


define("MYSQL_DRBD_WRITE_HOSTNAME", "10.10.26.100"); //67.222.99.249

define("MYSQL_DRBD_READ1_HOSTNAME", "10.10.26.12"); //67.222.99.227  .12
define("MYSQL_DRBD_READ2_HOSTNAME", "10.10.26.13"); //67.222.99.228  .13
define("MYSQL_DRBD_READ3_HOSTNAME", "10.10.26.14"); //67.222.104.104 .14

define("MYSQL_DRBD_WRITE_DBMS", "CGS");
define("MYSQL_DRBD_READ_DBMS", "CGS");

define("MYSQL_DRBDREMOTE_W", "drbdremote_w");
define("MYSQL_DRBD_WRITE_PASSWORD", "S5Y4rYTCT4waweHe61iX");

define("MYSQL_DRBDREMOTE_R", "drbdremote_r");
define("MYSQL_DRBD_READ_PASSWORD", "S5Y4rYTCT4waweHe61iX");

define("MYSQL_DRBD_A", "dbadmin");
define("MYSQL_DRBD_A_PASS", "utdKKIdRp5XrhPgGSroN");

define("USE_DRBD", true);

define("USE_MONGO_LOGS", true);

if (!defined('MYSQL_REMOTE_HOSTNAME')) define("MYSQL_REMOTE_HOSTNAME", "72.34.59.133");
if (!defined('MYSQL_REMOTE_DBMS')) define("MYSQL_REMOTE_DBMS", "CGS");

if (!defined('MYSQL_REMOTE_USER')) define("MYSQL_REMOTE_USER", "remote");
if (!defined('MYSQL_REMOTE_PASSWORD')) define("MYSQL_REMOTE_PASSWORD", "CGSRemote");
if (!defined('MYSQL_REMOTE_DOMAIN')) define("MYSQL_REMOTE_DOMAIN", "admin.golivemobile.com");
/*
Unique user account and password for each back-script's.
*/
define("MYSQL_INDEX", "index");
define("MYSQL_MGET", "mget");
define("MYSQL_OINDEX", "oindex");
define("MYSQL_OMGET", "omget");
define("MYSQL_TEXT2WIN", "text2win");
define("MYSQL_SUBSCRIPTIONS", "subscriptions");
define("MYSQL_INDEX_PROCESS", "index_process");
define("MYSQL_BINRAW", "binraw");
define("MYSQL_TOUCHMAPS", "touchmaps");
define("MYSQL_WSMDRRAW", "wsmdrraw");
define("MYSQL_MORAW", "moraw");
define("MYSQL_MGET_PROCESS", "mget_process");
define("MYSQL_MSENDDPRAW", "msenddpraw");
define("MYSQL_OMSENDRAW", "omsendraw");
define("MYSQL_MSEND_SRM", "msend_srm");
define("MYSQL_MSEND_OIOO", "msend_oioo");
define("MYSQL_MSEND_USC", "msend_usc");
define("MYSQL_MSENDSI", "msendsi");
define("MYSQL_PSMS_DS_REPORT", "PSMS_ds_report");
define("MYSQL_OFFLINEBILL", "offlinebill");
define("MYSQL_MSENDDP", "msenddp");
define("MYSQL_MSEND", "msend");
define("MYSQL_CDR", "cdr");
define("MYSQL_GEODATA", "geodata");
define("MYSQL_SCHED", "scheduler");
define("MYSQL_ADC", "adc");
define("MYSQL_MSENDDPWAP", "msenddpwap");
define("MYSQL_SEND_PRV", "send_prv");
define("MYSQL_TTS", "send_tts");
define("MYSQL_MSEND_OPPC", "msend_oppc");
define("MYSQL_MAILER", "mailer");
define("MYSQL_OMSEND", "omsend");
define("MYSQL_OMDR", "omdr");
define("MYSQL_OMDRRC", "omdrrc");
define("MYSQL_MSENDDP_GLMT", "msenddp_glmt");
define("MYSQL_MSENDDP_L87", "msenddp_l87");
define("MYSQL_MSENDDP_OM", "msenddp_om");
define("MYSQL_MSEND_GLMT", "msend_glmt");
define("MYSQL_MSEND_L87", "msend_l87");
define("MYSQL_PINMT", "pinmt");
define("MYSQL_MOTINDEX", "motindex");
define("MYSQL_MOTGET", "motget");
define("MYSQL_MOTSEND", "motsend");
define("MYSQL_MSENDDP_MOT", "msenddp_mot");
define("MYSQL_CHATBOX", "intranet");
define("MYSQL_INTRANET", "intranet");
define("MYSQL_INSTACONNECT", "instaconnect");
define("MYSQL_INTERNAL", "internal");
define("MYSQL_LOGSCHECKER", "logschecker");
define("MYSQL_ANTON_MALOLOS", "anton_malolos");
define("MYSQL_QUERY_BUILDER", "query_builder");
define("MYSQL_DEACTIVATED_FILES", "deacfile");

/* DRBD WRITE USERS */
define("MYSQL_INDEX_W", "index_w");
define("MYSQL_MGET_W", "mget_w");
define("MYSQL_OINDEX_W", "oindex_w");
define("MYSQL_OMGET_W", "omget_w");
define("MYSQL_TEXT2WIN_W", "text2win_w");
define("MYSQL_SUBSCRIPTIONS_W", "subscriptions_w");
define("MYSQL_INDEX_PROCESS_W", "index_process_w");
define("MYSQL_BINRAW_W", "binraw_w");
define("MYSQL_TOUCHMAPS_W", "touchmaps_w");
define("MYSQL_WSMDRRAW_W", "wsmdrraw_w");
define("MYSQL_MORAW_W", "moraw_w");
define("MYSQL_MGET_PROCESS_W", "mget_process_w");
define("MYSQL_MSENDDPRAW_W", "msenddpraw_w");
define("MYSQL_OMSENDRAW_W", "omsendraw_w");
define("MYSQL_MSEND_SRM_W", "msend_srm_w");
define("MYSQL_MSEND_OIOO_W", "msend_oioo_w");
define("MYSQL_MSEND_USC_W", "msend_usc_w");
define("MYSQL_MSENDSI_W", "msendsi_w");
define("MYSQL_PSMS_DS_REPORT_W", "PSMS_ds_report_w");
define("MYSQL_OFFLINEBILL_W", "offlinebill_w");
define("MYSQL_MSENDDP_W", "msenddp_w");
define("MYSQL_MSEND_W", "msend_w");
define("MYSQL_CDR_W", "cdr_w");
define("MYSQL_GEODATA_W", "geodata_w");
define("MYSQL_SCHED_W", "scheduler_w");
define("MYSQL_ADC_W", "adc_w");
define("MYSQL_MSENDDPWAP_W", "msenddpwap_w");
define("MYSQL_SEND_PRV_W", "send_prv_w");
define("MYSQL_TTS_W", "send_tts_w");
define("MYSQL_MSEND_OPPC_W", "msend_oppc_w");
define("MYSQL_MAILER_W", "mailer_w");
define("MYSQL_OMSEND_W", "omsend_w");
define("MYSQL_OMDR_W", "omdr_w");
define("MYSQL_OMDRRC_W", "omdrrc_w");
define("MYSQL_MSENDDP_GLMT_W", "msenddp_glmt_w");
define("MYSQL_MSENDDP_L87_W", "msenddp_l87_w");
define("MYSQL_MSENDDP_OM_W", "msenddp_om_w");
define("MYSQL_MSEND_GLMT_W", "msend_glmt_w");
define("MYSQL_MSEND_L87_W", "msend_l87_w");
define("MYSQL_PINMT_W", "pinmt_w");
define("MYSQL_MOTINDEX_W", "motindex_w");
define("MYSQL_MOTGET_W", "motget_w");
define("MYSQL_MOTSEND_W", "motsend_w");
define("MYSQL_MSENDDP_MOT_W", "msenddp_mot_w");
define("MYSQL_CHATBOX_W", "intranet_w");
define("MYSQL_INTRANET_W", "intranet_w");
define("MYSQL_INSTACONNECT_W", "instaconnect_w");
define("MYSQL_INTERNAL_W", "internal_w");
define("MYSQL_LOGSCHECKER_W", "logschecker_w");
define("MYSQL_ANTON_MALOLOS_W", "anton_malolos_w");

/* DRBD READ USERS */
define("MYSQL_INDEX_R", "index_r");
define("MYSQL_MGET_R", "mget_r");
define("MYSQL_OINDEX_R", "oindex_r");
define("MYSQL_OMGET_R", "omget_r");
define("MYSQL_TEXT2WIN_R", "text2win_r");
define("MYSQL_SUBSCRIPTIONS_R", "subscriptions_r");
define("MYSQL_INDEX_PROCESS_R", "index_process_r");
define("MYSQL_BINRAW_R", "binraw_r");
define("MYSQL_TOUCHMAPS_R", "touchmaps_r");
define("MYSQL_WSMDRRAW_R", "wsmdrraw_r");
define("MYSQL_MORAW_R", "moraw_r");
define("MYSQL_MGET_PROCESS_R", "mget_process_r");
define("MYSQL_MSENDDPRAW_R", "msenddpraw_r");
define("MYSQL_OMSENDRAW_R", "omsendraw_r");
define("MYSQL_MSEND_SRM_R", "msend_srm_r");
define("MYSQL_MSEND_OIOO_R", "msend_oioo_r");
define("MYSQL_MSEND_USC_R", "msend_usc_r");
define("MYSQL_MSENDSI_R", "msendsi_r");
define("MYSQL_PSMS_DS_REPORT_R", "PSMS_ds_report_r");
define("MYSQL_OFFLINEBILL_R", "offlinebill_r");
define("MYSQL_MSENDDP_R", "msenddp_r");
define("MYSQL_MSEND_R", "msend_r");
define("MYSQL_CDR_R", "cdr_r");
define("MYSQL_GEODATA_R", "geodata_r");
define("MYSQL_SCHED_R", "scheduler_r");
define("MYSQL_ADC_R", "adc_r");
define("MYSQL_MSENDDPWAP_R", "msenddpwap_r");
define("MYSQL_SEND_PRV_R", "send_prv_r");
define("MYSQL_TTS_R", "send_tts_r");
define("MYSQL_MSEND_OPPC_R", "msend_oppc_r");
define("MYSQL_MAILER_R", "mailer_r");
define("MYSQL_OMSEND_R", "omsend_r");
define("MYSQL_OMDR_R", "omdr_r");
define("MYSQL_OMDRRC_R", "omdrrc_r");
define("MYSQL_MSENDDP_GLMT_R", "msenddp_glmt_r");
define("MYSQL_MSENDDP_L87_R", "msenddp_l87_r");
define("MYSQL_MSENDDP_OM_R", "msenddp_om_r");
define("MYSQL_MSEND_GLMT_R", "msend_glmt_r");
define("MYSQL_MSEND_L87_R", "msend_l87_r");
define("MYSQL_PINMT_R", "pinmt_r");
define("MYSQL_MOTINDEX_R", "motindex_r");
define("MYSQL_MOTGET_R", "motget_r");
define("MYSQL_MOTSEND_R", "motsend_r");
define("MYSQL_MSENDDP_MOT_R", "msenddp_mot_r");
define("MYSQL_CHATBOX_R", "intranet_r");
define("MYSQL_INTRANET_R", "intranet_r");
define("MYSQL_INSTACONNECT_R", "instaconnect_r");
define("MYSQL_INTERNAL_R", "internal_r");
define("MYSQL_LOGSCHECKER_R", "logschecker_r");
define("MYSQL_ANTON_MALOLOS_R", "anton_malolos_r");
define("MYSQL_QUERY_BUILDER_R", "query_builder_r");

if (!defined('HTTP_CRLF')) define('HTTP_CRLF', chr(13) . chr(10));
?>
