<?
	# =============================================================================
	# File Name    : mosms.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2009.08.16
	# Modify Date  : 
	#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
	# =============================================================================

	function getTableList($db) {

		$query = "show tables where Tables_in_krwu like 'mo_queue_%'";
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function insertMoTableToDb($db) {
		
		//
		$query = "SELECT MO_TABLE, MSEQ, INSERT_TIME FROM TBL_MOSMS ORDER BY MO_TABLE DESC, MSEQ DESC LIMIT 0,1";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$MO_TABLE			= $rows[0];
		$MSEQ					= $rows[1];
		$INSERT_TIME	= $rows[2];
		
		if ($MSEQ = "") $MSEQ = 0;

		$arr_rs = getTableList($db);
		
		$str_query = "SELECT * FROM (";
		
		$i_CNt = 0;

		if (sizeof($arr_rs) > 0) {
			for ($j=0;$j<sizeof($arr_rs);$j++) {
				
				$rs_table_nm = trim($arr_rs[$j]['Tables_in_krwu']);

				if ($rs_table_nm >= $MO_TABLE) {
					if ($i_CNt == 0) {
						$str_query .= "select mseq, type, oaaddr, daaddr, callback, stat, insert_time, subject, text, filecnt, 
																	fileloc1, fileloc2, fileloc3, fileloc4, fileloc5, telecom, '".$rs_table_nm."' AS MO_TABLE from ".$rs_table_nm;
						$i_CNt = 1;
					} else {
						$str_query .= " UNION ";
						$str_query .= "select mseq, type, oaaddr, daaddr, callback, stat, insert_time, subject, text, 
																	filecnt, fileloc1, fileloc2, fileloc3, fileloc4, fileloc5, telecom, '".$rs_table_nm."' AS MO_TABLE from ".$rs_table_nm;
					}
				}
				
			}
		}
		
		$str_query .= ") A WHERE A.MO_TABLE >= '$MO_TABLE' AND A.insert_time > '$INSERT_TIME' ORDER BY A.insert_time asc";
		
		//echo "<br>".$str_query."<br>";

		$result = mysql_query($str_query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$arr_mosms[$i] = sql_result_array($result,$i);
			}
		}
		
		if (sizeof($arr_mosms) > 0) {

			for ($h=0; $h < sizeof($arr_mosms); $h++) {
				$mseq					= trim($arr_mosms[$h]['mseq']);
				$type					= trim($arr_mosms[$h]['type']);
				$oaaddr				= trim($arr_mosms[$h]['oaaddr']);
				$daaddr				= trim($arr_mosms[$h]['daaddr']);
				$callback			= trim($arr_mosms[$h]['callback']);
				$stat					= trim($arr_mosms[$h]['stat']);
				$insert_time	= trim($arr_mosms[$h]['insert_time']);
				$subject			= trim($arr_mosms[$h]['subject']);
				$txt					= trim($arr_mosms[$h]['text']);
				$filecnt			= trim($arr_mosms[$h]['filecnt']);
				$fileloc1			= trim($arr_mosms[$h]['fileloc1']);
				$fileloc2			= trim($arr_mosms[$h]['fileloc2']);
				$fileloc3			= trim($arr_mosms[$h]['fileloc3']);
				$fileloc4			= trim($arr_mosms[$h]['fileloc4']);
				$fileloc5			= trim($arr_mosms[$h]['fileloc5']);
				$telecom			= trim($arr_mosms[$h]['telecom']);
				$MO_TABLE			= trim($arr_mosms[$h]['MO_TABLE']);
				
				//echo $MO_TABLE."<br>";

				$g_site_url = "http://krwu.nodong.net";
				$g_physical_path = "/data01/krwu/http/home2014/";

				// thumb_img 생성
				if ($fileloc1) {

					$thumb_img = str_replace("/data01/krwu/http/home2014/upload_data/mo_file/", "", $fileloc1);
					$img_url = $g_site_url.str_replace("/data01/krwu/http", "", $fileloc1);

					if (file_exists($fileloc1)) {
						if (create_thumbnail($img_url, $g_physical_path."upload_data/board/simg/".$thumb_img,"280","220")) {
							$temp_thumb_img = $thumb_img;
						}
					}
				}
				
				$query_in = "INSERT INTO TBL_MOSMS (MSEQ, MO_TABLE, TYPE, OAADDR, DAADDR, CALLBACK, STAT, INSERT_TIME, SUBJECT, TXT, FILE_NM, TELECOM, REG_DATE, THUMB_IMG) 
																		VALUES ('$mseq','$MO_TABLE','$type','$oaaddr','$daaddr','$callback','$stat','$insert_time','$subject','$txt','$fileloc1','$telecom', now(),'$temp_thumb_img') ";
				@mysql_query($query_in,$db);

				if ($fileloc2) {

					$thumb_img = str_replace("/data01/krwu/http/home2014/upload_data/mo_file/", "", $fileloc2);
					$img_url = $g_site_url.str_replace("/data01/krwu/http", "", $fileloc2);
					
					if (file_exists($fileloc2)) {
						if (create_thumbnail($img_url, $g_physical_path."upload_data/board/simg/".$thumb_img,"280","220")) {
							$temp_thumb_img = $thumb_img;
						}
					}

					$query_in = "INSERT INTO TBL_MOSMS (MSEQ, MO_TABLE, TYPE, OAADDR, DAADDR, CALLBACK, STAT, INSERT_TIME, SUBJECT, TXT, FILE_NM, TELECOM, REG_DATE, THUMB_IMG) 
																		VALUES ('$mseq','$MO_TABLE','$type','$oaaddr','$daaddr','$callback','$stat','$insert_time','$subject','$txt','$fileloc2','$telecom', now(),'$temp_thumb_img') ";
					@mysql_query($query_in,$db);
				}

				if ($fileloc3) {

					$thumb_img = str_replace("/data01/krwu/http/home2014/upload_data/mo_file/", "", $fileloc3);
					$img_url = $g_site_url.str_replace("/data01/krwu/http", "", $fileloc3);
					
					if (file_exists($fileloc3)) {
						if (create_thumbnail($img_url, $g_physical_path."upload_data/board/simg/".$thumb_img,"280","220")) {
							$temp_thumb_img = $thumb_img;
						}
					}

					$query_in = "INSERT INTO TBL_MOSMS (MSEQ, MO_TABLE, TYPE, OAADDR, DAADDR, CALLBACK, STAT, INSERT_TIME, SUBJECT, TXT, FILE_NM, TELECOM, REG_DATE, THUMB_IMG) 
																		VALUES ('$mseq','$MO_TABLE','$type','$oaaddr','$daaddr','$callback','$stat','$insert_time','$subject','$txt','$fileloc3','$telecom', now(),'$temp_thumb_img') ";
					@mysql_query($query_in,$db);
				}

				if ($fileloc4) {

					$thumb_img = str_replace("/data01/krwu/http/home2014/upload_data/mo_file/", "", $fileloc4);
					$img_url = $g_site_url.str_replace("/data01/krwu/http", "", $fileloc4);
					
					if (file_exists($fileloc4)) {
						if (create_thumbnail($img_url, $g_physical_path."upload_data/board/simg/".$thumb_img,"280","220")) {
							$temp_thumb_img = $thumb_img;
						}
					}

					$query_in = "INSERT INTO TBL_MOSMS (MSEQ, MO_TABLE, TYPE, OAADDR, DAADDR, CALLBACK, STAT, INSERT_TIME, SUBJECT, TXT, FILE_NM, TELECOM, REG_DATE, THUMB_IMG) 
																		VALUES ('$mseq','$MO_TABLE','$type','$oaaddr','$daaddr','$callback','$stat','$insert_time','$subject','$txt','$fileloc4','$telecom', now(),'$temp_thumb_img') ";
					@mysql_query($query_in,$db);
				}

				if ($fileloc5) {

					$thumb_img = str_replace("/data01/krwu/http/home2014/upload_data/mo_file/", "", $fileloc5);
					$img_url = $g_site_url.str_replace("/data01/krwu/http", "", $fileloc5);
					
					if (file_exists($fileloc5)) {
						if (create_thumbnail($img_url, $g_physical_path."upload_data/board/simg/".$thumb_img,"280","220")) {
							$temp_thumb_img = $thumb_img;
						}
					}

					$query_in = "INSERT INTO TBL_MOSMS (MSEQ, MO_TABLE, TYPE, OAADDR, DAADDR, CALLBACK, STAT, INSERT_TIME, SUBJECT, TXT, FILE_NM, TELECOM, REG_DATE, THUMB_IMG) 
																		VALUES ('$mseq','$MO_TABLE','$type','$oaaddr','$daaddr','$callback','$stat','$insert_time','$subject','$txt','$fileloc5','$telecom', now(),'$temp_thumb_img') ";
					@mysql_query($query_in,$db);
				}
			}
		}
	}


	function listMoSms($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		$logical_num = ($total_cnt - $offset) + 1 ;
		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, SEQ_NO, MSEQ, MO_TABLE, TYPE, OAADDR, DAADDR, CALLBACK, 
										 STAT, INSERT_TIME, SUBJECT, TXT, FILE_NM, TELECOM, REG_DATE, THUMB_IMG, USE_TF, DEL_TF, DEL_ADM, DEL_DATE
								FROM TBL_MOSMS A WHERE 1 = 1 AND FILE_NM <> '' ";
		

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

		#echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntMoSms ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_MOSMS WHERE 1 = 1 ";
		
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

	function updateMoSmsUseTF($db, $use_tf, $seq_no) {

		$query="UPDATE TBL_MOSMS SET 
							USE_TF	= '$use_tf'
				 WHERE SEQ_NO	= '$seq_no' ";

		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteMoSms($db, $adm_no, $seq_no) {

		$query="UPDATE TBL_MOSMS SET
							DEL_TF	= 'Y',
							DEL_ADM = '$adm_no',
							DEL_DATE = now()
				 WHERE SEQ_NO	= '$seq_no' ";

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