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
	$menu_right = "MB003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/member/member.php";
#====================================================================
# Request Parameter
#====================================================================

	$file_name=iconv("UTF-8", "EUC-KR","미등록당원리스트-".date("Ymd").".xls");

	  header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	  header( "Content-Disposition: attachment; filename=$file_name" );
	  header( "Content-Description: orion70kr@gmail.com" );

	$arr_rs = listMemberTemp($conn, $temp_no);

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
		<td align='center' bgcolor='#F4F1EF'>성별</td>
		<td align='center' bgcolor='#F4F1EF'>나이</td>
		<td align='center' bgcolor='#F4F1EF'>생년월일</td>
		<td align='center' bgcolor='#F4F1EF'>주민번호</td>
		<td align='center' bgcolor='#F4F1EF'>휴대전화</td>
		<td align='center' bgcolor='#F4F1EF'>자택전화</td>
		<td align='center' bgcolor='#F4F1EF'>우편번호</td>
		<td align='center' bgcolor='#F4F1EF'>광역시도당</td>
		<td align='center' bgcolor='#F4F1EF'>주소(시,군,구)</td>
		<td align='center' bgcolor='#F4F1EF'>상세주소</td>
		<td align='center' bgcolor='#F4F1EF'>부분</td>
		<td align='center' bgcolor='#F4F1EF'>직업</td>
		<td align='center' bgcolor='#F4F1EF'>직장명</td>
		<td align='center' bgcolor='#F4F1EF'>직장우편번호</td>
		<td align='center' bgcolor='#F4F1EF'>직장주 소(시,군,구</td>
		<td align='center' bgcolor='#F4F1EF'>직장상세주소</td>
		<td align='center' bgcolor='#F4F1EF'>소속단체</td>
		<td align='center' bgcolor='#F4F1EF'>결제방식(CMS/휴대전화)</td>
		<td align='center' bgcolor='#F4F1EF'>계좌번호</td>
		<td align='center' bgcolor='#F4F1EF'>은행명</td>
		<td align='center' bgcolor='#F4F1EF'>당비약정액</td>
		<td align='center' bgcolor='#F4F1EF'>지역위원회</td>
	</tr>
					<?
						$nCnt = 0;
						
						if (sizeof($arr_rs) > 0) {
							
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
								$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
								$M_NAME					= trim($arr_rs[$j]["M_NAME"]);
								$M_SEX					= trim($arr_rs[$j]["M_SEX"]);
								$M_AGE					= trim($arr_rs[$j]["M_AGE"]);
								$M_BIRTH				= trim($arr_rs[$j]["M_BIRTH"]);
								$M_JUMIN				= trim($arr_rs[$j]["M_JUMIN"]);
								$M_TEL					= trim($arr_rs[$j]["M_TEL"]);
								$M_HP						= trim($arr_rs[$j]["M_HP"]);
								$M_ZIP1					= trim($arr_rs[$j]["M_ZIP1"]);
								$M_ADDR1				= trim($arr_rs[$j]["M_ADDR1"]);
								$M_ADDR2				= trim($arr_rs[$j]["M_ADDR2"]);
								$SIDO						= trim($arr_rs[$j]["SIDO"]);
								$PARTY					= trim($arr_rs[$j]["PARTY"]);
								$JOB						= trim($arr_rs[$j]["JOB"]);
								$COM_NAME				= trim($arr_rs[$j]["COM_NAME"]);
								$M_GROUP				= trim($arr_rs[$j]["M_GROUP"]);
								$PAY_TYPE				= trim($arr_rs[$j]["PAY_TYPE"]);
								$BANK_NO				= trim($arr_rs[$j]["BANK_NO"]);
								$BANK_CODE			= trim($arr_rs[$j]["BANK_CODE"]);
								$CMS_AMOUNT			= trim($arr_rs[$j]["CMS_AMOUNT"]);
								$AREA						= trim($arr_rs[$j]["AREA"]);

								$O_ZIP1					= trim($arr_rs[$j]["O_ZIP1"]);
								$O_ADDR1				= trim($arr_rs[$j]["O_ADDR1"]);
								$O_ADDR2				= trim($arr_rs[$j]["O_ADDR2"]);
								
								$dec_jumin			= decrypt($key, $iv, $M_JUMIN);
								$dec_tel				= decrypt($key, $iv, $M_TEL);
								$dec_mtel				= decrypt($key, $iv, $M_HP);
								$dec_bank_no		= decrypt($key, $iv, $BANK_NO);
								$dec_bank_code	= decrypt($key, $iv, $BANK_CODE);


					?>
	<tr>
		<td bgColor='#FFFFFF' align='left'><?=$M_NAME?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_SEX?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_AGE?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_BIRTH?></td>
		<td bgColor='#FFFFFF' align='left'><?=$dec_jumin?></td>
		<td bgColor='#FFFFFF' align='left'><?=$dec_mtel?></td>
		<td bgColor='#FFFFFF' align='left'><?=$dec_tel?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_ZIP1?></td>
		<td bgColor='#FFFFFF' align='left'><?=$SIDO?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_ADDR1?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_ADDR2?></td>
		<td bgColor='#FFFFFF' align='left'><?=$PARTY?></td>
		<td bgColor='#FFFFFF' align='left'><?=$JOB?></td>
		<td bgColor='#FFFFFF' align='left'><?=$O_ZIP1?></td>
		<td bgColor='#FFFFFF' align='left'><?=$O_ADDR1?></td>
		<td bgColor='#FFFFFF' align='left'><?=$O_ADDR2?></td>
		<td bgColor='#FFFFFF' align='left'><?=$COM_NAME?></td>
		<td bgColor='#FFFFFF' align='left'><?=$M_GROUP?></td>
		<td bgColor='#FFFFFF' align='left'><?=$PAY_TYPE?></td>
		<td bgColor='#FFFFFF' align='left'><?=$dec_bank_no?></td>
		<td bgColor='#FFFFFF' align='left'><?=$dec_bank_code?></td>
		<td bgColor='#FFFFFF' align='left'><?=$CMS_AMOUNT?></td>
		<td bgColor='#FFFFFF' align='left'><?=$AREA?></td>
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