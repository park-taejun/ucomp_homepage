<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
session_start();
?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");


#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다
	//$menu_cd="0501";

	$menu_right = "PM003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/payment/payment.php";
#====================================================================
# Request Parameter
#====================================================================

	$str_title = iconv("UTF-8","EUC-KR","특별당비리스트");

	$file_name=$str_title."-".date("Ymd").".xls";

	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	

#============================================================
# Page process
#============================================================

	$nPage = 1;
	$nPageSize = "1000000";

	$nPageBlock	= 10;

	$is_direct = "N";

#===============================================================
# Get Search list count
#===============================================================
	$nListCnt =totalCntSpeMember($conn, $p_no, $search_field, $search_str);
	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listSpeMember($conn, $p_no, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별당비 납부 대상 엑셀 출력", "Excel");
	
	$SUM_CMS_AMOUNT = listSumSpeMemberAmount($conn, $p_no, $search_field, $search_str);


?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<font size=3><b>특별당비 납부 대상 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<td align='center' bgcolor='#F4F1EF'>번호</td>
		<td align='center' bgcolor='#F4F1EF'>이름</td>
		<td align='center' bgcolor='#F4F1EF'>생년월일</td>
		<td align='center' bgcolor='#F4F1EF'>전화번호</td>
		<td align='center' bgcolor='#F4F1EF'>휴대전화</td>
		<td align='center' bgcolor='#F4F1EF'>우편번호</td>
		<td align='center' bgcolor='#F4F1EF'>주소</td>
		<td align='center' bgcolor='#F4F1EF'>상세주소</td>
		<td align='center' bgcolor='#F4F1EF'>소속지역</td>
		<td align='center' bgcolor='#F4F1EF'>소속당</td>
		<td align='center' bgcolor='#F4F1EF'>소속단체</td>
		<td align='center' bgcolor='#F4F1EF'>특별당비납부금액</td>
	</tr>
	<?
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

				$rn								= trim($arr_rs[$j]["rn"]);
				$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
				$P_SEQ_NO					= trim($arr_rs[$j]["P_SEQ_NO"]);
				$M_NAME						= trim($arr_rs[$j]["M_NAME"]);
				$M_BIRTH					= trim($arr_rs[$j]["M_BIRTH"]);
				$M_HP							= trim($arr_rs[$j]["M_HP"]);
				$AMOUNT						= trim($arr_rs[$j]["AMOUNT"]);

				$M_TEL							= trim($arr_rs[$j]["M_TEL"]);
				$M_ZIP1							= trim($arr_rs[$j]["M_ZIP1"]);
				$M_ADDR1						= trim($arr_rs[$j]["M_ADDR1"]);
				$M_ADDR2						= trim($arr_rs[$j]["M_ADDR2"]);
				$M_3								= trim($arr_rs[$j]["M_3"]);
				$M_4								= trim($arr_rs[$j]["M_4"]);
				$SIDO								= trim($arr_rs[$j]["SIDO"]);

				$str_m_tel = decrypt($key, $iv, $M_TEL);
				$str_m_hp = decrypt($key, $iv, $M_HP);

	?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>
		<td><?=$rn?></td>
		<td><?=$M_NAME?></td>
		<td><?=$M_BIRTH?></td>
		<td><?=$str_m_tel?></td>
		<td><?=$str_m_hp?></td>
		<td><?=$M_ZIP1?></td>
		<td><?=$M_ADDR1?></td>
		<td><?=$M_ADDR2?></td>
		<td><?=$SIDO?></td>
		<td><?=$M_3?></td>
		<td><?=$M_4?></td>
		<td><?=number_format($AMOUNT)?></td>
	</tr>
	<?
			}
		} else { 
	?>
	<tr>
		<td align="center" height="50" colspan="12">데이터가 없습니다. </td>
	</tr>
	<? 
		}
	?>
</table>


<br><br>
<table border="1">
	<tr>
		<th >특별당비 총액</th>
		<td  style="text-align:right"><b><?=number_format($SUM_CMS_AMOUNT)?></b> 원</td>
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
