<?session_start();?>
<?
# =============================================================================
# File Name    : category_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright : Copyright @C&C Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "GD002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/category/category.php";

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

#===============================================================
# Get Search list count
#===============================================================

	$arr_rs = listCategory($conn, $con_cate, $use_tf, $del_tf, $search_field, $search_str);

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
<script type="text/javascript" src="../js/goods_common.js"></script>
<script type="text/javascript" >


	function js_write() {


		var url = "pcode_write_popup.php";

		NewWindow(url, '대분류등록', '600', '553', 'NO');
	}

	function js_view(rn, seq) {

		var url = "pcode_write_popup.php?mode=S&pcode_no="+seq;
		NewWindow(url, '대분류조회', '600', '553', 'NO');
	}
	
	function js_view_dcode(rn, seq) {

		var url = "dcode_list_popup.php?mode=R&pcode_no="+seq;
		NewWindow(url, '세부분류조회', '600', '650', 'NO');
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		/*
		frm.con_cate.value = "";

		if (frm.gd_cate_01 != null) {
			if (frm.gd_cate_01.value != "") {
				frm.con_cate.value = frm.gd_cate_01.value;
			}
		}

		if (frm.gd_cate_02 != null) {
			if (frm.gd_cate_02.value != "") {
				frm.con_cate.value = frm.gd_cate_02.value;
			}
		}

		if (frm.gd_cate_03 != null) {
			if (frm.gd_cate_03.value != "") {
				frm.con_cate.value = frm.gd_cate_03.value;
			}
		}

		if (frm.gd_cate_04 != null) {
			if (frm.gd_cate_04.value != "") {
				frm.con_cate.value = frm.gd_cate_04.value;
			}
		}
		*/
		frm.nPage.value = "1";
		frm.target = "";
		frm.method = "get";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_excel() {
		
		//alert("준비중 입니다..");
		//return;

		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER[PHP_SELF])?>";
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
<input type="hidden" name="depth" value="" />
<input type="hidden" name="pcode_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<p class="fRight">
					<? if ($sPageRight_I == "Y") {?>
						<a href="javascript:NewWindow('pop_category_write.php', 'pop_add_menu', '840', '685', 'no');">대표분류등록</a>&nbsp;&nbsp;
					<? } ?>
					<? if ($sPageRight_U == "Y") {?>
						<a href="javascript:NewWindow('pop_category_order.php', 'pop_order_menu', '560', '470', 'no');">순서변경 </a>
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
							<th scope="col">카테고리명</th>
							<th scope="col">카테고리코드</th>
							<th scope="col">카테고리설명</th>
							<th scope="col">비고</th>
						</tr>
						</thead>
						<tbody>

				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							//category_NO, category_CD, category_NAME, category_URL, category_FLAG, category_SEQ01, category_SEQ02, category_SEQ03, category_RIGHT
							
							$CATE_NO				= trim($arr_rs[$j]["CATE_NO"]);
							$CATE_CD				= trim($arr_rs[$j]["CATE_CD"]);
							$CATE_NAME			= trim($arr_rs[$j]["CATE_NAME"]);
							$CATE_MEMO			= trim($arr_rs[$j]["CATE_MEMU"]);
							$CATE_FLAG			= trim($arr_rs[$j]["CATE_FLAG"]);
							$CATE_SEQ01			= trim($arr_rs[$j]["CATE_SEQ01"]);
							$CATE_SEQ02			= trim($arr_rs[$j]["CATE_SEQ02"]);
							$CATE_SEQ03			= trim($arr_rs[$j]["CATE_SEQ03"]);
							$CATE_SEQ04			= trim($arr_rs[$j]["CATE_SEQ04"]);
							$CATE_CODE			= trim($arr_rs[$j]["CATE_CODE"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

							if (strlen($CATE_CD) == 2) {
								$cate_str = "<font color='blue'>⊙ ".$CATE_NAME."</font>";
							} else {
								for ($menuspace = 0 ; $menuspace < strlen($CATE_CD) ;$menuspace++) {
									$cate_str = $cate_str ."&nbsp;";
								}

								if (strlen($CATE_CD) == 4) {
									$cate_str = $cate_str ."┗ <font color='navy'>".$CATE_NAME."</font>";
								} else if (strlen($CATE_CD) == 6) {
									$cate_str = $cate_str ."&nbsp;&nbsp;┗ <font color='gray'>".$CATE_NAME."</font>";
								} else if (strlen($CATE_CD) == 8) {
									$cate_str = $cate_str ."&nbsp;&nbsp;&nbsp;┗ <font color='gray'>".$CATE_NAME."</font>";
								}
							}

				?>
					<tr>
						<td class="tit"><a href="javascript:NewWindow('pop_category_write.php?mode=S&m_level=<?=$CATE_CD?>&cate_no=<?=$CATE_NO?>', 'pop_modify_menu', '840', '685', 'no');"><?=$cate_str?></a></td>
						<td class="tit"><a href="javascript:NewWindow('pop_category_write.php?mode=S&m_level=<?=$CATE_CD?>&cate_no=<?=$CATE_NO?>', 'pop_modify_menu', '860', '685', 'no');"><?= $CATE_CD ?></a></td>
						<td class="tit"><?=$CATE_MEMU?></td>
						<td>
							<? 
								if ($sPageRight_I == "Y") {
									if (strlen($CATE_CD) <= 6) {
										if (strlen($CATE_CD) == 2) {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="javascript:NewWindow('pop_category_write.php?m_level=<?=$CATE_CD?>&m_seq01=<?=$CATE_SEQ01?>&m_seq02=<?=$CATE_SEQ02?>&m_seq03=<?=$CATE_SEQ03?>', 'pop_add_menu', '840', '685', 'no');">대분류등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_category_order.php?m_level=<?=$CATE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										} else if (strlen($CATE_CD) == 4){
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="javascript:NewWindow('pop_category_write.php?m_level=<?=$CATE_CD?>&m_seq01=<?=$CATE_SEQ01?>&m_seq02=<?=$CATE_SEQ02?>&m_seq03=<?=$CATE_SEQ03?>', 'pop_add_menu', '840', '685', 'no');">중분류등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_category_order.php?m_level=<?=$CATE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
							<? } ?>
							<?
										} else {
							?>
							<? if ($sPageRight_I == "Y") {?>
							<a href="javascript:NewWindow('pop_category_write.php?m_level=<?=$CATE_CD?>&m_seq01=<?=$CATE_SEQ01?>&m_seq02=<?=$CATE_SEQ02?>&m_seq03=<?=$CATE_SEQ03?>', 'pop_add_menu', '840', '685', 'no');">소분류등록</a>&nbsp;
							<? } ?>
							<? if ($sPageRight_U == "Y") {?>
							<a href="javascript:NewWindow('pop_category_order.php?m_level=<?=$CATE_CD?>', 'pop_order_menu', '560', '470', 'no');">순서변경</a>
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
							$cate_str = "";
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