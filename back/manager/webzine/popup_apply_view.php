<?session_start();?>
<?
# =============================================================================
# File Name    : popup_apply_view.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2017-02-06
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "WB002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";
	
#====================================================================
# common_header
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/webzine/webzine.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$seq_no					= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$eseq_no				= $_POST['eseq_no']!=''?$_POST['eseq_no']:$_GET['eseq_no'];


	$arr_info = selectWebzineEvent($conn, $eseq_no);

	$rs_seq_no				= trim($arr_info[0]["SEQ_NO"]);
	$rs_w_seq_no			= trim($arr_info[0]["W_SEQ_NO"]);
	$rs_yyyy					= trim($arr_info[0]["YYYY"]);
	$rs_mm						= trim($arr_info[0]["MM"]);
	$rs_type					= trim($arr_info[0]["TYPE"]);
	$rs_s_date				= trim($arr_info[0]["S_DATE"]);
	$rs_e_date				= trim($arr_info[0]["E_DATE"]);
	$rs_title					= SetStringFromDB($arr_info[0]["TITLE"]);
	$rs_memo					= trim($arr_info[0]["MEMO"]);
	$rs_image01				= trim($arr_info[0]["IMAGE01"]);
	$rs_use_tf				= trim($arr_info[0]["USE_TF"]);
	$rs_del_tf				= trim($arr_info[0]["DEL_TF"]);
	$rs_reg_date			= trim($arr_info[0]["REG_DATE"]);

	$arr_rs = selectApply($conn, $seq_no, $rs_type);

	if (sizeof($arr_rs) > 0) {

		$SEQ_NO									= trim($arr_rs[0]["SEQ_NO"]);
		$WSEQ_NO								= trim($arr_rs[0]["WSEQ_NO"]);
		$YYYY										= trim($arr_rs[0]["YYYY"]);
		$MM											= trim($arr_rs[0]["MM"]);
		$TYPE										= trim($arr_rs[0]["TYPE"]);
		$APPLY_NM								= SetStringFromDB($arr_rs[0]["APPLY_NM"]);
		$EMP_NM									= SetStringFromDB($arr_rs[0]["EMP_NM"]);
		$REL										= trim($arr_rs[0]["REL"]);
		$LOCATION								= trim($arr_rs[0]["LOCATION"]);
		$TEL										= trim($arr_rs[0]["TEL"]);
		$IMAGE_NM								= trim($arr_rs[0]["IMAGE_NM"]);
		$IMAGE_RNM							= trim($arr_rs[0]["IMAGE_RNM"]);
		$EPISODE								= trim($arr_rs[0]["EPISODE"]);
		$EPISODE_SOURCE					= trim($arr_rs[0]["EPISODE_SOURCE"]);
		$PICK_YN								= trim($arr_rs[0]["PICK_YN"]);

		if ($PICK_YN == "N") {
			$STR_PICK_YN = "<font color='red'>미당첨</font>";
		} else {
			$STR_PICK_YN = "<font color='navy'>당첨</font>";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<script type="text/javascript" src="../js/common.js"></script>
	<script src="../js/jquery-1.11.2.min.js"></script>
<style type="text/css">
	/*
	html { overflow:hidden; } 
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
 */
</style>
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 568px; }
-->
</style>
<script language="javascript">

	function js_list() {
		
		var frm = document.frm;

		frm.action = "popup_apply_list.php";
		frm.submit();

	}

</script>

</head>
<body id="popup_stock">

<form name="frm" method="post">
<input type="hidden" name="mode" id="mode" value="" >
<input type="hidden" name="seq_no" id="seq_no" value="<?= $seq_no?>">
<input type="hidden" name="eseq_no" id="eseq_no" value="<?= $eseq_no?>">

<div id="popupwrap_stock">
	<h1>응모자 조회</h1>
	<div id="postsch">
		<h2>* <?=$rs_yyyy?>년 <?=$rs_mm?>월 <?=$rs_title?> 응모자 조회 화면 입니다.</h2>
		<div class="addr_inp">

			<div class="sp5"></div>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
				<colgroup>
					<col width="20%">
					<col width="30%">
					<col width="20%">
					<col width="30%">
				</colgroup>
					<tr>
						<td colspan="4"><b><?= $rs_pcode_nm ?></b></td>
					</tr>
					<tr>
						<th>응모자명</th>
						<td>
							<?=$APPLY_NM	?>
						</td>
						<th>임직원명</th>
						<td>
							<?=$EMP_NM?>
						</td>
					</tr>
					<tr>
						<th>임직원과의 관계</th>
						<td>
							<?=getDcodeName($conn, "REL", $REL);?>
						</td>
						<th>사업장</th>
						<td>
							<?=getDcodeName($conn, "LOCATION", $LOCATION);?>
						</td>
					</tr>
					<tr>
						<th>연락처</th>
						<td colspan="3">
							<?=$TEL?><!--010</span><em>-</em><span>1234</span><em>-</em><span>5678-->
						</td>
					<tr>
					<? if (($TYPE == "TYPE01") || ($TYPE == "TYPE02") || ($TYPE == "TYPE10") || ($TYPE == "TYPE12") || ($TYPE == "TYPE13")) { ?>
					
					<?
						// 명장면, 명대사 이벤트 139
						if ($eseq_no == 139) {
					?>
					<tr>
						<th>명장면</th>
						<td colspan="3">
							<strong><?=$IMAGE_RNM?></strong>
							<div class="sp10"></div>
							<span class="pic"><img src="/upload_data/apply/<?=$IMAGE_NM?>" width="280" height="170"></span>
						</td>
					<tr>
					<? } else { ?>
					<tr>
						<th>등록한이미지</th>
						<td colspan="3">
							<strong><?=$IMAGE_RNM?></strong>
							<div class="sp10"></div>
							<span class="pic"><img src="/upload_data/apply/<?=$IMAGE_NM?>" width="280" height="170"></span>
						</td>
					<tr>
					<? }  ?>


					<? } ?>

					<? if (($TYPE == "TYPE02") || ($TYPE == "TYPE10") || ($TYPE == "TYPE11") || ($TYPE == "TYPE12")) { ?>
					
					<?
						// 명장면, 명대사 이벤트 139
						if ($eseq_no == 139) {
					?>
					
					<tr>
						<th>명대사</th>
						<td colspan="3" style="padding: 10px 10px 10px 10px">
							<?=nl2br($EPISODE)?>
						</td>
					<tr>

					<tr>
						<th>출처</th>
						<td colspan="3">
							<?=$EPISODE_SOURCE?>
						</td>
					<tr>

					<? } else { ?>
					<tr>
						<th>사연</th>
						<td colspan="3" style="padding: 10px 10px 10px 10px">
							<?=nl2br($EPISODE)?>
						</td>
					<tr>
					<? }  ?>


					<? } ?>

					<? if ($TYPE == "TYPE03") { ?>
					<?
						$arr_rs_question = listQuestion($conn, $eseq_no);
						
						$str_answer = "";

						if (sizeof($arr_rs_question) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs_question); $j++) {

								$Q_QSEQ_NO	= trim($arr_rs_question[$j]["QSEQ_NO"]);
								$Q_ESEQ_NO	= trim($arr_rs_question[$j]["ESEQ_NO"]);
								$Q_TITLE		= SetStringFromDB($arr_rs_question[$j]["TITLE"]);
								$Q_TYPE			= trim($arr_rs_question[$j]["TYPE"]);
								
								if ($Q_TYPE == "type01") {
									
									$arr_answer = getAnswer($conn, $Q_QSEQ_NO, $SEQ_NO);
									$ANSWER_STR	= trim($arr_answer[0]["ANSWER_STR"]);
									if ($str_answer == "") {
										$str_answer = $ANSWER_STR;
									} else {
										$str_answer = $str_answer." / ".$ANSWER_STR;
									}
								}
							}
						}
					?>
					<tr>
						<th>하우시스 학력고사</th>
						<td colspan="3" style="padding: 10px 10px 10px 10px">
							<?=$str_answer?>
						</td>
					<tr>
					<? } ?>

					<?
						//$arr_rs_question = listQuestion($conn, $ESEQ_NO);
						
						$str_answer = "";

						if (sizeof($arr_rs_question) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs_question); $j++) {

								$Q_QSEQ_NO	= trim($arr_rs_question[$j]["QSEQ_NO"]);
								$Q_ESEQ_NO	= trim($arr_rs_question[$j]["ESEQ_NO"]);
								$Q_TITLE		= SetStringFromDB($arr_rs_question[$j]["TITLE"]);
								$Q_TYPE			= trim($arr_rs_question[$j]["TYPE"]);
								
								if ($Q_TYPE == "type02") {
									
									$arr_answer = getAnswer($conn, $Q_QSEQ_NO, $SEQ_NO);
									$ANSWER_STR	= trim($arr_answer[0]["ANSWER_STR"]);
					?>
					<tr>
						<th><?=$Q_TITLE?></th>
						<td colspan="3" style="padding: 10px 10px 10px 10px">
							<?=nl2br($ANSWER_STR)?>
						</td>
					<tr>
					<?

								}
							}
						}

					?>
					<tr>
						<th>당첨여부</th>
						<td colspan="3">
							<?=$STR_PICK_YN?>
						</td>
					<tr>

			</table>
			<div class="sp5"></div>
			<table cellpadding="0" cellspacing="0" border="0" width="95%">
				<tr>
					<td align="left">&nbsp;</td>
					<td align="right"><a href="javascript:js_list();" class="btn_type6">목록</a></td>
				</tr>
			</table>
			
			
				
			


		</div>
	<br />
</div>

<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>