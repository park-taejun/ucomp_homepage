<?session_start();?>
<?
# =============================================================================
# File Name    : inquiry_read.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2013.06.19
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$seq_no				= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$state_tf			= $_POST['state_tf']!=''?$_POST['state_tf']:$_GET['state_tf'];
	$nPage				= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$con_cate_03	= $_POST['con_cate_03']!=''?$_POST['con_cate_03']:$_GET['con_cate_03'];
	$cp_type			= $_POST['cp_type']!=''?$_POST['cp_type']:$_GET['cp_type'];
	$con_ask_code	= $_POST['con_ask_code']!=''?$_POST['con_ask_code']:$_GET['con_ask_code'];
	$order_field	= $_POST['order_field']!=''?$_POST['order_field']:$_GET['order_field'];
	$order_str		= $_POST['order_str']!=''?$_POST['order_str']:$_GET['order_str'];
	$nPageSize		= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field	= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str		= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	

#==============================================================================
# Confirm right
#==============================================================================

	$menu_right = "AS001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/inquiry/inquiry.php";

#====================================================================
# DML Process
#====================================================================



	if ($mode == "U") {
		
		$reply	= SetStringToDB($reply); 

		$result = updateInquiryReply($conn, $seq_no, $reply, $reply_state, $s_adm_no);

		// 메일 전송 루틴 추가 
		if ($reply_state == "Y") {

			if ($send_email <> "") {
				
				$arr_rs = selectinquiry($conn, (int)$seq_no);
				$rs_contents		= SetStringFromDB($arr_rs[0]["CONTENTS"]);
				$rs_in_name			= SetStringFromDB($arr_rs[0]["IN_NAME"]); 

				$mail_contents = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> Welcome to DSR Corp &amp; DSR Wire Corp </TITLE>
</HEAD>
<BODY>
<table>
	<tr>
		<td><img src="http://dev.dsrcorp.com/images/email/mail_top.gif"></td>
	</tr>
	<tr>
		<td>
안녕하세요. DSR 입니다.<br /><br />
<b>문의하신 내용.</b><br /><br />'.nl2br($rs_contents).'<br><br><br>
<b>답변 내용.</b><br /><br />'.nl2br($reply).'<br><br>
		</td>
	</tr>
	<tr>
		<td><img src="http://dev.dsrcorp.com/images/email/mail_bot.gif"></td>
	</tr>
</table>
</BODY>
</HTML>';

				$title	= $rs_in_name."님의 문의에 대한 답변 입니다.";
				$name		= "DSR 담당자";

				//$send_result = sendMail($_SESSION['s_adm_email'], $name, $title, $mail_contents, $send_email);

			}
		}

		$mode = "S";
	}

	if ($mode == "D") {
		$result = deleteInquiry($conn, $s_adm_no, (int)$seq_no);
	}

	if ($mode == "S") {

		$arr_rs = selectinquiry($conn, (int)$seq_no);

		//SEQ_NO, ORDER_NO, CATE_CODE, LANG, TITLE, ASK_CODE, COM_NAME, IN_NAME, AREA, 
		//PHONE, HPHONE, EMAIL, ZIP_CODE, ADDR1, ADDR2, CONTENTS, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE,
		//USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE


		$rs_seq_no			= trim($arr_rs[0]["SEQ_NO"]); 
		$rs_order_no		= trim($arr_rs[0]["ORDER_NO"]); 
		$rs_cate_code		= trim($arr_rs[0]["CATE_CODE"]); 
		$rs_lang				= trim($arr_rs[0]["LANG"]); 
		$rs_ask_code		= trim($arr_rs[0]["ASK_CODE"]); 
		$rs_title				= SetStringFromDB($arr_rs[0]["TITLE"]); 
		$rs_com_name		= SetStringFromDB($arr_rs[0]["COM_NAME"]); 
		$rs_in_name			= SetStringFromDB($arr_rs[0]["IN_NAME"]); 

		$rs_area				= trim($arr_rs[0]["AREA"]); 
		$rs_phone				= trim($arr_rs[0]["PHONE"]); 
		$rs_hphone			= trim($arr_rs[0]["HPHONE"]); 
		$rs_email				= trim($arr_rs[0]["EMAIL"]); 
		$rs_zip_code		= trim($arr_rs[0]["ZIP_CODE"]); 
		$rs_addr1				= trim($arr_rs[0]["ADDR1"]); 
		$rs_addr2				= trim($arr_rs[0]["ADDR2"]); 
		$rs_contents		= SetStringFromDB($arr_rs[0]["CONTENTS"]); 

		$rs_reply				= SetStringFromDB($arr_rs[0]["REPLY"]);
		$rs_reply_adm		= trim($arr_rs[0]["REPLY_ADM"]); 
		$rs_reply_date	= trim($arr_rs[0]["REPLY_DATE"]); 
		$rs_reply_state	= trim($arr_rs[0]["REPLY_STATE"]); 
		$rs_reg_date		= trim($arr_rs[0]["REG_DATE"]); 

		$rs_use_tf						= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf						= trim($arr_rs[0]["DEL_TF"]); 


		if ($rs_cate_code == "AS03") {
			$arr_goods_detail = listOrderDetail($conn, $rs_order_no);
		}
	}

	$str_list_param = "?nPage=".$nPage;
	$str_list_param = $str_list_param."&nPageSize=".$nPageSize;
	$str_list_param = $str_list_param."&con_lang=".$con_lang."&con_cate_code=".$con_cate_code;
	$str_list_param = $str_list_param."&start_date=".$start_date."&end_date=".$end_date;
	$str_list_param = $str_list_param."&order_field=".$order_field."&order_str=".$order_str."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {
		$str_view_param = "?nPage=".$nPage;
		$str_view_param = $str_view_param."&nPageSize=".$nPageSize;
		$str_view_param = $str_view_param."&con_lang=".$con_lang."&con_cate_code=".$con_cate_code;
		$str_view_param = $str_view_param."&start_date=".$start_date."&end_date=".$end_date;
		$str_view_param = $str_view_param."&order_field=".$order_field."&order_str=".$order_str."&search_field=".$search_field."&search_str=".$search_str;
		$str_view_param = $str_view_param."&seq_no=".$seq_no."&mode=S";
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
<? if ($mode == "D") { ?>
		document.location.href = "inquiry_list.php<?=$str_list_param?>";
<? } else { ?>
		document.location.href = "inquiry_read.php<?=$str_view_param?>";
<? } ?>
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
<script type="text/javascript" src="../../../_common/SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript">

function js_list() {
	document.location.href = "inquiry_list.php<?=$str_list_param?>";
}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('자료를 삭제 하시겠습니까?');
	
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		//frm.action = "inquiry_read.php";
		frm.submit();
	}
}

function js_save() {
	var frm = document.frm;

	if (document.frm.rd_state_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_state_tf[0].checked == true) {
			frm.reply_state.value = "Y";
		} else {
			frm.reply_state.value = "N";
		}
	}

	frm.mode.value = "U";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	//frm.action = "inquiry_read.php";
	frm.submit();
}

function js_excel_print() {
	var frm = document.frm;
	frm.mode.value = "S";
	frm.action = "inquiry_read_excel.php";
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

<form name="frm" method="post">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=(int)$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>" />

<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

<input type="hidden" name="order_field" value="<?=$order_field?>">
<input type="hidden" name="order_str" value="<?=$order_str?>">

<input type="hidden" name="con_lang" value="<?=$con_lang?>">
<input type="hidden" name="con_cate_code" value="<?=$con_cate_code?>">

<input type="hidden" name="send_email" value="<?=$rs_email?>">

			<h3 class="conTitle">
				<?=$p_menu_name?>
			</h3>
			<div class="sp0"></div>
			<!--<span class="fr" style="padding-right:50px"><a href="javascript:js_excel_print();" class="btn_type6">엑셀로 받기</a></span>-->
			<div class="sp5"></div>

			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
				<colgroup>
					<col width="15%" />
					<col width="35%" />
					<col width="15%" />
					<col width="35%" />
				</colgroup>
				<thead>
					<!--
					<tr>
						<td colspan="4" style="padding:10px 10px 10px 10px">
							* 제품주문의 경우 하단에 주문 정보를 조회 하실 수 있습니다.
							답변이 완료된 문의인 경우 완료로 처리해 주십시오.
						</td>
					</tr>
					-->
				</thead>
				<tbody>
					<tr>
						<th scope="row">구분</th>
						<td colspan="3">
							<?= getDcodeName($conn, "ASK_CODE", $rs_ask_code) ?>
						</td>
					</tr>
					<tr>
						<th scope="row">제목</th>
						<td colspan="3">
							<?= $rs_title ?>
						</td>
					</tr>

					<tr>
						<th scope="row">성명</th>
						<td>
							<?= $rs_in_name ?>
						</td>
						<th scope="row">이메일</th>
						<td>
							<a href="mailto:<?=$rs_email?>"><?=$rs_email?></a>
						</td>
					</tr>
					<tr>
						<th scope="row">휴대전화번호</th>
						<td colspan="3">
							<?= $rs_hphone ?>
						</td>
					</tr>

					<tr>
						<th scope="row">문의내용</th>
						<td colspan="3">
							<?=nl2br($rs_contents)?> 
						</td>
					</tr>
					<tr>
						<th scope="row">답변</th>
						<td colspan="3">
							<textarea name="reply" style="width:90%;height:200px"><?=$rs_reply?></textarea> 
						</td>
					</tr>

					<tr>
						<th scope="row">처리여부</th>
						<td colspan="3">
							<input type="radio" class="radio" name="rd_state_tf" value="Y" <? if (($rs_reply_state =="Y") || ($rs_reply_state =="")) echo "checked"; ?>> 답변완료 <span style="width:20px;"></span>
							<input type="radio" class="radio" name="rd_state_tf" value="N" <? if ($rs_reply_state =="N") echo "checked"; ?>> 답변전
							<input type="hidden" name="reply_state" value="<?= $rs_reply_state ?>"> 
						</td>
					</tr>
					

				</tbody>
			</table>

			<div class="btnArea">
				<ul class="fRight">
				<? if ((int)$seq_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
					<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
				<? }?>

					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>

				<? if ((int)$seq_no <> "") {?>
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