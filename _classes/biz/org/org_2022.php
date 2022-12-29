<?

	# =============================================================================
	# File Name    : org_2022.php
	# Modlue       : 
	# Writer       : JeGal Jeong
	# Create Date  : 2021-11-10
	# Modify Date  : 
	#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================

	function listOrg($db, $use_tf, $del_tf, $yyyy){
		$query = "SELECT A.ADM_NO, A.ADM_NAME, A.PROFILE, 
										 B.HEADQUARTERS_CODE, B.DEPT_CODE, B.POSITION_CODE, B.OCCUPATION_CODE, B.LEADER_YN, B.LEADER_TITLE, B.DEPT_UNIT_NAME 
							FROM TBL_ADMIN_INFO A 
                   LEFT OUTER JOIN TBL_ORG B ON A.ADM_NO = B.ADM_NO AND B.YEAR = '2022'
							WHERE A.USE_TF = 'Y' AND A.DEL_TF = 'N' 
							ORDER BY FIELD(B.POSITION_CODE,'대표이사','이사','수석','책임','선임','전임')";

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

	function listOrgHead($db, $use_tf, $del_tf, $yyyy){
		$query = "SELECT DISTINCT HEADQUARTERS_CODE FROM TBL_ORG 
							 WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' 
							 ORDER BY FIELD(HEADQUARTERS_CODE,'경영기획팀', '기업부설연구소', 'PR', 'UX연구소', '플랫폼사업본부', '서비스 운영 본부', '디지털 구축 본부')";

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


	function listOrgDept($db, $headquarters_code, $yyyy) {

		$query = "SELECT DISTINCT(DEPT_CODE) FROM TBL_ORG 
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' 
							AND HEADQUARTERS_CODE = '$headquarters_code' AND YEAR = '$yyyy' 
							ORDER BY FIELD(DEPT_CODE,'운영1 TFT','운영2 TFT','운영3 TFT','데이터분석팀','구축1 TFT','구축2 TFT','구축3 TFT','구축4 TFT','구축5 TFT','구축6 TFT','기획팀','UX팀','퍼블리싱팀','개발팀','프론트엔드','백엔드')";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function selectOrgHeadLeader($db, $headquarters_code, $yyyy, $occu) {

		$query = "SELECT ADM_NO, POSITION_CODE, 
						 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS ADM_NAME, 
						 (SELECT PROFILE FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS PROFILE 
							FROM TBL_ORG A
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' 
							AND HEADQUARTERS_CODE = '$headquarters_code' 
							AND LEADER_YN= 'Y' AND LEADER_TITLE = '$occu'";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function SelectOrgDeptLeader($db, $headquarters_code, $dept_code, $yyyy) {

		$query = "SELECT ADM_NO FROM TBL_ORG A
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' 
							AND HEADQUARTERS_CODE = '$headquarters_code' AND DEPT_CODE ='$dept_code' 
							AND LEADER_YN= 'Y' AND LEADER_TITLE = '팀장'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function SelectOrgUnitLeader($db, $headquarters_code, $dept_code, $dept_unit_name, $yyyy) {

		$query = "SELECT ADM_NO FROM TBL_ORG A
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' 
							AND HEADQUARTERS_CODE = '$headquarters_code' AND DEPT_CODE ='$dept_code' 
							AND DEPT_UNIT_NAME = '$dept_unit_name' 
							AND LEADER_YN= 'Y' AND LEADER_TITLE = '유닛장'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listOrgUnit($db, $headquarters_code, $dept_code, $yyyy){

		$query = "SELECT ADM_NO, POSITION_CODE, OCCUPATION_CODE, LEADER_YN, LEADER_TITLE, DEPT_UNIT_NAME, 
							(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS ADM_NAME, 
							(SELECT PROFILE FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS PROFILE 
							FROM TBL_ORG A
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' AND HEADQUARTERS_CODE = '$headquarters_code' AND DEPT_CODE ='$dept_code' AND LEADER_TITLE NOT IN ('대표이사','본부장','이사') 
							ORDER BY DEPT_UNIT_NAME ASC, FIELD(LEADER_TITLE, '이사','본부장','팀장','유닛장',''), FIELD(POSITION_CODE,'이사','수석','책임','선임','전임'), ADM_NO ASC";

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


?>