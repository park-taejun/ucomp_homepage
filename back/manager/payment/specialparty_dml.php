<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : payment_write.php
# Modlue       : 
# Writer       : GIRINGRIM 
# Create Date  : 2016-04-06
# Modify Date  : 
#	Copyright : Copyright @GIRINGRIM.Com. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PM003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/payment/payment.php";

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$mode						= trim($mode);
	$seq_no					= trim($seq_no);
	$title						= trim($title);
	$memo				= trim($memo);
	$pay_date		= trim($pay_date);

	$result = false;
#====================================================================
# DML Process
#====================================================================


	if ($mode == "I") {

		$result = insertSpeParty($conn, $title, $pay_date, $memo, $s_adm_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별 당비 등록", "Insert");

		if ($result) {
			echo "T";
		} else {
			echo "F";
		}
	}

	if ($mode == "U") {

		$result = updateSpeParty($conn, $seq_no, $title, $pay_date, $memo, $s_adm_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별 당비 수정 (".$seq_no.")", "Update");

		if ($result) {
			echo "T";
		} else {
			echo "F";
		}
	}

	if ($mode == "D") {

		$result = deleteSpeParty($conn, $seq_no, $s_adm_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별 당비 삭제 (".$seq_no.") ", "Delete");

		if ($result) {
			echo "T";
		} else {
			echo "F";
		}
	}

#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>
