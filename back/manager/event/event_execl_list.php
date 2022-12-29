<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
# =============================================================================
# File Name    : admingroup_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "EV001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/event/event.php";

	$str_title = iconv("UTF-8","EUC-KR","당첨자리스트");

	$file_name=$str_title."-".date("Ymd").".xls";
		header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
		header( "Content-Disposition: attachment; filename=$file_name" );
		header( "Content-Description: orion70kr@gmail.com" );

#====================================================================
# Request Parameter
#====================================================================

	$result = false;

	$app_date			= trim($app_date);

	$del_tf = "N";
#============================================================
# Page process
#============================================================

	$nPage = 1;
	$app_flag = "1";

	$nPageSize = 10000;
	$nListCnt  = 10000;

#===============================================================
# Get Search list count
#===============================================================

	$arr_rs = listEventApply($conn, $sel_date2, $app_flag, "", "", $nPage, $nPageSize, $nListCnt);

?>
<font size=3><b>당첨자 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<td align='center' bgcolor='#F4F1EF'>추첨일자</td>
		<td align='center' bgcolor='#F4F1EF'>응모번호</td>
		<td align='center' bgcolor='#F4F1EF'>이름</td>
		<td align='center' bgcolor='#F4F1EF'>내용</td>
		<td align='center' bgcolor='#F4F1EF'>상태</td>
	</tr>
	<?
		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				$APP_NO			= trim($arr_rs[$j]["APP_NO"]);
				$APP_DATE		= trim($arr_rs[$j]["APP_DATE"]);
				$APP_NM			= trim($arr_rs[$j]["APP_NM"]);
				$CONTENTS		= trim($arr_rs[$j]["CONTENTS"]);
				$APP_FLAG		= trim($arr_rs[$j]["APP_FLAG"]);

				$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
	
	?>
	<tr style='border-collapse:collapse;table-layout:fixed;height:100pt'>
		<td ><?=$APP_DATE?></td>
		<td><?=$APP_NO?></td>
		<td><?=$APP_NM?></td>
		<td style="text-align:left;padding-left:10px;padding-right:10px"><?=$CONTENTS?></td>
		<td ><? if ($APP_FLAG == 1) echo "당첨"?></td>
	</tr>
	<?
			}
		} else { 
	?> 
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