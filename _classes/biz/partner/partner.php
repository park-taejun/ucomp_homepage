<?
	# =============================================================================
	# File Name    : partner.php
	# Modlue       : 
	# Writer       : Park Tae Jun
	# Create Date  : 2022.11.14
	# Modify Date  : 
	#	Copyright : Copyright @MONEUAL Corp. All Rights Reserved.
	# =============================================================================

	 
	function listPartner($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntPartner ($db, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, B.PARTNER_NM, B.REG_DATE, B.UP_DATE, 
						B.USE_TF, B.CONTENTS, B.PARTNER_NO, B.DOWN_IMG, B.DOWN_REAL_IMG, 
						B.UP_IMG, B.UP_REAL_IMG, B.DISP_SEQ, B.DEL_TF, B.REG_ADM, B.UP_ADM, 
						B.DEL_ADM, B.DEL_DATE, I.ADM_NAME AS REG_NAME, A.ADM_NAME AS UP_NAME,
						B.PORTFOLIO_NM
				FROM TBL_PARTNER B
					LEFT JOIN TBL_ADMIN_INFO I ON B.REG_ADM = I.ADM_NO 
					LEFT JOIN TBL_ADMIN_INFO A ON B.UP_ADM = A.ADM_NO 
				WHERE 1 = 1 AND( B.USE_TF <> 'N' OR B.DEL_TF <> 'N' ) ";
		 
		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND B.PARTNER_NM like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY B.USE_TF DESC, B.REG_DATE DESC,  B.DISP_SEQ asc, B.PARTNER_NO DESC limit ".$offset.", ".$nRowCount;
		
		// echo "query : " .$query. "<br />";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntPartner ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_PARTNER WHERE 1 = 1 AND( USE_TF <> 'N' OR DEL_TF <> 'N' )";


		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND PARTNER_NM like '%".$search_str."%' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function insertPartner($db, $arr_data) {		
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
				if ($key == "M_PASSWORD") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_PARTNER (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {			
			$query = "SELECT last_insert_id()";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$new_p_no  = $rows[0];
			return $new_p_no;
		}		 		
	}

	function selectPartner($db, $partner_no) {
		 
		$query = "SELECT PARTNER_NM, REG_DATE, UP_DATE, USE_TF, CONTENTS, PARTNER_NO, DOWN_IMG, 
						DOWN_REAL_IMG, UP_IMG, UP_REAL_IMG, DISP_SEQ, DEL_TF, REG_ADM, UP_ADM, 
						DEL_ADM, DEL_DATE, PORTFOLIO_NM										
				FROM TBL_PARTNER WHERE PARTNER_NO = '$partner_no'   ";		
		   
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;		
	}
	
	function updatePartner($db, $arr_data, $partner_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_PARTNER SET ".$set_query_str." ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE PARTNER_NO = '$partner_no' ";
		
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		} 
	}

	function deletePartner($db, $del_adm,  $partner_no) {
		$query = "UPDATE TBL_PARTNER SET
						 USE_TF 			= 'N',
						 DEL_TF				= 'N',
						 DEL_ADM			= '$del_adm',
						 DEL_DATE			= now()					
						WHERE PARTNER_NO		= '$partner_no' "; 
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	 
	/* 
	function listClickCnt($db, $story_no) {
		$query = "UPDATE TBL_STORY SET CLICK_CNT = CLICK_CNT + 1 WHERE STORY_NO = '$story_no' ";
		
		// echo " query : " .$query. "<br />";
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		} 	 
	}
	*/
	
	function imageDelete($db, $partner_no, $img_gubun ) {
		if ( $img_gubun == "1" ) {
			$query = "UPDATE TBL_PARTNER SET
						 DOWN_IMG 			= '',
						 DOWN_REAL_IMG		= '',
						 DEL_ADM			= '$del_adm',
						 DEL_DATE			= now()					
					WHERE PARTNER_NO		= '$partner_no' "; 
		} else {
			$query = "UPDATE TBL_PARTNER SET
						 UP_IMG 			= '',
						 UP_REAL_IMG		= '',
						 DEL_ADM			= '$del_adm',
						 DEL_DATE			= now()					
					WHERE PARTNER_NO		= '$partner_no' "; 
		} 
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	
	function listAllPartner($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalAllCntPartner ($db, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, PARTNER_NM, REG_DATE, UP_DATE,USE_TF, 
										CONTENTS, PARTNER_NO, DOWN_IMG, DOWN_REAL_IMG, UP_IMG, 
										UP_REAL_IMG,  DISP_SEQ, DEL_TF, REG_ADM, UP_ADM, 
										DEL_ADM, DEL_DATE, PORTFOLIO_NM
								FROM TBL_PARTNER WHERE 1 = 1 AND  USE_TF = 'Y' AND DEL_TF = 'N'  ";
		  
		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";  
		}
		
		$query .= " ORDER BY DISP_SEQ ASC,  REG_DATE DESC  limit ".$offset.", ".$nRowCount;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function totalAllCntPartner ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_PARTNER WHERE 1 = 1 AND ( USE_TF <> 'N' OR DEL_TF <> 'N' )";

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
?>