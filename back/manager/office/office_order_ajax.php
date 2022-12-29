<?session_start();?>
<?
# =============================================================================
# File Name    : goods_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright : Copyright @C&C Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PE004"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/office/office.php";

	//$arr_rs = getSiteInfo($conn, $site_no);
	$mode				= trim($mode);
	$con_type		= trim($con_type);
	$office_no	= trim($office_no);
	$order_type	= trim($order_type);

	$del_tf = "N";

#====================================================================
# DML Process
#====================================================================

	if ($mode == "O") {
		$result = updateOrderOfficeSeq($conn, $con_type, $con_use_tf, $del_tf, $order_type, $office_no);
	}

#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>