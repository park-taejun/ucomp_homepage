<?

	# =============================================================================
	# File Name    : org.php
	# Modlue       : 
	# Writer       : JeGal Jeong
	# Create Date  : 2020-12-28
	# Modify Date  : 2021-01-29
	#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function listOrg($db, $use_tf, $del_tf){
		$query = "SELECT * FROM TBL_ADMIN_INFO 
							WHERE USE_TF = 'Y' AND DEL_TF = 'N'
							ORDER BY FIELD(POSITION_CODE,'대표이사','이사','수석','책임','선임','전임')";

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

	function listOrgDept($db, $use_tf, $del_tf){
		$query = "SELECT DISTINCT HEADQUARTERS_CODE FROM TBL_ADMIN_DEPT_UNIT 
							 WHERE USE_TF = 'Y' AND DEL_TF = 'N'
							 ORDER BY FIELD(HEADQUARTERS_CODE,'전략사업본부(현창하 수석)', '대외사업본부','기술사업본부(CTO겸임)','기업부설연구소')";

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

	function listOrgDeptUnit($db, $headquarters_code) {

		$query = "SELECT DISTINCT(DEPT_CODE) FROM TBL_ADMIN_DEPT_UNIT 
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' 
							AND HEADQUARTERS_CODE = '$headquarters_code'
							ORDER BY DEPT_CODE DESC";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function listOrgDeptUnitName($db, $dept_code){
		$query = "SELECT DEPT_UNIT_NAME FROM TBL_ADMIN_DEPT_UNIT 
							 WHERE USE_TF = 'Y' AND DEL_TF = 'N'
							 AND DEPT_CODE = '$dept_code'

							 ORDER BY DEPT_UNIT_NAME ASC";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listOrgUnit($db, $dept_code, $dept_unit_name){
		$query = "SELECT * FROM TBL_ADMIN_INFO 
								 WHERE USE_TF = 'Y' AND DEL_TF = 'N'
									 AND DEPT_CODE ='$dept_code' AND DEPT_UNIT_NAME = '$dept_unit_name'
							ORDER BY FIELD(POSITION_CODE,'대표이사','이사','수석','책임','선임','전임')";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function listMember($db, $headquarters_code, $dept_code, $dept_unit_name){
		$query = "SELECT * FROM TBL_ADMIN_INFO 
							WHERE USE_TF = 'Y' AND DEL_TF = 'N'
								AND HEADQUARTERS_CODE = '$headquarters_code'
								AND DEPT_CODE = '$dept_code'
								AND DEPT_UNIT_NAME = '$dept_unit_name'
								AND LEADER_TITLE <> '이사'
								AND LEADER_TITLE <> '본부장'
								AND LEADER_TITLE <> '팀장'
					 ORDER BY FIELD(POSITION_CODE,'대표이사','이사','수석','책임','선임','전임')";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function selectDeptLeader($db, $headquarters_code, $dept_code) {
		if (($headquarters_code == "") && ($dept_code == "")) {
			$leader_title = "대표이사";
		} else if ($dept_code == "") {
				$leader_title = "이사";
		} else {
				$leader_title = "팀장";
		}

		$query = "SELECT * FROM TBL_ADMIN_INFO 
							 WHERE USE_TF = 'Y' AND DEL_TF ='N' 
							 AND HEADQUARTERS_CODE = '$headquarters_code' 
							 AND DEPT_CODE = '$dept_code' 
							 AND LEADER_TITLE = '$leader_title'";

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