<?

	function selectEquipmentRent($db, $eq_no) {

		$query = "SELECT *
								FROM TBL_EQUIPMENT_RENT WHERE EQ_NO = '$eq_no' ";
		
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

	function listEquipmentRent($db, $eq_type, $eq_user, $eq_state, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);


		$query = "SELECT @rownum:= @rownum - 1  as rn, EQ_NO, EQ_CD, EQ_TYPE, EQ_CONAME, EQ_MDATE, EQ_MODEL, EQ_INFO01, EQ_INFO02, EQ_INFO03, EQ_INFO04,
										 EQ_INFO05, EQ_INFO06, EQ_INFO07, EQ_INFO08, EQ_INDATE, EQ_RECDATE, EQ_RETDATE, EQ_DISDATE, EQ_MEMO,
										 EQ_USER, EQ_STATE, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_EQUIPMENT_RENT.EQ_USER) AS ADM_NAME
								FROM TBL_EQUIPMENT_RENT WHERE 1 = 1 ";

		if ($eq_type <> "") {
			$query .= " AND EQ_TYPE = '".$eq_type."' ";
		}

		if ($eq_user <> "") {

			if ($eq_user == "NONE") {
				$query .= " AND (EQ_USER = '' OR EQ_USER = '".$eq_user."') ";
			} else {
				$query .= " AND EQ_USER = '".$eq_user."' ";
			}

		}

		if ($eq_state <> "") {
			$query .= " AND EQ_STATE = '".$eq_state."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY EQ_NO desc limit ".$offset.", ".$nRowCount;
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntEquipmentRent($db, $eq_type, $eq_user, $eq_state, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_EQUIPMENT_RENT WHERE 1 = 1 ";
		
		if ($eq_type <> "") {
			$query .= " AND EQ_TYPE = '".$eq_type."' ";
		}

		if ($eq_user <> "") {
			if ($eq_user == "NONE") {
				$query .= " AND (EQ_USER = '' OR EQ_USER = '".$eq_user."') ";
			} else {
				$query .= " AND EQ_USER = '".$eq_user."' ";
			}
		}

		if ($eq_state <> "") {
			$query .= " AND EQ_STATE = '".$eq_state."' ";
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

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertEquipmentRent($db, $arr_data) {
		
		// ????????? ??????
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

		$query = "INSERT INTO TBL_EQUIPMENT_RENT (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]????????? ????????????????????? - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			
			$query = "SELECT last_insert_id()";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$new_seq_no  = $rows[0];
			return $new_seq_no;

		}
	}

	function insertEquipmentRentHistory($db, $arr_data) {
		
		// ????????? ??????
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

		$query = "INSERT INTO TBL_EQUIPMENT_RENT_HISTORY (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]????????? ????????????????????? - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			
			$query = "SELECT last_insert_id()";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$new_seq_no  = $rows[0];
			return $new_seq_no;

		}
	}

	function updateEquipmentRent($db, $arr_data, $eq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_EQUIPMENT_RENT SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "EQ_NO = '$eq_no' WHERE EQ_NO = '$eq_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]????????? ????????????????????? - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteEquipmentRent($db, $eq_no) {

		$query="UPDATE TBL_EQUIPMENT_RENT SET 
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE EQ_NO				= '$eq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]????????? ????????????????????? - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function listEquipmentRentHistory($db, $eq_no) {

		$query = "SELECT SEQ_NO, EQ_NO, EQ_CD, EQ_RECDATE, EQ_RETDATE, EQ_DISDATE, EQ_MEMO, EQ_PREUSER, EQ_NEXTUSER, EQ_STATE, DEL_TF,
										 REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_EQUIPMENT_RENT_HISTORY.REG_ADM) AS REG_ADM_NAME,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_EQUIPMENT_RENT_HISTORY.EQ_PREUSER) AS PRE_ADM_NAME,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_EQUIPMENT_RENT_HISTORY.EQ_NEXTUSER) AS NEXT_ADM_NAME
								FROM TBL_EQUIPMENT_RENT_HISTORY WHERE EQ_NO = '$eq_no' ";

		$query .= " ORDER BY SEQ_NO desc ";
		
		$result = mysql_query($query,$db);
		$record = array();
		
		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listEquipmentRentUserHistory($db, $eq_type, $adm_no) {

		$query = "SELECT A.SEQ_NO, A.EQ_NO, A.EQ_CD, A.EQ_RECDATE, A.EQ_RETDATE, A.EQ_DISDATE, A.EQ_MEMO, A.EQ_PREUSER, A.EQ_NEXTUSER, A.EQ_STATE, A.DEL_TF,
										 A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE, B.EQ_TYPE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.REG_ADM) AS REG_ADM_NAME,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.EQ_PREUSER) AS PRE_ADM_NAME,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = A.EQ_NEXTUSER) AS NEXT_ADM_NAME
								FROM TBL_EQUIPMENT_RENT_HISTORY A, TBL_EQUIPMENT_RENT B WHERE A.EQ_NO = B.EQ_NO AND (A.EQ_PREUSER = '$adm_no' OR A.EQ_NEXTUSER = '$adm_no') ";

		if ($eq_type <> "") {
			$query .= " AND B.EQ_TYPE = '".$eq_type."' ";
		}

		$query .= " ORDER BY A.SEQ_NO desc ";
		
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