<?session_start();?>
<?
# =============================================================================
# File Name    : vacation_dml.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-08-18
# Modify Date  : 
# Copyright    : Copyright @UCOM Corp. All Rights Reserved.
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
	include "../../_common/common_header_mobile.php"; 

	
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
		if (chkVacationDatePeriodMobile($conn, $va_user, $va_mdate, $seq_no)) { 

			mysql_close($conn);
			echo "DUP";
			exit;
		} 

		$arr_data = array("VA_TYPE"=>$va_type,
											"VA_MEMO"=>$va_memo,
											"VA_USER"=>$va_user,
											"VA_STATE"=>$va_state,
											"VA_STATE_POS"=>$va_state_pos,
											"DEL_TF"=>"N",
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"VA_PERIOD"=>"1",
											"VA_MDATE"=>$va_mdate,
											"VA_WAY"=>$va_way,
											"VA_HPHONE"=>$va_hphone,
											"MEMO"=>$memo
											);

		$new_seq_no = insertVacation($conn, $arr_data);
		
		if (($va_type == 1) || ($va_type == 7) || ($va_type == 11) || ($va_type == 13)) {
			$va_cnt = 0.5;
		} else if (($va_type == 2) || ($va_type == 3) || ($va_type == 4)) {
			$va_cnt = 1;
		} else {
			$va_cnt = 0;
		}

		//모바일은 날짜 추가 2022-08-18

		$term = explode(",", $va_mdate);

		for ($i=0; $i<sizeof($term); $i++) {
			$term_txt = $term[$i];
			$arr_data = array("SEQ_NO"=>$new_seq_no,
												"VA_DATE"=>$term_txt,
												"VA_USER"=>$va_user,
												"VA_CNT"=>$va_cnt
												);

			$result = insertVacationDate($conn, $arr_data);

		}

		echo "T";
	}

	// 연차 수정과 삭제는 PC에서만
	/*
	if ($mode == "U") {

		// 이미 DB에 있는 날짜가 있는지 확인 합니다.
		if (chkVacationDatePeriod($conn, $va_user, $va_sdate, $va_edate, $va_mdate, $seq_no)) {

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
											"VA_STATE_POS"=>$va_state_pos,
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"VA_PERIOD"=>$va_period,
											"VA_MDATE"=>$va_mdate,
											"VA_WAY"=>$va_way,
											"VA_HPHONE"=>$va_hphone,
											"MEMO"=>$memo
											);

		$result = updateVacation($conn, $arr_data, $seq_no);

		// 연차 수정 내용을 메일로 전달 한다.
		$arr_user_info	= getAdminAllInfo($conn, $va_user);
		$arr_adm_info		= getAdminAllInfo($conn, $_SESSION['s_adm_no']);
		
		$user_name	= trim($arr_user_info[0]["ADM_NAME"]); 
		$user_email = trim($arr_user_info[0]["ADM_EMAIL"]); 
		$adm_email	= trim($arr_adm_info[0]["ADM_EMAIL"]); 

		$EMAIL		= $adm_email;
		$NAME			= "유컴패니온 (인트라넷)";
		$SUBJECT	= "연차 신청 상태가 변경 되었습니다.";
		//$CONTENT	= $user_name."님 연차상태 변경 안내 입니다.<br><br>(".getDcodeName($conn, "VA_TYPE", $va_type).") <br><br>기간 : ".$va_sdate." ~ ".$va_edate."<br><br>".$va_memo."<br><br>상태 : ".getDcodeName($conn, "VA_STATE", $va_state);

		if ($va_period == "0") {

			$CONTENT	= $user_name."님 연차상태 변경 안내 입니다.<br><br>(".getDcodeName($conn, "VA_TYPE", $va_type).") <br><br>기간 : ".$va_sdate." ~ ".$va_edate."<br><br>".$va_memo."<br><br>상태 : ".getDcodeName($conn, "VA_STATE", $va_state);

		} else {
			$CONTENT	= $user_name."님 연차상태 변경 안내 입니다.<br><br>(".getDcodeName($conn, "VA_TYPE", $va_type).") <br><br>기간 : ".$va_sdate." ~ ".$va_edate."<br><br>".$va_memo."<br><br>상태 : ".getDcodeName($conn, "VA_STATE", $va_state);
			//$txt = rtrim($va_mdate, ",");
			//$CONTENT	= $user_name."님 연차상태 변경 안내 입니다.<br><br>(".getDcodeName($conn, "VA_TYPE", $va_type).") <br><br> 날짜 : ".$txt."<br><br>".$va_memo."<br><br>상태 : ".getDcodeName($conn, "VA_STATE", $va_state);

		}
		$mailto		= $user_email;
		$result_send_mail = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

		if (($va_type == 1) || ($va_type == 7) || ($va_type == 11) || ($va_type == 13)) {
			$va_cnt = 0.5;
		} else if (($va_type == 2) || ($va_type == 3) || ($va_type == 4)) {
			$va_cnt = 1;
		} else {
			$va_cnt = 0;
		}
		
		$result = deleteVacationDate($conn, $seq_no);

		if($va_period == "0") { //기간 설정일 경우

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
		} else { //날짜 선택인 경우

			$term = rtrim($va_mdate, ",");
			$term = explode(",", $term);

			for ($i=0; $i<sizeof($term); $i++) {
				$term_txt = str_replace("'", "", $term[$i]);
				$arr_data = array("SEQ_NO"=>$seq_no,
													"VA_DATE"=>$term_txt,
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
	*/

#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>