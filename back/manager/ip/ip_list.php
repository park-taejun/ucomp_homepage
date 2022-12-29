<?session_start();?>
<?
# =============================================================================
# File Name    : ip_list.php
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

	$menu_right = "IP002"; // 메뉴마다 셋팅 해 주어야 합니다

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

	#List Parameter
	$seq_no				= trim($seq_no);

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
	require "../../_classes/biz/ip/ip.php";
	
	//$arr_rs = getSiteInfo($conn, $site_no);
	
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

		$result =  insertBlockIP($conn, $block_ip, $use_tf, $s_adm_no);

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

		$result = updateBlockIP($conn, $block_ip, $use_tf, $s_adm_no, $seq_no);

		if ($result) {
?>	
<script language="javascript">
	location.href =  '<?=$_SERVER[PHP_SELF]?>?menu_cd=<?=$menu_cd?>';
</script>
<?
			exit;
		}

	}


	if ($mode == "T") {

		updateBlockIPUseTF($conn, $use_tf, $s_adm_no, $seq_no);

	}

	if ($mode == "D") {
		
		
		$row_cnt = count($chk);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_seq_no = $chk[$k];

			$result = deleteBlockIP($conn, $s_adm_no, $tmp_seq_no);
		
		}
	}

	if ($mode == "S") {

		$arr_rs = selectBlockIP($conn, $seq_no);
		
		$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]); 
		$rs_block_ip				= trim($arr_rs[0]["BLOCK_IP"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 

	}

	$del_tf = "N";
	$use_tf = "";
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

	$nListCnt =totalCntBlockIP($conn, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = ($nListCnt - 1) / $nPageSize + 1 ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	$arr_rs = listBlockIP($conn, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

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
	var seq_no = "<?= $seq_no ?>";
	
	frm.block_ip.value = frm.block_ip.value.trim();
	
	if (isNull(frm.block_ip.value)) {
		alert('차단할 아이피를 입력해주세요.');
		frm.block_ip.focus();
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


function js_toggle(banner_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('차단 여부를 변경 하시겠습니까?');
		
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.seq_no.value = banner_no;
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
		alert("선택 하신 아이피가 없습니다.");
	} else {

		bDelOK = confirm('선택하신 아이피를 삭제 하시겠습니까?');
		
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

<form id="bbsList" name="frm" method="post">
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 아이피 차단을 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?></caption>
					<tbody>
						<tr>
							<th class="long"><label for="bannerTit">차단 아이피</label></th>
							<td>
								<input type="text" name="block_ip" value="<?=$rs_block_ip?>" class="wfull" />
							</td>
						</tr>
						<tr class="last">
							<th class="long">차단여부</th>
							<td>
								<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /><label for="all">사용</label>
								<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /><label for="secret">미사용</label>
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
								<th class="num" width="10%">&nbsp;</th>
								<th class="tit" width="80%">차단아이피</th>
								<th width="10%">차단여부</th>
							</tr>
						</thead>
						<tbody>
						<?
								
							if (sizeof($arr_rs) > 0) {
											
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn								= trim($arr_rs[$j]["rn"]);
									$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
									$BLOCK_IP					= trim($arr_rs[$j]["BLOCK_IP"]);
									
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);

									if ($USE_TF == "Y") {
										$STR_USE_TF = "<font color='blue'>사용</font>";
									} else {
										$STR_USE_TF = "<font color='red'>미사용</font>";
									}

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
						?>
							<tr <? if ($j == (sizeof($arr_rs) -1)) echo "class='last'"; ?> >
								<td class="num"><input type="checkbox" name="chk[]" value="<?=$SEQ_NO?>"> <?=$rn?></td>
								<td class="tit">
									<a href="javascript:js_view('<?=$SEQ_NO?>');"><?=$BLOCK_IP?></a>
								</td>
								<td>
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
			<div class="sp20"></div>
			<div id="bbspgno">
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
			</div>

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
