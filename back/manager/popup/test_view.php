<?session_start();?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$writer_id = $s_adm_id;//작성자 아이디:로그인한 사용자 아이디

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = $bb_code; // 메뉴마다 셋팅 해 주어야 합니다
	require "../../_common/common_header.php";

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/popup/popup.php";


	$pop_no					= $_POST['pop_no']!=''?$_POST['pop_no']:$_GET['pop_no'];


		$arr_rs = selectPopup($conn, $pop_no);

		$rs_pop_no					= trim($arr_rs[0]["POP_NO"]);
		$rs_size_w					= trim($arr_rs[0]["SIZE_W"]);
		$rs_size_h					= SetStringFromDB($arr_rs[0]["SIZE_H"]);
		$rs_title						= SetStringFromDB($arr_rs[0]["TITLE"]);

		$rs_top						= SetStringFromDB($arr_rs[0]["TOP"]);
		$rs_left_						= SetStringFromDB($arr_rs[0]["LEFT_"]);
		$rs_scrollbars				= SetStringFromDB($arr_rs[0]["SCROLLBARS"]);
		$rs_title				= SetStringFromDB($arr_rs[0]["TITLE"]);
		$rs_contents					= SetStringFromDB($arr_rs[0]["CONTENTS"]);
		$rs_s_date					= SetStringFromDB($arr_rs[0]["S_DATE"]);
		$rs_s_time					= SetStringFromDB($arr_rs[0]["S_TIME"]);
		$rs_e_date					= SetStringFromDB($arr_rs[0]["E_DATE"]);
		$rs_e_time					= SetStringFromDB($arr_rs[0]["E_TIME"]);
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]);
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]);
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);

?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$rs_title?></title>
<body leftmargin="0" topmargin="0" bottommargin="0" rightmargin="0">

<?=$rs_contents?>

</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>