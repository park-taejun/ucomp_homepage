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

	$menu_right = "WB001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$seq_no							= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$con_yyyy						= $_POST['con_yyyy']!=''?$_POST['con_yyyy']:$_GET['con_yyyy'];
	$con_mm							= $_POST['con_mm']!=''?$_POST['con_mm']:$_GET['con_mm'];
	$con_use_tf					= $_POST['con_use_tf']!=''?$_POST['con_use_tf']:$_GET['con_use_tf'];

	if ($mode == "T") {
		updateWebzineUseTF($conn, $use_tf, $_SESSION['s_adm_id'], $seq_no);
	}

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

	$nListCnt =totalCntWebzine($conn, $con_yyyy, $con_mm, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listWebzine($conn, $con_yyyy, $con_mm, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);


	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "웹진 리스트 조회", "List");

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
		bDelOK = confirm('선택하신 웹진을 삭제 하시겠습니까?');
		if (bDelOK) {
			frm.mode.value = "D";
			frm.seq_no.value = seq_no;
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_modify(rn, seq) {

		var frm = document.frm;
		frm.m_no.value = seq;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "webzine_modify.php";
		frm.submit();
		
	}

	function js_read(seq) {

		var frm = document.frm;
		frm.seq_no.value = seq;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "webzine_write.php";
		frm.submit();
		
	}

	function js_write(){

		var frm = document.frm;
		frm.target = "";
		frm.method = "post";
		frm.action = "webzine_write.php";
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
									<select name="search_field" style="width:84px;" onChange="js_search_field();">
										<option value="TITLE" <? if ($search_field == "TITLE") echo "selected"; ?> >웹진타이틀</option>
										<option value="MEMO" <? if ($search_field == "MEMO") echo "selected"; ?> >웹진설명</option>
										<option value="VOL_NO" <? if ($search_field == "VOL_NO") echo "selected"; ?> >출판번호</option>
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
							<col width="10%" /><!-- 년월 -->
							<col width="32%" /><!-- 웹진타이틀 -->
							<col width="10%" /><!-- 출판일 -->
							<col width="10%" /><!-- 출판번호 -->
							<!--<col width="8%" /> 비주얼(배경) -->
							<!--<col width="8%" /> 비주얼(모바일배경) -->
							<!--<col width="8%" /> 비주얼(텍스트) -->
							<col width="8%" /><!-- PDF -->
							<col width="6%" /><!-- 사용여부-->
							<col width="8%" /><!-- 등록일 -->
						</colgroup>

						<thead>
							<tr>
								<th>번호</th>
								<th>썸네일</th>
								<th>년월</th>
								<th>웹진타이틀</th>
								<th>출판일</th>
								<th>출판번호</th>
								<!--
								<th>비주얼(배경)</th>
								<th>비주얼(모바일배경)</th>
								<th>비주얼(텍스트)</th>
								-->
								<th>PDF</th>
								<th>사용여부</th>
								<th>등록일</th>
							</tr>
						</thead>
						<tbody>

						<?
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn								= trim($arr_rs[$j]["rn"]);
									$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
									$YYYY							= trim($arr_rs[$j]["YYYY"]);
									$MM								= trim($arr_rs[$j]["MM"]);
									$PUB_DATE					= trim($arr_rs[$j]["PUB_DATE"]);
									$VOL_NO						= trim($arr_rs[$j]["VOL_NO"]);
									$TITLE						= SetStringFromDB($arr_rs[$j]["TITLE"]);
									$MEMO							= SetStringFromDB($arr_rs[$j]["MEMO"]);
									$MAIN_IMAGE01			= trim($arr_rs[$j]["MAIN_IMAGE01"]);
									$MAIN_IMAGE02			= trim($arr_rs[$j]["MAIN_IMAGE02"]);
									$MAIN_IMAGE03 		= trim($arr_rs[$j]["MAIN_IMAGE03"]);
									$PDF_IMAGE				= trim($arr_rs[$j]["PDF_IMAGE"]);
									$PDF_FILE					= trim($arr_rs[$j]["PDF_FILE"]);
									$HIT_CNT					= trim($arr_rs[$j]["HIT_CNT"]);
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);

									if ($USE_TF == "Y") {
										$STR_USE_TF = "<font color='blue'>공개</font>";
									} else {
										$STR_USE_TF = "<font color='red'>비공개</font>";
									}
					?>

							<tr <? if ($j == (sizeof($arr_rs)-1)) {?> class="last" <? } ?> >
								<td><?=$rn?></td>
								<td><a href="javascript:js_read('<?=$SEQ_NO?>');"><img src="/upload_data/webzine/<?=$PDF_IMAGE?>" style="width:150px"></a></td>
								<td><a href="javascript:js_read('<?=$SEQ_NO?>');"><?=$YYYY?>년 <?=$MM?>월</a></td>
								<td><a href="javascript:js_read('<?=$SEQ_NO?>');"><?=$TITLE?></a></td>
								<td><?=$PUB_DATE?></td>
								<td><?=$VOL_NO?></td>
								<!--
								<td><img src="/upload_data/webzine/<?=$MAIN_IMAGE01?>" style="width:50px"></td>
								<td><img src="/upload_data/webzine/<?=$MAIN_IMAGE02?>" style="width:50px"></td>
								<td><img src="/upload_data/webzine/<?=$MAIN_IMAGE03?>" style="width:50px"></td>
								-->
								<td>
									<? if ($PDF_FILE <> "") {?>
									<font color='blue'>등록</font>
									<? } else { ?>
									<font color='red'>미등록</font>
									<? } ?>
								</td>
								<td><a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a></td>
								<td><?=substr($REG_DATE ,0,10)?></td>
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
if($Ngroup_cd || $sel_party) {
?>
	<script type="text/javascript">
	<!--
		js_sel_party('<?=$group_cd_01?>', '<?=$group_cd_02?>', '<?=$group_cd_03?>', '<?=$group_cd_04?>', '<?=$group_cd_05?>');
	//-->
	</script>
	<?
}
?>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
