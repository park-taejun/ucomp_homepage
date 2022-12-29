<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
# =============================================================================
# File Name    : banner_order_dml.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright : Copyright @DSR Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================
//	include "$_SERVER[DOCUMENT_ROOT]/_common/common_header.php"; 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/banner/banner.php";
	

	//$arr_rs = getSiteInfo($conn, $site_no);
	
#====================================================================
# DML Process
#====================================================================

	if ($mode == "O") {
		
		
		$row_cnt = count($banner_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_banner_no = $banner_seq_no[$k];

			$result = updateOrderBanner($conn, $k, $g_site_no, $tmp_banner_no);
		
		}
	}

	mysql_close($conn);
?>
