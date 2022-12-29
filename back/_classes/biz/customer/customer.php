<?

	function listCustomer($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $category, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntCustomer($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $category, $use_tf, $del_tf, $search_field, $search_str);
		
		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 COMPANY_NM, HOMEPAGE, HOSTING, FTP_ADDR, FTP_PORT, FTP_ID, FTP_PW, DB_ADDR, DB_NM, DB_ID, DB_PW, ADMIN_ADDR, ADMIN_ID, ADMIN_PW, CONTENTS, CATEGORY,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF 
								FROM TBL_CUSTOMER WHERE 1 = 1 ";

		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($category <> "") {
			$query .= " AND CATEGORY  = '".$category."' ";
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

		$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;


		#echo $query."<br>";


		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function totalCntCustomer($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $category, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_CUSTOMER WHERE 1 = 1 ";
		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($category <> "") {
			$query .= " AND CATEGORY  = '".$category."' ";
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

	#	echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function selectPostBoard($db, $bb_code, $bb_po, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
								FROM TBL_BOARD WHERE BB_PO > '$bb_po' ";

		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
	//		$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//$query .= " ORDER BY BB_PO limit 1";
		$query .= " ORDER BY REG_DATE DESC limit 1";
	//	echo $query;
				
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

function selectPostBoardAsDate($db, $bb_code, $bb_no, $reg_date, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
										 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
										 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, THUMB_IMG, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
								FROM TBL_BOARD WHERE REG_DATE < '".$reg_date."' ";
								//FROM TBL_BOARD WHERE CONCAT(REG_DATE,BB_NO) < '".$reg_date.$bb_no."' ";

		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
	//		$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		//$query .= " ORDER BY BB_PO limit 1";
		$query .= " ORDER BY REG_DATE DESC limit 1";
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

	function selectPreBoard($db, $bb_code, $bb_po, $cate_01, $cate_02, $cate_03, $cate_04, $writer_id, $ref_ip, $reply_state, $use_tf, $del_tf, $search_field, $search_str) {

		$query = "SELECT BB_CODE, BB_NO, BB_PO, BB_RE, BB_DE, CATE_01, CATE_02, CATE_03, CATE_04, 
							 WRITER_ID, WRITER_NM, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_IP, RECOMM, RECOMMNO, CONTENTS,
							 FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, 
							 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
					FROM TBL_BOARD WHERE BB_PO < '$bb_po' ";

		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($cate_01 <> "") {
			$query .= " AND CATE_01 = '".$cate_01."' ";
		}

		if ($cate_02 <> "") {
			$query .= " AND CATE_02 = '".$cate_02."' ";
		}

		if ($cate_03 <> "") {
			$query .= " AND CATE_03 = '".$cate_03."' ";
		}

		if ($cate_04 <> "") {
			$query .= " AND CATE_04 = '".$cate_04."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($ref_ip <> "") {
//			$query .= " AND REF_IP = '".$ref_ip."' ";
		}

		if ($reply_state <> "") {
			$query .= " AND REPLY_STATE = '".$reply_state."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ((REPLY like '%".$search_str."%') or (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') or (WRITER_ID like '%".$search_str."%') or (WRITER_NM like '%".$search_str."%')) ";
			}elseif($search_field == "ALL2") {
				$query .= " AND ((CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%')) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
								
		//$query .= " ORDER BY BB_PO DESC limit 1";
		$query .= " ORDER BY REG_DATE ASC limit 1";
		
	//	echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}



	function insertCustomer($db, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $customer_cate, $company_nm, $homepage, $hosting, $ftp_addr, $ftp_port, $ftp_id, $ftp_pw, $db_addr, $db_nm, $db_id, $db_pw, $admin_addr, $admin_id, $admin_pw, $contents, $use_tf) {
		
		$query ="SELECT IFNULL(MAX(BB_NO),0) AS MAX_NO FROM TBL_CUSTOMER ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] <> 0) {
					
			$new_bb_no = $rows[0] + 1;

			//답변글 번호 찾기
			//$query2 ="SELECT IFNULL(MAX(BB_RE),0) AS MAX_NO FROM TBL_BOARD WHERE BB_CODE = '$bb_code' ";
			$query2 ="SELECT IFNULL(MAX(BB_RE),0) AS MAX_NO FROM TBL_CUSTOMER ";
			$result2 = mysql_query($query2,$db);
			$rows2   = mysql_fetch_array($result2);

			$new_bb_re = $rows2[0] + 1;

			//po 최소값 찾기
			//$query3 ="SELECT IFNULL(MIN(BB_PO),0) AS MAX_NO FROM TBL_BOARD WHERE BB_CODE = '$bb_code' ";
			$query3 ="SELECT IFNULL(MIN(BB_PO),0) AS MAX_NO FROM TBL_CUSTOMER  ";
			$result3 = mysql_query($query3,$db);
			$rows3   = mysql_fetch_array($result3);

			$new_bb_po = $rows3[0] + 1;


			//$query4 ="UPDATE TBL_BOARD SET BB_PO = BB_PO + 1 WHERE BB_CODE = '$bb_code' AND BB_PO > 0 ";
			$query4 ="UPDATE TBL_CUSTOMER SET BB_PO = BB_PO + 1 WHERE BB_PO > 0 ";

			mysql_query($query4,$db);
		
		} else {
		
			$new_bb_no = "1";
			$new_bb_po = "1";
			$new_bb_re = "1";
			$new_bb_de = "1";

		}
		
		$query5="INSERT INTO TBL_CUSTOMER (BB_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, COMPANY_NM, HOMEPAGE, HOSTING, FTP_ADDR, FTP_PORT, FTP_ID, FTP_PW, DB_ADDR, DB_NM, DB_ID, DB_PW, ADMIN_ADDR, ADMIN_ID, ADMIN_PW, CONTENTS, CATEGORY, USE_TF, REG_DATE) 
				values ('$bb_code', '$cate_01', '$cate_02', '$cate_03', '$cate_04', '$new_bb_no', '1', '$new_bb_re', '1', '$company_nm', '$homepage', '$hosting', '$ftp_addr', '$ftp_port', '$ftp_id', '$ftp_pw', '$db_addr', '$db_nm', '$db_id', '$db_pw', '$admin_addr', '$admin_id', '$admin_pw', '$contents', '$customer_cate', '$use_tf', now()); ";
	
	//echo $query5;
	//die;

		if(!mysql_query($query5,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return $new_bb_no;
		}

	}


	function selectCustomer($db, $bb_code, $bb_no) {

		$query = "SELECT BB_CODE, CATE_01, CATE_02, CATE_03, CATE_04, BB_NO, BB_PO, BB_RE, BB_DE, COMPANY_NM, HOMEPAGE, HOSTING, FTP_ADDR, FTP_PORT, FTP_ID, FTP_PW, DB_ADDR, DB_NM, DB_ID, DB_PW, ADMIN_ADDR, ADMIN_ID, ADMIN_PW, CONTENTS, CATEGORY, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE FROM TBL_CUSTOMER WHERE  BB_CODE = '$bb_code' AND  BB_NO = '$bb_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function updateCustomer($db, $cate_01, $cate_02, $cate_03, $cate_04, $category, $company_nm, $homepage, $hosting, $ftp_addr, $ftp_port, $ftp_id, $ftp_pw, $db_addr, $db_nm, $db_id, $db_pw, $admin_addr, $admin_id, $admin_pw, $contents, $use_tf, $up_adm, $bb_code, $bb_no) {

		$query = "UPDATE TBL_CUSTOMER SET 
						CATE_01				=	'$cate_01',
						CATE_02				=	'$cate_02',
						CATE_03				=	'$cate_03',
						CATE_04				=	'$cate_04',
						CATEGORY			=	'$category',
						COMPANY_NM	=	'$company_nm',
						HOMEPAGE			=	'$homepage',
						HOSTING			=	'$hosting',
						FTP_ADDR			=	'$ftp_addr',
						FTP_PORT			=	'$ftp_port',
						FTP_ID				=	'$ftp_id',
						FTP_PW				=	'$ftp_pw',
						DB_ADDR				=	'$db_addr',
						DB_NM				=	'$db_nm',
						DB_ID					=	'$db_id',
						DB_PW					=	'$db_pw',
						ADMIN_ADDR		=	'$admin_addr',
						ADMIN_ID			=	'$admin_id',
						ADMIN_PW			=	'$admin_pw',
						CONTENTS			= '$contents',
						USE_TF				=	'$use_tf',
						UP_ADM				=	'$up_adm',
						UP_DATE				=	now()
				 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";


		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}



	function updateBoardUseTF($db, $use_tf, $up_adm, $bb_code, $bb_no) {
		
		$query="UPDATE TBL_CUSTOMER SET 
							USE_TF					= '$use_tf',
							UP_ADM					= '$up_adm',
							UP_DATE					= now()
				 WHERE BB_CODE = '$bb_code' AND BB_NO = '$bb_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function deleteCustomer($db, $del_adm, $bb_code, $bb_no) {

		$query =  "SELECT BB_DE, BB_RE FROM TBL_CUSTOMER 
							  WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND BB_CODE				= '$bb_code' 
									AND BB_NO					= '$bb_no' ";
		
		$result = mysql_query($query);
		$list = mysql_fetch_array($result);
		
		$sde = $list[BB_DE];
		$sre = $list[BB_RE];

		$query =	"SELECT BB_DE FROM TBL_CUSTOMER 
								WHERE USE_TF = 'Y' 
									AND DEL_TF = 'N'
									AND BB_CODE = '$bb_code' 
									AND BB_RE = '$sre' 
								ORDER BY BB_DE DESC limit 1";

		$result = mysql_query($query);
		$list		= mysql_fetch_array($result);
		$chk_sde = $list[BB_DE];

		$query = "DELETE FROM TBL_CUSTOMER 
					 WHERE BB_CODE				= '$bb_code' 
						AND BB_NO					= '$bb_no' ";

		mysql_query($query,$db);

		if ($sde != $chk_sde) { 
			
			$query = "UPDATE TBL_CUSTOMER SET 
									TITLE = '작성자 또는 관리자에 의해 삭제 되었습니다.', 
									CONTENTS = '답변글이 남아 있어 내용만 삭제 되었습니다.'
						WHERE BB_CODE				= '$bb_code' 
							AND BB_NO					= '$bb_no' ";
		} else {

			$query = "UPDATE TBL_CUSTOMER SET
							 DEL_TF				= 'Y',
							 DEL_ADM			= '$del_adm',
							 DEL_DATE			= now()
						WHERE BB_CODE				= '$bb_code' 
							AND BB_NO					= '$bb_no' ";
		}
		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateBoardConfig($db, $site_no, $board_code, $board_type, $board_cate, $read_group, $write_group, $re_tf, $reply_tf, $html_tf, $file_tf, $file_cnt, $board_nm, $board_memo, $board_badword, $use_tf, $up_adm, $site_no, $config_no) {
		
		$new_board_code	= "GRBBS_".$site_no."_".$config_no;

		$query = "UPDATE TBL_BOARD_CONFIG SET 
							SITE_NO				=	'$site_no',
							BOARD_CODE			=	'$new_board_code',
							BOARD_TYPE			=	'$board_type',
							BOARD_CATE			=	'$board_cate',
							READ_GROUP			=	'$read_group',
							WRITE_GROUP			=	'$write_group',
							RE_TF				=	'$re_tf',
							REPLY_TF			=	'$reply_tf',
							HTML_TF				=	'$html_tf',
							FILE_TF				=	'$file_tf',
							FILE_CNT			= '$file_cnt',
							BOARD_NM			= '$board_nm',
							BOARD_MEMO			= '$board_memo',
							BOARD_BADWORD		= '$board_badword',							
							USE_TF				= '$use_tf',
							UP_ADM				=	'$up_adm',
							UP_DATE				=	now()
				WHERE SITE_NO		=	'$site_no' AND CONFIG_NO = '$config_no' ";
		
		//echo $query."<br>";


		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectBoardConfig($db, $site_no, $config_no) {

		$query = "SELECT B.CONFIG_NO, B.SITE_NO, B.BOARD_CODE, B.BOARD_TYPE, B.BOARD_CATE, B.READ_GROUP, B.WRITE_GROUP, B.RE_TF, B.REPLY_TF, B.HTML_TF, 
										 B.FILE_TF, B.FILE_CNT, B.BOARD_NM, B.BOARD_MEMO, B.BOARD_BADWORD, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE
								FROM TBL_BOARD_CONFIG B
							 WHERE B.SITE_NO		=	'$site_no' 
								 AND B.CONFIG_NO = '$config_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();
			
		//echo "<!--".$query."-->";

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function deleteBoardConfig($db, $del_adm, $site_no, $config_no) {

		$query="UPDATE TBL_BOARD_CONFIG SET 
														 DEL_TF				= 'Y',
														 DEL_ADM			= '$del_adm',
														 DEL_DATE			= now()														 
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateBoardConfigUseTF($db, $use_tf, $up_adm, $site_no, $config_no) {
		
		$query="UPDATE TBL_BOARD_CONFIG SET 
													USE_TF					= '$use_tf',
													UP_ADM					= '$up_adm',
													UP_DATE					= now()
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateBoardConfigRealTF($db, $real_tf, $up_adm, $site_no, $config_no) {
		
		$query="UPDATE TBL_BOARD_CONFIG SET 
													REAL_TF					= '$real_tf',
													UP_ADM					= '$up_adm',
													UP_DATE					= now()
										WHERE SITE_NO		=	'$site_no' 
											AND CONFIG_NO = '$config_no' ";

		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}




/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//임시 저장용 관련
*/


	function totalCntTemporarySave($db, $bb_code, $writer_id, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_BOARD_TEMPORARY  WHERE 1 = 1 ";

		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ( (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') ) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		#echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listTemporarySave($db, $bb_code, $writer_id, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntPdf($db, $use_tf, $del_tf, $search_field, $search_str);
		
		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, TEMP_NO, BB_CODE, WRITER_ID, TITLE, CONTENTS, REG_DATE, UP_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF
								FROM TBL_BOARD_TEMPORARY WHERE 1 = 1 ";

		
		if ($bb_code <> "") {
			$query .= " AND BB_CODE = '".$bb_code."' ";
		}

		if ($writer_id <> "") {
			$query .= " AND WRITER_ID = '".$writer_id."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND ( (CONTENTS like '%".$search_str."%') or (TITLE like '%".$search_str."%') ) ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}

		$query .= " ORDER BY SEQ_NO desc limit ".$offset.", ".$nRowCount;
		
		//$query .= " ORDER BY REG_DATE desc limit ".$offset.", ".$nRowCount;

		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	


	function listGirinMember($db) {

		$query = "SELECT ADM_NAME
							FROM TBL_ADMIN_INFO 
						 WHERE DEL_TF = 'N' AND GROUP_NO='2'
						 ORDER BY ADM_NAME asc limit 0, 100";

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