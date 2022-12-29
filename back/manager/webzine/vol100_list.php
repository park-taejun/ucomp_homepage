<?session_start();?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");


#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다
	//$menu_cd="0501";

	$menu_right = "WB004"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/webzine/webzine.php";
#====================================================================
# Request Parameter
#====================================================================

#============================================================
# Page process
#============================================================

#===============================================================
# Get Search list count
#===============================================================

	$arr_rs = listWebzineEventVol100($conn);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "웹진 100호 이벤트 리스트 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
<link href="../css/common.css" rel="stylesheet" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />

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
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>

<script language="javascript">

	$(document).ready(function() {

		$(".date").datepicker({
			dateFormat: "yy-mm-dd"
		});

		$( "#search_str" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});

	});


	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_delete(seq_no) {
		var frm = document.frm;
		bDelOK = confirm('선택하신 이벤트을 삭제 하시겠습니까?');
		if (bDelOK) {
			frm.mode.value = "D";
			frm.seq_no.value = seq_no;
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_read(seq) {

		var frm = document.frm;
		frm.seq_no.value = seq;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "vol100_write.php";
		frm.submit();
		
	}

	function js_write(){

		var frm = document.frm;
		frm.target = "";
		frm.method = "post";
		frm.action = "vol100_write.php";
		frm.submit();
		
	}


	function js_question_write(seq_no) {
		var url = "popup_question.php?eseq_no="+seq_no;
		NewWindow(url, '질문등록', '1014', '760', 'YES');
	}

	function js_apply_list(seq_no) {
		var url = "popup_apply_list.php?eseq_no="+seq_no;
		NewWindow(url, '응모자조회', '1114', '760', 'YES');
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

<form id="bbsList" name="frm" method="post" action="javascript:js_serch();">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<div class="sp0"></div>
					<div class="expArea">
						<ul class="fLeft">
							<!--<li class="total">총 <?=number_format($nListCnt)?>건</li>-->
							<!--<li class="file">파일 저장하기&nbsp;<a href="#"><img src="../images/bu/ic_file.gif" alt="엑셀파일 아이콘" /></a></li>
							<?if($_SERVER['REMOTE_ADDR']=="203.229.173.93"){?>
							<?}?>
							<a href="javascript:js_emailsend4check();"><img src="../images/mail/mailing_btn_01.gif" alt="메일보내기[선택된 사람에게]" /></a>&nbsp;&nbsp;
							<a href="javascript:js_emailsend4search();"><img src="../images/mail/mailing_btn_02.gif" alt="메일보내기[검색된 사람에게]" /></a>&nbsp;&nbsp;
							<a href="javascript:js_emailsend4all();"><img src="../images/mail/mailing_btn_03.gif" alt="메일보내기[전체]" /></a>-->
						</ul>
						<p class="fRight">
							<? if ($sPageRight_I == "Y") { ?>
							<!--<a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a>-->
							<? } ?>
						</p>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="4%" /><!-- 번호 -->
							<col width="10%" /><!-- 썸네일 -->
							<col width="6%" /><!-- 년월 -->
							<col width="24%" /><!-- 타이틀 -->
							<col width="13%" /><!-- 시작일 -->
							<col width="13%" /><!-- 종료일 -->
							<col width="8%" /><!-- 참여자수 -->
							<col width="8%" /><!-- 등록일 -->
							<!--<col width="7%" />--><!-- 질문 등록 -->
							<col width="14%" /><!-- 당첨자 등록 -->
						</colgroup>

						<thead>
							<tr>
								<th>번호</th>
								<th>썸네일</th>
								<th>년월</th>
								<th>타이틀</th>
								<th>시작일</th>
								<th>종료일</th>
								<th>참여자수</th>
								<th>등록일</th>
								<!--<th>질문 등록</th>-->
								<th>응모자 조회</th>
							</tr>
						</thead>
						<tbody>

						<?
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn								= trim($arr_rs[$j]["rn"]);
									$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
									$W_SEQ_NO					= trim($arr_rs[$j]["W_SEQ_NO"]);
									$YYYY							= trim($arr_rs[$j]["YYYY"]);
									$MM								= trim($arr_rs[$j]["MM"]);
									$TYPE							= trim($arr_rs[$j]["TYPE"]);
									$S_DATE						= trim($arr_rs[$j]["S_DATE"]);
									$E_DATE						= trim($arr_rs[$j]["E_DATE"]);
									$TITLE						= SetStringFromDB($arr_rs[$j]["TITLE"]);
									$MEMO							= SetStringFromDB($arr_rs[$j]["MEMO"]);
									$IMAGE01					= trim($arr_rs[$j]["IMAGE01"]);
									$APPLY_CNT				= trim($arr_rs[$j]["APPLY_CNT"]);
									$HIT_CNT					= trim($arr_rs[$j]["HIT_CNT"]);
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
									
									if ($REQ_CNT =="") $REQ_CNT = 0;

									if (($TYPE == "TYPE01") && ($IMAGE01 == "")) {
										$IMAGE01 = "/images/temp_img_01.jpg";
									} else if (($TYPE == "TYPE02") && ($IMAGE01 == "")) {
										$IMAGE01 = "/images/temp_img_02.jpg";
									} else if (($TYPE == "TYPE03") && ($IMAGE01 == "")) {
										$IMAGE01 = "/images/temp_img_03.jpg";
									} else {
										$IMAGE01 = "/upload_data/webzine/".$IMAGE01;
									}

									if ($USE_TF == "Y") {
										$STR_USE_TF = "<font color='blue'>공개</font>";
									} else {
										$STR_USE_TF = "<font color='red'>비공개</font>";
									}
					?>

							<tr <? if ($j == (sizeof($arr_rs)-1)) {?> class="last" <? } ?> >
								<td><?=sizeof($arr_rs) - $j?></td>
								<td><a href="javascript:js_read('<?=$SEQ_NO?>');"><img src="<?=$IMAGE01?>" style="width:150px"></a></td>
								<td><a href="javascript:js_read('<?=$SEQ_NO?>');"><?=$YYYY?>년 <?=$MM?>월</a></td>
								<td><a href="javascript:js_read('<?=$SEQ_NO?>');"><?=$TITLE?></a></td>
								<td><?=$S_DATE?></td>
								<td><?=$E_DATE?></td>
								<td><?=number_format($APPLY_CNT)?></td>
								<td><?=substr($REG_DATE ,0,10)?></td>
								<!--
								<td>
									<? if ($TYPE == "TYPE03") { ?>
									<a href="javascript:js_question_write('<?=$SEQ_NO?>');">질문등록</a>
									<? } else { ?>
									&nbsp;
									<? } ?>
								</td>
								-->
								<td><a href="javascript:js_apply_list('<?=$SEQ_NO?>');">응모자조회</a></td>
							</tr>
						<?
								}
							} else { 
						?>
							<tr>
								<td align="center" height="50" colspan="17">데이터가 없습니다. </td>
							</tr>
						<? 
							}
						?>

						</tbody>
					</table>
				</fieldset>
			</form>
			<div class="sp20"></div>
		</div>
		</section>
		<iframe src="about:blank" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?//=$_SERVER['SERVER_NAME']?>

<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
