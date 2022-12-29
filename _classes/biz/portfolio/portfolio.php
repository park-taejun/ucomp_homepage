<?

	function selectPortfolio($db, $p_no) {

		$query = "SELECT *
								FROM TBL_PORTFOLIO WHERE P_NO = '$p_no' ";
		
		// echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listPortfolio($db, $p_yyyy, $p_mm, $p_category, $p_type, $top_tf, $main_tf, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT B.*, @rownum:= @rownum - 1  as rn, CONCAT(P_YYYY,P_MM,P_NO) as SEQ,
						I.ADM_NAME AS REG_NAME, A.ADM_NAME AS UP_NAME
				FROM TBL_PORTFOLIO B
					LEFT JOIN TBL_ADMIN_INFO I ON B.REG_ADM = I.ADM_NO 
					LEFT JOIN TBL_ADMIN_INFO A ON B.UP_ADM = A.ADM_NO 
				WHERE 1 = 1 AND ( B.USE_TF <> 'N' OR B.DEL_TF <> 'N' ) ";
		
		
		
		if ($p_yyyy <> "") {
			$query .= " AND B.P_YYYY = '".$p_yyyy."' ";
		}

		if ($p_mm <> "") {
			$query .= " AND B.P_MM = '".$p_mm."' ";
		}

		if ($p_category <> "") {
			$query .= " AND B.P_CATEGORY like '%|".$p_category."|%' ";
		}

		if ($p_type <> "") {
			$query .= " AND B.P_TYPE like '%|".$p_type."|%' ";
		}
		
		/*
			if ($top_tf <> "") {
				$query .= " AND B.TOP_TF = '".$top_tf."' ";
			}
		*/
		
		if ($main_tf <> "") {
			$query .= " AND B.MAIN_TF = '".$main_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";  
		}
/*
		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}
*/
		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY SEQ desc limit ".$offset.", ".$nRowCount;
		
		// Echo "query : " .$query. "<br />";  
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntPortfolio($db, $p_yyyy, $p_mm, $p_category, $p_type, $top_tf, $main_tf, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_PORTFOLIO WHERE 1 = 1 AND ( USE_TF <> 'N' OR DEL_TF <> 'N' )";
		
		if ($p_yyyy <> "") {
			$query .= " AND P_YYYY = '".$p_yyyy."' ";
		}

		if ($p_mm <> "") {
			$query .= " AND P_MM = '".$p_mm."' ";
		}

		if ($p_category <> "") {
			$query .= " AND P_CATEGORY like '%|".$p_category."|%' ";
		}

		if ($p_type <> "") {
			$query .= " AND P_TYPE like '%|".$p_type."|%' ";
		}

		if ($top_tf <> "") {
			$query .= " AND TOP_TF = '".$top_tf."' ";
		}

		if ($main_tf <> "") {
			$query .= " AND MAIN_TF = '".$main_tf."' ";
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

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectPostPortfolio($db, $p_no, $p_yyyy, $p_mm, $p_category, $p_type, $top_tf, $main_tf, $use_tf, $del_tf, $search_field, $search_str) {
		

		$query = "SELECT *, CONCAT(P_YYYY,P_MM,P_NO) as SEQ
								FROM TBL_PORTFOLIO WHERE CONCAT(P_YYYY,P_MM,P_NO) < '".($p_yyyy.$p_mm.$p_no)."' ";
								//FROM TBL_BOARD WHERE CONCAT(REG_DATE,BB_NO) < '".$reg_date.$bb_no."' ";

		//if ($p_yyyy <> "") {
		//	$query .= " AND P_YYYY = '".$p_yyyy."' ";
		//}

		//if ($p_mm <> "") {
		//	$query .= " AND P_MM = '".$p_mm."' ";
		//}

		if ($p_category <> "") {
			$query .= " AND P_CATEGORY like '%|".$p_category."|%' ";
		}

		if ($p_type <> "") {
			$query .= " AND P_TYPE like '%|".$p_type."|%' ";
		}

		if ($top_tf <> "") {
			$query .= " AND TOP_TF = '".$top_tf."' ";
		}

		if ($main_tf <> "") {
			$query .= " AND MAIN_TF = '".$main_tf."' ";
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

		$query .= " ORDER BY SEQ DESC limit 1";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function selectPrePortfolio($db, $p_no, $p_yyyy, $p_mm, $p_category, $p_type, $top_tf, $main_tf, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT *, CONCAT(P_YYYY,P_MM,P_NO) as SEQ
								FROM TBL_PORTFOLIO WHERE CONCAT(P_YYYY,P_MM,P_NO) > '".($p_yyyy.$p_mm.$p_no)."' ";

		//if ($p_yyyy <> "") {
		//	$query .= " AND P_YYYY = '".$p_yyyy."' ";
		//}

		//if ($p_mm <> "") {
		//	$query .= " AND P_MM = '".$p_mm."' ";
		//}

		if ($p_category <> "") {
			$query .= " AND P_CATEGORY like '%|".$p_category."|%' ";
		}

		if ($p_type <> "") {
			$query .= " AND P_TYPE like '%|".$p_type."|%' ";
		}

		if ($top_tf <> "") {
			$query .= " AND TOP_TF = '".$top_tf."' ";
		}

		if ($main_tf <> "") {
			$query .= " AND MAIN_TF = '".$main_tf."' ";
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

		$query .= " ORDER BY SEQ ASC limit 1";
		
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


	function insertPortfolio($db, $arr_data) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
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
			}
		}

		$query = "INSERT INTO TBL_PORTFOLIO (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			
			$query = "SELECT last_insert_id()";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$new_p_no  = $rows[0];
			return $new_p_no;

		}
	}

	function updatePortfolio($db, $arr_data, $p_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_PORTFOLIO SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "P_NO = '$p_no' WHERE P_NO = '$p_no' ";

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

	function deletePortfolio($db, $del_adm, $p_no) {

		$query="UPDATE TBL_PORTFOLIO SET 
									 USE_TF 			= 'N',  
									 DEL_ADM			= '$del_adm',
									 DEL_TF				= 'N',
									 DEL_DATE			= now()
						 WHERE P_NO = '$p_no' ";
 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

	function listPortfolioFile($db, $p_no) {

		$query = "SELECT *
								FROM TBL_PORTFOLIO_FILE WHERE 1 = 1
								 AND DEL_TF = 'N'
								 AND P_NO = '".$p_no."' ";

		$query .= " ORDER BY FILE_NO ASC ";
		
		// echo "query : " .$query. "<br />";
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertPortfolioFile($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_PORTFOLIO_FILE (".$set_field.") 
					values (".$set_value."); ";
		
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

	function deletePortfolioFile($db, $p_no) {
				
		$query = "UPDATE TBL_PORTFOLIO_FILE SET DEL_TF = 'Y', DEL_DATE = now() WHERE P_NO = '$p_no'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectPortfolioFile($db, $file_no) {

		$query = "SELECT *
								FROM TBL_PORTFOLIO_FILE WHERE 1 = 1 
								 AND DEL_TF = 'N'
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


	function selectPortfolioPrize($db, $prize_no) {

		$query = "SELECT *
								FROM TBL_PORTFOLIO_PRIZE WHERE 1 = 1 
								 AND DEL_TF = 'N'
								 AND PRIZE_NO = '".$prize_no."' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listPortfolioPrize($db, $p_no) {

		$query = "SELECT *
								FROM TBL_PORTFOLIO_PRIZE WHERE 1 = 1
								 AND DEL_TF = 'N'
								 AND P_NO = '".$p_no."' ";

		$query .= " ORDER BY PRIZE_NO ASC ";
		
		// echo "query_1 : " .$query. "<br />";  
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertPortfolioPrize($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_PORTFOLIO_PRIZE (".$set_field.") 
					values (".$set_value."); ";
		
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

	function deletePortfolioPrize($db, $p_no) {
				
		$query = "UPDATE TBL_PORTFOLIO_PRIZE SET DEL_TF = 'Y', DEL_DATE = now() WHERE P_NO = '$p_no'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getPortfolioYear($db) {

		$query = "SELECT DISTINCT P_YYYY FROM TBL_PORTFOLIO WHERE DEL_TF = 'N' AND USE_TF = 'Y' ORDER BY P_YYYY DESC ";

		$result = mysql_query($query,$db);
		$record = array();
		
		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listPortfolioYear($db, $p_yyyy) {

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT *, CONCAT(P_YYYY,P_MM,P_NO) as SEQ
								FROM TBL_PORTFOLIO WHERE DEL_TF ='N' AND USE_TF = 'Y' ";

		if ($p_yyyy <> "") {
			$query .= " AND P_YYYY = '".$p_yyyy."' ";
		}

		$query .= " ORDER BY SEQ desc ";
		
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