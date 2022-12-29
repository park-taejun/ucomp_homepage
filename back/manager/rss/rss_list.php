<?session_start();?>
<?
# =============================================================================
# File Name    : rss_list.php
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

	$menu_right = "RS001"; // 메뉴마다 셋팅 해 주어야 합니다

#==============================================================================
# Confirm right
#==============================================================================

	#List Parameter
	$seq_no		= trim($seq_no);
	
	//echo $banner_type;

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
	require "../../_classes/biz/rss/rss.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode					= trim($mode);

	$nPage				= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field	= trim($search_field);
	$search_str		= trim($search_str);

#====================================================================
# DML Process
#====================================================================

	if ($mode == "I") {

		$title			= SetStringToDB($title);
		$rss_link		= trim($rss_link);

		$result =  insertRss($conn, $title, $rss_link, $use_tf);

		if ($result) {
?>	
<script language="javascript">
		//location.href =  '<?=$_SERVER[PHP_SELF]?>?banner_type=<?=$banner_type?>';
</script>
<?
			//exit;
		}
	}

	if ($mode == "U") {

		$result = updateRss($conn, $title, $rss_link, $use_tf, $seq_no);

		if ($result) {
?>	
<script language="javascript">
	location.href =  '<?=$_SERVER[PHP_SELF]?>';
</script>
<?
			exit;
		}
	}


	if ($mode == "T") {

		updateRssUseTF($conn, $use_tf, $seq_no);

	}

	if ($mode == "D") {
		
		
		$row_cnt = count($chk);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_seq_no = $chk[$k];

			$result = deleteRss($conn, $s_adm_no, $tmp_seq_no);
		
		}
	}

	if ($mode == "S") {

		$arr_rs = selectRss($conn, $seq_no);
		
		$rs_seq_no				= trim($arr_rs[0]["SEQ_NO"]); 
		$rs_title					= SetStringFromDB($arr_rs[0]["TITLE"]); 
		$rs_rss_link			= trim($arr_rs[0]["RSS_LINK"]); 
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 

	}

	$con_del_tf = "N";
	$con_use_tf = "";
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
		$nPageSize = 10;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntRss($conn, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = ($nListCnt - 1) / $nPageSize + 1 ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	$nPage		 = 1;
	$nPageSize = 100;

	$arr_rs = listRss($conn, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

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

	var frm = document.frm;
	var seq_no = "<?=$seq_no?>";
	
	frm.title.value = frm.title.value.trim();
	
	if (isNull(frm.title.value)) {
		alert('블로그명을 입력해주세요.');
		frm.title.focus();
		return ;		
	}

	frm.rss_link.value = frm.rss_link.value.trim();
	
	if (isNull(frm.rss_link.value)) {
		alert('블로그 RSS 경로를 입력해주세요.');
		frm.rss_link.focus();
		return ;		
	}

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (isNull(seq_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.seq_no.value = seq_no;
	}

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_view(seq) {

	var frm = document.frm;
		
	frm.seq_no.value = seq;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
		
}


function js_toggle(seq_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
		
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
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}
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

<form id="bbsList" name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="seq_no" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 메인 비주얼을 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?></caption>
					<tbody>
						<tr>
							<th class="long"><label for="bannerTit">블로그명</label></th>
							<td>
								<input type="text" name="title" value="<?=$rs_title?>" class="wfull" />
							</td>
						</tr>
						<tr>
							<th class="long">RSS 링크</th>
							<td>
								<input type="text" name="rss_link" value="<?=$rs_rss_link?>" class="wfull" />
							</td>
						</tr>
						<tr class="last">
							<th class="long">공개여부</th>
							<td>
								<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /><label for="all">공개</label>
								<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /><label for="secret">비공개</label>
								<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>">
							</td>
						</tr>
					</tbody>
					</table>

					<div class="btnArea">
						<ul class="fRight">
							<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
							<li><a href="javascript:document.frm.reset();"><img src="../images/btn/btn_cancel.gif" alt="취소" /></a></li>
							<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
						</ul>
					</div>

					<table summary="이곳에서 메인 페이지의 배너를 관리하실 수 있습니다" class="secBtm" id='t'>
						<thead>
							<tr>
								<th class="num">&nbsp;</th>
								<th class="num">번호</th>
								<th class="tit">블로그명</th>
								<th class="visual">RSS 링크</th>
								<th>공개여부</th>
							</tr>
						</thead>
						<tbody>
						<?
								
							if (sizeof($arr_rs) > 0) {
											
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn							= trim($arr_rs[$j]["rn"]);
									$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
									$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
									$RSS_LINK				= trim($arr_rs[$j]["RSS_LINK"]);
									
									$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
									$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

									if ($USE_TF == "Y") {
										$STR_USE_TF = "<font color='blue'>공개</font>";
									} else {
										$STR_USE_TF = "<font color='red'>비공개</font>";
									}

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
						?>
							<tr <? if ($j == (sizeof($arr_rs) -1)) echo "class='last'"; ?> >
								<td class="num"><input type="checkbox" name="chk[]" value="<?=$SEQ_NO?>"></td>
								<td class="num"><?=$rn?></td>
								<td class="tit">
									<a href="javascript:js_view('<?=$SEQ_NO?>');"><?=$TITLE?></a>
								</td>
								<td>
									<?=$RSS_LINK?>
								</td>
								<td><!--td에 클래스 on/off로 공개/비공개 제어-->
									<a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a>
								</td>
							</tr>
						<?
								}
							} else { 
						?>
							<tr>
								<td height="50" align="center" colspan="7">데이터가 없습니다. </td>
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
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
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
