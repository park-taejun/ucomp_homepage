<?session_start();?>
<?
# =============================================================================
# File Name    : pop_group_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2016-06-14
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "MB007"; // 메뉴마다 셋팅 해 주어야 합니다

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


	#sPageRight_R 해당 메뉴 읽기 권한
	#sPageRight_I 해당 메뉴 입력 권한
	#sPageRight_U 해당 메뉴 수정 권한
	#sPageRight_D 해당 메뉴 삭제 권한
	#sPageRight_  해당 메뉴 코드 메뉴 관리 에서 지정된 코드 값을 사용한다.

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/group/group.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$group_no					= $_POST['group_no']!=''?$_POST['group_no']:$_GET['group_no'];
	$group_kind				= $_POST['group_kind']!=''?$_POST['group_kind']:$_GET['group_kind'];
	$m_level					= $_POST['m_level']!=''?$_POST['m_level']:$_GET['m_level'];
	$m_seq01					= $_POST['m_seq01']!=''?$_POST['m_seq01']:$_GET['m_seq01'];
	$m_seq02					= $_POST['m_seq02']!=''?$_POST['m_seq02']:$_GET['m_seq02'];
	$m_seq03					= $_POST['m_seq03']!=''?$_POST['m_seq03']:$_GET['m_seq03'];
	$m_seq04					= $_POST['m_seq04']!=''?$_POST['m_seq04']:$_GET['m_seq04'];
	$m_seq05					= $_POST['m_seq05']!=''?$_POST['m_seq05']:$_GET['m_seq05'];

	$group_name				= $_POST['group_name']!=''?$_POST['group_name']:$_GET['group_name'];
	$group_sido				= $_POST['group_sido']!=''?$_POST['group_sido']:$_GET['group_sido'];
	$group_flag				= $_POST['group_flag']!=''?$_POST['group_flag']:$_GET['group_flag'];
	$group_content		= $_POST['group_content']!=''?$_POST['group_content']:$_GET['group_content'];
	$group_info01			= $_POST['group_info01']!=''?$_POST['group_info01']:$_GET['group_info01'];
	$group_info02			= $_POST['group_info02']!=''?$_POST['group_info02']:$_GET['group_info02'];
	$group_info03			= $_POST['group_info03']!=''?$_POST['group_info03']:$_GET['group_info03'];
	$group_cd					= $_POST['group_cd']!=''?$_POST['group_cd']:$_GET['group_cd'];
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

	$group_no		= SetStringToDB($group_no);
	$m_level		= SetStringToDB($m_level);
	$m_seq01		= SetStringToDB($m_seq01);
	$m_seq02		= SetStringToDB($m_seq02);
	$m_seq03		= SetStringToDB($m_seq03);
	$m_seq04		= SetStringToDB($m_seq04);
	$m_seq05		= SetStringToDB($m_seq05);
	$group_name	= SetStringToDB($group_name);
	$group_flag	= SetStringToDB($group_flag);
	$group_cd		= SetStringToDB($group_cd);

	$use_tf			= SetStringToDB($use_tf);

	$result = false;

#====================================================================
	$savedir1 = $g_physical_path."upload_data/menu";
#====================================================================
	
	if ($mode == "I") {
		
		$group_img				= upload($_FILES[group_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$group_img_over		= upload($_FILES[group_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));

		$result = insertGroup($conn, $group_kind, $group_sido, $m_level, $m_seq01, $m_seq02, $m_seq03, $m_seq04, $group_name, $group_flag, $group_img, $group_img_over, $group_content, $group_info01, $group_info02, $group_info03, $use_tf, $s_adm_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "조직 등록", "Insert");
	
	}


	if ($mode == "S") {

		$arr_rs = selectGroup($conn, $group_no);

		$rs_group_no				= trim($arr_rs[0]["GROUP_NO"]);
		$rs_group_sido			= trim($arr_rs[0]["GROUP_SIDO"]);
		$rs_group_kind			= trim($arr_rs[0]["GROUP_KIND"]);
		$rs_group_name			= trim($arr_rs[0]["GROUP_NAME"]);
		$rs_group_flag			= trim($arr_rs[0]["GROUP_FLAG"]); 
		$rs_group_cd				= trim($arr_rs[0]["GROUP_CD"]); 
		$rs_group_img				= trim($arr_rs[0]["GROUP_IMG"]); 
		$rs_group_img_over	= trim($arr_rs[0]["GROUP_IMG_OVER"]); 
		$rs_group_content		= trim($arr_rs[0]["GROUP_CONTENT"]); 
		$rs_group_info01		= trim($arr_rs[0]["GROUP_INFO01"]); 
		$rs_group_info02		= trim($arr_rs[0]["GROUP_INFO02"]); 
		$rs_group_info03		= trim($arr_rs[0]["GROUP_INFO03"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 

	}


	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$group_img					= upload($_FILES[group_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$group_img			= $old_group_img;
			break;
			case "delete" :
				$group_img			= "";
			break;
			case "update" :
				$group_img					= upload($_FILES[group_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag02) {
			case "insert" :
				$group_img_over		= upload($_FILES[group_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$group_img_over		= $old_group_img_over;
			break;
			case "delete" :
				$group_img_over		= "";
			break;
			case "update" :
				$group_img_over		= upload($_FILES[group_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		$result = updateGroup($conn, $group_kind, $group_name, $group_sido, $group_img, $group_img_over, $group_content, $group_info01, $group_info02, $group_info03, $use_tf, $s_adm_no, $group_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "조직 수정 (".$menu_no.") ", "Update");

	}

	if ($mode == "D") {
		$result = deleteGroup($conn, $s_adm_no, $group_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "조직 삭제(".$menu_no.") ", "Delete");
	}

	if ($rs_group_cd <> "") {

		if (strlen($m_level) == 3) {
			$level_str = "1 뎁스";
		} else if (strlen($m_level) == 6) {
			$level_str = "2 뎁스";
		} else if (strlen($m_level) == 9) {
			$level_str = "3 뎁스";
		} else if (strlen($m_level) == 12) {
			$level_str = "4 뎁스";
		} else if (strlen($m_level) == 15) {
			$level_str = "5 뎁스";
		}

	} else {

		if (strlen($m_level) == 0) {
			$level_str = "1 뎁스";
		} else if (strlen($m_level) == 3) {
			$level_str = "2 뎁스";
		} else if (strlen($m_level) == 6) {
			$level_str = "3 뎁스";
		} else if (strlen($m_level) == 9) {
			$level_str = "4 뎁스";
		} else if (strlen($m_level) == 12) {
			$level_str = "5 뎁스";
		}

	}

	if (strlen($m_level) >= 3) {
		$arr_rs_sido	= selectGroupAsGroupCD($conn, left($m_level,3));
		$group_sido		= trim($arr_rs_sido[0]["GROUP_SIDO"]);
	}

	
#=================================================================
# Get Result set from stored procedure
#=================================================================
	if ($result) {
?>	
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		sel_group_kind=opener.document.frm.sel_group_kind.value;
		opener.document.location = "group_list.php?sel_group_kind="+sel_group_kind;
		self.close();
</script>
<?
		exit;
	}	
?>
<!-- Top Menu 시작 -->
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<style type="text/css">
	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
</style>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->

<script language="javascript">
	
	function js_save() {
		
		var group_no = "<?= $group_no ?>";
		var frm = document.frm;

		if (frm.group_name.value == "") {
			alert("시도당, 조직명을 입력하세요.");
			frm.group_name.focus();
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

		if (isNull(group_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}
		
		frm.target = "";
		frm.action = "pop_group_write.php";
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
	

	function js_delete() {
		var frm = document.frm;

		bDelOK = confirm('해당 메뉴를 삭제 하시겠습니까?\n\n해당 메뉴에 하위 메뉴도 모두 삭제 됩니다.');
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
			frm.action = "pop_group_write.php";
			frm.submit();
		}
	}
	
	function js_group_sido() {
		var frm =document.frm;
		frm.group_name.value = frm.group_sido.value;
	}

</script>
</head>
<body id="popup_code">

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="">
<input type="hidden" name="group_kind" value="<?=$group_kind?>">
<input type="hidden" name="group_no" value="<?=$group_no?>">
<input type="hidden" name="m_level" value="<?=$m_level?>">
<input type="hidden" name="m_seq01" value="<?=$m_seq01?>">
<input type="hidden" name="m_seq02" value="<?=$m_seq02?>">
<input type="hidden" name="m_seq03" value="<?=$m_seq03?>">
<input type="hidden" name="m_seq04" value="<?=$m_seq04?>">

<div id="popupwrap_code">
	<h1>조직명 등록</h1>
	<div id="postsch_code">
		<h2>* 당원관리에서 사용할 조직을 등록하는 화면 입니다.</h2>
		<div class="addr_inp">

		<table cellpadding="0" cellspacing="0" width="95%">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
		
						<colgroup>
							<col width="20%">
							<col width="80%">
						</colgroup>
						<tr>
							<th>조직분류</th>
							<td>
								<?=$level_str?>
							</td>
						</tr>
						<? if ($level_str == "1 뎁스") { ?>
						<tr>
							<th>시도당</th>
							<td>
								<?= makeSelectBoxOnChange($conn,"AREA_CD","group_sido", "250","시도당을 선택하세요.", "",$rs_group_sido);?>
								<input type="hidden" name="group_name" value="<?= $rs_group_name ?>"/>
							</td>
						</tr>
						<? } else { ?>
						<tr>
							<th>조직명</th>
							<td>
								<input type="text" name="group_name" value="<?= $rs_group_name ?>" style="width:90%;" class="txt" />
								<input type="hidden" name="group_sido" value="<?= $group_sido ?>"/>
							</td>
						</tr>
						<? } ?>
						<tr>
							<th>조직설명</th>
							<td>
								<textarea name="group_content" class="txt" style="width:90%;border: 1px solid #a6a6a6;" rows="4"><?= $rs_group_content ?></textarea>
							</td>
						</tr>
						<tr>
							<th>사용여부</th>
							<td>
								<input type="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
								<input type="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?>> 미사용
								<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
							</td>
						<tr>
					</table>
				</td>
			</tr>
		</table>
		</div>
		
		<div class="btn">
				<? if ($menu_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
			<a href="javascript:js_save();"><img src="../images/admin/btn_regist_02.gif" alt="등록" /></a>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
			<a href="javascript:js_save();"><img src="../images/admin/btn_regist_02.gif" alt="등록" /></a>
					<? } ?>
				<? }?>
				<? if ($menu_no <> "") {?>
					<? if ($sPageRight_D == "Y") {?>
			<a href="javascript:js_delete();"><img src="../images/admin/btn_delete.gif" alt="삭제" /></a>
					<? } ?>
				<? }?>

		</div>
	</div>
	<br />
	<div class="bot_close"><a href="javascript: window.close();"><img src="../images/admin/icon_pclose.gif" alt="닫기" /></a></div>
</div>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>
