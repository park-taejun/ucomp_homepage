<?

	function totalCntAdminApprovalRight($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str) {

		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_INFO A 
																			LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206' 
																 WHERE 1 = 1 AND D.LEADER_YN = 'Y' AND D.LEADER_TITLE <> '유닛장' ";  //리더만
		
		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}

		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' ";
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

//echo $query;
//exit;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listAdminApprovalRight($db, $group_no, $headquarters_code, $dept_code, $position_code, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount) {

		$offset = $nRowCount*($nPage-1);
		
		$query = "set @rownum = ".$offset."; ";
		mysql_query($query,$db);


		$query = "SELECT @rownum:= @rownum + 1  as rn, A.ADM_ID, A.ADM_NO, A.PASSWD, A.ADM_NAME, A.ADM_INFO, A.GROUP_NO, A.ADM_HPHONE, A.ADM_PHONE, 
										 A.ADM_PHONE, A.ADM_EMAIL, A.ADM_BIRTHDAY, A.ADM_ZIP, A.ADM_ADDR,A.ADM_FLAG, 
                     A.COM_CODE, A.ENTER_DATE, A.PROFILE,
										 D.POSITION_CODE, D.OCCUPATION_CODE, D.DEPT_CODE, 
										 D.HEADQUARTERS_CODE, D.LEADER_YN, D.LEVEL, D.DEPT_UNIT_NAME, D.LEADER_TITLE, 
										 A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE,
										 B.DCODE_SEQ_NO, C.DCODE_SEQ_NO, B.DCODE_NM AS DEPT_NAME, C.DCODE_NM AS POSITION_NAME
								FROM TBL_ADMIN_INFO A 
                     LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '202206'
										 LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
										 LEFT OUTER JOIN TBL_CODE_DETAIL C ON D.POSITION_CODE = C.DCODE AND C.USE_TF = 'Y' AND C.DEL_TF ='N' AND C.PCODE = 'POSITION'
							 WHERE 1 = 1 AND D.LEADER_YN = 'Y' AND D.LEADER_TITLE <> '유닛장' ";

		if ($group_no <> "") {
			$query .= " AND A.GROUP_NO = '".$group_no."' ";
		}

		if ($headquarters_code <> "") {
			$query .= " AND D.HEADQUARTERS_CODE = '".$headquarters_code."' ";
		}

		if ($dept_code <> "") {
			$query .= " AND D.DEPT_CODE = '".$dept_code."' ";
		}
		
		if ($use_tf == "Y") {
			$query .= " AND D.POSITION_CODE <> '' " ;  //직급이 없는 관리자 외는 제외!!!2021-11-22
		}

		if ($position_code <> "") {
			$query .= " AND D.POSITION_CODE = '".$position_code."' "; 
		}

		if ($use_tf <> "") {
			$query .= " AND A.USE_TF = '".$use_tf."' ";
		}

		if ($del_tf <> "") {
			$query .= " AND A.DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}
		
		$query .= " ORDER BY FIELD(D.POSITION_CODE,'대표이사','이사','수석','책임','선임','전임'), FIELD(D.LEADER_TITLE,'이사','본부장','부문장','팀장','유닛장'), A.COM_CODE ASC, H.DCODE_SEQ_NO ASC, B.DCODE_SEQ_NO ASC, C.DCODE_SEQ_NO ASC limit ".$offset.", ".$nRowCount;

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

	function UpdateAdminApprovalRight($db, $app_type, $adm_no, $app_right, $s_adm_no) {

		foreach ($adm_no as $key => $value) {

			//echo $value."/".$app_right[$key]."<br>";

			$query = "SELECT ADM_NO FROM TBL_ADMIN_APPROVAL_RIGHT WHERE APP_TYPE='$app_type' AND ADM_NO = '$value' AND USE_TF = 'Y' AND DEL_TF = 'N'";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$record  = $rows[0];

			if ($record) {
					$query = "UPDATE TBL_ADMIN_APPROVAL_RIGHT SET APP_RIGHT = '$app_right[$key]' WHERE APP_TYPE='$app_type' AND ADM_NO = '$value' AND USE_TF = 'Y' AND DEL_TF = 'N'";
			} else {
					$query = "INSERT INTO TBL_ADMIN_APPROVAL_RIGHT (APP_TYPE, ADM_NO, APP_RIGHT, USE_TF, DEL_TF, REG_ADM, REG_DATE) VALUES ";
					$query .= "('$app_type','$value','$app_right[$key]','Y','N','$s_adm_no',now())";
			}

			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			}

		}
	}

	function selectApp($db, $app_type, $adm_no) {

		$query = "SELECT APP_RIGHT FROM TBL_ADMIN_APPROVAL_RIGHT WHERE APP_TYPE='$app_type' AND ADM_NO = '$adm_no'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


/*
		$query = "SELECT APP_RIGHT FROM TBL_ADMIN_APPROVAL_RIGHT WHERE ADM_NO = '$adm_no'";
		
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		if ($record) {

			foreach ($arr_data_org as $key => $value) {
				$value = str_replace("'","''",$value);
				$set_query_str .= $key." = '".$value."',"; 
			}

			$query = "UPDATE TBL_ADMIN_APPROVAL_RIGHT SET ".$set_query_str." ";
			$query .= "UP_DATE = now() WHERE ADM_NO = '$adm_no'";

			//echo $query;
			//exit;

			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			} else {
				return true;
			}
		} else {

			// 게시물 등록
			$set_field = "";
			$set_value = "";
			
			$first = "Y";

			foreach ($arr_data_org as $key => $value) {

				$value = str_replace("'","''",$value);

				if ($first == "Y") {
					$set_field .= $key; 
					$set_value .= "'".$value."'"; 
					$first = "N";
				} else {
					$set_field .= ",".$key; 
					$set_value .= ",'".$value."'";
				}
			}

			$query = "INSERT INTO TBL_ADMIN_APPROVAL_RIGHT (".$set_field.", REG_DATE, UP_DATE) 
						values (".$set_value.", now(), now()); ";

			echo $query."<br>"; 

			if(!mysql_query($query,$db)) {
				return false;
				echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
				exit;
			} else {
				return true;
			}
		}

*/


?>