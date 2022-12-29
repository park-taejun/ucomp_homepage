<?

/*
CREATE TABLE IF NOT EXISTS TBL_PROJECT (
  PROJECT_NO int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  PROJECT_NM varchar(60) NOT NULL DEFAULT '' COMMENT '이름',
  START_DATE datetime DEFAULT NULL COMMENT '시작일',
  END_DATE datetime DEFAULT NULL COMMENT '종료일',
  PROJECT_ROLL varchar(50) NOT NULL DEFAULT '' COMMENT '프로젝트 구분',
  PROJECT_STATE varchar(2) NOT NULL DEFAULT '' COMMENT '프로젝트 상태',
  BAR_COLOR varchar(20) NOT NULL,
  PROJECT_MEMO text NOT NULL,
  DEL_TF char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  REG_ADM int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
  REG_DATE datetime DEFAULT NULL COMMENT '등록일',
  UP_ADM int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
  UP_DATE datetime DEFAULT NULL COMMENT '수정일',
  DEL_ADM int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
  DEL_DATE datetime DEFAULT NULL COMMENT '삭제일',
  PRIMARY KEY (PROJECT_NO)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
*/

	function selectProject($db, $project_no) {

		$query = "SELECT *
								FROM TBL_PROJECT WHERE PROJECT_NO = '$project_no' ";
		
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

	function selectProjectChild($db, $project_child_no) {

		$query = "SELECT A.PROJECT_NO, A.PROJECT_NM, A.BAR_COLOR, B.PROJECT_CHILD_NO, 
										 left(B.START_DATE,10) AS START_DATE, left(B.END_DATE,10) AS END_DATE, B.PROJECT_ROLL, B.PROJECT_STATE, B.PROJECT_MEMO 
								FROM TBL_PROJECT A, TBL_PROJECT_CHILD B 
							 WHERE A.PROJECT_NO = B.PROJECT_NO 
								 AND PROJECT_CHILD_NO = '$project_child_no' ";
		
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

	function listProject($db) {

		$query = "SELECT A.PROJECT_NO, A.PROJECT_NM, A.BAR_COLOR,
										 A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 (SELECT MIN(START_DATE) FROM TBL_PROJECT_CHILD WHERE PROJECT_NO = A.PROJECT_NO) AS START_DATE
								FROM TBL_PROJECT A WHERE A.DEL_TF = 'N' 
							 ORDER BY START_DATE ASC ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listProjectYear($db, $year) {

		$query = "SELECT A.PROJECT_NO, A.PROJECT_NM, A.BAR_COLOR,
										 A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.START_DATE, B.END_DATE
								FROM TBL_PROJECT A, TBL_PROJECT_CHILD B 
							 WHERE A.PROJECT_NO = B.PROJECT_NO 
								 AND A.DEL_TF = 'N'
								 AND B.DEL_TF = 'N'
								 AND (B.START_DATE LIKE '".$year."%' OR B.END_DATE LIKE '".$year."%')
							 ORDER BY B.START_DATE ASC ";
		
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

	function listProjectChild($db) {

		$query = "SELECT PROJECT_NO, PROJECT_CHILD_NO, START_DATE, END_DATE, PROJECT_ROLL, PROJECT_STATE, PROJECT_MEMO,
										 DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_PROJECT_CHILD WHERE DEL_TF = 'N' ";

		$query .= " ORDER BY PROJECT_NO ASC ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertProject($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_PROJECT (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

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

	function insertProjectChild($db, $arr_data) {
		
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

		$query = "INSERT INTO TBL_PROJECT_CHILD (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";

		//echo $query."<br>"; 

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

	function updateProject($db, $arr_data, $project_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_PROJECT SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "PROJECT_NO = '$project_no' WHERE PROJECT_NO = '$project_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateProjectChild($db, $arr_data, $project_child_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_PROJECT_CHILD SET ".$set_query_str." ";
		$query .= "UP_DATE = now(), ";
		$query .= "PROJECT_CHILD_NO = '$project_child_no' WHERE PROJECT_CHILD_NO = '$project_child_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteProject($db, $project_no) {

		$query="UPDATE TBL_PROJECT SET 
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE PROJECT_NO = '$project_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteProjectChild($db, $project_child_no) {

		$query="UPDATE TBL_PROJECT_CHILD SET 
									 DEL_TF				= 'Y',
									 DEL_DATE			= now()
						 WHERE PROJECT_CHILD_NO = '$project_child_no'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
?>