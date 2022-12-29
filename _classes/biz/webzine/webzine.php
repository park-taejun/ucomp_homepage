<?

	function selectTopWebzine($db) {

		$query = "SELECT *
								FROM TBL_WEBZINE WHERE USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY YYYY DESC, MM DESC LIMIT 1 ";
		
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

	function listWebzine($db, $yyyy, $mm, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, YYYY, MM, PUB_DATE, VOL_NO, TITLE, MEMO,
										 MAIN_IMAGE01, MAIN_IMAGE02, MAIN_IMAGE03, PDF_IMAGE, PDF_FILE, HIT_CNT,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_WEBZINE WHERE 1 = 1 ";

		if ($yyyy <> "") {
			$query .= " AND YYYY = '".$yyyy."' ";
		}

		if ($mm <> "") {
			$query .= " AND MM = '".$mm."' ";
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

		$query .= " ORDER BY YYYY desc, MM desc limit ".$offset.", ".$nRowCount;
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntWebzine($db, $yyyy, $mm, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_WEBZINE WHERE 1 = 1 ";
		
		if ($yyyy <> "") {
			$query .= " AND YYYY = '".$yyyy."' ";
		}

		if ($mm <> "") {
			$query .= " AND MM = '".$mm."' ";
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

	function selectWebzine($db, $seq_no) {

		$query = "SELECT *
								FROM TBL_WEBZINE WHERE SEQ_NO = '$seq_no' ";
		
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


	function insertWebzine($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_WEBZINE (".$set_field.", REG_DATE, UP_DATE) 
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

	function updateWebzine($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_WEBZINE SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
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

	function deleteWebzine($db, $seq_no) {

		$query="UPDATE TBL_WEBZINE SET 
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

	function updateWebzineUseTF($db, $use_tf, $up_adm, $seq_no) {
		
		$query="UPDATE TBL_WEBZINE SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE SEQ_NO			= '$seq_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function insertWebzineEvent($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_EVENT (".$set_field.", REG_DATE, UP_DATE) 
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

	function selectWebzineEvent($db, $seq_no) {

		$query = "SELECT *
								FROM TBL_EVENT WHERE SEQ_NO = '$seq_no' ";
		
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

	function updateWebzineEvent($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_EVENT SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
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

	function deleteWebzineEvent($db, $seq_no) {

		$query="UPDATE TBL_EVENT SET 
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

	function listWebzineEvent($db, $yyyy, $mm, $type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.SEQ_NO, A.W_SEQ_NO, A.YYYY, A.MM, A.TYPE, 
										 A.S_DATE, A.S_HOUR, A.S_MIN, A.E_DATE, A.E_HOUR, A.E_MIN, A.TITLE, A.MEMO,
										 A.IMAGE01, A.HIT_CNT, A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE, B.VOL_NO,
										 (SELECT COUNT(SEQ_NO) FROM TBL_APPLY WHERE ESEQ_NO = A.SEQ_NO AND DEL_TF ='N') AS APPLY_CNT
								FROM TBL_EVENT A, TBL_WEBZINE B WHERE A.W_SEQ_NO = B.SEQ_NO AND B.DEL_TF = 'N' AND B.USE_TF = 'Y' AND A.TYPE IN ('TYPE01','TYPE02','TYPE03') ";

		if ($yyyy <> "") {
			$query .= " AND A.YYYY = '".$yyyy."' ";
		}

		if ($mm <> "") {
			$query .= " AND A.MM = '".$mm."' ";
		}

		if ($type <> "") {
			$query .= " AND A.TYPE = '".$type."' ";
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

		$query .= " ORDER BY A.YYYY desc, A.MM desc, A.SEQ_NO desc, A.TYPE ASC limit ".$offset.", ".$nRowCount;
		
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


	function totalCntWebzineEvent($db, $yyyy, $mm, $type, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_EVENT A, TBL_WEBZINE B WHERE A.W_SEQ_NO = B.SEQ_NO AND B.DEL_TF = 'N' AND B.USE_TF = 'Y' AND A.TYPE IN ('TYPE01','TYPE02','TYPE03') ";
		
		if ($yyyy <> "") {
			$query .= " AND A.YYYY = '".$yyyy."' ";
		}

		if ($mm <> "") {
			$query .= " AND A.MM = '".$mm."' ";
		}

		if ($type <> "") {
			$query .= " AND A.TYPE = '".$type."' ";
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

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listWebzineEndEvent($db, $w_seq_no, $type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $nPage;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		//echo $offset;

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.SEQ_NO, A.W_SEQ_NO, A.YYYY, A.MM, A.TYPE, A.S_DATE, A.E_DATE, A.TITLE, A.MEMO,
										 A.IMAGE01, A.HIT_CNT,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.VOL_NO
								FROM TBL_EVENT A, TBL_WEBZINE B WHERE A.W_SEQ_NO = B.SEQ_NO AND B.DEL_TF = 'N' AND B.USE_TF = 'Y' AND A.TYPE IN ('TYPE01','TYPE02','TYPE03') ";

		if ($w_seq_no <> "") {
			$query .= " AND A.W_SEQ_NO <> '".$w_seq_no."' ";
		}

		if ($type <> "") {
			$query .= " AND A.TYPE = '".$type."' ";
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

		$query .= " ORDER BY A.YYYY desc, A.MM desc, A.SEQ_NO DESC, A.TYPE ASC limit ".$offset.", ".$nRowCount;
		
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


	function totalCntWebzineEndEvent($db, $w_seq_no, $type, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_EVENT A, TBL_WEBZINE B WHERE A.W_SEQ_NO = B.SEQ_NO AND B.DEL_TF = 'N' AND B.USE_TF = 'Y' AND A.TYPE IN ('TYPE01','TYPE02','TYPE03') ";
		
		if ($w_seq_no <> "") {
			$query .= " AND A.W_SEQ_NO <> '".$w_seq_no."' ";
		}

		if ($type <> "") {
			$query .= " AND A.TYPE = '".$type."' ";
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

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}



	function listWebzineIngEventAsDate($db, $type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.SEQ_NO, A.W_SEQ_NO, A.YYYY, A.MM, A.TYPE, 
										 A.S_DATE, A.S_HOUR, A.S_MIN, A.E_DATE, A.E_HOUR, A.E_MIN, A.TITLE, A.MEMO,
										 A.IMAGE01, A.HIT_CNT, A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 (SELECT VOL_NO FROM TBL_WEBZINE WHERE SEQ_NO = A.W_SEQ_NO AND USE_TF ='Y' AND DEL_TF ='N') AS VOL_NO,
										 (SELECT COUNT(SEQ_NO) FROM TBL_APPLY WHERE ESEQ_NO = A.SEQ_NO AND DEL_TF ='N') AS APPLY_CNT
								FROM TBL_EVENT A, TBL_WEBZINE B 
							 WHERE A.W_SEQ_NO = B.SEQ_NO AND B.DEL_TF = 'N' AND B.USE_TF = 'Y' AND A.TYPE IN ('TYPE01','TYPE02','TYPE03')
								 AND CONCAT(replace(S_DATE,'-',''), IFNULL(S_HOUR,'00'), IFNULL(S_MIN,'00')) <= '".date("YmdHi",strtotime("0 day"))."'
								 AND CONCAT(replace(E_DATE,'-',''), IFNULL(E_HOUR,'00'), IFNULL(E_MIN,'00')) >= '".date("YmdHi",strtotime("0 day"))."' ";

		if ($type <> "") {
			$query .= " AND A.TYPE = '".$type."' ";
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

		$query .= " ORDER BY A.YYYY desc, A.MM desc, A.TYPE ASC limit ".$offset.", ".$nRowCount;
		
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


	function totalCntWebzineIngEventAsDate($db, $type, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT 
							 FROM TBL_EVENT A, TBL_WEBZINE B
							WHERE A.W_SEQ_NO = B.SEQ_NO AND B.DEL_TF = 'N' AND B.USE_TF = 'Y' AND A.TYPE IN ('TYPE01','TYPE02','TYPE03')
								AND CONCAT(replace(S_DATE,'-',''), IFNULL(S_HOUR,'00'), IFNULL(S_MIN,'00')) <= '".date("YmdHi",strtotime("0 day"))."'
								AND CONCAT(replace(E_DATE,'-',''), IFNULL(E_HOUR,'00'), IFNULL(E_MIN,'00')) >= '".date("YmdHi",strtotime("0 day"))."' ";
		
		if ($type <> "") {
			$query .= " AND A.TYPE = '".$type."' ";
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

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listWebzineEndEventAsDate($db, $type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $nPage;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		//echo $offset;

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.SEQ_NO, A.W_SEQ_NO, A.YYYY, A.MM, A.TYPE, A.S_DATE, A.S_HOUR, A.S_MIN, A.E_DATE, A.E_HOUR, A.E_MIN, A.TITLE, A.MEMO,
										 A.IMAGE01, A.HIT_CNT,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.VOL_NO
								FROM TBL_EVENT A, TBL_WEBZINE B WHERE A.W_SEQ_NO = B.SEQ_NO AND B.DEL_TF = 'N' AND B.USE_TF = 'Y' AND A.TYPE IN ('TYPE01','TYPE02','TYPE03')
								 AND CONCAT(replace(E_DATE,'-',''), IFNULL(E_HOUR,'00'), IFNULL(E_MIN,'00')) < '".date("YmdHi",strtotime("0 day"))."' ";

		if ($type <> "") {
			$query .= " AND A.TYPE = '".$type."' ";
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

		$query .= " ORDER BY A.YYYY desc, A.MM desc, A.SEQ_NO DESC, A.TYPE ASC limit ".$offset.", ".$nRowCount;
		
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


	function totalCntWebzineEndEventAsDate($db, $type, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT 
							 FROM TBL_EVENT A, TBL_WEBZINE B WHERE A.W_SEQ_NO = B.SEQ_NO AND B.DEL_TF = 'N' AND B.USE_TF = 'Y' AND A.TYPE IN ('TYPE01','TYPE02','TYPE03')
								AND CONCAT(replace(E_DATE,'-',''), IFNULL(E_HOUR,'00'), IFNULL(E_MIN,'00')) < '".date("YmdHi",strtotime("0 day"))."'";
		
		if ($type <> "") {
			$query .= " AND A.TYPE = '".$type."' ";
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

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listQuestion($db, $eseq_no) {

		$query = "SELECT *
								FROM TBL_QUESTION WHERE eseq_no = '$eseq_no' AND DEL_TF = 'N' ORDER BY QSEQ_NO ASC ";
		
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

	function insertQusetion($db, $arr_data) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";
		$set_eseq_no = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
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
				
				if ($key == "FORM_NO") {
					$set_form_no = $value;
				}

			}
		}

		$query = "INSERT INTO TBL_QUESTION (".$set_field.", DISP_SEQ, REG_DATE, UP_DATE) 
					values (".$set_value.", '0', now(), now()); ";

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

	function updateQusetion($db, $arr_data, $qseq_no) {
		
		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_QUESTION SET ".$set_query_str." ";
		$query .= "UP_DATE = now(),";
		$query .= "QSEQ_NO = '$qseq_no' WHERE QSEQ_NO = '$qseq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectQuestion($db, $qseq_no) {

		$query = "SELECT *
								FROM TBL_QUESTION 
							 WHERE QSEQ_NO = '$qseq_no' ";

		$result = mysql_query($query,$db);
		$record = array();
		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertOption($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_OPTION (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		
		//echo "insert ".$query;


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

	function updateOption($db, $arr_data, $oseq_no) {
		
		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_OPTION SET ".$set_query_str." ";
		$query .= "DEL_TF = 'N',";
		$query .= "UP_DATE = now(),";
		$query .= "OSEQ_NO = '$oseq_no' WHERE OSEQ_NO = '$oseq_no' ";

		//echo "update ".$query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteOption($db, $qseq_no) {
		
		$query="UPDATE TBL_OPTION SET 
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE QSEQ_NO			= '$qseq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listOption($db, $qseq_no, $use_tf, $del_tf) {

		$query = "SELECT *
								FROM TBL_OPTION  
							 WHERE 1 = 1 ";

		if ($qseq_no <> "") {
			$query .= " AND QSEQ_NO = '".$qseq_no."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}
		
		if ($order_field == "") 
			$order_field = "OSEQ_NO";

		if ($order_str == "") 
			$order_str = "ASC";

		$query .= " ORDER BY ".$order_field." ".$order_str;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function deleteQuestion($db, $qseq_no) {

		$query="UPDATE TBL_QUESTION SET 
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE QSEQ_NO			= '$qseq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getEventSeqNo($db, $wseq_no, $type) {
		
		//$query = "SELECT SEQ_NO FROM TBL_EVENT WHERE W_SEQ_NO = '$wseq_no' AND TYPE = '$type' AND DEL_TF = 'N' ORDER BY SEQ_NO DESC LIMIT 1 ";

		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") { 
			$query = "SELECT SEQ_NO FROM TBL_EVENT WHERE TYPE = '$type' AND DEL_TF = 'N' ORDER BY SEQ_NO DESC LIMIT 1 ";
		} else {
			$query = "SELECT SEQ_NO FROM TBL_EVENT WHERE TYPE = '$type' AND USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY SEQ_NO DESC LIMIT 1 ";
		}

		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}


	function listApply($db, $wseq_no, $eseq_no, $yyyy, $mm, $type, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, WSEQ_NO, YYYY, MM, TYPE, APPLY_NM, EMP_NM, REL, LOCATION,
										 TEL, PASS, IMAGE_NM, IMAGE_RNM, EPISODE, EPISODE_SOURCE, AGREE_YN, PICK_YN,
										 DEL_TF, REG_DATE, UP_DATE, DEL_DATE, ANSWER01, ANSWER02, ANSWER03
								FROM TBL_APPLY WHERE 1 = 1 ";

		if ($wseq_no <> "") {
			$query .= " AND WSEQ_NO = '".$wseq_no."' ";
		}

		if ($eseq_no <> "") {
			$query .= " AND ESEQ_NO = '".$eseq_no."' ";
		}

		if ($yyyy <> "") {
			$query .= " AND YYYY = '".$yyyy."' ";
		}

		if ($mm <> "") {
			$query .= " AND MM = '".$mm."' ";
		}

		if ($type <> "") {
			$query .= " AND TYPE = '".$type."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY SEQ_NO desc limit ".$offset.", ".$nRowCount;
		
		$result = mysql_query($query,$db);
		$record = array();
		
		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntApply($db, $wseq_no, $eseq_no, $yyyy, $mm, $type, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_APPLY WHERE 1 = 1 ";
		
		if ($wseq_no <> "") {
			$query .= " AND WSEQ_NO = '".$wseq_no."' ";
		}

		if ($eseq_no <> "") {
			$query .= " AND ESEQ_NO = '".$eseq_no."' ";
		}

		if ($yyyy <> "") {
			$query .= " AND YYYY = '".$yyyy."' ";
		}

		if ($mm <> "") {
			$query .= " AND MM = '".$mm."' ";
		}

		if ($type <> "") {
			$query .= " AND TYPE = '".$type."' ";
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

	function insertApply($db, $arr_data) {
		
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
				if ($key == "PASS") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_APPLY  (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo "insert ".$query;

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


	function updateApply($db, $arr_data, $seq_no) {
		
		foreach ($arr_data as $key => $value) {

			$value = str_replace("'","''",$value);

			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_APPLY SET ".$set_query_str." ";
		$query .= "UP_DATE = now(),";
		$query .= "SEQ_NO = '$seq_no' WHERE SEQ_NO = '$seq_no' ";

		//echo "update ".$query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectApply($db, $seq_no) {

		$query = "SELECT *
								FROM TBL_APPLY WHERE SEQ_NO = '$seq_no' AND DEL_TF = 'N' ";
		
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

	function dupApply($db, $wseq_no, $eseq_no, $apply_nm, $tel, $type) {
		
		$query = "SELECT COUNT(*) CNT FROM TBL_APPLY WHERE WSEQ_NO = '$wseq_no' AND ESEQ_NO = '$eseq_no' AND APPLY_NM = '$apply_nm' AND TEL = '$tel' AND TYPE = '$type' AND DEL_TF = 'N' ";

		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function deleteApply($db, $seq_no) {

		$query = "UPDATE TBL_APPLY SET ";
		$query .= "DEL_DATE = now(), ";
		$query .= "DEL_TF = 'Y' ";
		$query .= "WHERE SEQ_NO = '$seq_no' ";

		//echo "update ".$query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function insertAnswer($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_ANSWER (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		
		//echo "insert ".$query;


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

	function deleteAnswer($db, $qseq_no, $aseq_no) {

		$query = "DELETE FROM TBL_ANSWER WHERE QSEQ_NO = '$qseq_no' AND ASEQ_NO = '$aseq_no' ";

		//echo "update ".$query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getAnswer($db, $qseq_no, $aseq_no) {

		$query = "SELECT *
								FROM TBL_ANSWER WHERE QSEQ_NO = '$qseq_no' AND ASEQ_NO = '$aseq_no' ";
		
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

	function confrimApply($db, $eseq_no, $wseq_no, $type, $apply_nm, $tel, $pass ) {
		
		if ($_SERVER['REMOTE_ADDR'] == "182.208.250.10") {

		$query = "SELECT SEQ_NO 
								FROM TBL_APPLY 
							 WHERE ESEQ_NO = '$eseq_no' 
								 AND TYPE = '$type' 
								 AND APPLY_NM = '$apply_nm'
								 AND TEL = '$tel'
								 AND DEL_TF = 'N' LIMIT 1
								 ";

		} else {
	
		$query = "SELECT SEQ_NO 
								FROM TBL_APPLY 
							 WHERE ESEQ_NO = '$eseq_no' 
								 AND TYPE = '$type' 
								 AND APPLY_NM = '$apply_nm'
								 AND TEL = '$tel'
								 AND PASS = password('$pass')
								 AND DEL_TF = 'N' LIMIT 1
								 ";
		}

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

	function updateApplyPickTF($db, $pick_tf, $up_adm, $seq_no) {
		
		$query="UPDATE TBL_APPLY SET 
							PICK_YN			= '$pick_tf',
							UP_DATE			= now()
				 WHERE SEQ_NO			= '$seq_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getPickApplyCnt($db, $wseq_no, $type) {
		
		$query = "SELECT COUNT(*) CNT FROM TBL_APPLY WHERE PICK_YN = 'Y' AND DEL_TF = 'N' AND WSEQ_NO = '$wseq_no' AND TYPE = '$type' ";

		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function getPickApplyList($db, $eseq_no) {

		$query = "SELECT *
							  FROM TBL_APPLY WHERE PICK_YN = 'Y' AND DEL_TF = 'N' AND ESEQ_NO = '$eseq_no' ";
		
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


	function listWebzineEventVol100($db) {


		$query = "SELECT A.SEQ_NO, A.W_SEQ_NO, A.YYYY, A.MM, A.TYPE, A.S_DATE, A.E_DATE, A.TITLE, A.MEMO,
										 A.IMAGE01, A.HIT_CNT,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.VOL_NO,
										 (SELECT COUNT(SEQ_NO) FROM TBL_APPLY WHERE ESEQ_NO = A.SEQ_NO AND DEL_TF ='N') AS APPLY_CNT
								FROM TBL_EVENT A, TBL_WEBZINE B 
							 WHERE A.W_SEQ_NO = B.SEQ_NO 
								 AND A.DEL_TF = 'N' 
								 AND A.USE_TF = 'Y' 
								 AND B.DEL_TF = 'N' 
								 AND B.USE_TF = 'Y' 
								 AND A.TYPE IN ('TYPE10','TYPE11','TYPE12','TYPE13') ";

		$query .= " ORDER BY A.YYYY desc, A.MM desc, A.TYPE ASC ";
		
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


	function isEventOpenAsType ($db, $type) {

		$query = "SELECT COUNT(A.SEQ_NO) AS CNT
								FROM TBL_EVENT A, TBL_WEBZINE B 
							 WHERE A.W_SEQ_NO = B.SEQ_NO 
								 AND A.TYPE = '".$type."' 
								 AND A.USE_TF = 'Y'
								 AND A.DEL_TF = 'N'
								 AND B.USE_TF = 'Y'
								 AND B.DEL_TF = 'N'
								 AND CONCAT(replace(S_DATE,'-',''), IFNULL(S_HOUR,'00'), IFNULL(S_MIN,'00')) <= '".date("YmdHi",strtotime("0 day"))."' 
								 AND CONCAT(replace(E_DATE,'-',''), IFNULL(E_HOUR,'00'), IFNULL(E_MIN,'00')) >= '".date("YmdHi",strtotime("0 day"))."' ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}
?>