<?

	# =============================================================================
	# File Name    : payment.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2016-03-23
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	

	function listCmsPayment ($db, $is_direct, $pay_yyyy, $pay_mm, $pay_day, $pay_type, $area_cd, $party, $pay_state, $pay_reason, $group_cd, $p_seq_no, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, B.SEQ_NO, A.M_ID, A.M_NAME, A.SIDO, B.PAY_YYYY, B.PAY_MM, B.PAY_TYPE, 
										 B.CMS_AMOUNT, 
										 B.RES_CMS_AMOUNT - B.REFUND_AMOUNT AS RES_CMS_AMOUNT, 
										 B.CMS_CHARGE, B.RES_PAY_DATE, B.PAY_RESULT, B.PAY_RESULT_CODE,
										 B.PAY_RESULT_MSG, B.SEND_FLAG, B.SEND_DATE, B.REG_DATE, B.SEND_FILE_NAME, A.M_BIRTH, A.M_HP, B.PAY_REASON,
										 B.REFUND_AMOUNT
								FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B
							 WHERE A.M_NO = B.M_NO AND B.DEL_TF ='N' ";

		if ($p_seq_no <> "") {
			$query .= " AND B.S_SEQ_NO = '".$p_seq_no."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND A.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($is_direct <> "") {
			if ($is_direct == "Y") {
				$query .= " AND B.PAY_TYPE = 'D' ";
			}
			if ($is_direct == "N") {
				$query .= " AND B.PAY_TYPE IN ('C','B','H') ";
			}
		}

		if ($pay_yyyy <> "") {
			$query .= " AND B.PAY_YYYY = '".$pay_yyyy."' ";
		}

		if ($pay_mm <> "") {
			$query .= " AND B.PAY_MM = '".$pay_mm."' ";
		}

		if ($pay_reason <> "") {
			$query .= " AND B.PAY_REASON = '".$pay_reason."' ";
		}

		if ($pay_day <> "") {
			$query .= " AND B.PAY_DAY = '".$pay_day."' ";
		}

		if ($pay_type <> "") {
			$query .= " AND B.PAY_TYPE = '".$pay_type."' ";
		}

		if ($party <> "") {
			$query .= " AND A.M_3 = '".$party."' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%제주%') ";
			} else {
				$query .= " AND A.SIDO like '%".$area_cd."%' ";
			}

			//$query .= " AND A.SIDO like '%".$area_cd."%' ";
		}


		if ($pay_state <> "") {
			if ($pay_state == "Y") { $query .= " AND B.PAY_RESULT = 'Y' "; }
			if ($pay_state == "F") { $query .= " AND B.PAY_RESULT <> 'Y' AND B.PAY_RESULT <> '' "; }
			if ($pay_state == "S") { $query .= " AND B.PAY_RESULT = '' "; }
			if ($pay_state == "R") { $query .= " AND B.PAY_RESULT = 'R' "; }
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY B.SEQ_NO DESC limit ".$offset.", ".$nRowCount;
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}


	function totalCntCmsPayment($db, $is_direct, $pay_yyyy, $pay_mm, $pay_day, $pay_type, $area_cd, $party, $pay_state, $pay_reason, $group_cd, $p_seq_no, $search_field, $search_str) {

		$query = "SELECT COUNT(*) CNT 
								FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B
							 WHERE A.M_NO = B.M_NO AND B.DEL_TF ='N' ";

		if ($p_seq_no <> "") {
			$query .= " AND B.S_SEQ_NO = '".$p_seq_no."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND A.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($is_direct <> "") {
			if ($is_direct == "Y") {
				$query .= " AND B.PAY_TYPE = 'D' ";
			}
			if ($is_direct == "N") {
				$query .= " AND B.PAY_TYPE IN ('C','B','H') ";
			}
		}

		if ($pay_reason <> "") {
			$query .= " AND B.PAY_REASON = '".$pay_reason."' ";
		}

		if ($pay_yyyy <> "") {
			$query .= " AND B.PAY_YYYY = '".$pay_yyyy."' ";
		}

		if ($pay_mm <> "") {
			$query .= " AND B.PAY_MM = '".$pay_mm."' ";
		}

		if ($pay_day <> "") {
			$query .= " AND B.PAY_DAY = '".$pay_day."' ";
		}

		if ($pay_type <> "") {
			$query .= " AND B.PAY_TYPE = '".$pay_type."' ";
		}

		if ($party <> "") {
			$query .= " AND A.M_3 = '".$party."' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%제주%') ";
			} else {
				$query .= " AND A.SIDO like '%".$area_cd."%' ";
			}

			//$query .= " AND A.SIDO like '%".$area_cd."%' ";
		}

		if ($pay_state <> "") {
			if ($pay_state == "Y") { $query .= " AND B.PAY_RESULT = 'Y' "; }
			if ($pay_state == "F") { $query .= " AND B.PAY_RESULT <> 'Y' AND B.PAY_RESULT <> '' "; }
			if ($pay_state == "S") { $query .= " AND B.PAY_RESULT = '' "; }
			if ($pay_state == "R") { $query .= " AND B.PAY_RESULT = 'R' "; }
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

	function getPaymentYear($db) {

		$query = "SELECT distinct PAY_YYYY FROM TBL_MEMBER_PAYMENT ORDER BY PAY_YYYY DESC ";
		
		$result = mysql_query($query,$db);
		$record = array();
		
		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}


	function insertDirectPaymentInfo($db, $m_no, $pay_reason, $sel_year, $sel_month, $cms_amount, $pay_type, $res_cms_amount, $pay_memo, $res_pay_date, $adm_no) {
		
		$res_pay_date	= str_replace("-","",$res_pay_date);
		$cash_recipt	= "Y";
		$pay_result	= "Y";
		$pay_result_code	= "0000";
		$pay_result_msg	= "정상처리";
		$send_flag	= "1";
		$send_date		= date("Y-m-d",strtotime("0 day"));

		$query = "INSERT INTO TBL_MEMBER_PAYMENT (M_NO, PAY_YYYY, PAY_MM, PAY_REASON, CMS_AMOUNT, RES_PAY_DATE, RES_CMS_AMOUNT, CMS_CHARGE, 
														CASH_RECIPT, PAY_TYPE, PAY_RESULT, PAY_RESULT_CODE, PAY_RESULT_MSG, SEND_FLAG, SEND_DATE, REG_DATE, SEND_FILE_NAME, PAY_MEMO, REG_ADM) 
										VALUES ('$m_no', '$sel_year', '$sel_month', '$pay_reason', '$cms_amount', '$res_pay_date', '$res_cms_amount', '0',
														'$cash_recipt', '$pay_type', '$pay_result', '$pay_result_code', '$pay_result_msg', '$send_flag', '$send_date', now(), '', '$pay_memo', '$adm_no') ";
		
		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateRefundPayment($db, $sel_pick_date, $refund_amount, $contents, $pay_result_msg, $seq_no, $adm_no) {
		
		$res_pay_date	= str_replace("-","",$sel_pick_date);

		$query = "UPDATE TBL_MEMBER_PAYMENT SET REFUND_DATE = '$res_pay_date', REFUND_AMOUNT = '$refund_amount', REFUND_MEMO = '$contents', PAY_RESULT = 'R', REFUND_ADM = '$adm_no', PAY_RESULT_MSG='$pay_result_msg' WHERE SEQ_NO = '$seq_no'  ";
		

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateRefundCancelPayment($db, $pay_result_msg, $seq_no, $adm_no) {
		
		$query = "UPDATE TBL_MEMBER_PAYMENT SET REFUND_DATE = '', REFUND_AMOUNT = '0', REFUND_MEMO = '', PAY_RESULT = 'Y', REFUND_ADM = '$adm_no', PAY_RESULT_MSG='$pay_result_msg' WHERE SEQ_NO = '$seq_no'  ";
		

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function updateDirectPaymentInfo($db, $seq_no, $sel_year, $sel_month, $res_cms_amount, $pay_memo, $pay_reason, $res_pay_date, $adm_no) {
		
		$res_pay_date	= str_replace("-","",$res_pay_date);

		$query = "UPDATE TBL_MEMBER_PAYMENT SET PAY_YYYY = '$sel_year', PAY_MM = '$sel_month', RES_CMS_AMOUNT = '$res_cms_amount', PAY_REASON = '$pay_reason', PAY_MEMO = '$pay_memo', RES_PAY_DATE = '$res_pay_date' WHERE SEQ_NO = '$seq_no'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteDirectPaymentInfo($db, $seq_no, $adm_no) {
		
		$query = "UPDATE TBL_MEMBER_PAYMENT SET DEL_ADM = '$adm_no', DEL_TF = 'Y' WHERE SEQ_NO = '$seq_no'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function selectPaymentInfo($db, $seq_no) {

		$query = "SELECT SEQ_NO,M_NO,PAY_YYYY,PAY_MM,PAY_REASON,CMS_AMOUNT,RES_PAY_DATE,RES_CMS_AMOUNT,
										 CMS_CHARGE,RES_PAY_NO,CASH_RECIPT,PAY_TYPE,PAY_RESULT,PAY_RESULT_CODE,
										 PAY_RESULT_MSG,SEND_FLAG,SEND_DATE,REG_DATE,SEND_FILE_NAME,
										 REG_ADM,PAY_MEMO,DEL_ADM,DEL_TF,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_MEMBER_PAYMENT.REG_ADM) AS ADM_NAME,
										 (SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = TBL_MEMBER_PAYMENT.REFUND_ADM) AS REFUND_ADM_NAME,
										 (SELECT M_NAME FROM TBL_MEMBER WHERE M_NO = TBL_MEMBER_PAYMENT.M_NO) AS M_NAME,
										 REFUND_AMOUNT, REFUND_DATE, REFUND_ADM, REFUND_MEMO
								FROM TBL_MEMBER_PAYMENT WHERE SEQ_NO = '$seq_no' ";
		
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

	function listSumCmsPayment ($db, $is_direct, $pay_yyyy, $pay_mm, $pay_day, $pay_type, $area_cd, $party, $pay_state, $group_cd, $p_seq_no, $search_field, $search_str) {

		$query = "SELECT SUM(B.CMS_AMOUNT) AS SUM_CMS_AMOUNT, 
										 SUM(B.RES_CMS_AMOUNT - B.REFUND_AMOUNT) AS SUM_RES_CMS_AMOUNT,
										 SUM(B.REFUND_AMOUNT) AS SUM_REF_CMS_AMOUNT,
										 SUM(B.CMS_CHARGE) AS SUM_CMS_CHARGE
								FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B
							 WHERE A.M_NO = B.M_NO AND B.DEL_TF ='N' ";

		if ($p_seq_no <> "") {
			$query .= " AND B.S_SEQ_NO = '".$p_seq_no."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND A.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($is_direct <> "") {
			if ($is_direct == "Y") {
				$query .= " AND B.PAY_TYPE = 'D' ";
			}
			if ($is_direct == "N") {
				$query .= " AND B.PAY_TYPE IN ('C','B','H') ";
			}
		}

		if ($pay_yyyy <> "") {
			$query .= " AND B.PAY_YYYY = '".$pay_yyyy."' ";
		}

		if ($pay_mm <> "") {
			$query .= " AND B.PAY_MM = '".$pay_mm."' ";
		}

		if ($pay_day <> "") {
			$query .= " AND B.PAY_DAY = '".$pay_day."' ";
		}

		if ($pay_type <> "") {
			$query .= " AND B.PAY_TYPE = '".$pay_type."' ";
		}

		if ($party <> "") {
			$query .= " AND A.M_3 = '".$party."' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%제주%') ";
			} else {
				$query .= " AND A.SIDO like '%".$area_cd."%' ";
			}

			//$query .= " AND A.SIDO like '%".$area_cd."%' ";
		}


		if ($pay_state <> "") {
			if ($pay_state == "Y") { $query .= " AND B.PAY_RESULT = 'Y' "; }
			if ($pay_state == "F") { $query .= " AND B.PAY_RESULT <> 'Y' AND B.PAY_RESULT <> '' "; }
			if ($pay_state == "S") { $query .= " AND B.PAY_RESULT = '' "; }
			if ($pay_state == "R") { $query .= " AND B.PAY_RESULT = 'R' "; }
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
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

	function listSumCmsAmount ($db, $is_direct, $pay_yyyy, $pay_mm, $pay_day, $pay_type, $area_cd, $party, $pay_state, $group_cd, $p_seq_no, $search_field, $search_str) {

		$query = "SELECT SUM(AA.CMS_AMOUNT) AS SUM_CMS_AMOUNT
								FROM (SELECT DISTINCT B.M_NO, B.CMS_AMOUNT, B.PAY_YYYY, B.PAY_MM
								FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B
							 WHERE A.M_NO = B.M_NO AND B.DEL_TF ='N' ";

		if ($p_seq_no <> "") {
			$query .= " AND B.S_SEQ_NO = '".$p_seq_no."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND A.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($is_direct <> "") {
			if ($is_direct == "Y") {
				$query .= " AND B.PAY_TYPE = 'D' ";
			}
			if ($is_direct == "N") {
				$query .= " AND B.PAY_TYPE IN ('C','B','H') ";
			}
		}

		if ($pay_yyyy <> "") {
			$query .= " AND B.PAY_YYYY = '".$pay_yyyy."' ";
		}

		if ($pay_mm <> "") {
			$query .= " AND B.PAY_MM = '".$pay_mm."' ";
		}

		if ($pay_day <> "") {
			$query .= " AND B.PAY_DAY = '".$pay_day."' ";
		}

		if ($pay_type <> "") {
			$query .= " AND B.PAY_TYPE = '".$pay_type."' ";
		}

		if ($party <> "") {
			$query .= " AND A.M_3 = '".$party."' ";
		}

		if ($area_cd <> "") {

			if ($area_cd == "경북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (A.SIDO like '%".$area_cd."%' OR A.SIDO like '%제주%') ";
			} else {
				$query .= " AND A.SIDO like '%".$area_cd."%' ";
			}

			//$query .= " AND A.SIDO like '%".$area_cd."%' ";
		}

		if ($pay_state <> "") {
			if ($pay_state == "Y") { $query .= " AND B.PAY_RESULT = 'Y' "; }
			if ($pay_state == "F") { $query .= " AND B.PAY_RESULT <> 'Y' AND B.PAY_RESULT <> '' "; }
			if ($pay_state == "S") { $query .= " AND B.PAY_RESULT = '' "; }
			if ($pay_state == "R") { $query .= " AND B.PAY_RESULT = 'R' "; }
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ) AA ";

		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}


	function getHolidayList ($db) {
		
		$str_holiday = "";

		$yymmdd = date("Y-m-d",strtotime("0 month"));
		$yymmdd2 = date("Ymd",strtotime("0 month"));

		$query = "SELECT H_DATE, IS_HOLIDAY
								FROM TBL_HOLIDAY 
							 WHERE IS_HOLIDAY  = 'Y' AND H_DATE >= '$yymmdd' 
							UNION
							SELECT CONCAT(left(SEND_FILE_NAME,4), '-',substring(SEND_FILE_NAME,5,2), '-',substring(SEND_FILE_NAME,7,2)) AS H_DATE, 'Y' AS IS_HOLIDAY
								FROM TBL_CMS_FILE
							 WHERE SEND_FILE_NAME >= '".$yymmdd2.".30040111' AND SEND_TYPE = 'P' ";

		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}

		if (sizeof($record) > 0) {
			for ($j = 0 ; $j < sizeof($record); $j++) {

				$H_DATE	= trim($record[$j]["H_DATE"]);
				
				$arr_h_date = explode("-",$H_DATE);
				
				$yy = $arr_h_date[0];
				$mm = (int)$arr_h_date[1];
				$dd = (int)$arr_h_date[2];
				
				if ($str_holiday == "") {
					$str_holiday = "'".$yy."-".$mm."-".$dd."'";
				} else {
					$str_holiday = $str_holiday . ",'".$yy."-".$mm."-".$dd."'";
				}

			}
		}

		return $str_holiday;

	}

	function chk_payment_file($db) {
		
		$query = "SELECT COUNT(*) AS CNT_FILE FROM TBL_CMS_FILE WHERE SEND_TYPE = 'P' AND RES_FLAG = '0' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}


	function totalCntSpeParty($db, $search_field, $search_str) {

		$query = "SELECT COUNT(*) CNT 
								FROM TBL_SPECIALPARTY 
							 WHERE DEL_TF ='N' ";

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listSpeParty ($db, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, TITLE, PAY_DATE, REG_DATE, TEMP01, TEMP02, TEMP03,
										(SELECT COUNT(SEQ_NO) FROM TBL_SPECIALPARTY_MEMBER WHERE P_SEQ_NO = TBL_SPECIALPARTY.SEQ_NO AND USE_TF = 'Y' AND DEL_TF ='N') AS CNT_MEMBER,
										(SELECT SUM(AMOUNT) FROM TBL_SPECIALPARTY_MEMBER WHERE P_SEQ_NO = TBL_SPECIALPARTY.SEQ_NO AND USE_TF = 'Y' AND DEL_TF ='N') AS SUM_AMOUNT
								FROM TBL_SPECIALPARTY
							 WHERE DEL_TF ='N' ";

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY SEQ_NO DESC limit ".$offset.", ".$nRowCount;
		
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

	function insertSpeParty($db, $title, $pay_date, $memo, $adm_no) {
		
		//$pay_date	= str_replace("-","",$pay_date);

		$query = "INSERT INTO TBL_SPECIALPARTY (TITLE, PAY_DATE, MEMO, REG_ADM, REG_DATE) 
										VALUES ('$title', '$pay_date', '$memo', '$adm_no', now()) ";
		
		//echo $query."<br>";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectSpeParty($db, $seq_no) {

		$query = "SELECT SEQ_NO, TITLE, PAY_DATE, MEMO
								FROM TBL_SPECIALPARTY WHERE SEQ_NO = '$seq_no' ";
		
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

	function updateSpeParty($db, $seq_no, $title, $pay_date, $memo, $adm_no) {
		
		//$pay_date	= str_replace("-","",$pay_date);

		$query = "UPDATE TBL_SPECIALPARTY SET TITLE = '$title', PAY_DATE = '$pay_date', MEMO = '$memo' WHERE SEQ_NO = '$seq_no'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteSpeParty($db, $seq_no, $adm_no) {
		
		$query = "UPDATE TBL_SPECIALPARTY SET DEL_ADM = '$adm_no', DEL_TF = 'Y' WHERE SEQ_NO = '$seq_no'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function insertTempSpeMember($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_SPECIALPARTY_MEMBER_TEMP (".$set_field.") 
					values (".$set_value."); ";

	//				echo $query;
		//			die;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listSpeMemberTemp($db, $p_no, $temp_no) {

		$query = "SELECT * FROM TBL_SPECIALPARTY_MEMBER_TEMP WHERE P_SEQ_NO = '$p_no' and TEMP_NO='$temp_no'";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function SpedupGetMemberNo($db, $M_NAME, $M_BIRTH, $M_HP) {

		$query = "SELECT M_NO FROM TBL_MEMBER WHERE M_NAME = '$M_NAME' AND M_BIRTH = '$M_BIRTH' AND M_HP='$M_HP' AND M_LEAVE_DATE = '' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function SpedupCheckMember($db, $M_NAME, $M_BIRTH, $M_HP, $p_no) {
		
		$return_str = "T";

		$query = "SELECT COUNT(*) AS CNT FROM TBL_SPECIALPARTY_MEMBER WHERE P_SEQ_NO='$p_no' and M_NAME = '$M_NAME' AND M_BIRTH = '$M_BIRTH' AND M_HP='$M_HP' AND DEL_TF='N'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		if ($rows[0] == 0) {
			$return_str = "T";
		} else {
			$return_str = "F";
		}

		return $return_str;
	}

	function deleteSpeMemberTemp($db, $temp_no, $seq_no) {

		$query="DELETE FROM TBL_SPECIALPARTY_MEMBER_TEMP WHERE TEMP_NO = '$temp_no' AND SEQ_NO = '$seq_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertTempToRealSpeMember($db, $temp_no, $seq_no, $key, $iv) {

		$query="SELECT SEQ_NO, P_SEQ_NO, M_NAME, M_BIRTH, M_HP, AMOUNT FROM TBL_SPECIALPARTY_MEMBER_TEMP
						 WHERE TEMP_NO = '$temp_no' AND SEQ_NO IN ($seq_no) ";
		

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}

		if (sizeof($record) > 0) {
			for ($j = 0 ; $j < sizeof($record); $j++) {

				$SEQ_NO							= trim($record[$j]["SEQ_NO"]);
				$P_SEQ_NO						= trim($record[$j]["P_SEQ_NO"]);
				$M_NAME							= trim($record[$j]["M_NAME"]);
				$M_BIRTH						= trim($record[$j]["M_BIRTH"]);
				$M_HP								= trim($record[$j]["M_HP"]);
				$AMOUNT							= trim($record[$j]["AMOUNT"]);
				
				$M_NO = SpedupGetMemberNo($db, $M_NAME, $M_BIRTH, $M_HP);

				$result_jumin = SpedupCheckMember($db, $M_NAME, $M_BIRTH, $M_HP, $P_SEQ_NO);
				$result_jumin = SpedupCheckMembertable($db, $M_NAME, $M_BIRTH, $M_HP);

				if ($M_NAME == "") {
					$result_jumin .=  "이름 누락,";
				}

				if ($M_BIRTH == "") {
					$result_jumin .=  "생년월일 누락,";
				}

				if ($M_HP == "") {
					$result_jumin .=  "휴대번호 누락,";
				}

				if ($AMOUNT == "") {
					$result_jumin .=  "특별당비 누락,";
				}

				// 중복 없으면 등록
				if ($result_jumin == "T") {

					//$M_BIRTH	= "19".$M_BIRTH;

					$arr_data = array("M_NO"=>$M_NO,
														"M_NAME"=>$M_NAME,
														"P_SEQ_NO"=>$P_SEQ_NO,
														"M_BIRTH"=>$M_BIRTH,
														"M_HP"=>$M_HP,
														"AMOUNT"=>$AMOUNT,
														"REG_DATE"=>date("Y-m-d H:i:s",strtotime("0 day")));

					$new_mem_no =  insertSpeMember($db, $arr_data);

				}
			}
		}

		if(!$result) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

	function insertSpeMember($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_SPECIALPARTY_MEMBER (".$set_field.") 
					values (".$set_value."); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			$new_mem_no = mysql_insert_id();
			//deleteTemporarySave($db, $b_code, $writer_id);
			return $new_mem_no;
		}
	}

	function deleteTempToRealSpeMember($db, $temp_no, $seq_no) {

		$query=" DELETE FROM TBL_SPECIALPARTY_MEMBER_TEMP WHERE TEMP_NO = '$temp_no' AND SEQ_NO IN ($seq_no) ";
		
		#echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function totalCntSpeMember($db, $p_no, $search_field, $search_str) {

		$query = "SELECT COUNT(*) CNT 
						FROM TBL_MEMBER A, TBL_SPECIALPARTY_MEMBER B 
						WHERE A.M_NAME = B.M_NAME AND A.M_BIRTH=B.M_BIRTH AND A.M_HP=B.M_HP AND B.DEL_TF ='N' AND A.M_LEAVE_DATE=''";

		if ($p_no <> "") {
			$query .= " AND B.P_SEQ_NO  = '".$p_no."' ";
		}

		if ($search_str <> "") {
			$query .= " AND B.".$search_field." like '%".$search_str."%' ";
		}

		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	
	function listSpeMember ($db, $p_no, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		//echo $offset;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, B.SEQ_NO, B.P_SEQ_NO, B.M_NAME, B.M_BIRTH, B.M_HP, B.AMOUNT, A.M_TEL, A.M_ZIP1, A.M_ADDR1, A.M_ADDR2, A.M_3, A.M_4, A.SIDO
							FROM TBL_MEMBER A, TBL_SPECIALPARTY_MEMBER B 
						WHERE A.M_NAME = B.M_NAME AND A.M_BIRTH=B.M_BIRTH AND A.M_HP=B.M_HP AND B.DEL_TF ='N' AND A.M_LEAVE_DATE=''";

		if ($p_no <> "") {
			$query .= " AND B.P_SEQ_NO  = '".$p_no."' ";
		}

		if ($search_str <> "") {
			$query .= " AND B.".$search_field." like '%".$search_str."%' ";
		}

		$query .= " ORDER BY B.SEQ_NO DESC limit ".$offset.", ".$nRowCount;
		
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


	function listSumSpeMemberAmount ($db, $p_no, $search_field, $search_str) {

		$query = "SELECT SUM(B.AMOUNT) AS SUM_AMOUNT 
								FROM TBL_MEMBER A, TBL_SPECIALPARTY_MEMBER B 
						WHERE A.M_NAME = B.M_NAME AND A.M_BIRTH=B.M_BIRTH AND A.M_HP=B.M_HP AND B.DEL_TF ='N' AND A.M_LEAVE_DATE=''";

		if ($p_no <> "") {
			$query .= " AND B.P_SEQ_NO  = '".$p_no."' ";
		}

		if ($search_str <> "") {
			$query .= " AND B.".$search_field." like '%".$search_str."%' ";
		}

		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;

	}

	function SpedupCheckMembertable($db, $M_NAME, $M_BIRTH, $M_HP) {
		
		$return_str = "T";

		$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_NAME = '$M_NAME' AND M_BIRTH = '$M_BIRTH' AND M_HP='$M_HP' AND M_LEAVE_DATE=''";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		if ($rows[0] == 0) {
			$return_str = "F";
		} else {
			$return_str = "T";
		}

		return $return_str;
	}

	function SpedupCheckMemberRegCms($db, $M_NAME, $M_BIRTH, $M_HP) {
		
		$return_str = "T";
		

		$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_NAME = '$M_NAME' AND M_BIRTH = '$M_BIRTH' AND M_HP='$M_HP' AND M_LEAVE_DATE='' AND CMS_FLAG = 'R'  AND M_6 IN ('cms','card') AND SEND_FLAG = '0'";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		if ($rows[0] == 0) {
			$return_str = "F";
		} else {
			$return_str = "T";
		}

		return $return_str;
	}

	function deleteToRealSpeMember($db, $seq_no) {

		$query="DELETE FROM TBL_SPECIALPARTY_MEMBER WHERE SEQ_NO IN ($seq_no) ";
		
	#	echo $query;
		#die;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectSpeMember($db, $seq_no) {

		$query = "SELECT SEQ_NO, M_NAME, M_BIRTH, M_HP, AMOUNT 
								FROM TBL_SPECIALPARTY_MEMBER WHERE SEQ_NO = '$seq_no' ";
		
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

	function updateSpeMember($db, $seq_no, $amount, $s_adm_no) {
		
		$pay_date	= str_replace("-","",$pay_date);

	//	$query = "UPDATE TBL_SPECIALPARTY_MEMBER SET M_NAME = '$m_name', M_BIRTH = '$m_birth', M_HP = '$mtel' , AMOUNT = '$amount' WHERE SEQ_NO = '$seq_no'  ";
	$query = "UPDATE TBL_SPECIALPARTY_MEMBER SET AMOUNT = '$amount' WHERE SEQ_NO = '$seq_no'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
?>