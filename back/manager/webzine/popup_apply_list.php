<?session_start();?>
<?
# =============================================================================
# File Name    : popup_apply_list.php
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
	$title					= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$type						= $_POST['type']!=''?$_POST['type']:$_GET['type'];
	$use_tf					= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$pick_tf				= $_POST['pick_tf']!=''?$_POST['pick_tf']:$_GET['pick_tf'];

	if ($mode == "T") {

		updateApplyPickTF($conn, $pick_tf, $s_adm_no, (int)$seq_no);
		$result_log = insertUserLog($conn, 'apply', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당첨여부 변경 (응모번호 : ".(int)$seq_no.")", "Update");

	}

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

	$pink_cnt = getPickApplyCnt($conn, $rs_w_seq_no, $rs_type);

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";

#============================================================
# Page process
#============================================================

	if (($nPage <> "") && ($nPage <> 0)) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 2000;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$con_yyyy = "";
	$con_mm = "";
	$con_type = "";
	$con_use_tf = "Y";
	$con_del_tf = "N";

	$rs_w_seq_no = "";
	$rs_yyyy = "";
	$rs_mm = "";
	 
	$nListCnt = totalCntApply($conn, $rs_w_seq_no, $eseq_no, $rs_yyyy, $rs_mm, $rs_type, "N", $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listApply($conn, $rs_w_seq_no, $eseq_no, $rs_yyyy, $rs_mm, $rs_type, "N", $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	if ($rs_type == "TYPE03") {  
		$arr_rs_question = listQuestion($conn, $eseq_no);
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

	function js_toggle(seq_no, pick_tf) {

		var frm = document.frm;

		bDelOK = confirm('당첨 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (pick_tf == "Y") {
				pick_tf = "N";
			} else {
				pick_tf = "Y";
			}

			frm.seq_no.value = seq_no;
			frm.pick_tf.value = pick_tf;
			frm.mode.value = "T";
			frm.target = "";
			//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
			frm.action = "popup_apply_list.php";
			frm.submit();
		}
	}

	function js_view(seq_no) {
		
		var frm = document.frm;

		frm.seq_no.value = seq_no;
		frm.action = "popup_apply_view.php";
		frm.submit();

	}
	
	function js_excel_print() {

		var frm = document.frm;
		frm.action = "popup_apply_excel_list.php";
		frm.submit();
	}

</script>

</head>
<body id="popup_stock">

<form name="frm" method="post">
<input type="hidden" name="mode" id="mode" value="" >
<input type="hidden" name="pick_tf" id="pick_tf" value="">
<input type="hidden" name="seq_no" id="seq_no" value="<?= $seq_no?>">
<input type="hidden" name="eseq_no" id="eseq_no" value="<?= $eseq_no?>">

<div id="popupwrap_stock">
	<h1>응모자 조회</h1>
	<div id="postsch">
		<h2>* <?=$rs_title?> 응모자 조회 화면 입니다.</h2>
		<div class="addr_inp">
			<table cellpadding="0" cellspacing="0" border="0" width="95%">
				<tr>
					<td align="left">당첨자 수 (<?=number_format($pink_cnt)?>)</td>
					<td align="right"><a href="javascript:js_excel_print();" class="btn_type6">엑셀로 받기</a></td>
				</tr>
			</table>
			<div class="sp5"></div>
			
			<?
				// 명장면, 명대사 이벤트 139
				
				if ($eseq_no == 139) {

			?>

			<table cellpadding="0" class="rowstable" cellspacing="0" border="0" width="100%">
				<colgroup>
					<col width="5%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="11%">
					<col width="12%">
					<col width="20%">
					<col width="10%">
					<col width="10%">
					<col width="8%">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">NO.</th>
						<th scope="col">응모자명</th>
						<th scope="col">임직원명</th>
						<th scope="col">관계</th>
						<th scope="col">사업장</th>
						<th scope="col">연락처</th>
						<th scope="col">명장면/명대사</th>
						<th scope="col">출처</th>
						<th>응모일</th>
						<th class="end" scope="col">당첨여부</th>
					</tr>
				</thead>

			<? } else { ?>

			<table cellpadding="0" class="rowstable" cellspacing="0" border="0" width="100%">
				<colgroup>
					<col width="5%">
					<col width="9%">
					<col width="9%">
					<col width="9%">
					<col width="15%">
					<col width="15%">
					<col width="20%">
					<col width="10%">
					<col width="8%">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">NO.</th>
						<th scope="col">응모자명</th>
						<th scope="col">임직원명</th>
						<th scope="col">관계</th>
						<th scope="col">사업장</th>
						<th scope="col">연락처</th>
						<? if ($rs_type == "TYPE03") {  ?>
						<th scope="col">학력고사답</th>
						<? } else { ?>
						<th scope="col">등록이미지/정답</th>
						<? } ?>
						<th>응모일</th>
						<th class="end" scope="col">당첨여부</th>
					</tr>
				</thead>

				<? } ?>


				<tbody>
				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

							$rn							= trim($arr_rs[$j]["rn"]);
							$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
							$APPLY_NM				= trim($arr_rs[$j]["APPLY_NM"]);
							$EMP_NM					= trim($arr_rs[$j]["EMP_NM"]);
							$REL						= trim($arr_rs[$j]["REL"]);
							$LOCATION				= trim($arr_rs[$j]["LOCATION"]);
							$TEL						= trim($arr_rs[$j]["TEL"]);
							$IMAGE_NM				= trim($arr_rs[$j]["IMAGE_NM"]);
							$EPISODE				= trim($arr_rs[$j]["EPISODE"]);
							$EPISODE_SOURCE	= trim($arr_rs[$j]["EPISODE_SOURCE"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
							$PICK_YN				= trim($arr_rs[$j]["PICK_YN"]);

							$ANSWER01				= trim($arr_rs[$j]["ANSWER01"]);
							$ANSWER02				= trim($arr_rs[$j]["ANSWER02"]);
							$ANSWER03				= trim($arr_rs[$j]["ANSWER03"]);

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

							if ($PICK_YN == "N") {
								$STR_PICK_YN = "<font color='red'>미당첨</font>";
							} else {
								$STR_PICK_YN = "<font color='navy'>당첨</font>";
							}
				?>



				<?
					// 명장면, 명대사 이벤트 139
				
					if ($eseq_no == 139) {

				?>

					<tr>
						<td class="sort" style="height:25px"><span><a href="javascript:js_view('<?= $SEQ_NO ?>');"><?=$rn?></a></span></td>
						<td>
							<a href="javascript:js_view('<?= $SEQ_NO ?>');"><?= $APPLY_NM ?></a>
						</td>
						<td><?= $EMP_NM?></td>
						<td><?=getDcodeName($conn, "REL", $REL);?></td>
						<td><?=getDcodeName($conn, "LOCATION", $LOCATION);?></td>
						<td><?=$TEL?></td>
						<td style="padding: 5px 5px 5px 5px; text-align:left">
							<? if ($IMAGE_NM) { ?>
								<img src="/upload_data/apply/<?=$IMAGE_NM?>" width="150" height="91"><br>
							<? }  ?>
							<? if ($EPISODE) { ?>
								<?=nl2br($EPISODE)?>
							<? }  ?>

						</td>
						<td><?=$EPISODE_SOURCE ?></td>
						<td><?=$REG_DATE ?></td>
						<td><a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$PICK_YN?>');"><?=$STR_PICK_YN?></a></td>
					</tr>

				<?
					// 팬톤 컬러 고르기 이벤트 141
				
					} else if ($eseq_no == 141) {

				?>

					<tr>
						<td class="sort" style="height:25px"><span><a href="javascript:js_view('<?= $SEQ_NO ?>');"><?=$rn?></a></span></td>
						<td>
							<a href="javascript:js_view('<?= $SEQ_NO ?>');"><?= $APPLY_NM ?></a>
						</td>
						<td><?= $EMP_NM?></td>
						<td><?=getDcodeName($conn, "REL", $REL);?></td>
						<td><?=getDcodeName($conn, "LOCATION", $LOCATION);?></td>
						<td><?=$TEL?></td>
						<td style="padding: 5px 5px 5px 5px;">
							<? if ($IMAGE_NM) { ?>
								<img src="/upload_data/apply/<?=$IMAGE_NM?>" width="150" height="91"><br>
							<? }  ?>
							<? if ($EPISODE) { ?>
								<?=nl2br($EPISODE)?>
							<? }  ?>

						</td>
						<td><?=$REG_DATE ?></td>
						<td><a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$PICK_YN?>');"><?=$STR_PICK_YN?></a></td>
					</tr>

				<?
					// 2019 트렌드 이벤트
				
					} else if ($eseq_no == 147) {

				?>
					<tr>
						<td class="sort" style="height:25px"><span><a href="javascript:js_view('<?= $SEQ_NO ?>');"><?=$rn?></a></span></td>
						<td>
							<a href="javascript:js_view('<?= $SEQ_NO ?>');"><?= $APPLY_NM ?></a>
						</td>
						<td><?= $EMP_NM?></td>
						<td><?=getDcodeName($conn, "REL", $REL);?></td>
						<td><?=getDcodeName($conn, "LOCATION", $LOCATION);?></td>
						<td><?=$TEL?></td>
						<td style="padding: 5px 5px 5px 5px;">
								<?=nl2br($EPISODE)?>
						</td>
						<td><?=$REG_DATE ?></td>
						<td><a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$PICK_YN?>');"><?=$STR_PICK_YN?></a></td>
					</tr>

				<? } else { ?>


					<tr>
						<td class="sort" style="height:25px"><span><a href="javascript:js_view('<?= $SEQ_NO ?>');"><?=$rn?></a></span></td>
						<td>
							<a href="javascript:js_view('<?= $SEQ_NO ?>');"><?= $APPLY_NM ?></a>
						</td>
						<td><?= $EMP_NM?></td>
						<td><?=getDcodeName($conn, "REL", $REL);?></td>
						<td><?=getDcodeName($conn, "LOCATION", $LOCATION);?></td>
						<td><?=$TEL?></td>
						<? if ($rs_type == "TYPE03") {  ?>
						<?
									$str_answer = "";

									if (sizeof($arr_rs_question) > 0) {
										for ($jj = 0 ; $jj < sizeof($arr_rs_question); $jj++) {

											$Q_QSEQ_NO	= trim($arr_rs_question[$jj]["QSEQ_NO"]);
											$Q_ESEQ_NO	= trim($arr_rs_question[$jj]["ESEQ_NO"]);
											$Q_TITLE		= SetStringFromDB($arr_rs_question[$jj]["TITLE"]);
											$Q_TYPE			= trim($arr_rs_question[$jj]["TYPE"]);
											
											if ($Q_TYPE == "type01") {
												$arr_answer = getAnswer($conn, $Q_QSEQ_NO, $SEQ_NO);
												$ANSWER_STR	= trim($arr_answer[0]["ANSWER_STR"]);
												if ($str_answer == "") {
													$str_answer = $ANSWER_STR;
												} else {
													$str_answer = $str_answer." <br> ".$ANSWER_STR;
												}
											}
										}
									}
						?>
						<td style="padding: 5px 5px 5px 5px"><?=$str_answer?></td>
						<? } else { ?>

						<td style="padding: 5px 5px 5px 5px">
							<? if ($IMAGE_NM) { ?>
								<img src="/upload_data/apply/<?=$IMAGE_NM?>" width="150" height="91">
							<? } else {  ?>
							<? if ($ANSWER01 <> "") { ?>Quiz 1 : <?=$ANSWER01?></br><? } ?>
							<? if ($ANSWER02 <> "") { ?>Quiz 2 : <?=$ANSWER02?></br><? } ?>
							<? if ($ANSWER03 <> "") { ?>Quiz 3 : <?=$ANSWER03?></br><? } ?>
							<? } ?>
						</td>

						<? } ?>
						<td><?=$REG_DATE ?></td>
						<td><a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$PICK_YN?>');"><?=$STR_PICK_YN?></a></td>
					</tr>


				<? } ?>



				<?
						}
					} else { 
				?> 
					<tr>
						<td height="50" align="center" colspan="10">데이터가 없습니다. </td>
					</tr>
				<? 
					}
				?>
				</tbody>
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