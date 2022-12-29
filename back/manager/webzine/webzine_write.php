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
	$menu_right = "WB001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	$yyyy						= $_POST['yyyy']!=''?$_POST['yyyy']:$_GET['yyyy'];
	$mm							= $_POST['mm']!=''?$_POST['mm']:$_GET['mm'];
	$pub_date				= $_POST['pub_date']!=''?$_POST['pub_date']:$_GET['pub_date'];
	$vol_no					= $_POST['vol_no']!=''?$_POST['vol_no']:$_GET['vol_no'];
	$title					= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$memo						= $_POST['memo']!=''?$_POST['memo']:$_GET['memo'];
	$main_image01		= $_POST['main_image01']!=''?$_POST['main_image01']:$_GET['main_image01'];
	$main_image02		= $_POST['main_image02']!=''?$_POST['main_image02']:$_GET['main_image02'];
	$main_image03		= $_POST['main_image03']!=''?$_POST['main_image03']:$_GET['main_image03'];
	$pdf_image			= $_POST['pdf_image']!=''?$_POST['pdf_image']:$_GET['pdf_image'];
	$pdf_file				= $_POST['pdf_file']!=''?$_POST['pdf_file']:$_GET['pdf_file'];
	$use_tf					= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$old_main_image01		= $_POST['old_main_image01']!=''?$_POST['old_main_image01']:$_GET['old_main_image01'];
	$old_main_image02		= $_POST['old_main_image02']!=''?$_POST['old_main_image02']:$_GET['old_main_image02'];
	$old_main_image03		= $_POST['old_main_image03']!=''?$_POST['old_main_image03']:$_GET['old_main_image03'];
	$old_pdf_image			= $_POST['old_pdf_image']!=''?$_POST['old_pdf_image']:$_GET['old_pdf_image'];
	$old_pdf_file				= $_POST['old_pdf_file']!=''?$_POST['old_pdf_file']:$_GET['old_pdf_file'];

	$flag01							= $_POST['flag01']!=''?$_POST['flag01']:$_GET['flag01'];
	$flag02							= $_POST['flag02']!=''?$_POST['flag02']:$_GET['flag02'];
	$flag03							= $_POST['flag03']!=''?$_POST['flag03']:$_GET['flag03'];
	$flag04							= $_POST['flag04']!=''?$_POST['flag04']:$_GET['flag04'];
	$flag05							= $_POST['flag05']!=''?$_POST['flag05']:$_GET['flag05'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$con_yyyy						= $_POST['con_yyyy']!=''?$_POST['con_yyyy']:$_GET['con_yyyy'];
	$con_mm							= $_POST['con_mm']!=''?$_POST['con_mm']:$_GET['con_mm'];

	$nPage					= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);

	//$title					= SetStringToDB($title);
	//$memo						= SetStringToDB($memo);

	$title					= str_replace("\'", "'",$title);
	$memo						= str_replace("\'", "'",$memo);

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
		$main_image01				= upload($_FILES[main_image01], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
		$main_image02				= upload($_FILES[main_image02], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
		$main_image03				= upload($_FILES[main_image03], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
		$pdf_image					= upload($_FILES[pdf_image], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
		$pdf_file						= upload($_FILES[pdf_file], $savedir1, 1000000 , array('pdf'));

		// 웹진 등록
		$arr_data = array("YYYY"=>$yyyy,
											"MM"=>$mm,
											"PUB_DATE"=>$pub_date,
											"VOL_NO"=>$vol_no,
											"TITLE"=>$title,
											"MEMO"=>$memo,
											"MAIN_IMAGE01"=>$main_image01,
											"MAIN_IMAGE02"=>$main_image02,
											"MAIN_IMAGE03"=>$main_image03,
											"PDF_IMAGE"=>$pdf_image,
											"PDF_FILE"=>$pdf_file,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$new_seq_no = insertWebzine($conn, $arr_data);

		// 이벤트 자동 생성 막음

		$s_date = $yyyy."-".$mm."-10";
		$e_date = date("Y-m-d", strtotime($s_date."1 month"));

		$arr_data_sub = array("W_SEQ_NO"=>$new_seq_no,
											"YYYY"=>$yyyy,
											"MM"=>$mm,
											"TYPE"=>"TYPE03",
											"S_DATE"=>$s_date,
											"E_DATE"=>$e_date,
											"TITLE"=>"독자의견 등록",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		//$result_sub = insertWebzineEvent($conn, $arr_data_sub);

		$arr_data_sub = array("W_SEQ_NO"=>$new_seq_no,
											"YYYY"=>$yyyy,
											"MM"=>$mm,
											"TYPE"=>"TYPE02",
											"S_DATE"=>$s_date,
											"E_DATE"=>$e_date,
											"TITLE"=>"다른그림찾기 이벤트",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		//$result_sub = insertWebzineEvent($conn, $arr_data_sub);

		$arr_data_sub = array("W_SEQ_NO"=>$new_seq_no,
											"YYYY"=>$yyyy,
											"MM"=>$mm,
											"TYPE"=>"TYPE01",
											"S_DATE"=>$s_date,
											"E_DATE"=>$e_date,
											"TITLE"=>"Photo Time",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		//$result_sub = insertWebzineEvent($conn, $arr_data_sub);

		$result = true;

	}

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$main_image01	= upload($_FILES[main_image01], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$main_image01			= $old_main_image01;
			break;
			case "delete" :
				$main_image01			= "";
			break;
			case "update" :
				$main_image01	= upload($_FILES[main_image01], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag02) {
			case "insert" :
				$main_image02	= upload($_FILES[main_image02], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$main_image02			= $old_main_image02;
			break;
			case "delete" :
				$main_image02			= "";
			break;
			case "update" :
				$main_image02	= upload($_FILES[main_image02], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag03) {
			case "insert" :
				$main_image03	= upload($_FILES[main_image03], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$main_image03			= $old_main_image03;
			break;
			case "delete" :
				$main_image03			= "";
			break;
			case "update" :
				$main_image03	= upload($_FILES[main_image03], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag04) {
			case "insert" :
				$pdf_image	= upload($_FILES[pdf_image], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$pdf_image			= $old_pdf_image;
			break;
			case "delete" :
				$pdf_image			= "";
			break;
			case "update" :
				$pdf_image	= upload($_FILES[pdf_image], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag05) {
			case "insert" :
				$pdf_file	= upload($_FILES[pdf_file], $savedir1, 1000000 , array('pdf'));
			break;
			case "keep" :
				$pdf_file			= $old_pdf_file;
			break;
			case "delete" :
				$pdf_file			= "";
			break;
			case "update" :
				$pdf_file	= upload($_FILES[pdf_file], $savedir1, 1000000 , array('pdf'));
			break;
		}

		// 웹진 등록
		$arr_data = array("YYYY"=>$yyyy,
											"MM"=>$mm,
											"PUB_DATE"=>$pub_date,
											"VOL_NO"=>$vol_no,
											"TITLE"=>$title,
											"MEMO"=>$memo,
											"MAIN_IMAGE01"=>$main_image01,
											"MAIN_IMAGE02"=>$main_image02,
											"MAIN_IMAGE03"=>$main_image03,
											"PDF_IMAGE"=>$pdf_image,
											"PDF_FILE"=>$pdf_file,
											"USE_TF"=>$use_tf,
											"UP_ADM"=>$_SESSION['s_adm_no']
											);

		$result = updateWebzine($conn, $arr_data, $seq_no);


		// 이벤트 자동 생성 
		/*
		$s_date = $yyyy."-".$mm."-10";
		$e_date = date("Y-m-d", strtotime($s_date."1 month"));

		$arr_data_sub = array("W_SEQ_NO"=>$seq_no,
											"YYYY"=>$yyyy,
											"MM"=>$mm,
											"TYPE"=>"TYPE03",
											"S_DATE"=>$s_date,
											"E_DATE"=>$e_date,
											"TITLE"=>"독자의견 등록",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$result_sub = insertWebzineEvent($conn, $arr_data_sub);

		$arr_data_sub = array("W_SEQ_NO"=>$seq_no,
											"YYYY"=>$yyyy,
											"MM"=>$mm,
											"TYPE"=>"TYPE02",
											"S_DATE"=>$s_date,
											"E_DATE"=>$e_date,
											"TITLE"=>"Remind Picture 신청",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$result_sub = insertWebzineEvent($conn, $arr_data_sub);

		$arr_data_sub = array("W_SEQ_NO"=>$seq_no,
											"YYYY"=>$yyyy,
											"MM"=>$mm,
											"TYPE"=>"TYPE01",
											"S_DATE"=>$s_date,
											"E_DATE"=>$e_date,
											"TITLE"=>"네잎클로버 찾기",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$result_sub = insertWebzineEvent($conn, $arr_data_sub);
		*/


		//$result = true;
	}

	if ($mode == "D") {
		
		$result = deleteWebzine($conn, $seq_no);

	}


	if ($mode == "S") {
		
		$arr_rs = selectWebzine($conn, $seq_no);
		
		$rs_seq_no				= trim($arr_rs[0]["SEQ_NO"]);
		$rs_yyyy					= trim($arr_rs[0]["YYYY"]);
		$rs_mm						= trim($arr_rs[0]["MM"]);
		$rs_pub_date			= trim($arr_rs[0]["PUB_DATE"]);
		$rs_vol_no				= trim($arr_rs[0]["VOL_NO"]);
		$rs_title					= SetStringFromDB($arr_rs[0]["TITLE"]);
		$rs_memo					= SetStringFromDB($arr_rs[0]["MEMO"]);
		$rs_main_image01	= trim($arr_rs[0]["MAIN_IMAGE01"]);
		$rs_main_image02	= trim($arr_rs[0]["MAIN_IMAGE02"]);
		$rs_main_image03	= trim($arr_rs[0]["MAIN_IMAGE03"]);
		$rs_pdf_image			= trim($arr_rs[0]["PDF_IMAGE"]);
		$rs_pdf_file			= trim($arr_rs[0]["PDF_FILE"]);
		$rs_hit_cnt				= trim($arr_rs[0]["HIT_CNT"]);
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]);
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]);
		$rs_reg_date			= trim($arr_rs[0]["REG_DATE"]);

	} else {
		$rs_yyyy	= date("Y",strtotime("0 month"));
		$rs_mm		= date("m",strtotime("0 month"));
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_yyyy=".$con_yyyy."&con_mm=".$con_mm."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "webzine_list.php<?=$strParam?>";
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
	document.location = "webzine_list.php<?=$strParam?>";
}


function js_save() {

	var frm = document.frm;
	var seq_no = "<?= $seq_no ?>";
	
	if(document.frm.title.value==""){
		alert('웹진타이틀을 입력해주세요.');
		document.frm.title.focus();
		return;
	}

	if(document.frm.vol_no.value==""){
		alert('출판번호를 입력해주세요.');
		document.frm.vol_no.focus();
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
	//alert(frm.use_tf.value);

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

		bDelOK = confirm('웹진을 삭제 하시겠습니까?');
		
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
							<th>년월</th>
							<td colspan="3">
								<select style="width:150px" name="yyyy" id="yyyy">
									<?
										$this_yyyy = date("Y",strtotime("0 month"));

										for ($h = $this_yyyy ; $h > ($this_yyyy -10) ; $h--) {
									?>
									<option value="<?=$h?>" <? if ($h == $rs_yyyy) echo "selected"; ?> ><?=$h?></option>
									<?
										}
									?>
								</select>&nbsp;

								<select style="width:150px" name="mm" id="mm">
									<?
										for ($h = 1 ; $h < 13; $h++) {
											$s_mm = right("0".$h, 2);
											if ($s_mm == $rs_mm) {
									?>
									<option value="<?=$s_mm?>" selected><?=$s_mm?></option>
									<?
											} else {
									?>
									<option value="<?=$s_mm?>"><?=$s_mm?></option>
									<?
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">웹진타이틀</th>
							<td colspan="3">
								<? $str_rs_title = str_replace("\"","&quot;", $rs_title) ?>
								<input type="text" name="title" value="<?=$str_rs_title?>"/>
							</td>
						</tr>
						<tr>
							<th scope="row">출판일</th>
							<td>
								<input type="text" class="date" name="pub_date" value="<?=$rs_pub_date?>" style="width: 120px;" readonly="1"/>
							</td>
							<th scope="row">출판번호</th>
							<td>
								<input type="text" name="vol_no" value="<?=$rs_vol_no?>" style="width: 220px;" />
							</td>
						</tr>

						<tr> 
							<th scope="row">요약설명</th>
							<td colspan="3" style="padding: 10px 10px 10px 15px">
								<textarea style="width: 90%; height:40px" name="memo"><?=$rs_memo?></textarea>
								<div class="sp10"></div>
								* 두줄 정도 입력 하세요.
							</td>
						</tr>

						<tr style="display:none">
							<th class="long"><label for="main_image01">메인비주얼<br>(배경)</label></th>
							<td colspan="3">
							<?
								if (strlen($rs_main_image01) > 3) {
							?>
							<img src="/upload_data/webzine/<?=$rs_main_image01?>" width="200px" >
								<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_main_image01" value="<?= $rs_main_image01?>">

								<div id="file_change" style="display:none;">
										<input type="file" id="main_image01" class="w50per" name="main_image01" />&nbsp;<span class="explain">1920 * 536</span>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="main_image01" class="w50per" name="main_image01" /><span class="explain">1920 * 536</span>
								<input type="hidden" name="old_main_image01" value="">
								<input TYPE="hidden" name="flag01" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						<tr style="display:none">
							<th class="long"><label for="main_image02">메인비주얼<br>(모바일 배경)</label></th>
							<td colspan="3">
							<?
								if (strlen($rs_main_image02) > 3) {
							?>
							<img src="/upload_data/webzine/<?=$rs_main_image02?>" width="200px" >
								<select name="flag02" style="width:70px;" onchange="javascript:js_fileView(this,'02')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_main_image02" value="<?= $rs_main_image02?>">

								<div id="file_change2" style="display:none;">
										<input type="file" id="main_image02" class="w50per" name="main_image02" />&nbsp;<span class="explain">640 * 596</span>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="main_image02" class="w50per" name="main_image02" /><span class="explain">640 * 596</span>
								<input type="hidden" name="old_main_image02" value="">
								<input TYPE="hidden" name="flag02" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						<tr style="display:none">
							<th class="long"><label for="main_image03">메인비주얼<br>(텍스트)</label></th>
							<td colspan="3">
							<?
								if (strlen($rs_main_image03) > 3) {
							?>
							<img src="/upload_data/webzine/<?=$rs_main_image03?>" width="200px" >
								<select name="flag03" style="width:70px;" onchange="javascript:js_fileView(this,'03')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_main_image03" value="<?= $rs_main_image03?>">

								<div id="file_change3" style="display:none;">
										<input type="file" id="main_image03" class="w50per" name="main_image03" />&nbsp;<span class="explain">595 * 370</span>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="main_image03" class="w50per" name="main_image03" /><span class="explain">595 * 370</span>
								<input type="hidden" name="old_main_image03" value="">
								<input TYPE="hidden" name="flag03" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						<tr>
							<th class="long"><label for="pdf_image">리스트<br>(썸네일)</label></th>
							<td colspan="3">
							<?
								if (strlen($rs_pdf_image) > 3) {
							?>
							<img src="/upload_data/webzine/<?=$rs_pdf_image?>" width="200px" >
								<select name="flag04" style="width:70px;" onchange="javascript:js_fileView(this,'04')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_pdf_image" value="<?= $rs_pdf_image?>">

								<div id="file_change4" style="display:none;">
										<input type="file" id="pdf_image" class="w50per" name="pdf_image" />&nbsp;<span class="explain">203 * 169</span>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="pdf_image" class="w50per" name="pdf_image" /><span class="explain">203 * 169</span>
								<input type="hidden" name="old_pdf_image" value="">
								<input TYPE="hidden" name="flag04" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						<tr>
							<th class="long"><label for="pdf_file">PDF</label></th>
							<td colspan="3">
							<?
								if (strlen($rs_pdf_file) > 3) {
							?>
							<a href="../../_common/new_download_file.php?menu=webzine&seq_no=<?= $rs_seq_no ?>&field=pdf_file"><?=$rs_yyyy?>년<?=$rs_mm?>월 웹진.pdf</a>
							&nbsp;&nbsp;
								<select name="flag05" style="width:70px;" onchange="javascript:js_fileView(this,'05')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_pdf_file" value="<?= $rs_pdf_file?>">

								<div id="file_change5" style="display:none;">
										<input type="file" id="pdf_file" class="w50per" name="pdf_file" />&nbsp;<span class="explain">PDF 파일</span>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="pdf_file" class="w50per" name="pdf_file" /><span class="explain">PDF 파일</span>
								<input type="hidden" name="old_pdf_file" value="">
								<input TYPE="hidden" name="flag05" value="insert">
							<?
								}
							?>
							</td>
						</tr>

						<tr> 
							<th scope="row">노출여부</th>
							<td colspan="3">
								<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 보이기<span style="width:20px;"></span>
								<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 보이지않기 
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