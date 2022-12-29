<?

	function listEas($db, $adm_no, $state) {
		
		$str = "";

		if ($adm_no == "1"){
			$state_pos = "";
		} else {
			$state_pos = "AND A.VA_STATE_POS = '$adm_no'";
		}

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
					 					(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.DEL_TF = 'N'
								 AND ( A.VA_STATE = 0 OR A.VA_STATE = 4 ) ";
		$query = $query." ".$state_pos;
		$query = $query." ORDER BY B.VA_DATE ";

	//echo 	$query;

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	
	}

	function listEasIng($db, $adm_no, $state, $level, $leader_yn, $headquarters_code, $dept_code) {

		if ($leader_yn <> "Y") {

			$query = "SELECT ADM_NO FROM TBL_ADMIN_INFO 
								 WHERE DEPT_CODE = (SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = '$adm_no')";

			$result = mysql_query($query,$db);
			$record = array();
			$str = "";
			if ($result <> "") {
				for($i=0;$i < mysql_num_rows($result);$i++) {
					$record[$i] = sql_result_array($result,$i);
					$str = $str.implode(",",$record[$i]).",";
				}
			}
			$str = substr($str,0,strlen($str)-1); 

			$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
											(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
											(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
											(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
											(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
											(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
											(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_STATE_POS) AS VA_STATE_POS_NAME,
									FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
								 WHERE A.SEQ_NO = B.SEQ_NO
									 AND A.VA_USER = C.ADM_NO
									 AND A.DEL_TF = 'N'
									 AND A.VA_STATE = '$state'
									 AND A.VA_USER IN ($str) 
							ORDER BY B.VA_DATE ";

		} else {

			if ($level > 0) {

				if ($level <= 2 ) { //이사이상
					$query		 = "SELECT ADM_NO FROM TBL_ADMIN_INFO WHERE LEVEL = '$level' AND LEADER_YN = 'Y' AND IFNULL(HEADQUARTERS_CODE, '') = '$headquarters_code'";
				} else {
					$query		 = "SELECT ADM_NO FROM TBL_ADMIN_INFO WHERE LEVEL = '$level' AND LEADER_YN = 'Y' AND IFNULL(HEADQUARTERS_CODE, '') = '$headquarters_code'
												AND DEPT_CODE ='$dept_code'";
				}

				$result		 = mysql_query($query, $db);
				$record		 = sql_result_array($result,$i);
		
				$leader_no = $record["ADM_NO"];

				$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE, 
												(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
												(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
												(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
												(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
												(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
												(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_STATE_POS) AS VA_STATE_POS_NAME,
										FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C
									 WHERE A.SEQ_NO = B.SEQ_NO
										 AND A.VA_USER = C.ADM_NO
										 AND A.DEL_TF = 'N'
										 AND A.VA_STATE <> '1'
										 AND A.VA_STATE <> '3'
										 AND A.VA_STATE_POS = '$leader_no'
								ORDER BY B.VA_DATE ";
			}  //결재권한 있는 레벨은 state가 1(승인)이 아닌건 다 불러오기

		}			

//echo $query;

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	
	}


	function listEasDone($db, $adm_no, $state) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
										(SELECT HEADQUARTERS_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS H_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.DEL_TF = 'N'
								 AND A.VA_STATE = '$state'
						ORDER BY B.VA_DATE ";

	//echo 	$query;

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	
	}

	function listEasMy($db, $adm_no) {

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
										(SELECT HEADQUARTERS_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS H_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = '$adm_no'
								 AND A.DEL_TF = 'N'
						ORDER BY B.VA_DATE ";

	//echo 	$query;

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	
	}

	function listEasMyIng($db, $adm_no) {

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
										(SELECT HEADQUARTERS_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS H_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = '$adm_no'
								 AND A.DEL_TF = 'N'
								 AND A.VA_STATE <> '1'
								 AND A.VA_STATE <> '3'
						ORDER BY B.VA_DATE ";
/*
		$query = "SELECT SEQ_NO, VA_TYPE, VA_SDATE, VA_EDATE, VA_MEMO, VA_USER, VA_STATE, VA_STATE_POS, REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = '$adm_no') AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = '$adm_no') AS DEPT_CODE,
					 					(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = '$adm_no') AS POSITION_CODE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = VA_STATE_POS) AS VA_STATE_POS_NAME,
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = VA_STATE_POS) AS VA_STATE_POS_POSITION_CODE
								FROM TBL_NEW_VACATION
							 WHERE VA_USER = '$adm_no'
								 AND DEL_TF = 'N'
								 AND VA_STATE <> '1'
								 AND VA_STATE <> '3'
						ORDER BY VA_SDATE ";
*/
	//echo 	$query;

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	
	}

	function updateEas($db, $arr_data, $seq_no) {
		
		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_NEW_VACATION SET ".$set_query_str."  ";
		$query .= "UP_DATE = now() WHERE SEQ_NO = '$seq_no' ";

	//echo $query."<br>";
	//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return $query;
		}

	}

	function updateEasAll($db, $chk, $va_state_pos, $level) {
	
		if ($level == "0"){
			$va_state = "1";
		} else {
			$va_state = "4";
		}

		for ($i=0; $i < sizeof($chk) ; $i++){
			$query = "UPDATE TBL_NEW_VACATION SET VA_STATE = '$va_state', ";
			$query .= "VA_STATE_POS = '$va_state_pos', ";
			$query .= "UP_DATE = now() WHERE SEQ_NO = '$chk[$i]' ";
			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			}	
		}
			return true;

	}

	function selectPoseName($db, $va_state_pose) {
		
		$query = "SELECT * FROM TBL_ADMIN_INFO WHERE ADM_NO = '$va_state_pose'";

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