<?

/*
CREATE TABLE IF NOT EXISTS TBL_WEEK_REPORT (
  SEQ_NO int(11) unsigned NOT NULL AUTO_INCREMENT,
  USER_NO int(11),
  WE_DATE varchar(15) NOT NULL DEFAULT '',
  WE_WEEK varchar(15) NOT NULL DEFAULT '',
  WE_FILE varchar(50) DEFAULT NULL,
  WE_RFILE varchar(50) DEFAULT NULL,
  DEL_TF char(1) NOT NULL DEFAULT 'N',
  REG_ADM int(11) unsigned DEFAULT NULL,
  REG_DATE datetime DEFAULT NULL,
  UP_ADM int(11) unsigned DEFAULT NULL,
  UP_DATE datetime DEFAULT NULL,
  DEL_ADM int(11) unsigned DEFAULT NULL,
  DEL_DATE datetime DEFAULT NULL,
  PRIMARY KEY (SEQ_NO)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

*/

	function listUserWeekReport ($db, $in_we_user, $out_we_user, $headquarters_code, $dept_code, $we_user) {
		
		$query = "SELECT A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_FLAG, A.COM_CODE, A.ENTER_DATE,
										 D.POSITION_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, 
										 D.HEADQUARTERS_CODE, D.LEADER_YN, D.LEVEL, D.DEPT_UNIT_NAME, D.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME
								FROM TBL_ADMIN_INFO A 
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206'
										 LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON D.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE GROUP_NO = '4'
								 AND A.USE_TF = 'Y' AND A.DEL_TF ='N'";

		if ($in_we_user <> "") {
			$query .= " AND A.ADM_NO = '".$in_we_user."' ";
		}

		if ($out_we_user <> "") {
			$query .= " AND A.ADM_NO <> '".$out_we_user."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}

		if ($we_user <> "") {
			$query .= " AND A.ADM_NO = '".$we_user."' ";
		}

		$query .= " ORDER BY H.DCODE_SEQ_NO ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC ";

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

	function listUserWeekReport_1 ($db, $in_we_user, $out_we_user, $headquarters_code, $dept_code, $we_user, $con_headquarters_code, $con_dept_code) {

		$query = "SELECT A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_FLAG, 
										 D.POSITION_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, 
										 D.HEADQUARTERS_CODE, D.LEADER_YN, D.LEVEL, D.DEPT_UNIT_NAME, D.LEADER_TITLE,
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO as ORDER_POSITION, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME, 
                     CASE WHEN D.DEPT_CODE = '".$dept_code."' THEN '1' ELSE '2' 
										 END AS TEMP
    					FROM TBL_ADMIN_INFO A 
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206'
										 LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON D.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION' 
 						  WHERE GROUP_NO = '4' AND A.USE_TF = 'Y' AND A.DEL_TF ='N' ";

		if ($in_we_user <> "") {
			$query .= " AND A.ADM_NO = '".$in_we_user."' ";
		}

		if ($out_we_user <> "") {
			$query .= " AND A.ADM_NO <> '".$out_we_user."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($_SESSION['s_adm_nm'] == "현창하"){  //본부장 2개의 본부
			$query .= " OR D.HEADQUARTERS_CODE = '서비스 운영 2본부' ";
		}

		if ($_SESSION['s_adm_nm'] == "엄지용"){  //이사 2개의 본부
			$query .= " OR D.HEADQUARTERS_CODE = '디지털 구축 2본부' ";
		}

    if ( ( strpos($_SESSION['s_adm_position_code'],"대표") === false ) and ( strpos($_SESSION['s_adm_position_code'],"팀장") === false ) and ($_SESSION['s_adm_nm'] <> "시스템관리자") and ($_SESSION['s_adm_nm'] <> "유컴관리자") and ($_SESSION['s_adm_nm'] <> "본부관리자") and ($_SESSION['s_adm_nm'] <> "팀관리자") ){
					$query .= " AND D.DEPT_CODE <> '' ";
		} 
		else {
			
			if($con_headquarters_code == "") {
  			$query .= " AND D.HEADQUARTERS_CODE <> '' ";
			} else {
				$query .= " AND D.HEADQUARTERS_CODE = '".$con_headquarters_code."' ";
			}

			if($con_dept_code == "") {
				$query .= " AND D.DEPT_CODE <> '' ";
			} else {
				$query .= " AND D.DEPT_CODE = '".$con_dept_code."' ";
			}

		}

		if ($we_user <> "") {
			$query .= " AND A.ADM_NO = '".$we_user."' ";
		}
		$query .= " ORDER BY H.DCODE_SEQ_NO ASC, TEMP ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC ";


		//echo $query;
		//echo $we_user."**";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function addWeekReport($db, $user_no, $we_week, $file_nm, $file_rnm, $reg_adm) {
		
		$query = "SELECT COUNT(SEQ_NO) AS CNT FROM TBL_WEEK_REPORT WHERE DEL_TF = 'N' AND WE_WEEK = '$we_week' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		if ($rows[0] == 0) {
			// insert 
			
			$query = "INSERT INTO TBL_WEEK_REPORT (USER_NO, WE_DATE, WE_WEEK, WE_FILE, WE_RFILE, REG_ADM, REG_DATE) VALUES 
								('$user_no', '$we_week', '$we_week', '$file_nm', '$file_rnm', '$reg_adm', now())";

		} else {
			// update
			$query = "UPDATE TBL_WEEK_REPORT SET WE_FILE = '$file_nm', WE_RFILE = '$file_rnm', UP_ADM = '$reg_adm', UP_DATE = now() WHERE WE_WEEK = '$we_week' AND DEL_TF = 'N' ";

		}

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getWeekReport($db, $we_week) {

		$str = "";

		$query = "SELECT * FROM TBL_WEEK_REPORT WHERE DEL_TF = 'N' AND WE_WEEK = '$we_week' "; 

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);
			$SEQ_NO			= Trim($row["SEQ_NO"]);

			$str = "<a href='/_common/new_download_file.php?menu=weekreport&seq_no=".$SEQ_NO."' title='다운로드'>다운로드</a>";
		}

		return $str;
	}

	function selectWeekReport($db, $seq_no) {

		$query = "SELECT * FROM TBL_WEEK_REPORT WHERE DEL_TF = 'N' AND SEQ_NO = '$seq_no' "; 

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