<?session_start();?>
<?
# =============================================================================
# File Name    : branch_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2016-12-07
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "BA001"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/branch/branch.php";

#====================================================================
# Request Parameter
#====================================================================

	$result = false;
	
	$branch_lang				= $_POST['branch_lang']!=''?$_POST['branch_lang']:$_GET['branch_lang'];
	$branch_type				= $_POST['branch_type']!=''?$_POST['branch_type']:$_GET['branch_type'];
	$group_tf						= $_POST['group_tf']!=''?$_POST['group_tf']:$_GET['group_tf'];
	$logo_img01					= $_POST['logo_img01']!=''?$_POST['logo_img01']:$_GET['logo_img01'];
	$logo_img02					= $_POST['logo_img02']!=''?$_POST['logo_img02']:$_GET['logo_img02'];
	$logo_img03					= $_POST['logo_img03']!=''?$_POST['logo_img03']:$_GET['logo_img03'];
	$logo_img04					= $_POST['logo_img04']!=''?$_POST['logo_img04']:$_GET['logo_img04'];
	$facebook_id				= $_POST['facebook_id']!=''?$_POST['facebook_id']:$_GET['facebook_id'];
	$main_board_id			= $_POST['main_board_id']!=''?$_POST['main_board_id']:$_GET['main_board_id'];
	$post_no						= $_POST['post_no']!=''?$_POST['post_no']:$_GET['post_no'];
	$addr								= $_POST['addr']!=''?$_POST['addr']:$_GET['addr'];
	$doroaddr						= $_POST['doroaddr']!=''?$_POST['doroaddr']:$_GET['doroaddr'];
	$map_url						= $_POST['map_url']!=''?$_POST['map_url']:$_GET['map_url'];
	$phone							= $_POST['phone']!=''?$_POST['phone']:$_GET['phone'];
	$fax								= $_POST['fax']!=''?$_POST['fax']:$_GET['fax'];
	$email							= $_POST['email']!=''?$_POST['email']:$_GET['email'];

#===============================================================
# Get Search list count
#===============================================================


	$arr_rs = listBranchInfoList($conn);
	
	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "사이트 관리 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
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
<script type="text/javascript" >


	function js_write() {

		var frm = document.frm; 

		frm.target = "";
		frm.method = "post";
		frm.action = "branch_write.php";
		frm.submit();

	}


	function js_view(branch_lang) {

		var frm = document.frm; 
		frm.branch_lang.value = branch_lang;
		frm.mode.value = "S";
		frm.action = "branch_write.php";
		frm.submit();

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

		<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
		<input type="hidden" name="branch_lang" value="">
		<input type="hidden" name="mode" value="">

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>

				<table summary="이곳에서 메인 페이지의 배너를 관리하실 수 있습니다" class="secBtm">
					<colgroup>
						<col width="20%" />
						<col width="10%" />
						<col width="10%" />
						<col width="20%" />
						<col width="20%" />
						<col width="20%" />
					</colgroup>
					<thead>
						<tr>
							<th>사이트명</th>
							<th>사이트구분</th>
							<th>모임사용</th>
							<th>로고이미지 (메인)</th>
							<th>로고이미지 (하단)</th>
							<th>로고이미지 (모바일)</th>
						</tr>
					</thead>
					<tbody>

				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							#B.DCODE_NM, B.DCODE, A.BRANCH_TYPE, LOGO_IMG01, LOGO_IMG02, LOGO_IMG03

							$DCODE_NM			= trim($arr_rs[$j]["DCODE_NM"]);
							$DCODE				= trim($arr_rs[$j]["DCODE"]);
							$BRANCH_LANG	= trim($arr_rs[$j]["BRANCH_LANG"]);
							$BRANCH_TYPE	= trim($arr_rs[$j]["BRANCH_TYPE"]);
							$GROUP_TF			= trim($arr_rs[$j]["GROUP_TF"]);
							$LOGO_IMG01		= trim($arr_rs[$j]["LOGO_IMG01"]);
							$LOGO_IMG02		= trim($arr_rs[$j]["LOGO_IMG02"]);
							$LOGO_IMG03		= trim($arr_rs[$j]["LOGO_IMG03"]);

				?>
						<tr>
							<td class="pname">
								<a href="javascript:js_view('<?=$BRANCH_LANG?>');"><?=$DCODE_NM?></a>
							</td>
							<td>
								<?=getDcodeName($conn,"BRANCH_TYPE",$BRANCH_TYPE)?>
							</td>
							<td>
								<? if ($GROUP_TF == "Y") {  echo "사용함"; } else { echo "사용안함"; } ?>
							</td>
							<td class="filedown">
								<? if ($LOGO_IMG01 <> "") { ?>
								<img src="<?=$g_base_dir?>/upload_data/branch/<?=$LOGO_IMG01?>" style="background-color:darkorange;width:100px">
								<? } else { ?>
								&nbsp;
								<? } ?>
							</td>
							<td class="filedown">
								<? if ($LOGO_IMG02 <> "") { ?>
								<img src="<?=$g_base_dir?>/upload_data/branch/<?=$LOGO_IMG02?>" style="width:100px">
								<? } else { ?>
								&nbsp;
								<? } ?>
							</td>
							<td class="filedown">
								<? if ($LOGO_IMG03 <> "") { ?>
								<img src="<?=$g_base_dir?>/upload_data/branch/<?=$LOGO_IMG03?>" style="width:100px">
								<? } else { ?>
								&nbsp;
								<? } ?>
							</td>
						</tr>
				<?
						}
					} else { 
				?> 
						<tr>
							<td align="center" height="50" colspan="7">데이터가 없습니다. </td>
						</tr>
				<? 
					}
				?>
					</tbody>
				</table>
			</fieldset>
</form>
		</section>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<script type="text/javascript" src="../js/wrest.js"></script>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>