<?

	# =============================================================================
	# File Name    : company.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2009.06.05
	# Modify Date  : 
	#	Copyright : Copyright @minumsa Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_COMPANY
	#=========================================================================================================

	/*
	CREATE TABLE IF NOT EXISTS TBL_COMPANY (
	CP_NO								int(11)	unsigned NOT NULL	auto_increment	COMMENT	'회사	일련번호', 
	CP_TYPE							varchar(10)	NOT	NULL default ''						COMMENT	'거래 타입',
	CP_NM								varchar(50)	NOT	NULL default ''						COMMENT	'회사명',
	CP_PHONE						varchar(20)	NOT	NULL default ''						COMMENT	'대표	전화번호',
	CP_HPHONE						varchar(20)	NOT	NULL default ''						COMMENT	'대표	휴대전화번호',
	CP_FAX							varchar(20)	NOT	NULL default ''						COMMENT	'대표	Fax',
	CP_ZIP							varchar(10)	NOT	NULL default ''						COMMENT	'우편번호',	
	CP_ADDR							varchar(50)	NOT	NULL default ''						COMMENT	'주소',
	RE_ZIP							varchar(10)	NOT	NULL default ''						COMMENT	'반품 우편번호',	
	RE_ADDR							varchar(50)	NOT	NULL default ''						COMMENT	'반품 주소',
	HOMEPAGE						varchar(80)	NOT	NULL default ''						COMMENT	'사이트 주소',
	BIZ_NO							varchar(20)	NOT	NULL default ''						COMMENT	'사업자	번호',
	CEO_NM							varchar(20)	NOT	NULL default ''						COMMENT	'대표자	명',
	UPJONG							varchar(30)	NOT	NULL default ''						COMMENT	'업종',
	UPTEA								varchar(30)	NOT	NULL default ''						COMMENT	'업태',
	DC_RATE							int(11)																		COMMENT	'할인율 0 ~ 100 까지의 값 사용',
	MANAGER_NM					varchar(20)	NOT	NULL default ''						COMMENT	'담당자	명',
	PHONE								varchar(20)	NOT	NULL default ''						COMMENT	'담당자	전화번호',
	HPHONE							varchar(20)	NOT	NULL default ''						COMMENT	'담당자	휴대 전화번호',
	FPHONE							varchar(20)	NOT	NULL default ''						COMMENT	'담당자	FAX',
	EMAIL								varchar(50)	NOT	NULL default ''						COMMENT ' DATA 담당자 이메일',
	EMAIL_TF						char(1)	NOT	NULL default 'Y'							COMMENT ' DATA담당자 이메일 수신여부 사용(Y),사용안함(N)',
	CONTRACT_START			date																			COMMENT	'계약	시작일',
	CONTRACT_END				date	NOT	NULL default '9999-12-30'				COMMENT	'계약	종료일',
	AD_TYPE							varchar(30)	NOT	NULL default ''						COMMENT '정산 구분',
	ACCOUNT_BANK				varchar(30)	NOT	NULL default ''						COMMENT '거래 은행',
	ACCOUNT							varchar(20)	NOT	NULL default ''						COMMENT '계좌 번호',
	MEMO								TEXT	NOT	NULL default ''									COMMENT '몌모',
	USE_TF							char(1)	NOT	NULL default 'Y'							COMMENT	'사용	여부 사용(Y),사용안함(N)',
	DEL_TF							char(1)	NOT	NULL default 'N'							COMMENT	'삭제	여부 삭제(Y),사용(N)',
	REG_ADM							int(11)	unsigned													COMMENT	'등록	관리자 일련번호 TBL_ADMIN ADM_NO',
	REG_DATE						datetime																	COMMENT	'등록일',
	UP_ADM							int(11)	unsigned													COMMENT	'수정	관리자 일련번호 TBL_ADMIN ADM_NO',
	UP_DATE							datetime																	COMMENT	'수정일',
	DEL_ADM							int(11)	unsigned													COMMENT	'삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
	DEL_DATE						datetime																	COMMENT	'삭제일',
	PRIMARY KEY  (CP_NO)
	) TYPE=MyISAM COMMENT	=	'업체 마스터';
	*/
	#=========================================================================================================
	# End Table
	#=========================================================================================================


	# CP_NO, CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, BIZ_NO, CEO_NM, UPJONG, UPTEA, 
	# DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, AD_TYPE, ACCOUNT_BANK, ACCOUNT, 
	# MEMO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE				

	function listCompany($db, $cp_type, $ad_type, $date_start, $date_end, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount) {

		$total_cnt = totalCntCompany($db, $cp_type, $ad_type, $date_start, $date_end, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, CP_NO, CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, 
										 BIZ_NO, CEO_NM, UPJONG, UPTEA, DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, 
										 AD_TYPE, ACCOUNT_BANK, ACCOUNT, MEMO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_COMPANY WHERE 1 = 1 ";

		if ($cp_type <> "") {
			$query .= " AND CP_TYPE = '".$cp_type."' ";
		}

		if ($ad_type <> "") {
			$query .= " AND AD_TYPE = '".$ad_type."' ";
		}

		if ($date_start <> "" && $date_end <> "") {
			$query .= " AND CONTRACT_START BETWEEN '".$date_start."' AND date_add('".$date_end."', interval 1 day) ";
		} else if ($date1 <> "") {
			$query .= " AND CONTRACT_START  >= '".$date_start."'	";
		} else if ($date2 <> "") {
			$query .= " AND CONTRACT_START  <= date_add('".$date_end."', interval 1 day)	";
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
		

		if ($order_field == "") 
			$order_field = "REG_DATE";

		if ($order_str == "") 
			$order_str = "DESC";

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntCompany($db, $cp_type, $ad_type, $date_start, $date_end, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_COMPANY WHERE 1 = 1 ";

		if ($cp_type <> "") {
			$query .= " AND CP_TYPE = '".$cp_type."' ";
		}

		if ($ad_type <> "") {
			$query .= " AND AD_TYPE = '".$ad_type."' ";
		}

		if ($date_start <> "" && $date_end <> "") {
			$query .= " AND CONTRACT_START BETWEEN '".$date_start."' AND date_add('".$date_end."', interval 1 day) ";
		} else if ($date1 <> "") {
			$query .= " AND CONTRACT_START  >= '".$date_start."'	";
		} else if ($date2 <> "") {
			$query .= " AND CONTRACT_START  <= date_add('".$date_end."', interval 1 day)	";
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


	function insertCompany($db, $cp_type, $cp_nm, $cp_phone, $cp_hphone, $cp_fax, $cp_zip, $cp_addr, $re_zip, $re_addr, $homepage, $biz_no, $ceo_nm, $upjong, $uptea, $dc_rate, $manager_nm, $phone, $hphone, $fphone, $email, $email_tf, $contract_start, $contract_end, $ad_type, $account_bank, $account, $memo, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_COMPANY (CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, BIZ_NO, CEO_NM, UPJONG, UPTEA, 
																		 DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, AD_TYPE, ACCOUNT_BANK, ACCOUNT,
																		 MEMO, USE_TF, REG_ADM, REG_DATE) 
															 values ('$cp_type', '$cp_nm', '$cp_phone', '$cp_hphone', '$cp_fax', '$cp_zip', '$cp_addr', '$re_zip', '$re_addr', 
																			 '$homepage', '$biz_no', '$ceo_nm', '$upjong', '$uptea', '$dc_rate', '$manager_nm', '$phone', '$hphone', '$fphone', 
																			 '$email', '$email_tf', '$contract_start', '$contract_end', '$ad_type', '$account_bank', '$account', '$memo', 
																			 '$use_tf', '$reg_adm', now()); ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectCompany($db, $cp_no) {

		$query = "SELECT CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, BIZ_NO, CEO_NM, UPJONG, UPTEA, 
										 DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, AD_TYPE, 
										 ACCOUNT_BANK, ACCOUNT, MEMO, USE_TF, DEL_TF, 
										 REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_COMPANY WHERE CP_NO = '$cp_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function updateCompany($db, $cp_type, $cp_nm, $cp_phone, $cp_hphone, $cp_fax, $cp_zip, $cp_addr, $re_zip, $re_addr, $homepage, $biz_no, $ceo_nm, $upjong, $uptea, $dc_rate, $manager_nm, $phone, $hphone, $fphone, $email, $email_tf, $contract_start, $contract_end, $ad_type, $account_bank, $account, $memo, $use_tf, $up_adm, $cp_no) {

		$query="UPDATE TBL_COMPANY SET 
													CP_TYPE					= '$cp_type',
													CP_NM						= '$cp_nm',
													CP_PHONE				= '$cp_phone',
													CP_HPHONE				= '$cp_hphone',
													CP_FAX					= '$cp_fax',
													CP_ZIP					= '$cp_zip',
													CP_ADDR					= '$cp_addr',
													RE_ZIP					= '$re_zip',
													RE_ADDR					= '$re_addr',
													HOMEPAGE				= '$homepage',
													BIZ_NO					= '$biz_no',
													CEO_NM					= '$ceo_nm',
													UPJONG					= '$upjong',
													UPTEA						= '$uptea',
													DC_RATE					= '$dc_rate',
													MANAGER_NM			= '$manager_nm',
													PHONE						= '$phone',
													HPHONE					= '$hphone',
													FPHONE					= '$fphone',
													EMAIL						= '$email',
													EMAIL_TF				= '$email_tf',
													CONTRACT_START	= '$contract_start',
													CONTRACT_END		= '$contract_end',
													AD_TYPE					= '$ad_type',
													ACCOUNT_BANK		= '$account_bank',
													ACCOUNT					= '$account',
													MEMO						= '$memo',
													USE_TF					= '$use_tf',
													UP_ADM					=	'$up_adm',
													UP_DATE					=	now()
											 WHERE CP_NO				= '$cp_no' ";
		
		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteCompany($db, $del_adm, $cp_no) {

		$query="UPDATE TBL_COMPANY SET 
														 DEL_TF				= 'Y',
														 DEL_ADM			= '$del_adm',
														 DEL_DATE			= now()														 
											 WHERE CP_NO				= '$cp_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function listTempCompany($db, $temp_no) {

		$query = "SELECT TEMP_NO, CP_NO, CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, 
										 BIZ_NO, CEO_NM, UPJONG, UPTEA, DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, 
										 AD_TYPE, ACCOUNT_BANK, ACCOUNT, MEMO, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_TEMP_COMPANY WHERE TEMP_NO = '$temp_no' ";

		
		$query .= " ORDER BY CP_NO asc ";
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertTempCompany($db, $file_nm, $cp_type, $cp_nm, $cp_phone, $cp_hphone, $cp_fax, $cp_zip, $cp_addr, $re_zip, $re_addr, $homepage, $biz_no, $ceo_nm, $upjong, $uptea, $dc_rate, $manager_nm, $phone, $hphone, $fphone, $email, $email_tf, $contract_start, $contract_end, $ad_type, $account_bank, $account, $memo, $use_tf, $reg_adm) {
		

		$query="INSERT INTO TBL_TEMP_COMPANY (TEMP_NO, CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, BIZ_NO, CEO_NM, UPJONG, UPTEA, 
																		 DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, AD_TYPE, ACCOUNT_BANK, ACCOUNT,
																		 MEMO, USE_TF, REG_ADM, REG_DATE) 
															 values ('$file_nm','$cp_type', '$cp_nm', '$cp_phone', '$cp_hphone', '$cp_fax', '$cp_zip', '$cp_addr', '$re_zip', '$re_addr', 
																			 '$homepage', '$biz_no', '$ceo_nm', '$upjong', '$uptea', '$dc_rate', '$manager_nm', '$phone', '$hphone', '$fphone', 
																			 '$email', '$email_tf', '$contract_start', '$contract_end', '$ad_type', '$account_bank', '$account', '$memo', 
																			 '$use_tf', '$reg_adm', now()); ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectTempCompany($db, $temp_no, $cp_no) {

		$query = "SELECT TEMP_NO, CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, BIZ_NO, CEO_NM, UPJONG, UPTEA, 
										 DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, AD_TYPE, 
										 ACCOUNT_BANK, ACCOUNT, MEMO, USE_TF, DEL_TF, 
										 REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_TEMP_COMPANY WHERE TEMP_NO = '$temp_no' AND CP_NO = '$cp_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function updateTempCompany($db, $cp_type, $cp_nm, $cp_phone, $cp_hphone, $cp_fax, $cp_zip, $cp_addr, $re_zip, $re_addr, $homepage, $biz_no, $ceo_nm, $upjong, $uptea, $dc_rate, $manager_nm, $phone, $hphone, $fphone, $email, $email_tf, $contract_start, $contract_end, $ad_type, $account_bank, $account, $memo, $use_tf, $up_adm, $temp_no, $cp_no) {

		$query="UPDATE TBL_TEMP_COMPANY SET 
													CP_TYPE					= '$cp_type',
													CP_NM						= '$cp_nm',
													CP_PHONE				= '$cp_phone',
													CP_HPHONE				= '$cp_hphone',
													CP_FAX					= '$cp_fax',
													CP_ZIP					= '$cp_zip',
													CP_ADDR					= '$cp_addr',
													RE_ZIP					= '$re_zip',
													RE_ADDR					= '$re_addr',
													HOMEPAGE				= '$homepage',
													BIZ_NO					= '$biz_no',
													CEO_NM					= '$ceo_nm',
													UPJONG					= '$upjong',
													UPTEA						= '$uptea',
													DC_RATE					= '$dc_rate',
													MANAGER_NM			= '$manager_nm',
													PHONE						= '$phone',
													HPHONE					= '$hphone',
													FPHONE					= '$fphone',
													EMAIL						= '$email',
													EMAIL_TF				= '$email_tf',
													CONTRACT_START	= '$contract_start',
													CONTRACT_END		= '$contract_end',
													AD_TYPE					= '$ad_type',
													ACCOUNT_BANK		= '$account_bank',
													ACCOUNT					= '$account',
													MEMO						= '$memo',
													USE_TF					= '$use_tf',
													UP_ADM					=	'$up_adm',
													UP_DATE					=	now()
										WHERE TEMP_NO					= '$temp_no'
											AND CP_NO						= '$cp_no' ";
		
		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteTempCompany($db, $temp_no, $cp_no) {

		$query="DELETE FROM TBL_TEMP_COMPANY WHERE TEMP_NO = '$temp_no' AND CP_NO = '$cp_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function chkCpNm($db, $cp_nm) {
		$query="SELECT COUNT(*) AS CNT FROM TBL_COMPANY WHERE CP_NM = '$cp_nm' AND DEL_TF = 'N' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] == 0) {
			return true;
		} else {
			return false;
		}
	}


	function insertTempToRealCompany($db, $str_cp_no) {
		

		$query="INSERT INTO TBL_COMPANY (CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, BIZ_NO, CEO_NM, UPJONG, UPTEA, 
																		 DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, AD_TYPE, ACCOUNT_BANK, ACCOUNT,
																		 MEMO, USE_TF, REG_ADM, REG_DATE) 
														 SELECT  CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, BIZ_NO, CEO_NM, UPJONG, UPTEA, 
																		 DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, AD_TYPE, ACCOUNT_BANK, ACCOUNT,
																		 MEMO, USE_TF, REG_ADM, REG_DATE
															 FROM  TBL_TEMP_COMPANY
															WHERE  CP_NO IN ($str_cp_no) ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteTempToRealCompany($db, $str_cp_no) {
		

		$query=" DELETE FROM  TBL_TEMP_COMPANY WHERE  CP_NO IN ($str_cp_no) ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
?>