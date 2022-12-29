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

	function getNewMemberCmsList($db) {

		//$query = "SELECT * FROM TBL_MEMBER WHERE M_5 = 'Y' AND M_JUMIN <> '' AND M_HP  <> '' AND M_SIGNATURE <> '' AND M_LEAVE_DATE != '' AND M_6 IN ('cms', 'card') ";
		$query = "SELECT * FROM TBL_MEMBER WHERE M_5 = 'Y' AND M_JUMIN <> '' AND M_HP  <> '' AND M_SIGNATURE <> '' AND M_LEAVE_DATE = '' AND CMS_FLAG IN ('N','U')  AND M_6 IN ('cms', 'card') AND SEND_FLAG = '0' ";
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

	function updateMemberSendFlag($db, $m_no) {

		$query = "UPDATE TBL_MEMBER SET SEND_FLAG = '1' 
							 WHERE M_NO = '$m_no'  ";
		
		#echo $query;
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertReqMemberFile($db, $send_type, $file_name) {

		$query = "INSERT INTO TBL_CMS_FILE (SEND_TYPE, SEND_FILE_NAME) VALUES ('$send_type','$file_name')";
		
		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	$arr_rs = getNewMemberCmsList($conn);
	
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

			if ($M_PAY_TYPE == "cms") { 
				
				$M_DEPO_NAME			= trim($arr_rs[$j]["M_9"]);
				$DEC_M_DEPO_NAME	= decrypt($key, $iv, $M_DEPO_NAME);
				
				if ($DEC_M_DEPO_NAME == "") $DEC_M_DEPO_NAME = $M_NAME;

				$DEC_M_DEPO_NAME	= iconv('UTF-8', 'EUC-KR', $DEC_M_DEPO_NAME);

				$M_CMS_BIRTH			= trim($arr_rs[$j]["M_CMS_BIRTH"]);
				$M_CMS_BIRTH			= right($M_CMS_BIRTH,6);

			} else if ($M_PAY_TYPE == "card") {
				
				$M_DEPO_NAME			= trim($arr_rs[$j]["M_11"]);
				$DEC_M_DEPO_NAME	= decrypt($key, $iv, $M_DEPO_NAME);

				if ($DEC_M_DEPO_NAME == "") $DEC_M_DEPO_NAME = $M_NAME;

				$DEC_M_DEPO_NAME	= iconv('UTF-8', 'EUC-KR', $DEC_M_DEPO_NAME);

				$M_CMS_BIRTH			= trim($arr_rs[$j]["M_CMS_BIRTH"]);
				$M_CMS_BIRTH			= right($M_CMS_BIRTH,6);

			}

			$M_PAY_YY					= trim($arr_rs[$j]["M_9"]);
			$M_PAY_MM					= trim($arr_rs[$j]["M_10"]);
			$M_CMS_FLAG				= trim($arr_rs[$j]["CMS_FLAG"]);
			
			$DEC_M_PAY_CODE		= decrypt($key, $iv, $M_PAY_CODE);
			$DEC_M_PAY_NO			= decrypt($key, $iv, $M_PAY_NO);
			$DEC_M_JUMIN			= decrypt($key, $iv, $M_JUMIN);
			$DEC_M_HP					= decrypt($key, $iv, $M_HP);

			$DEC_M_PAY_YY			= decrypt($key, $iv, $M_PAY_YY);
			$DEC_M_PAY_MM			= decrypt($key, $iv, $M_PAY_MM);
			
			$arr_jumin				= explode("-",$DEC_M_JUMIN);

			$DEC_M_JUMIN = $arr_jumin[0];

			$DEC_M_JUMIN			= str_replace("-","",$DEC_M_JUMIN);

			if ($M_CMS_BIRTH == "") $M_CMS_BIRTH = $DEC_M_JUMIN;

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
			$info05 = makeSpace($M_CMS_FLAG,1);
			$info06 = makeSpace($str_id, 20);
			$info07 = makeHanSpace($DEC_M_DEPO_NAME, 20);

			if ($M_PAY_TYPE == "cms") { 
				$info08 = makeSpace($DEC_M_PAY_CODE, 3);
				
				if ($is_test == "Y") {
					$info09 = makeSpace("111111111111".$seq_no, 16);
				} else {
					$info09 = makeSpace($DEC_M_PAY_NO, 16);
				}

				//$info09 = makeSpace($DEC_M_PAY_NO, 16);
				//임의의 계좌번호
				//$info09 = makeSpace("111111111111".$seq_no, 16);
				
				if ($DEC_M_PAY_YY <> "") {
					$DEC_M_PAY_YY = iconv('UTF-8', 'EUC-KR', $DEC_M_PAY_YY);
					$info10 = makeHanSpace($DEC_M_PAY_YY, 20);
				} else { 
					$info10 = makeHanSpace($M_NAME, 20);
				}
			} else {
				$info08 = makeSpace("", 3);
				$info09 = makeSpace("", 16);
				$info10 = makeSpace("", 20);
			}

			if (($M_PAY_TYPE == "cms") || ($M_PAY_TYPE == "mobile")) { 
				$info11 = makeSpace($M_CMS_BIRTH, 13);
			} else {
				$info11 = makeSpace("", 13);
			}
			
			$info12 = makeSpace("", 6);
			$info13 = makeSpaceNum("", 12);

			$info14 = makeSpace($DEC_M_HP, 16);
			$info15 = makeSpace("", 30);
			$info16 = makeHanSpace(iconv('UTF-8', 'EUC-KR', "당비납부"), 30);
			$info17 = makeSpace("", 3);

			if ($M_PAY_TYPE == "card") { 
				$info18 = makeSpace($DEC_M_PAY_NO, 16);
				$info19 = makeSpace($DEC_M_PAY_YY.$DEC_M_PAY_MM, 4);
			} else {
				$info18 = makeSpace("", 16);
				$info19 = makeSpace("", 4);
			}

			if ($M_PAY_TYPE == "mobile") { 
				$info20 = makeSpace($DEC_M_PAY_NO, 1);
				$info21 = makeSpace($DEC_M_PAY_CODE, 16);
			} else {
				$info20 = makeSpace("", 1);
				$info21 = makeSpace("", 16);
			}
			
			$info22 = makeSpace("", 20);
			$info23 = makeSpace("", 17);

			if ($M_PAY_TYPE == "mobile") { 
				$info24 = "H";
			}

			if ($M_PAY_TYPE == "cms") { 
				$info24 = "B";
			}

			if ($M_PAY_TYPE == "card") { 
				$info24 = "C";
			}

			$info25 = makeSpace("", 1);
			$info26 = makeSpace("", 4);
			$info27 = makeSpace("", 30);
			$info28 = makeSpace("", 20);
			
			$str_body .= $info01.$info02.$info03.$info04.$info05.$info06.$info07.$info08.$info09.$info10.$info11.$info12.$info13.$info14.$info15.$info16.$info17.$info18.$info19.$info20.$info21.$info22.$info23.$info24.$info25.$info26.$info27.$info28."\r\n";

			$seq_no = $seq_no + 1;

			if ($is_test == "N") {
				$result_send = updateMemberSendFlag($conn, $M_NO);
			}

		}
	}
	
	$arr_rs_quit = getQuitMemberCmsList($conn);

	if (sizeof($arr_rs_quit) > 0) {

		for ($j = 0 ; $j < sizeof($arr_rs_quit); $j++) {
			
			$M_NO							= trim($arr_rs_quit[$j]["M_NO"]);
			$M_NAME						= trim($arr_rs_quit[$j]["M_NAME"]);
			$M_PAY_TYPE 			= trim($arr_rs_quit[$j]["M_6"]);

			if ($M_PAY_TYPE == "cms") { 
				
				$M_DEPO_NAME			= trim($arr_rs[$j]["M_9"]);
				$DEC_M_DEPO_NAME	= decrypt($key, $iv, $M_DEPO_NAME);
				
				if ($DEC_M_DEPO_NAME == "") $DEC_M_DEPO_NAME = $M_NAME;

				$DEC_M_DEPO_NAME	= iconv('UTF-8', 'EUC-KR', $DEC_M_DEPO_NAME);

			} else if ($M_PAY_TYPE == "card") {
				
				$M_DEPO_NAME			= trim($arr_rs[$j]["M_11"]);
				$DEC_M_DEPO_NAME	= decrypt($key, $iv, $M_DEPO_NAME);

				if ($DEC_M_DEPO_NAME == "") $DEC_M_DEPO_NAME = $M_NAME;

				$DEC_M_DEPO_NAME	= iconv('UTF-8', 'EUC-KR', $DEC_M_DEPO_NAME);

			}

			$M_PAY_CODE				= trim($arr_rs_quit[$j]["M_7"]);
			$M_PAY_NO					= trim($arr_rs_quit[$j]["M_8"]);
			$M_JUMIN 					= trim($arr_rs_quit[$j]["M_JUMIN"]);
			$M_HP							= trim($arr_rs_quit[$j]["M_HP"]);
			$M_EMAIL					= trim($arr_rs_quit[$j]["M_EMAIL"]);
			$M_PAY_YY					= trim($arr_rs_quit[$j]["M_9"]);
			$M_PAY_MM					= trim($arr_rs_quit[$j]["M_10"]);
			$M_CMS_FLAG				= trim($arr_rs_quit[$j]["CMS_FLAG"]);
			
			//$M_NAME						= iconv('UTF-8', 'EUC-KR', $M_NAME);
			
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
			$info07 = makeHanSpace($DEC_M_DEPO_NAME, 20);

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
	
	$all_req_cnt = sizeof($arr_rs) + sizeof($arr_rs_quit);

	$header = "H".$site_id." ".makeSpaceNum($all_req_cnt, 6).makeSpace("", 272).makeSpace("",55)."\r\n";
	$tail = "T".$site_id." ".makeSpaceNum($all_req_cnt, 6).makeSpace("", 6).makeSpace("", 6).makeSpace("", 260).makeSpace("",55)."\r\n";

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

	$result = insertReqMemberFile($conn, "M", $filename);

	//echo $result;

	if ($is_test == "N") {
		//system("/home/aegisCMSClnt/sh/memreq.sh");
	}

	mysql_close($conn);
?>