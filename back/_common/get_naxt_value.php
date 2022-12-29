<?session_start();?>
<?
// 절대 결코 윗부분 칸띄우지마세요
header("Content-Type: text/plain; charset=utf-8"); 

#====================================================================
# common_header Check Session
#====================================================================
	include "../_common/common_header.php"; 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/etc/etc.php";

#====================================================================
# Request Parameter
#====================================================================
	$pcode = trim($pcode);
	
	$query = "SELECT DCODE, DCODE_NM
							FROM TBL_CODE_DETAIL
						 WHERE 1 = 1 
							 AND DEL_TF = 'N' 
							 AND USE_TF = 'Y'
							 AND PCODE = '".$pcode."' 
						 ORDER BY DCODE_SEQ_NO ASC ";
		//echo $query;

	$result = mysql_query($query,$conn);

	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}

	$total  = mysql_affected_rows();

	for($i=0 ; $i< $total ; $i++) {
		
		mysql_data_seek($result,$i);
		$row = mysql_fetch_array($result);
			
		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);
		
		$str .= $RS_DCODE."".$RS_DCODE_NM."";

	}
	
	echo $str;

#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);

?>
