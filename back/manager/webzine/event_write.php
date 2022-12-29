<?session_start();?>
<?
# =============================================================================
# File Name    : webzine_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2017-02-05
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

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = "WB002"; // 메뉴마다 셋팅 해 주어야 합니다

	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/webzine/webzine.php";

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$seq_no					= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$type						= $_POST['type']!=''?$_POST['type']:$_GET['type'];
	$s_date					= $_POST['s_date']!=''?$_POST['s_date']:$_GET['s_date'];
	$s_hour					= $_POST['s_hour']!=''?$_POST['s_hour']:$_GET['s_hour'];
	$s_min					= $_POST['s_min']!=''?$_POST['s_min']:$_GET['s_min'];
	$e_date					= $_POST['e_date']!=''?$_POST['e_date']:$_GET['e_date'];
	$e_hour					= $_POST['e_hour']!=''?$_POST['e_hour']:$_GET['e_hour'];
	$e_min					= $_POST['e_min']!=''?$_POST['e_min']:$_GET['e_min'];
	$title					= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$memo						= $_POST['memo']!=''?$_POST['memo']:$_GET['memo'];
	$wiiner					= $_POST['wiiner']!=''?$_POST['wiiner']:$_GET['wiiner'];
	$image01				= $_POST['image01']!=''?$_POST['image01']:$_GET['image01'];
	$use_tf					= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$old_image01		= $_POST['old_image01']!=''?$_POST['old_image01']:$_GET['old_image01'];

	$flag01							= $_POST['flag01']!=''?$_POST['flag01']:$_GET['flag01'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$con_yyyy						= $_POST['con_yyyy']!=''?$_POST['con_yyyy']:$_GET['con_yyyy'];
	$con_mm							= $_POST['con_mm']!=''?$_POST['con_mm']:$_GET['con_mm'];
	$con_type						= $_POST['con_type']!=''?$_POST['con_type']:$_GET['con_type'];

	$webzine_info				= $_POST['webzine_info']!=''?$_POST['webzine_info']:$_GET['webzine_info'];

	$arr_webzine_info = explode("^",$webzine_info);
	
	$w_seq_no = $arr_webzine_info[0];
	$yyyy			= $arr_webzine_info[1];
	$mm				= $arr_webzine_info[2];

	$nPage					= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);

	$title					= SetStringToDB($title);
	$memo						= trim($memo);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);

#====================================================================
	$savedir1 = $g_physical_path."upload_data/webzine";
#====================================================================

#====================================================================
# DML Process
#====================================================================

	if ($mode == "I") {

		// 파일 첨부 
		$image01				= upload($_FILES[image01], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));

		// 이벤트 등록
		$arr_data = array("W_SEQ_NO"=>$w_seq_no,
											"YYYY"=>$yyyy,
											"MM"=>$mm,
											"TYPE"=>$type,
											"S_DATE"=>$s_date,
											"S_HOUR"=>$s_hour,
											"S_MIN"=>$s_min,
											"E_DATE"=>$e_date,
											"E_HOUR"=>$e_hour,
											"E_MIN"=>$e_min,
											"TITLE"=>$title,
											"MEMO"=>$memo,
											"IMAGE01"=>$image01,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$new_seq_no = insertWebzineEvent($conn, $arr_data);

		$result = true;

	}

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$image01	= upload($_FILES[image01], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$image01			= $old_image01;
			break;
			case "delete" :
				$image01			= "";
			break;
			case "update" :
				$image01	= upload($_FILES[image01], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		// 이벤트 등록
		$arr_data = array("W_SEQ_NO"=>$w_seq_no,
											"YYYY"=>$yyyy,
											"MM"=>$mm,
											"TYPE"=>$type,
											"S_DATE"=>$s_date,
											"S_HOUR"=>$s_hour,
											"S_MIN"=>$s_min,
											"E_DATE"=>$e_date,
											"E_HOUR"=>$e_hour,
											"E_MIN"=>$e_min,
											"TITLE"=>$title,
											"MEMO"=>$memo,
											"IMAGE01"=>$image01,
											"USE_TF"=>$use_tf,
											"UP_ADM"=>$_SESSION['s_adm_no']
											);

		$result = updateWebzineEvent($conn, $arr_data, $seq_no);

		//$result = true;
	}

	if ($mode == "D") {
		
		$result = deleteWebzineEvent($conn, $seq_no);

	}


	if ($mode == "S") {
		
		$arr_rs = selectWebzineEvent($conn, $seq_no);
		
		$rs_seq_no				= trim($arr_rs[0]["SEQ_NO"]);
		$rs_w_seq_no			= trim($arr_rs[0]["W_SEQ_NO"]);
		$rs_yyyy					= trim($arr_rs[0]["YYYY"]);
		$rs_mm						= trim($arr_rs[0]["MM"]);
		$rs_type					= trim($arr_rs[0]["TYPE"]);
		$rs_s_date				= trim($arr_rs[0]["S_DATE"]);
		$rs_s_hour				= trim($arr_rs[0]["S_HOUR"]);
		$rs_s_min					= trim($arr_rs[0]["S_MIN"]);
		$rs_e_date				= trim($arr_rs[0]["E_DATE"]);
		$rs_e_hour				= trim($arr_rs[0]["E_HOUR"]);
		$rs_e_min					= trim($arr_rs[0]["E_MIN"]);
		$rs_title					= SetStringFromDB($arr_rs[0]["TITLE"]);
		$rs_memo					= trim($arr_rs[0]["MEMO"]);
		$rs_image01				= trim($arr_rs[0]["IMAGE01"]);
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]);
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]);
		$rs_reg_date			= trim($arr_rs[0]["REG_DATE"]);

	} else {
		$rs_yyyy	= date("Y",strtotime("0 month"));
		$rs_mm		= date("m",strtotime("0 month"));
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_yyyy=".$con_yyyy."&con_mm=".$con_mm."&con_type=".$con_type."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "event_list.php<?=$strParam?>";
</script>
</head>
</html>
<?
		mysql_close($conn);
		exit;
	}

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
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
<script type="text/javascript" src="../js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<script language="javascript" type="text/javascript">

$(document).ready(function() {
	$(".date").datepicker({
		dateFormat: "yy-mm-dd"
	});
});

function js_list() {
	document.location = "event_list.php<?=$strParam?>";
}


function js_save() {

	var frm = document.frm;
	var seq_no = "<?= $seq_no ?>";

	if(document.frm.webzine_info.value==""){
		alert('웹진을 선택해주세요.');
		document.frm.webzine_info.focus();
		return;
	}

	if(document.frm.type.value==""){
		alert('구분을 입력해주세요.');
		document.frm.type.focus();
		return;
	}

	if(document.frm.title.value==""){
		alert('타이틀을 입력해주세요.');
		document.frm.title.focus();
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
	frm.method = "get";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function file_change(file) { 
	document.getElementById("file_name").value = file; 
}


function js_delete() {

	var frm = document.frm;
//	var chk_cnt = 0;

//	check=document.getElementsByName("chk[]");
	
//	for (i=0;i<check.length;i++) {
//		if(check.item(i).checked==true) {
//			chk_cnt++;
//		}
//	}
	
//	if (chk_cnt == 0) {
//		alert("선택 하신 자료가 없습니다.");
//	} else {

		bDelOK = confirm('이벤트을 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}

//	}
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
	if (idx == 03) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change3").style.display = "inline";
		} else {
			document.getElementById("file_change3").style.display = "none";
		}
	}
	if (idx == 04) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change4").style.display = "inline";
		} else {
			document.getElementById("file_change4").style.display = "none";
		}
	}
	if (idx == 05) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change5").style.display = "inline";
		} else {
			document.getElementById("file_change5").style.display = "none";
		}
	}
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
<input type="hidden" name="mode" value="" />
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

			<h3 class="conTitle"><?=$p_menu_name?>&nbsp;&nbsp;&nbsp;&nbsp;</h3>
			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
				<tr>
						<tr>
							<th>웹진 선택</th>
							<td colspan="3">
								<?
									$webzine_info = $rs_w_seq_no."^".$rs_yyyy."^".$rs_mm;
								?>
								<?=makeSelectBoxWebzine($conn,"webzine_info","200","선택하세요.","",$webzine_info)?>
							</td>
						</tr>
						<tr>
							<th scope="row">구분</th>
							<td colspan="3">
								<?= makeSelectBox($conn,"EVENT_TYPE","type","125","선택","",$rs_type)?>
							</td>
						</tr>
						<tr>
							<th scope="row">타이틀</th>
							<td colspan="3">
								<? $str_rs_title = str_replace("\"","&quot;", $rs_title) ?>
								<input type="text" name="title" value="<?=$str_rs_title?>"/>
							</td>
						</tr>

						<tr>
							<th scope="row">당첨자 메지시</th>
							<td colspan="3">
								<textarea name="memo"><?=$rs_memo?></textarea>
							</td>
						</tr>

						<tr>
							<th scope="row">시작일</th>
							<td>
								<input type="text" class="date" name="s_date" value="<?=$rs_s_date?>" style="width: 120px;" readonly="1"/>
								<?= makeSelectBox($conn,"TIME","s_hour","80","","",$rs_s_hour)?>
								<select name="s_min" id="s_min" style="width:80px">
								<?
									for ($k = 0 ; $k < 60 ; $k++) {
										$str_k = right(("0".$k),2);

										$str_selected = "";
										if ($rs_s_min == $str_k) $str_selected  = "selected";
								?>
									<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
								<?
									}
								?>
								</select>
							</td>
							<th scope="row">종료일</th>
							<td>
								<input type="text" class="date" name="e_date" value="<?=$rs_e_date?>" style="width: 120px;" readonly="1"/>
								<?= makeSelectBox($conn,"TIME","e_hour","80","","",$rs_e_hour)?>
								<select name="e_min" id="e_min" style="width:80px">
								<?
									for ($k = 0 ; $k < 60 ; $k++) {
										$str_k = right(("0".$k),2);

										$str_selected = "";
										if ($rs_e_min == $str_k) $str_selected  = "selected";
								?>
									<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
								<?
									}
								?>
								</select>
							</td>
						</tr>

						<tr>
							<th class="long"><label for="image01">썸네일</label></th>
							<td colspan="3">
							<?
								if (strlen($rs_image01) > 3) {
							?>
							<img src="/upload_data/webzine/<?=$rs_image01?>" width="200px" >
								<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_image01" value="<?= $rs_image01?>">

								<div id="file_change" style="display:none;">
										<input type="file" id="image01" class="w50per" name="image01" />&nbsp;<span class="explain">257 * 156</span>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="image01" class="w50per" name="image01" /><span class="explain">257 * 156</span>
								<input type="hidden" name="old_image01" value="">
								<input TYPE="hidden" name="flag01" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						<tr> 
							<th scope="row">사용여부</th>
							<td colspan="3">
								<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 공개<span style="width:20px;"></span>
								<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 비공개
								<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
							</td>
						</tr>

					</table>

					<div class="btnArea">
						<ul class="fRight">
							<? 
								if ($s_adm_no == $rs_reg_adm || $sPageRight_I == "Y") {
									echo '<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>';
									if ($seq_no <> "") {
										if($sPageRight_D=="Y"){
											echo '<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>';
										}
									}
								}
							?>
							<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
						</ul>
					</div>
				</section>
<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
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