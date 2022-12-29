<?

	include "../admin_session_check.inc";
	include "../../dbconn.inc";

	$sPcode = trim($pcode);
	$arr_cat_id = trim($arr_cat_id);

	$i = 1;

	$arr_values = explode("^",$arr_cat_id);

	for($i = 0; $i < sizeof($arr_values); $i++) {
	
		$query = "update tb_code_detail set
								dcode_seq = '$i'
		          where pcode = '$sPcode' and dcode_no = '$arr_values[$i]' ";		

		mysql_query($query) or die("Query Error");

		#$i++;
	}
	
	echo "<script language=\"javascript\">\n
			parent.frames[3].location = 'detail_code_list.php?page=$page&pcode=$sPcode&dcode_no=$iDcode_no' 
			</script>";

	mysql_close($connect);		
?>