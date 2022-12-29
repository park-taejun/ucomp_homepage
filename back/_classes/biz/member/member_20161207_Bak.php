<?

	# =============================================================================
	# File Name    : member.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2014.10.20
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	// 데이터 이전
	//DELETE FROM TBL_MEMBER;
	//INSERT INTO TBL_MEMBER SELECT * FROM g4_member

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function dupCheckMember($db, $type, $check_value) {
		
		$return_str = "T";

		if ($type == "ID") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_ID = '$check_value' AND M_LEAVE_DATE = '' ";
		}

		if ($type == "NICK") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_NICK = '$check_value' AND M_LEAVE_DATE = '' ";
		}

		if ($type == "EMAIL") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_EMAIL = '$check_value' AND M_LEAVE_DATE = '' ";
		}

		if ($type == "JUMIN") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_JUMIN = '$check_value' AND M_LEAVE_DATE = '' ";
		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		if ($rows[0] == 0) {
			$return_str = "T";
		} else {
			$return_str = "F";
		}

		if ($type == "ID" && $return_str =="T") {
			
			$query = "SELECT COUNT(*) AS CNT FROM TBL_ADMIN_INFO WHERE ADM_ID = '$check_value' ";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			if ($rows[0] == 0) {
				$return_str = "T";
			} else {
				$return_str = "F";
			}
		}

		return $return_str;
	}

	function dupCheckModifyMember($db, $type, $check_value, $m_no) {
		
		$return_str = "T";

		if ($type == "ID") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_ID = '$check_value' AND M_LEAVE_DATE = '' AND M_NO <> '$m_no' ";
		}

		if ($type == "NICK") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_NICK = '$check_value' AND M_LEAVE_DATE = '' AND M_NO <> '$m_no' ";
		}

		if ($type == "EMAIL") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_EMAIL = '$check_value' AND M_LEAVE_DATE = '' AND M_NO <> '$m_no' ";
		}

		if ($type == "JUMIN") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_JUMIN = '$check_value' AND M_LEAVE_DATE = '' AND M_NO <> '$m_no' ";
		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		if ($rows[0] == 0) {
			$return_str = "T";
		} else {
			$return_str = "F";
		}

		if ($type == "ID" && $return_str =="T") {
			
			$query = "SELECT COUNT(*) AS CNT FROM TBL_ADMIN_INFO WHERE ADM_ID = '$check_value' ";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			if ($rows[0] == 0) {
				$return_str = "T";
			} else {
				$return_str = "F";
			}
		}

		return $return_str;
	}


	function selectMemberAsJumin($db, $m_jumin) {

		$query = "SELECT * FROM TBL_MEMBER WHERE M_JUMIN = '$m_jumin' AND M_LEAVE_DATE = '' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function selectMemberAsID($db, $m_id) {
		$query = "SELECT * FROM TBL_MEMBER WHERE M_ID = '$m_id' AND M_LEAVE_DATE = '' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}



	function searchMember($db, $search_field, $m_name, $value) {

		$query = "SELECT * FROM TBL_MEMBER WHERE M_NAME = '$m_name' AND M_LEAVE_DATE = '' AND ".$search_field." = '$value' ORDER BY M_NO DESC limit 1";
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


	function listMember($db, $start_date, $end_date, $pay_type, $area_cd, $party, $agreement, $m_online_flag, $group_cd, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount) {
		$total_cnt = totalCntMember($db, $start_date, $end_date, $pay_type, $area_cd, $party, $agreement, $m_online_flag, $group_cd, $search_field, $search_str);
		$offset = $nRowCount*($nPage-1);
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, M_NO, M_ID, M_PASSWORD, M_NAME, M_NICK, M_NICK_DATE, M_EMAIL, M_HOMEPAGE, 
										 M_PASSWORD_Q, M_PASSWORD_A, M_LEVEL, M_JUMIN, M_SEX, M_BIRTH, M_TEL, M_HP, M_ZIP1, M_ZIP2,
										 M_ADDR1, M_ADDR2, M_SIGNATURE, M_RECOMMEND, M_POINT, M_TODAY_LOGIN, M_LOGIN_IP, M_DATETIME, M_IP, 
										 M_LEAVE_DATE, M_INTERCEPT_DATE, M_EMAIL_CERTIFY, M_MEMO, M_MAILLING, M_SMS, M_OPEN, M_OPEN_DATE, M_PROFILE, 
										 M_MEMO_CALL, M_1, M_2, M_3, M_4, M_5, M_6, M_7, M_8, M_9, M_10, M_11, M_12, SIDO, SIGUNGU, CMS_FLAG, SEND_FLAG, REQ_DAY, M_ONLINE_FLAG, M_ORGANIZATION 
							 FROM TBL_MEMBER 
							WHERE M_LEAVE_DATE = '' ";

		if ($start_date <> "") {
			$query .= " AND M_OPEN_DATE >= '".$start_date."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}


		if ($end_date <> "") {
			$query .= " AND M_OPEN_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		if ($m_online_flag <> "") {
			$query .= " AND M_ONLINE_FLAG = '$m_online_flag' ";
		}

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntMember($db, $start_date, $end_date, $pay_type, $area_cd,  $party, $agreement, $m_online_flag, $group_cd, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_MEMBER WHERE M_LEAVE_DATE = '' ";

		if ($start_date <> "") {
			$query .= " AND M_OPEN_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND M_OPEN_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		if ($m_online_flag <> "") {
			$query .= " AND M_ONLINE_FLAG = '$m_online_flag' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	
	
	function insertMember($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				if ($key == "M_PASSWORD") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_MEMBER (".$set_field.") 
					values (".$set_value."); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			$new_mem_no = mysql_insert_id();
			//deleteTemporarySave($db, $b_code, $writer_id);
			return $new_mem_no;
		}
	}

	function selectMember($db, $m_no) {

		$query = "SELECT * FROM TBL_MEMBER WHERE M_NO = '$m_no' AND M_LEAVE_DATE = '' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function changeMemberPassword($db, $m_password, $mem_id) {

		$query = "UPDATE TBL_MEMBER SET M_PASSWORD='$m_password' 
							 WHERE M_ID = '$mem_id'  ";
		
		#echo $query;
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function changeMemberlevel($db, $m_no, $m_levels) {

		$query = "UPDATE TBL_MEMBER SET M_LEVEL='$m_levels' 
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

		function updateMemberAdmin($db, $mem_type, $mem_nm, $mem_pw, $biz_num1, $biz_num2, $biz_num3, $birth_date, $calendar, $sex, $email, $email_tf, $user_pw_req, $user_pw_ans, $zipcode, $addr1, $addr2, $phone, $hphone, $sms_tf, $job, $position, $cphone, $cfax, $czipcode, $caddr1, $caddr2, $join_how, $join_how_person, $join_how_etc, $etc, $use_tf, $up_adm, $mem_no) {

		if ($mem_no <> "") {

			if ($mem_no <> "0") {

				$query="UPDATE TBL_MEMBER SET 
						MEM_TYPE				= '$mem_type',
						MEM_NM					= '$mem_nm',";
				if($mem_pw!="")$query.="MEM_PW					= '$mem_pw',";
				$query.="MEM_PW_REQ				= '$user_pw_req',
						MEM_PW_ANS				= '$user_pw_ans',
						BIRTH_DATE				= '$birth_date',
						CALENDAR				= '$calendar',						
						SEX						= '$sex',
						EMAIL					= '$email',
						EMAIL_TF				= '$email_tf',
						ZIPCODE					= '$zipcode',
						ADDR1					= '$addr1',
						ADDR2					= '$addr2',
						PHONE					= '$phone',
						HPHONE					= '$hphone',
						SMS_TF					= '$sms_tf',
						JOB						= '$job',
						POSITION				= '$position',
						CPHONE					= '$cphone',
						CFAX					= '$cfax',
						CZIPCODE				= '$czipcode',
						CADDR1					= '$caddr1',
						CADDR2					= '$caddr2',
						JOIN_HOW				= '$join_how',
						JOIN_HOW_PERSON			= '$join_how_person',
						JOIN_HOW_ETC			= '$join_how_etc',
						ETC						= '$etc',
						USE_TF					= '$use_tf',
						UP_ADM					= '$up_adm',
						UP_DATE					= now()
				 WHERE MEM_NO					= '$mem_no' ";



				if(!mysql_query($query,$db)) {
					return false;
					echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
					exit;
				} else {
					return true;
				}
			}
		}
		return true;
	}

	function updateMember($db, $arr_data, $m_password, $m_nick_date, $m_level, $m_id) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_MEMBER SET ".$set_query_str." ";
		
		if ($m_password) {
			$query .= "M_PASSWORD = PASSWORD('$m_password'),";
		}

		if ($m_nick_date) {
			$query .= "M_NICK_DATE = '$m_nick_date',";
		}

		if ($m_level) {
			$query .= "M_LEVEL = '$m_level',";
		}
		
		$query .= "M_ID = '$m_id' WHERE M_ID = '$m_id' ";

		//echo $query."<br>";

		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateMemberAsNo($db, $arr_data, $m_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_MEMBER SET ".$set_query_str." ";
		
		$query .= " M_NO = '$m_no' WHERE M_NO = '$m_no' ";

		//echo $query."<br>";

		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function quitMember($db, $m_id) {

		$date = date("Ymd",strtotime("0 day"));
		
		$query = "UPDATE TBL_MEMBER SET M_LEAVE_DATE = '$date', QUIT_CMS_FLAG ='D' WHERE M_ID = '$m_id' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function quitMemberno($db, $m_no) {

		$date = date("Ymd",strtotime("0 day"));
		
		$query = "UPDATE TBL_MEMBER SET M_LEAVE_DATE = '$date', QUIT_CMS_FLAG ='D' WHERE M_NO = '$m_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateMemberID($db, $id, $m_no) {
		
		$query = "UPDATE TBL_MEMBER SET M_ID = '$id' WHERE M_NO = '$m_no' ";


		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertTempMember($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				if ($key == "M_PASSWORD") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_MEMBER_TEMP (".$set_field.") 
					values (".$set_value."); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listMemberTemp($db, $temp_no) {

		$query = "SELECT * FROM TBL_MEMBER_TEMP WHERE TEMP_NO = '$temp_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function insertTempToRealMember($db, $temp_no, $seq_no, $key, $iv) {

		$query="SELECT SEQ_NO, TEMP_NO, M_NAME, M_SEX, M_AGE, M_BIRTH, M_JUMIN, M_TEL, M_HP, M_ZIP1,
									 M_ADDR1, M_ADDR2, SIDO, PARTY, JOB, COM_NAME, M_GROUP, PAY_TYPE, BANK_NO, BANK_CODE,
									 CMS_AMOUNT, AREA, O_ZIP1, O_ADDR1, O_ADDR2, M_ONLINE_FLAG, M_SMS, M_MAILLING, M_EMAIL, M_ORGANIZATION
							FROM TBL_MEMBER_TEMP
						 WHERE TEMP_NO = '$temp_no' AND SEQ_NO IN ($seq_no) ";
			
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}

		if (sizeof($record) > 0) {
			for ($j = 0 ; $j < sizeof($record); $j++) {

				$SEQ_NO							= trim($record[$j]["SEQ_NO"]);
				$TEMP_NO						= trim($record[$j]["TEMP_NO"]);
				$M_NAME							= trim($record[$j]["M_NAME"]);
				$M_SEX							= trim($record[$j]["M_SEX"]);
				$M_AGE							= trim($record[$j]["M_AGE"]);
				$M_BIRTH						= trim($record[$j]["M_BIRTH"]);
				$M_JUMIN						= trim($record[$j]["M_JUMIN"]);
				$M_TEL							= trim($record[$j]["M_TEL"]);
				$M_HP								= trim($record[$j]["M_HP"]);
				$M_ZIP1							= trim($record[$j]["M_ZIP1"]);
				$M_ADDR1						= trim($record[$j]["M_ADDR1"]);
				$M_ADDR2						= trim($record[$j]["M_ADDR2"]);
				$SIDO								= trim($record[$j]["SIDO"]);
				$PARTY							= trim($record[$j]["PARTY"]);
				$JOB								= trim($record[$j]["JOB"]);
				$COM_NAME						= trim($record[$j]["COM_NAME"]);
				$M_GROUP						= trim($record[$j]["M_GROUP"]);
				$PAY_TYPE						= trim($record[$j]["PAY_TYPE"]);
				$BANK_NO						= trim($record[$j]["BANK_NO"]);
				$BANK_CODE					= trim($record[$j]["BANK_CODE"]);
				$CMS_AMOUNT					= trim($record[$j]["CMS_AMOUNT"]);
				$AREA								= trim($record[$j]["AREA"]);
				$O_ZIP1							= trim($record[$j]["O_ZIP1"]);
				$O_ADDR1						= trim($record[$j]["O_ADDR1"]);
				$O_ADDR2						= trim($record[$j]["O_ADDR2"]);
				$M_ONLINE_FLAG			= trim($record[$j]["M_ONLINE_FLAG"]);
				$M_SMS							= trim($record[$j]["M_SMS"]);
				$M_MAILLING					= trim($record[$j]["M_MAILLING"]);
				$M_EMAIL						= trim($record[$j]["M_EMAIL"]);
				$M_ORGANIZATION			= trim($record[$j]["M_ORGANIZATION"]);
				$REQ_DAY						= trim($record[$j]["REQ_DAY"]);

				$result_jumin = dupCheckMember($db, "JUMIN", $M_JUMIN);
				
				// 중복 없으면 등록
				if ($result_jumin == "T") {
					
					// 비밀번호 만들기
					$strJumin = decrypt($key, $iv, $M_JUMIN);
					$arrJumin	= explode("-",$strJumin);

					$en_password = encrypt($key, $iv, $arrJumin[1]);

					$m_sex		= left($arrJumin[1],1);
					$m_birth	= "19".$arrJumin[0];

					$str_address = $SIDO." ".$M_ADDR1;

					$arr_address = explode(" ",$str_address);

					//echo $arr_address[0]."<br>";
					//echo $arr_address[1]."<br>";

					$g_register_level = "2";
					
					$en_cms_info01 = "";
					$en_cms_info02 = "";
					$en_cms_info03 = "";
					$en_cms_info04 = "";
					$en_cms_info05 = "";
					
					$is_pay = "N";

					//휴대폰 번호
					$strTel		= decrypt($key, $iv, $M_TEL);
					$strMTel	= decrypt($key, $iv, $M_HP); 

					$str_tel = getPhoneNumber($strTel);
					$str_mtel = getPhoneNumber($strMTel);
					
					$en_tel		= encrypt($key, $iv, $str_tel);
					$en_mtel	= encrypt($key, $iv, $str_mtel);
					
					// 결제 관련 코드로 변환 후 등록
					$strBankCode		= decrypt($key, $iv, $BANK_CODE);
					
					$pay_type = "";
					$is_pay = "N";

					if ($PAY_TYPE == "CMS") {
						$is_pay = "Y";
						$pay_type = "cms";

						$str_code = getDcodeCode($db, "BANK_CODE", $strBankCode);
						$en_bank_code	= encrypt($key, $iv, $str_code);
						$en_cms_info02 = $BANK_NO;
						$en_cms_info01 = $en_bank_code;

						$en_cms_info03	= encrypt($key, $iv, $M_NAME);

					}

					if ($PAY_TYPE == "신용카드") {
						$en_cms_info05	= encrypt($key, $iv, $M_NAME);
					}

					if ($PAY_TYPE == "휴대전화") {
						$is_pay = "Y";
						$pay_type = "mobile";

						$str_code = getDcodeCode($db, "MOBILE_COM", $strBankCode);
						$en_bank_code	= encrypt($key, $iv, $str_code);
						$en_cms_info01 = $BANK_NO;
						$en_cms_info02 = $en_bank_code;
					}

					$arr_data = array("M_NAME"=>$M_NAME,
														"M_JUMIN"=>$M_JUMIN,
														"M_PASSWORD"=>$arrJumin[1],
														"M_SEX"=>$m_sex,
														"M_BIRTH"=>$m_birth,
														"M_NICK_DATE"=>date("Y-m-d",strtotime("0 day")),
														"M_SMS"=>$M_SMS,
														"M_TEL"=>$en_tel,
														"M_HP"=>$en_mtel,
														"M_SMS"=>$M_SMS,
														"M_EMAIL"=>$M_EMAIL,
														"M_MAILLING"=>$M_MAILLING,
														"M_ZIP1"=>$M_ZIP1,
														"M_ADDR1"=>$str_address,
														"M_ADDR2"=>$M_ADDR2,
														"M_O_ZIP"=>$O_ZIP1,
														"M_O_ADDR1"=>$O_ADDR1,
														"M_O_ADDR2"=>$O_ADDR2,
														"M_SIGNATURE"=>$m_signature,
														"M_PROFILE"=>$m_profile,
														"M_TODAY_LOGIN"=>date("Y-m-d H:i:s",strtotime("0 day")),
														"M_DATETIME"=>date("Y-m-d H:i:s",strtotime("0 day")),
														"M_IP"=>$_SERVER[REMOTE_ADDR],
														"M_LEVEL"=>$g_register_level,
														"M_LOGIN_IP"=>$_SERVER[REMOTE_ADDR],
														"M_OPEN_DATE"=>date("Y-m-d",strtotime("0 day")),
														"M_1"=>$JOB,
														"M_2"=>$COM_NAME,
														"M_3"=>$PARTY,
														"M_4"=>$M_GROUP,
														"M_5"=>$is_pay,
														"M_6"=>$pay_type,
														"M_7"=>$en_cms_info01,
														"M_8"=>$en_cms_info02,
														"M_9"=>$en_cms_info03,
														"M_10"=>$en_cms_info04,
														"M_11"=>$en_cms_info05,
														"M_12"=>$CMS_AMOUNT,
														"M_CMS_BIRTH"=>$m_birth,
														"SIDO"=>$arr_address[0],
														"SIGUNGU"=>$arr_address[1],
														"M_ORGANIZATION"=>$M_ORGANIZATION,
														"REQ_DAY"=>$REQ_DAY,
														"M_MEMO"=>$m_memo,
														"M_ONLINE_FLAG"=>$M_ONLINE_FLAG,
														"M_EMAIL_CERTIFY"=>date("Y-m-d H:i:s",strtotime("0 day")));

					$new_mem_no =  insertMember($db, $arr_data);
					$str_id = right("00000000".$new_mem_no, 8);
					$result = updateMemberID($db, $str_id, $new_mem_no);

				}
			}
		}

		if(!$result) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

	function deleteTempToRealMember($db, $temp_no, $seq_no) {

		$query=" DELETE FROM TBL_MEMBER_TEMP WHERE TEMP_NO = '$temp_no' AND SEQ_NO IN ($seq_no) ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteMemberTemp($db, $temp_no, $seq_no) {

		$query="DELETE FROM TBL_MEMBER_TEMP WHERE TEMP_NO = '$temp_no' AND SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function insertMemberPayHistory($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		$first = "Y";

		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				if ($key == "M_PASSWORD") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_MEMBER_PAY_HISTORY  (".$set_field." , up_date) 
					values (".$set_value.", now()); ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			//$new_mem_no = mysql_insert_id();
			//deleteTemporarySave($db, $b_code, $writer_id);
			return true;
		}
	}

	function listMemberPayHistory($db, $pay_type, $area_cd, $party, $agreement, $group_cd, $search_field, $search_str, $nPage, $nRowCount) {
		
		$total_cnt = totalCntMemberPayHistory($db, $pay_type, $area_cd, $party, $agreement, $group_cd, $search_field, $search_str);
		
		$offset = $nRowCount*($nPage-1);
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.SEQ_NO, A.M_NO, B.M_ID, B.M_NAME, B.SIDO,
										 A.IS_PAY, A.PAY_TYPE, A.M_7, A.M_8, A.M_9, A.M_10, B.M_HP,
										 B.M_3, B.M_5, B.M_6, B.M_7 AS P_M_7, B.M_8 AS P_M_8, B.M_9 AS P_M_9, B.M_10 AS P_M_10, B.M_11, B.M_12, A.up_date, A.chk_flag 
								FROM TBL_MEMBER_PAY_HISTORY A,  TBL_MEMBER B WHERE A.M_NO = B.M_NO AND B.M_LEAVE_DATE = '' AND A.chk_flag = '0' ";


		if ($group_cd <> "") {
			$query .= " AND B.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND A.PAY_TYPE like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%제주%') ";
			} else {
				$query .= " AND B.SIDO like '%".$area_cd."%' ";
			}

		}

		if ($party <> "") {
			$query .= " AND B.M_3 like '%".$party."%' ";
		}
		
		$query .= " ORDER BY A.SEQ_NO desc limit ".$offset.", ".$nRowCount;
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntMemberPayHistory($db, $pay_type, $area_cd,  $party, $agreement, $group_cd, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT 
							 FROM TBL_MEMBER_PAY_HISTORY A, TBL_MEMBER B 
							WHERE A.M_NO = B.M_NO AND B.M_LEAVE_DATE = '' AND A.chk_flag = '0' ";

		if ($group_cd <> "") {
			$query .= " AND B.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND A.PAY_TYPE like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (B.SIDO like '%".$area_cd."%' OR B.SIDO like '%제주%') ";
			} else {
				$query .= " AND B.SIDO like '%".$area_cd."%' ";
			}

		}

		if ($party <> "") {
			$query .= " AND B.M_3 like '%".$party."%' ";
		}
		
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function updatePayInfo($db, $seq_no, $m_no) {
		
		$query = "SELECT IS_PAY, PAY_TYPE, M_7, M_8, M_9, M_10, M_11, M_12 FROM TBL_MEMBER_PAY_HISTORY WHERE SEQ_NO = '$seq_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		
		$RS_IS_PAY	= $record[0]["IS_PAY"];
		$PAY_TYPE		= $record[0]["PAY_TYPE"];
		$M_7				= $record[0]["M_7"];
		$M_8				= $record[0]["M_8"];
		$M_9				= $record[0]["M_9"];
		$M_10				= $record[0]["M_10"];
		$M_11				= $record[0]["M_11"];
		$M_12				= $record[0]["M_12"];


		$query = "UPDATE TBL_MEMBER SET M_5 = '$RS_IS_PAY',  M_6 = '$PAY_TYPE', M_7 = '$M_7', M_8 = '$M_8', M_9 = '$M_9', 
										M_10 = '$M_10', M_11 = '$M_11' , M_12 = '$M_12', CMS_FLAG = 'U', SEND_FLAG = '0', CMS_RESULT ='' WHERE M_NO = '$m_no'  ";

		//echo $query;
		
		mysql_query($query,$db);

		$query = "UPDATE TBL_MEMBER_PAY_HISTORY SET chk_flag = '1' WHERE SEQ_NO = '$seq_no'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function listCmsErrMember($db, $pay_type, $area_cd, $party, $agreement, $group_cd, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount) {
		$total_cnt = totalCntCmsErrMember($db, $pay_type, $area_cd, $party, $agreement, $group_cd, $search_field, $search_str);
		$offset = $nRowCount*($nPage-1);
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, M_NO, M_ID, M_PASSWORD, M_NAME, M_NICK, M_NICK_DATE, M_EMAIL, 
										 M_HOMEPAGE, M_PASSWORD_Q, M_PASSWORD_A, M_LEVEL, M_JUMIN, M_SEX, M_BIRTH, M_TEL, M_HP, 
										 M_ZIP1, M_ZIP2, M_ADDR1, M_ADDR2, M_SIGNATURE, M_RECOMMEND, M_POINT, M_TODAY_LOGIN, M_LOGIN_IP, 
										 M_DATETIME, M_IP, M_LEAVE_DATE, M_INTERCEPT_DATE, M_EMAIL_CERTIFY, M_MEMO, M_MAILLING, M_SMS, M_OPEN, 
										 M_OPEN_DATE, M_PROFILE, M_MEMO_CALL, M_1, M_2, M_3, M_4, M_5, M_6, M_7, M_8, M_9, M_10, M_11, M_12, SIDO, SIGUNGU, CMS_FLAG, SEND_FLAG,
										 CMS_RESULT_MSG
								FROM TBL_MEMBER WHERE M_LEAVE_DATE = '' AND CMS_RESULT = 'N' ";

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}

		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntCmsErrMember($db, $pay_type, $area_cd,  $party, $agreement, $group_cd, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_MEMBER WHERE M_LEAVE_DATE = ''  AND CMS_RESULT = 'N' ";

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}

		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function insertModifyHistoryMember($db, $m_no, $m_memo, $adm_no) {

		$query = "INSERT INTO TBL_MEMBER_MODIFY_HISTORY (M_NO, M_MEMO, UP_ADM, UP_DATE) 
					values ('$m_no','$m_memo','$adm_no', now()); ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listModifyHistoryMember($db, $m_no, $cnt) {
	
		$query = "SELECT S_NO, M_NO, M_MEMO, UP_ADM, UP_DATE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_MEMBER_MODIFY_HISTORY.UP_ADM) AS ADM_NAME
								FROM TBL_MEMBER_MODIFY_HISTORY WHERE M_NO = '$m_no' ORDER BY S_NO DESC ";
		
		if ($cnt <> "ALL") { 
			$query .= " limit ".$cnt;
		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;


	}

	function listPaymentHistoryMember($db, $m_no, $cnt) {
	
		$query = "SELECT A.M_ID, A.M_NAME, A.SIDO, B.PAY_YYYY, B.PAY_MM, B.PAY_TYPE, 
										 B.CMS_AMOUNT,
										 B.RES_CMS_AMOUNT -B.REFUND_AMOUNT AS RES_CMS_AMOUNT, 
										 B.CMS_CHARGE, B.RES_PAY_DATE, B.PAY_RESULT, B.PAY_RESULT_CODE,
										 B.PAY_RESULT_MSG, B.SEND_FLAG, B.SEND_DATE, B.REG_DATE, B.SEND_FILE_NAME, B.REFUND_AMOUNT
								FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B
							 WHERE A.M_NO = B.M_NO
								 AND B.DEL_TF = 'N'
								 AND A.M_NO = '$m_no' ORDER BY B.SEQ_NO DESC ";
		
		if ($cnt <> "ALL") { 
			$query .= " limit ".$cnt;
		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		
		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;


	}

	function listMemberAjaxSearch($db, $m_name, $party, $sido) {
	
		$query = "SELECT *
								FROM TBL_MEMBER
							 WHERE M_LEAVE_DATE = '' ";

		if ($m_name <> "") {
			$query .= " AND M_NAME like '%".$m_name."%' ";
		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}

		if ($sido <> "") {
			$query .= " AND SIDO like '%".$sido."%' ";
		}

		$query .= " ORDER BY M_NAME ASC ";

		$result = mysql_query($query,$db);
		$record = array();
		
		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;


	}


	function listQuitMember($db, $start_date, $end_date, $pay_type, $area_cd, $party, $agreement, $group_cd, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount) {
		$total_cnt = totalCntQuitMember($db, $start_date, $end_date, $pay_type, $area_cd, $party, $agreement, $group_cd, $search_field, $search_str);
		$offset = $nRowCount*($nPage-1);
		
		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, M_NO, M_ID, M_PASSWORD, M_NAME, M_NICK, M_NICK_DATE, M_EMAIL, M_HOMEPAGE, 
										 M_PASSWORD_Q, M_PASSWORD_A, M_LEVEL, M_JUMIN, M_SEX, M_BIRTH, M_TEL, M_HP, M_ZIP1, M_ZIP2,
										 M_ADDR1, M_ADDR2, M_SIGNATURE, M_RECOMMEND, M_POINT, M_TODAY_LOGIN, M_LOGIN_IP, M_DATETIME, M_IP, 
										 M_LEAVE_DATE, M_INTERCEPT_DATE, M_EMAIL_CERTIFY, M_MEMO, M_MAILLING, M_SMS, M_OPEN, M_OPEN_DATE, M_PROFILE, 
										 M_MEMO_CALL, M_1, M_2, M_3, M_4, M_5, M_6, M_7, M_8, M_9, M_10, M_11, M_12, SIDO, SIGUNGU, CMS_FLAG, SEND_FLAG, M_MEMO
							 FROM TBL_MEMBER 
							WHERE M_LEAVE_DATE <> '' ";

		if ($start_date <> "") {
			$query .= " AND M_LEAVE_DATE >= '".str_replace('-','',$start_date)."' ";
		}

		if ($end_date <> "") {
			$query .= " AND M_LEAVE_DATE <= '".str_replace('-','',$end_date)."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}

		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntQuitMember($db, $start_date, $end_date, $pay_type, $area_cd,  $party, $agreement, $group_cd, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_MEMBER WHERE M_LEAVE_DATE <> '' ";

		if ($start_date <> "") {
			$query .= " AND M_LEAVE_DATE >= '".str_replace('-','',$start_date)."' ";
		}

		if ($end_date <> "") {
			$query .= " AND M_LEAVE_DATE <= '".str_replace('-','',$end_date)."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectQuitMember($db, $m_no) {

		$query = "SELECT * FROM TBL_MEMBER WHERE M_NO = '$m_no' AND M_LEAVE_DATE <> '' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}



	function searchGroupCd($db, $party, $sido, $str) {
		
		$record = "";

		$query = "SELECT GROUP_CD FROM TBL_GROUP WHERE GROUP_CD = '$str' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		
		if ($record == "") {

			$temp_str = "str".$sido;

			if (strpos($temp_str, "경상북도") > 0) {
				$sido = "경북";
			} else if (strpos($temp_str, "경상남도") > 0) {
				$sido = "경남";
			} else if (strpos($temp_str, "전라북도") > 0) {
				$sido = "전북";
			} else if (strpos($temp_str, "전라남도") > 0) {
				$sido = "전남";
			} else if (strpos($temp_str, "충청북도") > 0) {
				$sido = "충북";
			} else if (strpos($temp_str, "충청남도") > 0) {
				$sido = "충남";
			} else if (strpos($temp_str, "세종") > 0) {
				$sido = "세종특별자치시";
			} else if (strpos($temp_str, "제주") > 0) {
				$sido = "제주특별자치도";
			} else if (strpos($temp_str, "광주") > 0) {
				$sido = "광주";
			} else if (strpos($temp_str, "인천") > 0) {
				$sido = "인천";
			} else if (strpos($temp_str, "부산") > 0) {
				$sido = "부산";
			} else if (strpos($temp_str, "대전") > 0) {
				$sido = "대전";
			} else if (strpos($temp_str, "대구") > 0) {
				$sido = "대구";
			} else if (strpos($temp_str, "울산") > 0) {
				$sido = "울산";
			} else if (strpos($temp_str, "강원") > 0) {
				$sido = "강원";
			} else if (strpos($temp_str, "경기") > 0) {
				$sido = "경기";
			} else if (strpos($temp_str, "서울") > 0) {
				$sido = "서울";
			}

			$query = "SELECT GROUP_CD FROM TBL_GROUP WHERE GROUP_KIND = '$party' AND GROUP_SIDO = '$sido' AND GROUP_NAME like '$str%' limit 1 ";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$record  = $rows[0];
		} 
		
		return $record;

	}

	function updateOrganization($db, $GROUP_CD, $SEQ_NO) {
		
		$query = "UPDATE TBL_MEMBER_TEMP SET M_ORGANIZATION = '$GROUP_CD' WHERE SEQ_NO = '$SEQ_NO'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}



?>