<?

	function getVacationUserDateWithCondition($db, $date, $insert_flag, $adm_no, $dept_code, $va_user) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_USER, A.VA_STATE, A.VA_MEMO,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS USER_NAME
								FROM TBL_VACATION A, TBL_VACATION_DATE B, TBL_ADMIN_INFO C
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
//echo $query;
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$flag_smaart_day = false;
		$flag_vacation_day = false;

		for($i=0 ; $i< $total ; $i++) {

			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);
			
			$VA_TYPE			= Trim($row["VA_TYPE"]);

			//if (($VA_TYPE == 5) || ($VA_TYPE == 6)) {
			if ($VA_TYPE == 5) {
				$flag_smaart_day  = true;
			} else {
				$flag_vacation_day  = true;
			}
		}

		if ($flag_smaart_day == true) {

			$str= $str."<br><font color='orange'>스마트데이</font><br>";

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
				
				//if (($VA_TYPE == 5) || ($VA_TYPE == 6)) {
				if ($VA_TYPE == 5) {
					$str= $str." ".$USER_NAME." ";
				}
			}
			$str= $str."<br>";
		}


		if ($flag_vacation_day == true) {

			$str= $str."<br><font color='navy'>휴무</font><br>";

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
				
				//if (($VA_TYPE != 5) && ($VA_TYPE != 6)) {
				if ($VA_TYPE != 5) {
					$str= $str." ".$USER_NAME." ";
				}
			}
			$str= $str."<br>";
		}

		return $str;
		
	}

	function getVacationUserDateWithCondition2022($db, $date, $insert_flag, $adm_no, $headquarters_code, $dept_code, $va_user) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.VA_TYPE, B.VA_USER, A.VA_STATE, A.VA_MEMO,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_STATE' AND DCODE = A.VA_STATE) AS STATE_NM,
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'VA_TYPE' AND DCODE = A.VA_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = B.VA_USER) AS USER_NAME
								FROM TBL_VACATION A, TBL_VACATION_DATE B, TBL_ADMIN_INFO C, TBL_ORG D 
							 WHERE A.SEQ_NO = B.SEQ_NO
								 AND A.VA_USER = C.ADM_NO
								 AND C.ADM_NO = D.ADM_NO AND D.YEAR = '2022' 
								 AND A.DEL_TF = 'N'
								 AND B.VA_DATE = '$date' ";

//echo $query;
//exit;
		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}

		if ($va_user <> "") {
			$query .= " AND A.VA_USER = '".$va_user."' ";
		}

		$query .= " ORDER BY A.SEQ_NO ASC";



		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$flag_smaart_day = false;
		$flag_vacation_day = false;

		for($i=0 ; $i< $total ; $i++) {

			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);
			
			$VA_TYPE			= Trim($row["VA_TYPE"]);

			//if (($VA_TYPE == 5) || ($VA_TYPE == 6)) {
			if ($VA_TYPE == 5) {
				$flag_smaart_day  = true;
			} else {
				$flag_vacation_day  = true;
			}
		}

		if ($flag_smaart_day == true) {

			$str= $str."<br><font color='orange'>스마트데이</font><br>";

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
				
				//if (($VA_TYPE == 5) || ($VA_TYPE == 6)) {
				if ($VA_TYPE == 5) {
					$str= $str." ".$USER_NAME." ";
				}
			}
			$str= $str."<br>";
		}


		if ($flag_vacation_day == true) {

			$str= $str."<br><font color='navy'>휴무</font><br>";

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
				
				//if (($VA_TYPE != 5) && ($VA_TYPE != 6)) {
				if ($VA_TYPE != 5) {
					$str= $str." ".$USER_NAME." ";
				}
			}
			$str= $str."<br>";
		}

		return $str;
		
	}

	function insertSchedule($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_SCHEDULE (".$set_field.", REG_DATE) 
					values (".$set_value.", now()); ";

		//echo $query."<br>"; 

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

	function updateSchedule($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_SCHEDULE SET ".$set_query_str." ";
		$query .= "SEQ_NO = '$seq_no' WHERE SEQ_NO = '$seq_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getScheduleUserDateWithCondition($db, $date, $dept_code, $s_user) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.S_TITLE, A.S_DATE, A.S_SITME, A.S_EITME, A.S_TYPE, A.S_USER, A.S_MEMO, A.S_COMPANY, 
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'SCHEDULE_TYPE' AND DCODE = A.S_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.S_USER) AS USER_NAME
								FROM TBL_SCHEDULE A, TBL_ADMIN_INFO B
							 WHERE A.S_USER = B.ADM_NO
								 AND A.DEL_TF = 'N'
								 AND A.S_DATE = '$date' ";

		if ($dept_code <> "") {
			$query .= " AND B.DEPT_CODE = '".$dept_code."' ";
		}

		if ($va_user <> "") {
			$query .= " AND A.S_USER = '".$s_user."' ";
		}

		$query .= " ORDER BY A.S_SITME ASC ";

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		for($i=0 ; $i< $total ; $i++) {

			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);
			
			$SEQ_NO			= Trim($row["SEQ_NO"]);
			$S_TITLE		= SetStringFromDB($row["S_TITLE"]);
			$S_DATE			= Trim($row["S_DATE"]);
			$S_SITME		= Trim($row["S_SITME"]);
			$S_EITME		= Trim($row["S_EITME"]);
			$S_TYPE			= Trim($row["S_TYPE"]);
			$S_USER			= Trim($row["S_USER"]);
			$S_MEMO			= SetStringFromDB($row["S_MEMO"]);
			$S_COMPANY	= Trim($row["S_COMPANY"]);
			$DCODE_NM		= Trim($row["DCODE_NM"]);
			$USER_NAME	= Trim($row["USER_NAME"]);

			$str= $str."<div style='position:relative'>";

			$str= $str."<div id='".$SEQ_NO.$S_DATE."' style='position:absolute; background-color: #EFEFEF; border: 1px solid #DEDEDE; padding:5px 5px 5px 5px; top:20px; left:-10px; text-align:left; z-index:1000; display:none;';>";
			$str= $str.nl2br($S_MEMO)."</div>";

			if (trim($S_USER) == trim($_SESSION['s_adm_no'])) {
				$str = $str."<a href='javascript:js_edit_schedule(".$SEQ_NO.")' onmouseover=\"js_layer_show('".$SEQ_NO.$S_DATE."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$S_DATE."');\">".$S_SITME."~".$S_EITME." <b>".$USER_NAME."</b> (".$DCODE_NM.")</a><br>".$S_TITLE."<br><font color='#8C8C8C'>".$S_COMPANY."</font><br>"; 
			} else {
				$str = $str."<span onmouseover=\"js_layer_show('".$SEQ_NO.$S_DATE."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$S_DATE."');\">".$S_SITME."~".$S_EITME." <b>".$USER_NAME."</b> (".$DCODE_NM.")</span><br>".$S_TITLE."<br>".$S_COMPANY."<br>"; 
			}

			$str= $str."</div>";

		}

		return $str;
		
	}

	function getScheduleUserDateWithCondition2022($db, $date, $headquarters_code, $dept_code, $s_user) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.S_TITLE, A.S_DATE, A.S_SITME, A.S_EITME, A.S_TYPE, A.S_USER, A.S_MEMO, A.S_COMPANY, 
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'SCHEDULE_TYPE' AND DCODE = A.S_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.S_USER) AS USER_NAME
								FROM TBL_SCHEDULE A, TBL_ADMIN_INFO B, TBL_ORG D
							 WHERE A.S_USER = B.ADM_NO
								 AND B.ADM_NO = D.ADM_NO AND D.YEAR = '2022'
								 AND A.DEL_TF = 'N'
								 AND A.S_DATE = '$date' ";

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}

		if ($va_user <> "") {
			$query .= " AND A.S_USER = '".$s_user."' ";
		}

		$query .= " ORDER BY A.S_SITME ASC ";

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		for($i=0 ; $i< $total ; $i++) {

			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);
			
			$SEQ_NO			= Trim($row["SEQ_NO"]);
			$S_TITLE		= SetStringFromDB($row["S_TITLE"]);
			$S_DATE			= Trim($row["S_DATE"]);
			$S_SITME		= Trim($row["S_SITME"]);
			$S_EITME		= Trim($row["S_EITME"]);
			$S_TYPE			= Trim($row["S_TYPE"]);
			$S_USER			= Trim($row["S_USER"]);
			$S_MEMO			= SetStringFromDB($row["S_MEMO"]);
			$S_COMPANY	= Trim($row["S_COMPANY"]);
			$DCODE_NM		= Trim($row["DCODE_NM"]);
			$USER_NAME	= Trim($row["USER_NAME"]);

			$str= $str."<div style='position:relative'>";

			$str= $str."<div id='".$SEQ_NO.$S_DATE."' style='position:absolute; background-color: #EFEFEF; border: 1px solid #DEDEDE; padding:5px 5px 5px 5px; top:20px; left:-10px; text-align:left; z-index:1000; display:none;';>";
			$str= $str.nl2br($S_MEMO)."</div>";

			if (trim($S_USER) == trim($_SESSION['s_adm_no'])) {
				$str = $str."<a href='javascript:js_edit_schedule(".$SEQ_NO.")' onmouseover=\"js_layer_show('".$SEQ_NO.$S_DATE."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$S_DATE."');\">".$S_SITME."~".$S_EITME." <b>".$USER_NAME."</b> (".$DCODE_NM.")</a><br>".$S_TITLE."<br><font color='#8C8C8C'>".$S_COMPANY."</font><br>"; 
			} else {
				$str = $str."<span onmouseover=\"js_layer_show('".$SEQ_NO.$S_DATE."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$S_DATE."');\">".$S_SITME."~".$S_EITME." <b>".$USER_NAME."</b> (".$DCODE_NM.")</span><br>".$S_TITLE."<br>".$S_COMPANY."<br>"; 
			}

			$str= $str."</div>";

		}

		return $str;
		
	}

	function selectSchedule($db, $seq_no) {
		
		$query = "SELECT * FROM TBL_SCHEDULE WHERE SEQ_NO = '$seq_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function deleteSchedule($db, $seq_no) {

		$query = "UPDATE TBL_SCHEDULE SET DEL_TF = 'Y' WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>