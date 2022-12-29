<?

	function selectBrochure($db, $file_no) {

		$query = "SELECT *
								FROM TBL_BROCHURE_FILE WHERE FILE_NO = '$file_no' ";
		
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

	function listBrochure($db, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1); 
	 
		$logical_num = ($total_cnt - $offset) + 1 ;
	 
		$query = "set @rownum = ".$logical_num ."; ";
		 
		mysql_query($query,$db); 

		$query = "	SELECT 
						@rownum:= @rownum - 1  as rn, 
						B.FILE_NO, B.REG_DATE, B.REG_ADM, B.UP_DATE, B.UP_ADM,
						B.HIT_CNT, B.USE_TF, B.DEL_TF, B.FILE_RNM, B.FILE_NM,
						I.ADM_NAME AS REG_NAME, A.ADM_NAME AS UP_NAME
					FROM TBL_BROCHURE_FILE B
						LEFT JOIN TBL_ADMIN_INFO I ON B.REG_ADM = I.ADM_NO 
						LEFT JOIN TBL_ADMIN_INFO A ON B.UP_ADM = A.ADM_NO 
					WHERE 1 = 1 AND ( B.USE_TF <> 'N' OR B.DEL_TF <> 'N' )";
		 
		$query .= " ORDER BY B.DEL_TF ASC , B.REG_DATE DESC  limit ".$offset.", ".$nRowCount;
		
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

	function totalCntBrochure($db) {

		$query =" SELECT COUNT(*) CNT FROM TBL_BROCHURE_FILE WHERE 1 = 1 AND ( USE_TF <> 'N' OR DEL_TF <> 'N' )";
		
		
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertBrochure($db, $arr_data, $usetf ) {
		
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
		
		
		if ( $usetf == "Y" ) {
			
			$query = " UPDATE TBL_BROCHURE_FILE SET DEL_TF = 'Y' WHERE DEL_TF = 'N' ";
			
			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			}  		
		}
		
		
		$query = "INSERT INTO TBL_BROCHURE_FILE (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";
	 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	 
	}

	function updateBrochure($db, $arr_data, $file_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_BROCHURE_FILE SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "FILE_NO = '$file_no' WHERE FILE_NO = '$file_no' ";

		// echo $query;
		//exit;
 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
		 
	}

	function deleteBrochure($db, $del_adm, $file_no) {
 
		$query="UPDATE TBL_BROCHURE_FILE SET 
					USE_TF				= 'N',
					DEL_ADM			= '$del_adm',
					DEL_TF				= 'N',
					DEL_DATE			= now()
				WHERE FILE_NO = '$file_no' ";
						 
		// echo "query : " .$query. "<br />";
		
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
		
	}

	function updateViewCntBrochure($db, $file_no) {

		$query = "UPDATE TBL_BROCHURE_FILE SET HIT_CNT = HIT_CNT + 1 WHERE FILE_NO = '$file_no' ";

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
	
	function updateUseTfBrochure($db){
		
		$query = "UPDATE TBL_BROCHURE_FILE SET DEL_TF = 'Y' ";
		
		echo "1234";
		// exit;
		
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	 
	}

?>