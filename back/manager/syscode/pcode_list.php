<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : pcode_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
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
	$menu_right = "SY002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/syscode/syscode.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

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

	$use_tf				= SetStringToDB($use_tf);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntPcode($conn, $g_site_no, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listPcode($conn, $g_site_no, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "대분류코드조회", "List");

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
<script language="javascript" type="text/javascript" >


	function js_write() {
		var url = "pcode_write_popup.php";
		NewWindow(url, '대분류등록', '560', '313', 'NO');
	}

	function js_view(rn, seq) {

		var url = "pcode_write_popup.php?mode=S&pcode_no="+seq;
		NewWindow(url, '대분류조회', '560', '313', 'NO');
	}
	
	function js_view_dcode(rn, seq) {

		var url = "dcode_list_popup.php?mode=R&pcode_no="+seq;
		NewWindow(url, '세부분류조회', '560', '650', 'NO');
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "pcode_list.php";
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
					<? if ($sPageRight_I == "Y") { ?><button type="button" class="btn-navy" onclick="js_write();">등록하기</button><? } ?>
				</h3>
				<div class="boardlist search">

<form id="searchBar" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="pcode_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

					<table>
						<colgroup>
							<col style="width:100px" />
							<col style="width:auto" />
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">검색조건</th>
								<td>
									<div class="searchbox">
										<span class="optionbox">
											<select name="search_field" id="kind">
												<option value="PCODE" <? if ($search_field == "PCODE") echo "selected"; ?> >코드</option>
												<option value="PCODE_NM" <? if ($search_field == "PCODE_NM") echo "selected"; ?> >코드명</option>
											</select>
										</span>
										<span class="inpbox">
											<input type="text" value="<?=$search_str?>" name="search_str" class="txt" />
										</span>
										<button type="button" onclick="js_search();" class="btn-border-white" id="btn_search">검색</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
</form>
				</div>

				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:5%">
							<col style="width:30%">
							<col style="width:30%">
							<col style="width:35%">
					</colgroup>
					<thead>
						<tr>
							<th scope="col">NO.</th>
							<th scope="col">코드</th>
							<th scope="col">코드명</th>
							<th scope="col">메뉴</th>
						</tr>
					</thead>
					<tbody>
					<?
						$nCnt = 0;
						
						if (sizeof($arr_rs) > 0) {
							
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								

								$PCODE_NO				= trim($arr_rs[$j]["PCODE_NO"]);
								$PCODE					= trim($arr_rs[$j]["PCODE"]);
								$PCODE_NM				= trim($arr_rs[$j]["PCODE_NM"]);
								$PCODE_MEMO			= trim($arr_rs[$j]["PCODE_MEMO"]);
								$PCODE_SEQ_NO		= trim($arr_rs[$j]["PCODE_SEQ_NO"]);
								$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
								$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
								$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

								$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
								$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;

					?>
						<tr> 
							<td><?=$rn?></td>
							<td><a href="javascript:js_view('<?= $rn ?>','<?= $PCODE_NO ?>');"><?= $PCODE ?></a></td>
							<td><?= $PCODE_NM ?></td>
							<td><a href="javascript:js_view_dcode('<?= $rn ?>','<?= $PCODE_NO ?>');">[세부분류코드]</a></td>
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
				</div>

				<p class="paging">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
				</p>

			</div>
		</div>
	</div>
	<!-- //E: container -->
	<!-- S: footer -->

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