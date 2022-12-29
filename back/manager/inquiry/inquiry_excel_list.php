<?session_start();?>
<?
# =============================================================================
# File Name    : inquiry_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright : Copyright @C&C Corp. All Rights Reserved.
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
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/inquiry/inquiry.php";
	require "../../_classes/biz/order/order.php";

	$str_title = iconv("UTF-8","EUC-KR","문의내용리스트");

	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );

#====================================================================
# Request Parameter
#====================================================================
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
<font size=3><b>문의 내용 리스트</b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<th>문의구분</th>
		<th>이름</th>
		<th>이메일</th>
		<th>메모</th>
	</tr>
<?
	$row_cnt = count($chk_no);
	for ($k = 0; $k < $row_cnt; $k++) {
		$str_seq_no = $chk_no[$k];
		
		$arr_rs = selectinquiry($conn, (int)$str_seq_no);

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

		if ($rs_cate_code == "AS03") {
			$arr_goods_detail = listOrderDetail($conn, $rs_order_no);
		} else {
			$arr_goods_detail = null;
		}

?>
	<tr>
		<td><?= getDcodeName($conn, "INQUIRY_TYPE", $rs_cate_code) ?></td>
		<td><?= $rs_in_name ?></td>
		<td><?= $rs_email ?></td>
		<td><?= $rs_contents ?></td>
	</tr>
<?
	}
?>
</table>
		</td>
	</tr>
</table>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>