<?session_start();?>
<?
# =============================================================================
# File Name    : admin_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD002"; // 메뉴마다 셋팅 해 주어야 합니다

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

#====================================================================
# DML Process
#====================================================================
	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$adm_no						= $_POST['adm_no']!=''?$_POST['adm_no']:$_GET['adm_no'];
	$old_adm_id				= $_POST['old_adm_id']!=''?$_POST['old_adm_id']:$_GET['old_adm_id'];
	$adm_id						= $_POST['adm_id']!=''?$_POST['adm_id']:$_GET['adm_id'];
	$adm_name					= $_POST['adm_name']!=''?$_POST['adm_name']:$_GET['adm_name'];
	$adm_info					= $_POST['adm_info']!=''?$_POST['adm_info']:$_GET['adm_info'];
	$passwd						= $_POST['passwd']!=''?$_POST['passwd']:$_GET['passwd'];
	$passwd_chk				= $_POST['passwd_chk']!=''?$_POST['passwd_chk']:$_GET['passwd_chk'];
	$adm_hphone				= $_POST['adm_hphone']!=''?$_POST['adm_hphone']:$_GET['adm_hphone'];
	$adm_phone				= $_POST['adm_phone']!=''?$_POST['adm_phone']:$_GET['adm_phone'];
	$adm_email				= $_POST['adm_email']!=''?$_POST['adm_email']:$_GET['adm_email'];
	$adm_flag					= $_POST['adm_flag']!=''?$_POST['adm_flag']:$_GET['adm_flag'];
	$position_code		= $_POST['position_code']!=''?$_POST['position_code']:$_GET['position_code'];
	$dept_code				= $_POST['dept_code']!=''?$_POST['dept_code']:$_GET['dept_code'];
	$group_no					= $_POST['group_no']!=''?$_POST['group_no']:$_GET['group_no'];
	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$adm_name	= SetStringToDB($adm_name);
	$adm_info	= SetStringToDB($adm_info);

	$mode			= SetStringToDB($mode);
	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);

	if ($mode == "I") {


		$adm_id					= SetStringToDB($adm_id);
		$passwd					= SetStringToDB($passwd);
		$adm_hphone			= SetStringToDB($adm_hphone);
		$adm_phone			= SetStringToDB($adm_phone);

		$adm_email			= SetStringToDB($adm_email);
		$adm_flag				= SetStringToDB($adm_flag);
		$position_code	= SetStringToDB($position_code);
		$dept_code			= SetStringToDB($dept_code);

		$use_tf					= SetStringToDB($use_tf);

		$search_field		= SetStringToDB($search_field);
		$search_str			= SetStringToDB($search_str);

		$result_flag = dupAdmin($conn, $adm_id);
		
		if(empty($adm_flag))$adm_flag="Y";
		
		if ($result_flag == 0) {

			$passwd_enc = encrypt($key, $iv, $passwd);
		
			$result =  insertAdmin($conn, $adm_id, $passwd_enc, $adm_name, $adm_info, $adm_hphone, $adm_phone, $adm_email, $group_no, $adm_flag, $position_code, $dept_code, $com_code, $use_tf, $s_adm_no);

			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 등록 (관리자 아이디 : ".$adm_id.") ", "Insert");

		} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('사용중인 ID 입니다.');
		document.location.href = "admin_write.php";
</script>
</head>
</html>
<?
		exit;
		}
	}

	if ($mode == "U") {

		$adm_id					= SetStringToDB($adm_id);
		$passwd					= SetStringToDB($passwd);
		$adm_hphone			= SetStringToDB($adm_hphone);
		$adm_phone			= SetStringToDB($adm_phone);

		$adm_email			= SetStringToDB($adm_email);
		$adm_flag				= SetStringToDB($adm_flag);
		$position_code	= SetStringToDB($position_code);
		$dept_code			= SetStringToDB($dept_code);

		$use_tf					= SetStringToDB($use_tf);

		$search_field		= SetStringToDB($search_field);
		$search_str			= SetStringToDB($search_str);

		if ($old_adm_id <> $adm_id) {
			$result_flag = dupAdmin($conn, $adm_id);
			echo "ccccccccc";
		}
		
		if ($result_flag == 0) {

			$passwd_enc = encrypt($key, $iv, $passwd);

			$result = updateAdmin($conn, $adm_id, $passwd, $adm_name, $adm_info, $adm_hphone, $adm_phone, $adm_email, $group_no, $adm_flag, $position_code, $dept_code, $com_code, $use_tf, $s_adm_no, (int)$adm_no);

			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 정보 수정 (관리자 아이디 : ".$adm_id.") ", "Update");

			if($passwd_chk=="Y")updateAdminPwd($conn, $passwd_enc, $s_adm_no, (int)$adm_no);
		} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('사용중인 ID 입니다.');
		document.location.href = "admin_write.php?mode=S&adm_no=<?=$adm_no?>";
</script>
</head>
</html>
<?
		exit;
		}
	}


	if ($mode == "T") {

		//updateEventUseTF($conn, $use_tf, $s_adm_no, $event_no);

	}

	if ($mode == "D") {
		
		
	//	$row_cnt = count($chk);
		
	//	for ($k = 0; $k < $row_cnt; $k++) {
		
	//		$tmp_banner_no = $chk[$k];

			$result = deleteAdmin($conn, $s_adm_no, (int)$adm_no);

			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 삭제 처리 (관리자 번호 : ".(int)$adm_no.") ", "Delete");

//		}
	}

	if ($mode == "S") {

		$arr_rs = selectAdmin($conn, (int)$adm_no);

		//ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, 
		//GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE

		$rs_adm_no					= trim($arr_rs[0]["ADM_NO"]); 
		$rs_adm_id					= trim($arr_rs[0]["ADM_ID"]); 
		$rs_passwd					= trim($arr_rs[0]["PASSWD"]); 
		$rs_adm_name				= SetStringFromDB($arr_rs[0]["ADM_NAME"]); 
		$rs_adm_info				= SetStringFromDB($arr_rs[0]["ADM_INFO"]); 
		$rs_adm_hphone			= trim($arr_rs[0]["ADM_HPHONE"]); 
		$rs_adm_phone				= trim($arr_rs[0]["ADM_PHONE"]); 
		$rs_adm_email				= trim($arr_rs[0]["ADM_EMAIL"]); 
		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]); 
		$rs_adm_flag				= trim($arr_rs[0]["ADM_FLAG"]); 
		$rs_position_code		= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_dept_code				= trim($arr_rs[0]["DEPT_CODE"]); 
		$rs_com_code				= trim($arr_rs[0]["COM_CODE"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 조회 (관리자 아이디 : ".$rs_adm_id.") ", "Read");


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
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "admin_list.php<?=$strParam?>";
</script>
</head>
</html>
<?
		exit;
	}	
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />

<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link href="../js/jquery-ui.min.css" rel="stylesheet" />
<link href="../css/jquery-ui.css" type="text/css" media="all" rel="stylesheet"  />
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>

<script type="text/javascript" src="../../../_common/SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->

<script type="text/javascript">

function js_list() {
	var frm = document.frm;
		
	frm.method = "get";
	frm.action = "admin_list.php";
	frm.submit();
}


function js_save() {

	var frm = document.frm;
	var adm_no = "<?= (int)$adm_no ?>";
	
	frm.adm_name.value = frm.adm_name.value.trim();
	frm.adm_id.value = frm.adm_id.value.trim();
	frm.passwd.value = frm.passwd.value.trim();

	if (frm.group_no.value == "") {
		alert('관리자 그룹을 선택해주세요.');
		frm.group_no.focus();
		return ;		
	}

	//if (frm.cp_type.value == "") {
	//	alert('소속을 선택해주세요.');
	//	frm.cp_type.focus();
	//	return ;		
	//}
	
	//frm.com_code.value = frm.cp_type.value;

	if (isNull(frm.adm_name.value)) {
		alert('이름을 입력해주세요.');
		frm.adm_name.focus();
		return ;		
	}

	if (isNull(frm.adm_id.value)) {
		alert('아이디을 입력해주세요.');
		frm.adm_id.focus();
		return ;		
	}

	if (isNull(adm_no) || parseInt(adm_no)==0) {
		if (isNull(frm.passwd.value)) {
			alert('비밀번호를 입력해주세요.');
			frm.passwd.focus();
			return ;		
		}
	}

	if (frm.passwd_chk.checked) {
		if (isNull(frm.passwd.value)) {
			alert('비밀번호를 입력해주세요.');
			frm.passwd.focus();
			return ;		
		}
	}


	if (isNull(frm.position_code.value)) {
		alert('소속지역을 선택 해주세요.');
		frm.position_code.focus();
		return ;		
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

	if (isNull(adm_no) || parseInt(adm_no)==0) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.adm_no.value = frm.adm_no.value;
	}

	frm.target = "";
	//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
	frm.action = "admin_write.php";
	frm.submit();

}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('자료를 삭제 하시겠습니까?');
	
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		//frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.action = "admin_write.php";
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


	function js_dept_code(group_cd_01, group_cd_02, group_cd_03, group_cd_04) {
		
		var frm = document.frm;
		var party_val = "";

		for (i=0 ; i < frm.dept_code.length ; i++) {
			if (frm.dept_code[i].checked == true) {
				party_val = frm.dept_code[i].value;
			}
		}

		if (party_val=="") {
			$("#add_group").hide();
		} else {

			var request = $.ajax({
				url:"/_common/get_next_group.php",
				type:"POST",
				data:{party_val:party_val, group_cd_01:group_cd_01, group_cd_02:group_cd_02, group_cd_03:group_cd_03, group_cd_04:group_cd_04},
				dataType:"html"
			});

			request.done(function(msg) {
				$("#group_div").html(msg);
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});

			$("#add_group").show();
		}

		$("#group_cd").val("");
		if ((group_cd_01 != "") && (group_cd_01 != null)) $("#group_cd").val(group_cd_01);
		if ((group_cd_02 != "") && (group_cd_02 != null)) $("#group_cd").val(group_cd_02);
		if ((group_cd_03 != "") && (group_cd_03 != null)) $("#group_cd").val(group_cd_03);
		if ((group_cd_04 != "") && (group_cd_04 != null)) $("#group_cd").val(group_cd_04);
		//if ((group_cd_05 != "") && (group_cd_05 != null)) $("#group_cd").val(group_cd_05);
	}

	$(document).ready(function() {

		$(document).on("change","#group_cd_01", function(){
			//document.frm.group_cd.value=$("#group_cd_01").val();
			js_party($("#group_cd_01").val(), '');
		});

		$(document).on("change","#group_cd_02", function(){
			//document.frm.group_cd.value=$("#group_cd_02").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val());
		});

		$(document).on("change","#group_cd_03", function(){
			//document.frm.group_cd.value=$("#group_cd_03").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val());
		});

		$(document).on("change","#group_cd_04", function(){
			//document.frm.group_cd.value=$("#group_cd_04").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val(), $("#group_cd_04").val());
		});

		$(document).on("change","#group_cd_05", function(){
			document.frm.group_cd.value=$("#group_cd_05").val();
		});

	});

	function js_party(group_cd_01, group_cd_02, group_cd_03, group_cd_04) {
		
		var frm = document.frm;
		var party_val = "";
		
		for (i=0 ; i < frm.dept_code.length ; i++) {
			if (frm.dept_code[i].checked == true) {
				party_val = frm.dept_code[i].value;
			}
		}

		if (party_val=="") {
			$("#add_group").hide();
		
		} else {
		
			var request = $.ajax({
				url:"/_common/get_next_group.php",
				type:"POST",
				data:{party_val:party_val, group_cd_01:group_cd_01, group_cd_02:group_cd_02, group_cd_03:group_cd_03, group_cd_04:group_cd_04},
				dataType:"html"
			});

			request.done(function(msg) {
				//alert(msg);
				$("#group_div").html(msg);
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});

			$("#add_group").show();
		}

		$("#group_cd").val("");
		if ((group_cd_01 != "") && (group_cd_01 != null)) $("#group_cd").val(group_cd_01);
		if ((group_cd_02 != "") && (group_cd_02 != null)) $("#group_cd").val(group_cd_02);
		if ((group_cd_03 != "") && (group_cd_03 != null)) $("#group_cd").val(group_cd_03);
		if ((group_cd_04 != "") && (group_cd_04 != null)) $("#group_cd").val(group_cd_04);
		//if ((group_cd_05 != "") && (group_cd_05 != null)) $("#group_cd").val(group_cd_05);
	}

</script>

</head>
<body>

<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";

?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2><?=$p_parent_menu_name?></h2>
		</div>
		<section class="conBox">

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="seq_no" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="menu_cd" value="<?//=$menu_cd?>" >
<input type="hidden" name="adm_no" value="<?=(int)$adm_no?>" />
<input type="hidden" name="adm_flag" value="<?=$rs_adm_flag?>" />
<input type="hidden" name="nPage" value="<?=(int)$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>" />

<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

			<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
				<colgroup>
					<col width="15%" />
					<col width="35%" />
					<col width="15%" />
					<col width="35%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="row">관리자 그룹</th>
						<td colspan="3">
							<?= makeAdminGroupSelectBox($conn, "group_no" , "155", "관리자 그룹 선택", "", (int)$rs_group_no); ?>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">이름</th>
						<td><input type="text" style="width:35%" name="adm_name" value="<?=$rs_adm_name?>" /></td>
						<th scope="row">아이디</th>
						<td>
							<input type="text" style="width:35%" name="adm_id" value="<?=$rs_adm_id?>" />
							<input type="hidden" name="old_adm_id" value="<?=$rs_adm_id?>">
						</td>
					</tr>
					<tr>
						<th scope="row">비밀번호</th>
						<td colspan="3">
						<? if ($s_adm_cp_type <> "운영") { ?>
							<input type="password" style="width:35%" name="passwd" value="<?//=$rs_passwd?>"  autocomplete="off"/>
						<? } else { ?>
							<input type="text" style="width:35%" name="passwd" value="<?//=$rs_passwd?>"  autocomplete="off"/>
						<? } ?><INPUT TYPE="checkbox" NAME="passwd_chk" value="Y" class="radio">비밀번호 변경
						</td>
					</tr>
					<tr>
						<th scope="row">소속지역</th>
						<td colspan="3"><?= makeSelectBox($conn,"AREA_CD","position_code","125","선택","",$rs_position_code)?></td>
					</tr>

					<tr>
						<th>소속당</th>
						<td colspan="3">
							<?=makeRadioBoxOnClick($conn,"PARTY","dept_code", $rs_dept_code)?>
						</td>
					</tr>
					<tr id="add_group" style="display:none">
						<th>조직</th>
						<td colspan="3">
							<div class="sp5"></div>
							<div id="group_div"><select name="group_cd_01" id="group_cd_01" style="width:200px;"></select></div>

							<div class="sp10"></div>
								<input type="text" name="group_name" id="group_name" class="txt" style="width:500px;"/>
							<font color='red'><b>조직코드</b></font> <input type="text" name="group_cd" id="group_cd" class="txt" style="width:100px;"/>
							<div class="sp5"></div>
						</td>
					</tr>

					<tr>
						<th scope="row">전화번호</th>
						<td><input type="text" style="width:35%" name="adm_phone" value="<?=$rs_adm_phone?>" onkeyup="return isPhoneNumber(this)" /></td>
						<th scope="row">휴대전화번호</th>
						<td><input type="text" style="width:35%" name="adm_hphone" value="<?=$rs_adm_hphone?>" onkeyup="return isPhoneNumber(this)" /></td>
					</tr>
					<tr>
						<th scope="row">이메일</th>
						<td colspan="3"><input type="text" style="width:45%" name="adm_email" value="<?=$rs_adm_email?>" /></td>
					</tr>
					<tr>
						<th scope="row">기타메모</th>
						<td colspan="3" class="subject"><textarea cols="100" rows="5" name="adm_info"><?=$rs_adm_info?></textarea></td>
					</tr>

					<tr>
						<th scope="row">사용여부</th>
						<td colspan="3">
							<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용함 <span style="width:20px;"></span>
							<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?>> 사용안함
							<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
						</td>
					</tr>

				</tbody>
			</table>

			<div class="btnArea">
				<ul class="fRight">
				<? if ((int)$adm_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<li><a href="javascript:sendKeyword();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
					<li><a href="javascript:sendKeyword();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
				<? }?>


					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>



				<? if ((int)$adm_no <> "") {?>
					<? if ($sPageRight_D == "Y") {?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<? } ?>
				<? } ?>
				</ul>
			</div>
		</section>
		<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>