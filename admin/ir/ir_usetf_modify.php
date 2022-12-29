<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : ir_usetf_modify.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-18
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================

	$menu_right = "IR001"; // 메뉴마다 셋팅 해 주어야 합니다
	//echo $b_code;

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
	echo "3333";
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/brochure/brochure.php";

	$result = updateUseTfBrochure($conn); 
	          
	  
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
