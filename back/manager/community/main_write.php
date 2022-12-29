<?session_start();?>
<?
# =============================================================================
# File Name    : comm_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2015.05.21
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================

	require "../../../_common/config.php";
	require "../../../_classes/community/util/util.php";
	require "../../../_classes/community/etc/etc.php";
	require "../../../_classes/community/ccommunity/community.php";
	

	if ($mode == "") $mode			= "S";
#====================================================================
# Request Parameter
#====================================================================

$c_level = Trim($c_level);
$c_seq01 = Trim($c_seq01);
$c_seq02 = Trim($c_seq02);

//echo $c_level."<br>";
//echo $c_seq01."<br>";
//echo $c_seq02."<br>";

#====================================================================
# Declare variables
#====================================================================

#====================================================================
# Request Parameter
#====================================================================

$comm_no					= trim($comm_no);
$c_level					= trim($c_level);
$c_seq01					= trim($c_seq01);
$c_seq02					= trim($c_seq02);
$comm_cd					= trim($comm_cd);
$comm_name				= trim($comm_name);
$comm_domain			= trim($comm_domain);
$comm_type				= trim($comm_type);
$comm_addr				= trim($comm_addr);
$comm_phone				= trim($comm_phone);
$comm_fax					= trim($comm_fax);
$comm_email				= trim($comm_email);
$comm_flag				= trim($comm_flag);
$comm_title_img		= trim($comm_title_img);
$comm_footer_img	= trim($comm_footer_img);
$comm_img					= trim($comm_img);
$comm_img_over		= trim($comm_img_over);

//echo $m_level;

$result = false;
#====================================================================
# DML Process
#====================================================================
	
#====================================================================
	$savedir1 = $g_physical_path."upload_data/community";
#====================================================================
	
	if ($mode == "I") {
		
		$comm_title_img		= upload($_FILES[comm_title_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$comm_footer_img	= upload($_FILES[comm_footer_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$comm_img					= upload($_FILES[comm_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$comm_img_over		= upload($_FILES[comm_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		
		//$file_rnm					= $_FILES[menu_img][name];

		$new_comm_no = insertCcommunity($conn, $c_level, $c_seq01, $c_seq02, $comm_name, $comm_domain, $comm_type, $comm_addr, $comm_phone, $comm_fax, $comm_email, $comm_flag, $comm_title_img, $comm_footer_img, $comm_img, $comm_img_over, $use_tf, $s_comm_adm_no);

		$result = make_branch_file($new_comm_no, $comm_domain, $comm_name);

	}


	if ($mode == "S") {

		$arr_rs = selectCcommunity($conn, $comm_no);

		$rs_comm_no					= trim($arr_rs[0]["COMM_NO"]); 
		$rs_comm_cd					= trim($arr_rs[0]["COMM_CD"]); 
		$rs_comm_domain			= trim($arr_rs[0]["COMM_DOMAIN"]); 
		$rs_comm_type				= trim($arr_rs[0]["COMM_TYPE"]); 
		$rs_comm_name				= trim($arr_rs[0]["COMM_NAME"]); 
		$rs_comm_addr				= trim($arr_rs[0]["COMM_ADDR"]); 
		$rs_comm_phone			= trim($arr_rs[0]["COMM_PHONE"]); 
		$rs_comm_fax				= trim($arr_rs[0]["COMM_FAX"]); 
		$rs_comm_email			= trim($arr_rs[0]["COMM_EMAIL"]); 
		$rs_comm_seq01			= trim($arr_rs[0]["COMM_SEQ01"]); 
		$rs_comm_seq02			= trim($arr_rs[0]["COMM_SEQ02"]); 
		$rs_comm_seq03			= trim($arr_rs[0]["COMM_SEQ03"]); 
		$rs_comm_flag				= trim($arr_rs[0]["COMM_FLAG"]); 
		$rs_comm_title_img	= trim($arr_rs[0]["COMM_TITLE_IMG"]); 
		$rs_comm_footer_img	= trim($arr_rs[0]["COMM_FOOTER_IMG"]); 
		$rs_comm_img				= trim($arr_rs[0]["COMM_IMG"]); 
		$rs_comm_img_over		= trim($arr_rs[0]["COMM_IMG_OVER"]); 
		$rs_str_lat					= trim($arr_rs[0]["STR_LAT"]);
		$rs_str_lng					= trim($arr_rs[0]["STR_LNG"]);
		$rs_tweeter					= trim($arr_rs[0]["TWEETER"]);
		$rs_facebook				= trim($arr_rs[0]["FACEBOOK"]);
		$rs_meto						= trim($arr_rs[0]["METO"]);
		$rs_yozm						= trim($arr_rs[0]["YOZM"]);
		$rs_main_style			= trim($arr_rs[0]["MAIN_STYLE"]);
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 

	}

	if ($mode == "U") {
		$result = updateCcommunityMainStyle($conn, $main_style, $comm_no);
	}

#=================================================================
# Get Result set from stored procedure
#=================================================================
	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&menu_cd=".$menu_cd;
?>	
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
	alert('정상 처리 되었습니다.');
	document.location.href = "main_write.php<?=$strParam?>";
</script>
<?
		exit;
	}	
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
<link href="../css/common.css" rel="stylesheet" />

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

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript">

	function js_save() {
		
		var comm_no = "<?= $comm_no ?>";
		var frm = document.frm;

		if (isNull(comm_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}

		//alert(frm.mode.value);

		frm.submit();
	}

	function js_change_style(idx) {
		document.getElementById("style_img").src = "/branch/manager/images/style"+idx+".png";
		document.getElementById("main_style").value = idx;
	}

</script>
</head>
<body>

<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2><?=$p_parent_menu_name?></h2>
		</div>
		<section class="conBox">

			<h3 class="conTitle"><?=$p_menu_name?></h3>

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" id="mode" value="">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>" />
<input type="hidden" name="comm_no" value="<?=$comm_no?>" />

			<table summary="이곳에서 내용을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
					<thead>
						<tr>
							<th scope="row">커뮤니티명</th>
							<td colspan="3">
								<?=$rs_comm_name?>
								<input type="hidden" name="comm_name" id="comm_name" value="<?= $rs_comm_name ?>" />
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">커뮤니티구분</th>
							<td colspan="3">
								<?= getDcodeName($conn, 'COMM_TYPE',$rs_comm_type);?>
								<input type="hidden" name="comm_type" value="<?= $rs_comm_type ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">커뮤니티도메인</th>
							<td colspan="3">
								<?= $rs_comm_domain ?>.goupp.org
								<input type="hidden" name="comm_domain" id="comm_domain" value="<?= $rs_comm_domain ?>" >
								<input type="hidden" name="old_comm_domain" id="old_comm_domain" value="<?= $rs_comm_domain ?>">
							</td>
						</tr>

						<tr>
							<th scope="row">메인 Layout</th>
							<td colspan="3" valign="top">

								<input type="radio" class="radio" name="rd_main_style" value="1" onClick="js_change_style('1');" <? if ($rs_main_style == "1") echo " checked"; ?>> 타입 1
								<input type="radio" class="radio" name="rd_main_style" value="2" onClick="js_change_style('2');" <? if ($rs_main_style == "2") echo " checked"; ?>> 타입 2
								<input type="radio" class="radio" name="rd_main_style" value="3" onClick="js_change_style('3');" <? if ($rs_main_style == "3") echo " checked"; ?>> 타입 3 <br>
								<img id="style_img" src="/branch/manager/images/style<?=$rs_main_style?>.png" width="500">
								<input type="hidden" name="main_style" id="main_style" value="<?=$rs_main_style?>">
							</td>
						</tr>
					</tbody>
				</table>
			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fRight">
					<li><a href="javascript:js_save();"><img src="../images/btn/btn_rewrite.gif" alt="수정" /></a></li>
				</ul>
			</div>
		</section>
	</section>
</section>
</div><!--wrapper-->
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>
