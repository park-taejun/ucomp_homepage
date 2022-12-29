<?session_start();?>
<?
// 절대 결코 윗부분 칸띄우지마세요
header("Content-Type: text/plain; charset=utf-8"); 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/etc/etc.php";


#====================================================================
# Request Parameter
#====================================================================
	$area_01 = trim($area_01);
	$area_02 = trim($area_02);
	
	$query = "SELECT DCODE, DCODE_NM
							FROM TBL_CODE_DETAIL 
						 WHERE PCODE = 'AREA_SUB'
							 AND DEL_TF = 'N' 
							 AND USE_TF = 'Y'
							 AND DCODE like '".$area_01."%' 
						 ORDER BY DCODE_NM ASC ";

	//echo $query."<br>";

	$result = mysql_query($query,$conn);

	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}

	$total  = mysql_affected_rows();
?>
<select name="area_cd_02" >
<?
	for($i=0 ; $i< $total ; $i++) {
		
		mysql_data_seek($result,$i);
		$row = mysql_fetch_array($result);
			
		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);
?>
<option value="<?=$RS_DCODE?>" <? if ($area_02 == trim($RS_DCODE)) echo "selected" ?>><?=$RS_DCODE_NM?></option>
<?
	}
	
?>
</select>
<?

#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);

?>
