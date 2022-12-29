<?session_start();?>
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
		
		$query = "SELECT * FROM TBL_CMS_FILE WHERE RES_FLAG = '0' AND SEND_TYPE = 'P' ORDER BY SEND_FILE_NAME ASC";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

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

	function updatePaymentReqFlagAsSeqNo($db, $mem_no, $seq_no, $res_pay_date, $res_cms_amount, $cms_charge, $res_pay_no, $req_result, $req_result_code, $req_result_msg) {

		$query = "UPDATE TBL_MEMBER_PAYMENT SET RES_PAY_DATE = '$res_pay_date', RES_CMS_AMOUNT = '$res_cms_amount', CMS_CHARGE = '$cms_charge', 
										 RES_PAY_NO = '$res_pay_no', PAY_RESULT = '$req_result', PAY_RESULT_CODE = '$req_result_code', PAY_RESULT_MSG = '$req_result_msg'
								 WHERE M_NO = '$mem_no' AND SEQ_NO = '$seq_no' ";

		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updatePaymentReqFlagAsDate($db, $mem_no, $req_yy, $req_mm, $res_pay_date, $res_cms_amount, $cms_charge, $res_pay_no, $req_result, $req_result_code, $req_result_msg) {

		$query = "UPDATE TBL_MEMBER_PAYMENT SET RES_PAY_DATE = '$res_pay_date', RES_CMS_AMOUNT = '$res_cms_amount', CMS_CHARGE = '$cms_charge', 
										 RES_PAY_NO = '$res_pay_no', PAY_RESULT = '$req_result', PAY_RESULT_CODE = '$req_result_code', PAY_RESULT_MSG = '$req_result_msg'
								 WHERE M_NO = '$mem_no' AND PAY_YYYY = '$req_yy' AND PAY_MM = '$req_mm' ";

		//echo $query."<br>";

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
	$dirname = "/home/aegisCMSClnt/data/payment/recv/";

	$arr_rs = getResMemberFile($conn);
	
	exec("ls /home/aegisCMSClnt/data/payment/recv/", $output);

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
							
							// 회원 ID
							$mem_id = substr($str,24,20);
							// 출금년 
							$req_yy = left($filename,4);
							// 회차
							$req_mm = substr($str,64,2);
							// 출금일
							$res_pay_date = substr($str,246,8);
							// 출금액
							$res_cms_amount = substr($str,254,10);
							// 출금수수료
							$cms_charge = substr($str,264,6);
							// 출금번호
							$res_pay_no = substr($str,270,10);
							// 결과 
							$req_result = substr($str,288,1);
							// 결과 코드
							$req_result_code = substr($str,289,4);
							// 결과 메시지
							$req_result_msg = substr($str,293,30);
							// 출금요청 번호
							$req_seq_no = substr($str,323,20);

							$req_seq_no = trim($req_seq_no);

							/*
							echo $mem_id."<br>";
							echo $req_seq_no."<br>";
							echo $req_result_msg."<br>";
							*/

							$req_result_msg = iconv('EUC-KR', 'UTF-8', $req_result_msg);

							$result = updatePaymentReqFlagAsSeqNo($conn, intval($mem_id), $req_seq_no, $res_pay_date, intval($res_cms_amount), intval($cms_charge), $res_pay_no, $req_result, $req_result_code, $req_result_msg);

						}
					}
					
					// 파일 처리 DB
					$result_up = updateMemberFileFlag($conn, $SEQ_NO);

				}
			}
		}
	}


	mysql_close($conn);
?>