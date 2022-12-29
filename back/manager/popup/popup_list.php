<?session_start();?>
<?
# =============================================================================
# File Name    : banner_list.php
# Modlue       :
# Writer       : Park Chan Ho
# Create Date  : 2012.05.29
# Modify Date  :
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$menu_right = "CS005"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/popup/popup.php";
	require "../../_classes/biz/admin/admin.php";

#==============================================================================
# Request Parameter
#==============================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$mode			= SetStringToDB($mode);

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$use_tf				= SetStringToDB($use_tf);


	if ($mode == "D") {

		$row_cnt = count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
			$tmp_bb_no = (int)$chk[$k];
			$result= deletePopup($conn, $tmp_bb_no);
		}

	}

#====================================================================
# Request Parameter
#====================================================================
	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$con_cate_01	= SetStringToDB($con_cate_01);
	$con_cate_02	= SetStringToDB($con_cate_02);
	$con_cate_03	= SetStringToDB($con_cate_03);
	$keyword		= SetStringToDB($keyword);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

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

	$nListCnt =totalCntPopup($conn, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listPopup($conn, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
<script language="javascript">

	function js_write() {
		var frm = document.frm;
		frm.target = "";
		frm.action = "popup_write.php";
		frm.submit();
	}

	function js_delete() {
		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");

		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}

		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');

			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
	}

	function js_view(rn, pop_no) {

		var frm = document.frm;

		frm.pop_no.value = pop_no;
		frm.mode.value = "S";

		frm.target = "";
		frm.method = "post";
		frm.action = "popup_write.php";
		frm.submit();

	}

	// 조회 버튼 클릭 시
	function js_search() {
		var frm = document.frm_search;

		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "board_list.php";
		frm.submit();
	}

function js_toggle(bb_code, bb_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');

	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.bb_code.value = bb_code;
		frm.bb_no.value = bb_no;
		frm.use_tf.value = use_tf;
		frm.mode.value = "T";
		frm.target = "";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "board_list.php";
		frm.submit();
	}
}

function js_toggle2(bb_code, bb_no, use_tf) {
	var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");

		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}

		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {


			bDelOK = confirm('승인 하시겠습니까?');

			if (bDelOK==true) {

				if (use_tf == "Y") {
					use_tf = "N";
				} else {
					use_tf = "Y";
				}

				frm.bb_code.value = bb_code;
				frm.bb_no.value = bb_no;
				frm.use_tf.value = use_tf;
				frm.mode.value = "T";
				frm.target = "";
				//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
				frm.action = "board_list.php";
				frm.submit();
			}
		}
}

function js_con_cate_01 () {
	frm.nPage.value = "1";
	frm.target = "";
	//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
	frm.action = "popup_list.php";
	frm.submit();
}

function js_con_cate_02 () {
	frm.nPage.value = "1";
	frm.target = "";
	//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
	frm.action = "popup_list.php";
	frm.submit();
}

function js_con_cate_03 () {
	frm.nPage.value = "1";
	frm.target = "";
	//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
	frm.action = "popup_list.php";
	frm.submit();
}

//function view(pop_no,size_w, size_h,top,left_,scrollbars){
function view(pop_no,top,left_){
	//window.open('test_view.php?pop_no='+pop_no,'test','width='+size_w+',height='+size_h+',top='+top+',left='+left_+',scrollbars='+scrollbars);
	var features = 'top=0,left=0,toolbar=yes,scrollbars=yes';
	window.open('../../main/preview_div.php?pop_no='+pop_no,'test',features);
}

//function view(pop_no,size_w, size_h,top,left_,scrollbars){
function view_div(pop_no,top,left_){
	//window.open('test_view.php?pop_no='+pop_no,'test','width='+size_w+',height='+size_h+',top='+top+',left='+left_+',scrollbars='+scrollbars);
	var features = 'top=0,left=0,toolbar=yes,scrollbars=yes';
	window.open('../../main/preview_div.php?pop_no='+pop_no,'test',features);
}

function view_pop(pop_no,size_w, size_h,top,left_,scrollbars){
	var features = 'top=0,left=0,toolbar=yes,scrollbars=yes';
	window.open('../../main/preview_popup?pop_no='+pop_no,'test','width='+size_w+',height='+size_h+',top='+top+',left='+left_+',scrollbars='+scrollbars);
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
<input type="hidden" name="pop_no" value="">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

			<fieldset>
				<legend class="conTitle">팝업관리&nbsp;&nbsp;&nbsp;&nbsp;</legend>
				<div class="tbMin">
				<table summary="이곳에서 <?=$p_menu_name?> 관리하실 수 있습니다">
					<caption>팝업관리</caption>
				<?
						require "popup_list_sub.php";

				?>
				</table>
				</div>
			</fieldset>
		</form>

			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fRight">
						<?	if ($sPageRight_I == "Y") { ?>
							<li><a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a></li>
						<?	} else echo "&nbsp;"; ?>

					<?	if ($sPageRight_D == "Y") { ?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<?  } ?>
				</ul>
			</div>
			<div id="bbspgno">
				<!-- --------------------- 페이지 처리 화면 START -------------------------->
				<?
					# ==========================================================================
					#  페이징 처리
					# ==========================================================================
					if (sizeof($arr_rs) > 0) {
						#$search_field		= trim($search_field);
						#$search_str			= trim($search_str);
						$strParam = $strParam."&nPageSize=".$nPageSize."&bb_code=".$bb_code."&search_field=".$search_field."&search_str=".$search_str;

				?>
				<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
				<?
					}
				?>
				<!-- --------------------- 페이지 처리 화면 END -------------------------->
			</div>

			<form id="searchBar" name="frm_search" action="javascript:js_search();" method="post">
			<input type="hidden" name="rn" value="">
			<input type="hidden" name="bb_no" value="">
			<input type="hidden" name="bb_code" value="<?=$bb_code?>">
			<input type="hidden" name="use_tf" value="">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
					<legend>검색창</legend>
					<? if ($b_board_cate) { ?>
					<?=makeBoardSelectBox("con_cate_01", "전체", "", $b_board_cate, "style='width:100px'", $con_cate_01); ?>
					<? } ?>
					<select name="search_field" style="width:84px;">
						<option value="title" <? if ($search_field == "title") echo "selected"; ?> >제목</option>
						<option value="contents" <? if ($search_field == "contents") echo "selected"; ?> >내용</option>
					</select>
					<input type="text" value="<?=$search_str?>" name="search_str" class="txt" />
					<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" class="sch" alt="Search" /></a>
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