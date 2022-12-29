<?

	function listVacationApproval($db, $adm_no, $state, $search_field, $search_str) {
		
		$state_pos = "";

		if (($adm_no == "1") || ($adm_no == "178") || ($adm_no == "4") || ($adm_no == "43") || ($adm_no == "44")){
			$state_pos = ""; //관리자
		} else {
			$state_pos = "AND VA_STATE_POS = '$adm_no'"; //본인 위치의 것만 보기
		}

		if (($state == "1") || ($state == "2") || ($state == "3") || ($state == "4")) { //완료, 보류, 반려는 모두 보이게
			$state_pos = "";
		}

		if ($state == "") {
			$state = "AND VA_STATE <> 1 AND VA_STATE <> 2 AND VA_STATE <> 3 "; //승인이 아닌 모든 결재할 문서
		} else {
			$state = "AND VA_STATE = '$state'"; //진행중, 완료, 보류, 반려문서의 경우
		}

		$query  = "SELECT *
								FROM TBL_VACATION WHERE 1 = 1 AND DEL_TF = 'N' ";  //TABLE변경
		$query .= " ".$state;
		$query .= " ".$state_pos;

/*
		if ($search_field <> "") {
			$query .= " AND HEADQUARTERS_CODE = '".$search_field."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ADM_NO = ".$search_str;
		}
*/
		$query .= " ORDER BY SEQ_NO DESC";

//echo $query;
//exit;

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

	function listVacationApprovalIng($db, $adm_no, $state, $dept_code, $order, $search_field, $search_str, $yy, $mm) {   //진행 및 완료된 모든문서
		
		if ($mm == 1) {
			$mm_1 = 12;
			$yy_1 = $yy-1;
		} else {
			$mm_1 = $mm-1;
			$yy_1 = $yy;
		}

		if ($mm < 10) $mm = "0".$mm;
		if ($mm_1 < 10) $mm_1 = "0".$mm_1;
		$t = $yy."-".$mm;
		$t_1 = $yy_1."-".$mm_1;

		$query  = "SELECT *
								FROM TBL_VACATION WHERE 1 = 1 AND DEL_TF = 'N' AND VA_STATE <> '1' ";

		if ($t <> "") {
			$query .= " AND (( LEFT(VA_SDATE, 7) IN ('".$t."', '".$t_1."')) OR ((VA_MDATE LIKE '%".$t."%') OR (VA_MDATE LIKE '%".$t_1."%'))) ";
		}

		if ($order == "a" ) {
			$query .= " ORDER BY FIELD(VA_STATE, 0, 4, 2, 3), UP_DATE DESC";
		} else if ($order == "d") {
			$query .= " ORDER BY VA_SDATE, VA_MDATE, UP_DATE DESC";
		} else {
			$query .= " ORDER BY UP_DATE DESC";
		}

//echo $query;
//exit;

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


	function listVacationApprovalMy($db, $adm_no, $state, $dept_code, $order, $search_field, $search_str, $yy, $mm) {   //진행 및 완료된 모든문서

		if ($mm == 1) {
			$mm_1 = 12;
			$yy_1 = $yy-1;
		} else {
			$mm_1 = $mm-1;
			$yy_1 = $yy;
		}

		if ($mm < 10) $mm = "0".$mm;
		if ($mm_1 < 10) $mm_1 = "0".$mm_1;
		$t = $yy."-".$mm;
		$t_1 = $yy_1."-".$mm_1;

		$query = "SELECT * FROM TBL_VACATION WHERE DEL_TF = 'N' AND VA_USER = '$adm_no' ";
		$query .= " AND (( LEFT(VA_SDATE, 7) IN ('".$t."', '".$t_1."')) OR ((VA_MDATE LIKE '%".$t."%') OR (VA_MDATE LIKE '%".$t_1."%'))) ";
		$query .= " ORDER BY VA_SDATE DESC, VA_MDATE DESC, REG_DATE DESC ";

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

	function listVacationApprovalDate($db, $seq_no) {
		
		$query = "SELECT * 
								FROM TBL_VACATION_DATE WHERE SEQ_NO = '$seq_no' ORDER BY VA_DATE";  ////////////////////TABLE변경

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
	
	function updateVacationApproval($db, $arr_data, $seq_no) {
		
		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}
		
		$query = "UPDATE TBL_VACATION SET ".$set_query_str."  ";
		$query .= "UP_DATE = now() WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return $query;
		}

	}

	function updateVacationApprovalAll($db, $chk, $va_state_pos, $s_adm_no) {
	
		if ($s_adm_no == "25"){ // 대표이사일 경우만 승인
			$va_state = "1";
		} else {
			$va_state = "4";
		}

		for ($i=0; $i < sizeof($chk) ; $i++){
			$query = "UPDATE TBL_VACATION SET VA_STATE = '$va_state', ";
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

	function selectVacationApproval($db, $seq_no) {

		$query  = "SELECT VA_LOG FROM TBL_VACATION WHERE SEQ_NO = ".$seq_no;

//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	
	}

?>