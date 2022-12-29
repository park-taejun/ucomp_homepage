<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util_ptj.php";

#====================================================================
	$savedir1 = $g_physical_path."/upload_data/prize";
	// $savedir1 = $g_physical_path."/upload_data/banner";
#====================================================================
	
	$file_nm		= upload($_FILES["prize_photo"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
	$file_rnm		= $_FILES["prize_photo"]["name"];

	echo $file_nm;

?>
