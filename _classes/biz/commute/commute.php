<?

	function selectCommute($db, $va_user) {

		$va_date = date("Y-m-d");

		$query = "SELECT COM_STATE 
								FROM TBL_COMMUTE WHERE VA_USER = '$va_user' AND VA_DATE = '$va_date'";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$new_seq_no  = $rows[0];

		return $new_seq_no;

	}

	function selectCommuteInfo($db, $va_user, $va_date) {

		$query = "SELECT *
								FROM TBL_COMMUTE WHERE VA_USER = '$va_user' AND VA_DATE = '$va_date'";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function selectCommuteStime($db, $va_user) {

		$va_date = date("Y-m-d");

		$query = "SELECT COM_STIME
								FROM TBL_COMMUTE WHERE VA_USER = '$va_user' AND VA_DATE = '$va_date'";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$new_seq_no  = $rows[0];

		return $new_seq_no;

	}

	function insertCommute($db, $arr_data) {
		
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
				if ($key == "M_PASSWORD") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_COMMUTE (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";


		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {

			$query = "SELECT last_insert_id()";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$new_seq_no  = $rows[0];
			return $new_seq_no;

		}
	}

	function updateCommute($db, $arr_data, $va_user) {

		$va_date = date("Y-m-d");

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_COMMUTE SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "VA_USER = '$va_user' WHERE VA_USER = '$va_user' AND VA_DATE = '$va_date'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateCommuteMemo($db, $va_user, $va_date, $com_memo) {

		$query = "UPDATE TBL_COMMUTE SET COM_MEMO = '$com_memo', ";
		$query .= "UP_DATE = now(), ";
		$query .= "VA_USER = '$va_user' WHERE VA_USER = '$va_user' AND VA_DATE = '$va_date' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateCommuteTask($db, $va_user, $va_date, $com_task) {

		$query = "UPDATE TBL_COMMUTE SET COM_TASK = '$com_task', ";
		$query .= "UP_DATE = now(), ";
		$query .= "VA_USER = '$va_user' WHERE VA_USER = '$va_user' AND VA_DATE = '$va_date' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listCommute($db, $va_date, $va_user, $year, $headquarters_code, $dept_code) {

/*
		$offset = $nRowCount*($nPage-1);
		$query = "set @rownum = ".$offset ."; ";
		mysql_query($query,$db);
*/

/*
		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, VA_TYPE, VA_SDATE, VA_EDATE, VA_MEMO, VA_USER, VA_STATE, 
										 DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_NEW_VACATION.VA_USER) AS ADM_NAME, 
										 (SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_NEW_VACATION.VA_USER) AS DEPT_CODE,
										 (SELECT COM_STATE FROM TBL_COMMUTE WHERE ADM_NO = TBL_NEW_VACATION_DATE.VA_USER AND VA_DATE =TBL_NEW_VACATION_DATE.VA_DATE) AS COM_STATE 
								FROM TBL_NEW_VACATION WHERE DEL_TF = 'N' ";

		$query = "SELECT B.VA_DATE, A.VA_USER, A.VA_STATE,
							(SELECT COM_STATE FROM TBL_COMMUTE WHERE VA_USER = A.VA_USER AND VA_DATE = B.VA_DATE) AS COM_STATE, 
							(SELECT COM_STIME FROM TBL_COMMUTE WHERE VA_USER = A.VA_USER AND VA_DATE = B.VA_DATE) AS COM_STIME,
							(SELECT COM_ETIME FROM TBL_COMMUTE WHERE VA_USER = A.VA_USER AND VA_DATE = B.VA_DATE) AS COM_ETIME,
							(SELECT COM_MEMO FROM TBL_COMMUTE WHERE VA_USER = A.VA_USER AND VA_DATE = B.VA_DATE) AS COM_MEMO,
							(SELECT COM_TASK FROM TBL_COMMUTE WHERE VA_USER = A.VA_USER AND VA_DATE = B.VA_DATE) AS COM_TASK,
							(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS ADM_NAME,
							(SELECT HEADQUARTERS_CODE FROM TBL_ORG WHERE ADM_NO = A.VA_USER AND YEAR = '".$year."') AS HEADQUARTERS_CODE,
							(SELECT DEPT_CODE FROM TBL_ORG WHERE ADM_NO = A.VA_USER AND YEAR = '".$year."') AS DEPT_CODE,
							(SELECT POSITION_CODE FROM TBL_ORG WHERE ADM_NO = A.VA_USER AND YEAR = '".$year."') AS POSITION_CODE,
							(SELECT COMMUTE_TIME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS COMMUTE_TIME
							FROM TBL_VACATION A, TBL_VACATION_DATE B 
							WHERE A.SEQ_NO = B.SEQ_NO AND A.VA_TYPE = '5' ";
*/

		$query = "SELECT B.VA_DATE, A.VA_USER, A.VA_STATE, C.COM_STATE, C.COM_STIME, C.COM_ETIME, C.COM_MEMO, C.COM_TASK, I.ADM_NAME, O.HEADQUARTERS_CODE, O.DEPT_CODE, O.POSITION_CODE, I.COMMUTE_TIME
								FROM TBL_VACATION A
										 LEFT OUTER JOIN TBL_VACATION_DATE B ON A.SEQ_NO = B.SEQ_NO AND B.VA_USER = A.VA_USER
										 LEFT OUTER JOIN TBL_COMMUTE C ON A.VA_USER = C.VA_USER AND B.VA_DATE = C.VA_DATE
										 LEFT OUTER JOIN TBL_ADMIN_INFO I ON I.ADM_NO = A.VA_USER 
										 LEFT OUTER JOIN TBL_ORG O ON O.ADM_NO = A.VA_USER AND O.YEAR = '".$year."'
							 WHERE A.SEQ_NO = B.SEQ_NO AND (A.VA_TYPE = '5' OR A.VA_TYPE = '13') ";

		$query .= " AND B.VA_DATE = '".$va_date."' ";


		if ($va_user <> "") {
			$query .= " AND B.VA_USER = '".$va_user."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND O.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}


//		$query .= " ORDER BY B.VA_DATE desc  limit ".$offset.", ".$nRowCount;

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

	function listAllCommute($db, $va_date, $va_user) {

/*
		$offset = $nRowCount*($nPage-1);
		$query = "set @rownum = ".$offset ."; ";
		mysql_query($query,$db);
*/

/*
		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, VA_TYPE, VA_SDATE, VA_EDATE, VA_MEMO, VA_USER, VA_STATE, 
										 DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_NEW_VACATION.VA_USER) AS ADM_NAME, 
										 (SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_NEW_VACATION.VA_USER) AS DEPT_CODE,
										 (SELECT COM_STATE FROM TBL_COMMUTE WHERE ADM_NO = TBL_NEW_VACATION_DATE.VA_USER AND VA_DATE =TBL_NEW_VACATION_DATE.VA_DATE) AS COM_STATE 
								FROM TBL_NEW_VACATION WHERE DEL_TF = 'N' ";
*/
		$query = "SELECT A.ADM_NAME, A.DEPT_CODE, A.COMMUTE_TIME, B.COM_STIME, B.COM_ETIME
							FROM TBL_ADMIN_INFO A, TBL_COMMUTE B 
							WHERE A.DEL_TF = 'N' AND A.USE_TF = 'Y' ";

		$query .= " AND B.VA_DATE = '".$va_date."' ";

		if ($va_user <> "") {
//			$query .= " AND B.VA_USER = '".$va_user."' ";
		}

//		$query .= " ORDER BY B.VA_DATE desc  limit ".$offset.", ".$nRowCount;

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

	function totalCntCommute($db, $va_type, $va_user){

		$query ="SELECT COUNT(*) CNT FROM TBL_VACATION WHERE 1 = 1 ";
		
		if ($va_type <> "") {
			$query .= " AND VA_TYPE = '".$va_type."' ";
		}

		if ($va_user <> "") {
			$query .= " AND VA_USER = '".$va_user."' ";
		}

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


//출퇴근 현황
	function viewCommuteState($db, $commute_time) {

		$query = "SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'COMMUTE_TIME' AND DCODE = '$commute_time' ";
//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		return $record;
	}

//승인상태
	function viewVaState($db, $va_state) {

		$query = "SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = '$va_state' ";
//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		return $record;
	}

	function listAdmCommute($db){      /////////////////////////////////////////////개인 출결을 한번 만들어 볼까요???????????
		$query ="SELECT * FROM TBL_COMMUTE WHERE VA_USER = 96";
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

?>