<?

	function insertSurvey($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_SURVEY (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";
		$query1 = "INSERT INTO TBL_SURVEY (".$set_field.", REG_DATE, UP_DATE) values (".$set_value.", now(), now()); ";

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


	function sessionCheckSurvey($db, $session_id) {

		$query  = "SELECT COUNT(*)
								 FROM TBL_SURVEY WHERE REG_ADM = '$session_id' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
	
		if ($rows[0] == 0) {
			return 0;
		} else {
			return 1;
		}

	}

	function updateSurvey($db, $arr_data, $session_id) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_SURVEY SET ".$set_query_str." ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE SEQ_NO = '$session_id' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateSurveyAsSeqNo($db, $arr_data, $seq_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_SURVEY SET ".$set_query_str." ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateSurveyAsS3($db, $seq_no) {

		$query  = "UPDATE TBL_SURVEY SET S3_1=null, S3_2=null, s4=null, s5=null, s6=null, s7=null, s8=null, s8_1=null, s8_2=null, s9=null, s10=null, ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listCustomers($db){
	
		$query = "SELECT COUNT(*) FROM TBL_SURVEY WHERE END_TF = 'Y'";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectCustomers($db){
	
		$query = "SELECT * FROM TBL_SURVEY WHERE END_TF = 'Y'";
		$result = mysql_query($query,$db);

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function resultSurvey($db, $question, $leng){
	
		$query	= "SELECT SUM(CASE WHEN $question = '1' THEN 1 ELSE 0 END) AS 'N1' 
										, SUM(CASE WHEN $question = '2' THEN 1 ELSE 0 END) AS 'N2' ";
		$j = 3;

		for ( $i = 0 ; $i < $leng ;$i++){
			if ($leng >= $j) {
					$query .= ", SUM(CASE WHEN $question = '".$j."' THEN 1 ELSE 0 END) AS 'N".$j."' ";
			} 
			$j++;
		}

		$query .= " FROM TBL_SURVEY WHERE END_TF = 'Y'";

		$result = mysql_query($query,$db);
		$record = mysql_fetch_array($result);

		return $record;

	}

	function resultSurveyMulti($db, $question, $leng){

		$query	= "SELECT SUM(CASE WHEN $question LIKE '%[1]%' THEN 1 ELSE 0 END) AS 'N1' 
										, SUM(CASE WHEN $question  LIKE '%[2]%' THEN 1 ELSE 0 END) AS 'N2' ";

		$j = 3;

		for ( $i = 0 ; $i < $leng ;$i++){
			if ($leng >= $j) {
					$query .= ", SUM(CASE WHEN $question LIKE '%[".$j."%' THEN 1 ELSE 0 END) AS 'N".$j."' ";
			} 
			$j++;
		}

		$query .= " FROM TBL_SURVEY WHERE END_TF = 'Y'";

		$result = mysql_query($query,$db);
		$record = mysql_fetch_array($result);

		return $record;

	}

	function resultSurveyMultiString($db, $question, $leng, $string){

		$str = explode(",", $string);

		$query	= "SELECT SUM(CASE WHEN $question like '%$str[0]%' THEN 1 ELSE 0 END) AS 'N1' 
									, SUM(CASE WHEN $question like '%$str[1]%' THEN 1 ELSE 0 END) AS 'N2' ";

		$j = 3;
		$m = 2;
		for ( $i = 0 ; $i < $leng ;$i++){
			if ($leng >= $j) {
					$query .= ", SUM(CASE WHEN $question like '%$str[$m]%' THEN 1 ELSE 0 END) AS 'N".$j."' ";
			} 
			$j++;
			$m++;
		}

		$query .= " FROM TBL_SURVEY WHERE END_TF = 'Y'";

		$result = mysql_query($query,$db);
		$record = mysql_fetch_array($result);

		return $record;


	}
	
	function resultSurveyMultiStringNot($db, $question, $s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8){
	
		$query = "SELECT COUNT(*) FROM TBL_SURVEY 
							 WHERE $question NOT LIKE '%$s1%' 
									AND $question NOT LIKE '%$s2%'
									AND $question NOT LIKE '%$s3%'
									AND $question NOT LIKE '%$s4%'
									AND $question NOT LIKE '%$s5%'
									AND $question NOT LIKE '%$s6%'
									AND $question NOT LIKE '%$s7%'
									AND $question NOT LIKE '%$s8%'
									AND END_TF = 'Y' " ;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function resultSurveyEtcList($db, $question, $num){
	
		$query = "SELECT DISTINCT($question) FROM TBL_SURVEY WHERE END_TF = 'Y' ";

		if ($num <> 0){ //기타추출
			$query = "SELECT DISTINCT($question) FROM TBL_SURVEY WHERE $question LIKE '$num%'";
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

	function resultSurveyMultiEtcList($db, $question, $num){
	
		$query = "SELECT DISTINCT($question) FROM TBL_SURVEY WHERE END_TF = 'Y' ";

		if ($num <> 0){ //기타추출
			$query = "SELECT DISTINCT($question) FROM TBL_SURVEY WHERE $question LIKE '%[$num%'";
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

	function resultSurveyRank($db, $question, $leng, $bank){
		
		if ( $bank == 1 ){

			$query	= "SELECT SUM(CASE WHEN $question like '[1]%' THEN 1 ELSE 0 END) AS 'N1' 
											, SUM(CASE WHEN $question like '[2]%' THEN 1 ELSE 0 END) AS 'N2' ";
			 
			if ($leng >= 3) {
					$query .= ", SUM(CASE WHEN $question like '[3]%' THEN 1 ELSE 0 END) AS 'N3' ";
			} 
			if ($leng >= 4) {
					$query .= ", SUM(CASE WHEN  $question like '[4]%' THEN 1 ELSE 0 END) AS 'N4' ";
			} 
			if ($leng >= 5) {
					$query .= ", SUM(CASE WHEN  $question like '[5]%' THEN 1 ELSE 0 END) AS 'N5' ";
			} 
			if ($leng >= 6) {
					$query .= ", SUM(CASE WHEN  $question like '[6]%' THEN 1 ELSE 0 END) AS 'N6' ";
			} 
			if ($leng >= 7) {
					$query .= ", SUM(CASE WHEN  $question like '[7]%' THEN 1 ELSE 0 END) AS 'N7' ";
			} 
			if ($leng >= 8) {
					$query .= ", SUM(CASE WHEN  $question like '[8]%' THEN 1 ELSE 0 END) AS 'N8' ";
			} 
			if ($leng >= 9) {
					$query .= ", SUM(CASE WHEN  $question like '[9]%' THEN 1 ELSE 0 END) AS 'N9' ";
			} 
			if ($leng >= 10){
					$query .= ", SUM(CASE WHEN  $question like '[10]%' THEN 1 ELSE 0 END) AS 'N10' ";
			} 
			if ($leng >= 11) {
					$query .= ", SUM(CASE WHEN  $question like '[11]%' THEN 1 ELSE 0 END) AS 'N11' ";
			} 
			if ($leng >= 12) {
					$query .= ", SUM(CASE WHEN  $question like '[12]%' THEN 1 ELSE 0 END) AS 'N12' ";
			} 

		} else if ( $bank == 2 ){

			$query	= "SELECT SUM(CASE WHEN $question like '%,[1],%' THEN 1 ELSE 0 END) AS 'N1' 
											, SUM(CASE WHEN $question like '%,[2],%' THEN 1 ELSE 0 END) AS 'N2' ";
			 
			if ($leng >= 3) {
					$query .= ", SUM(CASE WHEN $question like '%,[3],%' THEN 1 ELSE 0 END) AS 'N3' ";
			} 
			if ($leng >= 4) {
					$query .= ", SUM(CASE WHEN  $question like '%,[4],%' THEN 1 ELSE 0 END) AS 'N4' ";
			} 
			if ($leng >= 5) {
					$query .= ", SUM(CASE WHEN  $question like '%,[5],%' THEN 1 ELSE 0 END) AS 'N5' ";
			} 
			if ($leng >= 6) {
					$query .= ", SUM(CASE WHEN  $question like '%,[6],%' THEN 1 ELSE 0 END) AS 'N6' ";
			} 
			if ($leng >= 7) {
					$query .= ", SUM(CASE WHEN  $question like '%,[7],%' THEN 1 ELSE 0 END) AS 'N7' ";
			} 
			if ($leng >= 8) {
					$query .= ", SUM(CASE WHEN  $question like '%,[8],%' THEN 1 ELSE 0 END) AS 'N8' ";
			} 
			if ($leng >= 9) {
					$query .= ", SUM(CASE WHEN  $question like '%,[9],%' THEN 1 ELSE 0 END) AS 'N9' ";
			} 
			if ($leng >= 10){
					$query .= ", SUM(CASE WHEN  $question like '%,[10],%' THEN 1 ELSE 0 END) AS 'N10' ";
			} 
			if ($leng >= 11) {
					$query .= ", SUM(CASE WHEN  $question like '%,[11],%' THEN 1 ELSE 0 END) AS 'N11' ";
			} 
			if ($leng >= 12) {
					$query .= ", SUM(CASE WHEN  $question like '%,[12],%' THEN 1 ELSE 0 END) AS 'N12' ";
			} 

		} else if ( $bank == 3 ){

			$query	= "SELECT SUM(CASE WHEN $question like '%,[1]' THEN 1 ELSE 0 END) AS 'N1' 
											, SUM(CASE WHEN $question like '%,[2]' THEN 1 ELSE 0 END) AS 'N2' ";
			 
			if ($leng >= 3) {
					$query .= ", SUM(CASE WHEN $question like '%,[3]' THEN 1 ELSE 0 END) AS 'N3' ";
			} 
			if ($leng >= 4) {
					$query .= ", SUM(CASE WHEN  $question like '%,[4]' THEN 1 ELSE 0 END) AS 'N4' ";
			} 
			if ($leng >= 5) {
					$query .= ", SUM(CASE WHEN  $question like '%,[5]' THEN 1 ELSE 0 END) AS 'N5' ";
			} 
			if ($leng >= 6) {
					$query .= ", SUM(CASE WHEN  $question like '%,[6]' THEN 1 ELSE 0 END) AS 'N6' ";
			} 
			if ($leng >= 7) {
					$query .= ", SUM(CASE WHEN  $question like '%,[7]' THEN 1 ELSE 0 END) AS 'N7' ";
			} 
			if ($leng >= 8) {
					$query .= ", SUM(CASE WHEN  $question like '%,[8]' THEN 1 ELSE 0 END) AS 'N8' ";
			} 
			if ($leng >= 9) {
					$query .= ", SUM(CASE WHEN  $question like '%,[9]' THEN 1 ELSE 0 END) AS 'N9' ";
			} 
			if ($leng >= 10){
					$query .= ", SUM(CASE WHEN  $question like '%,[10]' THEN 1 ELSE 0 END) AS 'N10' ";
			} 
			if ($leng >= 11) {
					$query .= ", SUM(CASE WHEN  $question like '%,[11]' THEN 1 ELSE 0 END) AS 'N11' ";
			} 
			if ($leng >= 12) {
					$query .= ", SUM(CASE WHEN  $question like '%,[12]' THEN 1 ELSE 0 END) AS 'N12' ";
			} 

		}

			$query .= " FROM TBL_SURVEY WHERE END_TF = 'Y'";

		$result = mysql_query($query,$db);
		$record = mysql_fetch_array($result);

		return $record;


	}

?>