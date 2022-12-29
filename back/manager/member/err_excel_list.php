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

	$menu_right = "MB005"; // 메뉴마다 셋팅 해 주어야 합니다

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
#====================================================================
# Request Parameter
#====================================================================

	$str_title = iconv("UTF-8","EUC-KR","CMS_ERROR");

	$file_name=$str_title."_".date("Ymd").".xls";
		header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
		header( "Content-Disposition: attachment; filename=$file_name" );
		header( "Content-Description: orion70kr@gmail.com" );

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

#===============================================================
# Get Search list count
#===============================================================
	$nListCnt =totalCntCmsErrMember($conn, $sel_pay_type, $sel_area_cd, $sel_party, $is_agree, $Ngroup_cd, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listCmsErrMember($conn, $sel_pay_type, $sel_area_cd, $sel_party, $is_agree, $Ngroup_cd, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "CMS 등록오류 엑셀 출력", "Excel");

?>
<font size=3><b>당원 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<td align='center' bgcolor='#F4F1EF'>번호</td>
		<td align='center' bgcolor='#F4F1EF'>아이디</td>
		<td align='center' bgcolor='#F4F1EF'>이름</td>
		<td align='center' bgcolor='#F4F1EF'>광역시/도</td>
		<td align='center' bgcolor='#F4F1EF'>성별</td>
		<td align='center' bgcolor='#F4F1EF'>생년월일</td>
		<td align='center' bgcolor='#F4F1EF'>연락처</td>
		<td align='center' bgcolor='#F4F1EF'>소속당</td>
		<td align='center' bgcolor='#F4F1EF'>납부방법</td>
		<td align='center' bgcolor='#F4F1EF'>동의서</td>
		<td align='center' bgcolor='#F4F1EF'>가입일</td>
		<td align='center' bgcolor='#F4F1EF'>CMS등록구분</td>
		<td align='center' bgcolor='#F4F1EF'>오류정보</td>
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
				$M_SEX						= trim($arr_rs[$j]["M_SEX"]);
				$M_HP							= trim($arr_rs[$j]["M_HP"]);
				$M_3							= trim($arr_rs[$j]["M_3"]);
				$M_5							= trim($arr_rs[$j]["M_5"]);
				$M_6							= trim($arr_rs[$j]["M_6"]);
				$M_DATETIME 			= trim($arr_rs[$j]["M_DATETIME"]);
				$M_BIRTH					= trim($arr_rs[$j]["M_BIRTH"]);
				$M_LEVEL					= trim($arr_rs[$j]["M_LEVEL"]);
				$M_SIGNATURE			= trim($arr_rs[$j]["M_SIGNATURE"]);
				$CMS_FLAG					= trim($arr_rs[$j]["CMS_FLAG"]);
				$SEND_FLAG				= trim($arr_rs[$j]["SEND_FLAG"]);
				$CMS_RESULT_MSG		= trim($arr_rs[$j]["CMS_RESULT_MSG"]);

				$str_m_tel = decrypt($key, $iv, $M_HP);

	?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>
		<td><?=$rn?></td>
		<td><?=$M_ID?></td>
		<td><?=$M_NAME?></td>
		<td><?=$SIDO?></td>
		<td>
		<?
			if($M_SEX=="1"){
				echo "남자";
			}else{
				echo "여자";
			}
		?>
		</td>
		<td><?=$M_BIRTH?></td>
		<td><?=$str_m_tel?></td>
		<td><?=$M_3?></td>
		<td>
			<? if ($M_6 == "mobile") { echo "휴대전화"; } else if ($M_6 == "cms") { echo "CMS"; } else if ($M_6 == "card") { echo "신용카드"; } else { echo "&nbsp;";} ?>
		</td>
		<td>
			<b>
			<? if ($M_SIGNATURE == "") {?>
			<font color="red">X</font>
			<? } else { ?>
			<font color="navy">O</font>
			<? } ?>
			</b>
		</td>
		<td><?=substr($M_DATETIME ,0,10)?></td>
		<td>
			<? if ($CMS_FLAG == "N") { ?>신규<? } ?>
			<? if ($CMS_FLAG == "U") { ?>수정<? } ?>
			<? if ($CMS_FLAG == "D") { ?>삭제<? } ?>
			<? if ($CMS_FLAG == "R") { ?>등록<? } ?>
			<? if ($CMS_FLAG == "F") { ?><font color="red">오류</font><? } ?>
		</td>
		<td style="text-align:left">
			<?=$CMS_RESULT_MSG?>
		</td>
	</tr>
	<? 
			}
		}
	?>

</table>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>