<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : member_read.php
# Modlue       : 
# Writer       : GIRINGRIM 
# Create Date  : 2016-04-06
# Modify Date  : 
#	Copyright : Copyright @GIRINGRIM.Com. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "MB002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";	
	
#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/group/group.php";
	
	$str				= trim($str);
	$party_val	= trim($party_val);
	
	$arr_rs= listGroup($conn, $party_val, $group_cd, "Y", "N", "GROUP_NAME", $str);

	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$GROUP_NO			= trim($arr_rs[$j]["GROUP_NO"]);
			$GROUP_NAME		= trim($arr_rs[$j]["GROUP_NAME"]);
			$GROUP_CD			= trim($arr_rs[$j]["GROUP_CD"]);
			$GROUP_SIDO		= trim($arr_rs[$j]["GROUP_SIDO"]);

			$arr[$j] = array("label"=>$GROUP_NAME ." [".$GROUP_SIDO."] ".$GROUP_CD, "value"=>$GROUP_NAME, "group_name"=>$GROUP_NAME, "group_no"=>$GROUP_NO, "group_cd"=>$GROUP_CD); 

			//echo $ADM_NAME."<br>";

		}
	}
	

	echo json_encode($arr); 

	mysql_close($conn);
?>