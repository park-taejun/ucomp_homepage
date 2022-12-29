<?
	//exit;

	function db_connection($usr_type) {
		
		if ($usr_type == "w") {

			$host_addr  =  "localhost";				// DB 접속 IP 주소http://115.68.47.91/My_Sql_Admin
			$dbname     =  "ucomdev";								// My-Sql DB 선택 
			
			$db_id      =  "ucomdev";						// My-Sql DB ID
			$db_passwd  =  "ucomp2018**";						// My-Sql DB 비밀번호

		} else if ($usr_type == "r") {

			$host_addr  =  "localhost";				// DB 접속 IP 주소http://115.68.47.91/My_Sql_Admin
			$dbname     =  "ucomdev";								// My-Sql DB 선택 

			$db_id      =  "ucomdev";						// My-Sql DB ID
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
		for($i=0;$i<$count;$i++){
			$fieldName = mysql_field_name($handle,$i);
			$ret[$fieldName] = mysql_result($handle,$row,$i);
			//echo $fieldName . "=" . $ret[$fieldName] . "<BR>";
		}
		return $ret;
	}

?>