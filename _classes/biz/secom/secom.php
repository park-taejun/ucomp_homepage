<?

	# =============================================================================
	# File Name    : secom.php
	# Modlue       : 
	# Writer       : JeGal Jeong
	# Create Date  : 2022-03-08
	# Modify Date  : 
	#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listSecom($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);


		//$query = "SELECT * FROM TBL_SECOM WHERE 1 = 1";

		$query =	"SELECT A.ADM_NO, D.HEADQUARTERS_CODE, D.DEPT_CODE, D.POSITION_CODE, S.이름, S.근무일자 
									 , COUNT(CASE WHEN ((출근판정 <> '결근' OR ISNULL(출근판정)) AND (퇴근판정 <> '결근' OR ISNULL(퇴근판정))) THEN 1 END) AS 출근
									 , COUNT(CASE WHEN ((출근판정 = '결근') AND (퇴근판정 = '결근')) THEN 1 END) AS 결근
									 , COUNT(CASE WHEN ((출근판정 = '지각') OR ISNULL(출근판정)) THEN 1 END) AS 지각
									 , COUNT(CASE WHEN ((퇴근판정 = '조퇴') OR ISNULL(퇴근판정)) THEN 1 END) AS 조퇴
									 , COUNT(CASE WHEN ((S.연장근무시간 > 0) AND (연장근무시간-지각시간 > 0)) THEN 1 END) AS 연장근무
									 , COUNT(CASE WHEN S.야간근무시간 > 0 THEN 1 END) AS 야간근무      
									 , COUNT(CASE WHEN ((휴일근무시간 > 0) OR (출근판정 = '휴일출근') OR (퇴근판정 = '휴일퇴근')) THEN 1 END) AS 휴일근무
									 , TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(S.총근무시간))),'%H 시간 %i 분') AS 총근무시간 
							FROM TBL_SECOM S 
									 LEFT OUTER JOIN TBL_ADMIN_INFO A ON S.이름 = A.ADM_NAME 
									 LEFT OUTER JOIN TBL_ORG D ON D.ADM_NO = A.ADM_NO AND D.YEAR = '202206'
									 LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
									 LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
									 LEFT OUTER JOIN TBL_CODE_DETAIL C ON D.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							WHERE 1 = 1 ";

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}
		
		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' "; 
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " GROUP BY S.이름 
								ORDER BY A.COM_CODE ASC, H.DCODE_SEQ_NO ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC, A.ENTER_DATE ASC limit ".$offset.", ".$nRowCount;
		//$query .= " ORDER BY SEC_DATE ASC limit ".$offset.", ".$nRowCount;

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

	function totalCntSecom($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT COUNT(DISTINCT S.이름) CNT 
							FROM TBL_SECOM S 
										LEFT OUTER JOIN TBL_ADMIN_INFO A ON A.ADM_NAME = S.이름 
										LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206' 
							WHERE 1 = 1 ";

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}
		
		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' "; 
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function SelectSmartdayCommute($db, $adm_no, $sdate, $edate) {

		$query =	"SELECT COUNT(C.VA_DATE) AS 스마트데이출근 
										, COUNT( CASE WHEN ((TIMESTAMPDIFF(MINUTE, CONCAT(C.VA_DATE, ' ', LEFT(D.DCODE_NM, 5)), CONCAT(C.VA_DATE, ' ', C.COM_STIME))>0) OR ISNULL(C.COM_STIME)) THEN 1 END) AS 지각 
										, COUNT( CASE WHEN ((TIMESTAMPDIFF(MINUTE, CONCAT(C.VA_DATE, ' ', C.COM_ETIME), CONCAT(C.VA_DATE, ' ', RIGHT(D.DCODE_NM, 5)))>0) OR ISNULL(C.COM_ETIME)) THEN 1 END) AS 조퇴 
										, TIME_FORMAT( CONCAT(SUM(TIME_TO_SEC(TIMESTAMPDIFF(HOUR, CONCAT(C.VA_DATE, ' ', C.COM_STIME), CONCAT(C.VA_DATE, ' ', C.COM_ETIME)))),':00:00'), '%H 시간 %i 분') AS 총근무시간
							FROM TBL_COMMUTE C
									 LEFT OUTER JOIN TBL_ADMIN_INFO A ON C.VA_USER = A.ADM_NO
									 LEFT OUTER JOIN TBL_CODE_DETAIL D ON A.COMMUTE_TIME = D.DCODE AND D.USE_TF = 'Y' AND D.DEL_TF ='N' AND D.PCODE = 'COMMUTE_TIME'
							WHERE C.COM_MEMO NOT LIKE '%사무실 출근%' AND C.VA_USER = '$adm_no' AND C.VA_DATE BETWEEN '$sdate' AND '$edate';";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function SelectVacation($db, $adm_no, $sdate, $edate) {

		$query = "SELECT SUM(VA_CNT) FROM TBL_VACATION_DATE WHERE VA_USER = '$adm_no' AND VA_DATE BETWEEN '$sdate' AND '$edate'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

/*
	function insertAdmin($db, $arr_data) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";

		foreach ($arr_data as $key => $value) {

			$value = str_replace("'","''",$value);

			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'";
			}
		}

		$query ="SELECT IFNULL(MAX(ADM_NO),0) + 1 AS MAX_NO FROM TBL_ADMIN_INFO ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		$new_adm_no = $rows[0];

		$query = "INSERT INTO TBL_ADMIN_INFO (ADM_NO, ".$set_field.", REG_DATE, UP_DATE) 
					values ('$new_adm_no', ".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_adm_no;
		}
	}

	function selectUser($db, $adm_no) {

		$query = "SELECT ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, ADM_BIRTHDAY, PROFILE, 
										 GROUP_NO, ADM_FLAG, POSITION_CODE, OCCUPATION_CODE, DEPT_CODE, COM_CODE, ENTER_DATE, OUT_DATE, 
										 HEADQUARTERS_CODE, LEADER_YN, LEADER_TITLE, LEVEL, DEPT_UNIT_NAME, COMMUTE_TIME, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_INFO  WHERE ADM_NO = '$adm_no' ";
		//echo $query;
		//exit;
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

//조직도2022에 따른 추가 2021-11-22
	function selectUser2022($db, $adm_no) {

		$query = "SELECT A.ADM_NO, A.ADM_ID, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.ADM_HPHONE, A.ADM_PHONE, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.PROFILE, A.COMMUTE_TIME, 
										 A.GROUP_NO, A.ADM_FLAG, A.COM_CODE, A.ENTER_DATE, A.OUT_DATE, A.OCCUPATION_CODE, 
										 D.HEADQUARTERS_CODE, D.POSITION_CODE, D.DEPT_CODE, 
										 D.LEADER_YN, D.LEADER_TITLE, D.LEVEL, D.DEPT_UNIT_NAME, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE 
								FROM TBL_ADMIN_INFO A
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '2022'
								WHERE A.ADM_NO = '$adm_no' ";

		//echo $query;
		//exit;
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listAdminGroupMenuRight($db, $group_no) {

		$query = "SELECT MENU_CD, GROUP_NO, READ_FLAG, REG_FLAG, UPD_FLAG, DEL_FLAG, FILE_FLAG, TOP_FLAG, MAIN_FLAG, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_MENU_RIGHT WHERE GROUP_NO = '$group_no' ";

		#echo $query;
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function deleteAdminGroupMenuRight($db, $group_no) {
		
		$query="DELETE FROM TBL_ADMIN_MENU_RIGHT WHERE GROUP_NO			= '$group_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertAdminGroupMenuRight($db, $group_no, $menu_cd, $read_chk, $reg_chk, $upd_chk, $del_chk, $file_chk, $top_chk, $main_chk) {
		
		$query="INSERT INTO TBL_ADMIN_MENU_RIGHT (GROUP_NO, MENU_CD, READ_FLAG, REG_FLAG, UPD_FLAG, DEL_FLAG, FILE_FLAG, TOP_FLAG, MAIN_FLAG) 
																		  VALUES ('$group_no', '$menu_cd', '$read_chk', '$reg_chk', '$upd_chk', '$del_chk', '$file_chk' , '$top_chk' , '$main_chk')";
		#echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function listAdminLog($db, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $nListCnt) {
	

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum + 1  as rn, SEQ_NO, USER_TYPE, LOG_ID, LOG_IP, LOGIN_DATE, TASK, TASK_TYPE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_ID = TBL_USER_LOG.LOG_ID) AS ADM_NAME
								FROM TBL_USER_LOG WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND LOGIN_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND LOGIN_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($task_type <> "") {
			$query .= " AND TASK_TYPE = '".$task_type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY SEQ_NO desc limit ".$offset.", ".$nRowCount;

		#echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function totalCntAdminLog ($db, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str) { 

		$query ="SELECT COUNT(*) CNT FROM TBL_USER_LOG WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND LOGIN_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND LOGIN_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($task_type <> "") {
			$query .= " AND TASK_TYPE = '".$task_type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function listAdminHireDate($db, $group_no, $com_code, $dept_code, $position_code, $use_tf, $del_tf) {

		$query = "SELECT A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_FLAG, A.POSITION_CODE, A.OCCUPATION_CODE, A.DEPT_CODE, A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 A.HEADQUARTERS_CODE, A.LEADER_YN, A.LEVEL, A.DEPT_UNIT_NAME, A.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME 
								FROM TBL_ADMIN_INFO A 
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON A.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON A.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1 ";

		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($com_code <> "") {
			$query .= " AND A.COM_CODE = '".$com_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND A.DEPT_CODE = '".$dept_code."' ";
		}

		if ($position_code <> "") {
			$query .= " AND A.POSITION_CODE = '".$position_code."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}
		
		$query .= " ORDER BY A.COM_CODE ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC";

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

	function listAdminHireDateTest($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf) {

		$query = "SELECT  A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_FLAG, 
                     A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 D.POSITION_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, 
										 D.HEADQUARTERS_CODE, D.LEADER_YN, D.LEVEL, D.DEPT_UNIT_NAME, D.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME 
								FROM TBL_ADMIN_INFO A 
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON D.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1";

		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}
		
		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' "; 
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}
		
		$query .= " ORDER BY A.COM_CODE ASC, H.DCODE_SEQ_NO ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC";

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

	function selectOrgYear($db) {
	
		$query = "SELECT DISTINCT(YEAR)
								FROM TBL_ORG WHERE USE_TF = 'Y' AND DEL_TF = 'N'";
		
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

	function selectOrgAddYN($db, $adm_no) {
	
		$query = "SELECT COUNT(*)
								FROM TBL_ORG WHERE YEAR='2022' AND ADM_NO = '".$adm_no."' AND USE_TF = 'Y' AND DEL_TF = 'N'";
		
		//echo $query;
		//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectOrg($db, $adm_no, $yyyy) {

		$query = "SELECT * FROM TBL_ORG  WHERE ADM_NO = '$adm_no' AND YEAR = '".$yyyy."' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertOrgAdd($db, $arr_data) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";

		foreach ($arr_data as $key => $value) {

			$value = str_replace("'","''",$value);

			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'";
			}
		}

		$query = "INSERT INTO TBL_ORG (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrg($db, $arr_data_org, $adm_no, $rd_year) {

		foreach ($arr_data_org as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_ORG SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "ADM_NO = '$adm_no' WHERE ADM_NO = '$adm_no' AND YEAR = '$rd_year'";

		//echo $query;
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteOrg($db, $del_adm, $adm_no, $rd_year) {

		$query="UPDATE TBL_ORG SET 
									 USE_TF				= 'N',
									 DEL_TF				= 'Y',
									 DEL_ADM			= '$del_adm',
									 DEL_DATE			= now()														 
						 WHERE ADM_NO				= '$adm_no' AND YEAR = '$rd_year'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectAdminName($db, $va_user) {

		$query = "SELECT ADM_NAME
								FROM TBL_ADMIN_INFO  WHERE ADM_NO = '$va_user'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$str_result = $rows[0];

		return $str_result;

	}
	
	function selectAdminPosition($db, $adm_no, $year){

		$query = "SELECT POSITION_CODE FROM TBL_ORG WHERE ADM_NO = '$adm_no' AND USE_TF ='Y' AND DEL_TF='N' AND YEAR ='".$year."'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

//leader 찾기
function selectAdminLeader($db, $leader_yn, $headquarters_code, $dept_code, $dept_unit_name, $level, $leader_title, $year){

		$level = $level - 1; //팀장 찾기

		if ($level == 2) {

			if ($headquarters_code == "서비스 운영 2본부") {
				$headquarters_code = "서비스 운영 1본부";
				$dept_code = "기획";
			} 
			if (($headquarters_code == "디지털 구축 1본부") || ($headquarters_code == "디지털 구축 2본부")) {
				$headquarters_code = "디지털 구축 1본부";
				$dept_code = ""; // 이사는 dept_code 공백
			} 
			if ($headquarters_code == "경영기획팀") { //대표이사로 go!
				$headquarters_code = "";
				$dept_code = "";
				$level = 0;
			} 
		}

		if ($level == 1) {
				$headquarters_code = "경영기획팀";
				$dept_code = "재무/회계";
				$level = 3;
		}

		if (($level == 3) && ($headquarters_code == "기업부설연구소")){
				$headquarters_code = "";
				$dept_code = "";
				$level = 0;
		}

		$query = "SELECT A.ADM_NO, A.ADM_NAME, D.HEADQUARTERS_CODE, D.DEPT_CODE, D.POSITION_CODE 
							FROM TBL_ADMIN_INFO A 
									 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '".$year."' 
							WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
							AND D.LEADER_YN = 'Y' 
							AND IFNULL(D.HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
							AND IFNULL(D.DEPT_CODE, '') = '".$dept_code."' 
							AND D.LEVEL = '".$level."'"; 

//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

}

//관리자용 승인위치 모두 출력
	function selectAdminLeaderAll($db, $year){

		$query = "SELECT A.ADM_NO, A.ADM_NAME, D.HEADQUARTERS_CODE, D.DEPT_CODE, D.POSITION_CODE
							FROM TBL_ADMIN_INFO A
									 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '".$year."'
							WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
							AND D.LEADER_YN = 'Y' 
							ORDER BY FIELD(D.POSITION_CODE,'대표이사','이사','수석','책임','선임')";

//echo $query;
//exit;
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	//leader인지 판단
	function selectAdminLeaderYN($db, $adm_no, $year){

		$query  = "SELECT * FROM TBL_ORG 
							 WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND ADM_NO ='".$adm_no."' AND YEAR='".$year."'";


		$result = mysql_query($query, $db);
		$record = mysql_fetch_array($result);

		return $record;
	}

	function selectAdminEmail($db, $seq_no) {
		
		$query = "SELECT ADM_EMAIL FROM TBL_ADMIN_INFO WHERE ADM_NO = (SELECT VA_USER FROM TBL_NEW_VACATION WHERE SEQ_NO = $seq_no)";

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

	function selectAdminVacation($db, $adm_no) { //연차, 스마트데이 알림 함수! 사용자보기용 

		$query = "SELECT VA_TYPE FROM TBL_VACATION WHERE VA_USER = '$adm_no' AND CURDATE() >= VA_SDATE AND CURDATE() <= VA_EDATE AND DEL_TF = 'N'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}
*/
?>