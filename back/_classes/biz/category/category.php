<?

	# =============================================================================
	# File Name    : category.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2009.08.16
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_CATEGORY
	#=========================================================================================================

	/*
	CREATE TABLE IF NOT EXISTS `TBL_CATEGORY` (
  `CATE_NO` int(11) unsigned NOT NULL default '0' COMMENT '카테고리 SEQ',
  `CATE_CD` varchar(10) NOT NULL default '' COMMENT '카테고리코드',
  `CATE_NAME` varchar(50) NOT NULL default '' COMMENT '카테고리명',
  `CATE_MEMO` text NOT NULL default '' COMMENT '카테고리 설명',
  `CATE_SEQ01` varchar(3) NOT NULL default '' COMMENT '카테고리 순서 1',
  `CATE_SEQ02` varchar(3) NOT NULL default '' COMMENT '카테고리 순서 2',
  `CATE_SEQ03` varchar(3) NOT NULL default '' COMMENT '카테고리 순서 3',
  `CATE_SEQ04` varchar(3) NOT NULL default '' COMMENT '카테고리 순서 4',
  `CATE_FLAG` char(1) NOT NULL default '' COMMENT '카테고리 상태',
  `CATE_CODE` varchar(10) NOT NULL default '' COMMENT '카테고리 코드',
  `CATE_IMG` varchar(50) NOT NULL default '' COMMENT '카테고리 이미지',
  `CATE_IMG_OVER` varchar(50) NOT NULL default '' COMMENT '카테고리 이미지 2',
  `USE_TF` char(1) NOT NULL default 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
  `DEL_TF` char(1) NOT NULL default 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  `REG_ADM` int(11) unsigned default NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
  `REG_DATE` datetime default NULL COMMENT '등록일',
  `UP_ADM` int(11) unsigned default NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
  `UP_DATE` datetime default NULL COMMENT '수정일',
  `DEL_ADM` int(11) unsigned default NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
  `DEL_DATE` datetime default NULL COMMENT '삭제일'
	) ENGINE=MyISAM DEFAULT CHARSET=euckr COMMENT='카테고리 마스터';

	CREATE TABLE  `TBL_CATEGORY_GOODS` (
  `CATE_GOODS_NO` int(7) unsigned NOT NULL auto_increment,
  `CATE_CD` varchar(10) NOT NULL default '',
  `GOODS_NO` int(11) default '0',
  `DISP_LOCATION` varchar(10) NOT NULL default '',
  `USE_TF` varchar(2) NOT NULL default 'Y',
  `SEQ_NO_BIG` int(11) default 0,
  `SEQ_NO_SMALL` int(11) default 0,
  `VIEW_CNT` int(11) default 0,
  `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
  `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
  `UP_ADM` int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
  `UP_DATE` datetime DEFAULT NULL COMMENT '수정일',
  `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
  PRIMARY KEY  (`CATE_GOODS_NO`)
	) 

	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listCategory($db, $category, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ,
										 CATE_NO, CATE_CD, CATE_NAME, CATE_ENAME, CATE_CNAME, CATE_JNAME, CATE_MEMO, CATE_FLAG, CATE_SEQ01, CATE_SEQ02, CATE_SEQ03, CATE_SEQ04, CATE_CODE,
										 CATE_IMG, CATE_IMG_OVER, M_CATE_IMG, M_CATE_STR
							FROM TBL_CATEGORY WHERE 1 = 1 ";

		if ($category <> "") {
			$query .= " AND CATE_CD like '".$category."%' ";
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

	function dupCategory ($db, $cate_code) {
		
		if ($cate_code <> "") {
			$query ="SELECT COUNT(*) CNT FROM TBL_CATEGORY WHERE 1 = 1 AND DEL_TF = 'N' ";
		
			if ($cate_code <> "") {
				$query .= " AND CATE_CODE = '".$cate_code."' ";
			}

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
		
			if ($rows[0] == 0) {
				return 0;
			} else {
				return 1;
			}
		} else {
			return 0;
		}
				
	}


	/*	카테고리 등록*/
	
	function insertCategory($db, $m_level, $m_seq01, $m_seq02, $m_seq03, $cate_name, $cate_ename, $cate_cname, $cate_jname, $cate_memo, $cate_flag, $cate_code, $cate_img, $cate_img_over, $m_cate_img, $m_cate_str, $use_tf, $reg_adm) {

		$iMax = "0";	

		$sSeq01		= "";
		$sSeq02		= "";
		$sSeq03		= "";
		$sSeq04		= "";
		$sSeq_01	= "";
		$sSeq_02	= "";
		$sSeq_03	= "";
		$sSeq_04	= "";
		$sCate_cd	= "";
		
		if ($cate_code <> "") {
			$query = "SELECT COUNT(*) cnt FROM TBL_CATEGORY WHERE CATE_CODE = '$cate_code' ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);

			if ($row["cnt"] > 0) {
				return "2";
				exit;
			}
		}
		
		if (strlen($m_level) == 0) { 
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(CATE_CD,1,2)),0) + 1),-2) as M_CD 
									FROM TBL_CATEGORY ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq02 = "00";
			$sSeq03 = "00";
			$sSeq04 = "00";

			$sCate_cd = $row["M_CD"];

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(CATE_SEQ01),0) + 1),-2) as SEQ 
									FROM TBL_CATEGORY ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_01 = $row["SEQ"];

			$sSeq_02 = "00";
			$sSeq_03 = "00";
			$sSeq_04 = "00";

		}

		if (strlen($m_level) == 2) { 
			
			 $sSeq01 = $m_level;

			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(CATE_CD,3,2)),0) + 1),-2) as M_CD 
									FROM TBL_CATEGORY 
								 WHERE substring(CATE_CD,1,2) = '$m_level' ";
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);

			$sSeq02 = $row["M_CD"];
			$sSeq03 = "00";
			$sSeq04 = "00";

			$sCate_cd = $sSeq01.$sSeq02;

			$sSeq_01 = $m_seq01;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(CATE_SEQ02),0) + 1),-2) as SEQ 
									FROM TBL_CATEGORY 
								 WHERE substring(CATE_CD,1,2) = '$m_level' ";

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_02 = $row["SEQ"];
			$sSeq_03 = "00";
			$sSeq_04 = "00";

		}

		if (strlen($m_level) == 4) { 

			$sSeq01 = substr($m_level,0,2);
			$sSeq02 = substr($m_level,2,2);
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(CATE_CD,5,2)),0) + 1),-2) as M_CD 
									FROM TBL_CATEGORY 
								 WHERE substring(CATE_CD,1,2) = '".substr($m_level,0,2)."' 
									 and substring(CATE_CD,3,2) = '".substr($m_level,2,2)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq03 = $row["M_CD"];
			$sSeq04 = "00";
			
			$sCate_cd =  $sSeq01.$sSeq02.$sSeq03;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(CATE_SEQ03),0) + 1),-2) as SEQ 
									FROM TBL_CATEGORY 
								 WHERE substring(CATE_CD,1,2) = '".substr($m_level,0,2)."' 
									 and substring(CATE_CD,3,2) = '".substr($m_level,2,2)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_03 = $row["SEQ"];
			$sSeq_04 = "00";

		}
		
		if (strlen($m_level) == 6) { 

			$sSeq01 = substr($m_level,0,2);
			$sSeq02 = substr($m_level,2,2);
			$sSeq03 = substr($m_level,4,2);
			
			$query = "SELECT substring(CONCAT('00', ifnull(max(substring(CATE_CD,7,2)),0) + 1),-2) as M_CD 
									FROM TBL_CATEGORY 
								 WHERE substring(CATE_CD,1,2) = '".substr($m_level,0,2)."' 
									 and substring(CATE_CD,3,2) = '".substr($m_level,2,2)."' 
									 and substring(CATE_CD,5,2) = '".substr($m_level,4,2)."' ";
						
			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq04 = $row["M_CD"];
			
			$sCate_cd =  $sSeq01.$sSeq02.$sSeq03.$sSeq04;

			$sSeq_01 = $m_seq01;
			$sSeq_02 = $m_seq02;
			$sSeq_03 = $m_seq03;

			$query = "SELECT substring(CONCAT('00', ifnull(MAX(CATE_SEQ04),0) + 1),-2) as SEQ 
								 FROM TBL_CATEGORY 
								WHERE substring(CATE_CD,1,2) = '".substr($m_level,0,2)."' 
									and substring(CATE_CD,3,2) = '".substr($m_level,2,2)."' 
									and substring(CATE_CD,5,2) = '".substr($m_level,4,2)."' ";
			
			#echo $query;

			$result = mysql_query($query,$db);
			$row = mysql_fetch_array($result);
			
			$sSeq_04 = $row["SEQ"];

		}

		$query = "SELECT IFNULL(MAX(CATE_NO),0) + 1  as IMAX FROM TBL_CATEGORY ";
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
			
		$iMax = $row["IMAX"];
		
		$query = "INSERT INTO TBL_CATEGORY (CATE_NO, CATE_CD, CATE_NAME, CATE_ENAME, CATE_CNAME, CATE_JNAME, CATE_MEMO, CATE_SEQ01, CATE_SEQ02, CATE_SEQ03, CATE_SEQ04, 
												CATE_FLAG, CATE_CODE, CATE_IMG, CATE_IMG_OVER, M_CATE_IMG, M_CATE_STR, USE_TF, REG_ADM, REG_DATE)
							VALUES	('$iMax', '$sCate_cd', '$cate_name', '$cate_ename', '$cate_cname', '$cate_jname', '$cate_memo', '$sSeq_01', '$sSeq_02', '$sSeq_03', '$sSeq_04', 
											 '$cate_flag', '$cate_code','$cate_img','$cate_img_over','$m_cate_img','$m_cate_str','$use_tf', '$reg_adm', now()); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

	function selectCategory($db, $cate_no) {

		$query = "SELECT CATE_NO, CATE_NAME, CATE_ENAME, CATE_CNAME, CATE_JNAME, CATE_MEMO, CATE_FLAG, CATE_CD, CATE_CODE,CATE_IMG,CATE_IMG_OVER,M_CATE_IMG,M_CATE_STR
								FROM TBL_CATEGORY 
							 WHERE CATE_NO = '$cate_no' ";
		
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

	function selectCategoryAsCateCd($db, $cate_cd) {

		$query = "SELECT CATE_NO, CATE_NAME, CATE_ENAME, CATE_CNAME, CATE_JNAME, CATE_MEMO, CATE_FLAG, CATE_CD, CATE_CODE,CATE_IMG,CATE_IMG_OVER,M_CATE_IMG,M_CATE_STR 
								FROM TBL_CATEGORY 
							 WHERE CATE_CD = '$cate_cd' ";
		
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

	function updateCategory($db, $cate_name, $cate_ename, $cate_cname, $cate_jname, $cate_memo, $cate_flag, $cate_code, $cate_img, $cate_img_over, $m_cate_img, $m_cate_str, $use_tf, $up_adm, $cate_no) {

		$query="UPDATE TBL_CATEGORY SET 
									 CATE_NAME			= '$cate_name', 
									 CATE_ENAME			= '$cate_ename', 
									 CATE_CNAME			= '$cate_cname', 
									 CATE_JNAME			= '$cate_jname', 
									 CATE_MEMO			= '$cate_memo', 
									 CATE_FLAG			= '$cate_flag', 
									 CATE_CODE			= '$cate_code', 
									 CATE_IMG				= '$cate_img', 
									 CATE_IMG_OVER	= '$cate_img_over', 
									 M_CATE_IMG			= '$m_cate_img', 
									 M_CATE_STR			= '$m_cate_str', 
									 USE_TF					= '$use_tf',
									 UP_ADM					= '$up_adm',
									 UP_DATE				= now()
						 WHERE CATE_NO				= '$cate_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteCategory($db, $del_adm, $cate_no) {

		$query="SELECT CATE_CD FROM TBL_CATEGORY WHERE CATE_NO			= '$cate_no' ";
		$result = mysql_query($query,$db);
		$row = mysql_fetch_array($result);
			
		$rs_cate_cd = $row["CATE_CD"];
		
		#echo $rs_CATE_cd;

		$query="UPDATE TBL_CATEGORY SET 
												 DEL_TF				= 'Y',
												 DEL_ADM			= '$del_adm',
												 DEL_DATE			= now()														 
									 WHERE CATE_CD			like '".$rs_cate_cd."%' ";

		mysql_query($query,$db);

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateCategoryOrder($db, $arr_cate_no, $cate_level, $seq_no) {

		$query="UPDATE TBL_CATEGORY SET " .$cate_level. " = '" .$seq_no. "' WHERE CATE_NO IN	".$arr_cate_no;

		#echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listCategoryGoodsSort($db, $cate_cd, $disp_location, $seq_kind, $sale_type, $order_field, $order_str) {

		$query = "SELECT A.GOODS_NO, A.GOODS_CATE, A.GOODS_CODE, A.GOODS_NAME, A.GOODS_SUB_NAME, A.CATE_01, A.CATE_02, A.CATE_03, A.CATE_04, 
										 A.PRICE, A.BUY_PRICE, A.SALE_PRICE, A.EXTRA_PRICE, A.STOCK_CNT, 
										 A.IMG_URL, A.FILE_NM_100, A.FILE_RNM_100, A.FILE_PATH_100, A.FILE_SIZE_100, A.FILE_EXT_100, 
										 A.FILE_NM_150, A.FILE_RNM_150, A.FILE_PATH_150, A.FILE_SIZE_150, A.FILE_EXT_150, A.CONTENTS,
										 A.READ_CNT, A.DISP_SEQ, A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 (SELECT CP_NM FROM TBL_COMPANY WHERE TBL_COMPANY.CP_NO = A.CATE_03 ) AS CP_NAME,
										 B.CATE_GOODS_NO, B.SEQ_NO_BIG, B.SEQ_NO_SMALL ";

		if ($order_field == "SALE_CNT") {
			$query .= ", (SELECT COUNT(GOODS_NO) FROM TBL_ORDER_GOODS WHERE TBL_ORDER_GOODS.GOODS_NO = A.GOODS_NO) AS SALE_CNT ";
		}
		$query .= "	FROM TBL_GOODS A, TBL_CATEGORY_GOODS B 
							 WHERE A.GOODS_NO = B.GOODS_NO 
								 AND A.CATE_04 IN ('판매중','재판매') 
								 AND A.DEL_TF = 'N' ";



		if ($disp_location == "blog" || $disp_location == "event") { 
			$query .= " AND B.CATE_CD = '$cate_cd' ";
		} else {

			if ($cate_cd == "GROUP01") {
				$query .= " AND (A.GOODS_CATE like '01%' OR A.GOODS_CATE like '02%' OR A.GOODS_CATE like '03%') ";
			} else if ($cate_cd == "GROUP02") {
				$query .= " AND (A.GOODS_CATE like '04%' OR A.GOODS_CATE like '05%' OR A.GOODS_CATE like '06%') ";
			} else {
				$query .= " AND A.GOODS_CATE like '$cate_cd%' ";
			}
		}
		
		$query .= " AND B.DISP_LOCATION = '$disp_location' ";

		if ($sale_type <> "") {
			$query .= " AND A.GOODS_SUB_NAME LIKE '".$sale_type."' ";
		}

		if ($order_field == "") 
			$order_field = "A.REG_DATE";

		if ($order_str == "") 
			$order_str = "DESC";

		$query .= " ORDER BY ".$order_field." ".$order_str;

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

	function insertCategoryGoods($db, $cate_cd, $goods_no, $disp_location, $use_tf, $reg_adm) {
		
		if ($disp_location == "blog" || $disp_location == "event") { 
			$query ="SELECT COUNT(CATE_GOODS_NO) AS CNT FROM TBL_CATEGORY_GOODS WHERE DISP_LOCATION = '$disp_location' AND CATE_CD = '$cate_cd' AND GOODS_NO = '$goods_no' ";
		} else {
			$query ="SELECT COUNT(CATE_GOODS_NO) AS CNT FROM TBL_CATEGORY_GOODS WHERE DISP_LOCATION = '$disp_location' AND GOODS_NO = '$goods_no' ";
		}
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		$cnt = $rows[0];

		if ($cnt == 0) {

			$query="INSERT INTO TBL_CATEGORY_GOODS (CATE_CD, GOODS_NO, DISP_LOCATION, USE_TF, SEQ_NO_BIG, SEQ_NO_SMALL, VIEW_CNT, REG_ADM, REG_DATE) 
												 values ('$cate_cd','$goods_no', '$disp_location', '$use_tf', 0, 0, 0, '$reg_adm', now()); ";
		
			echo $query."<br>";

			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			} else {
				return true;
			}
		}
		return true;
	}

	function updateCategoryGoodsUseTF($db, $use_tf, $up_adm, $cate_goods_no) {
		
		$query="UPDATE TBL_CATEGORY_GOODS SET 
								USE_TF			= '$use_tf',
								UP_ADM			= '$up_adm',
								UP_DATE			= now()
					WHERE CATE_GOODS_NO			= '$cate_goods_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteCategoryGoods($db, $cate_goods_no) {
		
		$query="DELETE FROM TBL_CATEGORY_GOODS WHERE CATE_GOODS_NO = '$cate_goods_no' ";
		

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateCategoryGoodsOrderBig($db, $cate_goods_no, $seq_no) {

		$query="UPDATE TBL_CATEGORY_GOODS SET
													SEQ_NO_BIG			=	'$seq_no'
										WHERE CATE_GOODS_NO = '$cate_goods_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateCategoryGoodsOrderSmall($db, $cate_goods_no, $seq_no) {

		$query="UPDATE TBL_CATEGORY_GOODS SET
													SEQ_NO_SMALL	=	'$seq_no'
										WHERE CATE_GOODS_NO = '$cate_goods_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function cntCategoryGoods($db, $cate_cd, $disp_location) {

		$query = "SELECT COUNT(B.CATE_GOODS_NO) AS CNT
								FROM TBL_GOODS A, TBL_CATEGORY_GOODS B 
							 WHERE A.GOODS_NO = B.GOODS_NO 
								 AND A.CATE_04 = '판매중' 
								 AND A.DEL_TF = 'N' ";
		if ($disp_location == "list_pop") {
			$query .= "AND B.CATE_CD = '$cate_cd' ";
		} else {
			$query .= "AND A.GOODS_CATE like '$cate_cd%' ";
		}
		
		$query .= "AND B.DISP_LOCATION = '$disp_location' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
		
		return $record;
	
	}

	function listCategory_intro($db, $category, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT CATE_CD, CATE_NAME FROM TBL_CATEGORY WHERE CATE_CD != '01' and length(`CATE_CD`)=2 ";

		if ($category <> "") {
			$query .= " AND CATE_CD like '".$category."%' ";
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

		$query .= " ORDER BY CATE_NO ASC ";
		
	

		$result = mysql_query($query,$db);
		$record = array();

		for($i=0;$i < mysql_num_rows($result);$i++) {
			
			$record[$i] = sql_result_array($result,$i);
		}
		return $record;
	}

	function listCategoryDepth($db, $category, $depth, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {
		
		//echo $nRowCount;

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ,
										 CATE_NO, CATE_CD, CATE_NAME, CATE_ENAME, CATE_CNAME, CATE_JNAME, CATE_MEMO, CATE_FLAG, CATE_SEQ01, CATE_SEQ02, CATE_SEQ03, CATE_SEQ04, CATE_CODE,
										 CATE_IMG, CATE_IMG_OVER,M_CATE_IMG,M_CATE_STR
							FROM TBL_CATEGORY WHERE 1 = 1 ";

		if ($category <> "") {
			$query .= " AND CATE_CD like '".$category."%' ";
		}

		if ($depth <> "") {
			$query .= " AND length(CATE_CD) = '".$depth."' ";
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

		$query .= " ORDER BY SEQ ASC limit ".$offset.", ".$nRowCount;
		
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


	function cntCategoryDepth($db, $category, $depth, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT COUNT(*) AS CNT
							FROM TBL_CATEGORY WHERE 1 = 1 ";

		if ($category <> "") {
			$query .= " AND CATE_CD like '".$category."%' ";
		}

		if ($depth <> "") {
			$query .= " AND length(CATE_CD) = '".$depth."' ";
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

	function getCategoryIcon($db, $category, $depth, $goods_no) {

		$query = "SELECT DISTINCT C.CATE_IMG_OVER, C.CATE_IMG_OVER, CG.CATE_CD
								FROM TBL_CATEGORY C, TBL_CATEGORY_GOODS CG
							 WHERE C.CATE_CD = CG.CATE_CD
								 AND CG.GOODS_NO = '$goods_no'
								 AND CG.GOODS_NO = '$goods_no'
								 AND CG.CATE_CD like '$category%' 
								 AND CG.CATE_CD not like '03%' 
								 AND length(CG.CATE_CD) = $depth ";
		
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