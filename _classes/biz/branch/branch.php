<?

	function listBranchInfoList($db) {

		$query = "SELECT B.DCODE AS BRANCH_LANG, B.DCODE_NM, B.DCODE, A.BRANCH_TYPE, A.GROUP_TF, LOGO_IMG01, LOGO_IMG02, LOGO_IMG03 
								FROM TBL_BRANCH_INFO A right outer join TBL_CODE_DETAIL B ON A.BRANCH_LANG = B.DCODE
							 WHERE B.PCODE = 'LANG' 
									AND B.USE_TF = 'Y'
									AND B.DEL_TF = 'N'
									AND B.DCODE <> 'KOR'
								ORDER BY B.DCODE_SEQ_NO ASC ";
		
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
	
	function selectBranchInfo($db, $branch_lang) {

		$query = "SELECT BRANCH_LANG, BRANCH_TYPE, GROUP_TF, LOGO_IMG01, LOGO_IMG02, LOGO_IMG03, LOGO_IMG04, LOGO_IMG05, 
										 FACEBOOK_ID, MAIN_BOARD_ID, INTRO_PATH, NOTICE_BOARD_ID, POST_NO, ADDR, DOROADDR, MAP_URL, 
										 PHONE, FAX, EMAIL, REG_ADM, REG_DATE, UP_ADM, UP_DATE, INFO_01, INFO_02, INFO_03
								FROM TBL_BRANCH_INFO WHERE BRANCH_LANG = '$branch_lang' ";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function insertBranchInfo($db, $arr_data) {

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

		$query = "INSERT INTO TBL_BRANCH_INFO (".$set_field.") 
					values (".$set_value."); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBranchInfo($db, $arr_data, $branch_lang) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_BRANCH_INFO SET ".$set_query_str." ";
		
		$query .= "BRANCH_LANG = '$branch_lang' WHERE BRANCH_LANG = '$branch_lang' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
?>