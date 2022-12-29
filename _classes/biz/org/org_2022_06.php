<?

	# =============================================================================
	# File Name    : org_2022_06.php
	# Modlue       : 
	# Writer       : JeGal Jeong
	# Create Date  : 2022-06-10
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
                   LEFT OUTER JOIN TBL_ORG B ON A.ADM_NO = B.ADM_NO AND B.YEAR = '202206'
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
							 ORDER BY FIELD(HEADQUARTERS_CODE,'경영기획팀', 'PR', 'UX연구소','사업 서비스실','사업 관리실', '플랫폼개발실')";

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
							ORDER BY FIELD(DEPT_CODE,'Date Team','기획 1팀','기획 2팀','기획 3팀','기획 4팀','기획 5팀','Data Analytics Team','UXD 1팀','UXD 2팀','UXP 1팀','BACKEND 1팀','BACKEND 2팀','SKB 사업 1팀','SKB 사업 2팀','사업관리팀','Frontend Team','Cloud Native Team')";

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

	function listOrgPartDept($db, $headquarters_code, $occupation_code, $yyyy) {

		if ($occupation_code == "UX") {

			$query = "SELECT DISTINCT(DEPT_CODE) FROM TBL_ORG 
								WHERE USE_TF = 'Y' AND DEL_TF = 'N' 
								AND HEADQUARTERS_CODE = '$headquarters_code' AND YEAR = '$yyyy' AND OCCUPATION_CODE IN ('UX', '프론트엔드') 
								ORDER BY FIELD(DEPT_CODE,'Date Team','기획 1팀','기획 2팀','기획 3팀','기획 4팀','기획 5팀','Data Analytics Team','UXD 1팀','UXD 2팀','UXP 1팀','BACKEND 1팀','BACKEND 2팀','SKB 사업 1팀','SKB 사업 2팀','사업관리팀','Frontend Team','Cloud Native Team')";

		} else {

			$query = "SELECT DISTINCT(DEPT_CODE) FROM TBL_ORG 
								WHERE USE_TF = 'Y' AND DEL_TF = 'N' 
								AND HEADQUARTERS_CODE = '$headquarters_code' AND YEAR = '$yyyy' AND OCCUPATION_CODE = '$occupation_code' 
								ORDER BY FIELD(DEPT_CODE,'Date Team','기획 1팀','기획 2팀','기획 3팀','기획 4팀','기획 5팀','Data Analytics Team','UXD 1팀','UXD 2팀','UXP 1팀','BACKEND 1팀','BACKEND 2팀','SKB 사업 1팀','SKB 사업 2팀','사업관리팀','Frontend Team','Cloud Native Team')";
		}

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

	function selectOrgPartLeader($db, $headquarters_code, $yyyy, $occu) {

		$query = "SELECT ADM_NO, POSITION_CODE, OCCUPATION_CODE, 
						 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS ADM_NAME, 
						 (SELECT PROFILE FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS PROFILE 
							FROM TBL_ORG A
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' 
							AND HEADQUARTERS_CODE = '$headquarters_code' 
							AND LEADER_YN= 'Y' AND LEADER_TITLE = '$occu' 
							ORDER BY FIELD(OCCUPATION_CODE,'기획','UX','백엔드')";

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

		$query = "SELECT ADM_NO, DEPT_CODE, POSITION_CODE, OCCUPATION_CODE, LEADER_YN, LEADER_TITLE, DEPT_UNIT_NAME, 
							(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS ADM_NAME, 
							(SELECT PROFILE FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS PROFILE 
							FROM TBL_ORG A
							WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' AND HEADQUARTERS_CODE = '$headquarters_code' AND DEPT_CODE ='$dept_code' AND LEADER_TITLE NOT IN ('대표이사','본부장','이사', '부문장') 
							ORDER BY DEPT_UNIT_NAME ASC, FIELD(LEADER_TITLE, '이사','본부장','부문장','팀장','유닛장',''), FIELD(POSITION_CODE,'이사','수석','책임','선임','전임'), ADM_NO ASC";

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


	function listOrgPartUnit($db, $headquarters_code, $dept_code, $occupation_code, $yyyy){

		if ($occupation_code == "UX") {

			$query = "SELECT ADM_NO, DEPT_CODE, POSITION_CODE, OCCUPATION_CODE, LEADER_YN, LEADER_TITLE, DEPT_UNIT_NAME, 
								(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS ADM_NAME, 
								(SELECT PROFILE FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS PROFILE 
								FROM TBL_ORG A
								WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' AND HEADQUARTERS_CODE = '$headquarters_code' 
								AND DEPT_CODE ='$dept_code' AND OCCUPATION_CODE IN ('UX', '프론트엔드') AND LEADER_TITLE NOT IN ('대표이사','본부장','이사', '부문장') 
								ORDER BY DEPT_UNIT_NAME ASC, FIELD(LEADER_TITLE, '이사','본부장','부문장','팀장','유닛장',''), FIELD(POSITION_CODE,'이사','수석','책임','선임','전임'), ADM_NO ASC";

		} else {

			$query = "SELECT ADM_NO, DEPT_CODE, POSITION_CODE, OCCUPATION_CODE, LEADER_YN, LEADER_TITLE, DEPT_UNIT_NAME, 
								(SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS ADM_NAME, 
								(SELECT PROFILE FROM TBL_ADMIN_INFO WHERE TBL_ADMIN_INFO.ADM_NO = A.ADM_NO) AS PROFILE 
								FROM TBL_ORG A
								WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND YEAR = '$yyyy' AND HEADQUARTERS_CODE = '$headquarters_code' 
								AND DEPT_CODE ='$dept_code' AND OCCUPATION_CODE='$occupation_code' AND LEADER_TITLE NOT IN ('대표이사','본부장','이사', '부문장') 
								ORDER BY DEPT_UNIT_NAME ASC, FIELD(LEADER_TITLE, '이사','본부장','부문장','팀장','유닛장',''), FIELD(POSITION_CODE,'이사','수석','책임','선임','전임'), ADM_NO ASC";

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

?>