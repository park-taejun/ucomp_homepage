<?
	
	function db_connection($usr_type) {
		
		if ($usr_type == "w") {

			$host_addr  =  "localhost";				// DB 접속 IP 주소
			$dbname     =  "UCOM";								// My-Sql DB 선택 
			$db_id      =  "ucom";						// My-Sql DB ID
			$db_passwd  =  "ucomdb2017";						// My-Sql DB 비밀번호  //minew00!
				
		} else if ($usr_type == "r") {

			$host_addr  =  "localhost";				// DB 접속 IP 주소
			$dbname     =  "UCOM";								// My-Sql DB 선택 
			$db_id      =  "ucom";						// My-Sql DB ID
			$db_passwd  =  "ucomdb2017";						// My-Sql DB 비밀번호  //minew00!

		}

		$link = mysql_connect($host_addr, $db_id, $db_passwd);
		//$link = mysqli_connect($host_addr, $db_id, $db_passwd,$dbname);
		
		if (!$link) {
			die('데이터 베이스에 연결에 실패 하였습니다. :' . mysql_error());
		}

		$db_selected = mysql_select_db($dbname, $link);
		
		if (!$db_selected) {
			die ('해당 데이터 베이스를 찾을 수 없습니다. : ' . mysql_error());
		}

		return 	$link;

	}

	function sql_result_array($handle,$row) {
		$count = mysql_num_fields($handle);

		//echo $count;

		for($i=0;$i<$count;$i++){
			$fieldName = mysql_field_name($handle,$i);
			$ret[$fieldName] = mysql_result($handle,$row,$i);
			//echo $fieldName . "=" . $ret[$fieldName] . "<BR>";
		}
		return $ret;
	}



	function listAdminGroup($db) {

		$query = "SELECT GROUP_NO, GROUP_NAME, GROUP_FLAG, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_GROUP A WHERE 1 = 1 ";
		
		$query .= " ORDER BY GROUP_NO desc";

		#echo $query;

		$result = mysql_query($query, $db);
		$record = array();
		
		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntAdminGroup ($db) {

		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_GROUP WHERE 1 = 1 ";
		
		$result = mysql_query($query, $db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	$conn = db_connection("w");

	$arr_rs = listAdminGroup($conn);

	for ($i = 0 ; $i < sizeof($arr_rs); $i++) {
		echo $arr_rs[$i]["GROUP_NAME"]."<br>";
	}

	echo totalCntAdminGroup($conn);

	mysql_close($conn);

// cafe24 계정
// 아이디 : ucomp620
// 비번 : ucp87412*

?>