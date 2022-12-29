<?

	# =============================================================================
	# File Name    : admin.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2018-12-11
	# Modify Date  : 
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
								FROM TBL_ADMIN_GROUP A WHERE 1 = 1 ";
		

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

		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_GROUP WHERE 1 = 1 ";
		
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
		
		$query="INSERT INTO TBL_ADMIN_GROUP (GROUP_NAME, GROUP_FLAG, USE_TF, REG_ADM, REG_DATE) 
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
		
		$query="UPDATE TBL_ADMIN_GROUP SET 
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

		$query="UPDATE TBL_ADMIN_INFO_TEST SET 
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

		$query="UPDATE TBL_ADMIN_GROUP SET 
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
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_FLAG, A.POSITION_CODE, A.DEPT_CODE, A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 A.HEADQUARTERS_CODE, A.LEADER_YN, A.LEVEL, A.DEPT_GROUP_NAME,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME 
								FROM TBL_NEW_ADMIN_INFO A 
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON A.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON A.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1 ";


/*
		$query = "SELECT @rownum:= @rownum + 1  as rn, ADM_ID, ADM_NO, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, 
										 GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE, ENTER_DATE, OUT_DATE, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_INFO_TEST A WHERE 1 = 1 ";
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


	function totalCntAdmin ($db, $group_no, $com_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_NEW_ADMIN_INFO A WHERE 1 = 1 ";
		
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

		$query ="SELECT IFNULL(MAX(ADM_NO),0) + 1 AS MAX_NO FROM TBL_NEW_ADMIN_INFO";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		$new_adm_no = $rows[0];

		$query = "INSERT INTO TBL_NEW_ADMIN_INFO (ADM_NO, ".$set_field.", REG_DATE, UP_DATE) 
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

		$query = "SELECT ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, PROFILE, 
										 GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE, ENTER_DATE, OUT_DATE,
										 HEADQUARTERS_CODE, LEADER_YN, LEVEL, DEPT_GROUP_NAME,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_NEW_ADMIN_INFO  WHERE ADM_NO = '$adm_no' ";
		
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

		$query = "UPDATE TBL_NEW_ADMIN_INFO SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "ADM_NO = '$adm_no' WHERE ADM_NO = '$adm_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateAdminPwd($db, $passwd, $up_adm, $adm_no) {
		
		$query="UPDATE TBL_NEW_ADMIN_INFO SET 
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

		$query="UPDATE TBL_NEW_ADMIN_INFO SET 
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
		
		$query ="SELECT COUNT(*) CNT FROM TBL_NEW_ADMIN_INFO WHERE 1 = 1 ";
		
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
	
		$query = "SELECT ADM_NO, ADM_ID, PASSWD, ADM_NAME, ADM_EMAIL, GROUP_NO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, COM_CODE,
										 POSITION_CODE, DEPT_CODE, PROFILE,
										 (SELECT CP_TYPE FROM TBL_COMPANY WHERE TBL_COMPANY.CP_NO = A.COM_CODE) AS CP_TYPE,
										 ORGANIZATION
								FROM TBL_NEW_ADMIN_INFO A WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND ADM_ID = '$adm_id' ";
		
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
		
		$query="UPDATE TBL_NEW_ADMIN_INFO SET 
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
								FROM TBL_ADMIN_MENU_RIGHT WHERE GROUP_NO = '$group_no' ";

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
		
		$query="DELETE FROM TBL_ADMIN_MENU_RIGHT WHERE GROUP_NO			= '$group_no' ";

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
		
		$query="INSERT INTO TBL_ADMIN_MENU_RIGHT (GROUP_NO, MENU_CD, READ_FLAG, REG_FLAG, UPD_FLAG, DEL_FLAG, FILE_FLAG, TOP_FLAG, MAIN_FLAG) 
																		  VALUES ('$group_no', '$menu_cd', '$read_chk', '$reg_chk', '$upd_chk', '$del_chk', '$file_chk' , '$top_chk' , '$main_chk')";
		#echo $query."<br>";

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
										 (SELECT ADM_NAME FROM TBL_NEW_ADMIN_INFO WHERE ADM_ID = TBL_USER_LOG.LOG_ID) AS ADM_NAME
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
?>