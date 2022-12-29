<?
	# =============================================================================
	# File Name    : office.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2012.04.27
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_OFFICE
	#=========================================================================================================

	/*
CREATE TABLE IF NOT EXISTS `TBL_OFFICE` (
  `SEQ_NO` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `NAME` varchar(50) NOT NULL DEFAULT '' COMMENT '이름',
  `TYPE` varchar(30) NOT NULL COMMENT '사무실 구분',
  `TEL01` varchar(30) NOT NULL COMMENT '연락처',
  `TEL02` varchar(30) NOT NULL COMMENT '연락처',
  `FAX01` varchar(30) NOT NULL COMMENT '연락처',
  `FAX02` varchar(30) NOT NULL COMMENT '연락처',
  `EMAIL` varchar(150) NOT NULL COMMENT '이메일',
  `POST` varchar(7) NOT NULL COMMENT '우편번호',
  `ADDRESS` varchar(200) NOT NULL COMMENT '주소',
  `STR_LAT` varchar(60) NOT NULL DEFAULT '' COMMENT '경도',
  `STR_LNG` varchar(60) NOT NULL DEFAULT '' COMMENT '위도',
  `EX_INFO01` varchar(200) NOT NULL COMMENT '기타정보',
  `EX_INFO02` varchar(200) NOT NULL COMMENT '기타정보',
  `EX_INFO03` varchar(200) NOT NULL COMMENT '기타정보',
  `USE_TF` char(1) NOT NULL DEFAULT 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
  `DEL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
  `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_CANDIDATE',
  `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
  `UP_ADM` int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_CANDIDATE',
  `UP_DATE` datetime DEFAULT NULL COMMENT '수정일',
  `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_CANDIDATE',
  `DEL_DATE` datetime DEFAULT NULL COMMENT '삭제일',
  PRIMARY KEY (`SEQ_NO`)
)
	*/

	#=========================================================================================================
	# End Table
	#=========================================================================================================



	function listOffice($db, $type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntOffice($db, $type, $use_tf, $del_tf, $search_field, $search_str);
		
		$offset = $nRowCount*($nPage-1);
		
		$logical_num = ($total_cnt - $offset) + 1 ;
		
		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, NAME, TYPE, TEL01, TEL02, FAX01, FAX02,
										 EMAIL, POST, ADDRESS, STR_LAT, STR_LNG, 
										 EX_INFO01, EX_INFO02, EX_INFO03, DIS_SEQ, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_OFFICE WHERE 1=1 ";
		
		if ($type <>""){
			$query .= " AND TYPE = '".$type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (NAME like '%".$search_str."%' OR ADDRESS like '%".$search_str."%') ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		

		$query .= " ORDER BY DIS_SEQ DESC limit ".$offset.", ".$nRowCount;
		
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


	function totalCntOffice($db, $type, $use_tf, $del_tf, $search_field, $search_str){
	
		//echo $type;

		$query ="SELECT COUNT(*) CNT FROM TBL_OFFICE WHERE 1=1 ";
	
		if ($type <>""){
			$query .= " AND TYPE = '".$type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (NAME like '%".$search_str."%' OR ADDRESS like '%".$search_str."%') ";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		//echo $query."<br>";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertOffice($db, $name, $type, $tel01, $tel02, $fax01, $fax02, $email, $post, $address, $str_lat, $str_lng, $ex_info01, $ex_info02, $ex_info03, $use_tf, $reg_adm) {
		
		$query ="SELECT IFNULL(MAX(DIS_SEQ),0) AS MAX_SEQ_NO FROM TBL_OFFICE ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$dis_seq_no  = ($rows[0] + 1);
		
		$query="INSERT INTO TBL_OFFICE (NAME, TYPE, TEL01, TEL02, FAX01, FAX02, EMAIL, POST, ADDRESS, STR_LAT, STR_LNG, EX_INFO01, EX_INFO02, EX_INFO03, DIS_SEQ, USE_TF, REG_ADM, REG_DATE) 
		values ('$name', '$type', '$tel01', '$tel02', '$fax01', '$fax02', '$email', '$post', '$address', '$str_lat', '$str_lng', '$ex_info01', '$ex_info02', '$ex_info03', '$dis_seq_no', '$use_tf', '$reg_adm', now()); ";

		//echo $query;
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectOffice($db, $seq_no) {

		$query = "SELECT SEQ_NO, NAME, TYPE, TEL01, TEL02, FAX01, FAX02, EMAIL, 
										 POST, ADDRESS, STR_LAT, STR_LNG,
										 EX_INFO01, EX_INFO02, EX_INFO03, DIS_SEQ, USE_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_OFFICE WHERE SEQ_NO = '$seq_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function updateOffice($db, $name, $type, $tel01, $tel02, $fax01, $fax02, $email, $post, $address, $str_lat, $str_lng, $ex_info01, $ex_info02, $ex_info03, $use_tf, $up_adm, $seq_no) {

		$query="UPDATE TBL_OFFICE SET 
													NAME					= '$name',
													TYPE					= '$type',
													TEL01					= '$tel01',
													TEL02					= '$tel02',
													FAX01					= '$fax01',
													FAX02					= '$fax02',
													EMAIL					= '$email',
													POST					= '$post',
													ADDRESS				= '$address',
													STR_LAT				= '$str_lat',
													STR_LNG				= '$str_lng',
													EX_INFO01			= '$ex_info01',
													EX_INFO02			= '$ex_info02',
													EX_INFO03			= '$ex_info03',
													USE_TF				= '$use_tf',
													UP_ADM				= '$up_adm', 
													UP_DATE				= now() 
													WHERE SEQ_NO= '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOfficeUseTF($db, $use_tf, $up_adm, $seq_no) {
		
		$query="UPDATE TBL_OFFICE SET 
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

	function deleteOffice($db, $del_adm, $seq_no) {

		$query="UPDATE TBL_OFFICE SET DEL_ADM = '$del_adm', DEL_TF= 'Y', DEL_DATE = now() WHERE SEQ_NO= '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateOrderOffice($db, $disp_seq_no, $type, $seq_no) {

		$query="UPDATE TBL_OFFICE SET
										DIS_SEQ	=	'$disp_seq_no'
							WHERE SEQ_NO	= '$seq_no' AND TYPE = '$type' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function updateOrderOfficeSeq($db, $type, $use_tf, $del_tf, $order_type, $seq_no) {

		// 현재 SEQ 를 구한다..
		$query = "SELECT DIS_SEQ
								FROM TBL_OFFICE WHERE 1 = 1 ";
		
		if ($goods_no <> "") {
			$query .= " AND SEQ_NO = '".$seq_no."' ";
		}
		
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$disp_seq  = $rows[0];
		
		//echo $disp_seq;

		$query = "SELECT SEQ_NO, DIS_SEQ
								FROM TBL_OFFICE WHERE 1 = 1 ";

		if ($type <> "") {
			$query .= " AND TYPE = '".$type."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($order_type == "up") {
			$query .= " AND DIS_SEQ > '$disp_seq' ORDER BY DIS_SEQ ASC limit 1";
		} else {
			$query .= " AND DIS_SEQ < '$disp_seq' ORDER BY DIS_SEQ DESC limit 1";
		}
		
		echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$temp_seq_no		= $rows[0];
		$temp_disp_seq  = $rows[1];

		if ($temp_seq_no) {
			
			$query = "UPDATE TBL_OFFICE SET DIS_SEQ = '$temp_disp_seq' WHERE SEQ_NO = '$seq_no' ";
			mysql_query($query,$db);

			//echo $query."<br />";

			$query = "UPDATE TBL_OFFICE SET DIS_SEQ = '$disp_seq' WHERE SEQ_NO = '$temp_seq_no' ";
			mysql_query($query,$db);
			
			//echo $query."<br />";
			/*
			echo $seq_no."<br>";
			echo $disp_seq."<br>";

			echo $temp_seq_no."<br>";
			echo $temp_disp_seq."<br>";
			*/

		}
	}
?>