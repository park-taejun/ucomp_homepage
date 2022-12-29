<?
	# =============================================================================
	# File Name    : banner.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2009.08.12
	# Modify Date  : 
	#	Copyright : Copyright @MONEUAL Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_BANNER
	#=========================================================================================================
	
	/*
	CREATE TABLE IF	NOT	EXISTS TBL_BANNER (
	BANNER_NO						int(11) unsigned	NOT	NULL auto_increment	COMMENT	'배너	일련번호',
	SITE_NO							int(11)	unsigned													COMMENT	'사이트 일련번호',
	BANNER_TYPE					varchar(30)	NOT	NULL default ''						COMMENT	'배너 TYPE',
	BANNER_NM						varchar(30)	NOT	NULL default ''						COMMENT	'배너 이름',
	BANNER_IMG					varchar(50)	NOT	NULL default ''						COMMENT	'배너 이미지 명',
	BANNER_REAL_IMG			varchar(50)	NOT	NULL default ''						COMMENT	'배너 이미지 실제 명',
	BANNER_URL					varchar(150)NOT	NULL default ''						COMMENT	'배너 URL',
	TITLE_NM						varchar(50)	NOT	NULL default ''						COMMENT	'배너 이름',
	TITLE_IMG						varchar(50)	NOT	NULL default ''						COMMENT	'배너 이미지 명',
	TITLE_REAL_IMG			varchar(50)	NOT	NULL default ''						COMMENT	'배너 이미지 실제 명',
	URL_TYPE						varchar(10)	NOT	NULL default ''						COMMENT	'배너 순서',
	DISP_SEQ						int(11)	unsigned													COMMENT	'배너 순서',
	USE_TF							char(1)	NOT	NULL default 'Y'							COMMENT	'사용	여부 사용(Y),사용안함(N)',
	DEL_TF							char(1)	NOT	NULL default 'N'							COMMENT	'삭제	여부 삭제(Y),사용(N)',
	REG_ADM							int(11)	unsigned													COMMENT	'등록	관리자 일련번호 TBL_ADMIN ADM_NO',
	REG_DATE						datetime																	COMMENT	'등록일',
	UP_ADM							int(11)	unsigned													COMMENT	'수정	관리자 일련번호 TBL_ADMIN ADM_NO',
	UP_DATE							datetime																	COMMENT	'수정일',
	DEL_ADM							int(11)	unsigned													COMMENT	'삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
	DEL_DATE						datetime																	COMMENT	'삭제일',
	PRIMARY	KEY	 (`BANNER_NO`)
	)	TYPE=MyISAM COMMENT	=	'배너 마스터';
	*/

	#BANNER_NO, SITE_NO, BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, DISP_SEQ, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listBanner($db, $site_no, $banner_lang, $banner_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntBanner ($db, $site_no, $banner_lang, $banner_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BANNER_NO, SITE_NO, BANNER_LANG, BANNER_TYPE, 
										 BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, BANNER_URL, TITLE_NM, TITLE_IMG, TITLE_REAL_IMG, URL_TYPE, DISP_SEQ,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BANNER WHERE 1 = 1 ";

		if ($banner_type <> "") {
			$query .= " AND BANNER_TYPE = '".$banner_type."' ";
		}

		if ($site_no <> "") {
			$query .= " AND SITE_NO = '".$site_no."' ";
		}

		if ($banner_lang <> "") {
			$query .= " AND BANNER_LANG = '".$banner_lang."' ";
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
		
		$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, BANNER_NO DESC limit ".$offset.", ".$nRowCount;

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


	function totalCntBanner ($db, $site_no, $banner_lang, $banner_type, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_BANNER WHERE 1 = 1 ";

		if ($banner_type <> "") {
			$query .= " AND BANNER_TYPE = '".$banner_type."' ";
		}

		if ($site_no <> "") {
			$query .= " AND SITE_NO = '".$site_no."' ";
		}

		if ($banner_lang <> "") {
			$query .= " AND BANNER_LANG = '".$banner_lang."' ";
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


	function insertBanner($db, $site_no, $banner_lang, $banner_type, $banner_nm, $banner_img, $banner_real_img, $banner_url, $title_nm, $title_img, $title_real_img, $url_type, $disp_seq, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_BANNER (SITE_NO, BANNER_LANG, BANNER_TYPE, BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, BANNER_URL, TITLE_NM, TITLE_IMG, TITLE_REAL_IMG,
												URL_TYPE, DISP_SEQ, USE_TF, REG_ADM, REG_DATE) 
											 values ('$site_no', '$banner_lang', '$banner_type', '$banner_nm', '$banner_img', '$banner_real_img', '$banner_url', 
												'$title_nm', '$title_img', '$title_real_img', '$url_type', '$disp_seq', '$use_tf', '$reg_adm', now()); ";
		
		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectBanner($db, $site_no, $banner_no) {

		$query = "SELECT BANNER_NO, SITE_NO, BANNER_LANG, BANNER_TYPE, BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, BANNER_URL, TITLE_NM, TITLE_IMG, TITLE_REAL_IMG, 
										 URL_TYPE, DISP_SEQ, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BANNER WHERE BANNER_NO = '$banner_no' AND SITE_NO = '$site_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function updateBanner($db, $site_no, $banner_lang, $banner_type, $banner_nm, $banner_img, $banner_real_img, $banner_url, $title_nm, $title_img, $title_real_img, $url_type, $use_tf, $up_adm, $banner_no) {
		
		$query="UPDATE TBL_BANNER SET 
							SITE_NO					= '$site_no', 
							BANNER_LANG			= '$banner_lang', 
							BANNER_TYPE			= '$banner_type', 
							BANNER_NM				= '$banner_nm', 
							BANNER_IMG			= '$banner_img', 
							BANNER_REAL_IMG	= '$banner_real_img', 
							BANNER_URL			= '$banner_url', 
							TITLE_NM				= '$title_nm', 
							TITLE_IMG				= '$title_img', 
							TITLE_REAL_IMG	= '$title_real_img', 
							URL_TYPE				= '$url_type', 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE BANNER_NO			= '$banner_no' AND SITE_NO = '$site_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBannerUseTF($db, $use_tf, $up_adm, $site_no, $banner_no) {
		
		$query="UPDATE TBL_BANNER SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE BANNER_NO			= '$banner_no' AND SITE_NO = '$site_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrderBanner($db, $disp_seq_no, $site_no, $banner_no) {

		$query="UPDATE TBL_BANNER SET
										DISP_SEQ	=	'$disp_seq_no'
							WHERE BANNER_NO	= '$banner_no' AND SITE_NO = '$site_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

		function deleteBanner($db, $del_adm, $g_site_no, $tmp_banner_no) {

			$query = "UPDATE TBL_BANNER SET
							 DEL_TF				= 'Y',
							 DEL_ADM			= '$del_adm',
							 DEL_DATE			= now()
							WHERE SITE_NO				= '$g_site_no' 
							AND BANNER_NO				= '$tmp_banner_no' ";
	
			//echo $query."<br>";

			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			} else {
				return true;
			}
		}
?>