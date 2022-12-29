<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : ajax_login_dml.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2018-12-10
# Modify Date  : 2022-01-11
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/etc/etc.php";  
	require "../_classes/biz/story/story.php";	

#====================================================================
# Request Parameter
#====================================================================
	$tot_val		= trim($_POST['tot_val']);
	 
	$result = listUStoryAdd($conn, $tot_val );
	
	$rs_story_no					= trim($arr_rs[0]["STORY_NO"]);  

	$result = "";

	if ($rs_story_no == "") {
		$result = "1";		
	}
 
	$arr_result = array("result"=>$result, "msg"=>$str_result);
	echo json_encode($arr_result);

	mysql_close($conn);
?>