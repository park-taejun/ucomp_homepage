<?session_start();?>
<?
# =============================================================================
# File Name    : expense_dml.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-18
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
	$menu_right = "EX001"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header_mobile.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/new_vacation/new_vacation.php";
	require "../../_classes/biz/expense/expense.php";

#====================================================================
# DML Process
#====================================================================
	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ex_no			= $_POST['ex_no']!=''?$_POST['ex_no']:$_GET['ex_no'];
	$file_nm		= $_POST['file_nm']!=''?$_POST['file_nm']:$_GET['file_nm'];

	if ($mode == "img_del") {
		
		$result = deleteImgFile($conn, $ex_no, $file_nm);
		$filename = "/upload_data/expense/".$file_nm;
		$result_1  = FILEDEL( $filename );

		//$result = true;

		if ($result){
	?>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<script language="javascript">
			 alert("이미지가 삭제 되었습니다!");
			 parent.location.href="expense_write.php?mode=S&ex_no=<?=$ex_no?>";
			</script>

	<?
		} else {
	?>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<script language="javascript">
			 alert("이미지가 존재하지 않습니다!");
			 location.href="expense_write.php?mode=S&ex_no=<?=$ex_no?>";
			</script>

	<?
		}
	}

	if ($mode == "D") {
		$result = deleteExpense($conn, $ex_no);
		$result = deleteExpenseDate($conn, $ex_no);
		$result = deleteImg($conn, $ex_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "모바일 지출 승인 삭제 (관리자번호 : ".(int)$adm_no.")", "Delete");
		echo "T";

	}
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>