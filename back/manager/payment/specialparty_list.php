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

	$is_direct = "Y";

#===============================================================
# Get Search list count
#===============================================================
	$nListCnt =totalCntSpeParty($conn, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listSpeParty($conn, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별당비 리스트 조회", "List");

	$holiday_str = getHolidayList($conn);

	$set_date = date("Y-m-d",strtotime("1 day"));
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

		mindt = "<?=$set_date?>";

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

	function js_del_mem(seq_no) {
		var frm = document.frm;
		bDelOK = confirm('선택하신 회원을 탈퇴처리 하시겠습니까?');
		if (bDelOK) {
			frm.mode.value = "D";
			frm.seq_no.value = seq_no;
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_modify(seq) {

		var frm = document.frm;
		frm.seq_no.value = seq;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "specialparty_modify.php";
		frm.submit();
		
	}

	function js_write(){

		var frm = document.frm;
		frm.target = "";
		frm.method = "get";
		frm.action = "specialparty_write.php";
		frm.submit();
		
	}


	function js_mem_list(seq){
		var frm = document.frm;
		frm.p_no.value = seq;
		frm.target = "";
		frm.method = "get";
		frm.action = "specialparty_member_list.php";
		frm.submit();
	}

	function js_pay_res(seq) {

		var frm = document.frm;
		frm.p_no.value = seq;
		frm.target = "";
		frm.method = "get";
		frm.action = "specialparty_payment_list.php";
		frm.submit();

	}


	function js_upload_form(seq) {
		var frm = document.frm;
		frm.p_no.value = seq;
		frm.target = "";
		frm.method = "get";
		frm.action = "upload.php";
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

			bDelOK = confirm('선택하신 회원을 탈퇴처리 하시겠습니까?');
		
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
	}
	
	var chk_flag = false;

	function js_pay_req(s_seq_no) {
		
		if (chk_flag == false) {

			var req_date = $("#sel_pick_date_"+s_seq_no).val();
		
			if (req_date == "") {
				alert("출금일을 지정해 주세요.");
				return;
			}

			bDelOK = confirm('선택하신 출금일에 출금요청 하시겠습니까?\n출금 요청하시면 해당 출금요청이 처리 되기 전 까지 \n다시 출금요청 하실 수 없습니다.');

			if (bDelOK==true) {

				chk_flag = true;

				$.get("/manager/payment/make_payreq_file.php", 
					{ req_date:req_date, s_seq_no:s_seq_no }, 
			
					function(data){
						if (data == "T") {
							js_search();
						}
					}
				);
			}

		} else {
			alert("출금 전표를 생성하고 있습니다.");
			return;
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
<input type="hidden" name="p_no" value="">
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
								<td colspan="3">
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
						</ul>
						<p class="fRight">
							<? if ($sPageRight_I == "Y") { ?>
							<a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a>
							<? } ?>
						</p>
					</div>
					<div class="sp5"></div>
					<div style="padding-left:3%">
						<font color="red"><b>
							* 출금 신청 시 출금일은 다른 출금일과 같이 중복 될 수 없습니다.<br>
							* 출금 신청은 오후 4시 이전에 하셔야 합니다. (주말 또는 공휴일)에는 출금 하실 수 없습니다.
						</b></font>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="10%" /><!-- 번호 -->
							<col width="20%" /><!-- 제목 -->
							<col width="10%" /><!-- 출금일 -->
							<col width="10%" /><!-- 등록일 -->
							<col width="10%" /><!-- 등록당원수 -->
							<col width="10%" /><!-- 등록당비 -->
							<col width="13%" /><!-- 기타 -->
							<col width="17%" /><!-- 기타 -->
							</colgroup>

						<thead>
							<tr>
								<th>번호</th>
								<th>제목</th>
								<th>출금예정일</th>
								<th>등록일</th>
								<th>등록당원수</th>
								<th>등록당비</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>

						<?
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									//SEQ_NO, TITLE, PAY_DATE, REG_DATE

									$rn								= trim($arr_rs[$j]["rn"]);
									$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
									$TITLE						= trim($arr_rs[$j]["TITLE"]);
									$PAY_DATE					= trim($arr_rs[$j]["PAY_DATE"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
									$CNT_MEMBER				= trim($arr_rs[$j]["CNT_MEMBER"]);
									$SUM_AMOUNT				= trim($arr_rs[$j]["SUM_AMOUNT"]);
									$TEMP01						= trim($arr_rs[$j]["TEMP01"]);

					?>

							<tr <? if ($j == (sizeof($arr_rs)-1)) {?> class="last" <? } ?> >
								<td><?=$rn?></td>
								<td style="text-align:left"><a href="javascript:js_modify('<?=$SEQ_NO?>');"><?=$TITLE?></a></td>
								<td><?=$PAY_DATE?></td>
								<td><?=substr($REG_DATE ,0,10)?></td>
								<td style="text-align:right"><?=number_format($CNT_MEMBER)?> 명</td>
								<td style="text-align:right"><?=number_format($SUM_AMOUNT)?> 원</td>
								<td>
									<input type="button" name="aa" value=" 대상등록 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="js_upload_form('<?=$SEQ_NO?>');">&nbsp;
									<input type="button" name="bb" value=" 대상조회 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="js_mem_list('<?=$SEQ_NO?>');">&nbsp;
								</td>
								
								<? if ($TEMP01 == "") { ?>
								<td style="text-align:left">
									출금일 : <input type="text" name="sel_pick_date" id="sel_pick_date_<?=$SEQ_NO?>" class="date" value="" style="width:100px;border:1px solid #dfdfdf;" readonly="1">
									<input type="button" name="cc" value=" 출금신청 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="js_pay_req('<?=$SEQ_NO?>');">
								</td>
								<? } else { ?>
								<td style="text-align:left">
									출금일 : <b><?=$TEMP01?></b>&nbsp;&nbsp;&nbsp;
									<input type="button" name="dd" value=" 출금내역조회 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="js_pay_res('<?=$SEQ_NO?>');">
								</td>
								<? } ?>
								
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
				</fieldset>
			</form>
			<div class="btnArea">
				<ul class="fRight">
					<!--
					<input type="button" name="aa" value=" 엑셀 리스트 " class="btntxt"  style="cursor:hand" onclick="js_excel_list();"> 
					-->
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
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

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
