<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : admin_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-12-11
# Modify Date  : 
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
												"GROUP_NO"=>$group_no,
												"ADM_FLAG"=>$adm_flag,
												"POSITION_CODE"=>$position_code,
												"DEPT_CODE"=>$dept_code,
												"COM_CODE"=>$com_code,
												"ENTER_DATE"=>$enter_date,
												"OUT_DATE"=>$out_date,
												"PROFILE"=>$profile,
												"USE_TF"=>$use_tf,
												"REG_ADM"=>$_SESSION['s_adm_no']
											);

			$result =  insertAdmin($conn, $arr_data);
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
												"GROUP_NO"=>$group_no,
												"ADM_FLAG"=>$adm_flag,
												"POSITION_CODE"=>$position_code,
												"DEPT_CODE"=>$dept_code,
												"COM_CODE"=>$com_code,
												"ENTER_DATE"=>$enter_date,
												"OUT_DATE"=>$out_date,
												"PROFILE"=>$profile,
												"USE_TF"=>$use_tf,
												"UP_ADM"=>$_SESSION['s_adm_no']
											);
			
			$result = updateAdmin($conn, $arr_data, $adm_no);

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
		$rs_adm_hphone			= trim($arr_rs[0]["ADM_HPHONE"]); 
		$rs_adm_phone				= trim($arr_rs[0]["ADM_PHONE"]); 
		$rs_adm_email				= trim($arr_rs[0]["ADM_EMAIL"]); 
		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]); 
		$rs_adm_flag				= trim($arr_rs[0]["ADM_FLAG"]); 
		$rs_position_code		= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_dept_code				= trim($arr_rs[0]["DEPT_CODE"]); 
		$rs_com_code				= trim($arr_rs[0]["COM_CODE"]); 
		$rs_enter_date			= trim($arr_rs[0]["ENTER_DATE"]); 
		$rs_out_date				= trim($arr_rs[0]["OUT_DATE"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_profile					= trim($arr_rs[0]["PROFILE"]); 

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
<script type="text/javascript" src="/manager/js/httpRequest.js"></script> <!-- Ajax js -->

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

function js_list() {
	var frm = document.frm;
		
	frm.method = "get";
	frm.action = "admin_list.php";
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

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
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

<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">


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
								<td colspan="3">
									<? if ($s_adm_cp_type <> "운영") { ?>
									<span class="inpbox" style="width:50%"><input type="password" class="txt" name="passwd" value="" autocomplete="off" /></span>
									<? } else { ?>
									<span class="inpbox" style="width:50%"><input type="text" class="txt" name="passwd" value="" autocomplete="off"/></span>
									<? } ?>
									<? if ($adm_no) { ?>
									<span class="ickbox"><INPUT TYPE="checkbox" NAME="passwd_chk" value="Y" class="check"><label>비밀번호 변경</label></span>
									<? } ?>
								</td>
							</tr>
							<tr>
								<th>프로필</th>
								<td colspan="3">
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
							</tr>
							<tr>
								<th>부서</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"DEPT","dept_code","125px","선택","",$rs_dept_code)?></span></td>
								<th>직급</th>
								<td><span class="optionbox"><?= makeSelectBox($conn,"POSITION","position_code","125px","선택","",$rs_position_code)?></span></td>
							</tr>
							<tr>
								<th>전화번호</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:35%" name="adm_phone" value="<?=$rs_adm_phone?>" onkeyup="return isPhoneNumber(this)" /></span></td>
								<th>휴대전화번호</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:35%" name="adm_hphone" value="<?=$rs_adm_hphone?>" onkeyup="return isPhoneNumber(this)" /></span></td>
							</tr>
							<tr>
								<th>이메일</th>
								<td colspan="3"><span class="inpbox"><input type="text" class="txt" style="width:45%" name="adm_email" value="<?=$rs_adm_email?>" /></span></td>
							</tr>
							<tr>
								<th>입사일</th>
								<td><span class="inpbox"><input type="text" class="txt date" style="width:35%" name="enter_date" value="<?=$rs_enter_date?>" /></span></td>
								<th>퇴사일</th>
								<td><span class="inpbox"><input type="text" class="txt date" style="width:35%" name="out_date" value="<?=$rs_out_date?>" /></span></td>
							</tr>

							<tr>
								<th>기타메모</th>
								<td colspan="3" class="subject">
									<span class="textareabox">
										<textarea class="txt" cols="100" rows="5" name="adm_info"><?=$rs_adm_info?></textarea>
									</span>
								</td>
							</tr>

							<tr>
								<th>사용여부</th>
								<td colspan="3">
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>><label>사용함</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="sp20"></div>
					<div class="btnright">
					<? if ($adm_no <> "" ) {?>
						<? if ($sPageRight_U == "Y") {?>
						<button type="button" class="btn-navy" onClick="sendKeyword();" style="width:100px">확인</button>
						<? } ?>
					<? } else {?>
						<? if ($sPageRight_I == "Y") {?>
						<button type="button" class="btn-navy" onClick="sendKeyword();" style="width:100px">확인</button>
						<? } ?>
					<? }?>

					<? if ($s_adm_cp_type == "운영") { ?>
						<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
					<? } ?>

					<? if ($s_adm_cp_type == "운영") { ?>
					<? if ($adm_no <> "") {?>
						<? if ($sPageRight_D == "Y") {?>
						<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
						<? } ?>
					<? } ?>
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