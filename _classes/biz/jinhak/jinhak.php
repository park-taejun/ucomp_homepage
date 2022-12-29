<?
	function chkVacationDate($db, $uni_name, $s_date, $s_hour, $s_min, $e_date, $e_hour, $e_min, $IDX) {
		
		if ($IDX == "") {
			$query = "SELECT COUNT(*) FROM TBL_JINHAK 
								WHERE UNI_NAME = '$uni_name' AND S_DATE >= '$s_date' AND S_HOUR >= '$s_hour' AND S_MIN >= '$s_min' 
								AND E_DATE <= '$e_date' AND E_HOUR >= '$e_hour' AND E_MIN >= '$e_min' ";
		} else {
			$query = "SELECT COUNT(*) FROM TBL_JINHAK 
								WHERE UNI_NAME = '$uni_name' AND S_DATE >= '$s_date' AND S_HOUR >= '$s_hour' AND S_MIN >= '$s_min' 
								AND E_DATE <= '$e_date' AND E_HOUR >= '$e_hour' AND E_MIN >= '$e_min' 
								AND IDX <> '$IDX'  ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		//echo $rows[0];

		if ($rows[0] == 0) {
			return false;
		} else {
			return true;
		}
	}

	function insertJinhak($db, $arr_data) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";

		foreach ($arr_data as $key => $value) {
				$set_field .= $key.","; 
				$set_value .= "'".$value."',"; 
		}

		$query = "INSERT INTO TBL_JINHAK (".$set_field." REG_DATE, UP_DATE) 
					values (".$set_value." now(), now()); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateJinhak($db, $arr_data, $idx) {
		
		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_JINHAK SET ".$set_query_str."  ";
		$query .= "UP_DATE = now() WHERE IDX = '$idx' ";

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

	function deleteJinhak($db, $adm_no, $idx) {

		$query = "UPDATE TBL_JINHAK SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE IDX = '$idx' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listJinhak($db, $dd, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, IDX, UNI_NAME, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, FILENAME, TITLE, CONTENT,  
										 DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_JINHAK WHERE DEL_TF = 'N' AND S_DATE = '".$dd."'";

		$query .= " ORDER BY S_HOUR, FIELD(UNI_NAME,'부경대','아주대','세종대','광운대','한경대') "; // limit ".$offset.", ".$nRowCount;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listCalJinhak($db, $s_date) {

		$str = "";

		$query = "SELECT @rownum:= @rownum - 1  as rn, IDX, UNI_NAME, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, FILENAME, TITLE, CONTENT,  
										 DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_JINHAK WHERE DEL_TF = 'N' AND S_DATE = '".$s_date."'";

		$query .= " ORDER BY S_HOUR, FIELD(UNI_NAME,'부경대','아주대','세종대','광운대','한경대') "; // limit ".$offset.", ".$nRowCount;


		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);

			$IDX					= Trim($row["IDX"]);
			$UNI_NAME			= Trim($row["UNI_NAME"]);
			$DCODE_NM			= Trim($row["DCODE_NM"]);
			$S_DATE				= Trim($row["S_DATE"]);
			$S_HOUR				= Trim($row["S_HOUR"]);
			$E_DATE				= Trim($row["E_DATE"]);
			$E_HOUR				= Trim($row["E_HOUR"]);
			$FILENAME			= Trim($row["FILENAME"]);
			$TITLE				= Trim($row["TITLE"]);
			$CONTENT			= Trim($row["CONTENT"]);

			switch ($UNI_NAME) {
				case "부경대" :	$color = "skyblue"; break;
				case "아주대" :	$color = "deeppink"; break;
				case "세종대" :	$color = "blueviolet"; break;
				case "광운대" :	$color = "brown"; break;
				case "한경대" :	$color = "lightgreen"; break;
				default			:	$color = "navy";
			}

	//		$str= $str."<div style='position:relative'>";

		//	$str= $str."<div id='".$IDX.$s_date."' style='position:absolute; background-color: #EFEFEF; border: 1px solid #DEDEDE; padding:5px 5px 5px 5px; top:20px; left:-10px; text-align:left; z-index:1000; display:none;';>/";
		//	$str= $str.nl2br($TITLE)."/</div>";

			$str= $str."<a href='javascript:js_modify(".$IDX.")' title='".$CONTENT."' style='text-decoration:none;'><font color='".$color."'>[".$UNI_NAME."]</font> ".$S_HOUR."시 (".$TITLE.")</a><br>"; 
//			$str= $str."</div>";

		}

		return $str;

	}



	function selectJinhak($db, $IDX) {

		$query = "SELECT *
								FROM TBL_JINHAK WHERE IDX = '$IDX' ";
		
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


?>