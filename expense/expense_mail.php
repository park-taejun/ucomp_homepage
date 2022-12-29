<?session_start();?>
<?
# =============================================================================
# File Name    : expense_mail.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-01-28
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
	$menu_right = "EX002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	include "../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/admin_ptj/admin.php";	
	require "../_classes/biz/expense/expense.php";
	require "../../approval/approval_mailform.php";
	
	
	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];
	$year	 = "202206";

	// $mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$email				= $_POST['email']!=''?$_POST['email']:$_GET['email'];
	// $leader_no			= $_POST['leader_no']!=''?$_POST['leader_no']:$_GET['leader_no'];

	//$adm_name			= selectAdminName($conn, $s_adm_no);
	//$leader_email		= selectAdminEmail2($conn, $leader_no);
	
	$leader_email		= "cadt@ucomp.co.kr";
	
	
	echo "s_adm_id : ".$s_adm_id."<br />";
	echo "s_adm_no : ".$s_adm_no."<br />";
	echo "year : ".$year."<br />";
	echo "email : ".$email."<br />";
	echo "leader_email : ".$leader_email."<br />";

	//mail start
	if ($s_adm_no <> "25"){ 
		$SUBJECT	= "지출결의 결재 승인을 기다리고 있습니다(테스트 베타 버전)";
		$mailto		= "cadt@ucomp.co.kr";
		//$EMAIL		= $email;
		$EMAIL		= "cadt@ucomp.co.kr";
		$NAME		= "유컴패니온 ".$adm_name;
		$CONTENT  = $mail_string;

		$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 
	}
	
	//mail end

	$result = "T";

	echo $result;

#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>