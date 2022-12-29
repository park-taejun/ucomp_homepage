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

	$menu_right = "PM001"; // 메뉴마다 셋팅 해 주어야 합니다

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

	$str_title = iconv("UTF-8","EUC-KR","CMS 출금리스트");

	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );

	$arr_year = getPaymentYear($conn);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
	
	if ($order_field == "")
		$order_field = "M_DATETIME";
	
	if ($order_str=="")
		$order_str = "DESC";

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
	$nListCnt =totalCntCmsPayment($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $pay_reason, $Ngroup_cd, $p_seq_no, $search_field, $search_str);
	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listCmsPayment($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $pay_reason, $Ngroup_cd, $p_seq_no, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "CMS 당비 납부 엑셀 출력", "Excel");
	
	$arr_sum_rs			= listSumCmsPayment($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $Ngroup_cd, $p_seq_no, $search_field, $search_str);
	$SUM_CMS_AMOUNT = listSumCmsAmount($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $Ngroup_cd, $p_seq_no, $search_field, $search_str);


	$holiday_str = getHolidayList($conn);

	$req_file_cnt = chk_payment_file($conn);

?>
<font size=3><b>CMS 출금 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<td align='center' bgcolor='#F4F1EF'>번호</td>
		<td align='center' bgcolor='#F4F1EF'>아이디</td>
		<td align='center' bgcolor='#F4F1EF'>이름</td>
		<td align='center' bgcolor='#F4F1EF'>생년월일</td>
		<td align='center' bgcolor='#F4F1EF'>광역시/도</td>
		<td align='center' bgcolor='#F4F1EF'>신청년월</td>
		<td align='center' bgcolor='#F4F1EF'>납부방법</td>
		<td align='center' bgcolor='#F4F1EF'>약정당비</td>
		<td align='center' bgcolor='#F4F1EF'>납부당비</td>
		<td align='center' bgcolor='#F4F1EF'>실제출금일</td>
		<td align='center' bgcolor='#F4F1EF'>출금수수료</td>
		<td align='center' bgcolor='#F4F1EF'>승인번호</td>
		<td align='center' bgcolor='#F4F1EF'>출금상태</td>
		<td align='center' bgcolor='#F4F1EF'>처리결과</td>
		<td align='center' bgcolor='#F4F1EF'>출금신청일</td>
	</tr>
	<?
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				
				//A.MEM_NO, A.MEM_TYPE AS M_MEM_TYPE, A.MEM_NM, A.MEM_NICK, 
				//A.MEM_ID, A.BIRTH_DATE, A.HPHONE, A.USE_TF, B.SEQ_NO, B.COMM_NO, B.MEM_TYPE

				$rn								= trim($arr_rs[$j]["rn"]);
				$M_NO							= trim($arr_rs[$j]["M_NO"]);
				$M_ID							= trim($arr_rs[$j]["M_ID"]);
				$M_NAME						= trim($arr_rs[$j]["M_NAME"]);
				$SIDO							= trim($arr_rs[$j]["SIDO"]);
				$PAY_YYYY					= trim($arr_rs[$j]["PAY_YYYY"]);
				$PAY_MM						= trim($arr_rs[$j]["PAY_MM"]);
				$PAY_TYPE					= trim($arr_rs[$j]["PAY_TYPE"]);
				$CMS_AMOUNT				= trim($arr_rs[$j]["CMS_AMOUNT"]);
				$RES_CMS_AMOUNT		= trim($arr_rs[$j]["RES_CMS_AMOUNT"]);
				$RES_PAY_DATE			= trim($arr_rs[$j]["RES_PAY_DATE"]);
				$RES_PAY_DATE			= trim($arr_rs[$j]["RES_PAY_DATE"]);
				$CMS_CHARGE 			= trim($arr_rs[$j]["CMS_CHARGE"]);
				$RES_PAY_NO				= trim($arr_rs[$j]["RES_PAY_NO"]);
				$PAY_RESULT				= trim($arr_rs[$j]["PAY_RESULT"]);
				$PAY_RESULT_CODE	= trim($arr_rs[$j]["PAY_RESULT_CODE"]);
				$PAY_RESULT_MSG		= trim($arr_rs[$j]["PAY_RESULT_MSG"]);
				$SEND_FLAG				= trim($arr_rs[$j]["SEND_FLAG"]);
				$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
				$M_BIRTH					= trim($arr_rs[$j]["M_BIRTH"]);

				if ($PAY_TYPE == "B") $str_pay_type = "CMS";
				if ($PAY_TYPE == "C") $str_pay_type = "신용카드";
				if ($PAY_TYPE == "H") $str_pay_type = "휴대폰";
				if ($PAY_TYPE == "D") $str_pay_type = "직접납부";

				if ($PAY_RESULT == "Y") {
					$str_color = "navy";
				} else {
					$str_color = "red";
				}

	?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>
		<td><?=$rn?></td>
		<td><?=$M_ID?></td>
		<td><?=$M_NAME?></td>
		<td><?=$M_BIRTH?></td>
		<td><?=$SIDO?></td>
		<td><?=$PAY_YYYY?><?=$PAY_MM?></td>
		<td><?=$str_pay_type?></td>
		<td style="text-align:right;padding-right:20px"><?=number_format($CMS_AMOUNT)?></td>
		<td style="text-align:right;padding-right:20px"><?=number_format($RES_CMS_AMOUNT)?></td>
		<td><?=$RES_PAY_DATE?></td>
		<td style="text-align:right;padding-right:20px"><?=number_format($CMS_CHARGE)?></td>
		<td><?=$RES_PAY_NO?></td>
		<td><font color="<?=$str_color?>"><?=$PAY_RESULT?></font></td>
		<td><font color="<?=$str_color?>"><?=$PAY_RESULT_MSG?></font></td>
		<td><?=substr($REG_DATE ,0,10)?></td>
	</tr>
	<?
			}
		} else { 
	?>
	<tr>
		<td align="center" height="50" colspan="15">데이터가 없습니다. </td>
	</tr>
	<? 
		}
	?>
</table>

	<?
		if (sizeof($arr_sum_rs) > 0) {

			$SUM_RES_CMS_AMOUNT					= trim($arr_sum_rs[0]["SUM_RES_CMS_AMOUNT"]);
			$SUM_REF_CMS_AMOUNT					= trim($arr_sum_rs[0]["SUM_REF_CMS_AMOUNT"]);
			$SUM_CMS_CHARGE							= trim($arr_sum_rs[0]["SUM_CMS_CHARGE"]);
	?>
<br><br>
<table border="1">
	<tr>
		<th colspan="2">약정 총액</th>
		<td colspan="2" style="text-align:right"><b><?=number_format($SUM_CMS_AMOUNT)?></b> 원</td>
		<th colspan="2">납부 총액</th>
		<td colspan="2" style="text-align:right"><b><?=number_format($SUM_RES_CMS_AMOUNT)?></b> 원</td>
		<th colspan="2">환불 총액</th>
		<td colspan="2" style="text-align:right"><b><?=number_format($SUM_REF_CMS_AMOUNT)?></b> 원</td>
		<th colspan="1">수수료 총액</th>
		<td colspan="2" style="text-align:right"><b><?=number_format($SUM_CMS_CHARGE)?></b> 원</td>
	</tr>
</table>
	<?
		}
	?>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
