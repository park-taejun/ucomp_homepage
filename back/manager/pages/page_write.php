<?session_start();?>
<?
# =============================================================================
# File Name    : page_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2011.06.01
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
	$menu_right = "CS001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/page/page.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$page_no					= $_POST['page_no']!=''?$_POST['page_no']:$_GET['page_no'];
	$page_lang				= $_POST['page_lang']!=''?$_POST['page_lang']:$_GET['page_lang'];
	$m_level					= $_POST['m_level']!=''?$_POST['m_level']:$_GET['m_level'];
	$m_seq01					= $_POST['m_seq01']!=''?$_POST['m_seq01']:$_GET['m_seq01'];
	$m_seq02					= $_POST['m_seq02']!=''?$_POST['m_seq02']:$_GET['m_seq02'];
	$m_seq03					= $_POST['m_seq03']!=''?$_POST['m_seq03']:$_GET['m_seq03'];
	$m_seq04					= $_POST['m_seq04']!=''?$_POST['m_seq04']:$_GET['m_seq04'];
	$m_seq05					= $_POST['m_seq05']!=''?$_POST['m_seq05']:$_GET['m_seq05'];

	$sel_page_lang		= $_POST['sel_page_lang']!=''?$_POST['sel_page_lang']:$_GET['sel_page_lang'];

	$page_name				= $_POST['page_name']!=''?$_POST['page_name']:$_GET['page_name'];
	$page_url					= $_POST['page_url']!=''?$_POST['page_url']:$_GET['page_url'];
	$page_flag				= $_POST['page_flag']!=''?$_POST['page_flag']:$_GET['page_flag'];
	$page_script			= $_POST['page_script']!=''?$_POST['page_script']:$_GET['page_script'];
	$page_content			= $_POST['page_content']!=''?$_POST['page_content']:$_GET['page_content'];
	$page_info01			= $_POST['page_info01']!=''?$_POST['page_info01']:$_GET['page_info01'];
	$page_info02			= $_POST['page_info02']!=''?$_POST['page_info02']:$_GET['page_info02'];
	$page_info03			= $_POST['page_info03']!=''?$_POST['page_info03']:$_GET['page_info03'];
	$page_cd					= $_POST['page_cd']!=''?$_POST['page_cd']:$_GET['page_cd'];
	$in_page_right		= $_POST['in_page_right']!=''?$_POST['in_page_right']:$_GET['in_page_right'];
	$menu_tf					= $_POST['menu_tf']!=''?$_POST['menu_tf']:$_GET['menu_tf'];
	$login_tf					= $_POST['login_tf']!=''?$_POST['login_tf']:$_GET['login_tf'];
	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$mode	 = SetStringToDB($mode);

	$m_level = SetStringToDB($m_level);
	$m_seq01 = SetStringToDB($m_seq01);
	$m_seq02 = SetStringToDB($m_seq02);
	$m_seq03 = SetStringToDB($m_seq03);
	$m_seq04 = SetStringToDB($m_seq04);
	$m_seq05 = SetStringToDB($m_seq05);

#====================================================================
# Declare variables
#====================================================================

#====================================================================
# Request Parameter
#====================================================================

	$page_no		= SetStringToDB($page_no);
	$m_level		= SetStringToDB($m_level);
	$m_seq01		= SetStringToDB($m_seq01);
	$m_seq02		= SetStringToDB($m_seq02);
	$m_seq03		= SetStringToDB($m_seq03);
	$m_seq04		= SetStringToDB($m_seq04);
	$m_seq05		= SetStringToDB($m_seq05);
	$page_name	= SetStringToDB($page_name);
	$page_url		= SetStringToDB($page_url);
	$page_flag	= SetStringToDB($page_flag);
	$page_cd		= SetStringToDB($page_cd);
	$in_page_right = SetStringToDB($in_page_right);

	$use_tf			= SetStringToDB($use_tf);

	$result = false;

#====================================================================
	$savedir1 = $g_physical_path."upload_data/menu";
#====================================================================


	if ($mode == "I") {
		

		$title_img				= upload($_FILES[title_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$title_img_over		= upload($_FILES[title_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));

		$page_img					= upload($_FILES[page_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$page_img_over		= upload($_FILES[page_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));

		$result = insertPage($conn, $sel_page_lang, $m_level, $m_seq01, $m_seq02, $m_seq03, $m_seq04, $page_name, $page_url, $page_flag, $page_right, $title_img, $title_img_over, $page_img, $page_img_over, $page_script, $page_content, $page_info01, $page_info02, $page_info03, $menu_tf, $use_tf, $login_tf, $s_adm_no);

	}


	if ($mode == "S") {

		$arr_rs = selectPage($conn, $page_no);

		//PAGE_NO, PAGE_NAME, PAGE_URL, PAGE_FLAG, PAGE_CD, PAGE_RIGHT,PAGE_IMG,PAGE_IMG_OVER

		$rs_page_no					= trim($arr_rs[0]["PAGE_NO"]); 
		$rs_page_lang				= trim($arr_rs[0]["PAGE_LANG"]); 
		$rs_page_name				= setStringFromDB($arr_rs[0]["PAGE_NAME"]); 
		$rs_page_url				= setStringFromDB($arr_rs[0]["PAGE_URL"]); 
		$rs_page_flag				= trim($arr_rs[0]["PAGE_FLAG"]); 
		$rs_page_cd					= trim($arr_rs[0]["PAGE_CD"]); 
		$rs_page_right			= trim($arr_rs[0]["PAGE_RIGHT"]); 
		$rs_title_img				= trim($arr_rs[0]["TITLE_IMG"]); 
		$rs_title_img_over	= trim($arr_rs[0]["TITLE_IMG_OVER"]); 
		$rs_page_img				= trim($arr_rs[0]["PAGE_IMG"]); 
		$rs_page_img_over		= trim($arr_rs[0]["PAGE_IMG_OVER"]); 
		$rs_page_script			= trim($arr_rs[0]["PAGE_SCRIPT"]); 
		$rs_page_content		= trim($arr_rs[0]["PAGE_CONTENT"]); 
		$rs_page_info01			= trim($arr_rs[0]["PAGE_INFO01"]); 
		$rs_page_info02			= trim($arr_rs[0]["PAGE_INFO02"]); 
		$rs_page_info03			= trim($arr_rs[0]["PAGE_INFO03"]); 
		$rs_menu_tf					= trim($arr_rs[0]["MENU_TF"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_login_tf				= trim($arr_rs[0]["LOGIN_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 

	}
	
	if ($rs_page_lang == "") $rs_page_lang = $sel_page_lang;


	if ($mode == "U") {
		
		switch ($flag01) {
			case "insert" :
				$title_img					= upload($_FILES[title_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$title_img			= $old_title_img;
			break;
			case "delete" :
				$title_img			= "";
			break;
			case "update" :
				$title_img					= upload($_FILES[title_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag02) {
			case "insert" :
				$title_img_over		= upload($_FILES[title_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$title_img_over		= $old_title_img_over;
			break;
			case "delete" :
				$title_img_over		= "";
			break;
			case "update" :
				$title_img_over		= upload($_FILES[title_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag03) {
			case "insert" :
				$page_img		= upload($_FILES[page_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$page_img	= $old_page_img;
			break;
			case "delete" :
				$page_img		= "";
			break;
			case "update" :
				$page_img		= upload($_FILES[page_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag04) {
			case "insert" :
				$page_img_over		= upload($_FILES[page_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$page_img_over		= $old_page_img_over;
			break;
			case "delete" :
				$page_img_over		= "";
			break;
			case "update" :
				$page_img_over		= upload($_FILES[page_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		$result = updatePage($conn, $sel_page_lang, $page_name, $page_url, $page_flag, $page_right, $title_img, $title_img_over, $page_img, $page_img_over, $page_script, $page_content, $page_info01, $page_info02, $page_info03, $menu_tf, $use_tf, $login_tf, $s_adm_no, $page_no);
	}

	if ($mode == "D") {
		$result = deletePage($conn, $s_adm_no, $page_no);
	}

	$strParam = "?sel_page_lang=".$sel_page_lang;

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "page_list.php<?=$strParam?>";
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
<script type="text/javascript" src="../../_common/SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">
<!--

function js_list() {
	document.location = "page_list.php<?=$strParam?>";
}

function js_save() {

	var frm = document.frm;
	var page_no = "<?= $page_no ?>";
	
	if(document.frm.page_lang.value==""){
		alert('페이지 구분을 선택해주세요.');
		document.frm.page_lang.focus();
		return;
	}

	if(document.frm.page_name.value==""){
		alert('페이지 이름을 입력해주세요.');
		document.frm.page_name.focus();
		return;
	}

	if (document.frm.rd_menu_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_menu_tf[0].checked == true) {
			frm.menu_tf.value = "Y";
		} else {
			frm.menu_tf.value = "N";
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

	if (document.frm.rd_login_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_login_tf[0].checked == true) {
			frm.login_tf.value = "A";
		} else if (frm.rd_login_tf[1].checked == true) {
			frm.login_tf.value = "N";
		} else {
			frm.login_tf.value = "Y";
		}
	}
	
	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.

	if (isNull(page_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.page_no.value = frm.page_no.value;
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
			frm.method = "post";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
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
			document.getElementById("file_change01").style.display = "inline";
		} else {
			document.getElementById("file_change01").style.display = "none";
		}
	}
	if (idx == 02) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change02").style.display = "inline";
		} else {
			document.getElementById("file_change02").style.display = "none";
		}
	}

	if (idx == 03) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change03").style.display = "inline";
		} else {
			document.getElementById("file_change03").style.display = "none";
		}
	}

	if (idx == 04) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change04").style.display = "inline";
		} else {
			document.getElementById("file_change04").style.display = "none";
		}
	}
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
<input type="hidden" name="mode" value="">
<input type="hidden" name="sel_page_lang" value="<?=$sel_page_lang?>">
<input type="hidden" name="page_no" value="<?=$page_no?>">
<input type="hidden" name="m_level" value="<?=$m_level?>">
<input type="hidden" name="m_seq01" value="<?=$m_seq01?>">
<input type="hidden" name="m_seq02" value="<?=$m_seq02?>">
<input type="hidden" name="m_seq03" value="<?=$m_seq03?>">
<input type="hidden" name="m_seq04" value="<?=$m_seq04?>">

			<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
					<thead>
						<tr>
							<th scope="row">페이지 이름</th>
							<td  colspan="3">
								<input type="text" class="wfull" name="page_name" value="<?=$rs_page_name?>" />
								<input type="hidden" name="page_lang" id="page_lang" value="KOR">
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">페이지 경로</th>
							<td colspan="3">
								<input type="text" class="wfull" name="page_url" value="<?=$rs_page_url?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">비주얼 Class명</th>
							<td colspan="3">
								<input type="text" class="wfull" name="page_info01" value="<?=$rs_page_info01?>" /> 
							</td>
						</tr>
						<tr>
							<th scope="row">비주얼 명</th>
							<td colspan="3">
								<input type="text" class="wfull" name="page_info02" value="<?=$rs_page_info02?>" /> 
							</td>
						</tr>
						<tr>
							<th scope="row">비주얼 설명</th>
							<td colspan="3">
								<input type="text" class="wfull" name="page_info03" value="<?=$rs_page_info03?>" /> 
							</td>
						</tr>
						<tr>
							<th scope="row">제목 이미지</th>
							<td>
						<?
							if (strlen($rs_title_img) > 3) {
						?>
							<a href="../../_common/new_download_file.php?menu=page&page_no=<?= $rs_page_no ?>&field=title_img"><img src="<?=$g_base_dir?>/upload_data/menu/<?= $rs_title_img ?>"></a>
							&nbsp;&nbsp;
							<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_title_img" value="<?= $rs_title_img?>">
							<div id="file_change01" style="display:none;">
								<input type="file" name="title_img" size="30%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="title_img">
							<input type="hidden" name="old_title_img" value="">
							<input TYPE="hidden" name="flag01" value="insert">
						<?
							}	
						?>
							</td>
							<th scope="row">over 이미지</th>
							<td>
						<?
							if (strlen($rs_title_img_over) > 3) {
						?>
							<a href="../../_common/new_download_file.php?menu=page&page_no=<?= $rs_page_no ?>&field=title_img_over"><img src="<?=$g_base_dir?>/upload_data/menu/<?= $rs_title_img_over ?>"></a>
							&nbsp;&nbsp;
							<select name="flag02" style="width:70px;" onchange="javascript:js_fileView(this,'02')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_title_img_over" value="<?= $rs_title_img_over?>">
							<div id="file_change02" style="display:none;">
								<input type="file" name="title_img_over" size="30%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="title_img_over">
							<input type="hidden" name="old_title_img_over" value="">
							<input TYPE="hidden" name="flag02" value="insert">
						<?
							}	
						?>
							</td>
						</tr>

						<tr>
							<th scope="row">페이지 이미지</th>
							<td>
						<?
							if (strlen($rs_page_img) > 3) {
						?>
							<a href="../../_common/new_download_file.php?menu=page&page_no=<?= $rs_page_no ?>&field=page_img"><img src="<?=$g_base_dir?>/upload_data/menu/<?= $rs_page_img ?>"></a>
							&nbsp;&nbsp;
							<select name="flag03" style="width:70px;" onchange="javascript:js_fileView(this,'03')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_page_img" value="<?= $rs_page_img?>">
							<div id="file_change03" style="display:none;">
								<input type="file" name="page_img" size="30%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="page_img">
							<input type="hidden" name="old_page_img" value="">
							<input TYPE="hidden" name="flag03" value="insert">
						<?
							}	
						?>
							</td>
							<th scope="row">over 이미지</th>
							<td>
						<?
							if (strlen($rs_page_img_over) > 3) {
						?>
							<a href="../../_common/new_download_file.php?menu=page&page_no=<?= $rs_page_no ?>&field=page_img_over"><img src="<?=$g_base_dir?>/upload_data/menu/<?= $rs_page_img_over ?>"></a>
							&nbsp;&nbsp;
							<select name="flag04" style="width:70px;" onchange="javascript:js_fileView(this,'04')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_page_img_over" value="<?= $rs_page_img_over?>">
							<div id="file_change04" style="display:none;">
								<input type="file" name="page_img_over" size="30%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="page_img_over">
							<input type="hidden" name="old_page_img_over" value="">
							<input TYPE="hidden" name="flag04" value="insert">
						<?
							}	
						?>
							</td>
						</tr>

						<tr>
							<th scope="row">스크립트</th>
							<td colspan="3">
								<textarea name="page_script" id="page_script"  style="padding-left:0px;width:730px;height:150px;"><?=$rs_page_script?></textarea>
							</td>
						</tr>


						<tr> 
							<th class="conTxt">내용</th>
							<td colspan="3">
							<?
								// ================================================================== 수정 부분
							?>
								 <span class="fl" style="padding-left:0px;width:740px;height:500px;"><textarea name="page_content" id="page_content"  style="padding-left:0px;width:730px;height:400px;"><?=$rs_page_content?></textarea></span>
							<?
								// ================================================================== 수정 부분
							?>
							</td>
						</tr>
						<tr> 
							<th>로그인 노출여부</th>
							<td colspan="3" class="choices">
								<input type="radio" class="radio" name="rd_login_tf" value="A" <? if (($rs_login_tf =="A") || ($rs_login_tf =="")) echo "checked"; ?>> 전체 노출<span style="width:20px;"></span>
								<input type="radio" class="radio" name="rd_login_tf" value="N" <? if ($rs_login_tf =="N") echo "checked"; ?>> 로그인 전 노출<span style="width:20px;"></span>
								<input type="radio" class="radio" name="rd_login_tf" value="Y" <? if ($rs_login_tf =="Y")echo "checked"; ?>> 로그인 후 노출 
								<input type="hidden" name="login_tf" value="<?= $rs_login_tf ?>"> 
							</td>
						</tr>
						<tr> 
							<th>메뉴 노출여부</th>
							<td colspan="3" class="choices">
								<input type="radio" class="radio" name="rd_menu_tf" value="Y" <? if (($rs_menu_tf =="Y") || ($rs_menu_tf =="")) echo "checked"; ?>> 보이기<span style="width:20px;"></span>
								<input type="radio" class="radio" name="rd_menu_tf" value="N" <? if ($rs_menu_tf =="N")echo "checked"; ?>> 보이지않기 
								<input type="hidden" name="menu_tf" value="<?= $rs_menu_tf ?>"> 
							</td>
						</tr>
						<tr class="last"> 
							<th>노출여부</th>
							<td colspan="3" class="choices">
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
					<?	if ($page_no <> "") {?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<?	} ?>
					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
				</ul>
			</div>
		</section>
		<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
<SCRIPT LANGUAGE="JavaScript">
<!--
var oEditors = [];
 nhn.husky.EZCreator.createInIFrame({
 oAppRef: oEditors,
 elPlaceHolder: "page_content",
 sSkinURI: "../../_common/SE2.1.1.8141/SmartEditor2Skin.html",
 htParams : {bUseToolbar : true, 
 fOnBeforeUnload : function(){ 
  // alert('야') 
 }
 }, 
 fCreator: "createSEditor2"
});
//-->
</SCRIPT>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>