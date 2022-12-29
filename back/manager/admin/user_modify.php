<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : user_modify.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-10-19
# Modify Date  : 
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD005"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

	//$sPageRight_		= "Y";
	//$sPageRight_R		= "Y";
	//$sPageRight_I		= "Y";
	//$sPageRight_U		= "Y";
	//$sPageRight_D		= "Y";
	//$sPageRight_F		= "Y";

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";

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
	$group_cd					= $_POST['group_cd']!=''?$_POST['group_cd']:$_GET['group_cd'];
	$flag01						= $_POST['flag01']!=''?$_POST['flag01']:$_GET['flag01'];
	$old_profile			= $_POST['old_profile']!=''?$_POST['old_profile']:$_GET['old_profile'];

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

	if ($mode == "") $mode = "S";

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$profile		= upload($_FILES[profile], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
				$_SESSION['s_adm_profile']	= $profile;
			break;
			case "keep" :
				$profile			= $old_profile;
			break;
			case "delete" :
				$profile			= "";
			break;
			case "update" :
				$profile		= upload($_FILES[profile], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
				$_SESSION['s_adm_profile']	= $profile;
			break;
		}

		$arr_data = array("ADM_NAME"=>$adm_name,
											"DEPT_CODE"=>$dept_code,
											"POSITION_CODE"=>$position_code,
											"ADM_PHONE"=>$adm_phone,
											"ADM_HPHONE"=>$adm_hphone,
											"ADM_EMAIL"=>$adm_email,
											"ENTER_DATE"=>$enter_date,
											"OUT_DATE"=>$out_date,
											"ADM_INFO"=>$adm_info,
											"PROFILE"=>$profile,
											"UP_ADM"=>$_SESSION['s_adm_no']
											);

		$result = updateAdmin($conn, $arr_data, $s_adm_no);

		$passwd_enc = encrypt($key, $iv, $passwd);

		if($passwd_chk=="Y") updateAdminPwd($conn, $passwd_enc, $s_adm_no, (int)$adm_no);

		$mode = "S";

	}

	if ($mode == "S") {

		$arr_rs = selectAdmin($conn, $s_adm_no);

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
		$rs_enter_date			= trim($arr_rs[0]["ENTER_DATE"]); 
		$rs_out_date				= trim($arr_rs[0]["OUT_DATE"]); 
		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]); 
		$rs_adm_flag				= trim($arr_rs[0]["ADM_FLAG"]); 
		$rs_position_code		= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_dept_code				= trim($arr_rs[0]["DEPT_CODE"]); 
		$rs_com_code				= trim($arr_rs[0]["COM_CODE"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_profile					= trim($arr_rs[0]["PROFILE"]); 

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "user_modify.php";
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
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->

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
});

function js_save() {

	var frm = document.frm;
	var adm_no = "<?= $s_adm_no ?>";
	
	frm.adm_name.value = frm.adm_name.value.trim();
	frm.adm_id.value = frm.adm_id.value.trim();
	frm.passwd.value = frm.passwd.value.trim();

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

	if (frm.passwd_chk.checked) {
		if (isNull(frm.passwd.value)) {
			alert('비밀번호를 입력해주세요.');
			frm.passwd.focus();
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
				alert("사용중인 권한 코드 입니다.");
				return;
			} else {
				js_save();
			}
		} else {
			alert("에러 발생: "+httpRequest.status);
		}
	}
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
				<div class="boardwrite">


<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="adm_no" value="<?=$s_adm_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
<input type="hidden" name="group_no" value="<?= $rs_group_no ?>"> 

					<table>
						<colgroup>
							<col style="width:15%" />
							<col style="width:35%" />
							<col style="width:15%" />
							<col style="width:35%" />
						</colgroup>

						<tbody>
							<tr>
								<th>이름</th>
								<td><span class="inpbox"><input type="text" class="txt" name="adm_name" value="<?=$rs_adm_name?>" /></span></td>
								<th>아이디</th>
								<td>
									<input type="hidden" name="adm_id" value="<?=$rs_adm_id?>" /><?=$rs_adm_id?>
									<input type="hidden" name="old_adm_id" value="<?=$rs_adm_id?>">
								</td>
							</tr>
							<tr>
								<th>비밀번호</th>
								<td>
									<span class="inpbox" style="width:70%"><input type="password" class="txt" name="passwd" value="" autocomplete="off" /></span>
									<span class="ickbox"><input type="checkbox" NAME="passwd_chk" value="Y" class="check"><label>비밀번호 변경</label></span>
								</td>
								<th>프로필</th>
								<td colspan="3">
									<? if ($rs_profile) { ?>
									<span class="optionbox">
										<select name="flag01" onchange="javascript:js_fileView(this,'01')">
											<option value="keep">유지</option>
											<option value="delete">삭제</option>
											<option value="update">수정</option>
										</select>
									</span>
									<input type="hidden" name="old_profile" value="<?= $rs_profile?>">
									<span class="inpbox" id="file_change" style="display:none;"><input type="file" class="txt" style="width:35%" name="profile" value="<?=$rs_profile?>" /></span>
									<? } else { ?>
									<span class="inpbox"><input type="file" class="txt" style="width:35%" name="profile" value="<?=$rs_profile?>" /></span>
									<input type="hidden" name="old_profile" value="">
									<input TYPE="hidden" name="flag01" value="insert">
									<? } ?>
								</td>
							</tr>
							<tr>
								<th>부서</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"DEPT","dept_code","","선택","",$rs_dept_code)?></span></td>
								<th>직급</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"POSITION","position_code","","선택","",$rs_position_code)?></span></td>
							</tr>
							<tr>
								<th>전화번호</th>
								<td><span class="inpbox"><input type="text" class="txt" name="adm_phone" value="<?=$rs_adm_phone?>" onkeyup="return isPhoneNumber(this)" /></span></td>
								<th>휴대전화번호</th>
								<td><span class="inpbox"><input type="text" class="txt" name="adm_hphone" value="<?=$rs_adm_hphone?>" onkeyup="return isPhoneNumber(this)" /></span></td>
							</tr>
							<tr>
								<th>이메일</th>
								<td colspan="3"><span class="inpbox"><input type="text" class="txt" name="adm_email" value="<?=$rs_adm_email?>" /></span></td>
							</tr>
							<tr>
								<th>입사일</th>
								<td><span class="inpbox"><input type="text" class="txt date" name="enter_date" value="<?=$rs_enter_date?>" /></span></td>
								<th>퇴사일</th>
								<td><span class="inpbox"><input type="text" class="txt date" name="out_date" value="<?=$rs_out_date?>" /></span></td>
							</tr>
						</tbody>
					</table>
					<div class="sp20"></div>
					<div class="btnright">

						<? if ($sPageRight_U == "Y") {?>
						<button type="button" class="btn-navy" onClick="sendKeyword();" style="width:100px">확인</button>
						<? } ?>

					</div> 

				</div>
			</div>
		</div>
	</div>

<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
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