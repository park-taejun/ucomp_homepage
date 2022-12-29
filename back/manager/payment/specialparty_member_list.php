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

	$menu_right = "PM003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/payment/payment.php";
#====================================================================
# Request Parameter
#====================================================================

	// 로그인한 관리자 권한에 따라 보여주는 내용이 다릅니다. -- 여기 부터

	if (($_SESSION['s_adm_position_code'] == "") || ($_SESSION['s_adm_position_code'] == "중앙당")) {
		$sel_area_cd = trim($sel_area_cd);
	} else {
		$sel_area_cd = $_SESSION['s_adm_position_code'];
	}

	if (($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) {
		$sel_party = trim($sel_party);
	} else {
		$sel_party = $_SESSION['s_adm_dept_code'];
	}
	
	// --------------------------------------------------------- 여기 까지

	$arr_year = getPaymentYear($conn);
	

	if ($mode == "D") {

		$row_cnt = count($chk);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_seq_no = $chk[$k];

			$temp_result = deleteToRealSpeMember($conn, $tmp_seq_no);
		}

	}

	if ($mode == "U") {

		$row_cnt = count($M_NO);
		for ($k = 0; $k < $row_cnt; $k++) {

			$tmp_b_no = (int)$M_NO[$k];
			$m_levels=(int)$m_level[$k];
			$result= changeMemberlevel($conn, $tmp_b_no, $m_levels);

		}
	}

	if (($mode == "U")&&($result)) {
		?>
		<script type="text/javascript">
		<!--
			alert('수정되었습니다.');
		//-->
		</script>
		<?
	}

	if (($mode == "D")&&($temp_result)) {
		?>
		<script type="text/javascript">
		<!--
			alert('삭제되었습니다.');
		//-->
		</script>
		<?
	}

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
	
	if ($order_field == "")
		$order_field = "M_DATETIME";
	
	if ($order_str=="")
		$order_str = "DESC";

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

	$is_direct = "N";

#===============================================================
# Get Search list count
#===============================================================
	$nListCnt =totalCntSpeMember($conn, $p_no, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listSpeMember($conn, $p_no, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별당비 납부 대상 조회", "List");
	
	$SUM_CMS_AMOUNT = listSumSpeMemberAmount($conn, $p_no, $search_field, $search_str);


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

	var dt	= new Date();
	var y		= dt.getFullYear(); 
	var m		= dt.getMonth() + 1; 
	var d		= dt.getDate() + 1; 
	var h		= dt.getHours(); 

	//if (h >= 17) d++;

	mindt = y+"-"+m+"-"+d;

	$(".date").datepicker({
		dateFormat: "yy-mm-dd"
		,minDate: mindt
	//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
		,beforeShowDay: disableAllTheseDays
	});

		$( "#search_str" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});


});

var disabledDays = [<?=$holiday_str?>]; 

function noWeekendsOrHolidays(date) { 
	var noWeekend = jQuery.datepicker.noWeekends(date); 
	return noWeekend[0] ? [true] : noWeekend; 
}

function disableAllTheseDays(date) { 
	var m = date.getMonth(), d = date.getDate(), y = date.getFullYear(); 
	for (i = 0; i < disabledDays.length; i++) { 
		if($.inArray(y + '-' +(m+1) + '-' + d,disabledDays) != -1) { 
			return [false]; 
		} 
	}

	var noWeekend = jQuery.datepicker.noWeekends(date); 
	return noWeekend[0] ? [true] : noWeekend; 
	//return [true];
} 


	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
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
			alert("선택 하신 회원이 없습니다.");
		} else {

			bDelOK = confirm('선택하신 회원을 삭제처리 하시겠습니까?');
		
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
	}

	function js_excel_list() {

		var frm = document.frm;
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER[PHP_SELF])?>";
		frm.submit();

	}

	function js_modify(seq){
		var frm = document.frm;
		frm.seq_no.value = seq;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "specialparty_member_modify.php";
		frm.submit();
	}

	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "specialparty_list.php";
		frm.submit();
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
<input type="hidden" name="p_no" value="<?=$p_no?>">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							
							<tr class="last">
								<th>검색조건</th>
								<td>
									<select name="nPageSize" style="width:84px;">
										<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >20개씩</option>
										<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >50개씩</option>
										<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >100개씩</option>
										<option value="200" <? if ($nPageSize == "200") echo "selected"; ?> >200개씩</option>
										<option value="300" <? if ($nPageSize == "300") echo "selected"; ?> >300개씩</option>
										<option value="400" <? if ($nPageSize == "400") echo "selected"; ?> >400개씩</option>
										<option value="500" <? if ($nPageSize == "500") echo "selected"; ?> >500개씩</option>
									</select>&nbsp;
									<select name="search_field" style="width:84px;">
										<option value="M_NAME" <? if ($search_field == "M_NAME") echo "selected"; ?> >성명</option>
										<option value="M_BIRTH" <? if ($search_field == "M_BIRTH") echo "selected"; ?> >아이디</option>
									<!--	<option value="M_HP" <? if ($search_field == "M_HP") echo "selected"; ?> >휴대전화</option>-->
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
						</ul>
						<p class="fRight">
							<a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a>
						</p>
					</div>
					<div class="sp5"></div>
					<div id="pre_amout">
					</div>

					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="5%" /><!-- 체크박스 -->
							<col width="5%" /><!-- 번호 -->
							<col width="10%" /><!-- 이름 -->
							<col width="15%" /><!-- 생년월일 -->
							<col width="15%" /><!-- 전화번호 -->
							<col width="15%" /><!-- 연락처 -->
							<col width="15%" /><!-- 특별당비금액 -->
							<col width="15%" /><!-- 소속당 -->
							<col width="15%" /><!-- 지역 -->
						</colgroup>

						<thead>
							<tr>
								<th>&nbsp;</th>
								<th>번호</th>
								<th>이름</th>
								<th>생년월일</th>
								<th>전화번호</th>
								<th>휴대전화</th>
								<th>특별당비금액</th>
								<th>소속지역</th>
								<th>소속당</th>
							</tr>
						</thead>
						<tbody>

						<?
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									//SEQ_NO, P_SEQ_NO, M_NAME, M_BIRTH, M_HP, AMOUNT
									$rn								= trim($arr_rs[$j]["rn"]);
									$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
									$P_SEQ_NO					= trim($arr_rs[$j]["P_SEQ_NO"]);
									$M_NAME						= trim($arr_rs[$j]["M_NAME"]);
									$M_BIRTH					= trim($arr_rs[$j]["M_BIRTH"]);
									$M_HP							= trim($arr_rs[$j]["M_HP"]);
									$AMOUNT						= trim($arr_rs[$j]["AMOUNT"]);
									$M_TEL						= trim($arr_rs[$j]["M_TEL"]);
									$M_3							= trim($arr_rs[$j]["M_3"]);
									$SIDO							= trim($arr_rs[$j]["SIDO"]);
									$str_m_tel				= decrypt($key, $iv, $M_TEL);
									$str_m_hp					= decrypt($key, $iv, $M_HP);

						?>

							<tr <? if ($j == (sizeof($arr_rs)-1)) {?> class="last" <? } ?> >
								<td class="filedown" style="text-align:center">
								<input type="checkbox" name="chk[]" value="<?=$SEQ_NO?>">
							</td>
								<td><?=$rn?></td>
								<td><a href="javascript:js_modify('<?=$SEQ_NO?>');"><?=$M_NAME?></a></td>
								<td><?=$M_BIRTH?></td>
								<td><?=$str_m_tel?></td>
								<td><?=$str_m_hp?></td>
								<td style="text-align:right;padding-right:40px"><?=number_format($AMOUNT)?> 원</td>
								<td><?=$SIDO?></td>
								<td><?=$M_3?></td>
							</tr>
						<?			
								}
							} else { 
						?>
							<tr>
								<td align="center" height="50" colspan="9">데이터가 없습니다. </td>
							</tr>
						<? 
							}
						?>

						</tbody>
					</table>


					<br><br>
					<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
						<caption>내용 입력란</caption>
						<colgroup>
							<col width="10%" />
							<col width="10%" />
							<col width="80%" />
						</colgroup>
						<tr>
							<th>특별당비 총액</th>
							<td><b><?=number_format($SUM_CMS_AMOUNT )?></b> 원</td>
							<td>&nbsp;</td>
						</tr>
					</table>


				</fieldset>
			</form>
			<div class="btnArea">
				<ul class="fRight">
					<? if ($sPageRight_F == "Y") { ?>
					<input type="button" name="aa" value=" 선택삭제 " class="btntxt" style="cursor:pointer;height:25px;" onclick="js_delete();"> 
					<input type="button" name="aa" value=" 엑셀 리스트 " class="btntxt" style="cursor:pointer;height:25px;" onclick="js_excel_list();"> 
					<? } ?>
				</ul>
			</div>
			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&p_no=".$p_no;

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
