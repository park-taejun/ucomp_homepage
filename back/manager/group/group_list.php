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
	$menu_right = "MB007"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/group/group.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$group_kind					= $_POST['group_kind']!=''?$_POST['group_kind']:$_GET['group_kind'];
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
	
	if ($_SESSION['s_adm_dept_code'] != "") $sel_group_kind = "노동자당";

	if ($sel_group_kind == "") $sel_group_kind = "노동자당";

	$menu_tf = "";

	$arr_rs = listGroup($conn, $sel_group_kind, $group_sido, $use_tf, $del_tf, $search_field, $search_str);

	//echo sizeof($arr_rs);
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

<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" >

	$(document).ready(function() {
		$( "#search_str" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});
	});

	function js_write() {
		var url = "group_write.php";
	}

	function js_view(seq) {

		var url = "group_write_popup.php?mode=S&pcode_no="+seq;
		NewWindow(url, '대분류조회', '600', '253', 'NO');
	}
	
	function js_view_dcode(seq) {

		var url = "group_list_popup.php?mode=R&pcode_no="+seq;
		NewWindow(url, '세부분류조회', '600', '650', 'NO');
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "group_list.php";
		frm.submit();
	}

	function js_sel_group_kind() {
		var frm = document.frm;
		frm.action = "group_list.php";
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
<input type="hidden" name="group_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="page_cd" value="<?=$page_cd?>" >
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

			<div class="sp0"></div>
			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>

				<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
				<caption><?=$p_menu_name?> 관리</caption>
					<tbody>
						<tr class="last">
							<th>소속당</th>
							<td>
								<?
									if (($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) {
								?>
								<?= makeSelectBoxOnChange($conn,"PARTY","sel_group_kind", "250","", "",$sel_group_kind);?>
								<?
									} else {
								?>
								<?=getDcodeName($conn, "PARTY", $_SESSION['s_adm_dept_code'])?>
								<input type="hidden" name="sel_party" value="<?=$_SESSION['s_adm_dept_code']?>">
								<?
									}
								?>
							</td>
							<th>검색조건</th>
							<td>
								<select name="search_field" style="width:84px;">
									<option value="GROUP_NAME" <? if ($search_field == "GROUP_NAME") echo "selected"; ?> >조직명</option>
								</select>&nbsp;

								<input type="text" value="<?=$search_str?>" name="search_str" id="search_str" size="15"class="txt" />
								<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="expArea">
					<ul class="fLeft">
						<li class="total">총 <?=number_format(sizeof($arr_rs))?>건</li>
					</ul>
					<p class="fRight">
						<? if ($sPageRight_I == "Y") { ?>
						<input type="button" name="aa" value=" 1 뎁스 메뉴 등록 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="NewWindow('pop_group_write.php?group_kind=<?=$sel_group_kind?>', 'pop_write_group', '560', '330', 'no');">
						<? } ?>
						<? if ($sPageRight_U == "Y") { ?>
						<input type="button" name="bb" value=" 1 뎁스 순서 변경 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="NewWindow('pop_group_order.php?group_kind=<?=$sel_group_kind?>', 'pop_order_group', '560', '470', 'no');">
						<? } ?>
					</p>
				</div>

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
						<th scope="col">조직명</th>
						<th scope="col">인원</th>
						<th scope="col">노출여부</th>
						<th scope="col">비고</th>
					</tr>
					</thead>
					<tbody>
				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							$GROUP_NO				= trim($arr_rs[$j]["GROUP_NO"]);
							$GROUP_CD				= trim($arr_rs[$j]["GROUP_CD"]);
							$GROUP_NAME			= setStringFromDB($arr_rs[$j]["GROUP_NAME"]);
							$GROUP_SEQ01		= trim($arr_rs[$j]["GROUP_SEQ01"]);
							$GROUP_SEQ02		= trim($arr_rs[$j]["GROUP_SEQ02"]);
							$GROUP_SEQ03		= trim($arr_rs[$j]["GROUP_SEQ03"]);
							$GROUP_SEQ04		= trim($arr_rs[$j]["GROUP_SEQ04"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

							if (strlen($GROUP_CD) == 3) {
								$menu_str = "<font color='blue'>⊙ ".$GROUP_NAME."</font>";
							} else {
								for ($menuspace = 0 ; $menuspace < strlen($PAGE_CD) ;$menuspace++) {
									$menu_str = $menu_str ."&nbsp;";
								}

								if (strlen($GROUP_CD) == 6) {
									$menu_str = $menu_str ."<span style='padding-left:10px'></span>┗ <font color='navy'>".$GROUP_NAME."</font>";
								} else if (strlen($GROUP_CD) == 9) {
									$menu_str = $menu_str ."<span style='padding-left:30px'></span>┗ <font color='gray'>".$GROUP_NAME."</font>";
								} else if (strlen($GROUP_CD) == 12) {
									$menu_str = $menu_str ."<span style='padding-left:60px'></span>┗ <font color='darkgray'>".$GROUP_NAME."</font>";
								} else if (strlen($GROUP_CD) == 15) {
									$menu_str = $menu_str ."<span style='padding-left:90px'></span>┗ <font color='orange'>".$GROUP_NAME."</font>";
								}
							}

				?>
						<tr>
							<td class="tit"><a href="javascript:NewWindow('pop_group_write.php?mode=S&m_level=<?=$GROUP_CD?>&group_no=<?=$GROUP_NO?>&group_kind=<?=$sel_group_kind?>', 'pop_write_group', '560', '330', 'no');"><?=$menu_str?></a></td>
							<td><?=$GROUP_CNT?></td>
							<td><?=$USE_TF?></td>
							<td>
							<? 
								if ($sPageRight_I == "Y") {
									if (strlen($GROUP_CD) <= 12) {
										if (strlen($GROUP_CD) == 3) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<input type="button" name="aa" value=" 2 뎁스 메뉴 등록 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;background-color:#EFEFEF;" onclick="NewWindow('pop_group_write.php?group_kind=<?=$sel_group_kind?>&m_level=<?=$GROUP_CD?>&m_seq01=<?=$GROUP_SEQ01?>&m_seq02=<?=$GROUP_SEQ02?>', 'pop_write_group', '560', '330', 'no');">&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<input type="button" name="bb" value=" 2 뎁스 순서 변경 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;background-color:#EFEFEF;" onclick="NewWindow('pop_group_order.php?group_kind=<?=$sel_group_kind?>&m_level=<?=$GROUP_CD?>', 'pop_order_group', '560', '470', 'no');">
							<? } ?>
							<?
										} else if (strlen($GROUP_CD) == 6) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<input type="button" name="aa" value=" 3 뎁스 메뉴 등록 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="NewWindow('pop_group_write.php?group_kind=<?=$sel_group_kind?>&m_level=<?=$GROUP_CD?>&m_seq01=<?=$GROUP_SEQ01?>&m_seq02=<?=$GROUP_SEQ02?>&m_seq03=<?=$GROUP_SEQ03?>&m_seq04=<?=$GROUP_SEQ04?>', 'pop_write_group', '560', '330', 'no');">&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<input type="button" name="bb" value=" 3 뎁스 순서 변경 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="NewWindow('pop_group_order.php?group_kind=<?=$sel_group_kind?>&m_level=<?=$GROUP_CD?>', 'pop_order_group', '560', '470', 'no');">
							<? } ?>
							<?
										} else if (strlen($GROUP_CD) == 9) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<input type="button" name="aa" value=" 4 뎁스 메뉴 등록 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="NewWindow('pop_group_write.php?group_kind=<?=$sel_group_kind?>&m_level=<?=$GROUP_CD?>&m_seq01=<?=$GROUP_SEQ01?>&m_seq02=<?=$GROUP_SEQ02?>&m_seq03=<?=$GROUP_SEQ03?>&m_seq04=<?=$GROUP_SEQ04?>', 'pop_write_group', '560', '330', 'no');">&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<input type="button" name="bb" value=" 4 뎁스 순서 변경 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="NewWindow('pop_group_order.php?group_kind=<?=$sel_group_kind?>&m_level=<?=$GROUP_CD?>', 'pop_order_group', '560', '470', 'no');">
							<? } ?>
							<?
										} else if (strlen($GROUP_CD) == 12) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<input type="button" name="aa" value=" 5 뎁스 메뉴 등록 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="NewWindow('pop_group_write.php?group_kind=<?=$sel_group_kind?>&m_level=<?=$GROUP_CD?>&m_seq01=<?=$GROUP_SEQ01?>&m_seq02=<?=$GROUP_SEQ02?>&m_seq03=<?=$GROUP_SEQ03?>&m_seq04=<?=$GROUP_SEQ04?>', 'pop_write_group', '560', '330', 'no');">&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<input type="button" name="bb" value=" 5 뎁스 순서 변경 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="NewWindow('pop_group_order.php?group_kind=<?=$sel_group_kind?>&m_level=<?=$GROUP_CD?>', 'pop_order_group', '560', '470', 'no');">
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
