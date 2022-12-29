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
	$menu_right = "PM002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	$m_no						= trim($m_no);
	$cms_amount			= trim($cms_amount);
	$sel_year				= trim($sel_year);
	$sel_month			= trim($sel_month);
	$res_cms_amount	= trim($res_cms_amount);
	$pay_memo				= trim($pay_memo);
	$res_pay_date		= trim($res_pay_date);
	$pay_reason			= trim($pay_reason);

	$result = false;
#====================================================================
# DML Process
#====================================================================


	if ($mode == "I") {

	//	$pay_reason		= "당비";
		$pay_type	= "D";

		$result = insertDirectPaymentInfo($conn, $m_no, $pay_reason, $sel_year, $sel_month, $cms_amount, $pay_type, $res_cms_amount, $pay_memo, $res_pay_date, $s_adm_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "직접 당비 납부 등록", "Insert");

		if ($result) {
			echo "T";
		} else {
			echo "F";
		}
	}

	if ($mode == "U") {

		$result = updateDirectPaymentInfo($conn, $seq_no, $sel_year, $sel_month, $res_cms_amount, $pay_memo, $pay_reason, $res_pay_date, $s_adm_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "직접 당비 납부 수정 (".$seq_no.")", "Update");

		if ($result) {
			echo "T";
		} else {
			echo "F";
		}
	}

	if ($mode == "D") {

		$result = deleteDirectPaymentInfo($conn, $seq_no, $s_adm_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "직접 당비 납부 삭제 (".$seq_no.") ", "Delete");

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
