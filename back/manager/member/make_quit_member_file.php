<?
header("Content-Type: text/html; charset=euc-kr"); 
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "/home/httpd/html/_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "/home/httpd/html/_common/config.php";
	require "/home/httpd/html/_classes/com/util/Util.php";
	require "/home/httpd/html/_classes/com/etc/etc.php";
	require "/home/httpd/html/_classes/com/util/AES2.php";

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
	$today		= date("Ymd",strtotime("0 month"));
	$chk_date	= date("Y-m-d",strtotime("0 month"));

	$is_holiday = isHoliday($conn, $chk_date);

	if ($is_holiday == "true") {
		exit;
		mysql_close($conn);
	}


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

	function getQuitMemberCmsList($db) {

		$query = "SELECT * FROM TBL_MEMBER WHERE CMS_FLAG = 'R' AND M_LEAVE_DATE <> '' AND QUIT_CMS_FLAG = 'D' ";
		$result = mysql_query($query,$db);
		$record = array();

		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	$arr_rs = getQuitMemberCmsList($conn);
	
	$str_body = "";
	$seq_no = 1;

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
			$M_CMS_FLAG				= trim($arr_rs[$j]["CMS_FLAG"]);
			
			$M_NAME						= iconv('UTF-8', 'EUC-KR', $M_NAME);
			
			$DEC_M_PAY_CODE		= decrypt($key, $iv, $M_PAY_CODE);
			$DEC_M_PAY_NO			= decrypt($key, $iv, $M_PAY_NO);
			$DEC_M_JUMIN			= decrypt($key, $iv, $M_JUMIN);
			$DEC_M_HP					= decrypt($key, $iv, $M_HP);

			$DEC_M_PAY_YY			= decrypt($key, $iv, $M_PAY_YY);
			$DEC_M_PAY_MM			= decrypt($key, $iv, $M_PAY_MM);
			
			$arr_jumin				= explode("-",$DEC_M_JUMIN);

			$DEC_M_JUMIN = $arr_jumin[0];

			$DEC_M_JUMIN			= str_replace("-","",$DEC_M_JUMIN);
			$DEC_M_PAY_CODE		= str_replace("-","",$DEC_M_PAY_CODE);
			$DEC_M_PAY_NO			= str_replace("-","",$DEC_M_PAY_NO);
			$DEC_M_HP					= str_replace("-","",$DEC_M_HP);

			if ($M_EMAIL == "@") $M_EMAIL = "";
			
			$str_id = right("00000000".$M_NO, 8);

			$info01 = "D";
			$info02 = $site_id;
			$info03 = makeSpace("", 8);
			$info04 = makeSpaceNum($seq_no, 6);
			// 해당 부분은 나중에 신규, 수정, 삭제로 구분 한다.
			$info05 = "D";
			$info06 = makeSpace($str_id, 20);
			$info07 = makeHanSpace($M_NAME, 20);

			$info08 = makeSpace("", 223);
			
			if ($M_PAY_TYPE == "mobile") { 
				$info21 = "H";
			}

			if ($M_PAY_TYPE == "cms") { 
				$info21 = "B";
			}

			if ($M_PAY_TYPE == "card") { 
				$info21 = "C";
			}

			$info09 = makeSpace("", 1);
			$info10 = makeSpace("", 4);
			$info11 = makeSpace("", 30);
			$info12 = makeSpace("DEL", 20);
			
			$str_body .= $info01.$info02.$info03.$info04.$info05.$info06.$info07.$info08.$info21.$info09.$info10.$info11.$info12."\r\n";

			$seq_no = $seq_no + 1;

		}
	}

	$header = "H".$site_id." ".makeSpaceNum(sizeof($arr_rs), 6).makeSpace("", 272).makeSpace("",55)."\r\n";
	$tail = "T".$site_id." ".makeSpaceNum(sizeof($arr_rs), 6).makeSpace("", 6).makeSpace("", 6).makeSpace("", 260).makeSpace("",55)."\r\n";

	$space = " ";
	$str = "";
	
	$str	= $header; 
	$str .= $str_body; 
	$str .= $tail;

	$dirname = "/home/aegisCMSClnt/data/member/send/";
	$filename = $today.".".$file_site_id;

	$fp = fopen("$dirname$filename","w");

	fwrite($fp,$str); 
	fclose($fp);

	mysql_close($conn);
?>