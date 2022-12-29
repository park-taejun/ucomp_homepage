<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : admin_write.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong 
# Create Date  : 2018-12-11
# Modify Date  : 2021-11-18
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "HD001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";

	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$adm_no					= $_POST['adm_no']!=''?$_POST['adm_no']:$_GET['adm_no'];
	$old_adm_id				= $_POST['old_adm_id']!=''?$_POST['old_adm_id']:$_GET['old_adm_id'];
	$adm_id					= $_POST['adm_id']!=''?$_POST['adm_id']:$_GET['adm_id'];
	$adm_name				= $_POST['adm_name']!=''?$_POST['adm_name']:$_GET['adm_name'];
	$adm_info				= $_POST['adm_info']!=''?$_POST['adm_info']:$_GET['adm_info'];
	$passwd					= $_POST['passwd']!=''?$_POST['passwd']:$_GET['passwd'];
	$passwd_chk				= $_POST['passwd_chk']!=''?$_POST['passwd_chk']:$_GET['passwd_chk'];
	$adm_hphone				= $_POST['adm_hphone']!=''?$_POST['adm_hphone']:$_GET['adm_hphone'];
	$adm_phone				= $_POST['adm_phone']!=''?$_POST['adm_phone']:$_GET['adm_phone'];
	$adm_email				= $_POST['adm_email']!=''?$_POST['adm_email']:$_GET['adm_email'];
	$adm_birthday			= $_POST['adm_birthday']!=''?$_POST['adm_birthday']:$_GET['adm_birthday'];  //2021.08.10 생일추가
	$adm_zip				= $_POST['adm_zip']!=''?$_POST['adm_zip']:$_GET['adm_zip'];  //2022-01-04 주소 추가
	$adm_addr				= $_POST['adm_addr']!=''?$_POST['adm_addr']:$_GET['adm_addr'];  //2022-01-04 주소 추가

	$adm_flag				= $_POST['adm_flag']!=''?$_POST['adm_flag']:$_GET['adm_flag'];
	$position_code			= $_POST['position_code']!=''?$_POST['position_code']:$_GET['position_code'];
	$occupation_code		= $_POST['occupation_code']!=''?$_POST['occupation_code']:$_GET['occupation_code'];
	$dept_code				= $_POST['dept_code']!=''?$_POST['dept_code']:$_GET['dept_code'];
	$group_no				= $_POST['group_no']!=''?$_POST['group_no']:$_GET['group_no'];

// 전자 결재을 위한 필드 추가 2021-02-02
	$headquarters_code		= $_POST['headquarters_code']!=''?$_POST['headquarters_code']:$_GET['headquarters_code'];
	$leader_yn				= $_POST['leader_yn']!=''?$_POST['leader_yn']:$_GET['leader_yn'];
	$level					= $_POST['level']!=''?$_POST['level']:$_GET['level'];
	$dept_unit_name			= $_POST['dept_unit_name']!=''?$_POST['dept_unit_name']:$_GET['dept_unit_name'];
//

// 출퇴근 시간 추가 2021-09-28
	$commute_time			= $_POST['commute_time']!=''?$_POST['commute_time']:$_GET['commute_time'];
//
	$use_tf					= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$con_use_tf				= $_POST['con_use_tf']!=''?$_POST['con_use_tf']:$_GET['con_use_tf'];
	$group_cd				= $_POST['group_cd']!=''?$_POST['group_cd']:$_GET['group_cd'];
	$flag01					= $_POST['flag01']!=''?$_POST['flag01']:$_GET['flag01'];
	$old_profile			= $_POST['old_profile']!=''?$_POST['old_profile']:$_GET['old_profile'];

// 조직도 연도 추가
	$yyyy					= $_POST['yyyy']!=''?$_POST['yyyy']:$_GET['yyyy'];
	$mode_yyyy				= $_POST['mode_yyyy']!=''?$_POST['mode_yyyy']:$_GET['mode_yyyy'];  //연도확인용
	//$rd_year				= $_POST['rd_year']!=''?$_POST['rd_year']:$_GET['rd_year'];

	if ($yyyy =="") $yyyy="202206"; //2021, 2022 선택 제거 후

	$mode_yyyy	 = "202206"; //조직도 개편 후

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	$adm_name	= SetStringToDB($adm_name);
	$adm_info	= SetStringToDB($adm_info);

	#echo $adm_no;

#====================================================================
	$savedir1 = $g_physical_path."upload_data/profile";
#====================================================================

	if ($mode == "I") {

		$result_flag = dupAdmin($conn, $adm_id);

		if(empty($adm_flag)) $adm_flag="Y";
		
		if ($result_flag == 0) {

			$profile		= upload($_FILES[profile], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			
			$passwd_enc = encrypt($key, $iv, $passwd);
			
			$arr_data = array("ADM_ID"=>$adm_id,
												"PASSWD"=>$passwd_enc,
												"ADM_NAME"=>$adm_name,
												"ADM_INFO"=>$adm_info,
												"ADM_HPHONE"=>$adm_hphone,
												"ADM_PHONE"=>$adm_phone,
												"ADM_EMAIL"=>$adm_email,
												"ADM_BIRTHDAY"=>$adm_birthday,
												"ADM_ZIP"=>$adm_zip,
												"ADM_ADDR"=>$adm_addr,
												"GROUP_NO"=>$group_no,
												"ADM_FLAG"=>$adm_flag,
												"POSITION_CODE"=>$position_code,
												"OCCUPATION_CODE"=>$occupation_code,
												"DEPT_CODE"=>$dept_code,
												"COM_CODE"=>$com_code,
												"HEADQUARTERS_CODE"=>$headquarters_code,
												"LEADER_YN"=>$leader_yn,
												"LEADER_TITLE"=>$leader_title,
												"LEVEL"=>$level,
												"DEPT_UNIT_NAME"=>$dept_unit_name,
												"ENTER_DATE"=>$enter_date,
												"OUT_DATE"=>$out_date,
												"PROFILE"=>$profile,
												"COMMUTE_TIME"=>$commute_time,
												"USE_TF"=>$use_tf,
												"REG_ADM"=>$_SESSION['s_adm_no']
											);

			$result =  insertAdmin($conn, $arr_data);

			//2022은 admin_info 테이블과 조직도 테이블에도 업데이트
			$arr_data_org = array("ADM_NO"=>$result,
												"YEAR"=>$yyyy,
												"POSITION_CODE"=>$position_code,
												"OCCUPATION_CODE"=>$occupation_code,
												"DEPT_CODE"=>$dept_code,
												"HEADQUARTERS_CODE"=>$headquarters_code,
												"LEADER_YN"=>$leader_yn,
												"LEADER_TITLE"=>$leader_title,
												"LEVEL"=>$level,
												"DEPT_UNIT_NAME"=>$dept_unit_name,
												"USE_TF"=>$use_tf,
												"REG_ADM"=>$_SESSION['s_adm_no']
											);

			$result =  insertOrgAdd($conn, $arr_data_org);

			//분기별 출퇴근 시간 추가 2022-06-07
			/*
			$n = ceil(date("n") / 3);
			$arr_data_commute = array("VA_USER"=>$result,
													"YYYY"=>$yyyy,
													"QUARTER"=>$n,
													"COMMUTE_TIME"=>$commute_time,
													"USE_TF"=>$use_tf,
													"REG_ADM"=>$_SESSION['s_adm_no']
												);

			$result =  insertCommuteTime($conn, $arr_data_commute);
			*/
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 등록 (관리자 아이디 : ".$adm_id.") ", "Insert");
		
		} else {
?>	
<script language="javascript">
		alert('사용중인 ID 입니다.');
		document.location.href = "admin_write.php";
</script>
<?
		exit;
		}

	}

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$profile		= upload($_FILES[profile], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
				$_SESSION['s_adm_profile']	= $profile;
			break;
			case "keep" :
				$profile		= $old_profile;
			break;
			case "delete" :
				$profile		= "";
			break;
			case "update" :
				$profile		= upload($_FILES[profile], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
				$_SESSION['s_adm_profile']	= $profile;
			break;
		}

		if ($old_adm_id <> $adm_id) {
			$result_flag = dupAdmin($conn, $adm_id);
		}

		if ($result_flag == 0) {

			$passwd_enc = encrypt($key, $iv, $passwd);

			if(empty($adm_flag)) $adm_flag="Y";

			$arr_data = array("ADM_ID"=>$adm_id,
												"ADM_NAME"=>$adm_name,
												"ADM_INFO"=>$adm_info,
												"ADM_HPHONE"=>$adm_hphone,
												"ADM_PHONE"=>$adm_phone,
												"ADM_EMAIL"=>$adm_email,
												"ADM_BIRTHDAY"=>$adm_birthday,
												"ADM_ZIP"=>$adm_zip,
												"ADM_ADDR"=>$adm_addr,
												"OCCUPATION_CODE"=>$occupation_code,
												"GROUP_NO"=>$group_no,
												"ADM_FLAG"=>$adm_flag,
												"COM_CODE"=>$com_code,
												"ENTER_DATE"=>$enter_date,
												"OUT_DATE"=>$out_date,
												"PROFILE"=>$profile,
												"COMMUTE_TIME"=>$commute_time,
												"USE_TF"=>$use_tf,
												"UP_ADM"=>$_SESSION['s_adm_no']
/*
												"POSITION_CODE"=>$position_code,
												"DEPT_CODE"=>$dept_code,
												"HEADQUARTERS_CODE"=>$headquarters_code,
												"LEADER_YN"=>$leader_yn,
												"LEADER_TITLE"=>$leader_title,
												"LEVEL"=>$level,
												"DEPT_UNIT_NAME"=>$dept_unit_name,
*/
											);

			//2021부터 admin_info 테이블과 조직도 테이블 분리해서 관리 _ 업데이트
			$arr_data_org = array("POSITION_CODE"=>$position_code,
														"OCCUPATION_CODE"=>$occupation_code,
														"DEPT_CODE"=>$dept_code,
														"HEADQUARTERS_CODE"=>$headquarters_code,
														"LEADER_YN"=>$leader_yn,
														"LEADER_TITLE"=>$leader_title,
														"LEVEL"=>$level,
														"DEPT_UNIT_NAME"=>$dept_unit_name,
														"USE_TF"=>$use_tf
													);

			$result = updateAdmin($conn, $arr_data, $adm_no);
			$result = updateOrg($conn, $arr_data_org, $adm_no, $mode_yyyy);
			$result = updateApp($conn, $adm_no, $use_tf);

			//분기별 출퇴근 시간 추가 2022-06-07
			/*
			$n = ceil(date("n") / 3);
			$arr_data_commute = array("YYYY"=>$yyyy,
													"QUARTER"=>$n,
													"COMMUTE_TIME"=>$commute_time,
													"USE_TF"=>$use_tf,
												);

			$result = updateCommuteTime($conn, $arr_data_commute, $adm_no, $mode_yyyy, $n);
			*/
/*
			if ($rd_year == "2021") {
					$result = updateAdmin($conn, $arr_data_org, $adm_no);
					$result = updateOrg($conn, $arr_data_org, $adm_no, $rd_year);
			} else if ($rd_year == "2022") {
					$result = updateOrg($conn, $arr_data_org, $adm_no, $rd_year);
			}
*/

			if($passwd_chk=="Y") updateAdminPwd($conn, $passwd_enc, $s_adm_no, (int)$adm_no);

		} else {
?>	
<script language="javascript">
		alert('사용중인 ID 입니다.');
		document.location.href = "admin_write.php?mode=S&adm_no=<?=$adm_no?>";
</script>
<?
		exit;
		}
	}

	if ($mode == "D") {
		
		$result = deleteAdmin($conn, $s_adm_no, (int)$adm_no);
		$result = deleteOrg($conn, $s_adm_no, (int)$adm_no, $mode_yyyy);
		$result = deleteApp($conn, $s_adm_no, (int)$adm_no);
		//$n = ceil(date("n") / 3);
		//$result = deleteCommuteTime($conn, $s_adm_no, (int)$adm_no, $mode_yyyy, $n);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 삭제 처리 (관리자 번호 : ".(int)$adm_no.") ", "Delete");
	}

	if ($mode == "S") {

		$arr_rs = selectAdmin($conn, $adm_no);

		//ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, 
		//GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE

		$rs_adm_no					= trim($arr_rs[0]["ADM_NO"]); 
		$rs_adm_id					= trim($arr_rs[0]["ADM_ID"]); 
		$rs_passwd					= trim($arr_rs[0]["PASSWD"]); 
		$rs_adm_name				= SetStringFromDB($arr_rs[0]["ADM_NAME"]); 
		$rs_adm_info				= SetStringFromDB($arr_rs[0]["ADM_INFO"]); 
		$rs_adm_hphone				= trim($arr_rs[0]["ADM_HPHONE"]); 
		$rs_adm_phone				= trim($arr_rs[0]["ADM_PHONE"]); 
		$rs_adm_email				= trim($arr_rs[0]["ADM_EMAIL"]); 
		$rs_adm_birthday			= trim($arr_rs[0]["ADM_BIRTHDAY"]); 
		$rs_adm_zip					= trim($arr_rs[0]["ADM_ZIP"]); 
		$rs_adm_addr				= SetStringFromDB($arr_rs[0]["ADM_ADDR"]); 

		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]); 
		$rs_adm_flag				= trim($arr_rs[0]["ADM_FLAG"]); 
		$rs_position_code			= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_occupation_code			= trim($arr_rs[0]["OCCUPATION_CODE"]); 
		$rs_dept_code				= trim($arr_rs[0]["DEPT_CODE"]); 
		$rs_com_code				= trim($arr_rs[0]["COM_CODE"]); 

// 2021-02-02 전자결재 후 추가된 부분
		$rs_enter_date				= trim($arr_rs[0]["ENTER_DATE"]); 
		$rs_out_date				= trim($arr_rs[0]["OUT_DATE"]); 
		$rs_commute_time			= trim($arr_rs[0]["COMMUTE_TIME"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_profile					= trim($arr_rs[0]["PROFILE"]); 

/*
		$rs_headquarters_code		= trim($arr_rs[0]["HEADQUARTERS_CODE"]); 
		$rs_leader_yn				= trim($arr_rs[0]["LEADER_YN"]); 
		$rs_leader_title			= trim($arr_rs[0]["LEADER_TITLE"]);
		$rs_level					= trim($arr_rs[0]["LEVEL"]); 
		$rs_dept_unit_name			= trim($arr_rs[0]["DEPT_UNIT_NAME"]); 
*/

//		if ($mode_yyyy == "2022"){
		$arr_rs_org = selectOrg($conn, $adm_no, $mode_yyyy);

		$rs_headquarters_code		= trim($arr_rs_org[0]["HEADQUARTERS_CODE"]); 
		$rs_dept_code				= trim($arr_rs_org[0]["DEPT_CODE"]); 
		$rs_position_code			= trim($arr_rs_org[0]["POSITION_CODE"]); 
		$rs_leader_yn				= trim($arr_rs_org[0]["LEADER_YN"]); 
		$rs_leader_title			= trim($arr_rs_org[0]["LEADER_TITLE"]);
		$rs_level					= trim($arr_rs_org[0]["LEVEL"]); 
		$rs_dept_unit_name			= trim($arr_rs_org[0]["DEPT_UNIT_NAME"]); 
//		}

	}

//조직도 연도 추가

	if ($mode == "ADD") {

			$arr_data = array("ADM_NO"=>$adm_no,
												"YEAR"=>$yyyy,
												"POSITION_CODE"=>$position_code,
												"OCCUPATION_CODE"=>$occupation_code,
												"DEPT_CODE"=>$dept_code,
												"HEADQUARTERS_CODE"=>$headquarters_code,
												"LEADER_YN"=>$leader_yn,
												"LEADER_TITLE"=>$leader_title,
												"LEVEL"=>$level,
												"DEPT_UNIT_NAME"=>$dept_unit_name,
												"USE_TF"=>$use_tf,
												"REG_ADM"=>$_SESSION['s_adm_no']
											);

			$result =  insertOrgAdd($conn, $arr_data);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 조직도 연도 등록 (관리자 아이디 : ".$adm_id.") ", "Insert");
?>	
<script language="javascript">
		alert('추가되었습니다.');
		document.location.href = "admin_write_test.php?adm_no=<?=$adm_no?>&mode=S";
</script>
<?
		exit;

	}

	if ($rs_event_from == "") {
		$rs_event_from = date("Y-m-d",strtotime("0 day"));
	}

	if ($rs_event_to == "") {
		$rs_event_to = date("Y-m-d",strtotime("0 day"));
	}

	if ($rs_event_result == "") {
		$rs_event_result = date("Y-m-d",strtotime("0 day"));
	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;
?>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "admin_list.php<?=$strParam?>";
</script>
<?
		exit;
	}	
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript" src="/admin/js/httpRequest.js"></script> <!-- Ajax js -->
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	$(".date").datepicker({
		prevText: "이전달",
		nextText: "다음달",
		monthNames: [ "1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월" ], 
		monthNamesShort: [ "1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월" ], 
		dayNames: [ "일요일","월요일","화요일","수요일","목요일","금요일","토요일" ], 
		dayNamesShort: [ "일","월","화","수","목","금","토" ], 
		dayNamesMin: [ "일","월","화","수","목","금","토" ], 
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd"
		,minDate: new Date(1970, 4-1, 15)	//(연, 월-1, 일)
	//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
	});
	
	$(".date_birthday").datepicker({
		prevText: "이전달",
		nextText: "다음달",
		monthNames: [ "1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월" ], 
		monthNamesShort: [ "1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월" ], 
		dayNames: [ "일요일","월요일","화요일","수요일","목요일","금요일","토요일" ], 
		dayNamesShort: [ "일","월","화","수","목","금","토" ], 
		dayNamesMin: [ "일","월","화","수","목","금","토" ], 
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd",
		minDate: new Date("1965-01-01"),
		maxDate: new Date("2007-12-31"),
		yearRange: "1965:+nn"
	});
});

function js_list() {
	var frm = document.frm;
		
	frm.method = "get";
	frm.action = "admin_list.php";
	frm.submit();
}

/*
function js_add() { //신규조직도용 추가

	$("#y2021").css("display", "none");
	$("#y_add").css("display", "block");
	$('input[name="rd_year"]').prop("checked", false);

}
*/

function js_org_save(){

		frm.mode.value = "ADD";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF].$strParam?>";
		frm.submit();

}


function js_save() {

	var frm = document.frm;
	var adm_no = "<?= $adm_no ?>";
	
	frm.adm_name.value = frm.adm_name.value.trim();
	frm.adm_id.value = frm.adm_id.value.trim();
	frm.passwd.value = frm.passwd.value.trim();

	if (frm.group_no.value == "") {
		alert('관리자 그룹을 선택해주세요.');
		frm.group_no.focus();
		return ;		
	}

	if (frm.cp_type.value == "") {
		alert('소속 업체를 선택해주세요.');
		frm.cp_type.focus();
		return ;		
	}
	
	frm.com_code.value = frm.cp_type.value;

	if (isNull(frm.adm_name.value)) {
		alert('이름을 입력해주세요.');
		frm.adm_name.focus();
		return ;		
	}

	if (isNull(frm.adm_id.value)) {
		alert('아이디을 입력해주세요.');
		frm.adm_id.focus();
		return;
	}
	
	if (adm_no) { 
		if (frm.passwd_chk.checked) {
			if (isNull(frm.passwd.value)) {
				alert('비밀번호를 입력해주세요.');
				frm.passwd.focus();
				return ;		
			}
		}
	} else {
		if (isNull(frm.passwd.value)) {
			alert('비밀번호를 입력해주세요.');
			frm.passwd.focus();
			return ;		
		}
	}

	if (frm.commute_time.value == "") {
		alert('출퇴근시간을 선택해주세요.');
		frm.commute_time.focus();
		return ;		
	}

	if (isNull(frm.adm_email.value)) {
		alert('이메일을 입력해주세요.');
		frm.adm_email.focus();
		return;
	}

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (frm.leader_yn.value == "Y" ) {
		if (frm.leader_title.selectedIndex == 0 ) {
			alert("리더의 유형을 선택하세요");
			return ;
		}
	}

	if (isNull(adm_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.adm_no.value = frm.adm_no.value;
	}

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('자료를 삭제 하시겠습니까?');
	
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

}

	// Ajax
function sendKeyword() {

	if (frm.old_adm_id.value != frm.adm_id.value)	{

		var keyword = document.frm.adm_id.value;

		//alert(keyword);
					
		if (keyword != '') {
			var params = "keyword="+encodeURIComponent(keyword);
		
			//alert(params);
			sendRequest("admin_dup_check.php", params, displayResult, 'POST');
		}
		//setTimeout("sendKeyword();", 100);
	} else {
		js_save();
	}
}

function displayResult() {
	
	if (httpRequest.readyState == 4) {
		if (httpRequest.status == 200) {
			
			var resultText = httpRequest.responseText;
			
			var result = resultText;
			
			//alert(result);
			
			//return;
			if (result == "1") {
				alert("사용중인 아이디 입니다.");
				return;
			} else {
				js_save();
			}
		} else {
			alert("에러 발생: "+httpRequest.status);
		}
	}
}

function js_view(t, adm_no) {

	var frm = document.frm;
	
	frm.adm_no.value = adm_no;
	frm.mode.value = "S";
	frm.mode_yyyy.value = t;
	frm.target = "";
	frm.method = "get";
	frm.action = "admin_write.php";
	frm.submit();
	
}

function js_fileView(obj,idx) {
	
	var frm = document.frm;
	
	if (idx == 01) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change").style.display = "inline";
		} else {
			document.getElementById("file_change").style.display = "none";
		}
	}
	if (idx == 02) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change2").style.display = "inline";
		} else {
			document.getElementById("file_change2").style.display = "none";
		}
	}
}

	function sample6_execDaumPostcode(a) {
		new daum.Postcode({
			oncomplete: function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

				// 각 주소의 노출 규칙에 따라 주소를 조합한다.
				// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
				var fullAddr = ''; // 최종 주소 변수
				var extraAddr = ''; // 조합형 주소 변수

				// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
				if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
					fullAddr = data.roadAddress;

				} else { // 사용자가 지번 주소를 선택했을 경우(J)
					fullAddr = data.jibunAddress;
				}

				// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
				if(data.userSelectedType === 'R'){
					//법정동명이 있을 경우 추가한다.
					if(data.bname !== ''){
						extraAddr += data.bname;
					}
					// 건물명이 있을 경우 추가한다.
					if(data.buildingName !== ''){
						extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
					fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				if (a == "O") {
					document.getElementById('cp_zip').value = data.zonecode; //5자리 새우편번호 사용
					document.getElementById('cp_addr').value = fullAddr;
					// 커서를 상세주소 필드로 이동한다.
					document.getElementById('cp_addr').focus();
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				if (a == "R") {
					document.getElementById('adm_zip').value = data.zonecode; //5자리 새우편번호 사용
					document.getElementById('adm_addr').value = fullAddr;
					// 커서를 상세주소 필드로 이동한다.
					document.getElementById('adm_addr').focus();
				}

			}
		}).open();
	}

</script>

</head>
<body>

<div id="wrap">
<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>
	<!-- S: container -->
	<div class="container">
		<div class="contentsarea">
			<div class="menu-holiday">
				<h3><strong><?=$p_menu_name?></strong>
				</h3>

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="seq_no" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="adm_no" value="<?=$adm_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="con_group_no" value="<?=$con_group_no?>">
<input type="hidden" name="con_com_code" value="<?=$con_com_code?>">
<input type="hidden" name="con_dept_code" value="<?=$con_dept_code?>">
<input type="hidden" name="con_position_code" value="<?=$con_position_code?>">
<input type="hidden" name="con_use_tf" value="<?=$con_use_tf?>">

<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">
<input type="hidden" name="mode_yyyy" value="202206">


				<div class="boardwrite">
					<table>
						<colgroup>
							<col style="width:15%" />
							<col style="width:35%" />
							<col style="width:15%" />
							<col style="width:35%" />
						</colgroup>
						<tbody>
							<? if ($s_adm_cp_type <> "운영") { ?>
							<input type="hidden" name="group_no" value="<?=$rs_group_no?>">
							<tr>
								<th>소속 업체</th>
								<td colspan="3">
									<?= getCompanyName($conn, $rs_com_code);?>
									<input type="hidden" name="cp_type" value="<?=$rs_com_code?>">
									<input type="hidden" name="com_code" value="<?=$rs_com_code?>">
								</td>
							</tr>
							<? } else { ?>
							
							<tr>							
								<th>사용자 그룹</th>
								<td>
									<span class="optionbox">
										<?= makeAdminGroupSelectBox($conn, "group_no" , "125px", "사용자 그룹 선택", "", $rs_group_no); ?>
									</span>
								</td>
								<th>소속 업체</th>
								<td>
									<span class="optionbox">
										<?= makeCompanySelectBox($conn, '', $rs_com_code);?>
									</span>
									<input type="hidden" name="com_code" value="<?=$rs_com_code?>">
								</td>
							</tr>
							
							<? } ?>
							<!--
							<tr>
								<th>이름</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:35%" name="adm_name" value="<?=$rs_adm_name?>" /></span></td>
								<th>아이디</th>
								<td>
									<span class="inpbox"><input type="text" class="txt" style="width:35%" name="adm_id" value="<?=$rs_adm_id?>" /></span>
									<input type="hidden" name="old_adm_id" value="<?=$rs_adm_id?>">
								</td>
							</tr>
							<tr>
								<th>비밀번호</th>
								<td>
									<? if ($s_adm_cp_type <> "운영") { ?>
									<span class="inpbox" style="width:50%"><input type="password" class="txt" name="passwd" value="" autocomplete="off" /></span>
									<? } else { ?>
									<span class="inpbox" style="width:50%"><input type="text" class="txt" name="passwd" value="" autocomplete="off"/></span>
									<? } ?>
									<? if ($adm_no) { ?>
									<span class="ickbox"><INPUT TYPE="checkbox" NAME="passwd_chk" value="Y" class="check"><label>비밀번호 변경</label></span>
									<? } ?>
								</td>
								<th>직군</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"OCCUPATION","occupation_code","125px","선택","",$rs_occupation_code)?>
									</span>
								</td>
							</tr>
							-->
							<tr>
								<th>직군</th>
								<td colspan="3">
									<span class="optionbox">
										<?= makeSelectBox($conn,"OCCUPATION","occupation_code","125px","선택","",$rs_occupation_code)?>
									</span>
								</td>
							</tr>
							<tr>
								<th>프로필</th>
								<td>
									<? if ($rs_profile) { ?>
									<span class="optionbox" style="display:inline-block; width:70px;">
										<select name="flag01" onchange="javascript:js_fileView(this,'01')">
											<option value="keep">유지</option>
											<option value="delete">삭제</option>
											<option value="update">수정</option>
										</select>
									</span>
									<input type="hidden" name="old_profile" value="<?= $rs_profile?>">
									<span id="file_change" style="display:none;"><input type="file" style="width:35%" name="profile" value="<?=$rs_profile?>" /></span>
									<? } else { ?>
									<input type="file" class="txt" style="width:35%" name="profile" value="<?=$rs_profile?>" />
									<input type="hidden" name="old_profile" value="">
									<input TYPE="hidden" name="flag01" value="insert">
									<? } ?>
								</td>
								<th>출퇴근시간</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"COMMUTE_TIME","commute_time","125px","선택","",$rs_commute_time)?></span></td>
							</tr>
							<!--
							<tr>
								<th>전화번호</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:35%" name="adm_phone" value="<?=$rs_adm_phone?>" onkeyup="return isPhoneNumber(this)" /></span></td>
								<th>휴대전화번호</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:35%" name="adm_hphone" value="<?=$rs_adm_hphone?>" onkeyup="return isPhoneNumber(this)" /></span></td>
							</tr>
							-->
							<tr>
								<!--
								<th>이메일</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:85%" name="adm_email" value="<?=$rs_adm_email?>" /></span></td>
								-->
								<th>생년월일</th>
								<td colspan="3"><span class="inpbox"><input type="text" class="txt date_birthday" style="width:45%" name="adm_birthday" value="<?=$rs_adm_birthday?>" readonly /></span></td>
							</tr>
							<tr>
								<th>입사일</th>
								<td><span class="inpbox"><input type="text" class="txt date" style="width:35%" name="enter_date" value="<?=$rs_enter_date?>" readonly /></span></td>
								<th>퇴사일</th>
								<td><span class="inpbox"><input type="text" class="txt date" style="width:35%" name="out_date" value="<?=$rs_out_date?>" readonly /></span></td>
							</tr>
							<tr>
								<th>주소</th>
								<td colspan="3" class="address">
									<span class="inpbox post"><input type="Text" name="adm_zip" id="adm_zip"  value="<?= $rs_adm_zip?>" maxlength="7" class="txt"></span>
									<span class="inpbox address"><input type="Text" name="adm_addr" id="adm_addr" value="<?= $rs_adm_addr?>" class="txt"></span>
									<button type="button" class="btn-border-white" id="btn_search" onclick="sample6_execDaumPostcode('R')">주소검색</button>
								</td>
							</tr>
							<tr>
								<th>기타메모</th>
								<td colspan="3" class="subject">
									<span class="textareabox">
										<textarea class="txt" cols="100" rows="5" name="adm_info"><?=$rs_adm_info?></textarea>
									</span>
								</td>
							</tr>
							<!--
							<tr>
								<th>사용 여부</th>
								<td colspan="3">
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf == "Y") || ($rs_use_tf == "")) echo "checked"; ?>><label>사용함</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf == "N") echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									</div>
								</td>
							</tr>
							-->
							<!--//2021-02-02 전자결재로 추가 수정된 부분-->
							<tr>
								<th colspan="4"> 조직도 필요사항 <a href="../org/org_2.php" target="_blank" style="color:red;">[조직도 참조]</a></th>
							</tr>
<!--
							<tr>
								<th>사용 연도</th>
								<?
										$arr_add_YN = selectOrgAddYN($conn, $adm_no);  //202206년도 추가 여부
								?>
								<td colspan="3">
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_year" value="202206" <? if (($mode_yyyy == "202206") || ($rs_year == "")) echo "checked"; ?>><label>202206</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_year" value="2022" <? if ($mode_yyyy == "2022") echo "checked"; ?> onClick="js_view(this.value,'<?=$adm_no?>')"><label>2022</label></span>
									<? if ($arr_add_YN <> "1") {?>
										<input type="button" value="202206년도 추가" onclick="js_add()" style="margin-left:30px;">

									<? } else { ?>
										<span class="iradio"><input type="radio" class="radio" name="rd_year" value="2022" <? if (($mode_yyyy == "2022") || ($rs_year == "")) echo "checked"; ?> onClick="js_view(this.value,'<?=$adm_no?>')"><label>2022</label></span>
									<? } ?>
									</div>
								</td>
							</tr>
-->
						</tbody>
					</table>
			<?
/*
			if ($mode_yyyy == "2021") { ?>
					<div id="y2021">
					<table style="border-top:0px">
						<colgroup>
							<col style="width:15%" />
							<col style="width:35%" />
							<col style="width:15%" />
							<col style="width:35%" />
						</colgroup>
						<tbody>
							<tr>
								<th>본부 코드</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"HEADQUARTERS","headquarters_code","125px","선택","",$rs_headquarters_code)?></span></td>
								<th>부서</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"DEPT","dept_code","125px","선택","",$rs_dept_code)?></span></td>
							</tr>
							<tr>
								<th>직급</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"POSITION","position_code","125px","선택","",$rs_position_code)?></span></td>
								<th>직책(레벨)</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="level" value="1" <? if ($rs_level == "1") echo "checked"; ?>><label>경영팀장</label></span>
										<span class="iradio"><input type="radio" class="radio" name="level" value="2" <? if ($rs_level == "2") echo "checked"; ?>><label>이사,본부장</label></span>
										<span class="iradio"><input type="radio" class="radio" name="level" value="3" <? if ($rs_level == "3") echo "checked"; ?>><label>팀장</label></span>
										<span class="iradio"><input type="radio" class="radio" name="level" value="4" <? if (($rs_level == "4") || ($rs_level == "")) echo "checked"; ?>><label>유닛</label></span>
									</div>
								</td>
							</tr>
							<tr>
								<th>리더 여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="leader_yn" value="Y" <? if ($rs_leader_yn == "Y") echo "checked"; ?>><label>예</label></span>
										<span class="iradio"><input type="radio" class="radio" name="leader_yn" value="N" <? if (($rs_leader_yn == "N") || ($rs_leader_yn == "")) echo "checked"; ?> onClick="this.form.leader_title.options[0].selected=true;"><label>아니요</label></span>
										<span class="iradio"><span class="optionbox"><?= makeSelectBox($conn,"LEADER_TITLE","leader_title","50px","선택","",$rs_leader_title)?></span></span>
									</div>
								</td>
								<th>그룹내 유닛명 </th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="A" <? if (($rs_dept_unit_name == "A") || ($rs_dept_unit_name == "")) echo "checked"; ?>><label>A</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="B" <? if ($rs_dept_unit_name == "B") echo "checked"; ?>><label>B</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="C" <? if ($rs_dept_unit_name == "C") echo "checked"; ?>><label>C</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="D" <? if ($rs_dept_unit_name == "D") echo "checked"; ?>><label>D</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="E" <? if ($rs_dept_unit_name == "E") echo "checked"; ?>><label>E</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="F" <? if ($rs_dept_unit_name == "F") echo "checked"; ?>><label>F</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="G" <? if ($rs_dept_unit_name == "G") echo "checked"; ?>><label>G</label></span>
									</div>
								</td>
							</tr>
					</table>
					</div>
			<? 
			} 
*/
//			if (($mode_yyyy == "2022") || ($mode_yyyy == "")) { ?>
				<div class="boardwrite" id="y2022">
					<table style="border-top:0px">
						<colgroup>
							<col style="width:15%" />
							<col style="width:35%" />
							<col style="width:15%" />
							<col style="width:35%" />
						</colgroup>
						<tbody>
							<tr>
								<th>본부 코드</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"HEADQUARTERS_2022","headquarters_code","125px","선택","",$rs_headquarters_code)?></span></td>
								<th>부서</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"DEPT_2022","dept_code","125px","선택","",$rs_dept_code)?></span></td>
							</tr>
							<tr>
								<th>직급</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"POSITION","position_code","125px","선택","",$rs_position_code)?></span></td>
								<th>직책(레벨)</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="level" value="1" <? if ($rs_level == "1") echo "checked"; ?>><label>경영팀장</label></span>
										<span class="iradio"><input type="radio" class="radio" name="level" value="2" <? if ($rs_level == "2") echo "checked"; ?>><label>이사,본부장</label></span>
										<span class="iradio"><input type="radio" class="radio" name="level" value="3" <? if ($rs_level == "3") echo "checked"; ?>><label>부문장</label></span>
										<span class="iradio"><input type="radio" class="radio" name="level" value="4" <? if ($rs_level == "4") echo "checked"; ?>><label>팀장</label></span>
										<span class="iradio"><input type="radio" class="radio" name="level" value="5" <? if (($rs_level == "5") || ($rs_level == "")) echo "checked"; ?>><label>유닛</label></span>
									</div>
								</td>
							</tr>
							<tr>
								<th>리더 여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="leader_yn" value="Y" <? if ($rs_leader_yn == "Y") echo "checked"; ?>><label>예</label></span>
										<span class="iradio"><input type="radio" class="radio" name="leader_yn" value="N" <? if (($rs_leader_yn == "N") || ($rs_leader_yn == "")) echo "checked"; ?> onClick="this.form.leader_title.options[0].selected=true;"><label>아니요</label></span>
										<span class="iradio"><span class="optionbox"><?= makeSelectBox($conn,"LEADER_TITLE","leader_title","50px","선택","",$rs_leader_title)?></span></span>
									</div>
								</td>
								<th>그룹내 유닛명 </th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="A" <? if (($rs_dept_unit_name == "A") || ($rs_dept_unit_name == "")) echo "checked"; ?>><label>A</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="B" <? if ($rs_dept_unit_name == "B") echo "checked"; ?>><label>B</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="C" <? if ($rs_dept_unit_name == "C") echo "checked"; ?>><label>C</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="D" <? if ($rs_dept_unit_name == "D") echo "checked"; ?>><label>D</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="E" <? if ($rs_dept_unit_name == "E") echo "checked"; ?>><label>E</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="F" <? if ($rs_dept_unit_name == "F") echo "checked"; ?>><label>F</label></span>
										<span class="iradio"><input type="radio" class="radio" name="dept_unit_name" value="G" <? if ($rs_dept_unit_name == "G") echo "checked"; ?>><label>G</label></span>
									</div>
								</td>
							</tr>
					</table>
					</div>
				</div>
			<? 
//			}
			?>
			<!--//-->
					<div class="sp20"></div>
					<div class="btnright">
					<? if ($adm_no <> "" ) {?>
						<? if ($sPageRight_U == "Y") {?>
						<button type="button" class="btn-navy" onClick="sendKeyword();" style="width:100px">저장</button>
						<? } ?>
					<? } else {?>
						<? if ($sPageRight_I == "Y") {?>
						<button type="button" class="btn-navy" onClick="sendKeyword();" style="width:100px">저장</button>
						<? } ?>
					<? }?>

					<? if ($s_adm_cp_type == "운영") { ?>
						<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
					<? } ?>

					<? // if ($s_adm_cp_type == "운영") { ?>
					<? if ($adm_no <> "") {?>
						<? if ($sPageRight_D == "Y") {?>
						<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
						<? } ?>
					<? } ?>
					<? // } ?>
					</div>
				</form>
			</div>
		</div>
	</div>

<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/admin/js/common_ui.js"></script>
<script>

</script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>