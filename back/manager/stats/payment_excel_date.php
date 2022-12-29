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

	$menu_right = " ST002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/member/member.php";
	require "../../_classes/biz/stats/stats.php";
#====================================================================
# Request Parameter
#====================================================================

	$str_title = iconv("UTF-8","EUC-KR","당비 현황");

	$file_name=$str_title."-".date("Ymd").".xls";
		header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
		header( "Content-Disposition: attachment; filename=$file_name" );
		header( "Content-Description: orion70kr@gmail.com" );

	if ($con_yyyy == "") {
		$con_yyyy = date("Y",strtotime("0 month"));
	}

	if ($con_mm == "") {
		$con_mm = date("m",strtotime("0 month"));
	}

	if ($chk_type == "") {
		$chk_type = "D";
	}

	// 로그인한 관리자 권한에 따라 보여주는 내용이 다릅니다. -- 여기 부터

	if (($_SESSION['s_adm_position_code'] == "") || ($_SESSION['s_adm_position_code'] == "중앙당")) {
		$sel_area_cd = trim($sel_area_cd);
	} else {
		$sel_area_cd = $_SESSION['s_adm_position_code'];
	}

	if (($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) {
		$sel_party = trim($sel_party);
	} else {
		$sel_party = $_SESSION['s_adm_dept_code'];
	}
	
	// --------------------------------------------------------- 여기 까지
	
	//$con_yyyy = "2016";
	//$con_mm = "05";

	$arr_rs = NewpaymentStatsAsDate($conn, $con_yyyy, $con_mm, $chk_type, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $sel_pay_reason, $group_cd);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "일자별 당비 현황  조회", "Stats");

	if($group_cd){
			if(strlen($group_cd) == 3){
				$group_cd_01=$group_cd;
			}
			if(strlen($group_cd) == 6){
				$group_cd_01=substr($group_cd,0,3);
				$group_cd_02=substr($group_cd,0,6);
			}
			if(strlen($group_cd) == 9){
				$group_cd_01=substr($group_cd,0,3);
				$group_cd_02=substr($group_cd,0,6);
				$group_cd_03=substr($group_cd,0,9);
			}

			if(strlen($group_cd) == 12){
				$group_cd_01=substr($group_cd,0,3);
				$group_cd_02=substr($group_cd,0,6);
				$group_cd_03=substr($group_cd,0,9);
				$group_cd_04=substr($group_cd,0,12);
			}

			if(strlen($group_cd) == 15){
				$group_cd_01=substr($group_cd,0,3);
				$group_cd_02=substr($group_cd,0,6);
				$group_cd_03=substr($group_cd,0,9);
				$group_cd_04=substr($group_cd,0,12);
				$group_cd_05=substr($group_cd,0,15);
			}
		}
?>
<font size=3><b>당원 현황 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<? if ($chk_type == "M") {?>
		<td align='center' bgcolor='#F4F1EF'>년월</td>
		<? } else { ?>
		<td align='center' bgcolor='#F4F1EF'>일자 (요일)</td>
		<? } ?>
		<td align='center' bgcolor='#F4F1EF'>납부 당비</td>
		<td align='center' bgcolor='#F4F1EF'>환불 당비</td>
	</tr>
	<?
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$CAL_DATE					= trim($arr_rs[$j]["CAL_DATE"]);
			$DW								= trim($arr_rs[$j]["DW"]);
			$IN_COUNT					= trim($arr_rs[$j]["IN_COUNT"]);
			$OUT_COUNT				= trim($arr_rs[$j]["OUT_COUNT"]);

			if ($DW == 1) {
				$S_DW = "(일)";
			} else if ($DW == 2) {
				$S_DW = "(월)";
			} else if ($DW == 3) {
				$S_DW = "(화)";
			} else if ($DW == 4) {
				$S_DW = "(수)";
			} else if ($DW == 5) {
				$S_DW = "(목)";
			} else if ($DW == 6) {
				$S_DW = "(금)";
			} else if ($DW == 7) {
				$S_DW = "(토)";
			}

	?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>

		<? if ($chk_type == "M") {?>
		<td><?=left($CAL_DATE,4)?>-<?=right($CAL_DATE,2)?></td>
		<? } else { ?>
		<td><?=left($CAL_DATE,4)?>-<?=substr($CAL_DATE,4,2)?>-<?=right($CAL_DATE,2)?> <?=$S_DW?></td>
		<? } ?>
		<td><?=number_format($IN_COUNT)?></td>
		<td><?=number_format($OUT_COUNT)?></td>
	</tr>
	<?
		}
	?>
</table>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>