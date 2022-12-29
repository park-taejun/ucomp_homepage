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
	require "../_classes/com/util/ImgUtilResize.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/biz/member/member.php";
	
	$str = trim($str);

	$arr_rs= listMemberAjaxSearch($conn, $str, $party, $sido);
	
	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$M_NO				= trim($arr_rs[$j]["M_NO"]);
			$M_NAME			= trim($arr_rs[$j]["M_NAME"]);
			$M_JUMIN		= trim($arr_rs[$j]["M_JUMIN"]);

			$M_JUMIN		= decrypt($key, $iv, $M_JUMIN);

			$arr[$j] = array("label"=>$M_NAME, "value"=>$M_NO, "ju"=>$M_JUMIN); 

			//echo $ADM_NAME."<br>";

		}
	}
	

	echo json_encode($arr); 

	mysql_close($conn);
?>