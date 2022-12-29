<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : partner_modify.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-14
# Modify Date  : 
#	Copyright    : Copyright @Ucom Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "CO003"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";
 
#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";	
	require "../../_classes/biz/partner/partner.php";

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	 
	$img_gubun	= $_POST['img_gubun']!=''?$_POST['img_gubun']:$_GET['img_gubun'];
	
	$result = imageDelete($conn, $partner_no, $img_gubun);		 
	
	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_eq_type=".$con_eq_type."&con_eq_user=".$con_eq_user."&con_eq_user_name=".$con_eq_user_name."&con_eq_state=".$con_eq_state."&search_field=".$search_field."&search_str=".$search_str;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "partner_list.php<?=$strParam?>";
</script>
<?
		exit;
	}	
?>   

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>