<?session_start();?>
<?
# =============================================================================
# File Name    : vacation_dml.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-11-05
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "VA002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/vacation/vacation.php";

#====================================================================
# DML Process
#====================================================================
	
	//$va_type			= iconv("UTF-8", "EUC-KR", $va_type);
	//$va_memo			= iconv("UTF-8", "EUC-KR", $va_memo);
	//$va_user			= iconv("UTF-8", "EUC-KR", $va_user);
	//$va_state			= iconv("UTF-8", "EUC-KR", $va_state);

	#echo $adm_no;

	if ($mode == "I") {

		// 이미 DB에 있는 날짜가 있는지 확인 합니다.
		if (chkVacationDate($conn, $va_user, $va_sdate, $va_edate, $seq_no)) {

			mysql_close($conn);
			echo "DUP";
			exit;
		} 

		$arr_data = array("VA_TYPE"=>$va_type,
											"VA_SDATE"=>$va_sdate,
											"VA_EDATE"=>$va_edate,
											"VA_MEMO"=>$va_memo,
											"VA_USER"=>$va_user,
											"VA_STATE"=>$va_state,
											"DEL_TF"=>"N",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$new_seq_no = insertVacation($conn, $arr_data);
		
		if (($va_type == 1) || ($va_type == 7)) {
			$va_cnt = 0.5;
		} else if (($va_type == 2) || ($va_type == 3) || ($va_type == 4)) {
			$va_cnt = 1;
		} else {
			$va_cnt = 0;
		}

		$term = intval((strtotime($va_edate)-strtotime($va_sdate))/86400); //날짜 사이의 일수를 구한다.
		
		for($i=0; $i<=$term; $i++) {
			
			$tmp_date = date("Y-m-d", strtotime($va_sdate.'+'.$i.' day')); //두 날짜사이의 날짜를 구한다.
			$tmp_week = date("w",strtotime($va_sdate.'+'.$i.' day'));

			$is_weekend = "N";

			// 주말 인지 확인 합니다.
			if (($tmp_week == 6) || ($tmp_week == 0)) {
				$is_weekend = "Y";
			}

			if (isHoliday($conn, $tmp_date) == "true") {
				$is_weekend = "Y";
			}

			if ($is_weekend == "N") {

				// 휴일 날짜 등록
				$arr_data = array("SEQ_NO"=>$new_seq_no,
													"VA_DATE"=>$tmp_date,
													"VA_USER"=>$va_user,
													"VA_CNT"=>$va_cnt
													);

				$result = insertVacationDate($conn, $arr_data);
			}
		}

		echo "T";
	}

	// 연차 수정
	if ($mode == "U") {

		// 이미 DB에 있는 날짜가 있는지 확인 합니다.
		if (chkVacationDate($conn, $va_user, $va_sdate, $va_edate, $seq_no)) {

			mysql_close($conn);
			echo "DUP";
			exit;
		} 

		$arr_data = array("VA_TYPE"=>$va_type,
											"VA_SDATE"=>$va_sdate,
											"VA_EDATE"=>$va_edate,
											"VA_MEMO"=>$va_memo,
											"VA_USER"=>$va_user,
											"VA_STATE"=>$va_state,
											"UP_ADM"=>$_SESSION['s_adm_no']
											);

		$result = updateVacation($conn, $arr_data, $seq_no);
		
		if (($va_type == 1) || ($va_type == 7)) {
			$va_cnt = 0.5;
		} else if (($va_type == 2) || ($va_type == 3) || ($va_type == 4)) {
			$va_cnt = 1;
		} else {
			$va_cnt = 0;
		}
		
		$result = deleteVacationDate($conn, $seq_no);

		$term = intval((strtotime($va_edate)-strtotime($va_sdate))/86400); //날짜 사이의 일수를 구한다.
		
		for($i=0; $i<=$term; $i++) {
			
			$tmp_date = date("Y-m-d", strtotime($va_sdate.'+'.$i.' day')); //두 날짜사이의 날짜를 구한다.
			$tmp_week = date("w",strtotime($va_sdate.'+'.$i.' day'));
			
			$is_weekend = "N";

			// 주말 인지 확인 합니다.
			if (($tmp_week == 6) || ($tmp_week == 0)) {
				$is_weekend = "Y";
			}

			if (isHoliday($conn, $tmp_date) == "true") {
				$is_weekend = "Y";
			}

			if ($is_weekend == "N") {

				// 휴일 날짜 등록
				$arr_data = array("SEQ_NO"=>$seq_no,
													"VA_DATE"=>$tmp_date,
													"VA_USER"=>$va_user,
													"VA_CNT"=>$va_cnt
													);

				$result = insertVacationDate($conn, $arr_data);

			}
		}

		echo "UT";

	}

	if ($mode == "D") {
		
		// 연차 신청 삭제
		$result = deleteVacation($conn, $seq_no);

		// 연차 등록일 삭제
		$result = deleteVacationDate($conn, $seq_no);

		echo "T";
	}

#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>