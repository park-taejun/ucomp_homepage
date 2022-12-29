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
	$depth = trim($depth);
	
	if ($depth == "1") {

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
								FROM TBL_CATEGORY 
							 WHERE 1 = 1 
								 AND DEL_TF = 'N' 
								 AND USE_TF = 'Y'
								 AND CATE_CD like '".$gd_cate_01."%' 
								 AND LENGTH(CATE_CD) = '4' 
							 ORDER BY SEQ ASC ";
		//echo $query;
	}

	if ($depth == "2") {

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
								FROM TBL_CATEGORY 
							 WHERE 1 = 1 
								 AND DEL_TF = 'N' 
								 AND USE_TF = 'Y'
								 AND CATE_CD like '".$gd_cate_02."%' 
								 AND LENGTH(CATE_CD) = '6' 
							 ORDER BY SEQ ASC ";

	}

	if ($depth == "3") {

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
								FROM TBL_CATEGORY 
							 WHERE 1 = 1 
								 AND DEL_TF = 'N' 
								 AND USE_TF = 'Y'
								 AND CATE_CD like '".$gd_cate_03."%' 
								 AND LENGTH(CATE_CD) = '8' 
							 ORDER BY SEQ ASC ";

	}

	if ($depth == "4") {

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
								FROM TBL_CATEGORY 
							 WHERE 1 = 1 
								 AND DEL_TF = 'N' 
								 AND USE_TF = 'Y'
								 AND CATE_CD like '".$gd_cate_04."%' 
								 AND LENGTH(CATE_CD) = '10' 
							 ORDER BY SEQ ASC ";

	}

	//echo $query."<br>";

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
			
		$RS_GOODS_NO	= Trim($row[1]);
		$RS_GOODS_NM	= Trim($row[2]);
		
		$str .= $depth."".$RS_GOODS_NO."".$RS_GOODS_NM."";

	}
	
	echo $str;

#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);

?>
