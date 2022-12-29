<?
	function listApproval2022($db, $adm_no, $state, $nPage, $nRowCount, $search_field, $search_str, $date_start, $date_end, $payway, $sort_dn, $sort_date, $sort_state) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$state_pos = "";

		if (($adm_no == "1") || ($adm_no == "all")){
			$state_pos = ""; //관리자
		} else {
			$state_pos = "AND VA_STATE_POS = '$adm_no'"; //본인 위치의 것만 보기
		}

		if ($state == "") {
			$state = "AND VA_STATE <> '' "; //모든 결재할 문서
		} else {
			$state = "AND VA_STATE = '$state'"; //진행중, 완료, 반려문서의 경우
		}

		$query  = "SELECT @rownum:= @rownum - 1  as rn, EX_NO, EX_TITLE, EX_DATE, EX_MEMO, EX_TOTAL_PRICE, VA_USER, HEADQUARTERS_CODE, DEPT_CODE, VA_STATE, VA_STATE_POS, REG_DATE, EX_DN, EX_PAYLINE, EX_PAYWAY  
						FROM TBL_EXPENSE WHERE 1 = 1 ";
		$query .= " ".$state;
		$query .= " ".$state_pos;

		if ($date_start <> "") {
			if ($date_start <= $date_end) { 
				$query .= " AND EX_DATE >= '".$date_start."' AND EX_DATE <= '".$date_end."' ";
			}
		}

		if ($payway <> "") {
			$query .= " AND EX_PAYWAY = '".$payway."' ";
		}

		if ($search_field <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY ";

		if ($sort_dn <> "") {
			$query .= $sort_dn .", ";
		}

		if ($sort_date <> "") {
			$query .= $sort_date .", ";
		}

		if ($sort_state <> "") {
			$query .= $sort_state .", ";
		}

		$query .= " UP_DATE DESC limit ".$offset.", ".$nRowCount;

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

	function totalCntApproval2022($db, $adm_no, $state, $search_field, $search_str, $date_start, $date_end, $payway){

		$state_pos = "";

		if (($adm_no == "1") || ($adm_no == "all")){
			$state_pos = ""; //관리자
		} else {
			$state_pos = "AND VA_STATE_POS = '$adm_no'"; //본인 위치의 것만 보기
		}

		if ($state == "") {
			$state = "AND VA_STATE <> '' "; //모든 결재할 문서
		} else {
			$state = "AND VA_STATE = '$state'"; //진행중, 완료, 반려문서의 경우
		}

		$query  = "SELECT COUNT(*) CNT FROM TBL_EXPENSE WHERE 1 = 1 ";
		$query .= " ".$state;
		$query .= " ".$state_pos;

		if ($date_start <> "") {
			if ($date_start <= $date_end) { 
				$query .= " AND EX_DATE >= '".$date_start."' AND EX_DATE <= '".$date_end."' ";
			}
		}

		if ($payway <> "") {
			$query .= " AND EX_PAYWAY = '".$payway."' ";
		}

		if ($search_field <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY UP_DATE DESC";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listApproval($db, $adm_no, $state, $search_field, $search_str) {
		
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

		$query  = "SELECT EX_NO, EX_TITLE, EX_DATE, EX_MEMO, EX_TOTAL_PRICE, VA_USER, HEADQUARTERS_CODE, DEPT_CODE, VA_STATE, VA_STATE_POS, REG_DATE 
								FROM TBL_EXPENSE WHERE 1 = 1 ";
		$query .= " ".$state;
		$query .= " ".$state_pos;

		if ($search_field <> "") {
			$query .= " AND HEADQUARTERS_CODE like '%".$search_field."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ADM_NO = ".$search_str;
		}

		$query .= " ORDER BY UP_DATE DESC";

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
 
	function listApprovalIng($db, $adm_no, $state, $dept_code, $order, $search_field, $search_str, $yy, $mm) {   //진행 및 완료된 모든문서
		
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

		//echo ($t."<br>".(($t=="2022-02") || ($t == "2022-03")));

		$query  = "SELECT EX_NO, EX_TITLE, EX_DATE, EX_MEMO, EX_TOTAL_PRICE, VA_USER, HEADQUARTERS_CODE, DEPT_CODE, VA_STATE, VA_STATE_POS, REG_DATE
								FROM TBL_EXPENSE WHERE 1 = 1 ";
		//$query .= "AND VA_STATE <> 0 ";

		if ($dept_code <> "") {
			$query .= " AND DEPT_CODE like '%".$dept_code."%' ";
		}

		if ($search_field <> "") {
			$query .= " AND HEADQUARTERS_CODE like '%".$search_field."%' ";
		}

		if ($search_str <> "") {
			$query .= " AND ADM_NO = ".$search_str;
		}

		if ($t <> "") {
			$query .= " AND LEFT(EX_DATE, 7) IN ('".$t."', '".$t_1."') ";
		}

		if ($order == "a" ) {
			$query .= " ORDER BY FIELD(VA_STATE, 0, 4, 1, 2, 3), UP_DATE DESC";
		} else if ($order == "d") {
			$query .= " ORDER BY EX_DATE, UP_DATE DESC";
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

	function listApprovalMy($db, $adm_no, $state) {
		
		$query = "SELECT EX_NO, EX_TITLE, EX_DATE, EX_MEMO, EX_TOTAL_PRICE, VA_USER, HEADQUARTERS_CODE, DEPT_CODE, VA_STATE, VA_STATE_POS
								FROM TBL_EXPENSE WHERE VA_USER = '$adm_no' ORDER BY EX_DATE DESC, REG_DATE DESC";

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

////////////////////// 2022-10-11 추가 관리자용!!!
	function listApprovalAll($db, $adm_no, $state, $search_field, $search_str) {  
		
		$state_pos = "";
/*
		if (($state == "1") || ($state == "2") || ($state == "3") || ($state == "4")) { //완료, 보류, 반려는 모두 보이게
			$state_pos = "";
		}

		if ($state == "") {
			$state = "AND VA_STATE <> 1 AND VA_STATE <> 2 AND VA_STATE <> 3 "; //승인이 아닌 모든 결재할 문서
		} else {
			$state = "AND VA_STATE = '$state'"; //진행중, 완료, 보류, 반려문서의 경우
		}
*/

		$query  = "SELECT EX_NO, EX_TITLE, EX_DATE, EX_MEMO, EX_TOTAL_PRICE, VA_USER, HEADQUARTERS_CODE, DEPT_CODE, VA_STATE, VA_STATE_POS, REG_DATE 
								FROM TBL_EXPENSE WHERE 1 = 1 ";
		$query .= " ".$state_pos;

		if ($search_str <> "") {
			$query .= " AND ADM_NO = ".$search_str;
		}

		$query .= " ORDER BY UP_DATE DESC";

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

	function listApprovalDate($db, $ex_no) {
		
		$query = "SELECT EXD_NO, EX_NO, EXD_DATE, EXD_TYPE, EXD_CONTENT, EXD_PROJECT, EXD_PRICE, EXD_MEMO 
								FROM TBL_EXPENSE_DATE WHERE EX_NO = '$ex_no' ORDER BY EXD_DATE";

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
	
	function updateApproval($db, $arr_data, $ex_no) {
		
		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}
		
		$query = "UPDATE TBL_EXPENSE SET ".$set_query_str."  ";
		$query .= "UP_DATE = now() WHERE EX_NO = '$ex_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

	function updateApprovalAll($db, $chk, $va_state_pos, $s_adm_no) {
	
		if ($s_adm_no == "25"){ // 대표이사일 경우만 승인
			$va_state = "1";
		} else {
			$va_state = "4";
		}

		for ($i=0; $i < sizeof($chk) ; $i++){

			$ex_log = "";

			$arr_rs_ex_log = selectApproval($db, $chk[$i]);
			
			$ex_log = $arr_rs_ex_log."//".$s_adm_no."/".$va_state."/".date("Y-m-d H:i");

			$query = "UPDATE TBL_EXPENSE SET VA_STATE = '$va_state', ";
			$query .= "VA_STATE_POS = '$va_state_pos[$i]', EX_LOG = '$ex_log', ";
			$query .= "UP_DATE = now() WHERE EX_NO = '$chk[$i]' ";

//echo $query;
//exit;

			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			}	
		}
			return true;

	}

	function updateApprovalDone($db, $chk, $va_state_pos, $s_adm_no) {
	
		$va_state = "9";

		for ($i=0; $i < sizeof($chk) ; $i++){

			$ex_log = "";

			$arr_rs_ex_log = selectApproval($db, $chk[$i]);
			
			$ex_log = $arr_rs_ex_log."//".$s_adm_no."/".$va_state."/".date("Y-m-d H:i");

			$query = "UPDATE TBL_EXPENSE SET VA_STATE = '$va_state', ";
			$query .= "EX_LOG = '$ex_log', ";
			$query .= "UP_DATE = now() WHERE EX_NO = '$chk[$i]' ";

//echo $query;
//exit;

			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			}	
		}
			return true;

	}

	function selectApproval($db, $ex_no) {

		$query  = "SELECT EX_LOG FROM TBL_EXPENSE WHERE EX_NO = ".$ex_no;

//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	
	}

	function selectApprovalVacationCountMobile($db, $va_user) {

		$query ="SELECT COUNT(*) CNT FROM TBL_VACATION WHERE (VA_STATE = '0' OR VA_STATE = '4') AND DEL_TF = 'N' ";

		if ($va_user <> "") {
			$query .= " AND VA_STATE_POS = '".$va_user."'";
		}

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectApprovalExpenseCountMobile($db, $va_user) {

		$query ="SELECT COUNT(*) CNT FROM TBL_EXPENSE WHERE (VA_STATE = '0' OR VA_STATE = '4') AND DEL_TF = 'N' ";

		if ($va_user <> "") {
			$query .= " AND VA_STATE_POS = '".$va_user."' ";
		}

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectUserApprovalVacationCountMobile($db, $va_user) {

		$query ="SELECT COUNT(*) CNT FROM TBL_VACATION WHERE (VA_STATE = '0' OR VA_STATE = '4') AND DEL_TF = 'N' ";

		if ($va_user <> "") {
			$query .= " AND VA_USER = '".$va_user."' ";
		}

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectUserApprovalExpenseCountMobile($db, $va_user) {

		$query ="SELECT COUNT(*) CNT FROM TBL_EXPENSE WHERE (VA_STATE = '0' OR VA_STATE = '4') AND DEL_TF = 'N' ";

		if ($va_user <> "") {
			$query .= " AND VA_USER = '".$va_user."' ";
		}

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

?>