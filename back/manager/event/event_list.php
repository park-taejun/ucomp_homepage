<?session_start();?>
<?
	extract($_POST);
	extract($_GET);

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "EV002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

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
	require "../../_classes/biz/event/event.php";

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
	
	if ($order_field == "")
		$order_field = "REG_DATE";
	
	if ($order_str=="")
		$order_str = "DESC";

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
	
	$nListCnt =totalCntEvent($conn, $con_ev_type, $ev_start, $ev_end, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listEvent($conn, $con_ev_type, $ev_start, $ev_end, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
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

	// 조회 버튼 클릭 시 
	function js_reload() {
		var frm = document.frm;
		frm.method = "get";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_write() {
		var frm = document.frm;
		frm.target = "";
		frm.action = "event_write.php";
		frm.submit();
	}

	function js_view(seq_no) {

		var frm = document.frm;
		
		frm.seq_no.value = seq_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "event_read.php";
		frm.submit();
		
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}


	function js_excel() {
		
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER[PHP_SELF])?>";
		frm.submit();

	}

	function js_sel_member(seq_no) {
		var url = "event_mem_popup.php?mode=S&seq_no="+seq_no;
		NewWindow(url, '대상선택', '1017', '800', 'YES');
	}

	function js_send_push(seq_no, tlen) {

		var frm = document.frm;
		
		//if(tlen>80){
		//	alert('제목 글자수에 제한이 있습니다.(40자)\r\n내용을 줄여주세요');
		//	return;
		//}
		
		bDelOK = confirm('해당 내용으로 문자를 전송 하시겠습니까?');

		if (bDelOK==true) {
			frm.seq_no.value = seq_no;
			frm.mode.value = "SEND";
			frm.method = "post";
			frm.target = "";
			frm.action = "event_send.php";
			frm.submit();
		}

		//alert("준비 중 입니다.");
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
<input type="hidden" name="type" value="EVENT">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">


				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							<tr class="last">
								<th>검색조건</th>
								<td>
										<select name="search_field" style="width:84px;">
											<option value="" <? if ($search_field == "title") echo "selected"; ?> >제목</option>
										</select>&nbsp;

									<input type="text" value="<?=$search_str?>" name="search_str" size="15"class="txt" />
									<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
								</td>
							</tr>
						</tbody>
					</table>

					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 <?=$nListCnt?>건</li>
							<!--<li class="file">파일 저장하기&nbsp;<a href="#"><img src="../images/bu/ic_file.gif" alt="엑셀파일 아이콘" /></a></li>-->
						</ul>

						<p class="fRight">
							<? if ($sPageRight_I == "Y") {?>
							<a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a>
							<? } ?>
						</p>
					</div>

					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm" id='t'>
					
					<colgroup>
						<col width="5%" />
						<col width="30%" />
						<col width="15%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>내용</th>
							<th>기간</th>
							<th>대상구분</th>
							<th>대상선택</th>
							<th>대상인원</th>
							<th>전송구분</th>
							<th class="end">전송</th>
						</tr>
					</thead>
					<tbody>
					<?
						$nCnt = 0;
						
						if (sizeof($arr_rs) > 0) {
							
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
								$rn							= trim($arr_rs[$j]["rn"]);
								$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
								$EV_TYPE				= trim($arr_rs[$j]["EV_TYPE"]);
								$TITLE					= trim($arr_rs[$j]["TITLE"]);
								$EV_START				= trim($arr_rs[$j]["EV_START"]);
								$EV_START_TIME	= trim($arr_rs[$j]["EV_START_TIME"]);
								$EV_END					= trim($arr_rs[$j]["EV_END"]);
								$EV_END_TIME		= trim($arr_rs[$j]["EV_END_TIME"]);
								$EV_QUERY				= trim($arr_rs[$j]["EV_QUERY"]);
								$ALL_FLAG				= trim($arr_rs[$j]["ALL_FLAG"]);
								$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
								$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
								$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
								$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));

								if($USE_TF=="Y" || $USE_TF==""){
									$use_state="미전송";
								}else{
									$use_state="전송";
								}

								if($ALL_FLAG=="Y"){
									$STR_ALL_FLAG="회원전체";
								}else{
									$STR_ALL_FLAG="선택회원";
								}

					?>
					<tr>
						<td class="sort"><?=$rn?></td>
						<td style="text-align:left"><a href="javascript:js_view('<?=$SEQ_NO?>');"><?=$TITLE?></a></td>
						<td>
							<? if ($EV_START) { ?>
							<?=$EV_START?> ~ <?=$EV_END?>
							<? } else { ?>
								&nbsp;
							<? } ?>
						</td>
						<td><?=$STR_ALL_FLAG?></td>
						<td>
						<? if($ALL_FLAG!="Y"){ ?>
							<input type="button" name="btn" value=" 대상선택 " class="btntxt" onclick="js_sel_member('<?=$SEQ_NO?>');" style="border: 1px solid #a6a6a6;">
						<? } ?>
						</td>
						<?
							$all_mem = getAllEventMem($conn, $SEQ_NO, trim($ALL_FLAG));
						?>
						<td>
							<?=number_format($all_mem)?>
						</td>
						<td><?=$use_state?></td>
						<td>
						<?		if ($all_mem == 0) { ?>
							<input type="button" name="btn" value=" SMS 전송 " class="btntxt" onclick="alert('먼저 대상을 선택하세요.');" style="border: 1px solid #a6a6a6;">
						<?		} else { ?>
							<input type="button" name="btn" value=" SMS 전송 " class="btntxt" onclick="js_send_push('<?=$SEQ_NO?>','<?=strlen($TITLE)?>');" style="border: 1px solid #a6a6a6;">
						<?		} ?>
						</td>
					</tr>
				<?			
						}
					} else { 
				?>
					<tr>
						<td align="center" height="50" colspan="7">데이터가 없습니다. </td>
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
							$strParam = $strParam."&nPageSize=".$nPageSize."&bb_code=".$bb_code."&search_field=".$search_field."&search_str=".$search_str;
							$strParam = $strParam."&con_position_code=".$con_position_code;
							$strParam = $strParam."&order_field=".$order_field."&order_str=".$order_str;

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
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
