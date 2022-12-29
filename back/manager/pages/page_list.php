<?session_start();?>
<?
# =============================================================================
# File Name    : page_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2013.06.05
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
	$menu_right = "CS001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/page/page.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$page_lang					= $_POST['page_lang']!=''?$_POST['page_lang']:$_GET['page_lang'];
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
	
	if ($sel_page_lang == "") $sel_page_lang= "KOR";

	$menu_tf = "";

	$arr_rs = listPage($conn, $sel_page_lang, $menu_tf, $use_tf, $del_tf, $search_field, $search_str);

	#echo sizeof($arr_rs);
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
		var url = "pcode_write.php";
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
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "page_list.php";
		frm.submit();
	}

	function js_sel_page_lang() {
		var frm = document.frm;
		frm.action = "page_list.php";
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
<input type="hidden" name="rn" value="">
<input type="hidden" name="pcode_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="page_cd" value="<?=$page_cd?>" >
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

			<div class="sp0"></div>
			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
				<p class="fRight">
					
					<? if ($_SESSION['s_adm_position_code'] == "") { ?>

					<?= makeSelectBoxOnChange($conn,"LANG","sel_page_lang","125","","",$sel_page_lang)?>&nbsp;&nbsp;

					<? } else { ?>

					<input type="hidden" name="sel_page_lang" id="sel_page_lang" value="<?=$_SESSION['s_adm_position_code']?>">

					<? } ?>

					<!--<input type="hidden" name="sel_page_lang" id="sel_page_lang" value="KOR">-->
				<? if ($sPageRight_I == "Y") {?>
					<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>">1 뎁스메뉴등록</a>&nbsp;&nbsp;
				<? } ?>
				<? if ($sPageRight_U == "Y") {?>
					<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>', 'pop_order_page', '560', '470', 'no');">메뉴순서변경 </a>
				<? } ?>
				</p>

				<table summary="이곳에서 <?=$p_menu_name?> 관리하실 수 있습니다">
					<caption><?=$p_menu_name?></caption>
					<colgroup>
						<col width="30%" />
						<col width="40%" />
						<col width="10%" />
						<col width="20%" />
					</colgroup>
					<thead>
					<tr>
						<th scope="col">메뉴명</th>
						<th scope="col">메뉴URL</th>
						<th scope="col">메뉴노출여부</th>
						<th scope="col">비고</th>
					</tr>
					</thead>
					<tbody>
				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							//PAGE_NO, PAGE_CD, PAGE_NAME, PAGE_URL, PAGE_FLAG, PAGE_SEQ01, PAGE_SEQ02, PAGE_SEQ03, PAGE_RIGHT
							
							$PAGE_NO				= trim($arr_rs[$j]["PAGE_NO"]);
							$PAGE_CD				= trim($arr_rs[$j]["PAGE_CD"]);
							$PAGE_NAME			= setStringFromDB($arr_rs[$j]["PAGE_NAME"]);
							$PAGE_URL				= setStringFromDB($arr_rs[$j]["PAGE_URL"]);
							$PAGE_FLAG			= trim($arr_rs[$j]["PAGE_FLAG"]);
							$PAGE_SEQ01			= trim($arr_rs[$j]["PAGE_SEQ01"]);
							$PAGE_SEQ02			= trim($arr_rs[$j]["PAGE_SEQ02"]);
							$PAGE_SEQ03			= trim($arr_rs[$j]["PAGE_SEQ03"]);
							$PAGE_SEQ04			= trim($arr_rs[$j]["PAGE_SEQ04"]);
							$PAGE_RIGHT			= trim($arr_rs[$j]["PAGE_RIGHT"]);
							$MENU_TF					= trim($arr_rs[$j]["MENU_TF"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

							if (strlen($PAGE_CD) == 2) {
								$menu_str = "<font color='blue'>⊙ ".$PAGE_NAME." (".$PAGE_NO.")</font>";
							} else {
								for ($menuspace = 0 ; $menuspace < strlen($PAGE_CD) ;$menuspace++) {
									$menu_str = $menu_str ."&nbsp;";
								}

								if (strlen($PAGE_CD) == 4) {
									$menu_str = $menu_str ."┗ <font color='navy'>".$PAGE_NAME." (".$PAGE_NO.")</font>";
								} else if (strlen($PAGE_CD) == 6) {
									$menu_str = $menu_str ."&nbsp;&nbsp;┗ <font color='gray'>".$PAGE_NAME." (".$PAGE_NO.")</font>";
								} else if (strlen($PAGE_CD) == 8) {
									$menu_str = $menu_str ."&nbsp;&nbsp;&nbsp;&nbsp;┗ <font color='darkgray'>".$PAGE_NAME." (".$PAGE_NO.")</font>";
								} else if (strlen($PAGE_CD) == 10) {
									$menu_str = $menu_str ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗ <font color='orange'>".$PAGE_NAME." (".$PAGE_NO.")</font>";
								}
							}

				?>
						<tr>
							<td class="tit"><a href="page_write.php?mode=S&m_level=<?=$PAGE_CD?>&page_no=<?=$PAGE_NO?>&sel_page_lang=<?=$sel_page_lang?>"><?=$menu_str?></a></td>
							<td class="tit"><?=$PAGE_URL?></td>
							<td class="tit"><?=$MENU_TF?></td>
							<td>
							<? 
								if ($sPageRight_I == "Y") {
									if (strlen($PAGE_CD) <= 8) {
										if (strlen($PAGE_CD) == 2) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>&m_seq01=<?=$PAGE_SEQ01?>&m_seq02=<?=$PAGE_SEQ02?>">2 뎁스등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										} else if (strlen($PAGE_CD) == 4) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>&m_seq01=<?=$PAGE_SEQ01?>&m_seq02=<?=$PAGE_SEQ02?>&m_seq03=<?=$PAGE_SEQ03?>&m_seq04=<?=$PAGE_SEQ04?>">3 뎁스등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										} else if (strlen($PAGE_CD) == 6) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>&m_seq01=<?=$PAGE_SEQ01?>&m_seq02=<?=$PAGE_SEQ02?>&m_seq03=<?=$PAGE_SEQ03?>&m_seq04=<?=$PAGE_SEQ04?>">4 뎁스등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										} else if (strlen($PAGE_CD) == 8) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="page_write.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>&m_seq01=<?=$PAGE_SEQ01?>&m_seq02=<?=$PAGE_SEQ02?>&m_seq03=<?=$PAGE_SEQ03?>&m_seq04=<?=$PAGE_SEQ04?>">5 뎁스등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_page_order.php?sel_page_lang=<?=$sel_page_lang?>&m_level=<?=$PAGE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
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
			</fieldset>
			</form>

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

	mysql_close($conn);
?>
