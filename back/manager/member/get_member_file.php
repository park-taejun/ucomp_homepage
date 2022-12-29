<?
header("Content-Type: text/html; charset=euc-kr"); 
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "/home/httpd/html/_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	require "/home/httpd/html/_common/config.php";
	require "/home/httpd/html/_classes/com/util/Util.php";
	require "/home/httpd/html/_classes/com/etc/etc.php";
	require "/home/httpd/html/_classes/com/util/AES2.php";

	function getResMemberFile($db) {
		
		$query = "SELECT * FROM TBL_CMS_FILE WHERE RES_FLAG = '0' AND SEND_TYPE = 'M' ORDER BY SEND_FILE_NAME ASC";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function updateMemberReqFlag($db, $m_no, $result, $result_code, $result_mag) {

		if ($result == "Y") {
			$query = "UPDATE TBL_MEMBER SET CMS_FLAG = 'R', SEND_FLAG = '0', CMS_RESULT = '$result', CMS_RESULT_CODE = '$result_code', CMS_RESULT_MSG = '$result_mag'
								 WHERE M_NO = '$m_no'  ";
		} else {
			$query = "UPDATE TBL_MEMBER SET CMS_FLAG = 'F', SEND_FLAG = '0', CMS_RESULT = '$result', CMS_RESULT_CODE = '$result_code', CMS_RESULT_MSG = '$result_mag'
								 WHERE M_NO = '$m_no'  ";
		}

		#echo $query;
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateMemberDelFlag($db, $m_no, $result, $result_code, $result_mag) {

		if ($result == "Y") {
			$query = "UPDATE TBL_MEMBER SET CMS_FLAG = 'Q', SEND_FLAG = '0', CMS_RESULT = '$result', CMS_RESULT_CODE = '$result_code', CMS_RESULT_MSG = '$result_mag'
								 WHERE M_NO = '$m_no'  ";
		} else {
			$query = "UPDATE TBL_MEMBER SET CMS_FLAG = 'F', SEND_FLAG = '0', CMS_RESULT = '$result', CMS_RESULT_CODE = '$result_code', CMS_RESULT_MSG = '$result_mag'
								 WHERE M_NO = '$m_no'  ";
		}

		#echo $query;
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateMemberFileFlag($db, $seq_no) {

		$query = "UPDATE TBL_CMS_FILE SET RES_FLAG = '1' WHERE SEQ_NO = '$seq_no' ";
		
		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	$is_test = "N";

#====================================================================
# Request Parameter
#====================================================================
	// 결과 파일 위치
	$dirname = "/home/aegisCMSClnt/data/member/recv/";

	$arr_rs = getResMemberFile($conn);

	exec("ls /home/aegisCMSClnt/data/member/recv/", $output);

	// 등록된 파일
	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) { // DB 파일 시작
			
			$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
			$SEND_FILE_NAME	= trim($arr_rs[$j]["SEND_FILE_NAME"]);
			
			$RES_FILE_NAME = $SEND_FILE_NAME.".res";
			
			for ($k = 0 ; $k < sizeof($output); $k++) {  // 시스템 파일 시작

				if ($RES_FILE_NAME == $output[$k]) { // DB, 시스템 파일 동일
					
					// 결과 파일 처리
					$fo = fopen($dirname.$RES_FILE_NAME, "r");

					while($str = fgets($fo, 3000)){

						if (left($str,1) == "D") {
							
							$req_type = substr($str,23,1);

							$mem_id = substr($str,24,20);
							$req_result = substr($str,288,1);
							$req_result_code = substr($str,289,4);
							$req_result_msg = substr($str,293,30);
							$req_result_msg = iconv('EUC-KR', 'UTF-8', $req_result_msg);
							
							if ($req_type == "D") {
								$result = updateMemberDelFlag($conn, intval($mem_id), $req_result, $req_result_code, $req_result_msg);
							} else {
								$result = updateMemberReqFlag($conn, intval($mem_id), $req_result, $req_result_code, $req_result_msg);
							}

						}
					}

					// 파일 처리 DB
					$result_up = updateMemberFileFlag($conn, $SEQ_NO);

				} // DB, 시스템 파일 끝
			} // 시스템 파일 끝

		} // DB 파일 끝
	}
	
//	echo sizeof($output);

//	print_r( $output );
//	echo "<br>";

//	echo "0 : ".iconv('UTF-8', 'EUC-KR', $output[0])."<br>";
//	echo "1 : ".$output[1]."<br>";
//	echo "2 : ".$output[2]."<br>";
//	echo "3 : ".$output[3]."<br>";
//	echo "4 : ".$output[4]."<br>";
//	echo "5 : ".$output[5]."<br>";


	
//	if ($is_test == "N") {
//		system("/home/aegisCMSClnt/sh/memreq.sh");
//	}

	mysql_close($conn);
?>