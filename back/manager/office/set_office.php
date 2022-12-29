<?session_start();?>
<?
// 절대 결코 윗부분 칸띄우지마세요
header("Content-Type: text/plain; charset=utf-8"); 

function listOffice($db) {

	$query = "SELECT *
							FROM g4_write_branch ";

	$result = mysql_query($query,$db);
	$record = array();
		

	if ($result <> "") {
		for($i=0;$i < mysql_num_rows($result);$i++) {
			$record[$i] = sql_result_array($result,$i);
		}
	}
	return $record;
}


function insertOffice($db, $name, $tel01, $address) {

	$query="INSERT INTO TBL_OFFICE (NAME, TEL01, ADDRESS, USE_TF, DEL_TF, REG_DATE) 
										 values ('$name','$tel01', '$address', 'Y', 'N', now()); ";
		
		#echo $query;

	if(!mysql_query($query,$db)) {
		return false;
		echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
		exit;
	} else {
		return true;
	}
}

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$arr_rs = listOffice($conn);

	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$wr_subject	= trim($arr_rs[$j]["wr_subject"]);
			$wr_link1		= trim($arr_rs[$j]["wr_link1"]);
			$wr_7				= trim($arr_rs[$j]["wr_7"]);
			$wr_8				= trim($arr_rs[$j]["wr_8"]);

			$address = $wr_7." ".$wr_8;
			
			$result = insertOffice($conn, $wr_subject, $wr_link1, $address);
			
		}
	}
	
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);

?>
