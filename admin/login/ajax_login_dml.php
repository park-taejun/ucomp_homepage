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
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/com/etc/etc.php";  
	require "../../_classes/biz/admin/admin.php";

#====================================================================
# Request Parameter
#====================================================================
	$adm_id		= trim($_POST['adm_id']);
	$passwd		= trim($_POST['adm_pw']);
	// $adm_id		= $_POST['adm_id']!=''?$_POST['adm_id']:$_GET['adm_id'];	
	// $passwd		= $_POST['adm_pw']!=''?$_POST['adm_pw']:$_GET['adm_pw'];
/*
	echo " mode : " .$mode. "<br />"; 
echo " adm_id : " .$adm_id. "<br />";
echo " passwd : " .$passwd. "<br />";
*/
// exit;

	if ($mode == "S") {

		$arr_rs = confirmAdmin($conn, $adm_id);


		$rs_adm_no					= trim($arr_rs[0]["ADM_NO"]); 
		$rs_adm_id					= trim($arr_rs[0]["ADM_ID"]); 
		$rs_passwd					= trim($arr_rs[0]["PASSWD"]); 
		$rs_adm_name				= trim($arr_rs[0]["ADM_NAME"]); 
		$rs_adm_email				= trim($arr_rs[0]["ADM_EMAIL"]); 
		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]); 
		$rs_com_code				= trim($arr_rs[0]["COM_CODE"]); 
		$rs_cp_type					= trim($arr_rs[0]["CP_TYPE"]); 
		$rs_position_code			= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_dept_code				= trim($arr_rs[0]["DEPT_CODE"]); 
		$rs_organization			= trim($arr_rs[0]["ORGANIZATION"]); 
		$rs_profile					= trim($arr_rs[0]["PROFILE"]); 

		$rs_adm_type = "admin";
		
		$result = insertUserLog($conn, $rs_adm_type, $rs_adm_id, $_SERVER['REMOTE_ADDR'], '????????? ?????????', "Login");

		$result = "";

		if ($rs_adm_no == "") {
			$result = "1";
			$str_result = "?????? ???????????? ????????????.";
		} else {

			$str_resetpasswd = decrypt($key, $iv, $rs_passwd);			

			if ($passwd == $str_resetpasswd) {
				$result = "0";
				$str_result = "";
			} else {
				$result = "2";
				$str_result = "?????? ????????? ?????? ?????? ????????????.";
			}
		}

		//$result = "0";

		if ($result == "0") {

			$_SESSION['s_is_adm']				= "Y";
			$_SESSION['s_adm_no']				= $rs_adm_no;
			$_SESSION['s_adm_id']				= $rs_adm_id;
			$_SESSION['s_adm_pw']				= $rs_passwd;
			$_SESSION['s_adm_nm']				= $rs_adm_name;
			$_SESSION['s_adm_email']			= $rs_adm_email;
			$_SESSION['s_adm_group_no']			= $rs_group_no;
			$_SESSION['s_adm_com_code']			= $rs_com_code;
			$_SESSION['s_adm_cp_type']			= $rs_cp_type;
			$_SESSION['s_adm_position_code']	= $rs_position_code;		// ????????????
			$_SESSION['s_adm_dept_code']		= $rs_dept_code;			// ?????????
			$_SESSION['s_adm_organization']		= $rs_organization;			// ????????????
			$_SESSION['s_adm_profile']			= $rs_profile;				// ?????????

			// ????????? ????????? ?????? ?????? ??????
			$_SESSION['s_m_no']	= "";
			$_SESSION['s_m_id']	= "";

			$str_result = "";

		}
	} else {
		$result = "3";
		$str_result = "??????????????? ?????? ?????????.";
	}

	
	$arr_result = array("result"=>$result, "msg"=>$str_result);
	echo json_encode($arr_result);

	mysql_close($conn);
?>