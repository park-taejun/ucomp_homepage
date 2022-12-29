<?

	function insertRoomSchedule($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_ROOM_SCHEDULE (".$set_field.", REG_DATE) 
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

	function updateRoomSchedule($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_ROOM_SCHEDULE SET ".$set_query_str." ";
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

	function getRommScheduleUserDateWithCondition($db, $date, $dept_code, $s_user) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.S_TITLE, A.S_DATE, A.S_SITME, A.S_EITME, A.S_TYPE, A.S_USER, A.S_MEMO, A.S_COMPANY, 
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'ROOM_SCHEDULE_TYPE' AND DCODE = A.S_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.S_USER) AS USER_NAME
								FROM TBL_ROOM_SCHEDULE A, TBL_ADMIN_INFO B
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

	function getRoomScheduleUserDateWithCondition2022($db, $date, $room_schedule_type, $s_user) {
		
		$str = "";

		$query = "SELECT A.SEQ_NO, A.S_TITLE, A.S_DATE, A.S_SITME, A.S_EITME, A.S_TYPE, A.S_USER, A.S_MEMO, A.S_COMPANY, 
										(SELECT DCODE_NM FROM TBL_CODE_DETAIL WHERE PCODE = 'ROOM_SCHEDULE_TYPE' AND DCODE = A.S_TYPE) AS DCODE_NM,
										(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.S_USER) AS USER_NAME
								FROM TBL_ROOM_SCHEDULE A, TBL_ADMIN_INFO B, TBL_CODE_DETAIL C
							 WHERE A.S_USER = B.ADM_NO
								 AND A.S_TYPE = C.DCODE
								 AND A.DEL_TF = 'N'
								 AND A.S_DATE = '$date' ";

		if ($room_schedule_type <> "") {
			$query .= " AND A.S_TYPE = '".$room_schedule_type."' ";
		}


		if ($va_user <> "") {
			$query .= " AND A.S_USER = '".$s_user."' ";
		}

		$query .= " ORDER BY C.DCODE_SEQ_NO ASC, A.S_SITME ASC ";

		//echo $query;

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
				$str = $str."<a href='javascript:js_edit_schedule(".$SEQ_NO.")' onmouseover=\"js_layer_show('".$SEQ_NO.$S_DATE."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$S_DATE."');\"><b>".$DCODE_NM."</b> ".$S_SITME."~".$S_EITME." <b>".$USER_NAME." </b></a><br>".$S_TITLE."<br>"; 
			} else {
				$str = $str."<span onmouseover=\"js_layer_show('".$SEQ_NO.$S_DATE."');\" onmouseout=\"js_layer_hide('".$SEQ_NO.$S_DATE."');\"><b>".$DCODE_NM."</b> ".$S_SITME."~".$S_EITME." <b>".$USER_NAME."</b><br>".$S_TITLE."<br>"; 
			}

			$str= $str."</div>";

		}

		return $str;
		
	}

	function selectRoomSchedule($db, $seq_no) {
		
		$query = "SELECT * FROM TBL_ROOM_SCHEDULE WHERE SEQ_NO = '$seq_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function deleteRoomSchedule($db, $seq_no) {

		$query = "UPDATE TBL_ROOM_SCHEDULE SET DEL_TF = 'Y' WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>