<?session_start();?>
<?
# ===================================================================
# File Name    : people_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.04.27
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# ===================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../../_common/config.php";
	require "../../../_classes/community/util/util.php";
	require "../../../_classes/community/etc/etc.php";
	require "../../../_classes/community/cpeople/people.php";

#====================================================================
# DML Process
#====================================================================

	if ($mode == "I") {

#====================================================================
	$savedir1 = $g_physical_path."upload_data/people";
#====================================================================
		$file_cnt = count($file_name);

		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");

		$max_allow_file_size = $allow_file_size * 1024 * 1024;

		for($i=0; $i <= $file_cnt; $i++) {
			if ($_POST["file_flag"][$i] == "insert" or $_POST["file_flag"][$i] == "update") {
				if($_FILES[file_name]['size'][$i] > $max_allow_file_size) {
	?>
					<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
					<SCRIPT LANGUAGE="JavaScript">
					<!--
						alert('첨부파일은 <?=$allow_file_size?> MByte 을 넘을 수 없습니다.');
						history.back();
					//-->
					</SCRIPT>
	<?
					exit;
				}
			}
		}

		$file_nm		= upload($_FILES[file_nm], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
		$file_rnm		= $_FILES[file_nm][name];
		$file_size	= $_FILES[file_nm]['size'];
		$file_ext		= end(explode('.', $_FILES[file_nm][name]));

		$result =  insertCommPeople($conn, $comm_no, $name, $birthdate, $position_code, $position, $tel01, $tel02, $career, $homepage, $tweeter, $facebook, $meto, $yozm, $email, $file_nm, $file_rnm, $file_size, $file_ext, $ex_info01, $ex_info02, $ex_info03, $use_tf, $s_com_adm_no);

	}


	if ($mode == "U") {

#====================================================================
		$savedir1 = $g_physical_path."upload_data/people";
#====================================================================
		$file_cnt = count($file_name);

		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");

		$max_allow_file_size = $allow_file_size * 1024 * 1024;

		for($i=0; $i <= $file_cnt; $i++) {
			if ($_POST["file_flag"][$i] == "insert" or $_POST["file_flag"][$i] == "update") {
				if($_FILES[file_name]['size'][$i] > $max_allow_file_size) {
	?>
					<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
					<SCRIPT LANGUAGE="JavaScript">
					<!--
						alert('첨부파일은 <?=$allow_file_size?> MByte 을 넘을 수 없습니다.');
						history.back();
					//-->
					</SCRIPT>
	<?
					exit;
				}
			}
		}

		switch ($flag01) {
			case "insert" :

				$file_nm		= upload($_FILES[file_nm], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
				$file_rnm		= $_FILES[file_nm][name];
				$file_size	= $_FILES[file_nm]['size'];
				$file_ext		= end(explode('.', $_FILES[file_nm][name]));

			break;
			case "keep" :

				$file_nm		= $old_file_nm;
				$file_rnm		= $old_file_rnm;
				$file_size	= $old_file_size;
				$file_ext		= $old_file_ext;

			break;
			case "delete" :

				$file_nm	= "";
				$file_rnm	= "";
				$file_size = "";
				$file_ext  = "";

			break;
			case "update" :

				$file_nm		= upload($_FILES[file_nm], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
				$file_rnm		= $_FILES[file_nm][name];
				$file_size	= $_FILES[file_nm]['size'];
				$file_ext		= end(explode('.', $_FILES[file_nm][name]));

			break;
		}

		$result = updateCommPeople($conn, $comm_no, $name, $birthdate, $position_code, $position, $tel01, $tel02, $career, $homepage, $tweeter, $facebook, $meto, $yozm, $email, $file_nm, $file_rnm, $file_size, $file_ext, $ex_info01, $ex_info02, $ex_info03, $use_tf, $s_com_adm_no, $seq_no);

	}


	if ($mode == "D") {
		$result = deleteCommPeople($conn, $s_adm_no, $seq_no);
	}

	if ($mode == "S") {

		$arr_rs = selectCommPeople($conn, $seq_no);

		$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]); 
		$rs_name						= trim($arr_rs[0]["NAME"]); 
		$rs_birthdate				= trim($arr_rs[0]["BIRTHDATE"]); 
		$rs_position_code		= trim($arr_rs[0]["POSITION_CODE"]); 
		$rs_position				= trim($arr_rs[0]["POSITION"]); 
		$rs_tel01						= trim($arr_rs[0]["TEL01"]); 
		$rs_tel02						= trim($arr_rs[0]["TEL02"]); 
		$rs_career					= trim($arr_rs[0]["CAREER"]); 
		$rs_homepage				= trim($arr_rs[0]["HOMEPAGE"]); 
		$rs_tweeter					= trim($arr_rs[0]["TWEETER"]); 
		$rs_facebook				= trim($arr_rs[0]["FACEBOOK"]); 
		$rs_meto						= trim($arr_rs[0]["METO"]); 
		$rs_yozm						= trim($arr_rs[0]["YOZM"]); 
		$rs_email						= trim($arr_rs[0]["EMAIL"]); 
		$rs_file_nm					= trim($arr_rs[0]["FILE_NM"]); 
		$rs_file_rnm				= trim($arr_rs[0]["FILE_RNM"]); 
		$rs_file_size				= trim($arr_rs[0]["FILE_SIZE"]); 
		$rs_file_etc				= trim($arr_rs[0]["FILE_EXT"]); 
		$rs_ex_info01				= trim($arr_rs[0]["EX_INFO01"]); 
		$rs_ex_info02				= trim($arr_rs[0]["EX_INFO02"]); 
		$rs_ex_info03				= trim($arr_rs[0]["EX_INFO03"]); 
		
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_position_code=".$con_position_code."&search_field=".$search_field."&search_str=".$search_str."&menu_cd=".$menu_cd;

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "people_list.php<?=$strParam?>";
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
<title><?=$s_comm_name?> 관리자 로그인</title>
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

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript">
<!--

function js_list() {
	document.location = "people_list.php<?=$strParam?>";
}


function js_save() {

	var frm = document.frm;
	var seq_no = "<?=$seq_no ?>";
	
	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (isNull(seq_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.seq_no.value = frm.seq_no.value;
	}


	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_view(seq) {

	var frm = document.frm;
		
	frm.seq_no.value = seq;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "post";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
		
}

function file_change(file) { 
	document.getElementById("file_name").value = file; 
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

//	}
}

/**
* 파일 첨부에 대한 선택에 따른 파일첨부 입력란 visibility 설정
*/
function js_exfileView(idx) {

	var obj = document.frm["file_flag[]"][idx];
	
	if (obj.selectedIndex == 2) {
		document.frm["file_name[]"][idx].style.visibility = "visible"; 
	} else { 
		document.frm["file_name[]"][idx].style.visibility = "hidden"; 
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

function js_position_code() {
	var obj = document.getElementsByName("position_code")[0];
	var obj_text = document.getElementsByName("position")[0];
	obj_text.value = obj.options[obj.selectedIndex].text;
}

//-->
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
	include_once('../../../_common/fckeditor/fckeditor.php');
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
<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>" />
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
<input type="hidden" name="con_position_code" value="<?=$con_position_code?>" />

		<section class="conBox">
			<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 <?=$p_menu_name?> 내용을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
					<tbody>
						<tr>
							<th><label for="pName">이름</label></th>
							<td>
								<input type="text" class="w150" name="name" value="<?=$rs_name?>" style="width: 10%;" />
							</td>
						</tr>

						<tr>
							<th class="conTxt">사진</th>
							<td colspan="3">
								<?
									if (strlen($rs_file_nm) > 3) {
								?>
									<img src="/upload_data/people/<?= $rs_file_nm ?>" width="150" height="150">
									&nbsp;&nbsp;
									<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
										<option value="keep">유지</option>
										<option value="delete">삭제</option>
										<option value="update">수정</option>
									</select>
									
									<input type="hidden" name="old_file_nm" value="<?= $rs_file_nm?>">
									<input type="hidden" name="old_file_rnm" value="<?= $rs_file_rnm?>">
									<input type="hidden" name="old_file_size" value="<?= $rs_file_size?>">
									<input type="hidden" name="old_file_ext" value="<?= $rs_file_etc?>">

									<div id="file_change" style="display:none;">
										<input type="file" name="file_nm" class="w50per" /> 150px * 150px
									</div>

								<?
									} else {
								?>
									<input type="file" id="peoplePhoto" class="w50per" name="file_nm"> 150px * 150px
									<input type="hidden" name="old_file_nm" value="">
									<input type="hidden" name="old_file_rnm" value="">
									<input type="hidden" name="old_file_size" value="">
									<input type="hidden" name="old_file_ext" value="">
									<input TYPE="hidden" name="flag01" value="insert">
								<?
									}	
								?>
							</td>
						</tr>

						<tr>
							<th><label for="birth">생년월일</label></th>
							<td colspan="3">
								<input type="text" name="birthdate" value="<?=$rs_birthdate?>" class="w250"  /><span class="explain"> 예) 2012년 03월 23일</span>
							</td>
						</tr>
						<tr>
							<th><label for="jobName">직책</label></th>
							<td colspan="3">
								<?= makeSelectBoxOnChange($conn, "COMM_PEOPLE" ,"position_code" ,"250px" , "직책 선택" , "", $rs_position_code);?>
								<input type="hidden" name="position" value="" >
							</td>
						</tr>
						<tr>
							<th><label for="connect01">연락처 01</label></th>
							<td colspan="3">
								<input type="text" name="tel01" value="<?=$rs_tel01?>" class="w250" /><span class="explain">예) 02-2139-7777</span>
							</td>
						</tr>
						<tr>
							<th><label for="connect02">연락처 02</label></th>
							<td colspan="3">
								<input type="text" id="connect02" name="tel02" value="<?=$rs_tel02?>" class="w250" />
							</td>
						</tr>
						<tr>
							<th><label for="eMail">E-Mail</label></th>
							<td colspan="3">
								<input type="email" id="eMail" class="w250" name="email" value="<?=$rs_email?>" />
							</td>
						</tr>
						<tr>
							<th><label for="pHistory">약력</label></th>
							<td colspan="3">
								<textarea id="pHistory" name="career" rows="7" cols="80"><?=$rs_career?></textarea>
							</td>
						</tr>
						<tr>
							<th><label for="homepage">홈페이지 주소</label></th>
							<td colspan="3">
								<input type="type" id="homepage" name="homepage" value="<?=$rs_homepage?>" /><span class="explain">("http://"는 제외하고 입력해 주세요)</span>
							</td>
						</tr>
						<tr>
							<th><label for="twitt">트위터 계정</label></th>
							<td colspan="3">
								<input type="type" id="twitt" name="tweeter" value="<?=$rs_tweeter?>" /><span class="explain">아이디(ID)만 입력해 주세요</span>
							</td>
						</tr>
						<tr>
							<th><label for="facebook">페이스북 계정</label></th>
							<td colspan="3">
								<input type="type" id="facebook" name="facebook" value="<?=$rs_facebook?>" /><span class="explain">아이디(ID)만 입력해 주세요</span>
							</td>
						</tr>
						<tr>
							<th><label for="meto">미투데이 계정</label></th>
							<td colspan="3">
								<input type="type" id="meto" name="meto" value="<?=$rs_meto?>" /><span class="explain">아이디(ID)만 입력해 주세요</span>
							</td>
						</tr>
						<tr class="last">	
							<th>노출여부</th>
							<td colspan="3">
								<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 보이기<span style="width:20px;"></span>
								<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 보이지않기 
								<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
							</td>
						</tr>
					</tbody>
				</table>
			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fRight">
					<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
					<? if ($seq_no) {?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<? } ?>

				</ul>
			</div>
		</section>
		</form>
	</section>
	<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>