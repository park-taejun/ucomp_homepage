<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : menu_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-12-12
# Modify Date  : 
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");  

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "HD003"; // 메뉴마다 셋팅 해 주어야 합니다	
	// $site_add = $_SERVER['SERVER_SELF'];
	
#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

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
	require "../../_classes/biz/menu/menu.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$order_field				= $_POST['order_field']!=''?$_POST['order_field']:$_GET['order_field'];
	$order_str					= $_POST['order_str']!=''?$_POST['order_str']:$_GET['order_str'];

	#List Parameter

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$order_field		= SetStringToDB($order_field);
	$order_str			= SetStringToDB($order_str);

	$use_tf			= SetStringToDB($use_tf);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

#===============================================================
# Get Search list count
#===============================================================

	$arr_rs = listAdminMenu($conn, $use_tf, $del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 메뉴 조회", "List");

	#echo sizeof($arr_rs);
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript" >


	function js_write() {


		var url = "pcode_write_popup.php";

		NewWindow(url, '대분류등록', '600', '353', 'NO');
	}

	function js_view(rn, seq) {

		var url = "pcode_write_popup.php?mode=S&pcode_no="+seq;
		NewWindow(url, '대분류조회', '600', '353', 'NO');
	}
	
	function js_view_dcode(rn, seq) {

		var url = "dcode_list_popup.php?mode=R&pcode_no="+seq;
		NewWindow(url, '세부분류조회', '600', '650', 'NO');
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		// frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "/admin/menu/menu_list.php";
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
				<h3><strong><?=$p_menu_name?></strong></h3>
				<div class="boardlist search">

<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="pcode_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>" >
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

					<div class="btnright">
						<? if ($sPageRight_I == "Y") {?>
						<button type="button" class="btn-gray" onClick="NewWindow('pop_menu_write.php', 'pop_add_menu', '560', '285', 'no');" style="width:100px" >대분류등록</button>
						<? } ?>
						<? if ($sPageRight_U == "Y") {?>
						<button type="button" class="btn-gray" onClick="NewWindow('pop_menu_order.php', 'pop_order_menu', '560', '470', 'no');" style="width:100px" >메뉴순서변경</button>
						<? } ?>
					</div>
					<div class="sp10"></div>
					
					<div class="boardlist">

						<table>
							<colgroup>
								<col style="width:30%" />
								<col style="width:auto" />
								<col style="width:10%" />
								<col style="width:20%" />
							</colgroup>
						<thead>
							<tr>
								<th scope="col">메뉴명</th>
								<th scope="col">메뉴URL</th>
								<th scope="col">권한코드</th>
								<th scope="col">비고</th>
							</tr>
						</thead>
						<tbody>
						<?
							$nCnt = 0;
							
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									//MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_FLAG, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_RIGHT
									
									$MENU_NO				= trim($arr_rs[$j]["MENU_NO"]);
									$MENU_CD				= trim($arr_rs[$j]["MENU_CD"]);
									$MENU_NAME			= trim($arr_rs[$j]["MENU_NAME"]);
									$MENU_URL				= trim($arr_rs[$j]["MENU_URL"]);
									$MENU_FLAG			= trim($arr_rs[$j]["MENU_FLAG"]);
									$MENU_SEQ01			= trim($arr_rs[$j]["MENU_SEQ01"]);
									$MENU_SEQ02			= trim($arr_rs[$j]["MENU_SEQ02"]);
									$MENU_SEQ03			= trim($arr_rs[$j]["MENU_SEQ03"]);
									$MENU_RIGHT			= trim($arr_rs[$j]["MENU_RIGHT"]);
									$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

									if (strlen($MENU_CD) == 2) {
										$menu_str = "<font color='blue'>⊙ ".$MENU_NAME."</font>";
									} else {
										for ($menuspace = 0 ; $menuspace < strlen($MENU_CD) ;$menuspace++) {
											$menu_str = $menu_str ."&nbsp;";
										}

										if (strlen($MENU_CD) == 4) {
											$menu_str = $menu_str ."┗ <font color='navy'>".$MENU_NAME."</font>";
										} else if (strlen($MENU_CD) == 6) {
											$menu_str = $menu_str ."&nbsp;&nbsp;┗ <font color='gray'>".$MENU_NAME."</font>";
										}
									}
									
						?>
							<tr>
								<td><a href="javascript:NewWindow('pop_menu_write.php?mode=S&m_level=<?=$MENU_CD?>&menu_no=<?=$MENU_NO?>', 'pop_modify_menu', '560', '285', 'no');"><?=$menu_str?></a></td>
								<td><?=$MENU_URL?></td>
								<td><?= $MENU_RIGHT ?></td>
								<td class="text-center">
							<? 
								if ($sPageRight_I == "Y") {
									if (strlen($MENU_CD) <= 4) {
										if (strlen($MENU_CD) == 2) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<button type="button" class="btn-gray" onClick="NewWindow('pop_menu_write.php?m_level=<?=$MENU_CD?>&m_seq01=<?=$MENU_SEQ01?>&m_seq02=<?=$MENU_SEQ02?>', 'pop_add_menu', '560', '285', 'no');" style="width:100px" >중분류등록!</button>
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<button type="button" class="btn-gray" onClick="NewWindow('pop_menu_order.php?m_level=<?=$MENU_CD?>', 'pop_order_menu', '560', '470', 'no');" style="width:100px" >순서변경</button>
							<? } ?>
							<?
										} else {
							?>
							<? if ($sPageRight_I == "Yzzzz") {?>
							<button type="button" class="btn-gray" onClick="NewWindow('pop_menu_write.php?m_level=<?=$MENU_CD?>&m_seq01=<?=$MENU_SEQ01?>&m_seq02=<?=$MENU_SEQ02?>', 'pop_add_menu', '560', '285', 'no');" style="width:100px" >소분류등록</button>
							<? } ?>
							<? if ($sPageRight_U == "Yzzzz") {?>
							<button type="button" class="btn-gray" onClick="NewWindow('pop_menu_order.php?m_level=<?=$MENU_CD?>', 'pop_order_menu', '560', '470', 'no');" style="width:100px" >순서변경</button>
							<? } ?>
							<?
										}
									}
									echo "&nbsp;";
								} else {
									echo "&nbsp;";
								}
							?>
								</td>
							</tr>
						<?			
									$menu_str = "";
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
				</div>
</form>
			</div>
		</div>
	</div>

<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/admin/js/common_ui.js"></script>
<script>

</script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>