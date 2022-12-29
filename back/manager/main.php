<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : main.php
# Modlue       : 
# Writer       : chingong
# Create Date  : 2012-08-22
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================

	//echo $_COOKIE['s_adm_no'];


	if ($_SESSION['s_adm_no'] == "") {

		$next_url = "./login.php";

?>
<meta http-equiv='Refresh' content='0; URL=<?=$next_url?>'>
<?
			exit;
	}


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
	require "../_classes/biz/admin/admin.php";
#====================================================================
# Request Parameter
#====================================================================

#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/common_header.php"; 

	$s_adm_nm			= protect_xss_v2($s_adm_nm);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_adm_nm?> 관리자 로그인</title>
<link href="./css/common.css" rel="stylesheet" />
<script src="./js/common.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>


<body>
<div class="wrapper">
<section id="container">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../_common/left_area.php";
?>
	
	
	<section class="conRight">
		
<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../_common/top_area.php";
?>

		<div class="conTit">
			<h2>관리자 페이지 메인</h2>
		</div>
		
		<section class="conBox">
			<dl class="main">

			<?
				if (sizeof($arr_rs_menu) > 0) {
					for ($m = 0 ; $m < sizeof($arr_rs_menu); $m++) {
			
						$M_MENU_CD		= trim($arr_rs_menu[$m]["MENU_CD"]);
						$M_MENU_NAME	= trim($arr_rs_menu[$m]["MENU_NAME"]);
						$M_ADMIN_URL	= trim($arr_rs_menu[$m]["MENU_URL"]);

						if (strpos($M_ADMIN_URL, "?") > 0) {
							$str_menu_url = $M_ADMIN_URL."&menu_cd=".$M_MENU_CD;
						} else {
							$str_menu_url = $M_ADMIN_URL."?menu_cd=".$M_MENU_CD;
						}

						if (strlen($M_MENU_CD) == "2") {
							if ($m <> 0) {
			?>
					</ul>
				</dd>
			<?
							}
			?>
				<dt><?=$M_MENU_NAME?></dt>
				<dd>
					<ul>
			<?
						}
						if ((strlen($M_MENU_CD) == "4") && ($M_ADMIN_URL <> "#") && ($M_ADMIN_URL <> "")) {
			?>
						<li><a href="<?=$str_menu_url?>"><?=$M_MENU_NAME?></a></li>
			<?
						}
					}
				}
			?>

			</dl>
		</section>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================
	echo $_SERVER[DOCUMENT_ROOT];
	mysql_close($conn);
?>