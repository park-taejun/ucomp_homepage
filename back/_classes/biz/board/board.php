<?

	function checkRepeatBoard($db, $title, $contents) {

		$query = "SELECT MD5(CONCAT(REF_IP, TITLE, CONTENTS)) as prev_md5 FROM TBL_BOARD ORDER BY B_NO desc limit 1 ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		$curr_md5 = md5($_SERVER[REMOTE_ADDR].$title.$contents);

		if ($record == $curr_md5) {
			return true;
		} else {
			return false;
		}
	}

	function getBoardNextRe($db) {

		$query = "SELECT min(B_RE) as min_b_re FROM TBL_BOARD";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = (int)($rows[0]-1);

		return $record;

	}

	function listBoardMainDisp($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT *, datediff(NOW(), REG_DATE) AS BB_DATEDIFF,
						 (SELECT COUNT(FILE_NO) 
								FROM TBL_BOARD_FILE
							 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
								 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
								 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
				FROM TBL_BOARD WHERE MAIN_TF = 'Y' ";

		
		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = '".$search_str."' ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$query .= " ORDER BY REG_DATE desc ";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listBoardTop($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT *, datediff(NOW(), REG_DATE) AS BB_DATEDIFF,
						 (SELECT COUNT(FILE_NO) 
								FROM TBL_BOARD_FILE
							 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
								 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
								 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
				FROM TBL_BOARD WHERE TOP_TF = 'Y' ";

		
		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = '".$search_str."' ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$query .= " ORDER BY REG_DATE desc ";

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listBoard($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF,
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD WHERE 1 = 1 ";

		
		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			}elseif($search_field == "result") {
				$query .= " AND (TITLE like '%".$search_str."%') ";
			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = '".$search_str."' ";
			}else{
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//$query .= " ORDER BY B_RE, B_PO limit ".$offset.", ".$nRowCount;
		$query .= " ORDER BY REG_DATE DESC limit ".$offset.", ".$nRowCount;
		
		if ($_SERVER['REMOTE_ADDR'] == "121.128.140.144") {
			//echo $query;
		}

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntBoard($db, $b_code, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD WHERE 1 = 1 ";
		
		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		} else {
			$query .= " AND B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ') ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
			$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			}elseif($search_field == "result") {
				$query .= " AND (TITLE like '%".$search_str."%') ";
			}elseif($search_field == "WRITER_ID") {
				$query .= " AND WRITER_ID = '".$search_str."' ";
			}else{
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function selectPostBoard($db, $b_code, $b_po, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT B_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
								FROM TBL_BOARD WHERE BB_PO > '$bb_po' ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
	//		$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//$query .= " ORDER BY BB_PO limit 1";
		$query .= " ORDER BY REG_DATE DESC limit 1";
	//	echo $query;
				
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function selectPostBoardAsDate($db, $b_code, $bb_no, $reg_date, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
										 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
								FROM TBL_BOARD WHERE REG_DATE < '".$reg_date."' ";
								//FROM TBL_BOARD WHERE CONCAT(REG_DATE,BB_NO) < '".$reg_date.$bb_no."' ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
	//		$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//$query .= " ORDER BY BB_PO limit 1";
		$query .= " ORDER BY REG_DATE DESC limit 1";
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

	function selectPreBoard($db, $b_code, $bb_po, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, 
							 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
							 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
					FROM TBL_BOARD WHERE BB_PO < '$bb_po' ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
//			$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
								
		//$query .= " ORDER BY BB_PO DESC limit 1";
		$query .= " ORDER BY REG_DATE ASC limit 1";
		
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

	function selectPreBoardAsDate($db, $b_code, $bb_no, $reg_date, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, 
							 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
							 FILE_NM, FILE_RNM, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
					FROM TBL_BOARD WHERE REG_DATE > '".$reg_date."' ";
					//FROM TBL_BOARD WHERE CONCAT(REG_DATE, BB_NO) > '".$reg_date.$bb_no."' ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
//			$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
								
		//$query .= " ORDER BY BB_PO DESC limit 1";
		$query .= " ORDER BY REG_DATE ASC limit 1";
		
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

	function viewChkBoard($db, $b_code, $b_no) {
		
		$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function viewChkBoardAsAdmin($db, $b_code, $b_no, $adm_no) {
		
		$query="SELECT COUNT(*) AS CNT FROM TBL_BOARD_READ WHERE B_CODE = '$b_code' AND B_NO = '$b_no' AND ADM_NO = '$adm_no' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		if ($rows[0] == 0) {
			$query="INSERT INTO TBL_BOARD_READ (B_CODE, B_NO, ADM_NO, REG_DATE) VALUES ('$b_code', '$b_no', '$adm_no', now()) ";
			mysql_query($query,$db);

			$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";
			mysql_query($query,$db);
		}
	}

	function resetChkBoardAsAdmin($db, $b_code, $b_no) {
		
		$query="DELETE FROM TBL_BOARD_READ WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";
		mysql_query($query,$db);

	}

	function ChkBoardAsAdmin($db, $b_code, $b_no, $adm_no) {
		
		$query="SELECT COUNT(*) AS CNT FROM TBL_BOARD_READ WHERE B_CODE = '$b_code' AND B_NO = '$b_no' AND ADM_NO = '$adm_no' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] == 0) {
			return false;
		} else {
			return true;
		}
	}

	function viewChkBoardAsMember($db, $b_code, $bb_no, $member_id) {
		
		$query="SELECT COUNT(*) AS CNT FROM TBL_BOARD_READ_CNT WHERE B_CODE = '$b_code' AND BB_NO = '$bb_no' AND READ_MEMBER = '$member_id' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		if ($rows[0] == 0) {
			$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = '$b_code' AND BB_NO = '$bb_no' ";
			mysql_query($query,$db);

			$query="INSERT INTO TBL_BOARD_READ_CNT (B_CODE, BB_NO, READ_MEMBER, REG_DATE) VALUES ('$b_code', '$bb_no', '$member_id', now()) ";
			mysql_query($query,$db);
			
		}
	}

	function viewChkBoardAsIp($db, $b_code, $b_no, $ip) {
		
		$query="SELECT COUNT(B_NO) AS CNT FROM TBL_BOARD_READ_CNT_IP WHERE B_CODE = '$b_code' AND B_NO = '$b_no' AND IP = '$ip' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		if ($rows[0] == 0) {
			$query="INSERT INTO TBL_BOARD_READ_CNT_IP (B_CODE, B_NO, IP, REG_DATE) VALUES ('$b_code', '$b_no', '$ip', now()) ";
			mysql_query($query,$db);
			$query="UPDATE TBL_BOARD SET HIT_CNT = HIT_CNT + 1 WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";
			mysql_query($query,$db);
		}
	}

	function insertBoard($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";

		foreach ($arr_data as $key => $value) {
			if ($key == "REF_IP") $value = $_SERVER['REMOTE_ADDR'];
			$set_field .= $key.","; 
			$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_BOARD (".$set_field." UP_DATE) 
					values (".$set_value." now()); ";
		
		//echo $query;
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			$new_b_no = mysql_insert_id();
			

			$query = "UPDATE TBL_BOARD set PARENT_NO = '$new_b_no' where B_NO = '$new_b_no' ";
			mysql_query($query,$db);

			//deleteTemporarySave($db, $b_code, $writer_id);
			return $new_b_no;
		}
	}

	function insertBoardReply($db, $b_code, $bb_no, $bb_po, $bb_re, $bb_de, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $writer_nm, $writer_pw, $email, $homepage, $title, $ref_ip, $recomm, $contents, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $keyword, $comment_tf, $top_tf, $use_tf, $reg_adm) {
		
		
		$query = "SELECT BB_RE, BB_DE, BB_PO FROM TBL_BOARD WHERE B_CODE = '$b_code' AND BB_NO = '$bb_no' ";
		
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
		
		$bb_re = $row[0];
		$bb_de = $row[1];
		$bb_po = $row[2];
		$new_bb_de = $bb_de + 1;

		/*
		$query = "SELECT COUNT(BB_NO) AS CNT 
								FROM TBL_BOARD 
							 WHERE B_CODE = '$b_code' 
								 AND BB_RE = '$bb_re' 
								 AND BB_DE > '$bb_de' ";
		*/

		$query = "SELECT COUNT(BB_NO) AS CNT 
								FROM TBL_BOARD 
							 WHERE BB_RE = '$bb_re' 
								 AND BB_DE > '$bb_de' ";

		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);

		$plus_po = $row[0];

		$new_bb_po = $bb_po + $plus_po + 1;
		
		/*
		$query1 ="UPDATE TBL_BOARD SET BB_PO = BB_PO + 1 
							 WHERE B_CODE = '$b_code' 
								 AND BB_PO >= '$new_bb_po' ";
		*/
		$query1 ="UPDATE TBL_BOARD SET BB_PO = BB_PO + 1 
							 WHERE BB_PO >= '$new_bb_po' ";


		mysql_query($query1,$db);
		
		/*
		$query2 ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO 
								FROM TBL_BOARD 
							 WHERE B_CODE = '$b_code' ";
		*/
		$query2 ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO 
								FROM TBL_BOARD  ";

		$result2 = mysql_query($query2,$db);
		$rows2   = mysql_fetch_array($result2);

		$new_bb_no = $rows2[0] + 1;
		

		//위변조 체크
		if($_SERVER['REMOTE_ADDR']!=$ref_ip){
			$check_ip = $_SERVER['REMOTE_ADDR'];
			$query_ip="INSERT INTO TBL_BOARD_CHECK_IP (B_CODE, BB_NO, TITLE, REF_IP, REG_DATE) values ('$b_code', '$new_bb_no', '$title', '$check_ip',  now()); ";
			
			@mysql_query($query_ip,$db);
		}
		$ref_ip = $_SERVER['REMOTE_ADDR'];
		
		$query5="INSERT INTO TBL_BOARD (B_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, 
							 CONTENTS, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, COMMENT_TF, TOP_TF, USE_TF, REG_ADM, REG_DATE) 
				values ('$b_code', '$cate_01', '$cate_02', '$cate_03', '$cate_04', '$new_bb_no', '$new_bb_po', '$bb_re', '$new_bb_de', '$writer_id', '$writer_nm', '$writer_pw', 
								'$email', '$homepage', '$title', '0', '$ref_ip', '$recomm', '$contents', '$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', 
								'$keyword', '$comment_tf', '$top_tf,', '$use_tf', '$reg_adm', now()); ";
		


		if(!mysql_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			deleteTemporarySave($db, $b_code, $writer_id);
			return $new_bb_no;
		}
	}


	function selectBoard($db, $b_code, $b_no) {

		$query = "SELECT * FROM TBL_BOARD WHERE  B_CODE = '$b_code' AND  B_NO = '$b_no' ";
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

	function updateBoard($db, $arr_data, $b_code, $b_no) {

		foreach ($arr_data as $key => $value) {
			if ($key == "CATE_01") $CATE_01 = $value;
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_BOARD SET ".$set_query_str. "
							UP_DATE				=	now()
				WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";

		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			// 분류가 수정되는 경우 해당되는 코멘트의 분류명도 모두 수정함
			// 코멘트의 분류를 수정하지 않으면 검색이 제대로 되지 않음
			$query = " UPDATE TBL_BOARD SET CATE_01 = '$CATE_01' WHERE PARENT_NO = '$b_no' ";
			mysql_query($query,$db);

			return true;
		}
	}

	/*
	===============================================================================================================================
	추천수 관련
	===============================================================================================================================
	*/
	
	function totalCntBoardRecomm($db, $gubun, $b_code, $bb_no){

		if($gubun=="RECOMM"){
			$query ="SELECT RECOMM FROM TBL_BOARD WHERE B_CODE = '$b_code' AND B_NO = '$bb_no' ";
		}else if($gubun=="RECOMMNO"){
			$query ="SELECT RECOMM FROM TBL_BOARD WHERE B_CODE = '$b_code' AND B_NO = '$bb_no' ";
		}		

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}


	function updateBoardRecomm($db, $gubun, $b_code, $b_no) {


		if($gubun=="RECOMM"){
			$query="UPDATE TBL_BOARD SET RECOMM					= RECOMM + 1
					 WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";
		}else if($gubun=="RECOMMNO"){
			$query="UPDATE TBL_BOARD SET RECOMMNO					= RECOMMNO + 1
					 WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";
		}
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBoardUseTF($db, $use_tf, $up_adm, $b_code, $bb_no) {
		
		$query="UPDATE TBL_BOARD SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE B_CODE = '$b_code' AND BB_NO = '$bb_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateBoardConfirmTF($db, $confirm_tf, $up_adm, $b_code, $bb_no) {
		
		$query="UPDATE TBL_BOARD SET 
							REPLY_STATE					= '$confirm_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE B_CODE = '$b_code' AND BB_NO = '$bb_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateQnaAnswer($db, $reply, $reply_adm, $reply_state, $b_code, $bb_no) {

		$query = "UPDATE TBL_BOARD SET 
						REPLY				=	'$reply',
						REPLY_ADM		=	'$reply_adm',
						REPLY_STATE	=	'$reply_state',
						REPLY_DATE	=	now()
				WHERE B_CODE = '$b_code' AND BB_NO = '$bb_no' ";
		
		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function deleteBoardTF($db, $del_tf, $del_adm, $b_code, $bb_no) {

		

		$query = "UPDATE TBL_BOARD SET
						 DEL_TF				= '$del_tf',
						UP_ADM					= '$del_adm',
						UP_DATE					= now()
					WHERE B_CODE				= '$b_code' 
						AND BB_NO					= '$bb_no' ";
		
		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteBoard($db, $del_adm, $b_code, $b_no) {

		$query =  "SELECT B_PO, B_RE, COMMENT_CNT FROM TBL_BOARD 
							  WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND B_CODE	= '$b_code' 
									AND B_NO		= '$b_no' ";
		
		$result = mysql_query($query);
		$list = mysql_fetch_array($result);
		
		$spo					= $list[B_PO];
		$sre					= $list[B_RE];
		$scomment_cnt	= $list[COMMENT_CNT];

		$len = strlen($spo);
		if ($len < 0) $len = 0; 
		$reply = substr($spo, 0, $len);


		// 원글만 구한다.
		$query = "SELECT count(*) as cnt from TBL_BOARD
							 WHERE B_PO like '$reply%'
								 AND USE_TF = 'Y' 
								 AND DEL_TF = 'N'
								 AND B_NO <> '$b_no'
								 AND B_RE = '$sre'
								 AND COMMENT_CNT = 0 ";
		
		//echo $query;


		$result = mysql_query($query);
		$list		= mysql_fetch_array($result);
		$re_flag = $list[cnt];
		
		$del_flag = "Y";

		if (($re_flag) || ($scomment_cnt)) {
			$del_flag = "N";
		}

		//echo $del_flag;

		//exit;

		//alert("이 글과 관련된 답변글이 존재하므로 삭제 할 수 없습니다.\\n\\n우선 답변글부터 삭제하여 주십시오.");

		$query = "DELETE FROM TBL_BOARD_FILE 
							 WHERE B_CODE	= '$b_code' 
								 AND B_NO		= '$b_no' ";

		mysql_query($query,$db);

		if ($del_flag == "N") { 
			
			$query = "UPDATE TBL_BOARD SET 
									THUMB_IMG = '',
									TITLE = '작성자 또는 관리자에 의해 삭제 되었습니다.', 
									CONTENTS = '답변글이 남아 있어 내용만 삭제 되었습니다.'
								WHERE B_CODE	= '$b_code' 
									AND B_NO		= '$b_no' ";
		} else {

			$query = "UPDATE TBL_BOARD SET
							 DEL_TF				= 'Y',
							 DEL_ADM			= '$del_adm',
							 DEL_DATE			= now()
						WHERE B_CODE		= '$b_code' 
							AND B_NO			= '$b_no' ";
		}

		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}



	function listBoardConfig($db, $site_no, $board_code, $board_type, $board_cate, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBoardConfig($db, $site_no, $board_code, $board_type, $board_cate, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, B.CONFIG_NO, B.SITE_NO, B.BOARD_NM, B.BOARD_CODE, B.BOARD_TYPE, B.BOARD_CATE, B.BOARD_GROUP, B.LIST_GROUP, B.READ_GROUP, B.WRITE_GROUP, 
										 B.REPLY_GROUP, B.COMMENT_GROUP, B.LINK_GROUP, B.UPLOAD_GROUP, B.DOWNLOAD_GROUP, B.SECRET_TF, B.SEARCH_TF, B.LIKE_TF, B.UNLIKE_TF, 
										 B.REALNAME_TF, B.IP_TF, B.COMMENT_TF, B.REPLY_TF, B.HTML_TF, B.FILE_TF, B.FILE_CNT, 
										 B.MAX_TITLE, B.NEW_HOUR, B.HOT_CNT, B.BOARD_MEMO, B.BOARD_BADWORD, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE
								FROM TBL_BOARD_CONFIG B WHERE 1 = 1 ";

		if ($site_no <> "") {
			$query .= " AND B.SITE_NO = '".$site_no."' ";
		}

		if ($board_code <> "") {
			$query .= " AND B.BOARD_CODE = '".$board_code."' ";
		}

		if ($board_type <> "") {
			$query .= " AND B.BOARD_TYPE = '".$board_type."' ";
		}

		if ($board_cate <> "") {
			$query .= " AND B.BOARD_CATE = '".$board_cate."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY B.REG_DATE desc limit ".$offset.", ".$nRowCount;
		
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


	function totalCntBoardConfig($db, $site_no, $board_code, $board_type, $board_cate, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_CONFIG B WHERE 1 = 1 ";
		
		if ($site_no <> "") {
			$query .= " AND B.SITE_NO = '".$site_no."' ";
		}

		if ($board_code <> "") {
			$query .= " AND B.BOARD_CODE = '".$board_code."' ";
		}

		if ($board_type <> "") {
			$query .= " AND B.BOARD_TYPE = '".$board_type."' ";
		}

		if ($board_cate <> "") {
			$query .= " AND B.BOARD_CATE = '".$board_cate."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
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

	function insertBoardConfig($db, $site_no, $arr_data, $menu_right) {
		
		$query ="SELECT IFNULL(MAX(CONFIG_NO),0) AS MAX_NO FROM TBL_BOARD_CONFIG WHERE SITE_NO = '$site_no' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] <> 0) {
			$new_config_no	= $rows[0] + 1;
		} else {		
			$new_config_no	= "1";
		}

		$new_board_code	= "B_".$site_no."_".$new_config_no;
		
		$set_field = "";
		$set_value = "";

		foreach ($arr_data as $key => $value) {
			if ($key == "BOARD_NM") $board_nm = $value;
			if ($key == "BOARD_CODE") $value = $new_board_code;
			if ($key == "REG_ADM") $reg_adm = $value;
			$set_field .= $key.","; 
			$set_value .= "'".$value."',"; 
		}

		$query5="INSERT INTO TBL_BOARD_CONFIG (CONFIG_NO, ".$set_field." REG_DATE) 
					values ('$new_config_no', ".$set_value." now()); ";

		//echo $query5;

		//exit;

		if(!mysql_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			// 관리자 메뉴에 추가 된 게시판을 추가 한다.
			//echo "menu_right-->".$menu_right."<br>";

			$query = "SELECT substring(MENU_CD,1,2) AS MENU_CD FROM TBL_ADMIN_MENU WHERE MENU_RIGHT = '$menu_right'";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$MENU_CD  = $rows[0];

			$query = "SELECT MENU_SEQ01,MENU_SEQ02 FROM TBL_ADMIN_MENU WHERE MENU_CD = '".$MENU_CD."'";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$MENU_SEQ01  = $rows[0];
			$MENU_SEQ02  = $rows[1];

			$menu_url = "/manager/board/board_list.php?b_code=".$new_board_code;

			//echo $MENU_CD."<br>";
			//echo $MENU_SEQ01."<br>";
			//echo $MENU_SEQ02."<br>";

			//exit;

			$result = insertAdminMenu($db, $MENU_CD, $MENU_SEQ01, $MENU_SEQ02, $board_nm, $menu_url, "Y", $new_board_code, $menu_img, $menu_img_over, "Y", $reg_adm);

			return true;
		}
	}

	function updateBoardConfig($db, $site_no, $arr_data, $config_no) {
		
		$new_board_code	= "B_".$site_no."_".$config_no;

		$set_query_str = "";

		foreach ($arr_data as $key => $value) {
			if ($key == "BOARD_CODE") $value = $new_board_code;
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_BOARD_CONFIG SET ".$set_query_str. "
							UP_DATE				=	now()
				WHERE SITE_NO		=	'$site_no' AND CONFIG_NO = '$config_no' ";
		
		//echo $query."<br>";

		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectBoardConfig($db, $site_no, $config_no) {

		$query = "SELECT *
								FROM TBL_BOARD_CONFIG
							 WHERE SITE_NO		=	'$site_no' 
								 AND CONFIG_NO = '$config_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();
			
		//echo $query."<br>";

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function deleteBoardConfig($db, $del_adm, $site_no, $config_no) {

		$query="UPDATE TBL_BOARD_CONFIG SET 
														 DEL_TF				= 'Y',
														 DEL_ADM			= '$del_adm',
														 DEL_DATE			= now()														 
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateBoardConfigUseTF($db, $use_tf, $up_adm, $site_no, $config_no) {
		
		$query="UPDATE TBL_BOARD_CONFIG SET 
													USE_TF					= '$use_tf',
													UP_ADM					= '$up_adm',
													UP_DATE					= now()
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBoardConfigRealTF($db, $real_tf, $up_adm, $site_no, $config_no) {
		
		$query="UPDATE TBL_BOARD_CONFIG SET 
													REAL_TF					= '$real_tf',
													UP_ADM					= '$up_adm',
													UP_DATE					= now()
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listBoardFile($db, $b_code, $b_no) {

		$query = "SELECT FILE_NO, B_CODE, B_NO,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT,
										 DEL_TF, REG_ADM, REG_DATE, DEL_ADM, DEL_DATE, wr_id, bo_table
								FROM TBL_BOARD_FILE WHERE 1 = 1 
								 AND B_CODE = '".$b_code."' 
								AND B_NO = '".$b_no."' ";

		$query .= " ORDER BY REG_DATE desc ";

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertBoardFile($db, $b_code, $b_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $reg_adm) {
				
		$query = "INSERT INTO TBL_BOARD_FILE (B_CODE, B_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT, REG_ADM, REG_DATE) 
														values ('$b_code','$b_no', '$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', '0', '$reg_adm', now()); ";
		//echo $query;
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteBoardFile($db, $file_no) {
				
		$query = "DELETE FROM TBL_BOARD_FILE WHERE FILE_NO = '$file_no'";
		//echo $query;
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBoardFileHitCnt($db, $file_no) {
				
		$query = "UPDATE TBL_BOARD_FILE SET HIT_CNT = HIT_CNT + 1 WHERE FILE_NO = '$file_no'";
		//echo $query;
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function selectBoardFile($db, $file_no) {

		$query = "SELECT FILE_NO, B_CODE, B_NO,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT,
										 DEL_TF, REG_ADM, REG_DATE, DEL_ADM, DEL_DATE, bo_table
								FROM TBL_BOARD_FILE WHERE 1 = 1 
								 AND FILE_NO = '".$file_no."' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function getReplyCount($db, $b_code, $b_no) {

		$query = "SELECT COUNT(B_NO) AS CNT FROM TBL_BOARD_COMMENT WHERE B_NO = '$b_no' AND DEL_TF = 'N' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}



	function B_CODE_NAME($db, $b_code){

		$query ="SELECT BOARD_NM FROM TBL_BOARD_CONFIG WHERE BOARD_CODE = '$b_code' ";
		
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listBoardMain($db, $b_code, $cate_01, $use_tf, $del_tf, $nCnt) {


		$query = "SELECT B_CODE, B_NO, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 FILE_NM, FILE_RNM, THUMB_IMG, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD WHERE 1 = 1 ";

		
		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		//$query .= " ORDER BY TOP_TF desc, BB_PO asc limit ".$nCnt;
		$query .= " ORDER BY TOP_TF desc, REG_DATE desc limit ".$nCnt;

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function moveBoard($db, $pre_b_code, $next_b_code, $b_no) {
		
		$query = "UPDATE TBL_BOARD_READ_CNT_IP SET B_CODE = '$next_b_code' WHERE B_NO = '$b_no' ";
		mysql_query($query,$db);
		$query = "UPDATE TBL_BOARD_FILE SET B_CODE = '$next_b_code' WHERE B_NO = '$b_no' ";
		mysql_query($query,$db);
		$query = "UPDATE TBL_BOARD SET B_CODE = '$next_b_code' WHERE B_NO = '$b_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
		
	}


	function copyBoard($db, $pre_b_code, $next_b_code, $b_no) {
		
		$b_re = getBoardNextRe($db);
		$b_po = "";

		$query = "INSERT INTO TBL_BOARD (B_CODE, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, 
													WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, PHONE, HOMEPAGE, TITLE, REF_IP, FILE_CNT, CONTENTS, 
													THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, LINK01, LINK02, SECRET_TF, TOP_TF, USE_TF, DEL_TF, REG_DATE, wr_id, bo_table, THUMB_IMG_CHK)
							SELECT '".$next_b_code."', '".$b_po."','".$b_re."', CATE_01, CATE_02, CATE_03, CATE_04, 
													WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, PHONE, HOMEPAGE, TITLE, REF_IP, FILE_CNT, CONTENTS, 
													THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, LINK01, LINK02, SECRET_TF, TOP_TF, USE_TF, DEL_TF, REG_DATE, wr_id, bo_table, THUMB_IMG_CHK
										 FROM TBL_BOARD
										WHERE B_NO = '$b_no' ";
		
		//echo $query;
		//exit;

		mysql_query($query,$db);

		$new_b_no = mysql_insert_id();
		
		$query = "INSERT INTO TBL_BOARD_FILE (B_CODE, B_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, DEL_TF, REG_DATE, wr_id, bo_table)
								SELECT '".$next_b_code."','".$new_b_no."',FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, DEL_TF, REG_DATE, wr_id, bo_table
									FROM TBL_BOARD_FILE 
								 WHERE B_NO = '$b_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			$query = "UPDATE TBL_BOARD set PARENT_NO = '$new_b_no' where B_NO = '$new_b_no' ";
			mysql_query($query,$db);
			return true;
		}
	}


	function getAllBoardCnt($db, $search_str) {
	
		$query = "SELECT C.BOARD_NM, C.BOARD_CODE, IFNULL(BB.CNT,0) AS CNT
								FROM TBL_BOARD_CONFIG C LEFT OUTER JOIN 
										(SELECT COUNT(BB_NO) AS CNT, B_CODE 
											 FROM TBL_BOARD B 
											WHERE B.USE_TF = 'Y'
												AND B.DEL_TF ='N' 
												AND B.B_CODE IN (SELECT BOARD_CODE FROM TBL_BOARD_CONFIG WHERE DEL_TF = 'N' AND BOARD_TYPE <> 'FAQ')
												AND ((B.CONTENTS like '%".$search_str."%') or (B.TITLE like '%".$search_str."%') or (B.WRITER_NM like '%".$search_str."%'))
												GROUP BY B_CODE
										) BB ON C.BOARD_CODE = BB.B_CODE
							WHERE C.DEL_TF = 'N'
								AND C.BOARD_TYPE <> 'FAQ'
							ORDER BY C.CONFIG_NO ASC ";
		
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

	function getBoardName($db, $b_code) {

		$query = "SELECT BOARD_NM FROM TBL_BOARD_CONFIG WHERE BOARD_CODE = '$b_code' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function getBoardNameAsPageNo($db, $page_no) {

		$query = "SELECT PAGE_NAME FROM TBL_PAGES WHERE PAGE_NO = '$page_no' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function countRecom($db, $b_code,$b_no){
		$query = "SELECT count(distinct WRITER_ID) FROM TBL_RECOM WHERE B_CODE = '$b_code' and B_NO='$b_no' and RECOM_TF='Y'";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	
	function countNRecom($db, $b_code,$b_no){
		
		$query = "SELECT count(distinct WRITER_ID) FROM TBL_RECOM WHERE B_CODE = '$b_code' and B_NO='$b_no' and RECOM_TF='N'";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}


	function selectRecom($db, $b_code, $bb_no, $write_id) {

		$query = "SELECT count(seq_no) FROM TBL_RECOM WHERE B_CODE = '$b_code' AND  BB_NO = '$bb_no' and WRITER_ID='$write_id'";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}
	

	function insertRecom($db, $b_code, $b_no, $writer_id, $re_tf) {
		
		$ref_ip = $_SERVER['REMOTE_ADDR'];

		$query5="INSERT INTO TBL_RECOM (B_CODE, B_NO, WRITER_ID, RECOM_TF, REG_DATE, REF_IP) 
														values ('$b_code', '$b_no', '$writer_id', '$re_tf', now(), '$ref_ip'); ";
		
		if(!mysql_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return "Y";
		}
	}


	function listConfigBoard($db) {

		$query = "SELECT BOARD_CODE, BOARD_TYPE,BOARD_NM FROM TBL_BOARD_CONFIG WHERE 1 = 1 ";
		$query .= " AND BOARD_TYPE != 'QNA' ";
		$query .= " AND BOARD_TYPE != 'FAQ' ";
		$query .= " AND USE_TF = 'Y' ";
		$query .= " AND DEL_TF = 'N' ";
		$query .= " ORDER BY CONFIG_NO  asc";
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function MediaMain($db, $b_code, $cate_01, $use_tf, $del_tf, $nCnt) {

		$query = "SELECT B_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BOARD WHERE 1 = 1 ";

		$query .= " AND (B_CODE = 'GRBBS_1_17' or B_CODE = 'GRBBS_1_18')";


		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		$query .= " ORDER BY TOP_TF desc, REG_DATE DESC limit 1,".$nCnt;
		
		//$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function MediaTopMain($db, $b_code) {

		$query = "SELECT B_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, 
							 HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS, 
							 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, TOP_TF,
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, REF_IP
					FROM TBL_BOARD WHERE TOP_TF='Y'";

		$query .= " AND (B_CODE = 'GRBBS_1_17' or B_CODE = 'GRBBS_1_18')";
		$query .= " ORDER BY REG_DATE desc limit 0,1";

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


/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//도배 방지 
*/

	function checkUserWriteTime($db, $writer_id, $min) {

		$query = "SELECT COUNT(BB_NO) AS CNT FROM TBL_BOARD WHERE REG_DATE > TIMESTAMPADD(MINUTE, -".$min.", NOW()) AND WRITER_ID = '".$writer_id."' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		if ($record == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function systemDelBoard($db, $writer_id) {
		
		if ($writer_id) {
			$query = "SELECT COUNT(BB_NO) AS CNT 
									FROM TBL_BOARD 
								 WHERE B_CODE IN ('GRBBS_1_7','GRBBS_1_6') 
								   AND REG_DATE > TIMESTAMPADD(MINUTE, -15, NOW()) AND WRITER_ID = '".$writer_id."' ";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$record  = $rows[0];

			if ($record > 10) {
				$query = "UPDATE TBL_BOARD SET DEL_TF = 'Y', CATE_04 = 'system 자동 삭제' WHERE B_CODE IN ('GRBBS_1_7','GRBBS_1_6') 
								   AND REG_DATE > TIMESTAMPADD(MINUTE, -15, NOW()) AND WRITER_ID = '".$writer_id."' ";
				mysql_query($query,$db);

				$query = "UPDATE TBL_MEMBER SET USE_TF = 'N' WHERE MEM_ID = '".$writer_id."' ";
				mysql_query($query,$db);
			}
		}
	}

	function checkUserWrite($db, $writer_id) {
		
		if ($writer_id) {

			$query = "SELECT COUNT(MEM_ID) FROM TBL_MEMBER  WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND MEM_ID = '".$writer_id."' ";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$record  = $rows[0];
			
			if($record) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//금지어체크
	function checkUserWriteBadWord($check_words, $in_words) {
		
		$ret=true;//금지어가 없음
	
		$b_board_badword_arr		= explode(",",$check_words);
		
		for($bi=0;$bi<count($b_board_badword_arr);$bi++){
			if($bi>0)$b_board_badword2		.= ",";
				
			if (trim($b_board_badword_arr[$bi]) <> "") {
				$b_board_badword2		.= '"'.trim($b_board_badword_arr[$bi]).'"';
				$pos = strpos($in_words, trim($b_board_badword_arr[$bi]));

				if ($pos === false) {
					$ret=true;//금지어가 없음
				} else {
					$ret=false;//금지어가 있음
					break;
				}
			}
		}
		
		return $ret;
		
	}
/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//도배 방지 끝
*/






/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//임시 저장용 관련
*/


	function totalCntTemporarySave($db, $b_code, $writer_id, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_TEMPORARY  WHERE 1 = 1 ";

		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ( (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') ) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		#echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listTemporarySave($db, $b_code, $writer_id, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntPdf($db, $use_tf, $del_tf, $search_field, $search_str);
		
		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, TEMP_NO, B_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE, UP_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
								FROM TBL_BOARD_TEMPORARY WHERE 1 = 1 ";

		
		if ($b_code <> "") {
			$query .= " AND B_CODE = '".$b_code."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ( (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') ) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$query .= " ORDER BY SEQ_NO desc limit ".$offset.", ".$nRowCount;
		
		//$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	

	function insertTemporarySave($db, $b_code, $writer_id, $title, $contents) {
		
		$ret_sel = selectTemporarySave($db, $b_code, $writer_id);
		if($ret_sel && $ret_sel[0]["TEMP_NO"]){
			return updateTemporarySave($db, $b_code, $writer_id, $title, $contents,$ret_sel[0]["TEMP_NO"]);
		}else{

			$query="INSERT INTO TBL_BOARD_TEMPORARY (B_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE) 
			values ('$b_code', '$writer_id', '$title', '$contents',  now()); ";

			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			} else {
				return 1;
			}
		}

	}


	function selectTemporarySave4no($db, $seq_no) {

		$query = "SELECT TEMP_NO, B_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE, UP_DATE
					FROM TBL_BOARD_TEMPORARY WHERE  TEMP_NO = '$seq_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function selectTemporarySave($db, $b_code, $writer_id) {

		$query = "SELECT TEMP_NO, B_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE, UP_DATE
					FROM TBL_BOARD_TEMPORARY WHERE  B_CODE = '$b_code' and WRITER_ID = '$writer_id' ";

		#echo $query."<br>";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}



	function updateTemporarySave($db, $b_code, $writer_id, $title, $contents, $seq_no) {

		$query = "UPDATE TBL_BOARD_TEMPORARY SET 
						TITLE					=	'$title',
						CONTENTS				=	'$contents',
						UP_DATE					=	now()
				 WHERE TEMP_NO = '$seq_no' ";
		
		#echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}



	function deleteTemporarySave4no($db, $seq_no) {
				
		$query = "DELETE FROM TBL_BOARD_TEMPORARY WHERE TEMP_NO = '$seq_no'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	function deleteTemporarySave($db, $b_code, $writer_id) {
				
		$query = "DELETE FROM TBL_BOARD_TEMPORARY WHERE B_CODE = '$b_code' and WRITER_ID = '$writer_id'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	

/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//임시 저장용 관련 끝
*/

/*
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 썸네일 이미지 등록 하는 기능.
*/

	function updateThumbnailImg($db, $b_code, $b_no, $file_name) {

		$query = "UPDATE TBL_BOARD SET 
							THUMB_IMG	=	'$file_name'
							WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";
		
		#echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOldBoardThumbnailImg($db, $b_code, $b_no, $file_name) {

		$query = "UPDATE TBL_BOARD SET 
							THUMB_IMG	=	'$file_name',
							THUMB_IMG_CHK	=	'Y'
							WHERE B_CODE = '$b_code' AND B_NO = '$b_no' ";
		
		#echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

/*
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 썸네일 리스트
*/
	function listThumbImg($db, $b_code, $displaycnt) {

		$query = "SELECT B_CODE, B_NO, THUMB_IMG, TITLE
							FROM TBL_BOARD 
						 WHERE B_CODE = '$b_code'
							 AND THUMB_IMG <> ''
							 AND USE_TF = 'Y'
							 AND DEL_TF = 'N'
						 ORDER BY REG_DATE DESC limit 0, ".$displaycnt;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}


	function getMainBoardList($db, $b_code, $cnt) {

		$query= "SELECT * FROM TBL_BOARD 
							WHERE B_CODE = '$b_code' 
								AND USE_TF = 'Y' 
								AND DEL_TF = 'N' 
								AND B_PO = '' ORDER BY REG_DATE DESC limit 0,".$cnt;
		
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

	function getMainMoSmsList($db, $cnt) {
		$query= "SELECT * FROM TBL_MOSMS
							WHERE USE_TF = 'Y' 
								AND DEL_TF = 'N' 
								AND FILE_NM <> ''
								ORDER BY SEQ_NO DESC limit 0,".$cnt;
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function listAllBoard($db, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, A.B_CODE, A.B_NO, A.CATE_01, A.TITLE, B.BOARD_GROUP, B.BOARD_TYPE, A.REG_DATE
								FROM TBL_BOARD A, TBL_BOARD_CONFIG B
							 WHERE A.B_CODE = B.BOARD_CODE
								 AND B.SEARCH_TF = 'Y'
								 AND B.USE_TF = 'Y'
								 AND B.DEL_TF = 'N'
								 AND A.USE_TF = 'Y'
								 AND A.DEL_TF = 'N'
								 AND A.SECRET_TF <> 'Y' ";

		if ($search_str <> "") {
			$query .= " AND ((WRITER_NICK like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (KEYWORD like '%".$search_str."%')) ";
		}

		$query .= " ORDER BY A.REG_DATE DESC limit ".$offset.", ".$nRowCount;

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


	function totalCntAllBoard($db, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD A, TBL_BOARD_CONFIG B
							 WHERE A.B_CODE = B.BOARD_CODE
								 AND B.SEARCH_TF = 'Y'
								 AND B.USE_TF = 'Y'
								 AND B.DEL_TF = 'N'
								 AND A.USE_TF = 'Y'
								 AND A.DEL_TF = 'N'
								 AND A.SECRET_TF <> 'Y' ";

		if ($search_str <> "") {
			$query .= " AND ((WRITER_NICK like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (KEYWORD like '%".$search_str."%')) ";
		}
	//	echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectMainBoard($db, $seq_no) {
		$query = "SELECT * FROM TBL_MAIN_BOARD_LIST WHERE SEQ_NO = '$seq_no' ";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function updateMainBoard($db, $b_code_01, $b_code_02, $b_code_03, $b_code_04, $b_code_05, $b_code_06, $b_code_07, $b_code_08, $b_code_09, $b_code_10, $b_code_11, $b_code_12, $b_code_13, $b_code_14, $b_code_15, $seq_no) {

		$query = "UPDATE TBL_MAIN_BOARD_LIST SET 
							B_CODE_01	=	'$b_code_01',
							B_CODE_02	=	'$b_code_02',
							B_CODE_03	=	'$b_code_03',
							B_CODE_04	=	'$b_code_04',
							B_CODE_05	=	'$b_code_05',
							B_CODE_06	=	'$b_code_06',
							B_CODE_07	=	'$b_code_07',
							B_CODE_08	=	'$b_code_08',
							B_CODE_09	=	'$b_code_09',
							B_CODE_10	=	'$b_code_10',
							B_CODE_11	=	'$b_code_11',
							B_CODE_12	=	'$b_code_12',
							B_CODE_13	=	'$b_code_13',
							B_CODE_14	=	'$b_code_14',
							B_CODE_15	=	'$b_code_15'
							WHERE SEQ_NO = '$seq_no' ";
		
		#echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
?>