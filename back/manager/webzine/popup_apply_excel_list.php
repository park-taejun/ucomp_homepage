<?session_start();?>
<?
header("x-xss-Protection:0");
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


	$str_title = $rs_title." 응모자 리스트";

	$file_name=$str_title."-".date("Ymd").".xls";

	$file_name = iconv("utf-8","euc-kr",$file_name);

	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );


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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?=$g_title?></title>
</head>
<body>
<font size=3><b>응모자 리스트</b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">

	<?
		// 명장면, 명대사 이벤트 139
		if ($eseq_no == 139) {
	?>
	<tr>
		<th>번호</th>
		<th>응모자명</th>
		<th>임직원명</th>
		<th>관계</th>
		<th>사업장</th>
		<th>연락처</th>
		<th>명장면/명대사</th>
		<th>출처</th>
		<th>응모일</th>
		<th>당첨여부</th>
	</tr>
	<? } else { ?>


	<tr>
		<th>번호</th>
		<th>응모자명</th>
		<th>임직원명</th>
		<th>관계</th>
		<th>사업장</th>
		<th>연락처</th>
		<? if ($rs_type <> "TYPE03") {  ?>
		<th>등록이미지/정답</th>
		<? } ?>
		<? if (($rs_type == "TYPE01") || ($rs_type == "TYPE02")) {  ?>
		<th>사연</th>
		<? } ?>
		<? if ($rs_type == "TYPE03") {  ?>
		<th>학력고사답</th>

		<?
			if (sizeof($arr_rs_question) > 0) {
				for ($j = 0 ; $j < sizeof($arr_rs_question); $j++) {

					$Q_QSEQ_NO	= trim($arr_rs_question[$j]["QSEQ_NO"]);
					$Q_ESEQ_NO	= trim($arr_rs_question[$j]["ESEQ_NO"]);
					$Q_TITLE		= SetStringFromDB($arr_rs_question[$j]["TITLE"]);
					$Q_TYPE			= trim($arr_rs_question[$j]["TYPE"]);
											
					if ($Q_TYPE == "type02") {
		?>
		<th><?=$Q_TITLE?></th>
		<?

						}
					}
				}

		?>

		<? } ?>
		<th>응모일</th>
		<th>당첨여부</th>
	</tr>
	<? } ?>


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
				$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
				$PICK_YN				= trim($arr_rs[$j]["PICK_YN"]);
				$EPISODE				= trim($arr_rs[$j]["EPISODE"]);
				$EPISODE_SOURCE	= trim($arr_rs[$j]["EPISODE_SOURCE"]);

				$ANSWER01				= trim($arr_rs[$j]["ANSWER01"]);
				$ANSWER02				= trim($arr_rs[$j]["ANSWER02"]);
				$ANSWER03				= trim($arr_rs[$j]["ANSWER03"]);

				$REG_DATE = date("Y-m-d [H:i:s]",strtotime($REG_DATE));

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
		<td><?=$rn?></td>
		<td><?= $APPLY_NM ?></td>
		<td><?= $EMP_NM?></td>
		<td><?=getDcodeName($conn, "REL", $REL);?></td>
		<td><?=getDcodeName($conn, "LOCATION", $LOCATION);?></td>
		<td><?=$TEL?></td>
		<td>
			<? if ($IMAGE_NM) { ?>
			<?=$g_site_url?>/upload_data/apply/<?=$IMAGE_NM?><br>
			<? } ?>
			<? if ($EPISODE) { ?>
			<?=nl2br($EPISODE)?>
			<? } ?>
		</td>
		<td><?=$EPISODE_SOURCE ?></td>
		<td><?=$REG_DATE ?></td>
		<td><?=$STR_PICK_YN?></td>
	</tr>

	<?
		// 팬톤 컬러 고르기 이벤트 141
		} else if ($eseq_no == 141) {
	?>

	<tr>
		<td><?=$rn?></td>
		<td><?= $APPLY_NM ?></td>
		<td><?= $EMP_NM?></td>
		<td><?=getDcodeName($conn, "REL", $REL);?></td>
		<td><?=getDcodeName($conn, "LOCATION", $LOCATION);?></td>
		<td><?=$TEL?></td>
		<td align="center">
			<? if ($IMAGE_NM) { ?>
			<?=$g_site_url?>/upload_data/apply/<?=$IMAGE_NM?><br>
			<? } ?>
			<? if ($EPISODE) { ?>
			<?=nl2br($EPISODE)?>
			<? } ?>
		</td>
		<td><?=$EPISODE_SOURCE ?></td>
		<td><?=$REG_DATE ?></td>
		<td><?=$STR_PICK_YN?></td>
	</tr>

	<?
		// 2019 트렌드 이벤트
		} else if ($eseq_no == 147) {
	?>

	<tr>
		<td><?=$rn?></td>
		<td><?= $APPLY_NM ?></td>
		<td><?= $EMP_NM?></td>
		<td><?=getDcodeName($conn, "REL", $REL);?></td>
		<td><?=getDcodeName($conn, "LOCATION", $LOCATION);?></td>
		<td><?=$TEL?></td>
		<td align="center">
			<? if ($EPISODE) { ?>
			<?=nl2br($EPISODE)?>
			<? } ?>
		</td>
		<td><?=$EPISODE_SOURCE ?></td>
		<td><?=$REG_DATE ?></td>
		<td><?=$STR_PICK_YN?></td>
	</tr>

	<? } else { ?>

	<tr>
		<td><?=$rn?></td>
		<td><?= $APPLY_NM ?></td>
		<td><?= $EMP_NM?></td>
		<td><?=getDcodeName($conn, "REL", $REL);?></td>
		<td><?=getDcodeName($conn, "LOCATION", $LOCATION);?></td>
		<td><?=$TEL?></td>
		<? if ($rs_type <> "TYPE03") {  ?>
		<td>
			<? if ($IMAGE_NM) { ?>
			<?=$g_site_url?>/upload_data/apply/<?=$IMAGE_NM?>
			<? } else { ?>
			<?	if ($ANSWER01 <> "") { ?>Quiz 1 : <?=$ANSWER01?></br><? } ?>
			<?	if ($ANSWER02 <> "") { ?>Quiz 2 : <?=$ANSWER02?></br><? } ?>
			<?	if ($ANSWER03 <> "") { ?>Quiz 3 : <?=$ANSWER03?></br><? } ?>
			<? } ?>
		</td>

		<? } ?>

		<? if (($rs_type == "TYPE01") || ($rs_type == "TYPE02")) {  ?>
		<th><?=nl2br($EPISODE)?></th>
		<? } ?>

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
		<td><?=$str_answer ?></td>

		<?

			if (sizeof($arr_rs_question) > 0) {
				for ($jk = 0 ; $jk < sizeof($arr_rs_question); $jk++) {

					$Q_QSEQ_NO	= trim($arr_rs_question[$jk]["QSEQ_NO"]);
					$Q_ESEQ_NO	= trim($arr_rs_question[$jk]["ESEQ_NO"]);
					$Q_TITLE		= SetStringFromDB($arr_rs_question[$jk]["TITLE"]);
					$Q_TYPE			= trim($arr_rs_question[$jk]["TYPE"]);
											
					if ($Q_TYPE == "type02") {
												
						$arr_answer = getAnswer($conn, $Q_QSEQ_NO, $SEQ_NO);
						$ANSWER_STR	= trim($arr_answer[0]["ANSWER_STR"]);
		?>
		<td><?=nl2br($ANSWER_STR) ?></td>
		<?

					}
				}
			}
		?>

		<? } ?>

		<td><?=$REG_DATE ?></td>
		<td><?=$STR_PICK_YN?></td>
	</tr>
	<? }  // 명장면 명대사 끝?>
<?
		}
	}
?>
</table>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>