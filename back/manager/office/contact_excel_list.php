<?session_start();?>
<?
# =============================================================================
# File Name    : office_excel_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.04.27
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

$file_name= iconv("utf-8","euc-kr","사무처-").date("Ymd").".xls";
header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
header( "Content-Disposition: attachment; filename=$file_name" );
header( "Content-Description: orion@giringrim.com" );

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PE003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/office/office.php";

#====================================================================
# Request Parameter
#====================================================================

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
	$con_type = "office";

	if ($order_field == "")
		$order_field = "DIS_SEQ";
	
	if ($order_str=="")
		$order_str = "ASC";

#============================================================
# Page process
#============================================================
	$nPage = 1;
	$nPageSize = 200000;
	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$arr_rs = listOffice($conn, $con_type, $order_field, $order_str, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize);
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$g_title?></title>
</head>
<body>
<h3>사무처안내 리스트</h3>
<!-- List -->
<table border="1">
	<tr>
		<th>사무처</th>
		<th>연락처01</th>
		<th>연락처02</th>
		<th>FAX01</th>
		<th>FAX02</th>
		<th>이메일</th>
		<th>등록일</th>
		<th>노출여부</th>
	</tr>
	<?
		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				
				$rn							= trim($arr_rs[$j]["rn"]);
				$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
				$NAME						= trim($arr_rs[$j]["NAME"]);
				$TEL01					= trim($arr_rs[$j]["TEL01"]);
				$TEL02					= trim($arr_rs[$j]["TEL02"]);
				$FAX01					= trim($arr_rs[$j]["FAX01"]);
				$FAX02					= trim($arr_rs[$j]["FAX02"]);
				$EMAIL					= trim($arr_rs[$j]["EMAIL"]);
				$POST						= trim($arr_rs[$j]["POST"]);
				$ADDRESS				= trim($arr_rs[$j]["ADDRESS"]);
				$STR_LAT				= trim($arr_rs[$j]["STR_LAT"]);
				$STR_LNG				= trim($arr_rs[$j]["STR_LNG"]);
				$EX_INFO01			= trim($arr_rs[$j]["EX_INFO01"]);
				$EX_INFO02			= trim($arr_rs[$j]["EX_INFO02"]);
				$EX_INFO03			= trim($arr_rs[$j]["EX_INFO03"]);
				$DIS_SEQ				= trim($arr_rs[$j]["DIS_SEQ"]);
				$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
				$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
				$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));

				if($USE_TF=="Y"){
					$use_state="노출";
				}else{
					$use_state="노출안함";
				}

	?>
	<tr>
		<td><?=$NAME?></td>
		<td><?=$TEL01?></td>
		<td><?=$TEL02?></td>
		<td><?=$FAX01?></td>
		<td><?=$FAX02?></td>
		<td><?=$EMAIL?></td>
		<td><?=$REG_DATE?></td>
		<td><?=$use_state?></td>
	</tr>
	<?
				}
			} else { 
	?>
	<tr>
		<td align="center" height="50" colspan="8">데이터가 없습니다. </td>
	</tr>
	<? 
		}
	?>
</table>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
