<?session_start();?>
<?
# =============================================================================
# File Name    : board_write.php
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
	$bb_code						= $_POST['bb_code']!=''?$_POST['bb_code']:$_GET['bb_code'];

	$bb_code = trim($bb_code);

	//echo $bb_code;

	if ($bb_code == "")
		$bb_code = "BBS_1_1";

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = $bb_code; // 메뉴마다 셋팅 해 주어야 합니다
	require "../../_common/common_header.php";

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/etc/etc.php";
	#require "../../_classes/biz/category/category.php";
	require "../../_classes/biz/board/board.php";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$config_no					= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$bb_code						= $_POST['bb_code']!=''?$_POST['bb_code']:$_GET['bb_code'];
	$bb_no							= $_POST['bb_no']!=''?$_POST['bb_no']:$_GET['bb_no'];

	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$config_no = "";

	$arr_bb_code = explode("_", $bb_code);

	for ($i = 0; $i < sizeof($arr_bb_code) ; $i++) {
		$config_no = $arr_bb_code[$i];
	}

	// 게시판 설정을 구한다
	$arr_rs = selectBoardConfig($conn, $g_site_no, $config_no);

	$b_config_no				= trim($arr_rs[0]["CONFIG_NO"]);
	$b_site_no					= trim($arr_rs[0]["SITE_NO"]);
	$b_board_code				= trim($arr_rs[0]["BOARD_CODE"]);
	$b_board_type				= trim($arr_rs[0]["BOARD_TYPE"]);
	$b_read_group				= trim($arr_rs[0]["READ_GROUP"]);
	$b_write_group			= trim($arr_rs[0]["WRITE_GROUP"]);
	$b_re_tf						= trim($arr_rs[0]["RE_TF"]);
	$b_reply_tf					= trim($arr_rs[0]["REPLY_TF"]);
	$b_html_tf					= trim($arr_rs[0]["HTML_TF"]);
	$b_file_tf					= trim($arr_rs[0]["FILE_TF"]);
	$b_file_cnt					= trim($arr_rs[0]["FILE_CNT"]);
	$b_board_nm					= SetStringFromDB($arr_rs[0]["BOARD_NM"]);
	$b_board_memo				= SetStringFromDB($arr_rs[0]["BOARD_MEMO"]);
	$b_use_tf						= trim($arr_rs[0]["USE_TF"]);
	$b_del_tf						= trim($arr_rs[0]["DEL_TF"]);
	$b_reg_adm					= trim($arr_rs[0]["REG_ADM"]);


#====================================================================
# DML Process
#====================================================================

	if ($mode == "IR") {

#====================================================================
	$savedir1 = $g_physical_path."/upload_data/board";
#====================================================================
/*
		$title		= SetStringToDB($title);
		$contents = SetStringToDB($contents);

		$new_bb_no =  insertBoardReply($conn, $bb_code, $bb_no, $bb_po, $bb_re, $bb_de, $cate_01, $cate_02, $cate_03, $cate_04, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $s_adm_no);

		$file_cnt = count($file_name);

		for($i=0; $i <= $file_cnt; $i++) {

			if ($file_flag[$i] == "insert") {

				$file_name					= multiupload($_FILES[file_name], $i, $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf'));
				$file_rname					= $_FILES[file_name][name][$i];

				$file_size					= $_FILES[file_name][size][$i];
				$file_ext						= end(explode('.', $_FILES[file_name][name][$i]));

				$use_tf = "Y";

				if ($file_name <> "") {
					$result_file = insertBoardFile($conn, $bb_code, $new_bb_no, $file_name, $file_rname, $file_path, $file_size, $file_ext, $s_adm_no);
				}
			}
		}

		if ($new_bb_no) {
			$result = true;
		}
*/
	}


	if ($mode == "U") {

		$result = updateBoardUseTF($conn, $use_tf, $s_adm_no, $bb_code, $bb_no);

	}

	if ($mode == "D") {


	//	$row_cnt = count($chk);

	//	for ($k = 0; $k < $row_cnt; $k++) {

	//		$tmp_banner_no = $chk[$k];

			$result = deleteBoard($conn, $s_adm_no, $bb_code, $bb_no);

//		}
	}

	if ($mode == "R") {

		$arr_rs = selectBoard($conn, $bb_code, $bb_no);

		$rs_bb_no						= trim($arr_rs[0]["BB_NO"]);
		$rs_bb_de						= trim($arr_rs[0]["BB_DE"]);
		$rs_bb_re						= trim($arr_rs[0]["BB_RE"]);
		$rs_bb_po						= trim($arr_rs[0]["BB_PO"]);
		$rs_bb_code					= trim($arr_rs[0]["BB_CODE"]);
		$rs_ref_ip					= trim($arr_rs[0]["REF_IP"]);

		$rs_title						= "[답변] " . trim($arr_rs[0]["TITLE"]);

	}

	if ($mode == "S") {

		//$result = viewChkBoardAsMember($conn,$bb_code, $bb_no, $s_adm_no);

		$arr_rs = selectBoard($conn, $bb_code, $bb_no);

		$rs_bb_no						= trim($arr_rs[0]["BB_NO"]);
		$rs_bb_de						= trim($arr_rs[0]["BB_DE"]);
		$rs_bb_po						= trim($arr_rs[0]["BB_PO"]);
		$rs_bb_re						= trim($arr_rs[0]["BB_RE"]);

		$rs_bb_code					= trim($arr_rs[0]["BB_CODE"]);
		$rs_title						= SetStringFromDB($arr_rs[0]["TITLE"]);
		$rs_writer_id				= SetStringFromDB($arr_rs[0]["WRITER_ID"]);
		$rs_writer_nm				= SetStringFromDB($arr_rs[0]["WRITER_NM"]);
		$rs_writer_pw				= SetStringFromDB($arr_rs[0]["WRITER_PW"]);
		$rs_email						= trim($arr_rs[0]["EMAIL"]);
		$rs_phone						= trim($arr_rs[0]["PHONE"]);
		$rs_homepage				= trim($arr_rs[0]["HOMEPAGE"]);
		$rs_contents				= SetStringFromDB($arr_rs[0]["CONTENTS"]);
		$rs_recomm					= trim($arr_rs[0]["RECOMM"]);
		$rs_cate_01					= trim($arr_rs[0]["CATE_01"]);
		$rs_cate_02					= trim($arr_rs[0]["CATE_02"]);
		$rs_cate_03					= trim($arr_rs[0]["CATE_03"]);
		$rs_cate_04					= trim($arr_rs[0]["CATE_04"]);

		$rs_file_rnm					= trim($arr_rs[0]["FILE_RNM"]);
		$rs_file_path					= trim($arr_rs[0]["FILE_PATH"]);

		$rs_keyword					= trim($arr_rs[0]["KEYWORD"]);
		$rs_reply						= trim($arr_rs[0]["REPLY"]);
		$rs_reply_state			= trim($arr_rs[0]["REPLY_STATE"]);
		$rs_reply_date			= trim($arr_rs[0]["REPLY_DATE"]);
		$rs_top_tf					= trim($arr_rs[0]["TOP_TF"]);
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]);
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]);
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]);
		$rs_comment_tf					= trim($arr_rs[0]["COMMENT_TF"]);

		$rs_keyword				= trim($arr_rs[0]["KEYWORD"]);



		$rs_thumb_img				= trim($arr_rs[0]["THUMB_IMG"]);

		$rs_file_nm					= trim($arr_rs[0]["FILE_NM"]);
		$rs_file_rnm				= trim($arr_rs[0]["FILE_RNM"]);
		$rs_file_path				= trim($arr_rs[0]["FILE_PATH"]);
		$rs_file_size				= trim($arr_rs[0]["FILE_SIZE"]);
		$rs_file_ext				= trim($arr_rs[0]["FILE_EXT"]);

		$rs_ref_ip					= trim($arr_rs[0]["REF_IP"]);

		if ($rs_reply_state == "Y")
			$str_reply_state = "<font color='navy'>답변완료</font>";
		else
			$str_reply_state = "<font color='red'>대기중</font>";

		$content  = $rs_contents;

		$arr_rs_files = listBoardFile($conn, $bb_code, $bb_no);

	} else {
		$rs_writer_nm = $s_adm_nm;
		$rs_writer_pw = $s_adm_no;
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&mode=S&bb_code=".$bb_code."&bb_no=".$bb_no."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;

	if ($mode == "AU") {

		//echo "ss";
		$reply2 = iconv("UTF-8","EUC-KR",$reply);
		$reply = SetStringToDB($reply);

		$mailto=$email;
		$SUBJECT="사회적기업학회 - 문의하신 내용 답변드립니다.";
		$NAME="사회적기업학회";
		$NAME2 = iconv("UTF-8","EUC-KR",$NAME);


	//	sendMail_QNA($EMAIL, $NAME2, $SUBJECT, $reply2, $mailto);

		$result = updateQnaAnswer($conn, $reply, $s_adm_no, $reply_state, $bb_code, $bb_no);


		if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "board_read.php<?=$strParam?>";
</script>
</head>
</html>
<?
			exit;
		}
	}

	if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "board_list.php<?=$strParam?>";
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
<style>

table.rowstable03 { width: 95%; }
table.rowstable03 td.dot { color: #555555; text-align: center; vertical-align: middle; }
table.rowstable03 td.modeual_nm { text-align: left;  padding-top: 15px; padding-left: 10px; padding-right: 15px;}
table.rowstable03 td.modeual_cont { text-align: left;  padding-top: 5px; padding-bottom: 5px; padding-left: 15px; padding-right: 15px;  }
table.rowstable03 td.end { text-align: left;  padding-top: 5px; padding-left: 15px; padding-right: 15px;}

</style>
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
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/board.js"></script>
<script type="text/javascript" src="../js/boardcomment.js?<?=date("YmdHis")?>"></script>

<script language="javascript" type="text/javascript">


function js_list() {
	document.location = "board_list.php<?=$strParam?>";
}

function js_reply() {
	var frm = document.frm;
	frm.mode.value = "R";
	frm.target = "";
	frm.method = "get";
	frm.action = "board_write.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var bb_no = "<?= $bb_no ?>";

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (isNull(bb_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.bb_no.value = frm.bb_no.value;
	}

<? if ($mode == "R") {?>
		frm.mode.value = "IR";
<? }?>

	frm.method = "post";
	frm.target = "";
	//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_answer() {

	var frm = document.refrm;
	var bb_no = "<?= $bb_no ?>";
	frm.reply.value=frm.reply.value.trim();
	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.
	if(frm.reply.value!=""){
		frm.reply_state.value = "Y";
	}else{
		alert('답변내용이 없습니다.');
		return;
	}
	if (isNull(bb_no)) {
		return;
	} else {
		frm.mode.value = "AU";
		frm.bb_no.value = bb_no;
	}

	frm.method = "post";
	frm.target = "";
	//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
	frm.action = "board_read.php";
	frm.submit();

}

function js_view() {

	var frm = document.frm;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "post";
	frm.action = "board_write.php";
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

function js_move() {
	var frm = document.frm;

	NewWindow('about:blank', 'pop_move_board', '390', '230', 'NO');
	frm.target = "pop_move_board";
	frm.action = "pop_move_board.php";
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

			<h3 class="conTitle"><?=$b_board_nm?>&nbsp;&nbsp;&nbsp;&nbsp;</h3>
			<table summary="이곳에서 <?=$b_board_nm?>을 관리하실 수 있습니다" class="bbsRead">
				<caption><?=$b_board_nm?></caption>

				<thead>
				<?if($b_board_type!="QNA"){

						if ($rs_cate_01) {
							if(($bb_code!="GRBBS_1_2")&&($bb_code!="GRBBS_1_12")&&($bb_code!="GRBBS_1_11")){
							?>
							<tr>
								<th scope="row">공지 여부</th>
								<td>
								<? if ($rs_top_tf == "Y" ) { echo "공지"; } else {echo "공지안함"; } ?>
								</td>
								<th scope="row">카테고리</th>
								<!--td>
								[<?=$rs_cate_01?>]
								</td-->
								<td>
								[<?=$b_board_type?>]
								</td>
							</tr>
					<?
							}
						} else {
								if(($bb_code!="GRBBS_1_2")&&($bb_code!="GRBBS_1_12")&&($bb_code!="GRBBS_1_11")){
								?>
						<tr>
							<th scope="row">공지 여부</th>
							<td olspan="3">
							<? if ($rs_top_tf == "Y" ) { echo "공지"; } else {echo "공지안함"; } ?>
							</td>
							<th scope="row">게시판</th>
							<td >
							<?=$b_board_type?>
							</td>
						</tr>
					<?
							}
						}
					}
					?>
				</thead>
				<tbody>
				<?
					if(($bb_code=="GRBBS_1_2")||($bb_code=="GRBBS_1_11")){
				?>
					<tr>
						<th scope="row">논문 제목(국문)</th>
						<td colspan="3">
							<?=$rs_title?>
						</td>
					</tr>
					<tr>
						<th scope="row">논문 제목(영문)</th>
						<td colspan="3">
							<?=$rs_cate_02?>
						</td>
					</tr>
				<?
					}elseif($bb_code=="GRBBS_1_12"){
				?>
					<tr>
						<th scope="row">논문명</th>
						<td colspan="3">
							<?=$rs_title?>
						</td>
					</tr>
				<?
					}else{
				?>
					<tr>
						<th scope="row">제목</th>
						<td colspan="3">
							<?=$rs_title?>
						</td>
					</tr>
				<?}

					if($bb_code=="GRBBS_1_2"){
				?>
				<tr>
					<th scope="row">투고자성명(국문)</th>
					<td><?=$rs_writer_nm?></td>
					<th scope="row">투고자성명(영문)</th>
					<td><?=$rs_cate_03?></td>
				</tr>
				<tr>
					<th scope="row">소속 및 직위</th>
					<td><?=$rs_cate_04?></td>
					<th scope="row">세부 전공</th>
					<td><?=$rs_homepage?></td>
				</tr>
				<tr>
					<th scope="row">휴대전화</th>
					<td><?=$rs_cate_01?></td>
					<th scope="row">자택전화</th>
					<td><?=$rs_phone?></td>
				</tr>
				<tr>
					<th scope="row">이메일</th>
					<td colspan="3"><?=$rs_email?></td>
				</tr>
				<tr>
					<th scope="row">주소(우편물 수령)</th>
					<td colspan="3">(<?=$rs_file_nm?>) <?=$rs_file_rnm?> <?=$rs_file_path?></td>
				</tr>
				<?
					}elseif($bb_code=="GRBBS_1_11"){
				?>
				<tr>
					<th scope="row">투고자성명(국문)</th>
					<td><?=$rs_writer_nm?></td>
					<th scope="row">투고자성명(영문)</th>
					<td><?=$rs_cate_03?></td>
				</tr>
				<tr>
					<th scope="row">소속 및 직위</th>
					<td><?=$rs_cate_04?></td>
					<th scope="row">세부 전공</th>
					<td><?=$rs_homepage?></td>
				</tr>
				<tr>
					<th scope="row">First name </th>
					<td><?=$rs_writer_id?></td>
					<th scope="row">Last name</th>
					<td><?=$rs_keyword?></td>
				</tr>
				<tr>
					<th scope="row">E-mail</th>
					<td colspan="3"><?=$rs_email?></td>
				</tr>

				<tr>
					<th scope="row">Contact</th>
					<td colspan="3">[<?=$rs_cate_01?>] <?=$rs_phone?></td>
				</tr>


				<tr>
					<th scope="row">Address</th>
					<td colspan="3">(<?=getDcodeName($conn,"COUNTRY",$rs_file_rnm);?>) <?=$rs_file_path?></td>
				</tr>
				<?
					}elseif($bb_code=="GRBBS_1_12"){
				?>
				<tr>
					<th scope="row">저자</th>
					<td><?=$rs_writer_nm?> (<?=$rs_writer_id?>)</td>
					<th scope="row">이메일</th>
					<td><?=$rs_email?></td>
				</tr>

				<?}else{?>
				<tr>
					<th scope="row">작성자</th>
					<td><?=$rs_writer_nm?> (<?=$rs_writer_id?>)</td>
					<th scope="row">이메일</th>
					<td><?=$rs_email?></td>
				</tr>
				<?}?>

					<? if ($b_board_type == "MOVIE") {?>
					<?
						$HOMEPAGE = SetStringFromDB($rs_homepage);
						if($HOMEPAGE){
							if(strpos($HOMEPAGE,"youtu.be") !== false){
								$temp_youtobe01=explode("/" ,$HOMEPAGE);
								$temp_youtobe01_count=count($temp_youtobe01);
								$play_url=$temp_youtobe01[$temp_youtobe01_count-1];
							}
						}

						$youtobe_img="http://i4.ytimg.com/vi/".$play_url."/1.jpg";
					?>

					<tr>
						<th scope="row">영상</th>
						<td colspan="3">
							<? if (($rs_cate_03) && ($play_url == "")) { ?>
							<?=SetStringFromDB($rs_cate_03)?>
							<? } else { ?>
							<iframe width="591" id="play_now" height="369" src="http://www.youtube.com/embed/<?=$play_url?>?wmode=opaque" frameborder="0" allowfullscreen></iframe>
							<? } ?>
						</td>
					</tr>
					<? } ?>

					<? if ($b_board_type == "QNA") {?>
					<tr style='display:none'>
						<th scope="row">연락처</th>
						<td><?=$rs_phone?></td>
						<th scope="row">답변 여부</th>
						<td><?=$str_reply_state?></td>
					</tr>
					<? } ?>

					<?if($b_board_type!="QNA"){
						if(($bb_code!="GRBBS_1_2")&&($bb_code!="GRBBS_1_11")){
						?>
						<? if ($rs_thumb_img) { ?>
						<tr>
							<th scope="row">썸네일<BR>이미지</th>
							<td colspan="3">
								<img src="/upload_data/board/simg_180/<?=$rs_thumb_img?>">
							</td>
						</tr>
							<? } ?>
						<? } ?>
					<? } ?>

					<?
						if ($b_file_tf == "Y") {

							# ==========================================================================
							# Result List
							# ==========================================================================
							#Cnt = 0
							$f_Cnt = 0;
					?>
					<tr>
						<th scope="row">첨부파일</th>
						<td colspan="3">
					<?
							if (sizeof($arr_rs_files) > 0) {
								for ($j = 0 ; $j < sizeof($arr_rs_files); $j++) {

									//FILE_NO, BB_CODE, BB_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT
									$RS_FILE_NO			= trim($arr_rs_files[$j]["FILE_NO"]);
									$RS_BB_CODE			= trim($arr_rs_files[$j]["BB_CODE"]);
									$RS_BB_NO				= trim($arr_rs_files[$j]["BB_NO"]);
									$RS_FILE_NM			= trim($arr_rs_files[$j]["FILE_NM"]);
									$RS_FILE_RNM		= trim($arr_rs_files[$j]["FILE_RNM"]);
									$RS_FILE_PATH		= trim($arr_rs_files[$j]["FILE_PATH"]);
									$RS_FILE_SIZE		= trim($arr_rs_files[$j]["FILE_SIZE"]);
									$RS_FILE_EXT		= trim($arr_rs_files[$j]["FILE_EXT"]);
									$RS_HIT_CNT			= trim($arr_rs_files[$j]["HIT_CNT"]);

									if ($RS_FILE_NM <> "") {
					?>
							<a href="../../_common/new_download_file.php?menu=boardfile&file_no=<?= $RS_FILE_NO ?>"><?=$RS_FILE_RNM?></a>&nbsp;&nbsp;
					<?
									}
									$f_Cnt = $f_Cnt + 1;
								}
							}
					?>
						</td>
					</tr>
					<?
						}
					?>

					<tr style="display:none">
						<th scope="row">홈페이지</th>
						<td colspan="3">
							<?if($rs_homepage){?>
							<a href="<?=$rs_homepage?>" target="_blank"><?=$rs_homepage?></a>
							<?}?>
						</td>
					</tr>

					<?
					if(($bb_code!="GRBBS_1_2")&&($bb_code!="GRBBS_1_11")){?>
					<tr class="conTxt">
						<td colspan="4" id="contents_td" >
					<?

						if ($b_html_tf == "Y" && $bb_code != "BBS_1_1") {

								$rs_contents = str_replace("&quot;","\"", $rs_contents);
								$rs_contents = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' \\2 \\3", $rs_contents);
					?>
								<?=$rs_contents?>
					<? } else { ?>
								<?=stripslashes(nl2br($rs_contents))?>

					<? }  ?>
						</td>
					</tr>
					<?}?>


					<tr style="display:none">
						<th scope="row">키워드</th>
						<td colspan="3">
							<?=$rs_keyword?>
						</td>
					</tr>



					<? if ($b_board_type == "GALLERYxx" || $b_board_type == "BLOGxx" || $b_board_type == "EVENTxx") {?>
					<tr>
						<th scope="row">Facebook<BR>댓글</th>
						<td colspan="3">
						<iframe src="/site/sns_facebook/app20130207/index.php?bb_code=<?=$bb_code?>&bb_no=<?=$bb_no?>" width="100%" height="500px" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
						</td>
					</tr>
					<? } ?>

					<?if($bb_code!="GRBBS_1_2"){?>
					<tr>
						<th scope="row">노출여부</th>
						<td	colspan="3">
						<? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "보이기"; ?>
						<? if (($rs_use_tf !="Y") || ($rs_use_tf =="")) echo "보이지않기"; ?>
						</td>
					</tr>
					<?}else{?>
					<tr>
						<th scope="row">승인여부</th>
						<td	colspan="3">
						<? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "승인"; ?>
						<? if (($rs_use_tf !="Y") || ($rs_use_tf =="")) echo "대기"; ?>
						</td>
					</tr>
					<?}?>

					<tr>
						<th scope="row">등록일</th>
						<td	colspan="3">
							<?=$rs_reg_date?>
						</td>
					</tr>

				</tbody>
			</table>

			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fLeft">
					<!--
					<? if ($sPageRight_I == "Y" && $bb_no <> "" && $b_re_tf == "Y") { ?>
					<li><a href="javascript:js_reply();"><img src="../images/btn/btn_reply.gif" alt="답변" /></a></li>
					<? } ?>

					<? if ($sPageRight_U == "Y" && $bb_no <> "" && $b_board_type == "QNA") { ?>
					<li><a href="javascript:js_answer();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
					-->

					<? if ($sPageRight_U == "Y" && $bb_no <> "") { ?>
					<li><a href="javascript:js_view();"><img src="../images/btn/btn_rewrite.gif" alt="수정" /></a></li>
					<? } ?>

					<? if ($sPageRight_U == "N" && $bb_no <> "" && $s_adm_no == $rs_reg_adm) { ?>
					<li><a href="javascript:js_view();"><img src="../images/btn/btn_rewrite.gif" alt="수정" /></a></li>
					<? } ?>


					<? if ($sPageRight_D == "Y" && $bb_no <> "") { ?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<? } ?>

					<? if ($sPageRight_D == "N" && $bb_no <> "" && $s_adm_no == $rs_reg_adm) { ?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<? } ?>
				</ul>
				<ul class="fRight">
					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
				</ul>
			</div>

				<? if ($b_board_type == "QNA"){?>
				<br />
			<table summary="이곳에서 <?=$b_board_nm?>을 관리하실 수 있습니다" class="bbsRead">
			<form name="refrm" method="post" >
			<input type="hidden" name="rn" value="" />
			<input type="hidden" name="mode" value="" />
			<input type="hidden" name="bb_no" value="<?=$bb_no?>" />
			<input type="hidden" name="bb_code" value="<?=$bb_code?>" />
			<input type="hidden" name="nPage" value="<?=$nPage?>" />
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
			<input type="hidden" name="reply_state" value="">
			<input type="hidden" name="email" value="<?=$rs_email?>">

				<caption><?=$b_board_nm?> 답변</caption>

				<tbody>
						<tr>
							<th scope="row">내용</th>
							<td colspan="3" style="padding: 10px 10px 10px 15px">
							<?
								// ================================================================== 수정 부분
							?>
								 <span class="fl" style="padding-left:0px;width:740px;height:400px;"><textarea name="reply" id="reply"  style="padding-left:0px;width:730px;height:300px;"><?=$rs_reply?></textarea></span>
							<?
								// ================================================================== 수정 부분
							?>
							</td>
						</tr>
					<tr>
						<th scope="row">답변일</th>
						<td>
							<?=$rs_reply_date?>
						</td>
						<th scope="row"></th>
						<td>
						</td>
					</tr>

				</tbody>
				</form>
			</table>
			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fLeft">

					<? if ($sPageRight_I == "Y" && $bb_no <> "" && $b_re_tf == "Y") { ?>
					<li><a href="javascript:js_reply();"><img src="../images/btn/btn_reply.gif" alt="답변" /></a></li>
					<? } ?>

					<? if ($sPageRight_U == "Y" && $bb_no <> "" && $b_board_type == "QNA") { ?>
					<li><a href="javascript:js_answer();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
				</ul>
			</div>
			<?}?>



<form id="replyFrm" name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="bb_no" value="<?=$bb_no?>" />
<input type="hidden" name="bb_code" value="<?=$bb_code?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="bb_po" value="<?=$rs_bb_po?>">
<input type="hidden" name="bb_de" value="<?=$rs_bb_de?>">
<input type="hidden" name="bb_re" value="<?=$rs_bb_re?>">
<input type="hidden" name="ref_ip" value="<?=$rs_ref_ip?>">

<input type="hidden" name="reply_state" value="">
<input type="hidden" name="reply_mailtoname" value="<?=$rs_writer_nm?>">
<input type="hidden" name="reply_mailto" value="<?=$rs_email?>">
<input type="hidden" name="reply_title" value="<?=$rs_title?>">

<input type="hidden" name="writer_nm" value="<?=$rs_writer_nm?>" />
<input type="hidden" name="writer_pw" value="<?=$rs_writer_pw?>" />
<input type="hidden" name="email" value="" />
<input type="hidden" name="homepage" value="" />

				<? if ($b_reply_tf == "Y" && $rs_bb_no <> "" && $b_board_type == "EVENT") { ?>

				<fieldset style="display:none">
					<legend>댓글쓰기</legend>
					<table summary="게시글에 대한 댓글을 입력하실 수 있습니다." style="display:none">
						<caption>댓글란</caption>
						<thead>
							<tr>
								<th>댓글</th>
								<td>
									<ul>
										<li>이름 : <input type="text" id="girin_comment_writer_nm"  value="<?=$rs_writer_nm?>" maxlength="20" /></li>
										<li>비밀번호 : <input type="password" id="girin_comment_writer_pw" maxlength="20" value="<?=$s_adm_no?>" /></li>
									</ul>
									<p>
										<textarea id="girin_comment_contents" onFocus="if (this.value=='간단한 댓글을 올리실 수 있습니다.') this.value=''" onBlur="if (this.value == ''){this.value='간단한 댓글을 올리실 수 있습니다.'}">간단한 댓글을 올리실 수 있습니다.</textarea>
										<a href="javascript:gbc_reply_write();"><img src="../images/btn/btn_reply.gif" alt="댓글입력" /></a>
										<input type="hidden" id="girin_comment_write_nm" value="<?=$comment_writer_nm?>">
										<input type="hidden" id="girin_comment_write_no" value="<?=$s_adm_no?>">
										<input type="hidden" id="girin_comment_cate_03" value="<?=$s_adm_id?>">
										<input type="hidden" id="girin_comment_cate_04" value="<?=$s_adm_no?>">
										<input type="hidden" id="girin_comment_use_tf" value="Y">
									</p>
								</td>
							</tr>
						</thead>
					</table>
				</fieldset>
				<div class="sp20"></div>
				<div id="girin_board_comment" parent_board_code="<?=$rs_bb_code?>" parent_board_no="<?=$rs_bb_no?>"  width="100%" height="550">
				<ul id="girin_board_comment_ul" style="width:100%;height:100%"><ul>
				<div class="sp20"></div>
				<? } ?>

			</form>
		</section>
		<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
<script type="text/javascript">
	window.onload=function() {

		resizeBoardImage('680');
		//drawFont();
	}

</script>

<? if ($b_board_type == "QNA"){?>
<SCRIPT LANGUAGE="JavaScript">
<!--
<? if ($b_html_tf == "Y") { ?>
var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "reply",
	sSkinURI: "../../_common/SE2.1.1.8141/SmartEditor2Skin.html",
	htParams : {bUseToolbar : true,
	fOnBeforeUnload : function(){
		// alert('야')
	}
	},
	fCreator: "createSEditor2"
});
<? } ?>
//-->
</SCRIPT>
<?}?>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>