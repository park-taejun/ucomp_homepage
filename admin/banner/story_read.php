<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : equipment_read.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-10-16
# Modify Date  : 
#	Copyright    : Copyright @Ucom Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "MB001"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/banner/banner.php";

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	
	if ($mode == "S") {
		$arr_rs = selectBanner($conn, $banner_no);
		 
		$rs_banner_no				= trim($arr_rs[0]["BANNER_NO"]); 
		$rs_banner_type				= trim($arr_rs[0]["BANNER_TYPE"]); 
		$rs_banner_nm				= trim($arr_rs[0]["BANNER_NM"]); 		
		$rs_banner_url				= trim($arr_rs[0]["BANNER_URL"]); 
		$rs_title_nm				= trim($arr_rs[0]["TITLE_NM"]); 
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		$rs_banner_img				= trim($arr_rs[0]["BANNER_IMG"]); 
		
		if ($rs_use_tf == "Y") {
			$STR_USE_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_USE_TF = "<font color='red'>사용안함</font>";
		}
		   
	}

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript">

	function js_list() {
		var frm = document.frm;
			
		frm.method = "post";
		frm.action = "banner_list.php";
		frm.submit();
	}

	function js_modify() {
		var frm = document.frm;

		frm.mode.value = "S";
		frm.method = "post";
		frm.action = "banner_modify.php";
		frm.submit();
	}

</script>
</head>
<body>

<div id="wrap">
<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>
	<!-- S: container -->
	<div class="container">
		<div class="contentsarea">
			<div class="menu-holiday">
				<h3><strong><?=$p_menu_name?></strong>
				</h3>

<form name="frm" method="post">
<input type="hidden" name="banner_no" value="<?=$rs_banner_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="con_eq_type" value="<?=$con_eq_type?>">
<input type="hidden" name="con_eq_user" value="<?=$con_eq_user?>">
<input type="hidden" name="con_eq_state" value="<?=$con_eq_state?>">
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="boardlist search">
				
					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:24%" />
						</colgroup>
						<tbody>
							<!--
							<tr>
								<th>배너타입</th>
								<td colspan="5" class="subject">
									<?= getDcodeName($conn, "BANNER", $rs_banner_type); ?>
								</td>								 
							</tr>
							-->
							<tr>
								<th>제목</th>
								<td colspan="5" class="subject"><?=$rs_banner_nm?></td>								
							</tr> 
							<tr>
								<th>순번</th>
								<td colspan="5" class="subject"><?=nl2br($rs_disp_seq)?></td>
							</tr>
							<tr>
								<th>사용여부</th>
								<td colspan="5" class="subject"><?=nl2br($STR_USE_TF)?></td>
							</tr>
							<tr>
								<th>이미지</th>
								<td colspan="5" class="subject"><?=nl2br($rs_banner_img)?></td>
							</tr>
							<tr>
								<th>타이틀</th>
								<td colspan="5" class="subject"><?=nl2br($rs_title_nm)?></td>
							</tr>
							<tr>
								<th>URL</th>
								<td colspan="5" class="subject"><?=nl2br($rs_banner_url)?></td>
							</tr> 
						</tbody>
					</table>
				</div>
				<div class="btnright">
				<? if ($rs_banner_no <> "" ) {?>
					<? //if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_modify();" style="width:100px">수정</button>
					<? //} ?>
				<? }?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>
  
			<!-- // E: mwidthwrap -->

			</div>
		</div>
	</div>

	<!-- //S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>