<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	function selectNewVacation($db, $seq_no) {

		$query = "SELECT *
								FROM TBL_NEW_VACATION WHERE SEQ_NO = '$seq_no' ";
		
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

	//leader인지 판단
	function selectLeaderYN($db, $s_adm_id){
		$query  = "SELECT * FROM TBL_ADMIN_INFO 
							 WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND ADM_ID ='".$s_adm_id."'";

		$result = mysql_query($query, $db);
		$record = mysql_fetch_array($result);

		return $record;
	}

	//leader id 가져오기
	function selectLeaderId($db, $va_state_pos){
		$query  = "SELECT * FROM TBL_ADMIN_INFO 
							 WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND ADM_NO ='".$va_state_pos."'";

		$result = mysql_query($query, $db);
		$record = mysql_fetch_array($result);

		return $record;
	}

	function selectPositionCode($db, $leader_yn, $headquarters_code, $dept_code, $dept_unit_name, $level, $leader_title){

		if ($headquarters_code == "기업부설연구소") {
			$level = 1;
		}

		if ($headquarters_code == ""){  //경영팀
			if($leader_title == "") {
				$query = "SELECT * FROM TBL_ADMIN_INFO 
									WHERE USE_TF = 'Y' AND DEL_TF = 'N'
									AND IFNULL(HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
									AND IFNULL(DEPT_CODE, '') = '".$dept_code."' 
									AND LEADER_TITLE = '유닛장'";
			} else if ($leader_title == "유닛장"){
				$query = "SELECT * FROM TBL_ADMIN_INFO 
									WHERE USE_TF = 'Y' AND DEL_TF = 'N'
									AND IFNULL(HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
									AND IFNULL(DEPT_CODE, '') = '".$dept_code."' 
									AND LEADER_TITLE = '팀장'";
			} else {
				$query = "SELECT * FROM TBL_ADMIN_INFO 
									WHERE USE_TF = 'Y' AND DEL_TF = 'N'
									AND LEVEL = 0";
			}
		} else { // 경영팀 외

			if ($leader_yn == "N" && $level == "4") {  //유닛은 유닛장 찾기

				$query = "SELECT * FROM TBL_ADMIN_INFO 
									WHERE USE_TF = 'Y' AND DEL_TF = 'N'
									AND IFNULL(HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
									AND IFNULL(DEPT_CODE, '') = '".$dept_code."' 
									AND IFNULL(DEPT_UNIT_NAME, '') = '".$dept_unit_name."' 
									AND LEADER_TITLE = '유닛장'";

			} else {

				$level = $level - 1;

				if ($level >= 3) { //유닛장, 팀장
					$query = "SELECT * FROM TBL_ADMIN_INFO 
										WHERE USE_TF = 'Y' AND DEL_TF = 'N'
										AND IFNULL(HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
										AND IFNULL(DEPT_CODE, '') = '".$dept_code."' 
										AND LEVEL = '".$level."'";

				} else if ($level >= 2) { //본부장, 이사
					$query = "SELECT * FROM TBL_ADMIN_INFO 
										WHERE USE_TF = 'Y' AND DEL_TF = 'N'
										AND IFNULL(HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
										AND LEVEL = '".$level."'";
				} else {
					$query = "SELECT * FROM TBL_ADMIN_INFO 
										WHERE USE_TF = 'Y' AND DEL_TF = 'N'
										AND LEVEL = '".$level."'";
				}
			}
		}

//echo $query."///";
//exit;
		$result = mysql_query($query, $db);
		$record = mysql_fetch_array($result);

		return $record;

	}

function selectLeader2022($db, $leader_yn, $headquarters_code, $dept_code, $dept_unit_name, $level, $leader_title){

		$level = $level - 1; //팀장 찾기

		if ($level == 2) {

			if ($headquarters_code == "서비스 운영 2본부") {
				$headquarters_code = "서비스 운영 1본부";
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
				$level = 3;
		}

		$query = "SELECT A.ADM_NO, A.ADM_NAME, D.HEADQUARTERS_CODE, D.DEPT_CODE, D.POSITION_CODE 
							FROM TBL_ADMIN_INFO A 
									 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '2022' 
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
	function selectPositionCodeAll($db){

		$query = "SELECT * FROM TBL_ADMIN_INFO 
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' 
							AND LEADER_YN = 'Y' 
							ORDER BY FIELD(POSITION_CODE,'대표이사','이사','수석','책임','선임')";

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
	function selectLeaderAll2022($db){

		$query = "SELECT A.ADM_NO, A.ADM_NAME, D.HEADQUARTERS_CODE, D.DEPT_CODE, D.POSITION_CODE
							FROM TBL_ADMIN_INFO A
									 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '2022'
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

	function listNewVacation($db, $va_type, $va_user, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);


		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, VA_TYPE, VA_SDATE, VA_EDATE, VA_MEMO, VA_USER, 
										 DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_NEW_VACATION.VA_USER) AS ADM_NAME
								FROM TBL_NEW_VACATION WHERE DEL_TF = 'N' ";

		if ($va_type <> "") {
			$query .= " AND VA_TYPE = '".$va_type."' ";
		}

		if ($va_user <> "") {
			$query .= " AND VA_USER = '".$va_user."' ";
		}

		$query .= " ORDER BY SEQ_NO desc limit ".$offset.", ".$nRowCount;
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntNewVacation($db, $va_type, $va_user){

		$query ="SELECT COUNT(*) CNT FROM TBL_NEW_VACATION WHERE 1 = 1 ";
		
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

	function insertNewVacation($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_NEW_VACATION (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo $query."<br>"; 
		//exit;

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

	function updateNewVacation($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_NEW_VACATION SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "SEQ_NO = '$seq_no' WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteNewVacation($db, $seq_no) {

		$query="UPDATE TBL_NEW_VACATION SET 
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE SEQ_NO				= '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteNewVacationDate($db, $seq_no) {

		$query="DELETE FROM TBL_NEW_VACATION_DATE WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function insertNewVacationDate($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_NEW_VACATION_DATE (".$set_field.") 
					values (".$set_value."); ";

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} 
	}

	function insertNewVacationCnt($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_NEW_USER_VACATION_CNT (".$set_field.") 
					values (".$set_value."); ";

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} 
	}

	function deleteNewVacationCnt($db, $yyyy) {
		
		$query = "DELETE FROM TBL_NEW_USER_VACATION_CNT WHERE YYYY = '$yyyy' "; 

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} 
	}


	function listNewUserVacationCnt($db, $yyyy, $dept_code, $position_code, $search_field, $search_str) {

		$query = "SELECT A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_FLAG, A.POSITION_CODE, A.DEPT_CODE, A.COM_CODE, A.ENTER_DATE,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME,
										 D.VA_CNT
								FROM TBL_ADMIN_INFO A 
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON A.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON A.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
										 LEFT OUTER JOIN TBL_NEW_USER_VACATION_CNT D ON A.ADM_NO = D.VA_USER AND D.YYYY = '$yyyy'
							 WHERE GROUP_NO = '4'
								 AND A.USE_TF = 'Y' AND A.DEL_TF ='N'";


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

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}


		$query .= " ORDER BY C.DCODE_SEQ_NO ASC, B.DCODE_SEQ_NO ASC ";

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


	function setNewVacationCnt($db, $yyyy, $va_user, $va_cnt, $adm_no) {

		$query = "SELECT COUNT(*) FROM TBL_NEW_USER_VACATION_CNT WHERE YYYY = '$yyyy' AND VA_USER = '$va_user' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if (trim($rows[0]) == 0) {
			$query = "INSERT INTO TBL_NEW_USER_VACATION_CNT (YYYY, VA_USER, VA_CNT, REG_ADM, REG_DATE) VALUES ('$yyyy', '$va_user', '$va_cnt', '$adm_no', now()); ";

			mysql_query($query,$db);

		} else {

			$query = "DELETE FROM TBL_NEW_USER_VACATION_CNT WHERE YYYY = '$yyyy' AND VA_USER = '$va_user' ";
			
			mysql_query($query,$db);

			$query = "INSERT INTO TBL_NEW_USER_VACATION_CNT (YYYY, VA_USER, VA_CNT, REG_ADM, REG_DATE) VALUES ('$yyyy', '$va_user', '$va_cnt', '$adm_no', now()); ";

			mysql_query($query,$db);
		}

	}


	function chkNewVacationDate($db, $va_user, $va_sdate, $va_edate, $seq_no) {
		
		if ($seq_no == "") {
			$query = "SELECT COUNT(*) FROM TBL_NEW_VACATION_DATE WHERE VA_USER = '$va_user' AND VA_DATE >= '$va_sdate' AND VA_DATE <= '$va_edate' ";
		} else {
			$query = "SELECT COUNT(*) FROM TBL_NEW_VACATION_DATE WHERE VA_USER = '$va_user' AND VA_DATE >= '$va_sdate' AND VA_DATE <= '$va_edate' AND SEQ_NO <> '$seq_no' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		//echo $rows[0];

		if ($rows[0] == 0) {
			return false;
		} else {
			return true;
		}
	}

	function getNewVacationDate($db, $date, $insert_flag, $adm_no) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_USER, A.VA_STATE, A.VA_MEMO,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS USER_NAME
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.DEL_TF = 'N'
								 AND B.VA_DATE = '$date'
								 ORDER BY A.SEQ_NO ASC";

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);

			$SEQ_NO				= Trim($row["SEQ_NO"]);
			$VA_TYPE			= Trim($row["VA_TYPE"]);
			$DCODE_NM			= Trim($row["DCODE_NM"]);
			$USER_NAME		= Trim($row["USER_NAME"]);
			$VA_STATE			= Trim($row["VA_STATE"]);
			$STATE_NM			= Trim($row["STATE_NM"]);
			$VA_USER			= Trim($row["VA_USER"]);
			$VA_MEMO			= Trim($row["VA_MEMO"]);

			if (($VA_TYPE == 5) || ($VA_TYPE == 6)) {
				$DCODE_NM = "<font color='orange'>".$DCODE_NM."</font>";
			} else {
				$DCODE_NM = $DCODE_NM;
			}

			if ($VA_STATE == 0) {
				$STATE_NM = "<font color='green'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 1) {
				$STATE_NM = "<font color='#BFBFBF'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 2) {
				$STATE_NM = "<font color='orange'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 3) {
				$STATE_NM = "<font color='red'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 4) {
				$STATE_NM = "<font color='green'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 5) {
				$STATE_NM = "<font color='red'>".$STATE_NM."</font>";
			} else {
				$STATE_NM = $STATE_NM;
			}
			
			$str= $str."<div style='position:relative'>";

			$str= $str."<div id='".$SEQ_NO.$date."' style='position:absolute; background-color: #EFEFEF; border: 1px solid #DEDEDE; padding:5px 5px 5px 5px; top:20px; left:-10px; text-align:left; z-index:1000; display:none;';>";
			$str= $str.nl2br($VA_MEMO)."</div>";

			if ($insert_flag == "Y") {
				$str = $str."[".$STATE_NM."] <b><a href='javascript:js_modify(".$SEQ_NO.")' onmouseover=\"js_layer_show('".$SEQ_NO.$date."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$date."');\">".$USER_NAME."</a></b> (".$DCODE_NM.")<br>"; 
			} else {
				
				if ($VA_USER == $adm_no) {
					$str = $str."[".$STATE_NM."] <b>".$USER_NAME."</b> (".$DCODE_NM.") <br>"; 
					//$str = $str."[".$STATE_NM."] <b><a href='javascript:js_modify(".$SEQ_NO.")'>".$USER_NAME."</a></b> (".$DCODE_NM.") <a href='javascript:js_modify(".$SEQ_NO.")'>수정</a><br>"; 
				} else {
					$str = $str."[".$STATE_NM."] ".$USER_NAME." (".$DCODE_NM.")<br>"; 
				}
				
			}

			$str= $str."</div>";

			//echo $SEQ_NO;
			//echo $DCODE_NM;
			//echo $USER_NAME;
			//echo $VA_STATE;

		}

		return $str;
		
	}

	function getNewVacationDateWithCondition($db, $date, $insert_flag, $adm_no, $dept_code, $va_user) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_USER, A.VA_STATE, A.VA_MEMO,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS USER_NAME
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.DEL_TF = 'N'
								 AND B.VA_DATE = '$date' ";

		if ($dept_code <> "") {
			$query .= " AND C.DEPT_CODE = '".$dept_code."' ";
		}

		if ($va_user <> "") {
			$query .= " AND A.VA_USER = '".$va_user."' ";
		}

		$query .= " ORDER BY A.SEQ_NO ASC";
//echo $qurey;
//exit;
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);

			$SEQ_NO				= Trim($row["SEQ_NO"]);
			$VA_TYPE			= Trim($row["VA_TYPE"]);
			$DCODE_NM			= Trim($row["DCODE_NM"]);
			$USER_NAME		= Trim($row["USER_NAME"]);
			$VA_STATE			= Trim($row["VA_STATE"]);
			$STATE_NM			= Trim($row["STATE_NM"]);
			$VA_USER			= Trim($row["VA_USER"]);
			$VA_MEMO			= Trim($row["VA_MEMO"]);

			if (($VA_TYPE == 5) || ($VA_TYPE == 6)) {
				$DCODE_NM = "<font color='orange'>".$DCODE_NM."</font>";
			} else {
				$DCODE_NM = $DCODE_NM;
			}

			if ($VA_STATE == 0) {
				$STATE_NM = "<font color='green'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 1) {
				$STATE_NM = "<font color='#BFBFBF'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 2) {
				$STATE_NM = "<font color='orange'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 3) {
				$STATE_NM = "<font color='red'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 4) {
				$STATE_NM = "<font color='green'>".$STATE_NM."</font>";
			} else if ($VA_STATE == 5) {
				$STATE_NM = "<font color='red'>".$STATE_NM."</font>";
			} else {
				$STATE_NM = $STATE_NM;
			}
			
			$str= $str."<div style='position:relative'>";

			$str= $str."<div id='".$SEQ_NO.$date."' style='position:absolute; background-color: #EFEFEF; border: 1px solid #DEDEDE; padding:5px 5px 5px 5px; top:20px; left:-10px; text-align:left; z-index:1000; display:none;';>";
			$str= $str.nl2br($VA_MEMO)."</div>";

			if ($insert_flag == "Y") {
				$str = $str."[".$STATE_NM."] <b><a href='javascript:js_modify(".$SEQ_NO.")' onmouseover=\"js_layer_show('".$SEQ_NO.$date."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$date."');\" style='cursor:default;'>".$USER_NAME."</a></b> (".$DCODE_NM.")<br>"; 
			} else {
				
				if ($VA_USER == $adm_no) {
					$str = $str."[".$STATE_NM."] <b><a href='javascript:void();' onmouseover=\"js_layer_show('".$SEQ_NO.$date."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$date."');\" style='cursor:default;'>".$USER_NAME."</a></b> (".$DCODE_NM.") <br>"; 
					//$str = $str."[".$STATE_NM."] <b><a href='javascript:js_modify(".$SEQ_NO.")'>".$USER_NAME."</a></b> (".$DCODE_NM.") <a href='javascript:js_modify(".$SEQ_NO.")'>수정</a><br>"; 
				} else {
					$str = $str."[".$STATE_NM."] <a href='javascript:void();' onmouseover=\"js_layer_show('".$SEQ_NO.$date."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$date."');\" style='cursor:default;'>".$USER_NAME."</a> (".$DCODE_NM.")<br>"; 
				}
				
			}

			$str= $str."</div>";

			//echo $SEQ_NO;
			//echo $DCODE_NM;
			//echo $USER_NAME;
			//echo $VA_STATE;

		}

		return $str;
		
	}

	function listGetNewVacationDateWithCondition($db, $date, $insert_flag, $adm_no, $dept_code, $va_user) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_USER, A.VA_STATE, A.VA_MEMO, 
										(SELECT DCODE FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE_POS' AND DCODE = A.VA_STATE_POS) AS DCODE,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE_POS' AND DCODE = A.VA_STATE_POS) AS VA_STATE_POS,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS USER_NAME,
                    (SELECT POSITION_CODE FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS POSITION_CODE
								FROM TBL_NEW_VACATION A, TBL_NEW_VACATION_DATE B, TBL_ADMIN_INFO C
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND A.DEL_TF = 'N' ";
								// AND B.VA_DATE = '$date' ";

		if ($dept_code <> "") {
			$query .= " AND C.DEPT_CODE = '".$dept_code."' ";
		}

		if ($va_user <> "") {
			$query .= " AND A.VA_USER = '".$va_user."' ";
		}

		$query .= " ORDER BY A.SEQ_NO ASC";

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

	function listNewVacationYear($db) {

		$query = "SELECT DISTINCT YYYY
								FROM TBL_NEW_USER_VACATION_CNT ";

		$query .= " ORDER BY YYYY desc ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function listNewUserVacationYear ($db, $yyyy, $dept_code, $va_user) {
		
		$next_year = $yyyy + 1;

		$query = "SELECT A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_FLAG, A.POSITION_CODE, A.DEPT_CODE, A.COM_CODE, A.ENTER_DATE,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-01%'),0) AS 1M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-02%'),0) AS 2M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-03%'),0) AS 3M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-04%'),0) AS 4M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-05%'),0) AS 5M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-06%'),0) AS 6M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-07%'),0) AS 7M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-08%'),0) AS 8M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-09%'),0) AS 9M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-10%'),0) AS 10M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-11%'),0) AS 11M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-12%'),0) AS 12M,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (4,7) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$next_year."-01%'),0) AS 13M,

										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-01%'),0) AS 1S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-02%'),0) AS 2S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-03%'),0) AS 3S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-04%'),0) AS 4S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-05%'),0) AS 5S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-06%'),0) AS 6S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-07%'),0) AS 7S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-08%'),0) AS 8S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-09%'),0) AS 9S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-10%'),0) AS 10S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-11%'),0) AS 11S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-12%'),0) AS 12S,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (5) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$next_year."-01%'),0) AS 13S,

										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-01%'),0) AS 1R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-02%'),0) AS 2R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-03%'),0) AS 3R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-04%'),0) AS 4R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-05%'),0) AS 5R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-06%'),0) AS 6R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-07%'),0) AS 7R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-08%'),0) AS 8R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-09%'),0) AS 9R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-10%'),0) AS 10R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-11%'),0) AS 11R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-12%'),0) AS 12R,
										 IFNULL((SELECT COUNT(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (10) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$next_year."-01%'),0) AS 13R,

										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-01%'),0) AS 1B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-02%'),0) AS 2B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-03%'),0) AS 3B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-04%'),0) AS 4B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-05%'),0) AS 5B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-06%'),0) AS 6B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-07%'),0) AS 7B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-08%'),0) AS 8B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-09%'),0) AS 9B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-10%'),0) AS 10B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-11%'),0) AS 11B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (1,2,3,11) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."-12%'),0) AS 12B,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_NEW_VACATION_DATE AA, TBL_NEW_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE= '4' AND BB.VA_TYPE IN (4,7) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$next_year."-01%'),0) AS 13B,

										 D.VA_CNT
								FROM TBL_ADMIN_INFO A 
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON A.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON A.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
										 LEFT OUTER JOIN TBL_USER_VACATION_CNT D ON A.ADM_NO = D.VA_USER AND D.YYYY = '$yyyy'
							 WHERE GROUP_NO = '4'
								 AND A.USE_TF = 'Y' AND A.DEL_TF ='N'";


		if ($dept_code <> "") {
			$query .= " AND A.DEPT_CODE = '".$dept_code."' ";
		}

		if ($va_user <> "") {
			$query .= " AND A.ADM_NO = '".$va_user."' ";
		}

		$query .= " ORDER BY B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC ";

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


//vaccine관련
	function listUserVaccineeYear($db, $yyyy, $dept_code, $va_user) {
		
		$next_year = $yyyy + 1;

		$query = "SELECT A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_FLAG, A.POSITION_CODE, A.DEPT_CODE, A.COM_CODE, A.ENTER_DATE,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME,
										 IFNULL((SELECT SUM(AA.VA_CNT) FROM TBL_VACATION_DATE AA, TBL_VACATION BB WHERE AA.SEQ_NO = BB.SEQ_NO AND BB.VA_STATE IN ('1','5') AND BB.VA_TYPE IN (19) AND AA.VA_USER = A.ADM_NO AND AA.VA_DATE LIKE '".$yyyy."'),0),
										 D.VA_CNT
								FROM TBL_ADMIN_INFO A 
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON A.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON A.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
										 LEFT OUTER JOIN TBL_USER_VACATION_CNT D ON A.ADM_NO = D.VA_USER AND D.YYYY = '$yyyy'
							 WHERE GROUP_NO = '4'
								 AND A.USE_TF = 'Y' AND A.DEL_TF ='N'";

		if ($dept_code <> "") {
			$query .= " AND A.DEPT_CODE = '".$dept_code."' ";

		}

		if ($va_user <> "") {
			$query .= " AND A.ADM_NO = '".$va_user."' ";
		}

		$query .= " ORDER BY B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC ";

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


	function listMainVacation($db, $va_type, $yyyy, $va_user) {
		
		$next_year = $yyyy + 1;

		$query = "SELECT VA_TYPE, VA_SDATE, VA_EDATE  
								FROM TBL_NEW_VACATION 
							 WHERE VA_SDATE >= '".$yyyy."-01-01' 
								 AND VA_EDATE < '".$next_year."-02-01' 
								 AND VA_USER = '".$va_user."' AND DEL_TF = 'N' ";
		
		if ($va_type == "1") {
			$query .= " AND VA_TYPE IN ('1','2','3','4','7','11') ";
		}

		if ($va_type == "5") {
			$query .= " AND VA_TYPE IN ('5') ";
		}

		if ($va_type == "6") {
			$query .= " AND VA_TYPE IN ('6','10') ";
		}

		$query .= " ORDER BY VA_SDATE DESC ";

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

//이미지첨부건 2021-09-10
	function updateImg($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_NEW_VACATION SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "SEQ_NO = '$seq_no' WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectImg($db, $seq_no) {

		$query = "SELECT VA_FLAG, VA_IMG 
								FROM TBL_NEW_VACATION WHERE SEQ_NO = '$seq_no' ";
		
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