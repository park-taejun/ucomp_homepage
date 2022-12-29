<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
#====================================================================
# File Name    : vacation_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-11-05
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
#====================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#====================================================================
# Confirm right
#====================================================================
	$menu_right = "VA002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/vacation/vacation.php";

	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$seq_no			= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];

	$arr_holiday_date = getHoliday($conn);

	if (sizeof($arr_holiday_date) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_holiday_date); $j++) {

			$H_DATE	= trim($arr_holiday_date[$j]["H_DATE"]);

			$arr_yearmmdd = explode("-", $H_DATE);

			$yy = $arr_yearmmdd[0];
			$mm = (int)$arr_yearmmdd[1];
			$dd = (int)$arr_yearmmdd[2];

			if ($unable_dates == "") {
				$unable_dates = "'".$yy."-".$mm."-".$dd."'";
			} else {
				$unable_dates = $unable_dates . ",'".$yy."-".$mm."-".$dd."'";
			}
		}
	}

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================

	#echo $adm_no;

	if ($mode == "I") {

		/*
		echo $va_user."<br>";
		echo $va_type."<br>";
		echo $va_sdate."<br>";
		echo $va_edate."<br>";
		echo $va_memo."<br>";
		echo $va_state."<br><br>";
		*/

		$arr_data = array("VA_TYPE"=>$va_type,
											"VA_SDATE"=>$va_sdate,
											"VA_EDATE"=>$va_edate,
											"VA_MEMO"=>$va_memo,
											"VA_USER"=>$va_user,
											"VA_STATE"=>$va_state,
											"DEL_TF"=>"N",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$new_seq_no = insertVacation($conn, $arr_data);
		
		if (($va_type == 1) || ($va_type == 7)) {
			$va_cnt = 0.5;
		} else if (($va_type == 2) || ($va_type == 3) || ($va_type == 4)) {
			$va_cnt = 1;
		} else {
			$va_cnt = 0;
		}

		$term = intval((strtotime($va_edate)-strtotime($va_sdate))/86400); //날짜 사이의 일수를 구한다.
		
		for($i=0; $i<=$term; $i++) {
			
			$tmp_date = date("Y-m-d", strtotime($va_sdate.'+'.$i.' day')); //두 날짜사이의 날짜를 구한다.
			$tmp_week = date("w",strtotime($va_sdate.'+'.$i.' day'));
			
			$is_weekend = "N";

			// 주말 인지 확인 합니다.
			if (($tmp_week == 6) || ($tmp_week == 0)) {
				$is_weekend = "Y";
			}

			if (isHoliday($conn, $tmp_date) == "true") {
				$is_weekend = "Y";
			}

			if ($is_weekend == "N") {

				// 휴일 날짜 등록
				$arr_data = array("SEQ_NO"=>$new_seq_no,
													"VA_DATE"=>$tmp_date,
													"VA_USER"=>$va_user,
													"VA_CNT"=>$va_cnt
													);

				$result = insertVacationDate($conn, $arr_data);

				//echo $tmp_date."<br>";

			}
			//echo date("w",strtotime($va_sdate.'+'.$i.' day'))."<br>";

		}
	}

	if ($rs_event_from == "") {
		$rs_event_from = date("Y-m-d",strtotime("0 day"));
	}

	if ($rs_event_to == "") {
		$rs_event_to = date("Y-m-d",strtotime("0 day"));
	}

	if ($rs_event_result == "") {
		$rs_event_result = date("Y-m-d",strtotime("0 day"));
	}

	if ($mode == "S") {
		
		$arr_rs = selectVacation($conn, $seq_no);
		
		if (sizeof($arr_rs) > 0) {
			$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]);
			$rs_va_type					= trim($arr_rs[0]["VA_TYPE"]);
			$rs_va_sdate				= trim($arr_rs[0]["VA_SDATE"]);
			$rs_va_edate				= trim($arr_rs[0]["VA_EDATE"]);
			$rs_va_memo					= trim($arr_rs[0]["VA_MEMO"]);
			$rs_va_user					= trim($arr_rs[0]["VA_USER"]);
			$rs_va_state				= trim($arr_rs[0]["VA_STATE"]);
		}

	}

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->

<script type="text/javascript">

$(document).ready(function() {
	$(".date").datepicker({
		prevText: "이전달",
		nextText: "다음달",
		monthNames: [ "1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월" ], 
		monthNamesShort: [ "1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월" ], 
		dayNames: [ "일요일","월요일","화요일","수요일","목요일","금요일","토요일" ], 
		dayNamesShort: [ "일","월","화","수","목","금","토" ], 
		dayNamesMin: [ "일","월","화","수","목","금","토" ], 
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd"
		,minDate: new Date(1970, 4-1, 15)
		,beforeShowDay: disableAllTheseDays
	});
});

var disabledDays = [<?=$unable_dates?>]; 
 
// 특정일 선택막기 
function disableAllTheseDays(date) { 
	var m = date.getMonth(), d = date.getDate(), y = date.getFullYear(); 
	for (i = 0; i < disabledDays.length; i++) { 
		if($.inArray(y + '-' +(m+1) + '-' + d,disabledDays) != -1) { 
			return [false]; 
		}
	} 
	
	<? if ($_SESSION['s_adm_group_no'] > 3) { ?>

	if (date < new Date()) {
		return [false];
	}

	<? } ?>

	var noWeekend = jQuery.datepicker.noWeekends(date); 
	return noWeekend[0] ? [true] : noWeekend; 
}  
 
// 토, 일요일 선택 막기 
function noWeekendsOrHolidays(date) {  
	var noWeekend = $.datepicker.noWeekends(date); 
	return noWeekend[0] ? [true] : noWeekend;
}

function js_save() {

	var frm = document.frm;
	var seq_no = "<?=$seq_no?>";
	
	frm.va_user.value = frm.va_user.value.trim();
	frm.va_type.value = frm.va_type.value.trim();
	frm.va_sdate.value = frm.va_sdate.value.trim();
	frm.va_edate.value = frm.va_edate.value.trim();
	frm.va_memo.value = frm.va_memo.value.trim();
	frm.va_state.value = frm.va_state.value.trim();

	if (seq_no == "") {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}

	if (frm.va_user.value == "") {
		alert('신청자를 선택해주세요.');
		frm.va_user.focus();
		return ;
	}

	if (frm.va_type.value == "") {
		alert('구분을 선택해주세요.');
		frm.va_type.focus();
		return ;
	}
	
	if (isNull(frm.va_sdate.value)) {
		alert('시작일을 입력해주세요.');
		frm.va_sdate.focus();
		return ;		
	}

	if (isNull(frm.va_edate.value)) {
		alert('종료일을 입력해주세요.');
		frm.va_edate.focus();
		return ;
	}

	if (isNull(frm.va_memo.value)) {
		alert('사유를 입력해주세요.');
		frm.va_memo.focus();
		return ;		
	}

	if (frm.va_state.value == "") {
		alert('상태를 선택해주세요.');
		frm.va_state.focus();
		return ;
	}

	// 기타 조건에 따른 제약조건
	if (frm.va_type.value == "1") {
		
		if (frm.va_sdate.value != frm.va_edate.value) {
			alert("반차 신청의 경우 시작일과 종료일이 동일해야 합니다.");
			return;
		}

	}

	if (frm.va_sdate.value > frm.va_edate.value) {
		alert("시작일이 종료일보다 작거나 같아야 합니다.");
		return;
	}

	var request = $.ajax({
		url:"/manager/vacation/vacation_dml.php",
		type:"POST",
		data:{mode:frm.mode.value
				 ,va_user:frm.va_user.value
				 ,va_type:frm.va_type.value
				 ,va_sdate:frm.va_sdate.value
				 ,va_edate:frm.va_edate.value
				 ,va_memo:frm.va_memo.value
				 ,va_state:frm.va_state.value
				 ,seq_no:seq_no
				 },
		dataType:"html"
	});

	request.done(function(msg) {

		var array_date = frm.va_sdate.value.split("-");

		//alert(array_date[0]);
		//alert(array_date[1]);

		if (msg == "T") {
			alert('저장 되었습니다.');
			document.location = "/manager/vacation/vacation.php?year="+array_date[0]+"&month="+array_date[1];
		} else if (msg == "UT") {
			alert('수정 되었습니다.');
			document.location = "/manager/vacation/vacation.php?year="+array_date[0]+"&month="+array_date[1];
			return;
		} else if (msg == "DUP") {
			alert('해당 일자에 신청된 연차가 있습니다.');
			return;
		} 
	});

	request.fail(function(jqXHR, textStatus) {
		alert("Request failed : " +textStatus);
		return false;
	});

}


function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('연차신청을 삭제 하시겠습니까?');
	
	if (bDelOK==true) {

		var seq_no = "<?=$seq_no?>";
		frm.mode.value = "D";

		var request = $.ajax({
			url:"/manager/vacation/vacation_dml.php",
			type:"POST",
			data:{mode:frm.mode.value
					 ,seq_no:seq_no
					 },
			dataType:"html"
		});

		request.done(function(msg) {

			if (msg == "T") {
				alert('삭제 되었습니다.');
				document.location = "/manager/vacation/vacation.php";
				return;
			} 
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});
	}
}

function js_list() {
	document.location = "vacation.php";
}


</script>

</head>
<body>

<div id="wrap">
<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>
	<!-- S: container -->
	<div class="container">
		<div class="contentsarea">
			<div class="menu-holiday">
				<h3><strong><?=$p_menu_name?></strong>
				</h3>

<form name="frm" method="post">
<input type="hidden" name="seq_no" value="" />
<input type="hidden" name="mode" value="" />

				<div class="boardwrite">

					<table>
						<colgroup>
							<col style="width:15%" />
							<col style="width:35%" />
							<col style="width:15%" />
							<col style="width:35%" />
						</colgroup>
						<tbody>
							<tr>
								<th>신청자</th>
								<td>
								<? if ($sPageRight_I <> "Y") { ?>
									<?=$s_adm_nm?>
									<input type="hidden" name="va_user" id="va_user" value="<?=$s_adm_no?>">
								<? } else { ?>
									<span class="optionbox">
										<?=makeEmpSelectBox($conn, "va_user" , "" , " 선택 ", "", $rs_va_user)?>
									</span>
								<? } ?>
								</td>
								<th>구분</th>
								<td colspan="3">
									<span class="optionbox">
										<?= makeSelectBox($conn,"VA_TYPE","va_type",""," 선택 ","",$rs_va_type)?>
									</span>
								</td>
							</tr>
							<tr>
								<th>기간</th>
								<td colspan="3">
									<span class="inpbox" style="width:120px"><input type="text" class="date" name="va_sdate" value="<?=$rs_va_sdate?>" autocomplete="off"/></span> 부터 ~ 
									<span class="inpbox" style="width:120px"><input type="text" class="date" name="va_edate" value="<?=$rs_va_edate?>" autocomplete="off"/></span> 까지 
								</td>
							</tr>

							<tr>
								<th>사유</th>
								<td colspan="3">
									<span class="textareabox">
										<textarea name="va_memo"><?=$rs_va_memo?></textarea>
									</span>
								</td>
							</tr>
							<? if ($sPageRight_I <> "Y") { ?>
								<input type="hidden" name="va_state" id="va_state" value="1">
							<? } else { ?>
							<tr>
								<th>상태</th>
								<td colspan="3">
									<span class="optionbox" style="width:125px">
										<?= makeSelectBox($conn,"VA_STATE","va_state",""," 선택 ","",$rs_va_state)?>
									</span>
								</td>
							</tr>
							<? } ?>
						</tbody>
					</table>
				</div>
				<div class="btnright">
					<? if ($seq_no <> "" ) {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">수정</button>
					<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					<? } else {  ?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<? } ?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>
			</div>
			<!-- // E: mwidthwrap -->

			</div>
		</div>
	</div>
</form>
	<!-- //S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>