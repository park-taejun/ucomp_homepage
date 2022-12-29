<?session_start();?>
<?
# =============================================================================
# File Name    : intro_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.05.30
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
	require "../../../_classes/community/cintro/intro.php";

	if ($mode == "T") {
		updateCommIntroUseTF($conn, $use_tf, $s_comm_adm_no, $seq_no);
	}

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
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

	$nListCnt =totalCntCommIntro($conn, $comm_no, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listCommIntro($conn, $comm_no, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize);

	#echo sizeof($arr_rs);
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
<script type="text/javascript" >


	function js_write() {
		document.location.href = "intro_write.php?menu_cd=<?=$menu_cd?>";
	}

	function js_view(seq_no) {

		var frm = document.frm;
		
		frm.seq_no.value = seq_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "intro_write.php";
		frm.submit();
		
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "post";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_toggle(seq_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('사용 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.seq_no.value = seq_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.method = "post";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
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

<form id="bbsList" name="bbslist">

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>

					<div style="padding-left:50px;padding-bottom:5px">* 등록된 내용 중 가장 최근 보이기인 자료가 노출 됩니다.</div>
					<table summary="이곳에서 소개을 등록, 수정, 삭제하실 수 있습니다">
					<caption><?=$p_menu_name?>관리</caption>
						<colgroup>
							<col width="10%" />
							<col width="70%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>
						<thead>
							<tr>
								<th scope="col">번호</th>
								<th scope="col">이름</th>
								<th scope="col">등록일</th>
								<th scope="col">사용여부</th>
							</tr>
						</thead>
						<tbody>
						<?

							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn						= trim($arr_rs[$j]["rn"]);
									$SEQ_NO				= trim($arr_rs[$j]["SEQ_NO"]);
									$TITLE				= SetStringFromDB($arr_rs[$j]["TITLE"]);
									$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF				= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);

									if ($USE_TF == "Y") {
										$STR_USE_TF = "<font color='navy'>사용중</font>";
									} else {
										$STR_USE_TF = "<font color='red'>사용안함</font>";
									}

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
				
						?>
							<tr>
								<td><?=$rn?></td>
								<td class="tit"><a href="javascript:js_view('<?= $SEQ_NO ?>');"><?= $TITLE ?></a></td>
								<td><?= $REG_DATE ?></td>
								<td><a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$USE_TF?>');"><?= $STR_USE_TF ?></a></td>
							</tr>
						<?			
								}
							} else { 
						?> 
							<tr>
								<td align="center" height="50" colspan="4">데이터가 없습니다. </td>
							</tr>
						<? 
							}
						?>
						</tbody>
					</table>
				</fieldset>
			</form>
			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fRight">
					<li><a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a></li>
				</ul>
			</div>

					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&menu_cd=".$menu_cd;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->

			<!--검색바-->
			<form id="searchBar" name="frm" method="post" action="javascript:js_search();">

<input type="hidden" name="seq_no" value="">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
					<legend>검색창</legend>
					<select name="search_field" id="kind">
						<option value="TITLE" <? if ($search_field == "TITLE") echo "selected"; ?> >이름</option>
					<select>
					<input type="text" id="keyword" value="<?=$search_str?>" name="search_str" />
					<input type="image" id="searBtn" src="../images/btn/btn_search.gif" alt="검색" />
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
