<?
	
	function db_connection($usr_type) {
		
		if ($usr_type == "w") {

			$host_addr  =  "localhost";				// DB ���� IP �ּ�
			$dbname     =  "UCOM";								// My-Sql DB ���� 
			$db_id      =  "ucom";						// My-Sql DB ID
			$db_passwd  =  "ucomdb2017";						// My-Sql DB ��й�ȣ  //minew00!
				
		} else if ($usr_type == "r") {

			$host_addr  =  "localhost";				// DB ���� IP �ּ�
			$dbname     =  "UCOM";								// My-Sql DB ���� 
			$db_id      =  "ucom";						// My-Sql DB ID
			$db_passwd  =  "ucomdb2017";						// My-Sql DB ��й�ȣ  //minew00!

		}

		$link = mysqli_connect($host_addr, $db_id, $db_passwd,$dbname);
			
		
		if (mysqli_connect_errno($link)) {
			echo "" .mysqli_connect_errno($link);
		}


		if (!$link) {
			die('������ ���̽��� ���ῡ ���� �Ͽ����ϴ�. :' . mysqli_error());
		}

		//$db_selected = mysql_select_db($dbname, $link);
		
		//if (!$db_selected) {
		//	die ('�ش� ������ ���̽��� ã�� �� �����ϴ�. : ' . mysql_error());
		//}

		return 	$link;

	}

	function mysqli_field_name($result, $field_offset) {
		$properties = mysqli_fetch_field_direct($result, $field_offset);
		return is_object($properties) ? $properties->name : null;
	}

	function mysqli_result($res,$row=0,$col=0){ 
		$numrows = mysqli_num_rows($res); 
		if ($numrows && $row <= ($numrows-1) && $row >=0){
			mysqli_data_seek($res,$row);
			$resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
			if (isset($resrow[$col])){
				return $resrow[$col];
			}
		}
		return false;
	}

	function sql_result_array($handle,$row) {
		$count = mysqli_num_fields($handle);

		//echo $count;

		for($i=0;$i<$count;$i++){
			$fieldName = mysqli_field_name($handle,$i);
			$ret[$fieldName] = mysqli_result($handle,$row,$i);
			//echo $fieldName . "=" . $ret[$fieldName] . "<BR>";
		}
		return $ret;
	}



	function listAdminGroup($db) {

		$query = "SELECT GROUP_NO, GROUP_NAME, GROUP_FLAG, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_ADMIN_GROUP A WHERE 1 = 1 ";
		
		$query .= " ORDER BY GROUP_NO desc";

		#echo $query;

		$result = mysqli_query($db, $query);
		$record = array();
		
		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function totalCntAdminGroup ($db) {

		$query ="SELECT COUNT(*) CNT FROM TBL_ADMIN_GROUP WHERE 1 = 1 ";
		
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	$conn = db_connection("w");

	$arr_rs = listAdminGroup($conn);

	$arr_rs = listAdminGroup($conn);

	for ($i = 0 ; $i < sizeof($arr_rs); $i++) {
		echo $arr_rs[$i]["GROUP_NAME"]."<br>";
	}

	echo totalCntAdminGroup($conn);

	mysqli_close($conn);

// cafe24 ����
// ���̵� : ucomp620
// ��� : ucp87412*

?>