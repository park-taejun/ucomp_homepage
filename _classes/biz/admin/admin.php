<?

	# =============================================================================
	# File Name    : admin.php
	# Modlue       : 
	# Writer       : Park Chan Ho /JeGal Jeong
	# Create Date  : 2018-12-11
	# Modify Date  : 2021-02-02
	#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function listAdminGroup($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db); 

		$query = "SELECT @rownum:= @rownum + 1  as rn, GROUP_NO, GROUP_NAME, GROUP_FLAG, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_HOME_ADMIN_GROUP A WHERE 1 = 1  ";
		

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY GROUP_NO desc limit ".$offset.", ".$nRowCount;

		#echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntAdminGroup ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_HOME_ADMIN_GROUP WHERE 1 = 1 ";
		
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

	function insertAdminGroup($db, $group_name, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_HOME_ADMIN_GROUP (GROUP_NAME, GROUP_FLAG, USE_TF, REG_ADM, REG_DATE) 
											 values ('$group_name', 'Y', 'Y', '$reg_adm', now()); ";
		
		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateAdminGroup($db, $group_name, $use_tf, $up_adm, $group_no) {
		
		$query="UPDATE TBL_HOME_ADMIN_GROUP SET 
									 GROUP_NAME	= '$group_name', 
									 USE_TF				= 'Y',
									 UP_ADM				= '$up_adm',
									 UP_DATE			= now()
						 WHERE GROUP_NO				= '$group_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteAdminGroup($db, $del_adm, $group_no) {

		$query="UPDATE TBL_ADMIN_INFO SET 
											 DEL_TF				= 'Y',
											 DEL_ADM			= '$del_adm',
											 DEL_DATE			= now()														 
								 WHERE GROUP_NO			= '$group_no' ";
		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		}

		$query="UPDATE TBL_HOME_ADMIN_GROUP SET 
											 DEL_TF				= 'Y',
											 DEL_ADM			= '$del_adm',
											 DEL_DATE			= now()														 
								 WHERE GROUP_NO			= '$group_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listAdmin($db, $group_no, $com_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);


		$query = "SELECT @rownum:= @rownum + 1  as rn, A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_ZIP, A.ADM_ADDR, A.ADM_FLAG, A.POSITION_CODE, A.OCCUPATION_CODE, A.DEPT_CODE, A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 A.HEADQUARTERS_CODE, A.LEADER_YN, A.LEVEL, A.DEPT_UNIT_NAME, A.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME 
								FROM TBL_ADMIN_INFO A 
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON A.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON A.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1 ";

/*
		$query = "SELECT @rownum:= @rownum + 1  as rn, ADM_ID, ADM_NO, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, ADM_BIRTHDAY, 
										 GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE, ENTER_DATE, OUT_DATE, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_INFO A WHERE 1 = 1 ";
*/

		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($com_code <> "") {
			$query .= " AND A.COM_CODE = '".$com_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND A.DEPT_CODE = '".$dept_code."' ";
		}

		if ($position_code <> "") {
			$query .= " AND A.POSITION_CODE = '".$position_code."' ";
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
		
		$query .= " ORDER BY A.COM_CODE ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC limit ".$offset.", ".$nRowCount;

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

	function listAdminTest($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);


		$query = "SELECT @rownum:= @rownum + 1  as rn, A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_ZIP, A.ADM_ADDR,A.ADM_FLAG, 
                     A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 D.POSITION_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, 
										 D.HEADQUARTERS_CODE, D.LEADER_YN, D.LEVEL, D.DEPT_UNIT_NAME, D.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME 
								FROM TBL_ADMIN_INFO A 
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206'
										 LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON D.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1";

/*
		$query = "SELECT @rownum:= @rownum + 1  as rn, ADM_ID, ADM_NO, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, ADM_BIRTHDAY, 
										 GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE, ENTER_DATE, OUT_DATE, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_INFO A WHERE 1 = 1 ";
*/

		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}
		
		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' "; 
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
		
		$query .= " ORDER BY FIELD(D.POSITION_CODE, '대표이사') DESC, A.COM_CODE ASC, H.DCODE_SEQ_NO ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC limit ".$offset.", ".$nRowCount;

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

	function totalCntAdmin ($db, $group_no, $com_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_INFO A WHERE 1 = 1 ";
		
		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($com_code <> "") {
			$query .= " AND A.COM_CODE = '".$com_code."' ";
		}

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

	function totalCntAdminTest($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_INFO A 
																			LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206' 
																 WHERE 1 = 1 ";
		
		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}

		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' ";
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

//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

//mobile admin 목록 scroll infinite을 위해 2022-07-29
	function listAdminTestScroll($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);


		$query = "SELECT @rownum:= @rownum + 1  as rn, A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_ZIP, A.ADM_ADDR,A.ADM_FLAG, 
                     A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 D.POSITION_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, 
										 D.HEADQUARTERS_CODE, D.LEADER_YN, D.LEVEL, D.DEPT_UNIT_NAME, D.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME 
								FROM TBL_ADMIN_INFO A 
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206'
										 LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON D.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1";

/*
		$query = "SELECT @rownum:= @rownum + 1  as rn, ADM_ID, ADM_NO, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, ADM_BIRTHDAY, 
										 GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE, ENTER_DATE, OUT_DATE, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_INFO A WHERE 1 = 1 ";
*/

		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}
		
		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' "; 
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
		
		$query .= " ORDER BY FIELD(D.POSITION_CODE, '대표이사') DESC, A.COM_CODE ASC, H.DCODE_SEQ_NO ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC limit ".$offset.", ".$nRowCount;

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



//mobile scroll infinite end


	function insertAdmin($db, $arr_data) {
		
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

	function selectAdmin($db, $adm_no) {

		$query = "SELECT ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, ADM_BIRTHDAY, ADM_ZIP, ADM_ADDR, PROFILE, 
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

//조직도2022에 따른 추가 2021-11-22
	function selectAdmin2022($db, $adm_no) {

		$query = "SELECT A.ADM_NO, A.ADM_ID, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.ADM_HPHONE, A.ADM_PHONE, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_ZIP, A.ADM_ADDR, A.PROFILE, A.COMMUTE_TIME, 
										 A.GROUP_NO, A.ADM_FLAG, A.COM_CODE, A.ENTER_DATE, A.OUT_DATE, A.OCCUPATION_CODE, 
										 D.HEADQUARTERS_CODE, D.POSITION_CODE, D.DEPT_CODE, 
										 D.LEADER_YN, D.LEADER_TITLE, D.LEVEL, D.DEPT_UNIT_NAME, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE 
								FROM TBL_ADMIN_INFO A
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206'
								WHERE A.ADM_NO = '$adm_no' ";

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

	function updateAdmin($db, $arr_data, $adm_no) {

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

	function updateAdminPwd($db, $passwd, $up_adm, $adm_no) {
		
		$query="UPDATE TBL_ADMIN_INFO SET 
									 PASSWD					= '$passwd'
						 WHERE ADM_NO					= '$adm_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteAdmin($db, $del_adm, $adm_no) {

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

	function dupAdmin ($db,$adm_id) {
		
		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_INFO WHERE 1 = 1 ";
		
		if ($adm_id <> "") {
			$query .= " AND ADM_ID = '".$adm_id."' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
	
		if ($rows[0] == 0) {
			return 0;
		} else {
			return 1;
		}
				
	}


	function confirmAdmin($db, $adm_id) {
	
		/*
		$query = "SELECT ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_EMAIL, ADM_BIRTHDAY, GROUP_NO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, COM_CODE,
										 POSITION_CODE, OCCUPATION_CODE, DEPT_CODE, PROFILE,
										 (SELECT CP_TYPE FROM TBL_COMPANY WHERE TBL_COMPANY.CP_NO = A.COM_CODE) AS CP_TYPE, ORGANIZATION
								FROM TBL_ADMIN_INFO A WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND ADM_ID = '$adm_id' ";
		*/
		$query = "SELECT A.ADM_NO, A.ADM_ID, A.PASSWD, A.ADM_NAME, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.GROUP_NO, A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, 
										 A.UP_DATE, A.DEL_ADM, A.DEL_DATE, A.COM_CODE, A.OCCUPATION_CODE, A.PROFILE,
										 D.HEADQUARTERS_CODE, D.POSITION_CODE, D.DEPT_CODE, D.LEADER_YN, D.LEADER_TITLE, D.LEVEL, D.DEPT_UNIT_NAME,
										 (SELECT CP_TYPE FROM TBL_COMPANY WHERE TBL_COMPANY.CP_NO = A.COM_CODE) AS CP_TYPE
								FROM TBL_ADMIN_INFO A 
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206'
               WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' AND A.ADM_ID = '$adm_id' ";

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
/*
	function insertUserLog($db, $user_type, $log_id, $log_ip) {
		
		$query="INSERT INTO TBL_USER_LOG (USER_TYPE, LOG_ID, LOG_IP, LOGIN_DATE) 
															 values ('$user_type', '$log_id', '$log_ip', now()); ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
*/
	function updateAdminUseTF($db, $use_tf, $up_adm, $adm_no) {
		
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

	function selectAdminGroup($db, $group_no) {

		$query = "SELECT GROUP_NO, GROUP_NAME, GROUP_FLAG, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_GROUP WHERE GROUP_NO = '$group_no' ";

		#echo $query;
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listAdminGroupMenuRight($db, $group_no) {

		$query = "SELECT MENU_CD, GROUP_NO, READ_FLAG, REG_FLAG, UPD_FLAG, DEL_FLAG, FILE_FLAG, TOP_FLAG, MAIN_FLAG, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_HOME_ADMIN_MENU_RIGHT WHERE GROUP_NO = '$group_no' ";

		#echo $query;
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function deleteAdminGroupMenuRight($db, $group_no) {
		
		$query="DELETE FROM TBL_HOME_ADMIN_MENU_RIGHT WHERE GROUP_NO			= '$group_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertAdminGroupMenuRight($db, $group_no, $menu_cd, $read_chk, $reg_chk, $upd_chk, $del_chk, $file_chk, $top_chk, $main_chk) {
		
		$query="INSERT INTO TBL_HOME_ADMIN_MENU_RIGHT (GROUP_NO, MENU_CD, READ_FLAG, REG_FLAG, UPD_FLAG, DEL_FLAG, FILE_FLAG, TOP_FLAG, MAIN_FLAG) 
																		  VALUES ('$group_no', '$menu_cd', '$read_chk', '$reg_chk', '$upd_chk', '$del_chk', '$file_chk' , '$top_chk' , '$main_chk')";
		// echo $query."<br>";
		
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
		 
	}


	function listAdminLog($db, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $nListCnt) {
	

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum + 1  as rn, SEQ_NO, USER_TYPE, LOG_ID, LOG_IP, LOGIN_DATE, TASK, TASK_TYPE,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_ID = TBL_USER_LOG.LOG_ID) AS ADM_NAME
								FROM TBL_USER_LOG WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND LOGIN_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND LOGIN_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($task_type <> "") {
			$query .= " AND TASK_TYPE = '".$task_type."' ";
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

		$query .= " ORDER BY SEQ_NO desc limit ".$offset.", ".$nRowCount;

		#echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function totalCntAdminLog ($db, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str) { 

		$query ="SELECT COUNT(*) CNT FROM TBL_USER_LOG WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND LOGIN_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND LOGIN_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($task_type <> "") {
			$query .= " AND TASK_TYPE = '".$task_type."' ";
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
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function listAdminHireDate($db, $group_no, $com_code, $dept_code, $position_code, $use_tf, $del_tf) {

		$query = "SELECT A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_ZIP, A.ADM_ADDR, A.ADM_FLAG, A.POSITION_CODE, A.OCCUPATION_CODE, A.DEPT_CODE, A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 A.HEADQUARTERS_CODE, A.LEADER_YN, A.LEVEL, A.DEPT_UNIT_NAME, A.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME 
								FROM TBL_ADMIN_INFO A 
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON A.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON A.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1 ";

		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($com_code <> "") {
			$query .= " AND A.COM_CODE = '".$com_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND A.DEPT_CODE = '".$dept_code."' ";
		}

		if ($position_code <> "") {
			$query .= " AND A.POSITION_CODE = '".$position_code."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}
		
		$query .= " ORDER BY A.COM_CODE ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC";

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

	function listAdminHireDateTest($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf) {

		$query = "SELECT  A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_ZIP, A.ADM_ADDR, A.ADM_FLAG, 
                     A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 D.POSITION_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, 
										 D.HEADQUARTERS_CODE, D.LEADER_YN, D.LEVEL, D.DEPT_UNIT_NAME, D.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME 
								FROM TBL_ADMIN_INFO A 
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206'
										 LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON D.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1";

		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}
		
		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' "; 
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}
		
		$query .= " ORDER BY A.COM_CODE ASC, H.DCODE_SEQ_NO ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC";

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

	function selectOrgYear($db) {
	
		$query = "SELECT DISTINCT(YEAR)
								FROM TBL_ORG WHERE USE_TF = 'Y' AND DEL_TF = 'N'";
		
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

	function selectOrgAddYN($db, $adm_no) {
	
		$query = "SELECT COUNT(*)
								FROM TBL_ORG WHERE YEAR='202206' AND ADM_NO = '".$adm_no."' AND USE_TF = 'Y' AND DEL_TF = 'N'";
		
		//echo $query;
		//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectOrg($db, $adm_no, $yyyy) {

		$query = "SELECT * FROM TBL_ORG  WHERE ADM_NO = '$adm_no' AND YEAR = '".$yyyy."' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertOrgAdd($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_ORG (".$set_field.", REG_DATE, UP_DATE) 
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

	function updateOrg($db, $arr_data_org, $adm_no, $rd_year) {

		foreach ($arr_data_org as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_ORG SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "ADM_NO = '$adm_no' WHERE ADM_NO = '$adm_no' AND YEAR = '$rd_year'";

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

	function updateApp($db, $adm_no, $use_tf) {

		$query = "UPDATE TBL_ADMIN_APPROVAL_RIGHT SET USE_TF ='$use_tf', ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE ADM_NO = '$adm_no'";

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

	function deleteOrg($db, $del_adm, $adm_no, $rd_year) {

		$query="UPDATE TBL_ORG SET 
									 USE_TF				= 'N',
									 DEL_TF				= 'Y',
									 DEL_ADM			= '$del_adm',
									 DEL_DATE			= now()														 
						 WHERE ADM_NO				= '$adm_no' AND YEAR = '$rd_year'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteApp($db, $del_adm, $adm_no) {

		$query="UPDATE TBL_ADMIN_APPROVAL_RIGHT SET 
									 USE_TF				= 'N',
									 DEL_TF				= 'Y',
									 DEL_ADM			= '$del_adm',
									 DEL_DATE			= now()														 
						 WHERE ADM_NO				= '$adm_no'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	
	//분기별 출퇴근시간 기록 저장
	function insertCommuteTime($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_COMMUTE_TIME (".$set_field.", REG_DATE, UP_DATE) 
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
/*
	function updateCommuteTime($db, $arr_data_commute, $adm_no, $rd_year, $rd_quarter) {

		foreach ($arr_data_org as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_COMMUTE_TIME SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "ADM_NO = '$adm_no' WHERE ADM_NO = '$adm_no' AND YEAR = '$rd_year' AND QUARTER = '$rd_quarter'";

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

	function deleteCommuteTime($db, $del_adm, $adm_no, $rd_year, $rd_quarter) {

		$query="UPDATE TBL_COMMUTE_TIME SET 
									 USE_TF				= 'N',
									 DEL_TF				= 'Y',
									 DEL_ADM			= '$del_adm',
									 DEL_DATE			= now()														 
						 WHERE ADM_NO				= '$adm_no' AND YEAR = '$rd_year' AND QUARTER = '$rd_quarter'";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
*/
	function selectAdminName($db, $va_user) {

		$query = "SELECT ADM_NAME
								FROM TBL_ADMIN_INFO  WHERE ADM_NO = '$va_user'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$str_result = $rows[0];

		return $str_result;

	}

	function selectAdminHeadquarters($db, $adm_no, $year){

		$query = "SELECT HEADQUARTERS_CODE FROM TBL_ORG WHERE ADM_NO = '$adm_no' AND USE_TF ='Y' AND DEL_TF='N' AND YEAR ='".$year."'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminPosition($db, $adm_no, $year){

		$query = "SELECT POSITION_CODE FROM TBL_ORG WHERE ADM_NO = '$adm_no' AND USE_TF ='Y' AND DEL_TF='N' AND YEAR ='".$year."'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminDept($db, $adm_no, $year){

		$query = "SELECT DEPT_CODE FROM TBL_ORG WHERE ADM_NO = '$adm_no' AND USE_TF ='Y' AND DEL_TF='N' AND YEAR ='".$year."'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminLeaderTitle($db, $adm_no, $year){

		$query = "SELECT LEADER_TITLE FROM TBL_ORG WHERE ADM_NO = '$adm_no' AND USE_TF ='Y' AND DEL_TF='N' AND YEAR ='".$year."'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record = $rows[0];
		return $record;
	}

	//leader 찾기
	function selectAdminLeader($db, $leader_yn, $headquarters_code, $dept_code, $dept_unit_name, $level, $leader_title, $year){

		$level = $level - 1; //팀장 찾기

		if ($level == 3) {
			if ($headquarters_code == "사업 서비스실") {
				$dept_code = ""; // 부문장은 dept_code 공백
			} 
		}

		if ($level == 2) {

			if ($headquarters_code == "사업 서비스실") {
				$dept_code = ""; // 이사는 dept_code 공백
			} 
			if ($headquarters_code == "사업 관리실") {
				$dept_code = ""; // 이사는 dept_code 공백
			} 
			if ($headquarters_code == "플랫폼개발실") {
				$dept_code = ""; // 이사는 dept_code 공백
			} 
			if ($headquarters_code == "경영기획팀") { //대표이사로 go!
				$headquarters_code = "";
				$dept_code = "";
				$level = 0;
			} 
		}

		if ($level == 1) { //경영관리자 대표이사로 go!
				$headquarters_code = "";
				$dept_code = "";
				$level = 0;
		}

		if (($level == 4) && ($headquarters_code == "PR")){ //대표이사로 go!
				$headquarters_code = "";
				$dept_code = "";
				$level = 0;
		}

		if (($level == 4) && ($headquarters_code == "UX연구소")){ //대표이사로 go!
				$headquarters_code = "";
				$dept_code = "";
				$level = 0;
		}

		$query = "SELECT A.ADM_NO, A.ADM_NAME, A.ADM_EMAIL, D.HEADQUARTERS_CODE, D.DEPT_CODE, D.POSITION_CODE, D.LEADER_TITLE 
							FROM TBL_ADMIN_INFO A 
									 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '".$year."' 
							WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
							AND D.LEADER_YN = 'Y' 
							AND IFNULL(D.HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
							AND IFNULL(D.DEPT_CODE, '') = '".$dept_code."' 
							AND D.LEVEL = '".$level."'"; 

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

	//leader 찾기 부문장 포함
	function selectAdminPartLeader($db, $leader_yn, $headquarters_code, $occupation_code, $dept_code, $dept_unit_name, $level, $leader_title, $year){
			 
		$level = $level - 1; //팀장 찾기

		if ($level == 3) {

			if ($headquarters_code == "사업 서비스실") {
				$dept_code = ""; // 부문장은 dept_code 공백
			} 

			if (($headquarters_code == "경영기획팀") || ($headquarters_code == "PR") || ($headquarters_code == "UX연구소")) { //재무확인으로 go!
				$headquarters_code = "";
				$occupation_code = ""; 
				$level = 1;
			}

		}

		if ($level == 2) {

			if ($headquarters_code == "사업 서비스실") {
				$dept_code = ""; // 이사는 dept_code 공백
				$occupation_code = "기획"; 
			} 
			if ($headquarters_code == "사업 관리실") {
				$dept_code = ""; // 이사는 dept_code 공백
				$occupation_code = ""; 
			} 
			if ($headquarters_code == "플랫폼개발실") {
				$dept_code = ""; // 이사는 dept_code 공백
				$occupation_code = "백엔드"; 
			} 
			if ($headquarters_code == "경영기획팀") { //재무확인으로 go!
				$headquarters_code = "";
				$occupation_code = ""; 
				$level = 1;
			}

		}

		if ($level == 4) { //재무확인으로 바로 go!
			if(($headquarters_code == "경영기획팀") || ($headquarters_code == "PR") || ($headquarters_code == "UX연구소")) {  
				$headquarters_code = "";
				$occupation_code = ""; 
				$level = 1;
			}
		}

		if ($level == 0) { 
				$headquarters_code = "";
				$dept_code = "";
				$occupation_code = "UX"; 
		}

		if ($occupation_code <> "프론트엔드") { //UXP 1팀은 조완섭부문장 결재라인으로

			$query = "SELECT A.ADM_NO, A.ADM_NAME, A.ADM_EMAIL, D.HEADQUARTERS_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, D.POSITION_CODE, D.LEADER_TITLE 
								FROM TBL_ADMIN_INFO A 
										 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '".$year."' 
								WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
								AND D.LEADER_YN = 'Y' 
								AND IFNULL(D.HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
								AND IFNULL(D.OCCUPATION_CODE, '') = '".$occupation_code."' 
								AND IFNULL(D.DEPT_CODE, '') = '".$dept_code."' 
								AND D.LEVEL = '".$level."'"; 

		} else {
			//UXP 1팀의 경우 팀장일경우와 부분장
			if ($level == 4){
				$query = "SELECT A.ADM_NO, A.ADM_NAME, A.ADM_EMAIL, D.HEADQUARTERS_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, D.POSITION_CODE, D.LEADER_TITLE 
									FROM TBL_ADMIN_INFO A 
											 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '".$year."' 
									WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
									AND D.LEADER_YN = 'Y' 
									AND IFNULL(D.HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
									AND IFNULL(D.OCCUPATION_CODE, '') = '".$occupation_code."' 
									AND IFNULL(D.DEPT_CODE, '') = '".$dept_code."' 
									AND D.LEVEL = '".$level."'"; 
			} else {
				$query = "SELECT A.ADM_NO, A.ADM_NAME, A.ADM_EMAIL, D.HEADQUARTERS_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, D.POSITION_CODE, D.LEADER_TITLE 
									FROM TBL_ADMIN_INFO A 
											 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '".$year."' 
									WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
									AND D.LEADER_YN = 'Y' 
									AND IFNULL(D.HEADQUARTERS_CODE, '') = '".$headquarters_code."' 
									AND IFNULL(D.OCCUPATION_CODE, '') = 'UX' 
									AND IFNULL(D.DEPT_CODE, '') = '".$dept_code."' 
									AND D.LEVEL = '".$level."'"; 
			}
		}
		
		if ($level == 1) {
			$query = "SELECT A.ADM_NO, A.ADM_NAME, A.ADM_EMAIL, D.HEADQUARTERS_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, D.POSITION_CODE, D.LEADER_TITLE 
									FROM TBL_ADMIN_INFO A 
											 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO
									WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
									AND D.LEADER_YN = 'Y' 
									AND IFNULL(D.HEADQUARTERS_CODE, '') = '' 
									AND IFNULL(D.OCCUPATION_CODE, '') = '' 
									AND D.LEVEL = '".$level."'"; 
		}

 //echo $query."<br>";
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

//관리자용 승인위치 모두 출력
	function selectAdminLeaderAll($db, $year){

		$query = "SELECT A.ADM_NO, A.ADM_NAME, D.HEADQUARTERS_CODE, D.DEPT_CODE, D.POSITION_CODE, D.LEADER_TITLE , D.LEVEL 
							FROM TBL_ADMIN_INFO A
									 LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '".$year."'
							WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
							AND D.LEADER_YN = 'Y' 
							ORDER BY FIELD(D.POSITION_CODE,'대표이사','이사','수석','책임','선임'), FIELD(D.LEADER_TITLE,'재무관리','이사','본부장','부문장','팀장','유닛장')";

// echo $query;
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

	//leader인지 판단
	function selectAdminLeaderYN($db, $adm_no, $year){

		$query  = "SELECT * FROM TBL_ORG 
							 WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND ADM_NO ='".$adm_no."' AND YEAR='".$year."'";

		$result = mysql_query($query, $db);
		$record = mysql_fetch_array($result);

		return $record;
	}

	function selectAdminEmail($db, $ex_no) { //지출결의승인 메일용. admin email 찾기는 아래쪽 selectAdminEmail2() 함수 이용!
		
		$query = "SELECT ADM_EMAIL FROM TBL_ADMIN_INFO WHERE ADM_NO = (SELECT VA_USER FROM TBL_EXPENSE WHERE EX_NO = $ex_no)";

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

	function selectAdminNo($db, $adm_name){

		$query = "SELECT ADM_NO FROM TBL_ADMIN_INFO WHERE ADM_NAME = '$adm_name' AND USE_TF ='Y' AND DEL_TF='N'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminEmail2($db, $adm_no) {
		
		$query = "SELECT ADM_EMAIL FROM TBL_ADMIN_INFO WHERE ADM_NO = $adm_no";

//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminHPhone($db, $adm_no) {
		
		$query = "SELECT ADM_HPHONE FROM TBL_ADMIN_INFO WHERE ADM_NO = $adm_no";

//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminProfile($db, $adm_no) {
		
		$query = "SELECT PROFILE FROM TBL_ADMIN_INFO WHERE ADM_NO = $adm_no";

//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminIdProfile($db, $adm_id) {
		
		$query = "SELECT PROFILE FROM TBL_ADMIN_INFO WHERE ADM_ID = '$adm_id'";

//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminApprovalRightLeader($db, $adm_no) {

		$query = "SELECT ADM_NO FROM TBL_ADMIN_APPROVAL_RIGHT WHERE ADM_NO = $adm_no AND APP_TYPE='0' AND APP_RIGHT = '1'";

//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function selectAdminVacation($db, $adm_no) { //연차, 스마트데이 알림 함수! 사용자보기용 

		$query = "SELECT COUNT(*) FROM TBL_VACATION_DATE WHERE VA_USER = '$adm_no' AND VA_DATE = LEFT(CURDATE(), 10)"; //휴일알림때문에 추가

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		if ($record <> 0) {

			//$query = "SELECT VA_TYPE FROM TBL_VACATION WHERE VA_USER = '$adm_no' AND CURDATE() = VA_SDATE AND CURDATE() <= VA_EDATE AND DEL_TF = 'N'";
			$query = "SELECT VA_TYPE FROM TBL_VACATION WHERE DEL_TF = 'N' AND SEQ_NO = (SELECT SEQ_NO FROM TBL_VACATION_DATE WHERE VA_DATE = CURDATE() AND VA_USER = '$adm_no')";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$record  = $rows[0];
			return $record;
		}
	}
	
	function updatePassword($db, $adm_no, $str_resetpasswd){
		$query="UPDATE TBL_ADMIN_INFO SET 
							PASSWD			= '$str_resetpasswd',							
							UP_DATE			= now()
				 WHERE ADM_NO			= '$adm_no' ";
		// echo $query;
		
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	
	}

?>
