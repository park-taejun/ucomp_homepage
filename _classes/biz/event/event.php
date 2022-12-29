<?
	# =============================================================================
	# File Name    : event.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2014.07.03
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_EVENT
	#=========================================================================================================

	/*
CREATE TABLE IF NOT EXISTS TBL_EVENT (
  SEQ_NO int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  EV_TYPE varchar(20) NOT NULL,
  TITLE varchar(50) NOT NULL DEFAULT '' COMMENT '이벤트명',
  EV_START varchar(12) NOT NULL COMMENT '시작일',
  EV_START_TIME varchar(12) NOT NULL COMMENT '시작시간',
  EV_END varchar(12) NOT NULL COMMENT '종료일',
  EV_END_TIME varchar(12) NOT NULL COMMENT '종료시간',
  EV_QUERY text NOT NULL COMMENT '대상자 쿼리',
  ALL_FLAG varchar(1) NOT NULL COMMENT '전체 여부',
  USE_TF char(1) NOT NULL DEFAULT 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
  DEL_TF char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  REG_ADM int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_CANDIDATE',
  REG_DATE datetime DEFAULT NULL COMMENT '등록일',
  UP_ADM int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_CANDIDATE',
  UP_DATE datetime DEFAULT NULL COMMENT '수정일',
  DEL_ADM int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_CANDIDATE',
  DEL_DATE datetime DEFAULT NULL COMMENT '삭제일',
  PRIMARY KEY (SEQ_NO)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================



	function listEvent($db, $ev_type, $ev_start, $ev_end, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {
		
		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, EV_TYPE, TITLE, EV_START, EV_START_TIME, EV_END, EV_END_TIME, EV_QUERY, ALL_FLAG, FILE_NM,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_EVENT WHERE 1=1 ";
		
		if ($ev_type <>""){
			$query .= " AND EV_TYPE = '".$ev_type."' ";
		}

		if ($ev_start <> "") {
			$query .= " AND EV_START >= '".$ev_start."' ";
		}

		if ($ev_end <> "") {
			$query .= " AND EV_END <= '".$ev_end." 23:59:59' ";
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
		
		$query .= " ORDER BY SEQ_NO desc limit ".$offset.", ".$nRowCount;
		
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


	function totalCntEvent($db, $ev_type, $ev_start, $ev_end, $use_tf, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(*) CNT FROM TBL_EVENT WHERE 1=1 ";
	
		if ($ev_type <>""){
			$query .= " AND EV_TYPE = '".$ev_type."' ";
		}

		if ($ev_start <> "") {
			$query .= " AND EV_START >= '".$ev_start."' ";
		}

		if ($ev_end <> "") {
			$query .= " AND EV_END <= '".$ev_end." 23:59:59' ";
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
		
	//	echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertEvent($db, $ev_type, $title, $ev_start, $ev_start_time, $ev_end, $ev_end_time, $ev_query, $all_flag, $file_nm, $use_tf, $reg_adm) {
		
		$query="INSERT INTO TBL_EVENT (EV_TYPE, TITLE, EV_START, EV_START_TIME, EV_END, EV_END_TIME, EV_QUERY, ALL_FLAG, FILE_NM, USE_TF, REG_ADM, REG_DATE) 
		values ('$ev_type', '$title', '$ev_start', '$ev_start_time', '$ev_end', '$ev_end_time', '$ev_query', '$all_flag', '$file_nm', '$use_tf', '$reg_adm', now()); ";

		//echo $query;
		//die;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {

			$query = "select last_insert_id() as new_seq_no";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$record  = $rows[0];

			return $record;
		}
	}

	function selectEvent($db, $seq_no) {

		$query = "SELECT SEQ_NO, EV_TYPE, TITLE, EV_START, EV_START_TIME, EV_END, EV_END_TIME, EV_QUERY, ALL_FLAG, FILE_NM, CONDITION_STR,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE FROM TBL_EVENT WHERE SEQ_NO = '$seq_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function updateEvent($db, $ev_type, $title, $ev_start, $ev_start_time, $ev_end, $ev_end_time, $ev_query, $all_flag, $file_nm, $use_tf, $up_adm, $seq_no) {

		$query="UPDATE TBL_EVENT SET 
													EV_TYPE					= '$ev_type',
													TITLE						= '$title',
													EV_START				= '$ev_start',
													EV_START_TIME		= '$ev_start_time',
													EV_END					= '$ev_end',
													EV_END_TIME			= '$ev_end_time',
													EV_QUERY				= '$ev_query',
													ALL_FLAG				= '$all_flag',
													FILE_NM					= '$file_nm',
													USE_TF					= '$use_tf',
													UP_ADM					= '$up_adm', 
													UP_DATE					= now() 
													WHERE SEQ_NO		= '$seq_no' ";
		
		//echo $TBL_EVENT."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateEventUseTF($db, $use_tf, $up_adm, $seq_no) {
		
		$query="UPDATE TBL_EVENT SET 
							USE_TF			= '$use_tf',
							UP_ADM			= '$up_adm',
							UP_DATE			= now()
				 WHERE SEQ_NO= '$seq_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteEvent($db, $del_adm, $seq_no) {

		$query="DELETE FROM TBL_EVENT_EX WHERE SEQ_NO= '$seq_no' ";
		mysql_query($query,$db);

		$query="DELETE FROM TBL_EVENT_MEM WHERE SEQ_NO= '$seq_no' ";
		mysql_query($query,$db);

		$query="DELETE FROM TBL_EVENT_ST WHERE SEQ_NO= '$seq_no' ";
		mysql_query($query,$db);

		$query="UPDATE TBL_EVENT SET DEL_ADM = '$del_adm', DEL_TF= 'Y', DEL_DATE = now() WHERE SEQ_NO= '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function insertEventEx($db, $ex_no, $seq_no, $ex) {
		
		$query="INSERT INTO TBL_EVENT_EX (EX_NO, SEQ_NO, EX) 
		values ('$ex_no', '$seq_no', '$ex'); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectEventEx($db, $seq_no) {
		
		$query="SELECT EX_NO, SEQ_NO, EX FROM TBL_EVENT_EX WHERE SEQ_NO = '$seq_no' ORDER BY EX_NO ASC ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function getEventEx($db, $seq_no, $ex_no) {
		
		$query="SELECT EX FROM TBL_EVENT_EX WHERE SEQ_NO = '$seq_no' AND EX_NO = '$ex_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function selectEventExSt($db, $seq_no, $mem_id) {

		$query="SELECT A.EX_NO, A.EX, A.SEQ_NO, B.EX_NO AS CHK_EX_NO
							FROM TBL_EVENT_EX A left outer join TBL_EVENT_ST B on A.SEQ_NO = B.SEQ_NO
						 WHERE A.SEQ_NO = '$seq_no'
							 AND B.MEM_ID = '$mem_id'
						 ORDER BY EX_NO ASC ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	
	}

	function deleteEventEx($db, $seq_no) {
		
		$query="DELETE FROM TBL_EVENT_EX WHERE SEQ_NO= '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertEventSt($db, $ex_no, $seq_no, $mem_id) {
		
		$query="INSERT INTO TBL_EVENT_ST (EX_NO, SEQ_NO, MEM_ID) 
		values ('$ex_no', '$seq_no', '$mem_id'); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function insertEventMem($db, $seq_no, $mem_id, $mem_nm, $hp) {
		
		$query="INSERT INTO TBL_EVENT_MEM (SEQ_NO, MEM_ID, MEM_NM, HP) 
		values ('$seq_no', '$mem_id', '$mem_nm', '$hp'); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteEventMem($db, $seq_no) {
		
		$query="DELETE FROM TBL_EVENT_MEM WHERE SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listEventMem($db, $seq_no, $sel_type) {
		
		if ($sel_type == "db") {

			$query = "SELECT A.M_ID, A.M_NAME, A.M_NICK, A.M_SEX, A.M_DATETIME,  
										 A.M_HP
									FROM TBL_EVENT_MEM B, TBL_MEMBER A 
								 WHERE A.M_ID = B.MEM_ID
									 AND B.SEQ_NO = '$seq_no' 
								 ORDER BY A.M_NAME ASC ";

		} else {

			$query = "SELECT MEM_ID AS M_ID, MEM_NM AS M_NAME, HP AS M_HP, now() AS M_DATETIME
									FROM TBL_EVENT_MEM
								 WHERE SEQ_NO = '$seq_no' 
								 ORDER BY MEM_NM ASC ";
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


	function updateEventMemChk($db, $seq_no, $mem_id, $chk_tf) {
		
		$query="UPDATE TBL_EVENT_MEM SET CHK_TF = '$chk_tf' WHERE SEQ_NO ='seq_no' AND MEM_ID = '$mem_id' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listSidoAreaComm($db, $area) {
			
		if ($area) {

			$query = "SELECT CONCAT(COMM_SEQ01,COMM_SEQ02,COMM_SEQ03) as SEQ, COMM_CD, COMM_NAME, AREA_CODE
									FROM CTBL_COMMUNITY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND COMM_CD like '".$area."%'
									 AND COMM_CD <> '".$area."' 
								 ORDER BY SEQ ASC ";

		} else {

			$query = "SELECT CONCAT(COMM_SEQ01,COMM_SEQ02,COMM_SEQ03) as SEQ, COMM_CD, COMM_NAME, AREA_CODE
									FROM CTBL_COMMUNITY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND LENGTH(COMM_CD) = '2' 
								 ORDER BY SEQ ASC ";

		}
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function updateEventCondition($db, $condition, $seq_no) {
		
		$query="UPDATE TBL_EVENT SET 
							CONDITION_STR	= '$condition'
				 WHERE SEQ_NO= '$seq_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertEventTempFile($db, $file_nm, $mem_nm, $mem_hp) {
		
		$query="INSERT INTO TBL_EVENT_TEMP_FILE (FILE_NM, MEM_NM, HP) 
		values ('$file_nm', '$mem_nm', '$mem_hp'); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getEventMember($db, $seq_no, $sel_type, $query_str_all, $file_nm, $mode) {

		if ($sel_type == "db") {

			$arr_query_str = explode("#",$query_str_all);

			//echo $arr_query_str[0]."<br>";
			//echo "chk_all_age".$arr_query_str[1]."<br>";

			$arr_chk_all_age = explode("|",$arr_query_str[1]);
			
			// 연령별 구분
			$query_ages = "";

			if ($arr_chk_all_age[1] != "all") {

				$arr_age = explode("|",$arr_query_str[2]);
				
				if ($arr_age[1]) {

					$arr_ages = explode("%",$arr_age[1]);
					
					for ($h = 0; $h < sizeof($arr_ages) ; $h++) {
					
						if ($query_ages == "") {
							$query_ages = "AND (AA.AGE LIKE '".left($arr_ages[$h],1)."%' ";
						} else {
							$query_ages .= " OR AA.AGE LIKE '".left($arr_ages[$h],1)."%'";
						}
					}
					
					$query_ages .= " )";
					
				}
			}

			// 성별
			$query_sex = "";
			
			$arr_chk_all_sex = explode("|",$arr_query_str[3]);

			if ($arr_chk_all_sex[1] != "all") {
				$query_sex = " AND M_SEX = '".$arr_chk_all_sex[1]."' ";
			}
			
			$arr_chk_all_level = explode("|",$arr_query_str[4]);

			$query_level = "";

			if ($arr_chk_all_level[1] != "all") {

				$arr_level = explode("|",$arr_query_str[5]);
				
				if ($arr_level[1]) {

					$arr_level = explode("%",$arr_level[1]);
					
					for ($h = 0; $h < sizeof($arr_level) ; $h++) {
					
						if ($query_level == "") {
							$query_level = "AND (AA.M_LEVEL = '".trim($arr_level[$h])."' ";
						} else {
							$query_level .= " OR AA.M_LEVEL = '".trim($arr_level[$h])."'";
						}
					}
					
					$query_level .= " )";
					
				}
			}
			

			$arr_search_field = explode("|",$arr_query_str[6]);
			$search_field = $arr_search_field[1];

			$arr_search_str = explode("|",$arr_query_str[7]);
			$search_str = $arr_search_str[1];

			//echo "query_up_date-> ".$query_up_date."<br>";

				$query = "SELECT AA.M_ID, AA.M_NAME, AA.M_NICK, AA.M_SEX, AA.M_BIRTH, AA.M_LEVEL,
											 AA.M_HP, AA.M_DATETIME, AA.AGE 
									FROM (SELECT DISTINCT M_BIRTH, M_SEX, M_ID, M_NAME, M_NICK, M_HP, M_DATETIME, M_LEVEL,
															 CASE WHEN DATE_FORMAT(NOW() ,'%m%e') >= DATE_FORMAT(M_BIRTH,'%m%e')
															 THEN YEAR(NOW()) - YEAR(M_BIRTH) 
															 ELSE YEAR(NOW()) - YEAR(M_BIRTH) - 1 END AS AGE
													FROM TBL_MEMBER
												 WHERE M_LEAVE_DATE = '' 
													 AND M_HP <> '' ";

			$query .= $query_sex;

			if ($search_str <> "") {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
			
			if ($query_ages) {
				$query .= " AND M_BIRTH <> '' ";
			}
			
			$query .= " ) AA WHERE 1 = 1  ";

			$query .= $query_ages;
			$query .= $query_level;

			$query .= "ORDER BY AA.M_NAME";
			
			//echo "Qry-> ".$query."<br>";


		} else {

			$query = "SELECT MEM_NM AS M_NAME, HP AS M_HP, now() AS M_DATETIME
									FROM TBL_EVENT_TEMP_FILE
								 WHERE FILE_NM = '$file_nm' ";

		}

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}


	function listEvent4App($db, $mem_id, $nPage, $nRowCount, $total_cnt) {
		
		if($total_cnt == "")$total_cnt = totalCntEvent4App($db, $mem_id);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, AA.SEQ_NO, AA.EV_TYPE, AA.TITLE, AA.ALL_FLAG, AA.CHK_CNT, AA.EV_START, AA.EV_END, AA.FILE_NM
								FROM (SELECT A.SEQ_NO, A.EV_TYPE, A.TITLE, ALL_FLAG,
														 CASE WHEN EV_START = '' THEN '2000-01-01 00:00' ELSE concat(EV_START,' ',EV_START_TIME,':00') END AS EV_START, 
														 CASE WHEN EV_END = '' THEN '2100-01-01 00:00' ELSE concat(EV_END,' ',EV_END_TIME,':00') END AS EV_END, A.FILE_NM,
														(SELECT COUNT(*) FROM TBL_EVENT_ST C WHERE C.SEQ_NO = A.SEQ_NO AND MEM_ID = '$mem_id') AS CHK_CNT
												FROM TBL_EVENT A, TBL_EVENT_MEM B
											 WHERE A.SEQ_NO = B.SEQ_NO
												 AND MEM_ID = '$mem_id'
												 AND A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND A.ALL_FLAG = 'N'
												 AND A.EV_TYPE = 'EV01'
											UNION
												SELECT A.SEQ_NO, A.EV_TYPE, A.TITLE, ALL_FLAG,
														 CASE WHEN EV_START = '' THEN '2000-01-01 00:00' ELSE concat(EV_START,' ',EV_START_TIME,':00') END AS EV_START, 
														 CASE WHEN EV_END = '' THEN '2100-01-01 00:00' ELSE concat(EV_END,' ',EV_END_TIME,':00') END AS EV_END, A.FILE_NM,
														(SELECT COUNT(*) FROM TBL_EVENT_ST C WHERE C.SEQ_NO = A.SEQ_NO) AS CHK_CNT
												FROM TBL_EVENT A
											 WHERE A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND A.ALL_FLAG = 'Y'
												 AND A.EV_TYPE = 'EV01'
											) AA
								WHERE AA.EV_START <= now()
									AND AA.EV_END > now()
								ORDER BY AA.CHK_CNT ASC, AA.SEQ_NO DESC limit ".$offset.", ".$nRowCount;
		
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


	function totalCntEvent4App($db, $mem_id){

		$query ="SELECT COUNT(*)
								FROM (SELECT A.SEQ_NO, A.EV_TYPE, A.TITLE, ALL_FLAG,
														 CASE WHEN EV_START = '' THEN '2000-01-01 00:00' ELSE concat(EV_START,' ',EV_START_TIME,':00') END AS EV_START, 
														 CASE WHEN EV_END = '' THEN '2100-01-01 00:00' ELSE concat(EV_END,' ',EV_END_TIME,':00') END AS EV_END,
														(SELECT COUNT(*) FROM TBL_EVENT_ST C WHERE C.SEQ_NO = A.SEQ_NO) AS CHK_CNT
												FROM TBL_EVENT A, TBL_EVENT_MEM B
											 WHERE A.SEQ_NO = B.SEQ_NO
												 AND MEM_ID = '$mem_id'
												 AND A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND A.ALL_FLAG = 'N'
												 AND A.EV_TYPE = 'EV01'
											UNION
												SELECT A.SEQ_NO, A.EV_TYPE, A.TITLE, ALL_FLAG,
														 CASE WHEN EV_START = '' THEN '2000-01-01 00:00' ELSE concat(EV_START,' ',EV_START_TIME,':00') END AS EV_START, 
														 CASE WHEN EV_END = '' THEN '2100-01-01 00:00' ELSE concat(EV_END,' ',EV_END_TIME,':00') END AS EV_END,
														(SELECT COUNT(*) FROM TBL_EVENT_ST C WHERE C.SEQ_NO = A.SEQ_NO) AS CHK_CNT
												FROM TBL_EVENT A
											 WHERE A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND A.ALL_FLAG = 'Y'
												 AND A.EV_TYPE = 'EV01'
											) AA
								WHERE AA.EV_START <= now()
									AND AA.EV_END > now()  ";
		
		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listEventNotice4App($db) {
		
		$query = "SELECT A.SEQ_NO, A.EV_TYPE, A.TITLE, A.EV_QUERY, A.FILE_NM, B.EX, A.EV_START, A.EV_START_TIME
								FROM TBL_EVENT A, TBL_EVENT_EX B
							 WHERE A.SEQ_NO = B.SEQ_NO
                 AND A.USE_TF ='Y'
								 AND A.DEL_TF = 'N'
								 AND A.EV_TYPE = 'EV02'
							 ORDER BY A.SEQ_NO DESC";
		
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

	function listEventNotice24App($db, $mem_id) {
		
		$query = "SELECT AA.SEQ_NO, AA.EV_TYPE, AA.TITLE, AA.ALL_FLAG, AA.FILE_NM, AA.EV_START, AA.EV_END, AA.EX
								FROM (SELECT A.SEQ_NO, A.EV_TYPE, A.TITLE, ALL_FLAG, A.FILE_NM,
														 CASE WHEN EV_START = '' THEN '2000-01-01 00:00' ELSE concat(EV_START,' ',EV_START_TIME,':00') END AS EV_START, 
														 CASE WHEN EV_END = '' THEN '2100-01-01 00:00' ELSE concat(EV_END,' ',EV_END_TIME,':00') END AS EV_END, C.EX
												FROM TBL_EVENT A, TBL_EVENT_MEM B, TBL_EVENT_EX C
											 WHERE A.SEQ_NO = B.SEQ_NO
												 AND A.SEQ_NO = C.SEQ_NO
												 AND MEM_ID = '$mem_id'
												 AND A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND A.ALL_FLAG = 'N'
												 AND A.EV_TYPE = 'EV03'
											UNION
												SELECT A.SEQ_NO, A.EV_TYPE, A.TITLE, ALL_FLAG, FILE_NM,
														 CASE WHEN EV_START = '' THEN '2000-01-01 00:00' ELSE concat(EV_START,' ',EV_START_TIME,':00') END AS EV_START, 
														 CASE WHEN EV_END = '' THEN '2100-01-01 00:00' ELSE concat(EV_END,' ',EV_END_TIME,':00') END AS EV_END, C.EX
												FROM TBL_EVENT A, TBL_EVENT_EX C
											 WHERE A.SEQ_NO = C.SEQ_NO
												 AND A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND A.ALL_FLAG = 'Y'
												 AND A.EV_TYPE = 'EV03'
											) AA
								WHERE AA.EV_START <= now()
									AND AA.EV_END > now()
								ORDER BY AA.SEQ_NO DESC ";
		
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



	function chkEventNotice4App($db, $sub_query, $mem_id) {
		
		$query = "SELECT COUNT(MEM_ID) AS CNT FROM TBL_MEMBER WHERE MEM_ID IN (".$sub_query.") AND MEM_ID = '$mem_id' ";
		
		//echo "--> ".$query."<br>";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function getAllEventMem($db, $seq_no, $all_flag) {
		
		if ($all_flag == "Y") {

			$query = "SELECT COUNT(distinct (M_ID)) AS CNT
									FROM TBL_MEMBER
								 WHERE M_SMS = '1'
									 AND M_LEAVE_DATE = ''
									 AND M_HP <> '' ";

		} else {

			$query = "SELECT COUNT(distinct (MEM_NM)) AS CNT
									FROM TBL_EVENT_MEM
								 WHERE SEQ_NO = '$seq_no' ";

		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function getEventChkMem($db, $ex, $start_date, $end_date) {

		$query = "SELECT COUNT(SEQ_NO) AS CNT
									FROM TBL_MOSMS
								 WHERE INSERT_TIME >= '$start_date'
									 AND INSERT_TIME < '".$end_date." 23:59:59'
									 AND TXT LIKE '%".$ex."%' ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function getEventChkMemList($db, $ex, $start_date, $end_date) {

		$query = "SELECT distinct OAADDR, INSERT_TIME, TXT, TELECOM
									FROM TBL_MOSMS
								 WHERE INSERT_TIME >= '$start_date'
									 AND INSERT_TIME < '".$end_date." 23:59:59'
									 AND TXT LIKE '%".$ex."%' ";
		
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

	function getPushEventMemList($db, $seq_no, $all_flag) {
		
		if ($all_flag == "Y") {

			$query = "SELECT A.MEM_ID, B.pid, B.devicename, B.set_goupp
									FROM TBL_MEMBER A, apns_devices B
								 WHERE A.MEM_ID = B.clientid
									 AND B.status = 'active'
									 AND A.USE_TF = 'Y' 
									 AND A.DEL_TF = 'N' ";

		} else {

			$query = "SELECT A.MEM_ID, B.pid, B.devicename, B.set_goupp
									FROM TBL_EVENT_MEM A, apns_devices B
								 WHERE A.MEM_ID = B.clientid
									 AND B.status = 'active'
									 AND A.SEQ_NO = '$seq_no' ";

		}

		$result = mysql_query($query,$db);
		$record = array();
		
		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}


	function getPushBatchMemList($db, $sub_query) {
		

		$query = "SELECT A.MEM_ID, B.pid, B.devicename, B.set_goupp
								FROM TBL_MEMBER A, apns_devices B 
							 WHERE A.MEM_ID = B.clientid
								 AND B.status = 'active'
								 AND A.USE_TF = 'Y' 
								 AND A.DEL_TF = 'N'
								 AND A.MEM_ID IN (".$sub_query.") ";
		
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

	function insertEventTempToReal($db, $seq_no, $file_nm) {
		
		$query = "INSERT INTO TBL_EVENT_MEM (SEQ_NO, MEM_ID, MEM_NM) SELECT ".$seq_no.", MEM_ID, MEM_NM FROM TBL_EVENT_TEMP_FILE WHERE FILE_NM = '$file_nm' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function sendSms($db, $seq_no, $all_flag) {
		
		if ($all_flag == "Y") {

			$query = "SELECT M_NAME AS MEM_NM, M_HP AS HP
									FROM TBL_MEMBER
								 WHERE M_HP <> ''
								   AND M_LEAVE_DATE = '' 
									 AND M_SMS = '1' ";
		} else {

			$query = "SELECT MEM_NM, HP
									FROM TBL_EVENT_MEM
								 WHERE SEQ_NO = '$seq_no' ";

		}

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

?>