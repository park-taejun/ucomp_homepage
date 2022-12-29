<?
	# =============================================================================
	# File Name    : team.php
	# Modlue       : 
	# Writer       : Park Tae Jun
	# Create Date  : 2022. 12. 19
	# Modify Date  : 
	#	Copyright : Copyright @MONEUAL Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# Used Table TBL_TEAM
	#=========================================================================================================
	
	/*
	CREATE TABLE `TBL_TEAM` (
	  `TEAM_NO` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '배너	일련번호',
	  `TEAM_TYPE` varchar(30) NOT NULL COMMENT '팀 구분',
	  `TEAM_NM` varchar(30) NOT NULL COMMENT '팀 이름',
	  `TEAM_IMG` varchar(50) NOT NULL COMMENT '팀 이미지 명',
	  `TEAM_REAL_IMG` varchar(50) NOT NULL COMMENT '팀 이미지 실제 명',
	  `TEAM_CONTENTS` varchar(50) DEFAULT NULL COMMENT '팀 소개',
	  `DISP_SEQ` int(10) unsigned DEFAULT NULL COMMENT '순번',
	  `USE_TF` char(1) NOT NULL DEFAULT 'Y' COMMENT '사용	여부 사용(Y),사용안함(N)',
	  `DEL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제	여부 삭제(Y),사용(N)',
	  `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록	관리자 일련번호 TBL_ADMIN ADM_NO',
	  `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
	  `UP_ADM` int(11) unsigned DEFAULT NULL COMMENT '수정	관리자 일련번호 TBL_ADMIN ADM_NO',
	  `UP_DATE` datetime DEFAULT NULL COMMENT '수정일',
	  `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제	관리자 일련번호 TBL_ADMIN ADM_NO',
	  `DEL_DATE` datetime DEFAULT NULL COMMENT '삭제일',
	  PRIMARY KEY (`TEAM_NO`)
	) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
	*/
 
	#=========================================================================================================
	# End Table
	#=========================================================================================================


	function listTeam($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$total_cnt = totalCntTeam ($db, $use_tf, $del_tf, $search_field, $search_str);

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT  @rownum:= @rownum - 1  as rn, B.TEAM_NO, B.TEAM_TYPE, B.TEAM_NM, B.TEAM_IMG, 
										 B.TEAM_REAL_IMG, B.TEAM_CONTENTS, B.DISP_SEQ, B.USE_TF, B.DEL_TF, 
										 B.REG_ADM, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE,
										 I.ADM_NAME AS REG_NAME, A.ADM_NAME AS UP_NAME
								FROM TBL_TEAM B  
									LEFT JOIN TBL_ADMIN_INFO I ON B.REG_ADM = I.ADM_NO 
									LEFT JOIN TBL_ADMIN_INFO A ON B.UP_ADM = A.ADM_NO 
								WHERE 1 = 1 AND ( B.USE_TF <> 'N' OR B.DEL_TF <> 'N' )   ";    
		 
		if ($use_tf <> "") {
			$query .= " AND B.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND B.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND B.TEAM_NM like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY B.DISP_SEQ ASC, B.REG_DATE DESC limit ".$offset.", ".$nRowCount;

		// echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listTeamAll($db) {
 
		$query = "SELECT  
						B.TEAM_NO, B.TEAM_TYPE, B.TEAM_NM, B.TEAM_IMG, 
						B.TEAM_REAL_IMG, B.TEAM_CONTENTS, B.DISP_SEQ, B.USE_TF, B.DEL_TF, 
						B.REG_ADM, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE 
				FROM TBL_TEAM B  					
				WHERE B.USE_TF = 'Y' 
				AND B.DEL_TF = 'N' ";    				
		
		$query .= " ORDER BY B.DISP_SEQ ASC, B.REG_DATE DESC  ";

		// echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listTeamTop($db) {
 
		$query = "SELECT  
						B.TEAM_NO, B.TEAM_TYPE, B.TEAM_NM, B.TEAM_IMG, 
						B.TEAM_REAL_IMG, B.TEAM_CONTENTS, B.DISP_SEQ, B.USE_TF, B.DEL_TF, 
						B.REG_ADM, B.REG_DATE, B.UP_ADM, B.UP_DATE, B.DEL_ADM, B.DEL_DATE 
				FROM TBL_TEAM B  					
				WHERE B.USE_TF = 'Y' 
				AND B.DEL_TF = 'N' ";     
		
		$query .= " ORDER BY B.DISP_SEQ ASC, B.REG_DATE DESC limit 1 ";

		// echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function listTeamSelectAll($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT  
						B.TEAM_NO, B.TEAM_NM, B.TEAM_IMG
				FROM TBL_TEAM B  					
				WHERE B.USE_TF = 'Y' 
				AND B.DEL_TF = 'N' ";    
				
		
		$query .= " ORDER BY B.DISP_SEQ ASC, B.REG_DATE DESC ";
		 
		// echo $query;
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class='box01'  style='width:".$size."px;' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE		= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}
	 
	function totalCntTeam ($db, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_TEAM WHERE 1 = 1 AND ( USE_TF <> 'N' OR DEL_TF <> 'N' ) ";
 
		if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND TEAM_NM like '%".$search_str."%' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function insertTeam($db, $arr_data) {
		 
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

		$query = "INSERT INTO TBL_TEAM (".$set_field.", REG_DATE, UP_DATE) 
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
			$new_p_no  = $rows[0];
			return $new_p_no;
		}
		  
	}

	// function selectBanner($db, $site_no, $banner_no) {
	function selectTeam($db, $team_no) {
		 
		$query = "SELECT 
						TEAM_NO, TEAM_TYPE, TEAM_NM, TEAM_IMG, TEAM_REAL_IMG, TEAM_CONTENTS,
						DISP_SEQ, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, 
						DEL_DATE						
				FROM TBL_TEAM WHERE TEAM_NO = '$team_no'   ";
		
		// echo $query;
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	function updateTeamAll($db, $arr_data, $team_no) {

		foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_TEAM SET ".$set_query_str." ";
		$query .= "UP_DATE = now() ";
		$query .= "WHERE TEAM_NO = '$team_no' ";
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		} 
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

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

		// function deleteBanner($db, $del_adm, $g_site_no, $tmp_banner_no) {
	function deleteTeam($db, $del_adm,  $team_no) {
		$query = "UPDATE TBL_TEAM SET
						 USE_TF 			= 'N',  
						 DEL_TF				= 'N',
						 DEL_ADM			= '$del_adm',
						 DEL_DATE			= now()					
						WHERE TEAM_NO				= '$team_no' ";
 
		// echo $query."<br>";
		// exit;
		 
		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
		 
	}
?>