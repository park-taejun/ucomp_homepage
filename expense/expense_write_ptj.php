<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
#====================================================================
# File Name    : expense_write.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2021-09-28
# Modify Date  : 2022-03-02
# Copyright    : Copyright @UCOM Corp. All Rights Reserved.
#====================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#====================================================================
# Confirm right
#====================================================================
	$menu_right = "EX001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/expense/expense.php";
	require "../approval/approval_mailform.php";

#====================================================================
	$savedir1 = $g_physical_path."upload_data/expense";
#====================================================================

	///* 각 계정 테스트용
	//$_SESSION['s_adm_no']="252";

	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];
	$year	 = "202206";
	//*/ 

	$mode			= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ex_no			= $_POST['ex_no']!=''?$_POST['ex_no']:$_GET['ex_no'];
	$exd_no			= $_POST['exd_no']!=''?$_POST['exd_no']:$_GET['exd_no'];
	$va_user		= $_POST['va_user']!=''?$_POST['va_user']:$_GET['va_user'];
	$ex_date		= $_POST['ex_date']!=''?$_POST['ex_date']:$_GET['ex_date'];
	
	

	if ($va_user == "") {
		$va_user = $s_adm_no;
	}

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

		$arr_data = array("EX_TITLE"=>$ex_title,
											"EX_DATE"=>$ex_date,
											"EX_MEMO"=>$ex_memo,
											"EX_TOTAL_PRICE"=>$ex_total_price,
											"VA_USER"=>$va_user,
											"HEADQUARTERS_CODE"=>$headquarters_code,
											"DEPT_CODE"=>$dept_code,
											"VA_STATE"=>$va_state,
											"VA_STATE_POS"=>$va_state_pos,
											"EX_IMG"=>$ex_img,
											"EX_IP"=>$_SERVER['REMOTE_ADDR'],
											"DEL_TF"=>"N",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$new_ex_no = insertExpense($conn, $arr_data);
		
		$list_count = count($exd_date);

		for($i=0; $i < $list_count; $i++){

			$arr_data_list = array("EX_NO"=>$new_ex_no,
														"EXD_DATE"=>$exd_date[$i],
														"EXD_TYPE"=>$exd_type[$i],
														"EXD_CONTENT"=>$exd_content[$i],
														"EXD_PROJECT"=>$exd_project[$i],
														"EXD_PRICE"=>$exd_price[$i],
														);

			$result = insertExpenseDate($conn, $arr_data_list);

		}

		// Count total files
		$file_cnt = count($_FILES['ex_files']['name']);

		for($i=0; $i <= $file_cnt; $i++) {
			
			//if ($_POST["file_flag"][$i] == "insert") {

				$file_nm					= multiupload_1($_FILES["ex_files"], $i, $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));

				$file_rnm					= $_FILES["ex_files"]["name"][$i];
				$file_size					= $_FILES["ex_files"]["size"][$i];
				$file_ext					= explode('.', $_FILES["ex_files"]["name"][$i]);
				$file_ext					= end($file_ext);
				
				$use_tf = "Y";

				if ($file_nm <> "") {
					$result_file = insertExpenseFile($conn, $new_ex_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $_SESSION['s_adm_no']);
				}
			//}
		}

	}

	if ($mode == "S") { 
		
		$arr_rs = selectExpense($conn, $ex_no);

		if (sizeof($arr_rs) > 0) {
			$rs_ex_no						= trim($arr_rs[0]["EX_NO"]);
			$rs_ex_type						= trim($arr_rs[0]["EX_TYPE"]);
			$rs_ex_title					= trim($arr_rs[0]["EX_TITLE"]);
			$rs_ex_date						= trim($arr_rs[0]["EX_DATE"]);
			$rs_ex_total_price				= trim($arr_rs[0]["EX_TOTAL_PRICE"]);
			$rs_va_user						= trim($arr_rs[0]["VA_USER"]);
			$rs_headquarters_code			= trim($arr_rs[0]["HEADQUARTERS_CODE"]);
			$rs_dept_code					= trim($arr_rs[0]["DEPT_CODE"]);
			$rs_va_state					= trim($arr_rs[0]["VA_STATE"]);
			$rs_va_state_pos				= trim($arr_rs[0]["VA_STATE_POS"]);
			//$rs_va_flag					= trim($arr_rs[0]["VA_FLAG"]);
			//$rs_va_img					= trim($arr_rs[0]["VA_IMG"]);

			$arr_rs_name					= selectAdmin2022($conn, $rs_va_user);
			$rs_va_user_name				= trim($arr_rs_name[0]["ADM_NAME"]);
		}
	}

	if ($mode == "U") {

		$arr_data = array("EX_TITLE"=>$ex_title,
											"EX_MEMO"=>$ex_memo,
											"EX_TOTAL_PRICE"=>$ex_total_price,
											"VA_USER"=>$va_user,
											"HEADQUARTERS_CODE"=>$headquarters_code,
											"DEPT_CODE"=>$dept_code,
											"VA_STATE"=>$va_state,
											"VA_STATE_POS"=>$va_state_pos,
											"EX_IMG"=>$ex_img,
											"EX_IP"=>$_SERVER['REMOTE_ADDR'],
											"DEL_TF"=>"N",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$new_ex_no = updateExpense($conn, $arr_data, $ex_no);
		
		$list_count = count($exd_date);

		if ($list_count > 0) { 
			$result = deleteExpenseDate($conn, $ex_no); //기존 상세내역 지운후 다시 insertExpenseDate
		}

		for($i=0; $i < $list_count; $i++){

			$arr_data_list = array("EX_NO"=>$ex_no,
														 "EXD_DATE"=>$exd_date[$i],
														 "EXD_TYPE"=>$exd_type[$i],
														 "EXD_CONTENT"=>$exd_content[$i],
														 "EXD_PROJECT"=>$exd_project[$i],
														 "EXD_PRICE"=>$exd_price[$i],
														);
			$result = insertExpenseDate($conn, $arr_data_list);

		}

		// Count total files
		$file_cnt = count($_FILES['ex_files']['name']);

		for($i=0; $i <= $file_cnt; $i++) {
			
			//if ($_POST["file_flag"][$i] == "insert") {

				$file_nm					= multiupload_1($_FILES["ex_files"], $i, $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png'));
				
				$file_rnm					= $_FILES["ex_files"]["name"][$i];
				$file_size					= $_FILES["ex_files"]["size"][$i];
				$file_ext					= explode('.', $_FILES["ex_files"]["name"][$i]);
				$file_ext					= end($file_ext);
				
				$use_tf = "Y";

				if ($file_nm <> "") {
					$result_file = insertExpenseFile($conn, $ex_no, $file_nm, $file_rnm, $file_path, $file_size, $file_ext, $_SESSION['s_adm_no']);
				}
			//}
		}

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "expense_list.php<?=$strParam?>";
</script>
<?
		mysql_close($conn);
		exit;
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
<script type="text/javascript" src="/js/jquery/jquery.js"></script>

<script type="text/javascript">

var sel_files =[]; //이미지 정보들을 담을 배열

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

//첨부파일
//	$("#input_imgs").on("change", handleImgFileSelect);  //change를 click으로 변경

});

var disabledDays = [<?=$unable_dates?>]; 

function datep(){
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
}

// 특정일 선택막기 
function disableAllTheseDays(date) { 
	var m = date.getMonth(), d = date.getDate(), y = date.getFullYear(); 
	for (i = 0; i < disabledDays.length; i++) { 
		if($.inArray(y + '-' +(m+1) + '-' + d,disabledDays) != -1) { 
			return [false]; 
		}
	} 
	
	<? if ($_SESSION['s_adm_group_no'] > 3) { ?>

	var now = new Date();
	//if (date < new Date(now.setMonth(now.getMonth() - 1))) {  //한달전까지 선택가능하게
	if (date < new Date(now.getFullYear(), now.getMonth() - 1, 1)) { //한달전 1일 까지 선택가능하게 2022-03-21
		return [false]; //이전날짜 막는 부분
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
	var ex_no = "<?=$ex_no?>";

	frm.va_user.value = frm.va_user.value.trim();
	frm.va_state.value = frm.va_state.value.trim();
	frm.va_state_pos.value = frm.va_state_pos.value.trim();

	//alert($('input[name="exd_date[]"]').length);
	if (ex_no == "") {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}

	<? if (($s_adm_no == "1") || ($s_adm_no== "178") || ($s_adm_no == "4")) {	?>
			if (frm.con_va_user_name.value == "") {
				alert('신청자를 선택해주세요.');
				frm.con_va_user_name.focus();
				return ;
			}
	<? } ?>

	if (isNull(frm.ex_title.value)) {
		alert('제목을 입력해주세요.');
		frm.ex_title.focus();
		return ;		
	}

	ex_date_len = $('input[name="exd_date[]"]').length;

	for (i=0;i<ex_date_len ;i++) {
		if ($('input[name="exd_date[]"]')[i].value == "") {
			alert((i+1)+'번째 날짜를 선택해주세요');
			$('input[name="exd_date[]"]')[i].focus();
			return ;
		}
	}

	ex_price_len = $('input[name="exd_price[]"]').length;

	for (i=0;i<ex_price_len ;i++) {
		if ($('input[name="exd_price[]"]')[i].value == "") {
			alert((i+1)+'번째 금액을 작성해주세요');
			$('input[name="exd_price[]"]')[i].focus();
			return ;
		}
	}
	

	if (frm.mode.value != "U") {  // id= file_1 이 없다면...
		if (document.getElementById("input_imgs").files.length <= 0) {
			alert('이미지를 첨부해 주세요.');
			return ;
		}
		frm.ex_files_cnt.value = document.getElementById("input_imgs").files.length;
	} else {

		if(document.querySelector('#imgs_wrap a') == null){
			alert('이미지를 첨부해 주세요.');
			return ;
		}
	}
	//alert($('input[name="exd_no[]"]')[0].value);

	//img 확장자 체크
	img_len = document.getElementById("input_imgs").files.length;

	for (i=0;i<img_len ;i++) {
		img_name = document.getElementById("input_imgs").files[i].name;
		img_name = img_name.slice(img_name.indexOf(".") + 1).toLowerCase();

		if(img_name != "jpg" && img_name != "png" &&  img_name != "gif" &&  img_name != "bmp" &&  img_name != "jpeg"){
			alert("등록할수 없는 파일 확장자입니다.\n이미지 파일은 (jpg, png, gif, bmp, jpeg) 형식만 등록 가능합니다.\n이미지를 눌러 다시 선택해주세요.");
			document.getElementById("input_imgs").files[i].focus();
			return;
		}

	}

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}


function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('작성된 내역을 삭제 하시겠습니까?');
	
	if (bDelOK==true) {

		var ex_no = "<?=$ex_no?>";
		frm.mode.value = "D";

		var request = $.ajax({
			url:"/manager/expense/expense_dml.php",
			type:"POST",
			data:{mode:frm.mode.value
					 ,ex_no:ex_no
					 },
			dataType:"html"
		});

		request.done(function(msg) {

			if (msg == "T") {
				alert('삭제 되었습니다.');
				document.location = "/manager/expense/expense_list.php";
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
	document.location = "expense_list.php";
}

function js_imgopen(t) {
	var img = new Image();
	img.src = t;
	imgopen = window.open('','imgwindow','width='+img.width+', height='+img.height+', toolbar=0, scrollbars=0, resizable=no');
	if (imgopen != null) {
		if (imgopen.opener == null) {
				imgopen.opener = self;
		}
		imgopen.location.href = "http://ucomp.co.kr"+t;
	}
}

function js_zip(n) {
	var frm = document.frm;
	frm.ex_no.value = n;
	frm.method = "post";
	frm.target = "";
	frm.action = "ex_img_zip.php";
	frm.submit();
}

//사용자 이름으로 검색 수정 2021.08.17
<? if ($sPageRight_I == "Y") { ?>
	function js_name(t){

		var frm = document.frm ;

		if (t != "") {
			var request = $.ajax({
				url:"/manager/admin/admin_namecheck_dml.php",
				type:"POST",
				data:$("form").serialize(),
				dataType:"json"
			});

			request.done(function(data) {
				if (data.result == 0) {
					alert("존재하지 않는 이름입니다!");
					frm.con_va_user_name.value = "";
					frm.va_user.value = "";
					frm.con_va_user_name.focus();
					return false;
				} else {
					frm.va_user.value = data.msg;
				}
			});
		} else {
			frm.va_user.value = "";
		}
	}
<? } ?>

function js_mail(adm) {

	var frm = document.frm;

	bDelOK = confirm(' 승인요청건이 있다는 메시지만 전송됩니다!\n 승인 요청 메일을 보내시겠습니까?');
	
	if (bDelOK==true) {

		frm.mode.value = "email";
		frm.adm_email.value = adm;

		var request = $.ajax({
			url:"/manager/expense/expense_mail.php",
			type:"POST",
			data:{mode:frm.mode.value
					 ,email:frm.adm_email.value
					 ,leader_no:frm.va_state_pos.options[frm.va_state_pos.selectedIndex].value
					 },
			success: function(data){
				alert("메일 발송 완료!");
			}
		});
	}
}
 
	$(function(){
			
		$("#chkAll").click(function(){			
			if($('#chkAll').is(':checked')){
				alert("2");	
				$("input[name='exd_chk[]']").each(function() {    
					$(this).attr('checked', 'checked');   
				})
			}else{
				alert("3");	
				$("input[name='exd_chk[]']").each(function() {
					$(this).attr('checked', false);
				})
			}
		
		});
		
	});


</script>

</head>
<body>
<div id="wrap1">
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

<form name="frm" method="post" enctype="multipart/form-data">
<input type="text" name="ex_no" value="<?=$rs_ex_no?>" />
<input type="text" name="mode" value="" />
<input type="text" name="ex_ip" value="<?=$_SERVER['REMOTE_ADDR']?>" />
<input type="text" name="ex_files_cnt" value="" />
<input type="text" name="adm_email" value="cadt@naver.com" />
<input type="text" name="leader_no" value="" />

<!--
<? if (($s_adm_no == "1") or ($s_adm_no == "178") or ($s_adm_no == "4")) { ?>
<input type="hidden" name="va_user" value="<?=$va_user?>">
<? } ?>
-->
				※ 승인 이후 수정이 불가하오니 내용 확인 후 등록 바랍니다<br/>
				<div class="boardwrite">
					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:25%" />
							<col style="width:10%" />
							<col style="width:25%" />
							<col style="width:10%" />
							<col style="width:20%" />
						</colgroup>
						<tbody>
							<tr>
								<th>신청자</th>
								<td>
								<? if (($s_adm_no <> "1") && ($s_adm_no <> "178") && ($s_adm_no <> "4")) { ?>
									<?=$s_adm_nm?>
									<input type="hidden" name="va_user" id="va_user" value="<?=$s_adm_no?>">
								<? } else { ?>
									<!--
									<span class="optionbox">
										<?=makeEmpSelectBox($conn, "va_user" , "" , " 선택 ", "", $rs_va_user)?>
									</span>
									-->
									<span class="inpbox" style="width:100px;">
										<input type="text" value="<?=$rs_va_user_name?>" name="con_va_user_name" class="txt" onBlur="js_name(this.value)" />
										<input type="hidden" name="va_user" id="va_user" value="<?=$rs_va_user?>">
									</span>
								<? } ?>
								</td>
								<th>본부</th>
								<td>
									<?
										$arr_rs = selectAdmin2022($conn,$s_adm_no);
										$LEADER_YN				= $arr_rs[0]["LEADER_YN"];
										$HEADQUARTERS_CODE		= $arr_rs[0]["HEADQUARTERS_CODE"];
										$OCCUPATION_CODE		= $arr_rs[0]["OCCUPATION_CODE"];
										$DEPT_CODE				= $arr_rs[0]["DEPT_CODE"];
										$DEPT_UNIT_NAME			= $arr_rs[0]["DEPT_UNIT_NAME"];
										$ADM_EMAIL				= $arr_rs[0]["ADM_EMAIL"];
										$LEVEL					= $arr_rs[0]["LEVEL"];
										$LEADER_TITLE			= $arr_rs[0]["LEADER_TITLE"];
										$my_dept_code			= $DEPT_CODE;
										$my_headquarters_code	= $HEADQUARTERS_CODE;
										$my_position_code		= $POSITION_CODE;
										$my_level				= $LEVEL;

										if (($s_adm_no <> "1") && ($s_adm_no <> "178") && ($s_adm_no <> "4") && ($s_adm_no <> "43") && ($s_adm_no <> "44")) { ?>
										<?=$HEADQUARTERS_CODE?>
										<input type="hidden" name="headquarters_code" id="headquarters_code" value="<?=$HEADQUARTERS_CODE?>">
									<? } else { ?>
										<span class="optionbox"><?= makeSelectBox($conn,"HEADQUARTERS_2022","headquarters_code","125px","선택","",$rs_headquarters_code)?></span>
									<? } ?>
								</td>
								<th>부서</th>
								<td>
									<? if (($s_adm_no <> "1") && ($s_adm_no <> "178") && ($s_adm_no <> "4") && ($s_adm_no <> "43") && ($s_adm_no <> "44")) { ?>
										<?=$DEPT_CODE?>
										<input type="hidden" name="dept_code" id="dept_code" value="<?=$DEPT_CODE?>">
									<? } else { ?>
										<span class="optionbox"><?= makeSelectBox($conn,"DEPT_2022","dept_code","125px","선택","",$rs_dept_code)?></span>
									<? } ?>
								</td>
							</tr>
							<tr>
								<th>제목</th>
								<td>
									<span class="inpbox">
										<?
											if ($ex_no == "")	{
												$str_title	= date("m", time())."월 지출결의_".$s_adm_nm;
												$str_date	= date("Y-m-d", time());
											} else {
												$str_title	= $rs_ex_title;
												$str_date	= $rs_ex_date;
											}
										?>
										<input type="text" name="ex_title" class="txt" value="<?=$str_title?>" />
										<input type="hidden" name="ex_date" class="txt" value="<?=$str_date?>" />
									</span>
								</td>
								<th>승인위치</th>
								<td>

									<?	
										
										 		$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
												
												if (sizeof($arr_leader) <= 0) {
													$LEVEL = $LEVEL - 1; //leader가 존재하지 않을 때 한단계 위!
													$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
													
												}
												
												$leader_no								= $arr_leader[0]["ADM_NO"];
												$leader_headquarters_code				= $arr_leader[0]["HEADQUARTERS_CODE"];
												$leader_name							= $arr_leader[0]["ADM_NAME"];
												$leader_email							= $arr_leader[0]["ADM_EMAIL"];
												$leader_dept_code						= $arr_leader[0]["DEPT_CODE"];
												$leader_position_code					= $arr_leader[0]["POSITION_CODE"];
												
										?>
								<!--
										<? 
												if (($s_adm_no == "1") || ($s_adm_no == "178") || ($s_adm_no == "4") || ($s_adm_no == "43") || ($s_adm_no == "44")) { //관리자 또는 경영관리자 라면 옵션으로 선택하도록
										?>
													<span class="optionbox" style="width:300px">
													<? $arr_leader_all = selectAdminLeaderAll($conn, $year); ?> 
														<select name="va_state_pos">
															<? for ($i = 0 ; $i < sizeof($arr_leader_all) ; $i++) { ?>
																<option value="<?=$arr_leader_all[$i]["ADM_NO"]?>" <?if ($rs_va_state_pos == $arr_leader_all[$i]["ADM_NO"]){?>selected<?}?>>
																<?=$arr_leader_all[$i]["ADM_NAME"]?>[<?=$arr_leader_all[$i]["POSITION_CODE"]?>]
																<? if ($arr_leader_all[$i]["DEPT_CODE"] <> "") {?>
																	 _<?=$arr_leader_all[$i]["DEPT_CODE"]?>
																<? } ?>
																</option>
															<? } ?>
														</select>
													</span>

										<? } else { ?> 
													<input type="hidden" name="va_state_pos" id="va_state_pos" value="<?=$leader_no?>">
													<? if ($leader_dept_code<> "") { ?> 
																[<?=$leader_dept_code?>] <?=$leader_name?> <?=$leader_position_code?>
													<? } else {?>
																<? if ($leader_headquarters_code <> "") { ?>
																		[<?=$leader_headquarters_code?>] 
																<? }?>
																<?=$leader_name?> <?=$leader_position_code?>
													<? } ?>
										<? } ?>
								-->
									<span class="optionbox" style="width:300px">
									<? $arr_leader_all = selectAdminLeaderAll($conn, $year); ?> 
										<select name="va_state_pos">
											<? for ($i = 0 ; $i < sizeof($arr_leader_all) ; $i++) { 
													 if (($my_level < $arr_leader_all[$i]["LEVEL"]) and ($my_level <> "")) {  //리더 아래LEVEL은 나오지 않도록 2022-03-02
															continue;
													 } else {

														if ($mode <> "S"){
											?>
															<option value="<?=$arr_leader_all[$i]["ADM_NO"]?>" <?if ($leader_no == $arr_leader_all[$i]["ADM_NO"]){?>selected<?}?>>
															<?=$arr_leader_all[$i]["ADM_NAME"]?>[<?=$arr_leader_all[$i]["POSITION_CODE"]?>]
															<? if ($arr_leader_all[$i]["DEPT_CODE"] <> "") {?>
																 _<?=$arr_leader_all[$i]["DEPT_CODE"]?>
															<? } ?>
															</option>
											<?
														} else {
											?>
															<option value="<?=$arr_leader_all[$i]["ADM_NO"]?>" <?if ($rs_va_state_pos == $arr_leader_all[$i]["ADM_NO"]){?>selected<?}?>>
															<?=$arr_leader_all[$i]["ADM_NAME"]?>[<?=$arr_leader_all[$i]["POSITION_CODE"]?>]
															<? if ($arr_leader_all[$i]["DEPT_CODE"] <> "") {?>
																 _<?=$arr_leader_all[$i]["DEPT_CODE"]?>
															<? } ?>
															</option>
											<?

														}
													}
												} 
											?>
										</select>
									</span>
								</td>
								<th>상태</th>
								<td>
							<? if ($sPageRight_I <> "Y") { ?>
								<input type="hidden" name="va_state" id="va_state" value="0">신청
							<? } else { ?>
									<span class="optionbox" style="width:125px">
									<? if (($s_adm_no == 1) || ($s_adm_no == 178)|| ($s_adm_no == 4)){ ?>
		  								<?= makeSelectBox($conn,"VA_STATE","va_state",""," 선택 ","",$rs_va_state)?>
									<? } else { ?>
												<select name="va_state">
									<?		switch ($rs_va_state) {
													case "1" : echo "<option value='1' selected disabled>승인";
													break;
													case "2" : echo "<option value='2' selected disabled>보류";
													break;
													case "3" : echo "<option value='3' selected disabled>반려";
													break;
													case "4" : echo "<option value='4' selected disabled>진행중";
													break;
													case "0" : echo "<option value='0' selected>신청";
													break;
													default : echo "<option value='0' selected>신청";
												}
									?>
												</select>
									<? } ?>
									</span>
							<? } ?>
								</td>
							</tr>
						</tbody>
					</table>
					<br />
					※ 지출 내역 (석식대는 당일 8천원 초과불가)
					<div style="float:right"><a onClick="js_inputAdd()" style="color:red">목록추가 +</a></div>
					<br/>
					<table id="item_add">
						<colgroup>
							<col style="width:15%" />
							<col style="width:15%" />
							<col style="width:30%" />
							<col style="width:20%" />
							<col style="width:15%" />
							<col style="width:5%" />
						</colgroup>
						<tbody id="tbody">
							<tr>
								<th><input type="checkbox" onclick="js_selectall();" id="chkAll" ></th><th>기간</th><th>지출타입</th><th>상세내역</th><th>프로젝트</th><th>금액</th><th>삭제</th>
							</tr>

							<?
							//expense_date 파일 select!
							$arr_rs_date = selectExpenseDate($conn, $ex_no);
							$arr_rs_file = selectExpenseFile($conn, $ex_no);
							$i = 0;
							if (sizeof($arr_rs_date) > 0) {

								for($i = 0 ; $i < sizeof($arr_rs_date) ; $i++){
									$rs_exd_no					= trim($arr_rs_date[$i]["EXD_NO"]);
									$rs_exd_date				= trim($arr_rs_date[$i]["EXD_DATE"]);
									$rs_exd_type				= trim($arr_rs_date[$i]["EXD_TYPE"]);
									$rs_exd_content				= trim($arr_rs_date[$i]["EXD_CONTENT"]);
									$rs_exd_project				= trim($arr_rs_date[$i]["EXD_PROJECT"]);
									$rs_exd_price				= trim($arr_rs_date[$i]["EXD_PRICE"]);
									$rs_exd_memo				= trim($arr_rs_date[$i]["EXD_MEMO"]);
							?>
							<tr>
								<input type="hidden" name="exd_no[]" value="<?=$rs_exd_no?>">
								<td><input type="checkbox"  name="exd_chk[]" value=""  /></td>
								<td><span class="inpbox"><input type="text" class="date" name="exd_date[]" value="<?=$rs_exd_date?>" autocomplete="off" readonly /></span></td>
								<td><span class="optionbox"><?= makeSelectBox($conn,"EXPENSE_TYPE","exd_type[]","50px","선택","",$rs_exd_type)?></span></td>
								<td><span class="inpbox"><input type="text" value="<?=$rs_exd_content?>" name="exd_content[]" class="txt" /></span></td>
								<td><span class="inpbox"><input type="text" value="<?=$rs_exd_project?>" name="exd_project[]" class="txt" /></span></td>
								<td><span class="inpbox"><input type="text" value="<?=$rs_exd_price?>" name="exd_price[]" class="txt" style="text-align:right" onBlur="js_sum()" /></span></td>
								<td>
									<? if ($i == 0) {?>
										<img src="../images/del.png" width="30" style="opacity:20%">
									<? } else {?>
										<img id="img_0" src="../images/del.png" width="30" onClick="js_image(this)" style="cursor:pointer">
									<? } ?>
								</td>
							</tr>
							<?
								}
							} else {
							?>
							<tr>
								<td><input type="checkbox"  name="exd_chk[]" value="" /></td>
								<td><span class="inpbox"><input type="text" class="date" name="exd_date[]" autocomplete="off" readonly /></span></td>
								<td><span class="optionbox"><?= makeSelectBox($conn,"EXPENSE_TYPE","exd_type[]","50px","선택","",$rs_exd_type)?></span></td>
								<td><span class="inpbox"><input type="text" name="exd_content[]" class="txt" /></span></td>
								<td><span class="inpbox"><input type="text" name="exd_project[]" class="txt" /></span></td>
								<td><span class="inpbox"><input type="text" name="exd_price[]" class="txt" style="text-align:right" onBlur="js_sum()" maxlength="15" /></span></td>
								<td> <img src="../images/del.png" width="30" style="opacity:20%">
										 <!--<img id="img_0" src="../images/plus.png" width="30" onClick="js_image(this)" style="cursor:pointer">-->
								</td>
							</tr>
							<?
							}
							?>

						</tbody>
					</table>
					<table style="border-top:0;border-bottom:0">
						<colgroup>
							<col style="width:20%" />
							<col style="width:35%" />
							<col style="width:20%" />
							<col style="width:20%" />
							<col style="width:5%" />
						</colgroup>
						<tbody>
							<tr>
								<td colspan="3" style="text-align:right;border-bottom:0px">총 <input type="text" name="total_count" value="<?=$i?>" style="border:0;text-align:right;width:20px;"> 건</td>
								<td style="text-align:right;border-bottom:0px">총 금액<input type="text" name="ex_total_price" value="<?=$rs_ex_total_price?>" style="border:0;text-align:right;width:140px;"> 원</td>
								<td style="border-bottom:0px">&nbsp;</td>
							</tr>
						</tbody>
					</table>
					※ 영수증 사진 첨부 (Ctrl키를 누르고 여러개 영수증 선택 가능. 단, 같은 파일을 지우고 다시 선택금지. <span style="color:red">오류시에는 새로고침 후 파일 선택</span>을 해 주시기 바랍니다!)
					<table>
						<tbody>
							<tr>
								<td>
									<div id="file_btn" style="display:block">
										<input type="file" class="txt" name="ex_files[]" id="input_imgs" value="<?=$rs_va_img?>" accept="image/*" multiple onChange="js_images_name(this.files.length)" />
									</div>
									<!--<label for="input_imgs" class="btn-gray"> &nbsp;&nbsp;파일 선택&nbsp;&nbsp; </label>-->
									<div id="imgs_wrap" style="margin-top:5px;">

									<? if (sizeof($arr_rs_file) > 0) { 
												for ($i=0 ; $i < sizeof($arr_rs_file); $i++){
													$rs_file_nm			= trim($arr_rs_file[$i]["FILE_NM"]);
									?>
													<a href="javascript:js_imgopen('/upload_data/expense/<?=$rs_file_nm?>')"><img src="/upload_data/expense/<?=$rs_file_nm?>" width="100px" height="150px" style="vertical-align:top;" id="file_<?=$i?>"></a>
													<? if (($va_user <> "1") && ($va_user <> "178") && ($va_user <> "43") && ($va_user <> "44")) {
																if (($sPageRight_I <> "Y") || ($rs_va_state == "") || ($rs_va_state == "0") || ($va_user == "4")) { ?>
																	<a href="javascript:void(0)" onClick="js_img_delete('<?=$ex_no?>','<?=$rs_file_nm?>')">X</a> &nbsp;
													<?		}
														 }
												} 
										 } 
									?>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="btnright">

					<button type="button" class="btn-gray" onClick="js_mail('<?=$ADM_EMAIL?>');" style="width:150px;height:45px">승인요청 메일발송</button>
					<? if ($mode <> "") {?>
						<button type="button" class="btn-gray" onclick="js_zip(<?=$ex_no?>)" style="width:160px;height:45px"> 영수증 압축 다운로드</button>
					<? } ?>
					<? if (($rs_va_state <> "1") && ($rs_va_state <> "2") && ($rs_va_state <> "3") && ($rs_va_state <> "4")) { 
								if (($va_user <> "1") && ($va_user <> "178") && ($va_user <> "43") && ($va_user <> "44")) { //경영관리자외의 관리자는 수정 불가 
									if ($ex_no <> "") {
					?>
										<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">수정</button>
										<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					<?				} else {  ?>
										<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<?				} 
								}
						 } else {  //어떤상태이던지 경영팀에서는 수정 가능토록
							 if ($va_user == "4"){ ?>
									<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">수정</button>
									<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
						<? }
						 }
					?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>
			</div>
			<!-- // E: mwidthwrap -->

			</div>
		</div>
	</div>
<iframe id="ifr_hidden" src="" frameborder="No" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
	<!-- //S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>

<script>
/* 버튼 추가용 스크립트 */
	function js_image(th){

		var detail_cnt =$("#item_add").find("tr").length;

		if (th.src == "http://ucomp.co.kr/manager/images/plus.png"){
			th.src = "http://ucomp.co.kr/manager/images/del.png";
			js_inputAdd();
		} else {
			js_inputRemove(th);
			if($(th).closest('tr').is(':last-child')){
				th.src = "http://ucomp.co.kr/manager/images/plus.png";
			} else {
				th.src = "http://ucomp.co.kr/manager/images/del.png";
			}
			if (th.id == "img_0"){
				if (detail_cnt <= 3){
					th.src = "http://ucomp.co.kr/manager/images/plus.png";
				} 
			}
		}
	}

	function js_inputAdd(){

		var detail_cnt =$("#item_add").find("tr").length;

		var t = "<tr>";
				t +="<td><span><input type='checkbox' name='exd_chk[]'/></span></td>";
				t +="<td><span class='inpbox'><input type='text' class='date' name='exd_date[]' autocomplete='off' onClick='datep()' readonly /></span></td>";
				t +="<td><span class='optionbox'><?= makeSelectBox($conn,'EXPENSE_TYPE','exd_type[]','50px','선택','','')?></span></td>";
				t +="<td><span class='inpbox'><input type='text' name='exd_content[]' class='txt' /></span></td>";
				t +="<td><span class='inpbox'><input type='text' name='exd_project[]' class='txt' /></span></td>";
				t +="<td><span class='inpbox'><input type='text' name='exd_price[]' class='txt' style='text-align:right' onBlur='js_sum()' onkeyup='js_numcheck(this, event.keyCode)' maxlength='15' /></span></td>";
				t +="<td><img src='../images/del.png' width='30' onClick='js_image(this)' style='cursor:pointer'></td>";
				t +="</tr>";

		$("#item_add").append(t);
		datep();  //append후 date 박스 클릭 안되는 오류를 막기위해
	}

	function js_inputRemove(t){
		//$(t).parent().parent().next().remove();
		$(t).parent().parent().remove();
		js_sum();
	}

	function js_sum(){
		var detail_cnt =$("#item_add").find("tr").length;
		tot = 0;
		for (i = 0 ; i < detail_cnt-1 ; i++){
			tot_str = $('input[name="exd_price[]"]')[i].value;
			if (tot_str == ''){
				tot_str = 0;
			} else {
				tot_str = parseInt(tot_str.replace(/,/g , '')); //숫자로 변환
			}
			tot += tot_str;
		}
		document.frm.total_count.value = i;
		document.frm.ex_total_price.value = tot.toLocaleString( "en-US" );
	}


$('input[name="exd_price[]"]').on("keyup", function(event) {
    // 1.
    var selection = window.getSelection().toString();
    if ( selection !== '' ) {
        return;
    }
 
    // 2.
    if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
        return;
    }
  
    // 3
    var $this = $( this );
    var input = $this.val();
 
    // 4
    var input = input.replace(/[\D\s\._\-]+/g, "");
 
    // 5
    input = input ? parseInt( input, 10 ) : 0;
 
    // 6
    $this.val( function() {
        return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );  //,찍기
    });
});

	//위쪽 keyup 이벤트 함수로 축약!!!
	function js_numcheck(t, e){
		var tt = t.value;
		tt = tt.replace(/[\D\s\._\-]+/g, "");
		tt = tt ? parseInt( tt, 10 ) : 0;

		t.value = tt.toLocaleString( "en-US" );
	}


 //영수증 첨부파일
	var str_image = "";
	var str_image_name = "";
	var input = document.querySelector('#input_imgs');
	const dt = new DataTransfer()
	dt.items.add(new File([], 'image.jpg'))

	function js_images_name(l){

		for(i=0;i<dt.files.length;i++){
			dt.items.remove(i);
		}

		console.log("length : "+input.files.length + "////dt : "+dt.files.length);
		$("#imgs_wrap *").remove();
		dt.files = input.files;

		for (let file of input.files) {
			//if (file !== input.files[0]) {
				dt.items.add(file);
			//}
		}

		var index = 0;
		for (i=0; i<l ;i++)	{

			var fReader = new FileReader();

			fReader.readAsDataURL(dt.files[i]);
			fReader.fileName = dt.files[i].name;

			str_image_name += fReader.fileName+"/";
			fReader.onloadend = function(event){
				str_image = "<a href=\"javascript:void(0);\" onClick=\"deleteImageAction("+index+")\" id=\"img_id_"+index+"\"><img src='"+event.target.result+"' width='100px' height='150px' style='vertical-align:bottom;'>"+event.target.fileName+"</a> ";
				$("#imgs_wrap").append(str_image); //event가 for문 밖으로 나가면 사라짐 ..그래서 += 이용하지 않음
				index++;
			}
		}
			
		document.frm.ex_img.value = str_image_name;
	}

	function deleteImageAction(index){
		//sel_files.splice(index, 1);
		var file_reselect = 0;

		if(input.files.length == 1){  //파일을 다 제거하는 순간
			file_reselect = 1;
		}

		var img_id = "#img_id_"+index;
		$(img_id).remove();

		dt.items.remove(index);
		input.files = dt.files;

		if (file_reselect == 1) {
			alert("파일선택을 다시 하셔야 합니다!");
			$("#input_imgs").click();
		}

		$("#input_imgs").change(function(){
			$(":input[name='ex_files']").val("");
			//readURL(this);
			//alert("change");
		});

		//console.log(sel_files);
	}

	function readURL(input) {
			if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
							//$('#blah').attr('src', e.target.result);
					}

				reader.readAsDataURL(input.files[0]);
			}
	}

	function js_img_delete(ex_no, img_file_nm){
		if(confirm("이미지를 삭제하시겠습니까?")){
			document.getElementById("ifr_hidden").src = "expense_dml.php?mode=img_del&ex_no="+ex_no+"&file_nm="+img_file_nm;
		} else {
			return false;
		}
	}
</script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>