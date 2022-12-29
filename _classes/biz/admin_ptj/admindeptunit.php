<?

	# =============================================================================
	# File Name    : admindeptunit.php
	# Modlue       : 
	# Writer       : Park Chan Ho / JeGal Jeong
	# Create Date  : 2021-01-27
	# Modify Date  : 
	#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function dupAdminDept($db,$headquarters_code, $dept_code, $dept_unit_name) {
		
		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_DEPT_UNIT 
	            WHERE USE_TF = 'Y' AND DEL_TF = 'N'
						    AND IFNULL(HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
								AND IFNULL(DEPT_CODE, '') = '".$dept_code."'
								AND IFNULL(DEPT_UNIT_NAME, '') = '".$dept_unit_name."'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] == 0) {
			return 0;
		} else {
			return 1;
		}
				
	}

	function insertAdminDept($db, $arr_data, $adm_no) {
		
		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";

		$query = "UPDATE TBL_ADMIN_INFO SET LEADER_YN = 'Y' WHERE ADM_NO ='$adm_no'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		}

		foreach ($arr_data as $key => $value) {

			$value = str_replace("'","''",$value);

			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'";
			}
		}

		$query = "INSERT INTO TBL_ADMIN_DEPT_UNIT (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateAdminDept($db, $arr_data, $dept_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_ADMIN_DEPT_UNIT SET ".$set_query_str." ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE DEPT_NO = '$dept_no' ";

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

	function deleteAdminDept($db, $dept_no) {

		$query="UPDATE TBL_ADMIN_DEPT_UNIT SET 
									 DEL_TF		= 'Y',
									 DEL_ADM	= '$del_adm',
									 DEL_DATE	= now() 
						 WHERE DEPT_NO	= '$dept_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectAdminDept($db, $dept_no) {

		$query = "SELECT * FROM TBL_ADMIN_DEPT_UNIT WHERE DEPT_NO = '$dept_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function selectAdminInfo($db, $headquarters_code, $dept_code, $dept_unit_name, $position_code, $leader_title, $leader_name) {
		if ($leader_title == "대표이사") {

			$query = "SELECT * FROM TBL_ADMIN_INFO 
								 WHERE USE_TF = 'Y' AND DEL_TF ='N' 
								 AND POSITION_CODE = '$position_code' 
								 AND LEADER_TITLE = '$leader_title' 
								 AND ADM_NAME = '$leader_name'";

		} elseif ($leader_title == "이사") {

			$query = "SELECT * FROM TBL_ADMIN_INFO 
								 WHERE USE_TF = 'Y' AND DEL_TF ='N' 
								 AND HEADQUARTERS_CODE = '$headquarters_code' 
								 AND POSITION_CODE = '$position_code' 
								 AND LEADER_TITLE = '$leader_title' 
								 AND ADM_NAME = '$leader_name'";

		} else {
			$query = "SELECT * FROM TBL_ADMIN_INFO 
								 WHERE USE_TF = 'Y' AND DEL_TF ='N' 
								 AND IFNULL(HEADQUARTERS_CODE, '') = '$headquarters_code' 
								 AND DEPT_CODE = '$dept_code' 
								 AND DEPT_UNIT_NAME = '$dept_unit_name' 
								 AND POSITION_CODE = '$position_code' 
								 AND LEADER_TITLE = '$leader_title' 
								 AND ADM_NAME = '$leader_name'";
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

	function totalCntAdminDept ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_DEPT_UNIT WHERE 1 = 1 ";
		
		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listAdminDept($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum + 1  as rn, DEPT_NO, HEADQUARTERS_CODE, DEPT_CODE, DEPT_UNIT_NAME, POSITION_CODE, LEADER_TITLE, LEADER_NAME, USE_TF 
								FROM TBL_ADMIN_DEPT_UNIT WHERE 1 = 1 ";

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY HEADQUARTERS_CODE ASC, DEPT_CODE ASC, DEPT_UNIT_NAME ASC limit ".$offset.", ".$nRowCount;

	//	echo $query;
	//	exit;


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