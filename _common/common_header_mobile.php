<?
	function selectMenuAsMenuCd($db, $menu_cd) {

		$query = "SELECT MENU_NAME, MENU_URL
								FROM TBL_ADMIN_MENU WHERE MENU_CD = '$menu_cd' ";
		
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

	function getAdminMenuRight($db, $group_no, $menu_right) {

		$query = "SELECT A.MENU_CD, A.GROUP_NO, A.READ_FLAG, A.REG_FLAG, A.UPD_FLAG, A.DEL_FLAG, A.FILE_FLAG, A.TOP_FLAG, A.MAIN_FLAG
								FROM TBL_ADMIN_MENU_RIGHT A, TBL_ADMIN_MENU B 
							 WHERE A.MENU_CD = B.MENU_CD 
								 AND A.GROUP_NO = '$group_no' 
								 AND B.DEL_TF = 'N' 
								 AND B.MENU_RIGHT = '$menu_right' ";
		
		$result = mysql_query($query,$db);
		//echo $query;
		//die;
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
?>
<?
	// 관리자 페이지 
	$arr_page_nm = explode("/", $_SERVER['PHP_SELF']);

	if ($arr_page_nm[1] == "m" ) {  //모바일용 권한 추가 위해 2022-08-17

		if ($_SESSION['s_adm_no'] == "") {
?>
<meta http-equiv='Refresh' content='0; URL=/m/login.php'>
<?
			mysql_close($conn);
			exit;

		} else {

			// 페이지 권한을 체크한다..

			//$menu_right = "ddd";

			$arr_menu_right_ = getAdminMenuRight($conn, $_SESSION['s_adm_group_no'], $menu_right);
			
			if (sizeof($arr_menu_right_) > 0) {

				$sPageRight_		= "Y";
				$sPageRight_R		= trim($arr_menu_right_[0]["READ_FLAG"]);
				$sPageRight_I		= trim($arr_menu_right_[0]["REG_FLAG"]);
				$sPageRight_U		= trim($arr_menu_right_[0]["UPD_FLAG"]);
				$sPageRight_D		= trim($arr_menu_right_[0]["DEL_FLAG"]);
				$sPageRight_F		= trim($arr_menu_right_[0]["FILE_FLAG"]);
				$sPageRight_T		= trim($arr_menu_right_[0]["TOP_FLAG"]);
				$sPageRight_M		= trim($arr_menu_right_[0]["MAIN_FLAG"]);
				$sPageMenu_CD		= trim($arr_menu_right_[0]["MENU_CD"]);

			} else {

				$sPageRight_		= "N";
				$sPageRight_R		= "N";
				$sPageRight_I		= "N";
				$sPageRight_U		= "N";
				$sPageRight_D		= "N";
				$sPageRight_F		= "N";
				/*
				$sPageRight_		= "Y";
				$sPageRight_R		= "Y";
				$sPageRight_I		= "Y";
				$sPageRight_U		= "Y";
				$sPageRight_D		= "Y";
				$sPageRight_F		= "Y";
				*/
				
			}
			/*	
			if ($sPageRight_R <> "Y") {
?>
<meta http-equiv='Refresh' content='0; URL=/m/main/main.php'>
<?
				mysql_close($conn);
				exit;
			}


			// 관리자 페이지 메뉴 이름 경로 가지고 오기
			//$sPageMenu_CD = "001";

			$arr_rs_menu = selectMenuAsMenuCd($conn, $sPageMenu_CD);
		
			if (sizeof($arr_rs_menu) > 0) {

				$p_menu_name	= trim($arr_rs_menu[0]["MENU_NAME"]); 
				$p_menu_url		= trim($arr_rs_menu[0]["MENU_URL"]); 
			}
		
			// 관리자 페이지 메뉴 이름 경로 가지고 오기
			$arr_rs_menu = selectMenuAsMenuCd($conn, substr($sPageMenu_CD,0, 2));
		
			if (sizeof($arr_rs_menu) > 0) {
				$p_parent_menu_name	= trim($arr_rs_menu[0]["MENU_NAME"]); 
			}
			*/
		}
	}
?>