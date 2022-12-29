<?

	function listEas($db, $adm_no, $state) {
		
		$str = "";

		if (($adm_no == "1") || ($adm_no == "178")|| ($adm_no == "4")){
			$state_pos = "";
		} else {
			$state_pos = "AND A.VA_STATE_POS = '$adm_no'";
		}

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, A.VA_SDATE, A.VA_EDATE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
					 					(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.VA_SDATE = B.VA_DATE
								 AND A.DEL_TF = 'N'
								 AND ( A.VA_STATE = 0 OR A.VA_STATE = 4 OR A.VA_STATE = 5 ) ";
		$query .= " ".$state_pos;
		$query .= " ORDER BY B.VA_DATE";

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

	function listEasIng($db, $adm_no, $state, $level, $leader_yn, $headquarters_code, $dept_code, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);		

		if ($level <= 2){
			$state_pos = "";
		} else {
			$state_pos = "AND C.DEPT_CODE = '$dept_code'";
		}

		$query = "SELECT @rownum:= @rownum + 1  as rn, A.SEQ_NO, A.VA_TYPE, A.VA_SDATE, A.VA_EDATE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE, 
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_STATE_POS) AS VA_STATE_POS_NAME,
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = VA_STATE_POS) AS VA_STATE_POS_POSITION_CODE,
										(SELECT HEADQUARTERS_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS H_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.VA_SDATE = B.VA_DATE
								 AND A.DEL_TF = 'N'
								 AND A.VA_STATE <> '1'
								 AND A.VA_STATE <> '2'
								 AND A.VA_STATE <> '3'";

		$query .= " ".$state_pos;


		if ($search_field <> "") {
			$query .= " AND C.DEPT_CODE like '%".$search_field."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND C.ADM_NO = ".$search_str;
		}

		$query .= " ORDER BY A.REG_DATE DESC, B.VA_DATE ASC limit ".$offset.", ".$nRowCount;

//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		$record = array();

		if ($result <> "") {
			for($i = 0 ; $i < mysql_num_rows($result) ; $i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	
	}

	function totalCntEasIng($db, $adm_no, $state, $level, $dept_code, $search_field, $search_str) {

		$str = "";

		if ($level <= 2 ){
			$state_pos = "";
		} else {
			$state_pos = "AND C.DEPT_CODE = '$dept_code'";
		}

			$query = "SELECT COUNT(*) CNT
									FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
								 WHERE A.SEQ_NO = B.SEQ_NO
									 AND A.VA_USER = C.ADM_NO
									 AND A.VA_SDATE = B.VA_DATE
									 AND A.DEL_TF = 'N'
									 AND A.VA_STATE <> '1'
									 AND A.VA_STATE <> '2'
									 AND A.VA_STATE <> '3'";

			$query .= " ".$state_pos;

		if ($search_field <> "") {
			$query .= " AND C.DEPT_CODE like '%".$search_field."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND C.ADM_NO = ".$search_str;
		}
//echo $query;
//exit;
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		return $record;

	}

	function listEasDone($db, $adm_no, $state, $level, $dept_code, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);		

		$str = "";

		if ($level <= 2 ){
			$state_pos = "";
		} else {
			$state_pos = "AND C.DEPT_CODE = '$dept_code'";
		}

		$query = "SELECT @rownum:= @rownum + 1  as rn, A.SEQ_NO, A.VA_TYPE, A.VA_SDATE, A.VA_EDATE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
										(SELECT HEADQUARTERS_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS H_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.VA_SDATE = B.VA_DATE
								 AND A.DEL_TF = 'N'
								 AND A.VA_STATE = '$state'";
		$query .= " ".$state_pos;

		if ($search_field <> "") {
			$query .= " AND C.DEPT_CODE like '%".$search_field."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND C.ADM_NO = ".$search_str;
		}

			$query .= " ORDER BY B.VA_DATE DESC limit ".$offset.", ".$nRowCount;


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

	function totalCntEasDone($db, $adm_no, $state, $level, $dept_code, $search_field, $search_str) {

		$str = "";

		if ($level <= 2 ){
			$state_pos = "";
		} else {
			$state_pos = "AND C.DEPT_CODE = '$dept_code'";
		}

			$query = "SELECT COUNT(*) CNT
									FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
								 WHERE A.SEQ_NO = B.SEQ_NO
									 AND A.VA_USER = C.ADM_NO
									 AND A.VA_SDATE = B.VA_DATE
									 AND A.DEL_TF = 'N'
									 AND A.VA_STATE = '$state'";
			$query .= " ".$state_pos;

		if ($search_field <> "") {
			$query .= " AND C.DEPT_CODE like '%".$search_field."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND C.ADM_NO = ".$search_str;
		}

//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function listEasMy($db, $adm_no, $state, $level, $dept_code, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);		

		$str = "";

		if ($level <= 2 ){
			$state_pos = "";
		} else {
			$state_pos = "AND C.DEPT_CODE = '$dept_code'";
		}

		$query = "SELECT @rownum:= @rownum + 1 as rn, A.SEQ_NO, A.VA_TYPE, A.VA_SDATE, A.VA_EDATE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_STATE_POS) AS VA_STATE_POS_NAME,
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = VA_STATE_POS) AS VA_STATE_POS_POSITION_CODE,
										(SELECT HEADQUARTERS_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS H_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.VA_USER = '$adm_no'
								 AND A.VA_SDATE = B.VA_DATE
								 AND A.DEL_TF = 'N'";
		$query .= " ".$state_pos;

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

			$query .= " ORDER BY B.VA_DATE DESC limit ".$offset.", ".$nRowCount;

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

	function totalCntEasMy($db, $adm_no, $state, $level, $dept_code, $search_field, $search_str) {

		$str = "";

		if ($level <= 2 ){
			$state_pos = "";
		} else {
			$state_pos = "AND C.DEPT_CODE = '$dept_code'";
		}

			$query = "SELECT COUNT(*) CNT
									FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
								 WHERE A.SEQ_NO = B.SEQ_NO
									 AND A.VA_USER = C.ADM_NO
									 AND A.VA_USER = '$adm_no'
									 AND A.VA_SDATE = B.VA_DATE
									 AND A.DEL_TF = 'N'";
			$query = $query." ".$state_pos;

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function listEasMyIng($db, $adm_no) {

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, A.VA_SDATE, A.VA_EDATE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_STATE_POS) AS VA_STATE_POS_NAME,
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = VA_STATE_POS) AS VA_STATE_POS_POSITION_CODE,
										(SELECT HEADQUARTERS_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS H_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = '$adm_no'
								 AND A.VA_SDATE = B.VA_DATE
								 AND A.DEL_TF = 'N'
								 AND A.VA_STATE <> '1'
								 AND A.VA_STATE <> '3'
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

	function listEasBack($db, $adm_no, $state) {

		$str = "";

		if ($level <= 2 ){
			$state_pos = "";
		} else {
			$state_pos = "AND C.DEPT_CODE = '$dept_code'";
		}

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, A.VA_SDATE, A.VA_EDATE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_STATE_POS) AS VA_STATE_POS_NAME,
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = VA_STATE_POS) AS VA_STATE_POS_POSITION_CODE,
										(SELECT HEADQUARTERS_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS H_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.VA_SDATE = B.VA_DATE
								 AND A.DEL_TF = 'N'
								 AND A.VA_STATE = '$state'";
			$query = $query." ".$state_pos;
			$query .= " ORDER BY B.VA_DATE ";

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

	function listEasSearch($db, $adm_no, $state, $search_field, $search_str ) {
		
		$str = "";

		if (($adm_no == "1") || ($adm_no == "178") || ($adm_no == "4")){
			$state_pos = "";
		} else {
			$state_pos = "AND A.VA_STATE_POS = '$adm_no'";
		}

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, A.VA_SDATE, A.VA_EDATE, B.VA_DATE, A.VA_MEMO, A.VA_USER, A.VA_STATE, A.VA_STATE_POS, A.REG_DATE,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS USER_NAME,
										(SELECT DEPT_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = A.VA_USER) AS DEPT_CODE,
					 					(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,  
										(SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.VA_SDATE = B.VA_DATE
								 AND A.DEL_TF = 'N'
								 AND ( A.VA_STATE = 0 OR A.VA_STATE = 4 OR A.VA_STATE = 5 ) ";
		$query .= " ".$state_pos;

		if ($search_field <> "") {
			$query .= " AND C.DEPT_CODE like '%".$search_field."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND C.ADM_NO = ".$search_str;
		}

		$query .= " ORDER BY B.VA_DATE";

//	echo 	$query;

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

?>