<?

	function selectSupplies($db, $su_no) {

		$query = "SELECT *
								FROM TBL_SUPPLIES WHERE SU_NO = '$su_no' ";
		
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

	function listSupplies($db, $su_type, $ask_adm_no, $ask_state, $buy_state, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);


		$query = "SELECT @rownum:= @rownum - 1  as rn, SU_NO, TITLE, SU_TYPE, SU_MODEL, SU_PRICE, BUY_LINK, ASK_ADM_NO, ASK_DATE, ASK_STATE, BUY_STATE,
										 BUY_COMPANY, BUY_DATE, PAY_TYPE, BUY_PRICE, BUY_MEMO, MEMO, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_SUPPLIES.ASK_ADM_NO) AS ADM_NAME
								FROM TBL_SUPPLIES WHERE 1 = 1 ";

		if ($su_type <> "") {
			$query .= " AND SU_TYPE = '".$su_type."' ";
		}

		if ($ask_adm_no <> "") {
			$query .= " AND ASK_ADM_NO = '".$ask_adm_no."' ";
		}

		if ($ask_state <> "") {
			$query .= " AND ASK_STATE = '".$ask_state."' ";
		}

		if ($buy_state <> "") {
			$query .= " AND BUY_STATE = '".$buy_state."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY SU_NO desc limit ".$offset.", ".$nRowCount;
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntSupplies($db, $su_type, $ask_adm_no, $ask_state, $buy_state, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_SUPPLIES WHERE 1 = 1 ";
		
		if ($su_type <> "") {
			$query .= " AND SU_TYPE = '".$su_type."' ";
		}

		if ($ask_adm_no <> "") {
			$query .= " AND ASK_ADM_NO = '".$ask_adm_no."' ";
		}

		if ($ask_state <> "") {
			$query .= " AND ASK_STATE = '".$ask_state."' ";
		}

		if ($buy_state <> "") {
			$query .= " AND BUY_STATE = '".$buy_state."' ";
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

	function insertSupplies($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_SUPPLIES (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

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

	function updateSupplies($db, $arr_data, $su_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_SUPPLIES SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "SU_NO = '$su_no' WHERE SU_NO = '$su_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteSupplies($db, $del_adm, $su_no) {

		$query="UPDATE TBL_SUPPLIES SET 
									 DEL_ADM			= '$del_adm',
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE SU_NO				= '$su_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>