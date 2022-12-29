<?
	# =============================================================================
	# File Name    : story.php
	# Modlue       : 
	# Writer       : Park Tae Jun
	# Create Date  : 2022.11.09
	# Modify Date  : 
	#	Copyright : Copyright @MONEUAL Corp. All Rights Reserved.
	# =============================================================================

	 
	function listStory($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT @rownum:= @rownum - 1  as rn, B.STORY_NM, B.REG_DATE, B.UP_DATE, 
						B.CLICK_CNT, B.USE_TF, B.MAIN_TF, B.CONTENTS, B.STORY_NO, B.STORY_TYPE, 
						B.STORY_IMG, B.STORY_REAL_IMG, B.OVERVIEW_IMG, B.OVERVIEW_REAL_IMG,  
						B.DISP_SEQ, B.DEL_TF, B.REG_ADM, B.UP_ADM, B.DEL_ADM, B.DEL_DATE,
						I.ADM_NAME AS REG_NAME, A.ADM_NAME AS UP_NAME
					FROM TBL_STORY B
						LEFT JOIN TBL_ADMIN_INFO I ON B.REG_ADM = I.ADM_NO 
						LEFT JOIN TBL_ADMIN_INFO A ON B.UP_ADM = A.ADM_NO 
					WHERE 1 = 1 AND( B.USE_TF <> 'N' OR B.DEL_TF <> 'N' )";

		if ($story_type <> "") {
			$query .= " AND B.STORY_TYPE = '".$story_type."' ";
		}
		 
		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND B.STORY_NM like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY B.USE_TF DESC, B.REG_DATE DESC,  B.DISP_SEQ asc, B.STORY_NO DESC limit ".$offset.", ".$nRowCount;
		
		// echo " query : " .$query. "<br />";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listUStory($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;
  
		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'U:Story' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'";
		
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC limit 3  ";
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listUStoryAll($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;
  
		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'U:Story' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'";
		 
		// $query .= " ORDER BY F.FILE_NO DESC limit ".$offset.", ".$nRowCount;
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC ";
		
		//echo " listUStory : " .$query. "<br />";  

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listUStoryAdd($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;
  
		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'UStory' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'
					AND F.STORY_NO > '$STORY_NO'
					";
		 
		// $query .= " ORDER BY F.FILE_NO DESC limit ".$offset.", ".$nRowCount;
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC limit 3  ";
		
		echo " listUStory : " .$query. "<br />";  

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listNews($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'News' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'";
		 
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC limit 3  ";
		
		//echo " listNews : " .$query. "<br />";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listNewsAll($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'News' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'";
		 
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC ";
		
		//echo " listNews : " .$query. "<br />";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listNewsletter($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'Newsletter' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'";
		 
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC limit 3  ";
		
		// echo " listNewsletter : " .$query. "<br />";


		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listNewsletterAll($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'Newsletter' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'";
		 
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC ";
		
		//echo " listNewsletter : " .$query. "<br />";


		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listReport($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'Report' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'";
		 
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC limit 3  ";
		
		//echo " listReport : " .$query. "<br />";


		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listReportAll($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "	SELECT 
						F.FILE_NM , S.STORY_NO, S.STORY_NM, S.REG_DATE, S.STORY_IMG, 
						S.STORY_REAL_IMG, LEFT(S.REG_DATE , 10 ) AS REGDATE
					FROM TBL_STORY_FILE F
						LEFT JOIN TBL_STORY S ON S.STORY_NO = F.STORY_NO 
												AND S.STORY_TYPE = 'Report' 
												AND ( S.USE_TF <> 'N' OR S.DEL_TF <> 'N' )
					WHERE F.FILE_NO IN 	(
											SELECT
												MAX( FILE_NO ) 
											FROM TBL_STORY_FILE  
											WHERE DEL_TF = 'N'
											GROUP BY STORY_NO 
											ORDER BY FILE_NO DESC
										)  
					AND F.STORY_NO = S.STORY_NO
					AND F.DEL_TF = 'N'";
		 
		$query .= " ORDER BY S.DISP_SEQ ASC , S.REG_DATE DESC ";
		
		//echo " listReport : " .$query. "<br />";


		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntStory ($db, $site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_STORY WHERE 1 = 1 AND( USE_TF <> 'N' OR DEL_TF <> 'N' )";

		if ($banner_type <> "") {
			$query .= " AND STORY_TYPE = '".$story_type."' ";
		}	 

		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND STORY_NM like '%".$search_str."%' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function insertStory($db, $arr_data) {		
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
		
		$query = "INSERT INTO TBL_STORY (".$set_field.", REG_DATE, UP_DATE) 
					values (".$set_value.", now(), now()); ";
		
		// echo "query : " .$query. "<br />";
		
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {			
			$query = "SELECT last_insert_id()";
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$new_story_no  = $rows[0];
			return $new_story_no;
		}		 
		
	}
	
	function insertStoryFile($db, $arr_data) {

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

		$query = "INSERT INTO TBL_STORY_FILE (".$set_field.") 
					values (".$set_value."); ";
		
		//echo $query."<br>";
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	 

	function selectStory($db, $story_no) {
		 
		$query = "SELECT STORY_NM, REG_DATE, UP_DATE, CLICK_CNT, USE_TF, MAIN_TF,
										CONTENTS, STORY_NO, STORY_TYPE, STORY_IMG, STORY_REAL_IMG, OVERVIEW_IMG, 
										OVERVIEW_REAL_IMG,  DISP_SEQ, DEL_TF, REG_ADM, UP_ADM, DEL_ADM, DEL_DATE
								FROM TBL_STORY WHERE STORY_NO = '$story_no'   ";
		
		  
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function selectStoryPrize($db, $prize_no) {

		$query = "SELECT *
								FROM TBL_PORTFOLIO_PRIZE WHERE 1 = 1 
								 AND DEL_TF = 'N'
								 AND PRIZE_NO = '".$prize_no."' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function updateStory($db, $arr_data, $story_no) {
		
		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_STORY SET ".$set_query_str." ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE STORY_NO = '$story_no' ";
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		} 
		
	}

	function deleteStory($db, $del_adm,  $story_no) {
		$query = "UPDATE TBL_STORY SET
						 USE_TF 			= 'N',
						 DEL_TF				= 'N',
						 DEL_ADM			= '$del_adm',
						 DEL_DATE			= now()					
						WHERE STORY_NO		= '$story_no' "; 
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	
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
	
	function imageDelete($db, $story_no, $img_gubun ) {
		 
		if ( $img_gubun == "1" ) {
			$query = "UPDATE TBL_STORY SET
						 STORY_IMG 				= '',
						 STORY_REAL_IMG			= '',
						 DEL_ADM				= '$del_adm',
						 DEL_DATE				= now()					
					WHERE STORY_NO				= '$story_no' "; 
		} else {
			$query = "UPDATE TBL_STORY SET
						 OVERVIEW_IMG 			= '',
						 OVERVIEW_REAL_IMG		= '',
						 DEL_ADM				= '$del_adm',
						 DEL_DATE				= now()					
					WHERE STORY_NO				= '$story_no' "; 
		}
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}		
	}
	
	function listStoryFile($db, $story_no) {

		$query = "SELECT *
								FROM TBL_STORY_FILE WHERE 1 = 1
								 AND DEL_TF = 'N'
								 AND STORY_NO = '$story_no' ";

		$query .= " ORDER BY FILE_NO ASC ";
		
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
	
	function deleteStoryFile($db, $story_no) {  
				
		$query = "UPDATE TBL_STORY_FILE SET DEL_TF = 'Y', DEL_DATE = now() WHERE STORY_NO = '$story_no'";
		
		// echo "query : " .$query. "<br />";
		
		
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
		
	}
	
	function listStoryAll($db, $story_no, $story_gubun) {	
		if ($story_gubun != "v"){
			$query = "	SELECT 
						S.STORY_NO, S.STORY_TYPE, S.STORY_NM, S.REG_DATE, S.STORY_IMG,
						S.STORY_REAL_IMG, S.CONTENTS, F.FILE_NO, F.FILE_NM , LEFT(S.REG_DATE,10) AS REGDATE
					FROM TBL_STORY S , TBL_STORY_FILE F
					WHERE S.STORY_NO = '$story_no'
					AND S.STORY_TYPE = '$story_gubun' 
					AND F.DEL_TF = 'N' 
					AND S.STORY_NO = F.STORY_NO   ";
		} else {
			$query = "	SELECT 
						S.STORY_NO, S.STORY_TYPE, S.STORY_NM, S.REG_DATE, S.STORY_IMG,
						S.STORY_REAL_IMG, S.CONTENTS, F.FILE_NO, F.FILE_NM , LEFT(S.REG_DATE,10) AS REGDATE
					FROM TBL_STORY S , TBL_STORY_FILE F
					WHERE S.STORY_NO = '$story_no'					
					AND F.DEL_TF = 'N' 
					AND S.STORY_NO = F.STORY_NO   ";
		}
		
		
		
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
	
	function listStoryImgAll($db) {		 
		$query = "	SELECT 
						S.STORY_NO, S.STORY_TYPE, S.STORY_NM, S.REG_DATE, S.CONTENTS,
						S.STORY_IMG, S.STORY_REAL_IMG						
					FROM TBL_STORY S 
					WHERE USE_TF = 'Y' 
					AND MAIN_TF = 'Y' 
					AND DEL_TF = 'N' ";  
					
		$query .= " ORDER BY S.DISP_SEQ ASC, S.REG_DATE DESC ";
		
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
	
	function listNextStory($db, $next_no, $P_STORY_TYPE){
		$query = "SELECT STORY_NM, REG_DATE, UP_DATE, CLICK_CNT, USE_TF, MAIN_TF,
										CONTENTS, STORY_NO, STORY_TYPE, STORY_IMG, STORY_REAL_IMG, OVERVIEW_IMG, 
										OVERVIEW_REAL_IMG,  DISP_SEQ, DEL_TF, REG_ADM, UP_ADM, DEL_ADM, DEL_DATE
								FROM TBL_STORY WHERE STORY_NO > '$next_no'
								AND STORY_TYPE = '$P_STORY_TYPE' 
								ORDER BY STORY_NO DESC LIMIT 1";
		//echo "query : " .$query. "<br />";
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	   
	function listPrevStory($db, $prev_no, $P_STORY_TYPE){
		$query = "SELECT STORY_NM, REG_DATE, UP_DATE, CLICK_CNT, USE_TF, MAIN_TF,
										CONTENTS, STORY_NO, STORY_TYPE, STORY_IMG, STORY_REAL_IMG, OVERVIEW_IMG, 
										OVERVIEW_REAL_IMG,  DISP_SEQ, DEL_TF, REG_ADM, UP_ADM, DEL_ADM, DEL_DATE
								FROM TBL_STORY 
								WHERE STORY_NO < '$prev_no'   
								AND STORY_TYPE = '$P_STORY_TYPE'
								ORDER BY STORY_NO DESC LIMIT 1 ";
		
		//echo "query : " .$query. "<br />";
		
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