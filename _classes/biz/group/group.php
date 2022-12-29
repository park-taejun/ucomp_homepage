<?
	# =============================================================================
	# File Name    : group.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2016-06-14
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_GROUP
	#=========================================================================================================

	/*
CREATE TABLE IF NOT EXISTS TBL_GROUP (
  GROUP_NO int(11) unsigned NOT NULL DEFAULT '0' COMMENT '조직 SEQ',
  GROUP_KIND varchar(15) NOT NULL COMMENT '소속당',
  GROUP_CD varchar(15) NOT NULL DEFAULT '' COMMENT '조직 코드',
  GROUP_SIDO varchar(15) NOT NULL COMMENT '조직명',
  GROUP_NAME varchar(100) NOT NULL COMMENT '페이지명',
  GROUP_SEQ01 varchar(3) NOT NULL DEFAULT '' COMMENT '조직 순서 1',
  GROUP_SEQ02 varchar(3) NOT NULL DEFAULT '' COMMENT '조직 순서 2',
  GROUP_SEQ03 varchar(3) NOT NULL DEFAULT '' COMMENT '조직 순서 3',
  GROUP_SEQ04 varchar(3) NOT NULL DEFAULT '' COMMENT '조직 순서 4',
  GROUP_SEQ05 varchar(3) NOT NULL DEFAULT '' COMMENT '조직 순서 5',
  GROUP_FLAG char(1) NOT NULL DEFAULT '' COMMENT '조직 상태',
  GROUP_IMG varchar(50) NOT NULL DEFAULT '' COMMENT '조직 이미지',
  GROUP_IMG_OVER varchar(50) NOT NULL DEFAULT '' COMMENT '조직 이미지 2',
  GROUP_CONTENT long NOT NULL COMMENT '내용',
  GROUP_INFO01 varchar(100) NOT NULL DEFAULT '' COMMENT '임시',
  GROUP_INFO02 varchar(100) NOT NULL DEFAULT '' COMMENT '임시',
  GROUP_INFO03 varchar(100) NOT NULL DEFAULT '' COMMENT '임시',
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


	function listGroup($db, $group_kind, $group_cd, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT CONCAT(GROUP_SEQ01,GROUP_SEQ02,GROUP_SEQ03,GROUP_SEQ04,GROUP_SEQ05) as SEQ,
										 GROUP_NO, GROUP_KIND, GROUP_CD, GROUP_SIDO, GROUP_NAME, GROUP_SEQ01, GROUP_SEQ02, GROUP_SEQ03, GROUP_SEQ04, GROUP_SEQ05, GROUP_FLAG,
										 GROUP_IMG, GROUP_IMG_OVER, GROUP_CONTENT, GROUP_INFO01, GROUP_INFO02, GROUP_INFO03, 
										 USE_TF, DEL_TF
							FROM TBL_GROUP WHERE 1 = 1 ";

		if ($group_kind <> "") {
			$query .= " AND GROUP_KIND = '".$group_kind."' ";
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

	function listSubGroup($db, $group_kind, $depth, $menu_tf, $use_tf, $del_tf) {
		
		$query = "SELECT CONCAT(GROUP_SEQ01,GROUP_SEQ02,GROUP_SEQ03,GROUP_SEQ04,GROUP_SEQ05) as SEQ,
										 GROUP_NO, GROUP_KIND, GROUP_CD, GROUP_SIDO, GROUP_NAME, GROUP_SEQ01, GROUP_SEQ02, GROUP_SEQ03, GROUP_SEQ04, GROUP_SEQ05, GROUP_FLAG,
										 GROUP_IMG, GROUP_IMG_OVER, GROUP_CONTENT, GROUP_INFO01, GROUP_INFO02, GROUP_INFO03, 
										 USE_TF, DEL_TF
							FROM TBL_GROUP WHERE 1 = 1 ";

		if ($group_kind <> "") {
			$query .= " AND GROUP_KIND = '".$group_kind."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($depth <> "") {
			$query .= " AND length(GROUP_CD) = '".$depth."' ";
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

	function insertGroup($db, $group_kind, $group_sido, $m_level, $m_seq01, $m_seq02, $m_seq03, $m_seq04, $group_name, $group_flag, $group_img, $group_img_over, $group_content, $group_info01, $group_info02, $group_info03, $use_tf, $reg_adm) {

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
		$sGroup_cd	= "";
		
		if (strlen($m_level) == 0) { 
			
			$query = "SELECT substring(CONCAT('000', ifnull(max(substring(GROUP_CD,1,3)),0) + 1),-3) as M_CD FROM TBL_GROUP ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq02 = "000";
			$sSeq03 = "000";
			$sSeq04 = "000";
			$sSeq05 = "000";

			$sMenu_cd = $row["M_CD"];

			$query = "SELECT substring(CONCAT('000', ifnull(MAX(GROUP_SEQ01),0) + 1),-3) as SEQ FROM TBL_GROUP ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_01 = $row["SEQ"];

			$sSeq_02 = "000";
			$sSeq_03 = "000";
			$sSeq_04 = "000";
			$sSeq_05 = "000";

		}

		if (strlen($m_level) == 3) { 
			
			 $sSeq01 = $m_level;

			$query = "SELECT substring(CONCAT('000', ifnull(max(substring(GROUP_CD,4,3)),0) + 1),-3) as M_CD FROM TBL_GROUP WHERE substring(GROUP_CD,1,3) = '$m_level' ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);

			$sSeq02 = $row["M_CD"];

			$sSeq03 = "000";
			$sSeq04 = "000";
			$sSeq05 = "000";

			$sMenu_cd = $sSeq01.$sSeq02;

			$sSeq_01 = $m_seq01;

			$query = "SELECT substring(CONCAT('000', ifnull(MAX(GROUP_SEQ02),0) + 1),-3) as SEQ FROM TBL_GROUP WHERE substring(GROUP_CD,1,3) = '$m_level' ";

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_02 = $row["SEQ"];
			$sSeq_03 = "000";
			$sSeq_04 = "000";
			$sSeq_05 = "000";

		}

		if (strlen($m_level) == 6) { 

			$sSeq01 = substr($m_level,0,3);
			$sSeq02 = substr($m_level,3,3);
			
			$query = "SELECT substring(CONCAT('000', ifnull(max(substring(GROUP_CD,7,3)),0) + 1),-3) as M_CD 
									FROM TBL_GROUP 
								 WHERE substring(GROUP_CD,1,3) = '".substr($m_level,0,3)."' 
									 and substring(GROUP_CD,4,3) = '".substr($m_level,3,3)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq03 = $row["M_CD"];
			$sSeq04 = "000";
			$sSeq05 = "000";
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;

			$query = "SELECT substring(CONCAT('000', ifnull(MAX(GROUP_SEQ03),0) + 1),-3) as SEQ 
									FROM TBL_GROUP 
								 WHERE substring(GROUP_CD,1,3) = '".substr($m_level,0,3)."' 
									 and substring(GROUP_CD,4,3) = '".substr($m_level,3,3)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_03 = $row["SEQ"];
			$sSeq_04 = "000";
			$sSeq_05 = "000";

		}

		if (strlen($m_level) == 9) { 

			$sSeq01 = substr($m_level,0,3);
			$sSeq02 = substr($m_level,3,3);
			$sSeq03 = substr($m_level,6,3);
			
			$query = "SELECT substring(CONCAT('000', ifnull(max(substring(GROUP_CD,10,3)),0) + 1),-3) as M_CD 
									FROM TBL_GROUP 
								 WHERE substring(GROUP_CD,1,3) = '".substr($m_level,0,3)."' 
									 and substring(GROUP_CD,4,3) = '".substr($m_level,3,3)."' 
									 and substring(GROUP_CD,7,3) = '".substr($m_level,6,3)."' ";
			
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq04 = $row["M_CD"];
			$sSeq05 = "000";
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03.$sSeq04;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;
			$sSeq_03 = $m_seq03;

			$query = "SELECT substring(CONCAT('000', ifnull(MAX(GROUP_SEQ04),0) + 1),-3) as SEQ 
									FROM TBL_GROUP
								 WHERE substring(GROUP_CD,1,3) = '".substr($m_level,0,3)."' 
									 and substring(GROUP_CD,4,3) = '".substr($m_level,3,3)."' 
									 and substring(GROUP_CD,7,3) = '".substr($m_level,6,3)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_04 = $row["SEQ"];
			$sSeq_05 = "000";

		}

		if (strlen($m_level) == 12) { 

			$sSeq01 = substr($m_level,0,3);
			$sSeq02 = substr($m_level,3,3);
			$sSeq03 = substr($m_level,6,3);
			$sSeq04 = substr($m_level,9,3);
			
			$query = "SELECT substring(CONCAT('000', ifnull(max(substring(GROUP_CD,13,3)),0) + 1),-3) as M_CD 
									FROM TBL_GROUP
								 WHERE substring(GROUP_CD,1,3) = '".substr($m_level,0,3)."' 
									 and substring(GROUP_CD,4,3) = '".substr($m_level,3,3)."' 
									 and substring(GROUP_CD,7,3) = '".substr($m_level,6,3)."' 
									 and substring(GROUP_CD,10,3) = '".substr($m_level,9,3)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq05 = $row["M_CD"];
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03.$sSeq04.$sSeq05;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;
			$sSeq_03 = $m_seq03;
			$sSeq_04 = $m_seq04;

			$query = "SELECT substring(CONCAT('000', ifnull(MAX(GROUP_SEQ05),0) + 1),-3) as SEQ 
									FROM TBL_GROUP
								 WHERE substring(GROUP_CD,1,3) = '".substr($m_level,0,3)."' 
									 and substring(GROUP_CD,4,3) = '".substr($m_level,3,3)."' 
									 and substring(GROUP_CD,7,3) = '".substr($m_level,6,3)."' 
									 and substring(GROUP_CD,10,3) = '".substr($m_level,9,3)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_05 = $row["SEQ"];

		}

		$query = "SELECT IFNULL(MAX(GROUP_NO),0) + 1  as IMAX FROM TBL_GROUP ";
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
			
		$iMax = $row["IMAX"];

		/*
		echo $sMenu_cd."<br>";
		echo $sSeq_01."<br>";
		echo $sSeq_02."<br>";
		echo $sSeq_03."<br>";
		echo $sSeq_04."<br>";
		echo $sSeq_05."<br>";
		exit;
		*/

		$query = "INSERT INTO TBL_GROUP (GROUP_NO, GROUP_CD, GROUP_KIND, GROUP_SIDO, GROUP_NAME, GROUP_SEQ01, GROUP_SEQ02, GROUP_SEQ03, GROUP_SEQ04, GROUP_SEQ05, 
												GROUP_FLAG, GROUP_IMG, GROUP_IMG_OVER, GROUP_CONTENT,
												GROUP_INFO01, GROUP_INFO02, GROUP_INFO03, USE_TF, REG_ADM, REG_DATE)
							VALUES	('$iMax', '$sMenu_cd', '$group_kind', '$group_sido', '$group_name', '$sSeq_01', '$sSeq_02', '$sSeq_03', '$sSeq_04', '$sSeq_05', 
											 '$group_flag','$group_img','$group_img_over', '$group_content',
											 '$group_info01', '$group_info02', '$group_info03', '$use_tf', '$reg_adm', now()); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectGroup($db, $group_no) {

		$query = "SELECT GROUP_NO, GROUP_KIND, GROUP_CD, GROUP_SIDO, GROUP_NAME, GROUP_SEQ01, GROUP_SEQ02, GROUP_SEQ03, GROUP_SEQ04, GROUP_SEQ05, GROUP_FLAG,
										 GROUP_IMG, GROUP_IMG_OVER, GROUP_CONTENT, GROUP_INFO01, GROUP_INFO02, GROUP_INFO03, 
										  USE_TF, DEL_TF
								FROM TBL_GROUP 
							 WHERE GROUP_NO = '$group_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function selectGroupAsGroupCD($db, $group_cd) {

		$query = "SELECT GROUP_NO, GROUP_KIND, GROUP_CD, GROUP_SIDO, GROUP_NAME, GROUP_SEQ01, GROUP_SEQ02, GROUP_SEQ03, GROUP_SEQ04, GROUP_SEQ05, GROUP_FLAG,
										 GROUP_IMG, GROUP_IMG_OVER, GROUP_CONTENT, GROUP_INFO01, GROUP_INFO02, GROUP_INFO03, 
										  USE_TF, DEL_TF
								FROM TBL_GROUP
							 WHERE GROUP_CD = '$group_cd' ";
		
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

	function updateGroup($db, $group_kind, $group_name, $group_sido, $group_img, $group_img_over, $group_content, $group_info01, $group_info02, $group_info03, $use_tf, $up_adm, $group_no) {

		$query="UPDATE TBL_GROUP SET 
									 GROUP_KIND			= '$group_kind', 
									 GROUP_SIDO			= '$group_sido', 
									 GROUP_NAME			= '$group_name', 
									 GROUP_IMG			= '$group_img', 
									 GROUP_IMG_OVER	= '$group_img_over', 
									 GROUP_CONTENT	= '$group_content',
									 GROUP_INFO01		= '$group_info01',
									 GROUP_INFO02		= '$group_info02',
									 GROUP_INFO03		= '$group_info03',
									 USE_TF					= '$use_tf',
									 UP_ADM					= '$up_adm',
									 UP_DATE				= now()
						 WHERE GROUP_NO				= '$group_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteGroup($db, $del_adm, $group_no) {

		$query="SELECT GROUP_CD FROM TBL_GROUP WHERE GROUP_NO			= '$group_no' ";
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
			
		$rs_group_cd = $row["GROUP_CD"];
		
		#echo $rs_page_cd;

		$query="UPDATE TBL_GROUP SET 
												 DEL_TF				= 'Y',
												 DEL_ADM			= '$del_adm',
												 DEL_DATE			= now()														 
									 WHERE GROUP_CD			like '".$rs_group_cd."%' ";

		mysql_query($query,$db);

		$query="DELETE FROM TBL_GROUP WHERE GROUP_CD like '".$rs_group_cd."%' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateGroupOrder($db, $arr_group_no, $group_level, $seq_no) {

		$query="UPDATE TBL_GROUP SET " .$group_level. " = '" .$seq_no. "' WHERE GROUP_NO IN	".$arr_group_no;

		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getNextDepthGroupCd($db, $group_cd) {
		
		$query ="SELECT CONCAT(GROUP_SEQ01,GROUP_SEQ02,GROUP_SEQ03,GROUP_SEQ04,GROUP_SEQ05) as SEQ, GROUP_CD 
							 FROM TBL_GROUP 
							WHERE GROUP_CD like '".$group_cd."%' AND length(GROUP_CD) > length('$group_cd') 
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