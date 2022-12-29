<?
	//exit;

	function db_connection($usr_type) {
		
		if ($usr_type == "w") {

			$host_addr  =  "localhost";				// DB 접속 IP 주소http://115.68.47.91/My_Sql_Admin
			$dbname     =  "ucomp_db";								// My-Sql DB 선택 
			
			$db_id      =  "ucomp_user";						// My-Sql DB ID
			$db_passwd  =  "ucomp2018**";						// My-Sql DB 비밀번호

		} else if ($usr_type == "r") {

			$host_addr  =  "localhost";				// DB 접속 IP 주소http://115.68.47.91/My_Sql_Admin
			$dbname     =  "ucomp_db";								// My-Sql DB 선택 

			$db_id      =  "ucomp_user";						// My-Sql DB ID
			$db_passwd  =  "ucomp2018**";						// My-Sql DB 비밀번호

		}
		
		$link = mysql_connect($host_addr, $db_id, $db_passwd);
		
		if (!$link) {
			// 연결 실패 후 다시 한번 시도.
			$link = mysql_connect($host_addr, $db_id, $db_passwd);
			if (!$link) {
				// 연결 실패 후 다시 한번 시도.
				$link = mysql_connect($host_addr, $db_id, $db_passwd);
				
				if (!$link) {
					//die('데이터 베이스에 연결에 실패 하였습니다. :' . mysql_error());
				}
			}
		}

		$db_selected = @mysql_select_db($dbname, $link);
		
		if (!$db_selected) {
			//die ('해당 데이터 베이스를 찾을 수 없습니다. : ' . mysql_error());
		}

		mysql_query("SET NAMES utf8");
		mysql_query("SET character_set_server = utf8");

		return 	$link;

	}


	function sql_result_array($handle,$row) {
		$count = mysql_num_fields($handle);
		
		echo $count;

		for($i=0;$i<$count;$i++){
			$fieldName = mysql_field_name($handle,$i);
			$ret[$fieldName] = mysql_result($handle,$row,$i);
			//echo $fieldName . "=" . $ret[$fieldName] . "<BR>";
		}
		return $ret;
	}

	$conn = db_connection("w");

	function selectCompany($db) {

		$query = "SELECT CP_TYPE, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, RE_ZIP, RE_ADDR, HOMEPAGE, BIZ_NO, CEO_NM, UPJONG, UPTEA, 
							 DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, EMAIL, EMAIL_TF, CONTRACT_START, CONTRACT_END, AD_TYPE, 
							 ACCOUNT_BANK, ACCOUNT, DELIVERY_LIMIT, DELIVERY_PRICE, MEMO, COM_AUTH_TF, USE_TF, DEL_TF, 
							 REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_COMPANY";
		
		$result = mysql_query($query, $db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}
	
	$arr_rs = selectCompany($conn);

	mysql_close($conn);
?>