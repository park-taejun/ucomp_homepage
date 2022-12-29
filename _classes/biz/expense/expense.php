<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	function selectExpense($db, $ex_no) {

		$query = "SELECT *
								FROM TBL_EXPENSE WHERE EX_NO = '$ex_no' AND DEL_TF = 'N' ";
		
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

	function selectExpenseDate($db, $ex_no) {

		$query = "SELECT *
								FROM TBL_EXPENSE_DATE WHERE EX_NO = '$ex_no' order by EXD_DATE asc ";
		
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

	function selectExpenseFile($db, $ex_no) {

		$query = "SELECT *
								FROM TBL_EXPENSE_FILE WHERE EX_NO = '$ex_no' ";
		
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

	function insertExpense($db, $arr_data) {
		
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

		$query = "SELECT MAX(EX_NO) FROM TBL_EXPENSE WHERE DEL_TF = 'N'";
		$result = mysql_query($query,$db);
		$ex_max = mysql_fetch_array($result);
		$ex_max_no = $ex_max[0];
		$ex_dn = date("Y-m")."-".$ex_max_no;

		$query = "INSERT INTO TBL_EXPENSE (".$set_field.", EX_DN, REG_DATE, UP_DATE) 
					values (".$set_value.", '".$ex_dn."', now(), now()); ";

		//echo $query."<br>"; 
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			
			$query = "SELECT last_insert_id()";
			$result = mysql_query($query,$db);
			$rows = mysql_fetch_array($result);
			$new_seq_no = $rows[0];
			return $new_seq_no;

		}
	}

	function insertExpenseDate($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_EXPENSE_DATE (".$set_field.") 
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


	function insertExpenseFile($db, $ex_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $reg_adm) {
				
		$query = "INSERT INTO TBL_EXPENSE_FILE (EX_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT, REG_ADM, REG_DATE) 
														values ('$ex_no', '$file_nm', '$file_rnm', '$file_path', '$file_size', '$file_ext', '0', '$reg_adm', now()); ";
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

	function listExpense($db, $va_user, $nPage, $nRowCount, $sort_dn, $sort_date, $sort_state) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);


		$query = "SELECT @rownum:= @rownum - 1  as rn, EX_NO, EX_TITLE, EX_DATE, EX_MEMO, EX_TOTAL_PRICE, VA_USER, HEADQUARTERS_CODE, DEPT_CODE, VA_STATE, VA_STATE_POS, REG_ADM, REG_DATE, EX_DN 
								FROM TBL_EXPENSE WHERE DEL_TF = 'N' ";

		if ($ex_type <> "") {
			$query .= " AND EX_TYPE = '".$ex_type."' ";
		}

		if ($va_user <> "") {
			$query .= " AND VA_USER = '".$va_user."' ";
		}

		//$query .= " ORDER BY EX_NO desc limit ".$offset.", ".$nRowCount;

		$query .= " ORDER BY ";

		if ($sort_dn <> "") {
			$query .= $sort_dn .", ";
		}

		if ($sort_date <> "") {
			$query .= $sort_date .", ";
		}

		if ($sort_state <> "") {
			$query .= $sort_state .", ";
		}

		$query .= " EX_NO desc limit ".$offset.", ".$nRowCount;

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

	function totalCntExpense($db, $va_type, $va_user){

		$query ="SELECT COUNT(*) CNT FROM TBL_EXPENSE WHERE 1 = 1 ";
		
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

	function updateExpense($db, $arr_data, $ex_no) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_EXPENSE SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "EX_NO = '$ex_no' WHERE EX_NO = '$ex_no' ";

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

	function deleteExpenseDate($db, $ex_no) {
		
		$query = "DELETE FROM TBL_EXPENSE_DATE WHERE EX_NO = '$ex_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteImgFile($db, $ex_no, $file_nm) {

		$query = "DELETE FROM TBL_EXPENSE_FILE WHERE EX_NO = '$ex_no' AND FILE_NM ='$file_nm' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteExpense($db, $ex_no) {
		
		$query = "DELETE FROM TBL_EXPENSE WHERE EX_NO = '$ex_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteImg($db, $ex_no) {

		$query = "DELETE FROM TBL_EXPENSE_FILE WHERE EX_NO = '$ex_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>