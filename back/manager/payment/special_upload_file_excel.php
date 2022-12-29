<?
ini_set('memory_limit',-1);
session_start();
?>
<?
# =============================================================================
# File Name    : upload_file_excel.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2016-03-15
# Modify Date  : 
#	Copyright    : Copyright @giringrim Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PM003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/payment/payment.php";
#====================================================================
# Request Parameter
#====================================================================

	$file_name=iconv("UTF-8", "EUC-KR","미등록특별당비당원리스트-".date("Ymd").".xls");

//	  header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
//	  header( "Content-Disposition: attachment; filename=$file_name" );
//	  header( "Content-Description: orion70kr@gmail.com" );

	$arr_rs = listSpeMemberTemp($conn, $p_no, $temp_no);

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<style>td { mso-number-format:\@; } </style> 
<title><?=$g_title?></title>
</head>
<body>
<TABLE border=1>
	<tr>
		<td align='center' bgcolor='#F4F1EF'>이름</td>
		<td align='center' bgcolor='#F4F1EF'>생년월일</td>
		<td align='center' bgcolor='#F4F1EF'>휴대전화</td>
		<td align='center' bgcolor='#F4F1EF'>특별당비금액</td>
	</tr>
					<?
						$nCnt = 0;
						
						if (sizeof($arr_rs) > 0) {
							
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
								$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
								$P_SEQ_NO				= trim($arr_rs[$j]["P_SEQ_NO"]);
								$M_NAME					= trim($arr_rs[$j]["M_NAME"]);
								$M_BIRTH				= trim($arr_rs[$j]["M_BIRTH"]);
								$M_HP						= trim($arr_rs[$j]["M_HP"]);
								$AMOUNT			= trim($arr_rs[$j]["AMOUNT"]);
								
					?>
	<tr>
		<td bgColor='#FFFFFF' align='left'><?=$M_NAME?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_BIRTH?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_HP?></td>
		<td bgColor='#FFFFFF' align='left'><?=$AMOUNT?></td>
	</tr>
					<?			
										$err_str = "";
									}
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