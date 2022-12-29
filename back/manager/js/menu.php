<?

	# =============================================================================
	# File Name    : menu.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2009.08.16
	# Modify Date  : 
	#	Copyright : Copyright @아름지기 Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_ADMIN_MENU
	#=========================================================================================================

	/*
	CREATE TABLE IF NOT EXISTS TBL_ADMIN_MENU (
	MENU_NO							int(11)				unsigned NOT NULL default '0'	COMMENT	'메뉴 SEQ',
	MENU_CD							varchar(10)		NOT	NULL default ''						COMMENT	'메뉴코드',
	MENU_NAME						varchar(50)		NOT	NULL default ''						COMMENT	'메뉴명',
	MENU_URL						varchar(100)	NOT	NULL default ''						COMMENT	'메뉴 URL',
	MENU_SEQ01					varchar(3)		NOT	NULL default ''						COMMENT	'메뉴 순서 1',
	MENU_SEQ02					varchar(3)		NOT	NULL default ''						COMMENT	'메뉴 순서 2',
	MENU_SEQ03					varchar(3)		NOT	NULL default ''						COMMENT	'메뉴 순서 3',
	MENU_FLAG						char(1)				NOT	NULL default ''						COMMENT	'메뉴 상태',
	MENU_RIGHT					varchar(10)		NOT	NULL default ''						COMMENT	'메뉴 권한',
	MENU_IMG						varchar(50)		NOT	NULL default ''						COMMENT	'메뉴 이미지',
	MENU_IMG_OVER				varchar(50)		NOT	NULL default ''						COMMENT	'메뉴 이미지 2',
	USE_TF							char(1)				NOT	NULL default 'Y'					COMMENT	'사용	여부 사용(Y),사용안함(N)',
	DEL_TF							char(1)				NOT	NULL default 'N'					COMMENT	'삭제	여부 삭제(Y),사용(N)',
	REG_ADM							int(11)				unsigned											COMMENT	'등록	관리자 일련번호 TBL_ADMIN ADM_NO',
	REG_DATE						datetime																		COMMENT	'등록일',
	UP_ADM							int(11)				unsigned											COMMENT	'수정	관리자 일련번호 TBL_ADMIN ADM_NO',
	UP_DATE							datetime																		COMMENT	'수정일',
	DEL_ADM							int(11)				unsigned											COMMENT	'삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
	DEL_DATE						datetime																		COMMENT	'삭제일'
	) TYPE=MyISAM COMMENT	=	'메뉴 마스터';
	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listAdminMenu($db, $use_tf, $del_tf, $search_field, $search_str) {
		
		$query = "SELECT CONCAT(MENU_SEQ01,MENU_SEQ02,MENU_SEQ03) as SEQ,
										 MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_FLAG, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_RIGHT
							FROM TBL_ADMIN_MENU WHERE 1 = 1 ";

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

		$result = mysql_query($query,$db);
		$record = array();

		for($i=0;$i < mysql_num_rows($result);$i++) {
			
			$record[$i] = sql_result_array($result,$i);
		}
		return $record;
	}


	function insertAdmin($db, $site_no, $adm_id, $adm_pw, $adm_nm, $adm_dept, $adm_position, $adm_num, $adm_phone, $adm_hphone, $adm_fax, $adm_email, $confirm_tf, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_ADMIN (SITE_NO, ADM_ID, ADM_PW, ADM_NM, ADM_DEPT, ADM_POSITION, ADM_NUM, ADM_PHONE, ADM_HPHONE, ADM_FAX, ADM_EMAIL, CONFIRM_TF, USE_TF, REG_ADM, REG_DATE) 
											 values ('$site_no', '$adm_id', '$adm_pw', '$adm_nm', '$adm_dept', '$adm_position', '$adm_num', '$adm_phone', '$adm_hphone', '$adm_fax', '$adm_email', '$confirm_tf', '$use_tf', '$reg_adm', now()); ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectAdmin($db, $adm_no) {

		$query = "SELECT ADM_NO, SITE_NO, ADM_ID, ADM_PW, ADM_NM, ADM_DEPT, ADM_POSITION, ADM_NUM, ADM_PHONE, 
										 ADM_HPHONE, ADM_FAX, ADM_EMAIL, CONFIRM_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN WHERE ADM_NO = '$adm_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function updateAdmin($db, $site_no, $adm_id, $adm_pw, $adm_nm, $adm_dept, $adm_position, $adm_num, $adm_phone, $adm_hphone, $adm_fax, $adm_email, $confirm_tf, $use_tf, $up_adm, $adm_no) {
		
		$query="UPDATE TBL_ADMIN SET 
														 SITE_NO			= '$site_no', 
														 ADM_ID				= '$adm_id', 
														 ADM_PW				= '$adm_pw', 
														 ADM_NM				= '$adm_nm', 
														 ADM_DEPT			= '$adm_dept', 
														 ADM_POSITION	= '$adm_position', 
														 ADM_NUM			= '$adm_num', 
														 ADM_PHONE		= '$adm_phone', 
														 ADM_HPHONE		= '$adm_hphone', 
														 ADM_FAX			= '$adm_fax', 
														 ADM_EMAIL		= '$adm_email', 
														 CONFIRM_TF		= '$confirm_tf', 
														 USE_TF				= '$use_tf',
														 UP_ADM				= '$up_adm',
														 UP_DATE			= now()
											 WHERE ADM_NO				= '$adm_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteAdmin($db, $del_adm, $adm_no) {

		$query="UPDATE TBL_ADMIN SET 
														 DEL_TF				= 'Y',
														 DEL_ADM			= '$del_adm',
														 DEL_DATE			= now()														 
											 WHERE ADM_NO				= '$adm_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function dupAdmin ($db,$adm_id) {
		
		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN WHERE 1 = 1 AND DEL_TF = 'N' ";
		
		if ($adm_id <> "") {
			$query .= " AND ADM_ID = '".$adm_id."' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		if ($rows[0] == 0) {
			return 0;
		} else {
			return 1;
		}
				
	}


	function confirmAdmin($db, $adm_id) {
	
		$query = "SELECT ADM_NO, SITE_NO, ADM_ID, ADM_PW, ADM_NM, ADM_DEPT, ADM_POSITION, ADM_NUM, ADM_PHONE, 
										 ADM_HPHONE, ADM_FAX, ADM_EMAIL, CONFIRM_TF, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN A WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND ADM_ID = '$adm_id' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;	
	}

	function insertUserLog($db, $user_type, $log_id, $log_ip) {
		
		$query="INSERT INTO TBL_USER_LOG (USER_TYPE, LOG_ID, LOG_IP, LOGIN_DATE) 
															 values ('$user_type', '$log_id', '$log_ip', now()); ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateAdminConfirmTF($db, $confirm_tf, $up_adm, $adm_no) {
		
		$query="UPDATE TBL_ADMIN SET 
							CONFIRM_TF			= '$confirm_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE ADM_NO			= '$adm_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
?>