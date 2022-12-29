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

	$menu_right = "WB002"; // 메뉴마다 셋팅 해 주어야 합니다

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

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$con_yyyy						= $_POST['con_yyyy']!=''?$_POST['con_yyyy']:$_GET['con_yyyy'];
	$con_mm							= $_POST['con_mm']!=''?$_POST['con_mm']:$_GET['con_mm'];
	$con_type						= $_POST['con_type']!=''?$_POST['con_type']:$_GET['con_type'];

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
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntWebzineEvent($conn, $con_yyyy, $con_mm, $con_type, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listWebzineEvent($conn, $con_yyyy, $con_mm, $con_type, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);


	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "웹진 이벤트 리스트 조회", "List");

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
		frm.action = "event_write.php";
		frm.submit();
		
	}

	function js_write(){

		var frm = document.frm;
		frm.target = "";
		frm.method = "post";
		frm.action = "event_write.php";
		frm.submit();
		
	}


	function js_question_write(seq_no) {
		var url = "popup_question.php?eseq_no="+seq_no;
		NewWindow(url, '질문등록', '1114', '760', 'YES');
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
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							<tr>
								<th>년 월</th>
								<td>
									<select style="width:150px" name="con_yyyy" id="con_yyyy">
										<option value="">년도를 선택 하세요</option>
										<?
											$this_yyyy = date("Y",strtotime("0 month"));

											for ($h = $this_yyyy ; $h > ($this_yyyy -10) ; $h--) {
										?>
										<option value="<?=$h?>" <? if ($h == $con_yyyy) echo "selected"; ?> ><?=$h?></option>
										<?
											}
										?>
									</select>&nbsp;

									<select style="width:150px" name="con_mm" id="con_mm">
										<option value="">월을 선택 하세요</option>
										<?
											for ($h = 1 ; $h < 13; $h++) {
												$s_mm = right("0".$h, 2);
												if ($s_mm == $con_mm) {
										?>
										<option value="<?=$s_mm?>" selected><?=$s_mm?></option>
										<?
												} else {
										?>
										<option value="<?=$s_mm?>"><?=$s_mm?></option>
										<?
												}
											}
										?>
									</select>

								</td>
								<th>검색조건</th>
								<td>

									<?= makeSelectBox($conn,"EVENT_TYPE","con_type","125","선택","",$con_type)?>&nbsp;

									<select name="search_field" style="width:84px;" onChange="js_search_field();">
										<option value="TITLE" <? if ($search_field == "TITLE") echo "selected"; ?> >제목</option>
									</select>&nbsp;

									<input type="text" value="<?=$search_str?>" name="search_str" id="search_str" size="15"class="txt" />
									<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
								</td>
							</tr>
						</tbody>
					</table>

					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 <?=number_format($nListCnt)?>건</li>
							<!--<li class="file">파일 저장하기&nbsp;<a href="#"><img src="../images/bu/ic_file.gif" alt="엑셀파일 아이콘" /></a></li>
							<?if($_SERVER['REMOTE_ADDR']=="203.229.173.93"){?>
							<?}?>
							<a href="javascript:js_emailsend4check();"><img src="../images/mail/mailing_btn_01.gif" alt="메일보내기[선택된 사람에게]" /></a>&nbsp;&nbsp;
							<a href="javascript:js_emailsend4search();"><img src="../images/mail/mailing_btn_02.gif" alt="메일보내기[검색된 사람에게]" /></a>&nbsp;&nbsp;
							<a href="javascript:js_emailsend4all();"><img src="../images/mail/mailing_btn_03.gif" alt="메일보내기[전체]" /></a>-->
						</ul>
						<p class="fRight">
							<? if ($sPageRight_I == "Y") { ?>
							<a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a>
							<? } ?>
						</p>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="4%" /><!-- 번호 -->
							<col width="10%" /><!-- 썸네일 -->
							<!-- <col width="6%" />년월 -->
							<col width="20%" /><!-- 타이틀 -->
							<col width="14%" /><!-- 시작일 -->
							<col width="14%" /><!-- 종료일 -->
							<col width="8%" /><!-- 참여자수 -->
							<col width="8%" /><!-- 참여자수 -->
							<col width="8%" /><!-- 등록일 -->
							<col width="7%" /><!-- 질문 등록 -->
							<col width="7%" /><!-- 당첨자 등록 -->
						</colgroup>

						<thead>
							<tr>
								<th>번호</th>
								<th>썸네일</th>
								<!--<th>년월</th>-->
								<th>타이틀</th>
								<th>시작일</th>
								<th>종료일</th>
								<th>참여자수</th>
								<th>사용여부</th>
								<th>등록일</th>
								<th>질문 등록</th>
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
									$S_HOUR						= trim($arr_rs[$j]["S_HOUR"]);
									$S_MIN						= trim($arr_rs[$j]["S_MIN"]);
									$E_DATE						= trim($arr_rs[$j]["E_DATE"]);
									$E_HOUR						= trim($arr_rs[$j]["E_HOUR"]);
									$E_MIN						= trim($arr_rs[$j]["E_MIN"]);
									$TITLE						= SetStringFromDB($arr_rs[$j]["TITLE"]);
									$MEMO							= SetStringFromDB($arr_rs[$j]["MEMO"]);
									$IMAGE01					= trim($arr_rs[$j]["IMAGE01"]);
									$APPLY_CNT				= trim($arr_rs[$j]["APPLY_CNT"]);
									$HIT_CNT					= trim($arr_rs[$j]["HIT_CNT"]);
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
									

									if ($S_HOUR =="") $S_HOUR = "00";
									if ($S_MIN =="") $S_MIN = "00";
									if ($E_HOUR =="") $E_HOUR = "00";
									if ($E_MIN =="") $E_MIN = "00";

									if ($REQ_CNT =="") $REQ_CNT = 0;

									if (($TYPE == "TYPE01") && ($IMAGE01 == "")) {

										if ($SEQ_NO > 98) {
											$IMAGE01 = "/images/temp_img_11.jpg";
										} else {
											$IMAGE01 = "/images/temp_img_01.jpg";
										}

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
								<td><?=$rn?></td>
								<td><a href="javascript:js_read('<?=$SEQ_NO?>');"><img src="<?=$IMAGE01?>" style="width:150px"></a></td>
								<!--<td><a href="javascript:js_read('<?=$SEQ_NO?>');"><?=$YYYY?>년 <?=$MM?>월</a></td>-->
								<? if ($SEQ_NO > 134) { ?>
								<td style="text-align:left;padding-left:20px"><a href="javascript:js_read('<?=$SEQ_NO?>');"><?=$TITLE?></a></td>
								<? } else { ?>
								<td style="text-align:left;padding-left:20px"><a href="javascript:js_read('<?=$SEQ_NO?>');">[<?=$YYYY?>-<?=$MM?>] <?=$TITLE?></a></td>
								<? } ?>

								<td><?=$S_DATE?> <?=$S_HOUR?>:<?=$S_MIN?></td>
								<td><?=$E_DATE?> <?=$E_HOUR?>:<?=$E_MIN?></td>
								<td><?=number_format($APPLY_CNT)?></td>
								<td><?=$STR_USE_TF?></td>
								<td><?=substr($REG_DATE ,0,10)?></td>
								<td>
									<? if ($TYPE == "TYPE03") { ?>
									<a href="javascript:js_question_write('<?=$SEQ_NO?>');">질문등록</a>
									<? } else { ?>
									&nbsp;
									<? } ?>
								</td>
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
			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&start_date=".$start_date."&end_date=".$end_date."&Ngroup_cd=".$Ngroup_cd;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>

					<!-- --------------------- 페이지 처리 화면 END -------------------------->
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
