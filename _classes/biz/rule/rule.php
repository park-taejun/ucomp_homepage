<?
	# =============================================================================
	# File Name    : rule.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-04-24
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_RULE
	#=========================================================================================================

	/*
CREATE TABLE IF NOT EXISTS TBL_RULE (
  RULE_NO int(11) unsigned NOT NULL DEFAULT '0' COMMENT '사규 SEQ',
  RULE_TYPE varchar(15) NOT NULL COMMENT '사규 언어',
  RULE_CD varchar(15) NOT NULL DEFAULT '' COMMENT '사규 코드',
  RULE_NAME varchar(100) NOT NULL COMMENT '사규명',
  RULE_SEQ01 varchar(3) NOT NULL DEFAULT '' COMMENT '사규 순서 1',
  RULE_SEQ02 varchar(3) NOT NULL DEFAULT '' COMMENT '사규 순서 2',
  RULE_SEQ03 varchar(3) NOT NULL DEFAULT '' COMMENT '사규 순서 3',
  RULE_SEQ04 varchar(3) NOT NULL DEFAULT '' COMMENT '사규 순서 4',
  RULE_SEQ05 varchar(3) NOT NULL DEFAULT '' COMMENT '사규 순서 5',
  RULE_CONTENT longtext NOT NULL COMMENT '내용',
  RULE_INFO01 varchar(100) NOT NULL DEFAULT '' COMMENT '임시',
  RULE_INFO02 varchar(100) NOT NULL DEFAULT '' COMMENT '임시',
  RULE_INFO03 varchar(100) NOT NULL DEFAULT '' COMMENT '임시',
  USE_TF char(1) NOT NULL DEFAULT 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
  DEL_TF char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  REG_ADM int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
  REG_DATE datetime DEFAULT NULL COMMENT '등록일',
  UP_ADM int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
  UP_DATE datetime DEFAULT NULL COMMENT '수정일',
  DEL_ADM int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
  DEL_DATE datetime DEFAULT NULL COMMENT '삭제일'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listRule($db, $rule_type, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT CONCAT(RULE_SEQ01,RULE_SEQ02,RULE_SEQ03,RULE_SEQ04,RULE_SEQ05) as SEQ,
										 RULE_NO, RULE_TYPE, RULE_CD, RULE_NAME, RULE_SEQ01, RULE_SEQ02, RULE_SEQ03, RULE_SEQ04, RULE_SEQ05,
										 RULE_CONTENT, RULE_INFO01, RULE_INFO02, RULE_INFO03, 
										 USE_TF, DEL_TF
							FROM TBL_RULE WHERE 1 = 1 ";

		if ($rule_type <> "") {
			$query .= " AND RULE_TYPE = '".$rule_type."' ";
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

		$query .= " ORDER BY SEQ ASC ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		for($i=0;$i < mysql_num_rows($result);$i++) {
			$record[$i] = sql_result_array($result,$i);
		}
		return $record;
	}

	function listSubRule($db, $rule_type, $depth, $use_tf, $del_tf) {
		
		$query = "SELECT CONCAT(RULE_SEQ01,RULE_SEQ02,RULE_SEQ03,RULE_SEQ04,RULE_SEQ05) as SEQ,
										 RULE_NO, RULE_TYPE, RULE_CD, RULE_NAME, RULE_SEQ01, RULE_SEQ02, RULE_SEQ03, RULE_SEQ04, RULE_SEQ05,
										 RULE_CONTENT, RULE_INFO01, RULE_INFO02, RULE_INFO03, 
										 USE_TF, DEL_TF
							FROM TBL_RULE WHERE 1 = 1 ";

		if ($rule_type <> "") {
			$query .= " AND RULE_TYPE = '".$rule_type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($depth <> "") {
			$query .= " AND length(RULE_CD) = '".$depth."' ";
		}

		$query .= " ORDER BY SEQ ASC ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		for($i=0;$i < mysql_num_rows($result);$i++) {
			
			$record[$i] = sql_result_array($result,$i);
		}
		return $record;
	}

	/*	페이지 등록*/

	function insertRule($db, $rule_type, $m_level, $m_seq01, $m_seq02, $m_seq03, $m_seq04, $rule_name, $rule_content, $rule_info01, $rule_info02, $rule_info03, $use_tf, $reg_adm) {

		$iMax = "0";	

		$sSeq01		= "";
		$sSeq02		= "";
		$sSeq03		= "";
		$sSeq04		= "";
		$sSeq05		= "";
		$sSeq_01	= "";
		$sSeq_02	= "";
		$sSeq_03	= "";
		$sSeq_04	= "";
		$sSeq_05	= "";
		$sMenu_cd	= "";
		
		if (strlen($m_level) == 0) { 
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(RULE_CD,1,2)),0) + 1),-2) as M_CD FROM TBL_RULE ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq02 = "00";
			$sSeq03 = "00";
			$sSeq04 = "00";
			$sSeq05 = "00";

			$sMenu_cd = $row["M_CD"];

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(RULE_SEQ01),0) + 1),-2) as SEQ FROM TBL_RULE ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_01 = $row["SEQ"];

			$sSeq_02 = "00";
			$sSeq_03 = "00";
			$sSeq_04 = "00";
			$sSeq_05 = "00";

		}

		if (strlen($m_level) == 2) { 
			
			 $sSeq01 = $m_level;

			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(RULE_CD,3,2)),0) + 1),-2) as M_CD FROM TBL_RULE WHERE substring(RULE_CD,1,2) = '$m_level' ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);

			$sSeq02 = $row["M_CD"];

			$sSeq03 = "00";
			$sSeq04 = "00";
			$sSeq05 = "00";

			$sMenu_cd = $sSeq01.$sSeq02;

			$sSeq_01 = $m_seq01;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(RULE_SEQ02),0) + 1),-2) as SEQ FROM TBL_RULE WHERE substring(RULE_CD,1,2) = '$m_level' ";

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_02 = $row["SEQ"];
			$sSeq_03 = "00";
			$sSeq_04 = "00";
			$sSeq_05 = "00";

		}

		if (strlen($m_level) == 4) { 

			$sSeq01 = substr($m_level,0,2);
			$sSeq02 = substr($m_level,2,2);
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(RULE_CD,5,2)),0) + 1),-2) as M_CD FROM TBL_RULE WHERE substring(RULE_CD,1,2) = '".substr($m_level,0,2)."' and substring(RULE_CD,3,2) = '".substr($m_level,2,2)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq03 = $row["M_CD"];
			$sSeq04 = "00";
			$sSeq05 = "00";
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(RULE_SEQ03),0) + 1),-2) as SEQ FROM TBL_RULE WHERE substring(RULE_CD,1,2) = '".substr($m_level,0,2)."' and substring(RULE_CD,3,2) = '".substr($m_level,2,2)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_03 = $row["SEQ"];
			$sSeq_04 = "00";
			$sSeq_05 = "00";

		}

		if (strlen($m_level) == 6) { 

			$sSeq01 = substr($m_level,0,2);
			$sSeq02 = substr($m_level,2,2);
			$sSeq03 = substr($m_level,4,2);
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(RULE_CD,7,2)),0) + 1),-2) as M_CD FROM TBL_RULE WHERE substring(RULE_CD,1,2) = '".substr($m_level,0,2)."' and substring(RULE_CD,3,2) = '".substr($m_level,2,2)."' and substring(RULE_CD,5,2) = '".substr($m_level,4,2)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq04 = $row["M_CD"];
			$sSeq05 = "00";
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03.$sSeq04;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;
			$sSeq_03 = $m_seq03;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(RULE_SEQ04),0) + 1),-2) as SEQ FROM TBL_RULE WHERE substring(RULE_CD,1,2) = '".substr($m_level,0,2)."' and substring(RULE_CD,3,2) = '".substr($m_level,2,2)."' and substring(RULE_CD,5,2) = '".substr($m_level,4,2)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_04 = $row["SEQ"];
			$sSeq_05 = "00";

		}

		if (strlen($m_level) == 8) { 

			$sSeq01 = substr($m_level,0,2);
			$sSeq02 = substr($m_level,2,2);
			$sSeq03 = substr($m_level,4,2);
			$sSeq04 = substr($m_level,6,2);
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(RULE_CD,9,2)),0) + 1),-2) as M_CD FROM TBL_RULE WHERE substring(RULE_CD,1,2) = '".substr($m_level,0,2)."' and substring(RULE_CD,3,2) = '".substr($m_level,2,2)."' and substring(RULE_CD,5,2) = '".substr($m_level,4,2)."' and substring(RULE_CD,7,2) = '".substr($m_level,6,2)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq05 = $row["M_CD"];
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03.$sSeq04.$sSeq05;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;
			$sSeq_03 = $m_seq03;
			$sSeq_04 = $m_seq04;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(RULE_SEQ05),0) + 1),-2) as SEQ FROM TBL_RULE WHERE substring(RULE_CD,1,2) = '".substr($m_level,0,2)."' and substring(RULE_CD,3,2) = '".substr($m_level,2,2)."' and substring(RULE_CD,5,2) = '".substr($m_level,4,2)."' and substring(RULE_CD,7,2) = '".substr($m_level,6,2)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_05 = $row["SEQ"];

		}

		$query = "SELECT IFNULL(MAX(RULE_NO),0) + 1  as IMAX FROM TBL_RULE ";
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
			
		$iMax = $row["IMAX"];

		$query = "INSERT INTO TBL_RULE (RULE_NO, RULE_CD, RULE_TYPE, RULE_NAME, RULE_SEQ01, RULE_SEQ02, RULE_SEQ03, RULE_SEQ04, RULE_SEQ05, 
												RULE_CONTENT, RULE_INFO01, RULE_INFO02, RULE_INFO03, USE_TF, REG_ADM, REG_DATE)
							VALUES	('$iMax', '$sMenu_cd', '$rule_type', '$rule_name', '$sSeq_01', '$sSeq_02', '$sSeq_03', '$sSeq_04', '$sSeq_05', 
											 '$rule_content', '$rule_info01', '$rule_info02', '$rule_info03', '$use_tf', '$reg_adm', now()); ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectRule($db, $rule_no) {

		$query = "SELECT RULE_NO, RULE_TYPE, RULE_CD, RULE_NAME, RULE_SEQ01, RULE_SEQ02, RULE_SEQ03, RULE_SEQ04, RULE_SEQ05, RULE_CONTENT, RULE_INFO01, RULE_INFO02, RULE_INFO03, 
										 USE_TF, DEL_TF
								FROM TBL_RULE 
							 WHERE RULE_NO = '$rule_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function selectRuleAsRuleCD($db, $rule_no) {

		$query = "SELECT RULE_NO, RULE_TYPE, RULE_CD, RULE_NAME, RULE_SEQ01, RULE_SEQ02, RULE_SEQ03, RULE_SEQ04, RULE_SEQ05, RULE_CONTENT, RULE_INFO01, RULE_INFO02, RULE_INFO03, 
										 USE_TF, DEL_TF
								FROM TBL_RULE 
							 WHERE RULE_CD = '$rule_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function updateRule($db, $rule_type, $rule_name, $rule_content, $rule_info01, $rule_info02, $rule_info03, $use_tf, $up_adm, $rule_no) {

		$query="UPDATE TBL_RULE SET 
									 RULE_TYPE			= '$rule_type', 
									 RULE_NAME			= '$rule_name', 
									 RULE_CONTENT		= '$rule_content',
									 RULE_INFO01		= '$rule_info01',
									 RULE_INFO02		= '$rule_info02',
									 RULE_INFO03		= '$rule_info03',
									 USE_TF					= '$use_tf',
									 UP_ADM					= '$up_adm',
									 UP_DATE				= now()
						 WHERE RULE_NO				= '$rule_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteRule($db, $del_adm, $rule_no) {

		$query="SELECT RULE_CD FROM TBL_RULE WHERE RULE_NO = '$rule_no' ";
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
			
		$rs_rule_cd = $row["RULE_CD"];
		
		#echo $rs_RULE_cd;

		$query="UPDATE TBL_RULE SET 
												 DEL_TF				= 'Y',
												 DEL_ADM			= '$del_adm',
												 DEL_DATE			= now() 
									 WHERE RULE_CD			like '".$rs_rule_cd."%' ";

		mysql_query($query,$db);

		$query="DELETE FROM TBL_RULE WHERE RULE_CD like '".$rs_rule_cd."%' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateRuleOrder($db, $arr_rule_no, $rule_level, $seq_no) {

		$query="UPDATE TBL_RULE SET " .$rule_level. " = '" .$seq_no. "' WHERE RULE_NO IN	".$arr_rule_no;

		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getNextDepthRuleCd($db, $rule_cd) {
		
		$query ="SELECT CONCAT(RULE_SEQ01,RULE_SEQ02,RULE_SEQ03,RULE_SEQ04,RULE_SEQ05) as SEQ, RULE_CD 
							 FROM TBL_RULE 
							WHERE RULE_CD like '".$rule_cd."%' AND length(RULE_CD) > length('$rule_cd') 
								AND USE_TF ='Y'
								AND DEL_TF ='N'
							ORDER BY SEQ ASC";
	
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[1];
		return $record;

	}

?>