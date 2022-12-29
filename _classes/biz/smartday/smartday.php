<?

	function selectSmartday($db, $seq_no) {

		$query = "SELECT *
								FROM TBL_SMARTDAY WHERE SEQ_NO = '$seq_no' ";
		
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

	function listSmartday($db, $va_type, $va_user) { //, $nPage, $nRowCount

		//$offset = $nRowCount*($nPage-1);

		//$query = "set @rownum = ".$offset ."; ";
		//mysql_query($query,$db);

		//echo $nRowCount;
		//exit;

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, VA_TYPE, VA_SDATE, VA_EDATE, VA_MEMO, VA_USER, VA_STATE, 
										 DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_VACATION.VA_USER) AS ADM_NAME
								FROM TBL_VACATION WHERE DEL_TF = 'N' ";

		//$query = "SELECT * FROM TBL_NEW_VACATION WHERE 1=1 " ;

		if ($va_type <> "") {
			$query .= " AND VA_TYPE = '".$va_type."' ";
		}

		if ($va_user <> "") {
			$query .= " AND VA_USER = '".$va_user."' ";
		}

		$query .= " ORDER BY VA_SDATE desc "; // limit ".$offset.", ".$nRowCount;

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

	function totalCntSmartday($db, $va_type, $va_user){

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

	function listSmartdayDate($db, $va_user, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);

		$query = "set @rownum = ".$offset ."; ";
		mysql_query($query,$db);

		//$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, FROM TBL_VACATION_DATE WHERE SEQ_NO = ".$seq_no;
		$query = "SELECT @rownum:= @rownum - 1  as rn, A.SEQ_NO, A.VA_DATE, B.VA_STATE FROM TBL_VACATION_DATE A, TBL_VACATION B ";
		$query .= "WHERE A.VA_USER = '".$va_user."' AND (B.VA_TYPE = '5' OR B.VA_TYPE = '13') AND A.SEQ_NO = B.SEQ_NO ";
		$query .= " ORDER BY A.VA_DATE desc limit ".$offset.", ".$nRowCount;

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

	function totalCntSmartdayDate($db, $va_type, $va_user){

		$query ="SELECT COUNT(*) CNT FROM TBL_VACATION_DATE WHERE SEQ_NO = (SELECT SEQ_NO FROM TBL_VACATION WHERE SEQ_NO = TBL_VACATION_DATE.SEQ_NO AND (VA_TYPE = '$va_type' OR VA_TYPE = '13')) ";
		
		if ($va_user <> "") {
			$query .= " AND VA_USER = '".$va_user."' ";
		}

		//echo $query."<br>";
		//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertSmartday($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_SMARTDAY (".$set_field.", REG_DATE, UP_DATE) 
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

	function updateSmartday($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_SMARTDAY SET ".$set_query_str." ";
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

	function deleteSmartday($db, $seq_no) {

		$query="UPDATE TBL_SMARTDAY SET 
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

	function deleteSmartdayDate($db, $seq_no) {

		$query="DELETE FROM TBL_SMARTDAY_DATE WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>