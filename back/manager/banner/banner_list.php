<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
# =============================================================================
# File Name    : banner_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.05.29
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$menu_right = "CS002"; // 메뉴마다 셋팅 해 주어야 합니다

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

	#List Parameter
	$banner_no				= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$banner_type			= $_POST['banner_type']!=''?$_POST['banner_type']:$_GET['banner_type'];
	$sel_banner_lang	= $_POST['sel_banner_lang']!=''?$_POST['sel_banner_lang']:$_GET['sel_banner_lang'];
	
	$banner_no				= trim($banner_no);
	$banner_type			= trim($banner_type);
	$sel_banner_lang	= trim($sel_banner_lang);
	
	if ($sel_banner_lang == "") $sel_banner_lang= "KOR";

	if (empty($banner_type)) {
		$banner_type = "MAIN";
	}

	if ($banner_type == "MAIN") {
		$str_title_name = "메인비주얼관리";
		$str_img_size = "가로:1920px 세로:350px";
		$str_img_real_size = "가로:640px 세로:550px";
		$str_width = "1920";
		$str_height = "350";
		$str_real_width = "640";
		$str_real_height = "550";
	}

	if ($banner_type == "CAMPAIGN") {
		$menu_right = "CS003"; // 메뉴마다 셋팅 해 주어야 합니다
		$str_title_name = "CAMPAIGN 관리";
		$str_img_size = "가로:346px 세로:320px";
		$str_width = "346";
		$str_height = "320";
	}

	if ($banner_type == "right") {
		$menu_right = "CS004"; // 메뉴마다 셋팅 해 주어야 합니다
		$str_title_name = "메인 우축 배너";
		$str_img_size = "가로:200px, 세로:205px";
		$str_width = "200";
		$str_height = "205";
	}

	if ($banner_type == "JPN") {
		$menu_right = "CS005"; // 메뉴마다 셋팅 해 주어야 합니다
		$str_title_name = "메인비주얼관리 (일문)";
		$str_img_size = "가로:708px, 세로:400px";
	}
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
	require "../../_classes/biz/banner/banner.php";
	
	//$arr_rs = getSiteInfo($conn, $site_no);
	
#====================================================================
# Request Parameter
#====================================================================

	$mode					= trim($mode);
	$nPage				= trim($nPage);
	$nPageSize		= trim($nPageSize);
	$search_field	= trim($search_field);
	$search_str		= trim($search_str);

#====================================================================
# DML Process
#====================================================================

	if ($mode == "I") {

#====================================================================
	$savedir1 = $g_physical_path."upload_data/banner";
#====================================================================

		$banner_nm = SetStringToDB($banner_nm);
		$banner_url = SetStringToDB($banner_url);
		
		$disp_seq = "0";

		$banner_img				= upload($_FILES[banner_img], $savedir1, 10 , array('gif', 'jpeg', 'jpg','png'));
		$banner_real_img	= upload($_FILES[banner_real_img], $savedir1, 10 , array('gif', 'jpeg', 'jpg','png'));

		//$file_ext = end(explode('.', $_FILES[banner_img]['name']));
		//$banner_real_img = str_replace(".".$file_ext,"",$_FILES[banner_img]['name']).".".$file_ext;
		$result =  insertBanner($conn, $g_site_no, $sel_banner_lang, $banner_type, $banner_nm, $banner_img, $banner_real_img, $banner_url, $title_nm, $title_img, $title_real_img, $url_type, $disp_seq, $use_tf, $s_adm_no);

		if ($result) {
?>	
<script language="javascript">
		//location.href =  '<?=$_SERVER[PHP_SELF]?>?banner_type=<?=$banner_type?>';
</script>
<?
			//exit;
		}
	}

	if ($mode == "U") {

#====================================================================
		$savedir1 = $g_physical_path."upload_data/banner";
#====================================================================
		# file업로드
		switch ($flag01) {
			case "insert" :
				$banner_img			= upload($_FILES[banner_img], $savedir1, 10 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$banner_img			= $old_banner_img;
			break;
			case "delete" :
				$banner_img			= "";
			break;
			case "update" :
				$banner_img			= upload($_FILES[banner_img], $savedir1, 10 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag02) {
			case "insert" :
				$banner_real_img			= upload($_FILES[banner_real_img], $savedir1, 10 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$banner_real_img			= $old_banner_real_img;
			break;
			case "delete" :
				$banner_real_img			= "";
			break;
			case "update" :
				$banner_real_img			= upload($_FILES[banner_real_img], $savedir1, 10 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		$result = updateBanner($conn, $g_site_no, $sel_banner_lang, $banner_type, $banner_nm, $banner_img, $banner_real_img, $banner_url, $title_nm, $title_img, $title_real_img, $url_type, $use_tf, $s_adm_no, $banner_no);

		if ($result) {
?>	
<script language="javascript">
	location.href =  '<?=$_SERVER[PHP_SELF]?>?banner_type=<?=$banner_type?>&menu_cd=<?=$menu_cd?>';
</script>
<?
			exit;
		}
	}


	if ($mode == "T") {

		updateBannerUseTF($conn, $use_tf, $s_adm_no, $g_site_no, $banner_no);

	}


	if ($mode == "O") {
		
		
		$row_cnt = count($banner_seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_banner_no = $banner_seq_no[$k];

			$result = updateOrderBanner($conn, $k, $g_site_no, $tmp_banner_no);
		
		}
	}


	if ($mode == "D") {
		
		
		$row_cnt = count($chk);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_banner_no = $chk[$k];

			$result = deleteBanner($conn, $s_adm_no, $g_site_no, $tmp_banner_no);
		
		}
	}

	if ($mode == "S") {

		$arr_rs = selectBanner($conn, $g_site_no, $banner_no);
		
		//BANNER_NO, SITE_NO, BANNER_TYPE, BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, BANNER_URL, DISP_SEQ, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
		$rs_banner_no				= trim($arr_rs[0]["BANNER_NO"]); 
		$rs_comm_no					= trim($arr_rs[0]["COMM_NO"]); 
		$rs_banner_lang			= trim($arr_rs[0]["BANNER_LANG"]); 
		$rs_banner_type			= trim($arr_rs[0]["BANNER_TYPE"]); 
		$rs_banner_nm				= SetStringFromDB($arr_rs[0]["BANNER_NM"]); 
		$rs_banner_img			= trim($arr_rs[0]["BANNER_IMG"]); 
		$rs_banner_real_img	= trim($arr_rs[0]["BANNER_REAL_IMG"]); 
		$rs_banner_url			= SetStringFromDB($arr_rs[0]["BANNER_URL"]); 
		$rs_url_type				= trim($arr_rs[0]["URL_TYPE"]); 
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 

	}

	$del_tf = "N";
	$use_tf = "";
#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 5;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntBanner($conn, $g_site_no, $sel_banner_lang, $banner_type, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = ($nListCnt - 1) / $nPageSize + 1 ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//$nPage		 = 1;
	//$nPageSize = 100;

	$arr_rs = listBanner($conn, $g_site_no, $sel_banner_lang, $banner_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

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

function js_save() {

	var frm = document.frm;
	var seq_no = "<?= $banner_no ?>";
	
	frm.banner_nm.value = frm.banner_nm.value.trim();
	
	if (isNull(frm.banner_nm.value)) {
		alert('배너명을 입력해주세요.');
		frm.banner_nm.focus();
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

	if (document.frm.rd_url_type == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_url_type[0].checked == true) {
			frm.url_type.value = "Y";
		} else {
			frm.url_type.value = "N";
		}
	}

	if (isNull(seq_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.seq_no.value = frm.banner_no.value;
	}

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_view(rn, seq) {

	var frm = document.frm;
		
	frm.seq_no.value = seq;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
		
}


function js_toggle(banner_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
		
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.seq_no.value = banner_no;
		frm.use_tf.value = use_tf;
		frm.mode.value = "T";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}
}

function file_change(file) { 
	document.getElementById("file_name").value = file; 
}

var preid = -1;

function js_up(n) {
	
	preid = parseInt(n);

	if (preid > 1) {
		

		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid-1].innerHTML;

		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid-1].cells;

		for(var j=0 ; j < cells1.length; j++) {
			
			if (j != 1) {
				var temp = cells2[j].innerHTML;

				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;

				var tempCode = document.frm.seq_banner_no[preid-2].value;
			
				document.frm.seq_banner_no[preid-2].value = document.frm.seq_banner_no[preid-1].value;
				document.frm.seq_banner_no[preid-1].value = tempCode;
			}
		}
		
		//preid = preid - 1;
		js_change_order();

	} else {
		alert("가장 상위에 있습니다. ");
	}
}


function js_down(n) {

	preid = parseInt(n);

	if ((preid < document.getElementById("t").rows.length-1) && (document.frm.seq_banner_no[preid].value != null)) {

	//alert(preid);
	//return;

		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid+1].innerHTML;
		
		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid+1].cells;
		
		for(var j=0 ; j < cells1.length; j++) {

			if (j != 1) {
				var temp = cells2[j].innerHTML;

			
				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;
	
				var tempCode = document.frm.seq_banner_no[preid-1].value;
				document.frm.seq_banner_no[preid-1].value = document.frm.seq_banner_no[preid].value;
				document.frm.seq_banner_no[preid].value = tempCode;
			}
		}
		
		//preid = preid + 1;
		js_change_order();
	} else{
		alert("가장 하위에 있습니다. ");
	}
}

function js_change_order() {
	
	if(document.getElementById("t").rows.length < 2) {
		alert("순서를 저장할 메뉴가 없습니다");//순서를 저장할 메뉴가 없습니다");
		return;
	}

	document.frm.mode.value = "O";
	document.frm.target = "ifr_hidden";
	document.frm.action = "banner_order_dml.php";
	document.frm.submit();

}

function js_delete() {

	var frm = document.frm;
	var chk_cnt = 0;

	check=document.getElementsByName("chk[]");
	
	for (i=0;i<check.length;i++) {
		if(check.item(i).checked==true) {
			chk_cnt++;
		}
	}
	
	if (chk_cnt == 0) {
		alert("선택 하신 자료가 없습니다.");
	} else {

		bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
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

}

	function js_sel_banner_lang() {
		var frm = document.frm;
		frm.action = "banner_list.php";
		frm.submit();
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

<form id="bbsList" name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="seq_no" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="comm_no" value="<?=$comm_no?>" />
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>" />
<input type="hidden" name="banner_no" value="<?=$banner_no?>" />
<input type="hidden" name="banner_type" value="<?=$banner_type?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					
					<p style="float:right; padding-right:3%; padding-top:25px;">
						<input type="hidden" name="sel_banner_lang" id="sel_banner_lang" value="KOR">
					</p>

					<table summary="이곳에서 메인 비주얼을 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?></caption>
					<tbody>
						<tr>
							<th class="long" style="width:120px"><label for="bannerTit">제목</label></th>
							<td>
								<input type="text" name="banner_nm" value="<?=$rs_banner_nm?>" class="wfull" />
							</td>
						</tr>
						<tr>
							<th class="long"><label for="bannerImg">웹 이미지</label></th>
							<td>
							<?
								if (strlen($rs_banner_img) > 3) {
							?>
							<!--<a href="/_common/download_file.php?menu=banner&banner_no=<?= $rs_banner_no ?>&field=banner_img"><?= $rs_banner_real_img ?></a>-->
								<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_banner_img" value="<?= $rs_banner_img?>">

								<div id="file_change01" style="display:none;">
										<input type="file" id="bannerImg01" class="w50per" name="banner_img" />&nbsp;<?=$str_img_size?>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="bannerImg01" class="w50per" name="banner_img" /><span class="explain"><?=$str_img_size?></span>
								<input type="hidden" name="old_banner_img" value="">
								<input TYPE="hidden" name="flag01" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						<tr>
							<th class="long"><label for="bannerImg">모바일 이미지</label></th>
							<td>
							<?
								if (strlen($rs_banner_real_img) > 3) {
							?>
								<select name="flag02" style="width:70px;" onchange="javascript:js_fileView(this,'02')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>

								<input type="hidden" name="old_banner_real_img" value="<?= $rs_banner_real_img?>">

								<div id="file_change02" style="display:none;">
										<input type="file" id="bannerImg02" class="w50per" name="banner_real_img" />&nbsp;<?=$str_img_real_size?>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="bannerImg02" class="w50per" name="banner_real_img" /><span class="explain"><?=$str_img_real_size?></span>
								<input type="hidden" name="old_banner_real_img" value="">
								<input TYPE="hidden" name="flag02" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						<tr>
							<th class="long"><label for="bannerLink">링크주소</label></th>
							<td><input type="text" name="banner_url" value="<?=$rs_banner_url?>" class="wfull" /></td>
						</tr>

						<tr>
							<th class="long">링크방식</th>
							<td>
								<input type="radio" id="blank" name="rd_url_type" value="Y" <? if (($rs_url_type =="Y") || ($rs_url_type =="")) echo "checked"; ?> class="radio" /> <label for="banLink">새창</label>
								<input type="radio" id="own" name="rd_url_type" value="N" <? if ($rs_url_type =="N") echo "checked"; ?> class="radio" /> <label for="banLink">자기창</label>
								<input type="hidden" name="url_type" value="<?= $rs_url_type ?>">
							</td>
							</tr>
							<tr class="last">
								<th class="long">공개여부</th>
								<td>
									<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /> <label for="all">공개</label>
									<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /> <label for="secret">비공개</label>
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>">
								</td>
							</tr>
						</tbody>
					</table>

					<div class="btnArea">
						<ul class="fRight">
							<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
							<li><a href="javascript:document.frm.reset();"><img src="../images/btn/btn_cancel.gif" alt="취소" /></a></li>
							<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
						</ul>
					</div>

					<table summary="이곳에서 메인 페이지의 배너를 관리하실 수 있습니다" class="secBtm" id='t'>
						<thead>
							<tr>
								<th class="num">순서</th>
								<th class="moveIcon">&nbsp;</th>
								<th class="tit">제목</th>
								<th class="visual">웹이미지</th>
								<th class="visual">모바일이미지</th>
								<th>공개여부</th>
							</tr>
						</thead>
						<tbody>
						<?
								
							if (sizeof($arr_rs) > 0) {
											
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn								= trim($arr_rs[$j]["rn"]);
									$BANNER_NO				= trim($arr_rs[$j]["BANNER_NO"]);
									$BANNER_NM				= trim($arr_rs[$j]["BANNER_NM"]);
									$BANNER_IMG				= trim($arr_rs[$j]["BANNER_IMG"]);
									$BANNER_REAL_IMG	= trim($arr_rs[$j]["BANNER_REAL_IMG"]);
									
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);

									if ($USE_TF == "Y") {
										$STR_USE_TF = "<font color='blue'>공개</font>";
									} else {
										$STR_USE_TF = "<font color='red'>비공개</font>";
									}

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
						?>
							<tr <? if ($j == (sizeof($arr_rs) -1)) echo "class='last'"; ?> >
								<td class="num"><input type="checkbox" name="chk[]" value="<?=$BANNER_NO?>"> <?=$j+1?></td>
								<td class="moveIcon">
									<? if ($USE_TF == "Y") { ?>
									<ul>
										<li><a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/btn/btn_up.gif" alt="up" /></a></li>
										<li><a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/btn/btn_down.gif" alt="down" /></a></li>
									</ul>
									<? } ?>
								</td>
								<td class="tit">
									<a href="javascript:js_view('<?=$rn?>','<?=$BANNER_NO?>');"><?=$BANNER_NM?></a>
									<? if ($USE_TF == "Y") { ?>
									<input type="hidden" name="seq_banner_no" value="<?=$BANNER_NO?>">
									<input type="hidden" name="banner_seq_no[]" value="<?=$BANNER_NO?>">
									<? } ?>
								</td>
								<td>
									<?
										if ($BANNER_IMG == "") {
									?>
									<?=$str_img_size?> 사이즈 <br />이미지 넣어주세요
									<?
										} else {
									?>
									<img src="<?=$g_base_dir?>/upload_data/banner/<?=$BANNER_IMG?>" width="500px" >
									<?
										}
									?>
								</td>
								<td>
									<?
										if ($BANNER_REAL_IMG == "") {
									?>
									<?=$str_img_real_size?> 사이즈 <br />이미지 넣어주세요
									<?
										} else {	
									?>
									<img src="<?=$g_base_dir?>/upload_data/banner/<?=$BANNER_REAL_IMG?>" width="200px" >
									<?
										}
									?>
								</td>

								<td><!--td에 클래스 on/off로 공개/비공개 제어-->
									<a href="javascript:js_toggle('<?=$BANNER_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a>
								</td>
							</tr>
						<?
								}
							} else { 
						?>
							<tr>
								<td height="50" align="center" colspan="7">데이터가 없습니다. </td>
							</tr>
				<? 
					}
				?>
						</tbody>
				</table>
			</fieldset>
			</form>
			<div class="sp20"></div>
			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
			</div>

		</section>
	</section>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
