<?
	# =============================================================================
	# File Name    : page.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2009.08.16
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_ADMIN_PAGE
	#=========================================================================================================

	/*
	CREATE TABLE IF NOT EXISTS `TBL_PAGES` (
  `PAGE_NO` int(11) unsigned NOT NULL default '0' COMMENT '페이지 SEQ',
  `PAGE_LANG` varchar(15) NOT NULL default '' COMMENT '페이지 언어',
  `PAGE_CD` varchar(15) NOT NULL default '' COMMENT '페이지 코드',
  `PAGE_NAME` varchar(50) NOT NULL default '' COMMENT '페이지명',
  `PAGE_URL` varchar(100) NOT NULL default '' COMMENT '페이지 URL',
  `PAGE_SEQ01` varchar(3) NOT NULL default '' COMMENT '페이지 순서 1',
  `PAGE_SEQ02` varchar(3) NOT NULL default '' COMMENT '페이지 순서 2',
  `PAGE_SEQ03` varchar(3) NOT NULL default '' COMMENT '페이지 순서 3',
  `PAGE_SEQ04` varchar(3) NOT NULL default '' COMMENT '페이지 순서 4',
  `PAGE_SEQ05` varchar(3) NOT NULL default '' COMMENT '페이지 순서 5',
  `PAGE_FLAG` char(1) NOT NULL default '' COMMENT '페이지 상태',
  `PAGE_RIGHT` varchar(10) NOT NULL default '' COMMENT '페이지 권한',
  `TITLE_IMG` varchar(50) NOT NULL default '' COMMENT '타이틀 이미지',
  `TITLE_IMG_OVER` varchar(50) NOT NULL default '' COMMENT '타이틀 이미지 2',
  `PAGE_IMG` varchar(50) NOT NULL default '' COMMENT '페이지 이미지',
  `PAGE_IMG_OVER` varchar(50) NOT NULL default '' COMMENT '페이지 이미지 2',
  `PAGE_SCRIPT` text NOT NULL default '' COMMENT '자바스트립트',
  `PAGE_CONTENT` longtext NOT NULL default '' COMMENT '내용',
  `PAGE_INFO01` varchar(100) NOT NULL default '' COMMENT '임시',
  `PAGE_INFO02` varchar(100) NOT NULL default '' COMMENT '임시',
  `PAGE_INFO03` varchar(100) NOT NULL default '' COMMENT '임시',
  `USE_TF` char(1) NOT NULL default 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
  `DEL_TF` char(1) NOT NULL default 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  `REG_ADM` int(11) unsigned default NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
  `REG_DATE` datetime default NULL COMMENT '등록일',
  `UP_ADM` int(11) unsigned default NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
  `UP_DATE` datetime default NULL COMMENT '수정일',
  `DEL_ADM` int(11) unsigned default NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
  `DEL_DATE` datetime default NULL COMMENT '삭제일'
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listPage($db, $page_lang, $menu_tf, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT CONCAT(PAGE_SEQ01,PAGE_SEQ02,PAGE_SEQ03,PAGE_SEQ04,PAGE_SEQ05) as SEQ,
										 PAGE_NO, PAGE_LANG, PAGE_CD, PAGE_NAME, PAGE_URL, PAGE_FLAG, PAGE_SEQ01, PAGE_SEQ02, PAGE_SEQ03, PAGE_SEQ04, PAGE_SEQ05, PAGE_RIGHT,
										 TITLE_IMG, TITLE_IMG_OVER, PAGE_IMG, PAGE_IMG_OVER, PAGE_SCRIPT, PAGE_CONTENT, PAGE_INFO01, PAGE_INFO02, PAGE_INFO03, 
										 MENU_TF, USE_TF, DEL_TF, LOGIN_TF
							FROM TBL_PAGES WHERE 1 = 1 ";

		if ($page_lang <> "") {
			$query .= " AND PAGE_LANG = '".$page_lang."' ";
		}

		if ($menu_tf <> "") {
			$query .= " AND MENU_TF = '".$menu_tf."' ";
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

	function listSubPage($db, $page_lang, $depth, $menu_tf, $use_tf, $del_tf) {
		
		$query = "SELECT CONCAT(PAGE_SEQ01,PAGE_SEQ02,PAGE_SEQ03,PAGE_SEQ04,PAGE_SEQ05) as SEQ,
										 PAGE_NO, PAGE_LANG, PAGE_CD, PAGE_NAME, PAGE_URL, PAGE_FLAG, PAGE_SEQ01, PAGE_SEQ02, PAGE_SEQ03, PAGE_SEQ04, PAGE_SEQ05, PAGE_RIGHT,
										 TITLE_IMG, TITLE_IMG_OVER, PAGE_IMG, PAGE_IMG_OVER, PAGE_SCRIPT, PAGE_CONTENT, PAGE_INFO01, PAGE_INFO02, PAGE_INFO03, 
										 MENU_TF, USE_TF, DEL_TF, LOGIN_TF
							FROM TBL_PAGES WHERE 1 = 1 ";

		if ($page_lang <> "") {
			$query .= " AND PAGE_LANG = '".$page_lang."' ";
		}

		if ($menu_tf <> "") {
			$query .= " AND MENU_TF = '".$menu_tf."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($depth <> "") {
			$query .= " AND length(PAGE_CD) = '".$depth."' ";
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

	function insertPage($db, $page_lang, $m_level, $m_seq01, $m_seq02, $m_seq03, $m_seq04, $page_name, $page_url, $page_flag, $page_right, $title_img, $title_img_over, $page_img, $page_img_over, $page_script, $page_content, $page_info01, $page_info02, $page_info03, $menu_tf, $use_tf, $login_tf, $reg_adm) {

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
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(PAGE_CD,1,2)),0) + 1),-2) as M_CD FROM TBL_PAGES ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq02 = "00";
			$sSeq03 = "00";
			$sSeq04 = "00";
			$sSeq05 = "00";

			$sMenu_cd = $row["M_CD"];

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(PAGE_SEQ01),0) + 1),-2) as SEQ FROM TBL_PAGES ";
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

			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(PAGE_CD,3,2)),0) + 1),-2) as M_CD FROM TBL_PAGES WHERE substring(PAGE_CD,1,2) = '$m_level' ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);

			$sSeq02 = $row["M_CD"];

			$sSeq03 = "00";
			$sSeq04 = "00";
			$sSeq05 = "00";

			$sMenu_cd = $sSeq01.$sSeq02;

			$sSeq_01 = $m_seq01;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(PAGE_SEQ02),0) + 1),-2) as SEQ FROM TBL_PAGES WHERE substring(PAGE_CD,1,2) = '$m_level' ";

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
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(PAGE_CD,5,2)),0) + 1),-2) as M_CD FROM TBL_PAGES WHERE substring(PAGE_CD,1,2) = '".substr($m_level,0,2)."' and substring(PAGE_CD,3,2) = '".substr($m_level,2,2)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq03 = $row["M_CD"];
			$sSeq04 = "00";
			$sSeq05 = "00";
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(PAGE_SEQ03),0) + 1),-2) as SEQ FROM TBL_PAGES WHERE substring(PAGE_CD,1,2) = '".substr($m_level,0,2)."' and substring(PAGE_CD,3,2) = '".substr($m_level,2,2)."' ";
			
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
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(PAGE_CD,7,2)),0) + 1),-2) as M_CD FROM TBL_PAGES WHERE substring(PAGE_CD,1,2) = '".substr($m_level,0,2)."' and substring(PAGE_CD,3,2) = '".substr($m_level,2,2)."' and substring(PAGE_CD,5,2) = '".substr($m_level,4,2)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq04 = $row["M_CD"];
			$sSeq05 = "00";
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03.$sSeq04;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;
			$sSeq_03 = $m_seq03;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(PAGE_SEQ04),0) + 1),-2) as SEQ FROM TBL_PAGES WHERE substring(PAGE_CD,1,2) = '".substr($m_level,0,2)."' and substring(PAGE_CD,3,2) = '".substr($m_level,2,2)."' and substring(PAGE_CD,5,2) = '".substr($m_level,4,2)."' ";
			
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
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(PAGE_CD,9,2)),0) + 1),-2) as M_CD FROM TBL_PAGES WHERE substring(PAGE_CD,1,2) = '".substr($m_level,0,2)."' and substring(PAGE_CD,3,2) = '".substr($m_level,2,2)."' and substring(PAGE_CD,5,2) = '".substr($m_level,4,2)."' and substring(PAGE_CD,7,2) = '".substr($m_level,6,2)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq05 = $row["M_CD"];
			
			$sMenu_cd =  $sSeq01.$sSeq02.$sSeq03.$sSeq04.$sSeq05;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;
			$sSeq_03 = $m_seq03;
			$sSeq_04 = $m_seq04;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(PAGE_SEQ05),0) + 1),-2) as SEQ FROM TBL_PAGES WHERE substring(PAGE_CD,1,2) = '".substr($m_level,0,2)."' and substring(PAGE_CD,3,2) = '".substr($m_level,2,2)."' and substring(PAGE_CD,5,2) = '".substr($m_level,4,2)."' and substring(PAGE_CD,7,2) = '".substr($m_level,6,2)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_05 = $row["SEQ"];

		}

		$query = "SELECT IFNULL(MAX(PAGE_NO),0) + 1  as IMAX FROM TBL_PAGES ";
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
			
		$iMax = $row["IMAX"];

		$query = "INSERT INTO TBL_PAGES (PAGE_NO, PAGE_CD, PAGE_LANG, PAGE_NAME, PAGE_URL, PAGE_SEQ01, PAGE_SEQ02, PAGE_SEQ03, PAGE_SEQ04, PAGE_SEQ05, 
												PAGE_FLAG, PAGE_RIGHT, TITLE_IMG, TITLE_IMG_OVER, PAGE_IMG, PAGE_IMG_OVER, PAGE_SCRIPT, PAGE_CONTENT, 
												PAGE_INFO01, PAGE_INFO02, PAGE_INFO03, MENU_TF, USE_TF, LOGIN_TF, REG_ADM, REG_DATE)
							VALUES	('$iMax', '$sMenu_cd', '$page_lang', '$page_name', '$page_url', '$sSeq_01', '$sSeq_02', '$sSeq_03', '$sSeq_04', '$sSeq_05', 
											 '$page_flag', '$page_right','$title_img', '$title_img_over','$page_img','$page_img_over', '$page_script', '$page_content',
											 '$page_info01', '$page_info02', '$page_info03', '$menu_tf', '$use_tf', '$login_tf', '$reg_adm', now()); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectPage($db, $page_no) {

		$query = "SELECT PAGE_NO, PAGE_LANG, PAGE_CD, PAGE_NAME, PAGE_URL, PAGE_FLAG, PAGE_SEQ01, PAGE_SEQ02, PAGE_SEQ03, PAGE_SEQ04, PAGE_SEQ05, PAGE_RIGHT,
										 TITLE_IMG, TITLE_IMG_OVER, PAGE_IMG, PAGE_IMG_OVER, PAGE_SCRIPT, PAGE_CONTENT, PAGE_INFO01, PAGE_INFO02, PAGE_INFO03, 
										 MENU_TF, USE_TF, DEL_TF, LOGIN_TF
								FROM TBL_PAGES 
							 WHERE PAGE_NO = '$page_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function selectPageAsPageCD($db, $page_cd) {

		$query = "SELECT PAGE_NO, PAGE_LANG, PAGE_CD, PAGE_NAME, PAGE_URL, PAGE_FLAG, PAGE_SEQ01, PAGE_SEQ02, PAGE_SEQ03, PAGE_SEQ04, PAGE_SEQ05, PAGE_RIGHT,
										 TITLE_IMG, TITLE_IMG_OVER, PAGE_IMG, PAGE_IMG_OVER, PAGE_SCRIPT, PAGE_CONTENT, PAGE_INFO01, PAGE_INFO02, PAGE_INFO03, 
										 MENU_TF, USE_TF, DEL_TF, LOGIN_TF
								FROM TBL_PAGES 
							 WHERE PAGE_CD = '$page_cd' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function updatePage($db, $page_lang, $page_name, $page_url, $page_flag, $page_right, $title_img, $title_img_over, $page_img, $page_img_over, $page_script, $page_content, $page_info01, $page_info02, $page_info03, $menu_tf, $use_tf, $login_tf, $up_adm, $page_no) {

		$query="UPDATE TBL_PAGES SET 
									 PAGE_LANG			= '$page_lang', 
									 PAGE_NAME			= '$page_name', 
									 PAGE_URL				= '$page_url', 
									 PAGE_FLAG			= '$page_flag', 
									 PAGE_RIGHT			= '$page_right', 
									 TITLE_IMG			= '$title_img', 
									 TITLE_IMG_OVER	= '$title_img_over', 
									 PAGE_IMG				= '$page_img', 
									 PAGE_IMG_OVER	= '$page_img_over', 
									 PAGE_SCRIPT		= '$page_script',
									 PAGE_CONTENT		= '$page_content',
									 PAGE_INFO01		= '$page_info01',
									 PAGE_INFO02		= '$page_info02',
									 PAGE_INFO03		= '$page_info03',
									 MENU_TF				= '$menu_tf',
									 USE_TF					= '$use_tf',
									 LOGIN_TF				= '$login_tf',
									 UP_ADM					= '$up_adm',
									 UP_DATE				= now()
						 WHERE PAGE_NO				= '$page_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deletePage($db, $del_adm, $page_no) {

		$query="SELECT PAGE_CD FROM TBL_PAGES WHERE PAGE_NO			= '$page_no' ";
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
			
		$rs_page_cd = $row["PAGE_CD"];
		
		#echo $rs_page_cd;

		$query="UPDATE TBL_PAGES SET 
												 DEL_TF				= 'Y',
												 DEL_ADM			= '$del_adm',
												 DEL_DATE			= now()														 
									 WHERE PAGE_CD			like '".$rs_page_cd."%' ";

		mysql_query($query,$db);

		$query="DELETE FROM TBL_PAGES WHERE PAGE_CD like '".$rs_page_cd."%' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updatePageOrder($db, $arr_page_no, $page_level, $seq_no) {

		$query="UPDATE TBL_PAGES SET " .$page_level. " = '" .$seq_no. "' WHERE PAGE_NO IN	".$arr_page_no;

		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getNextDepthPageCd($db, $page_cd) {
		
		$query ="SELECT CONCAT(PAGE_SEQ01,PAGE_SEQ02,PAGE_SEQ03,PAGE_SEQ04,PAGE_SEQ05) as SEQ, PAGE_CD 
							 FROM TBL_PAGES 
							WHERE PAGE_CD like '".$page_cd."%' AND length(PAGE_CD) > length('$page_cd') 
								AND USE_TF ='Y'
								AND DEL_TF ='N'
							ORDER BY SEQ ASC";
	
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[1];
		return $record;

	}

	function getBoardPageInfo($db, $lang, $b_code, $depth) {

		$query = "SELECT PAGE_NAME, PAGE_URL FROM TBL_PAGES
							 WHERE USE_TF ='Y' 
								 AND DEL_TF = 'N' 
								 AND length(PAGE_CD) = '$depth' 
								 AND PAGE_LANG = '$lang'
								 AND ((PAGE_URL like '%".$b_code."') or (PAGE_URL like '%".$b_code."&%')) ";
		
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
?>