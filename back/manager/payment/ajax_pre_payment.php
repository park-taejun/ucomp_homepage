<?session_start();?>
<?
header("Content-Type: text/html; charset=utf-8"); 
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
	require "../../_classes/biz/payment/payment.php";

	$req_date = trim($req_date);
	$pick_date = trim($pick_date);

	$req_file_cnt = chk_payment_file($conn);

	// 로그인한 관리자 권한에 따라 보여주는 내용이 다릅니다. -- 여기 부터

	if (($_SESSION['s_adm_position_code'] == "") || ($_SESSION['s_adm_position_code'] == "중앙당")) {
		$sel_area_cd = "";
	} else {
		$sel_area_cd = $_SESSION['s_adm_position_code'];
	}

	if (($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) {
		$sel_party = "";
	} else {
		$sel_party = $_SESSION['s_adm_dept_code'];
	}

	// --------------------------------------------------------- 여기 까지


	function getPayReqList($db, $pay_yyyy, $pay_mm, $sel_area_cd, $sel_party, $mem_req_day) {

		$temp_str1 = "잔액부족";
		$temp_str2 = "잔고부족";

		$query = "SELECT COUNT(M_NO) AS M_CNT, M_6, SUM(M_12) AS SUM_AMOUNT
								FROM TBL_MEMBER 
							 WHERE M_5 = 'Y' 
								AND M_JUMIN <> '' AND M_HP  <> ''  
								AND M_LEAVE_DATE = '' 
								AND M_SIGNATURE <> '' 
								AND CMS_FLAG = 'R'  
								AND M_6 IN ('cms','card')
								AND REQ_DAY = '$mem_req_day'
								AND SEND_FLAG = '0' ";

		if ($sel_area_cd <> "") {
			$query .= " AND SIDO like '%".$sel_area_cd."%' ";
		}

		if ($sel_party <> "") {
			$query .= " AND M_3 like '%".$sel_party."%' ";
		}

		$query .= "	AND M_NO NOT IN (SELECT M_NO FROM TBL_MEMBER_PAYMENT WHERE PAY_YYYY = '$pay_yyyy' AND PAY_MM = '$pay_mm' AND PAY_DAY = '$mem_req_day' AND PAY_RESULT_MSG <> '$temp_str1' AND PAY_RESULT_MSG <> '$temp_str2')
							GROUP BY M_6 ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		//echo $query."<br>";

		//exit;

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	// 출금일 
	// pay_type 출금 방식
	$mem_req_day	= trim($req_date);
	$pay_req_day	= trim($pick_date);
	$pay_req_type = trim($pay_req_type);
	
	if ($pay_req_day == "") {

		mysql_close($conn);
		exit;

		//$pay_req_day = "2016-04-25";
	}

#====================================================================
# Request Parameter
#====================================================================


	$arr_pay_req_day = explode("-", $pay_req_day);
	$str_yy		= $arr_pay_req_day[0];
	$str_mm		= $arr_pay_req_day[1];


	$arr_rs = getPayReqList($conn, $str_yy, $str_mm, $sel_area_cd, $sel_party, $mem_req_day);
	
	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$M_CNT					= trim($arr_rs[$j]["M_CNT"]);
			$M_6						= trim($arr_rs[$j]["M_6"]);
			$SUM_AMOUNT			= trim($arr_rs[$j]["SUM_AMOUNT"]);
			
			if ($M_6 == "card") {
				$card_m_cnt		= $M_CNT;
				$card_amount	= $SUM_AMOUNT;
			}


			if ($M_6 == "cms") {
				$cms_m_cnt		= $M_CNT;
				$cms_amount	= $SUM_AMOUNT;
			}

		}
	}

?>
<div class="sp10"></div>
<table class="bbsWrite">
	<caption>내용 입력란</caption>
	<colgroup>
		<col width="10%" />
		<col width="15%" />
		<col width="10%" />
		<col width="15%" />
		<col width="10%" />
		<col width="15%" />
		<col width="10%" />
		<col width="15%" />
	</colgroup>
	<tr>
		<th>CMS 납부 건</th>
		<td><b><?=number_format($cms_m_cnt)?></b> 건</td>
		<th>CMS 납부 예상금액</th>
		<td><b><?=number_format($cms_amount)?></b> 원</td>
		<th>신용카드 납부 건</th>
		<td><b><?=number_format($card_m_cnt)?></b> 건</td>
		<th>신용카드 납부 예상금액</th>
		<td><b><?=number_format($card_amount)?></b> 원</td>
			</tr>
</table>

<div class="btnArea">
	<ul class="fRight">
		<?
			if ($sPageRight_I == "Y") {
				if ($req_file_cnt == "0") {
		?>
		<input type="button" name="aa" id="btn_req_payment" value=" 출금 요청 " style="border:1px solid red;cursor:pointer;height:25px;" onclick="js_make_file();">
		<?
				} else { 
		?>
		<font color="red">출금 신청 중 입니다.</font>
		<?
				}
			}
		?>
	</ul>
</div>
<div class="sp10"></div>
<?
	mysql_close($conn);
?>