<?

	# =============================================================================
	# File Name    : admin_org.php
	# Modlue       : 
	# Writer       : JeGal Jeong
	# Create Date  : 2021-11-08
	# Modify Date  : 
	#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================



	function listAdminOrg($db, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum + 1  as rn, ADM_NO, YEAR, POSITION_CODE, OCCUPATION_CODE, DEPT_CODE, HEADQUARTERS_CODE, LEADER_YN, LEVEL, DEPT_UNIT_NAME, LEADER_TITLE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_ORG
							WHERE DEPT_CODE <> '' AND DEPT_CODE <> '기타' AND LEVEL <> '' ";

		if ($dept_code <> "") {
			$query .= " AND DEPT_CODE = '".$dept_code."' ";
		}

		if ($position_code <> "") {
			$query .= " AND POSITION_CODE = '".$position_code."' ";
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
		
		$query .= " ORDER BY YEAR DESC, HEADQUARTERS_CODE ASC, DEPT_CODE ASC limit ".$offset.", ".$nRowCount;

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


	function totalCntAdminOrg ($db, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_ORG WHERE DEPT_CODE <> '' AND DEPT_CODE <> '기타' AND LEVEL <> '' ";
		
		if ($dept_code <> "") {
			$query .= " AND A.DEPT_CODE = '".$dept_code."' ";
		}

		if ($position_code <> "") {
			$query .= " AND A.POSITION_CODE = '".$position_code."' ";
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


	function insertAdminOrg($db, $arr_data) {
		
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
				$set_value .= ",'".$value."'";
			}
		}

		$query ="SELECT IFNULL(MAX(ADM_NO),0) + 1 AS MAX_NO FROM TBL_ADMIN_INFO ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		$new_adm_no = $rows[0];

		$query = "INSERT INTO TBL_ADMIN_INFO (ADM_NO, ".$set_field.", REG_DATE, UP_DATE) 
					values ('$new_adm_no', ".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_adm_no;
		}
	}

	function selectAdminOrg($db, $adm_no) {

		$query = "SELECT ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, ADM_BIRTHDAY, PROFILE, 
										 GROUP_NO, ADM_FLAG, POSITION_CODE, OCCUPATION_CODE, DEPT_CODE, COM_CODE, ENTER_DATE, OUT_DATE, 
										 HEADQUARTERS_CODE, LEADER_YN, LEADER_TITLE, LEVEL, DEPT_UNIT_NAME, COMMUTE_TIME, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_INFO  WHERE ADM_NO = '$adm_no' ";
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

	function updateAdminOrg($db, $arr_data, $adm_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_ADMIN_INFO SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "ADM_NO = '$adm_no' WHERE ADM_NO = '$adm_no' ";

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

	function deleteAdminOrg($db, $del_adm, $adm_no) {

		$query="UPDATE TBL_ADMIN_INFO SET 
									 DEL_TF				= 'Y',
									 DEL_ADM			= '$del_adm',
									 DEL_DATE			= now()														 
						 WHERE ADM_NO				= '$adm_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateAdminOrgUseTF($db, $use_tf, $up_adm, $adm_no) {
		
		$query="UPDATE TBL_ADMIN_INFO SET 
							USE_TF			= '$use_tf',
							UP_ADM			= '$up_adm',
							UP_DATE			= now()
				 WHERE ADM_NO			= '$adm_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

?>