<?
	ini_set('memory_limit',-1);
	ini_set('max_execution_time', 120);
	session_start();
?>
<?
header("Content-Type: text/html; charset=euc-kr"); 
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

	$menu_right = "MB002"; // 메뉴마다 셋팅 해 주어야 합니다

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

	function makeSpace($str, $length) {
	
		if (strlen($str) > $length) {
			$str = left($str, $length);
		}

		$space = " ";
		$ret = "";
		$temp = "";

		for ($j = 0; $j < ($length - strlen($str)) ; $j++) {
			$temp = $temp.$space;
		}
		
		$ret = $str.$temp;
		return $ret;
	}

	function makeHanSpace($str, $length) {

		if (strlen($str) > $length) {
			$str = left($str, $length);
		}

		$space = " ";
		$ret = "";
		$temp = "";

		for ($j = 0; $j < ($length - strlen($str)) ; $j++) {
			$temp = $temp.$space;
		}
		
		$ret = $str.$temp;
		return $ret;
	}

	function makeSpaceNum($str, $length) {

		if (strlen($str) > $length) {
			$str = left($str, $length);
		}

		for ($j = 0; $j < $length ; $j++) {
			$temp = $temp."0";
		}

		$ret = right($temp.$str, $length);

		return $ret;
	}

	function getPayReqList($db, $pay_yyyy, $pay_mm, $mem_req_day) {

		$temp_str1 = iconv('EUC-KR', 'UTF-8', "잔액부족");
		$temp_str2 = iconv('EUC-KR', 'UTF-8', "잔고부족");

		$query = "SELECT * 
								FROM TBL_MEMBER 
							 WHERE M_5 = 'Y' 
								 AND M_JUMIN <> '' 
								 AND M_HP  <> ''  
								 AND M_LEAVE_DATE = ''
								 AND M_SIGNATURE <> '' 
								 AND CMS_FLAG = 'R'  
								 AND M_6 IN ('cms','card') 
								 AND SEND_FLAG = '0' 
								 AND REQ_DAY = '25'
								 AND M_NO NOT IN (SELECT M_NO 
																		FROM TBL_MEMBER_PAYMENT 
																	 WHERE PAY_YYYY = '2016' 
																		 AND PAY_MM = '10' 
																		 AND PAY_DAY = '25' 
																		 AND PAY_RESULT_MSG <> '$temp_str1' 
																		 AND PAY_RESULT_MSG <> '$temp_str2') ";

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

	function insertPaymentInfo($db, $m_no, $pay_yyyy, $pay_mm, $pay_day, $cms_amount, $pay_type, $send_date, $send_file_name) {

		$query = "INSERT INTO TBL_MEMBER_PAYMENT (M_NO, PAY_YYYY, PAY_MM, PAY_DAY, CMS_AMOUNT, PAY_TYPE, SEND_DATE, SEND_FILE_NAME, REG_DATE) 
										VALUES ('$m_no', '$pay_yyyy', '$pay_mm', '$pay_day', '$cms_amount', '$pay_type', '$send_date', '$send_file_name', now()) ";
		
		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			$new_pay_no = mysql_insert_id();
			return $new_pay_no;
		}
	}

	function insertReqMemberFile($db, $send_type, $file_name) {

		$query = "INSERT INTO TBL_CMS_FILE (SEND_TYPE, SEND_FILE_NAME) VALUES ('$send_type','$file_name') ";
		
		#echo $query;
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	// 출금일 
	// pay_type 출금 방식
	$mem_req_day	= trim($req_date);
	$pay_req_day	= trim($pick_date);
	$pay_req_type = trim($pay_req_type);
	
	$mem_req_day	= "25";
	$pay_req_day = "20161101";

	if ($pay_req_day == "") {
		mysql_close($conn);
		exit;
	}

	if ($pay_req_type == "") {
		$pay_req_type = "CMS";
	}

	$is_test = "N";

#====================================================================
# Request Parameter
#====================================================================
	
	if ($is_test == "Y") {
		$site_id	= "tbtest";
		$file_site_id	= "tbtest";
	} else {
		$site_id	= "30040111";
		$file_site_id	= "30040111";
	}

	$site_id  = makeSpace($site_id, 8);



	$today		= str_replace("-", "", $pay_req_day);

	$arr_pay_req_day = explode("-", $pay_req_day);

	$str_yy				= "2016";
	$str_mm				= "10";

	$dirname = "/home/aegisCMSClnt/data/payment/send/";
	$filename = $today.".".$file_site_id;

	$arr_rs = getPayReqList($conn, $str_yy, $str_mm, $mem_req_day);
	
	$str_body = "";
	$seq_no = 1;
	$int_total_amount = 0;

	//echo "총건수 : ".sizeof($arr_rs)."<br>";

	if (sizeof($arr_rs) > 0) {

		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$M_NO							= trim($arr_rs[$j]["M_NO"]);
			$M_NAME						= trim($arr_rs[$j]["M_NAME"]);
			$M_PAY_TYPE 			= trim($arr_rs[$j]["M_6"]);
			$M_PAY_CODE				= trim($arr_rs[$j]["M_7"]);
			$M_PAY_NO					= trim($arr_rs[$j]["M_8"]);
			$M_JUMIN 					= trim($arr_rs[$j]["M_JUMIN"]);
			$M_HP							= trim($arr_rs[$j]["M_HP"]);
			$M_EMAIL					= trim($arr_rs[$j]["M_EMAIL"]);
			$M_PAY_YY					= trim($arr_rs[$j]["M_9"]);
			$M_PAY_MM					= trim($arr_rs[$j]["M_10"]);
			$M_CMS_AMOUNT			= trim($arr_rs[$j]["M_12"]);
			
			$M_NAME						= iconv('UTF-8', 'EUC-KR', $M_NAME);
			
			$M_CMS_AMOUNT			= str_replace(" ","",$M_CMS_AMOUNT);
			$M_CMS_AMOUNT			= str_replace(",","",$M_CMS_AMOUNT);
			// 총 액수
			$int_total_amount = $int_total_amount + intval($M_CMS_AMOUNT);

			$DEC_M_PAY_CODE		= decrypt($key, $iv, $M_PAY_CODE);
			$DEC_M_PAY_NO			= decrypt($key, $iv, $M_PAY_NO);
			$DEC_M_JUMIN			= decrypt($key, $iv, $M_JUMIN);
			$DEC_M_HP					= decrypt($key, $iv, $M_HP);

			$DEC_M_PAY_YY			= decrypt($key, $iv, $M_PAY_YY);
			$DEC_M_PAY_MM			= decrypt($key, $iv, $M_PAY_MM);
			
			$arr_jumin				= explode("-",$DEC_M_JUMIN);

			$DEC_M_JUMIN = $arr_jumin[0];

			$DEC_M_JUMIN			= str_replace("-","",$DEC_M_JUMIN);
			$DEC_M_HP					= str_replace("-","",$DEC_M_HP);

			if ($M_EMAIL == "@") $M_EMAIL = "";
			
			$str_id = right("00000000".$M_NO, 8);

			$info01 = "D";
			$info02 = $site_id;
			$info03 = makeSpace("", 8);
			$info04 = makeSpaceNum($seq_no, 6);
			// 해당 부분은 나중에 신규, 취소로 구분 한다.
			$info05 = "N";
			$info06 = makeSpace($str_id, 20);
			$info07 = makeHanSpace($M_NAME, 20);
			// 통장기재내역(월,회차표시)
			$info08 = makeSpace($str_mm, 2);
			$info09 = makeSpaceNum($M_CMS_AMOUNT, 10);

			$info10 = makeSpace("00000000", 8);
			$info11 = makeSpace("000000000", 9);
			$info12 = makeSpace("00000000", 8);
			$info13 = makeSpace("000000000", 9);
			$info14 = makeSpace("00000000", 8);
			$info15 = makeSpace("000000000", 9);
			$info16 = makeSpace("00000000", 8);
			$info17 = makeSpace("000000000", 9);
			$info18 = makeSpace("00000000", 8);
			$info19 = makeSpace("000000000", 9);
			$info20 = makeSpace("00000000", 8);
			$info21 = makeSpace("000000000", 9);
			$info22 = makeSpace("00000000", 8);
			$info23 = makeSpace("000000000", 9);
			$info24 = makeSpace("00000000", 8);
			$info25 = makeSpace("000000000", 9);
			$info26 = makeSpace("00000000", 8);
			$info27 = makeSpace("000000000", 9);
			$info28 = makeSpace("00000000", 8);
			$info29 = makeSpace("000000000", 9);

			$info30 = makeSpace("", 8);
			$info31 = makeSpaceNum("", 10);
			$info32 = makeSpaceNum("", 6);
			$info33 = makeSpace("", 10);
			$info34 = makeSpace("", 1);
			$info35 = makeSpace("", 6);

			if ($M_PAY_TYPE == "mobile") { 
				$info36 = "H";
			}

			if ($M_PAY_TYPE == "cms") { 
				$info36 = "B";
			}

			if ($M_PAY_TYPE == "card") { 
				$info36 = "C";
			}

			$info37 = makeSpace("", 1);
			$info38 = makeSpace("", 4);
			$info39 = makeSpace("", 30);

			$new_pay_no = insertPaymentInfo($conn, $M_NO, $str_yy, $str_mm, $mem_req_day, $M_CMS_AMOUNT, $info36, $pay_req_day, $filename);

			$info40 = makeSpace($new_pay_no, 20);

			$str_body .= $info01.$info02.$info03.$info04.$info05.$info06.$info07.$info08.$info09.$info10.$info11.$info12.$info13.$info14.$info15.$info16.$info17.$info18.$info19.$info20.$info21.$info22.$info23.$info24.$info25.$info26.$info27.$info28.$info29.$info30.$info31.$info32.$info33.$info34.$info35.$info36.$info37.$info38.$info39.$info40."\r\n";

			$seq_no = $seq_no + 1;


		}
	}


	$header = "H".$site_id." ".makeSpaceNum(sizeof($arr_rs), 6).makeSpaceNum($int_total_amount,15).makeSpace("", 257).makeSpace("",55)."\r\n";
	$tail = "T".$site_id." ".makeSpaceNum(sizeof($arr_rs), 6).makeSpaceNum($int_total_amount,15).makeSpaceNum("", 6).makeSpaceNum("", 15).makeSpace("", 236).makeSpace("",55)."\r\n";

	$space = " ";
	$str = "";
	
	$str	= $header; 
	$str .= $str_body; 
	$str .= $tail;

	$fp = fopen("$dirname$filename","w");

	fwrite($fp,$str); 
	fclose($fp);

	$result = insertReqMemberFile($conn, "P", $filename);
		
	echo "T";
	//if ($is_test == "N") {
	//exec("/home/aegisCMSClnt/sh/payreq.sh", $output);
	//echo  $output[0]."<br>";
	//echo  $output[1]."<br>";
	//echo  $output[2]."<br>";
	//echo  $output[3]."<br>";
	//}

	mysql_close($conn);
?>