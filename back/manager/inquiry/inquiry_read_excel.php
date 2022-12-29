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
	require "../../_classes/biz/order/order.php";


	$str_title = iconv("UTF-8","EUC-KR","문의내용");

	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );

#====================================================================
# DML Process
#====================================================================

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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?=$g_title?></title>
</head>
<body>
<table style='border-collapse:collapse;table-layout:fixed;width:460pt' width=880>
	<tr>
		<td>

<font size=3><b>문의 내용 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<th>사이트</th>
		<td>
			<?= getDcodeName($conn, "LANG", $rs_lang) ?>
		</td>
		<th>문의구분</th>
		<td>
			<?= getDcodeName($conn, "INQUIRY_TYPE", $rs_cate_code) ?>
		</td>
	</tr>
	<? if ($rs_cate_code == "AS02") {?>
	<tr>
		<th>구분</th>
		<td colspan="3">
			<?= getDcodeName($conn, "ASK_CODE", $rs_ask_code) ?>
		</td>
	</tr>
	<? } ?>
	<tr>
		<th>제목</th>
		<td colspan="3">
			<?= $rs_title ?>
		</td>
	</tr>

	<tr>
		<th>회사명</th>
		<td>
			<?= $rs_com_name ?>
		</td>
		<th>성명</th>
		<td>
			<?= $rs_in_name ?>
		</td>
	</tr>
	<tr>
		<th>소재지역</th>
		<td>
			<? if (($rs_lang == "KOR") || ($rs_lang == "MOB")) { ?>
			<?= getDcodeName($conn, "AREA", $rs_area) ?>
			<? } else {?>
			<?= getDcodeName($conn, "W_AREA", $rs_area) ?>
			<? } ?>
		</td>
		<th>이메일</th>
		<td>
			<a href="mailto:<?=$rs_email?>"><?=$rs_email?></a>
		</td>
	</tr>

	<tr>
		<th>전화번호</th>
		<td>
			<?= $rs_phone ?>
		</td>
		<th>휴대전화번호</th>
		<td>
			<?= $rs_hphone ?>
		</td>
	</tr>

	<tr>
		<th>주소</th>
		<td colspan="3">
			<? if ($rs_zip_code) { ?> [<?=$rs_zip_code?>] <? } ?> <?=$rs_addr1?> 
		</td>
	</tr>

	<tr>
		<th>메모</th>
		<td colspan="3">
			<?=nl2br($rs_contents)?> 
		</td>
	</tr>
	<? if ($rs_cate_code == "AS03") {?>
	<tr>
		<td colspan="4">
			* 주문 제품 정보
		</td>
	</tr>
	<?
		if (sizeof($arr_goods_detail) > 0) {
			for ($j = 0 ; $j < sizeof($arr_goods_detail); $j++) {
				$F						= trim($arr_goods_detail[$j]["F"]);
				$V						= trim($arr_goods_detail[$j]["V"]);
	?>
	<tr>
		<th colspan="2"><?=SetStringFromDB($F)?></th>
		<td colspan="2"><?=SetStringFromDB($V)?></td>
	</tr>
	<?
				}
			}
	?>
	<? } ?>
	<tr>
		<th scope="row">답변</th>
		<td colspan="3">
			<?=nl2br($rs_reply)?>
		</td>
	</tr>

	<tr>
		<th scope="row">처리여부</th>
		<td colspan="3">
			<? if (($rs_reply_state =="Y") || ($rs_reply_state =="")) echo "답변완료"; ?>
			<? if ($rs_reply_state =="N") echo "답변전"; ?>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>