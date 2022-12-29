<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
#====================================================================
# File Name    : vacation_write.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-04
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
	include "../../_common/common_header_mobile.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/vacation/vacation.php";
	require "../../_classes/biz/admin/admin.php";

	$mode			= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$seq_no			= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];

	$year			= "202206";  //2022-04-08 승인용 추가

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

/* //dml파일로 처리됨!
	if ($mode == "I") {

		$arr_data = array("VA_TYPE"=>$va_type,
											"VA_MEMO"=>$va_memo,
											"VA_USER"=>$va_user,
											"VA_STATE"=>$va_state,
											"VA_STATE_POS"=>$va_state_pos,
											"DEL_TF"=>"N",
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"VA_PERIOD"=>"1",
											"VA_MDATE"=>$va_mdate,
											"VA_WAY"=>$va_way,
											"VA_HPHONE"=>$va_hphone,
											);

		$new_seq_no = insertVacation($conn, $arr_data);
		
		if (($va_type == 1) || ($va_type == 7)) {
			$va_cnt = 0.5;
		} else if (($va_type == 2) || ($va_type == 3) || ($va_type == 4)) {
			$va_cnt = 1;
		} else {
			$va_cnt = 0;
		}

		//기간이 아닌 날짜를 선택만 추가
		$va_mdate_date = explode("/", $va_mdate);

		for ($i=0; $i<=$va_mdate_date; $i++) {

			$arr_data = array("SEQ_NO"=>$new_seq_no,
												"VA_DATE"=>$va_mdate_date[$i],
												"VA_USER"=>$va_user,
												"VA_CNT"=>$va_cnt
												);

			$result = insertVacationDate($conn, $arr_data);
		}

	}

	if ($mode == "S") {
		
		$arr_rs = selectVacation($conn, $seq_no);
		
		if (sizeof($arr_rs) > 0) {
			$rs_seq_no				= trim($arr_rs[0]["SEQ_NO"]);
			$rs_va_type				= trim($arr_rs[0]["VA_TYPE"]);
			$rs_va_sdate			= trim($arr_rs[0]["VA_SDATE"]);
			$rs_va_edate			= trim($arr_rs[0]["VA_EDATE"]);
			$rs_va_memo				= trim($arr_rs[0]["VA_MEMO"]);
			$rs_va_user				= trim($arr_rs[0]["VA_USER"]);
			$rs_va_state			= trim($arr_rs[0]["VA_STATE"]);
			$rs_va_state_pos		= trim($arr_rs[0]["VA_STATE_POS"]);
			$rs_va_flag				= trim($arr_rs[0]["VA_FLAG"]);
			$rs_va_img				= trim($arr_rs[0]["VA_IMG"]);

			$rs_va_period			= trim($arr_rs[0]["VA_PERIOD"]);
			$rs_va_mdate			= trim($arr_rs[0]["VA_MDATE"]);
			$rs_va_way				= trim($arr_rs[0]["VA_WAY"]);
			$rs_va_hphone			= trim($arr_rs[0]["VA_HPHONE"]);
			$rs_memo				= trim($arr_rs[0]["MEMO"]);

			$rs_va_user_name		= selectAdminName($conn, $rs_va_user) ; //이름

			//$rs_va_mdate_tmp = rtrim($rs_va_mdate, ",");

		}

	}
*/

?>

<!DOCTYPE html>
<html lang="ko">
<head>
	<title><?=$g_title_name?></title>
	<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/m_common_script.php";
?>
	<link rel="stylesheet" type="text/css" href="/m/assets/css/layout.front.css">
<style>

/*.air-datepicker-body--cells .air-datepicker-cell.-emoji-cell- {pointer-events:none}*/

</style>
	<script type="text/javascript" src="/manager/js/httpRequest.js"></script> <!-- Ajax js -->

	<script type="text/javascript">
	function js_save() {

		var frm = document.frm;
		var seq_no = "<?=$seq_no?>";

		//선택한 날짜 정렬해서 DB에 저장
		va_mdate_str = frm.va_mdate.value;
		va_mdate_array = va_mdate_str.split(",");
		va_mdate_str = va_mdate_array.sort();
		frm.va_mdate.value = va_mdate_str;

		frm.va_user.value = frm.va_user.value.trim();
		frm.va_type.value = frm.va_type.value.trim();
		//frm.va_sdate.value = frm.va_sdate.value.trim();
		//frm.va_edate.value = frm.va_edate.value.trim();
		frm.va_memo.value = frm.va_memo.value.trim();
		frm.va_state.value = frm.va_state.value.trim();
		frm.va_state_pos.value = frm.va_state_pos.value.trim();

		if (seq_no == "") {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}

		<? if ($sPageRight_I == "Y") {	?>
			
			if (frm.va_user.value == "") {
				alert('신청자를 선택해주세요.');
				frm.va_user.focus();
				return ;
			}

		<? } ?>

		if (frm.va_type.value == "") {
			alert('구분을 선택해주세요.');
			frm.va_type.focus();
			return ;
		}
		/*
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
		*/

		if (frm.va_mdate.value == "") {
			alert('날짜를 선택해주세요.');
			frm.va_mdate.focus();
			return ;		
		}

		if (frm.va_memo.value == "") {
			alert('사유를 입력해주세요.');
			frm.va_memo.focus();
			return ;		
		}

		if (frm.va_state.value == "") {
			alert('상태를 선택해주세요.');
			frm.va_state.focus();
			return ;
		}

		if (frm.va_state_pos.value == "") {
			alert('승인위치를 선택해주세요.');
			frm.va_state_pos.focus();
			return ;
		}

		/*
		// 기타 조건에 따른 제약조건
		if ((frm.va_type.value == "1") || frm.va_type.value == "13") {
			
			if (frm.va_sdate.value != frm.va_edate.value) {
				alert("반차 신청의 경우 시작일과 종료일이 동일해야 합니다.");
				return;
			}

		}

		if (frm.va_sdate.value > frm.va_edate.value) {
			alert("시작일이 종료일보다 작거나 같아야 합니다.");
			return;
		}
		*/

		chk = ""; 
		for (i=0;i<frm.va_way.length;i++ ){
			if(frm.va_way[i].checked){
				chk += frm.va_way[i].value;
			}
		}

		var request = $.ajax({
			url:"/m/vacation/vacation_dml.php",
			type:"POST",
			data:{mode:frm.mode.value
					 ,va_user:frm.va_user.value
					 ,va_type:frm.va_type.value
					 //,va_sdate:frm.va_sdate.value
					 //,va_edate:frm.va_edate.value
					 ,va_mdate:frm.va_mdate.value
					 ,va_memo:frm.va_memo.value
					 ,va_state:frm.va_state.value
					 ,va_state_pos:frm.va_state_pos.value
					 ,seq_no:seq_no
					 ,va_way:chk
					 ,va_hphone:frm.va_hphone.value
					 },
			dataType:"html"
		});

		request.done(function(msg) {

			if (msg == "T") {
				if (frm.va_state.value == "0") {
					alert("연차 사용 신청이 등록 되었습니다.\n결제 서류 제출 시 경영 지원팀에서 승인 처리 됩니다.\n승인 처리 되어야 연차 사용이 가능 합니다.\n승인 확인 후 연차 사용 바랍니다.");
				} else {
					alert('저장 되었습니다.');
				}
				document.location = "/m/vacation/vacation.php";
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
				url:"/m/vacation/vacation_mobile_dml.php",
				type:"POST",
				data:{mode:frm.mode.value
						 ,seq_no:seq_no
						 },
				dataType:"html"
			});

			request.done(function(msg) {

				if (msg == "T") {
					alert('삭제 되었습니다.');
					document.location = "/m/vacation/vacation.php";
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

	//사용자 이름으로 검색 수정 2021.08.17
	<? if ($sPageRight_I == "Y") { ?>
		function js_name(t){

			var frm = document.frm ;

			if (t != "") {
				var request = $.ajax({
					url:"/manager/admin/admin_namecheck_vacation_dml.php",
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
						frm.va_hphone.value = data.hphone;
					}
				});
			} else {
				frm.va_user.value = "";
			}
		}
	<? } ?>

	function search_opt_search(val){

		var frm = document.frm;

		if (val == 5) {
			$("#form-item_contact").css("display", "block");
			$("#form-item_phoneNumber").css("display", "block");
			$("#va_memo").text("업무내역");
			for (i=0;i<frm.va_way.length;i++) {
				frm.va_way[i].checked = true;
			}
		} else {
			$("#form-item_contact").css("display", "none");
			$("#form-item_phoneNumber").css("display", "none");
			$("#va_memo").text("사유");
			for(var i=0 ; i<frm.va_way.length;i++){
				frm.va_way[i].checked = false;
			}
		}
	}

	function tt(f){
		//alert("인트라넷 PC와의 통일화로 수정 준비중입니다.\n연차는 현재 사용중인 메뉴이므로 \n지출결재로만 테스트 진행될 수 있습니다.");
		//return false;
		//f.submit();
		return false;

	}

function js_open() {
	//window.open("vacation_write_img.php?seq_no=<?=$seq_no?>", "pop01", "top=400, left=800, width=450, height=200, status=no, menubar=no, toolbar=no, resizable=no,location=no");
	alert("not yet for Mobile");
}

	</script>
</head>
<body>
<div id="wrap">
	<!-- page -->
	<div id="page">
		<!-- page-body -->
		<div class="page-body page-default page-holiday">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-title" id="localTitle"><span class="local-name">근무현황</span></h2>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-head -->
						<div class="content-head">
							<h3 class="content-title"><span class="content-name">연차 등록</span></h3>
							<div class="content-navi">
								<a class="btn" href="javascript:history.back()"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>뒤로</title><path d="M12.7,5.7a1,1,0,0,0-1.4-1.4l-7,7a1,1,0,0,0,0,1.4l7,7a1,1,0,0,0,1.4-1.4L7.4,13H19a1,1,0,0,0,0-2H7.4Z"></path></svg></a>
							</div>
						</div>
						<!-- //content-head -->
						<!-- content-body -->
						<div class="content-body">
							<!-- datepicker -->
							<div class="datepicker-inline style-a" id="datepickerInline"></div>
							<!-- //datepicker -->
							<!-- section -->
							<div class="section module-a style-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name">등록 정보</span></h4></div>
								<div class="section-body">
									<!-- submit-form -->
<form name="frm" method="get">
<input type="hidden" name="seq_no" value="" />
<input type="hidden" name="mode" value="" />
									<fieldset class="submit-form module-a large">
										<legend>연차 유형 선택 서식</legend>
										<div class="form-list">
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="">유형</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form select module-c style-b type-line normal-01 large flex">
																<?= makeSelectBoxMobile($conn,"VA_TYPE","va_type",""," 선택 ","",$rs_va_type)?>
															</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</fieldset>
									<!-- SmartDay & Etc Input Form -->
									<fieldset class="submit-form module-a type-a medium">
										<legend>연차 등록 입력 서식</legend>
										<div class="form-list">
										<? if ($sPageRight_I <> "Y") { ?>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="va_user">신청자</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="text" value="<?=$s_adm_nm?>" class="form-elem" readonly />
																<input type="hidden" name="va_user" id="va_user" value="<?=$s_adm_no?>">
															</span>
														</div>
													</div>
												</div>
											</div>
										<? } else { ?>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="va_user">신청자</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="text" value="<?=$rs_va_user_name?>" name="con_va_user_name" class="form-elem" placeholder="이름을 입력해주세요." onBlur="js_name(this.value)" />
																<input type="hidden" name="va_user" id="va_user" value="<?=$rs_va_user?>">
															</span>
														</div>
													</div>
												</div>
											</div>
										<? } ?>
											<div class="form-item" id="form-item_periodLeave">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="periodLeave">일시</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<!--<input type="text" name="va_sdate" class="form-elem" id="periodLeave" placeholder="일시를 입력해주세요." readonly="readonly" />-->
																<input type="text" name="va_mdate" class="form-elem" id="periodLeave" placeholder="일시를 입력해주세요." autocomplete="off" readonly />
																<!--<input type="hidden" value="<?=$rs_va_mdate?>" name="va_mdate" id="multidate" />-->
															</span>
														</div>
													</div>
												</div>
											</div>

											<div class="form-item" id="form-item_contact" style="display:none">
												<div class="form-wrap">
													<div class="form-head"><span class="form-name">업무연락</span></div>
													<div class="form-body">
														<div class="form-area">
															<label class="form checkbox module-b style-b type-line normal-04 medium flex"><input type="checkbox" name="va_way" value="phone" class="form-elem" /> <span class="form-text">전화</span></label>
															<label class="form checkbox module-b style-b type-line normal-04 medium flex"><input type="checkbox" name="va_way" value="teams" class="form-elem" /> <span class="form-text">팀즈</span></label>
															<label class="form checkbox module-b style-b type-line normal-04 medium flex"><input type="checkbox" name="va_way" value="email" class="form-elem" /> <span class="form-text">이메일</span></label>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item" id="form-item_phoneNumber" style="display:none">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="phoneNumber">연락처</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
															<?
															 $va_hphone = selectAdminHPhone($conn, $s_adm_no);
															 if ($rs_va_hphone <> ""){
																	$va_hphone = $rs_va_hphone;
															 }
															?>
																<input type="tel" name="va_hphone" value="<?=$va_hphone?>" class="form-elem" id="phoneNumber" placeholder="연락처를 입력해주세요." value="" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
															</span>
														</div>
													</div>
												</div>
											</div>
											<!--
											<div class="form-item" id="form-item_todayTask" style="display:none">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="todayTask">업무내역</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textarea module-b style-b type-line normal-04 medium flex">
																<textarea name="memo" class="form-elem" id="todayTask" rows="4" placeholder="업무 내역을 입력해 주세요."></textarea>
															</span>
														</div>
													</div>
												</div>
											</div>
											-->
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="requestReason" id="va_memo">사유</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textarea module-b style-b type-line normal-04 medium flex">
																<textarea name="va_memo" class="form-elem" id="requestReason" rows="3" placeholder="내용을 입력해 주세요"><?=$rs_va_memo?></textarea>
															</span>
														</div>
													</div>
												</div>
											</div>

											<?//이미지추가용 2021-09-10 수정은 pc에서만...
												if (($rs_va_type == "19") and ($mode == "S")) {
											?>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="requestImage">이미지</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form button module-b style-b type-line normal-04 medium flex" style="padding-top:5pt;">
															<? if ($rs_va_img == "") {
																	$btn_name = "이미지 추가";
																	$img_src  = "";
																} else {
																	$btn_name = "이미지 수정";
																	$img_src  = " <a href='/upload_data/img/".$rs_va_img."'>".$rs_va_img."</a>";
																}
																?>
																<button onClick="js_open()" ><?=$btn_name?></button>
																<?=$img_src ?>
															</span>
														</div>
													</div>
												</div>
											</div>
											<?
												}
											?>

											<div class="form-item">
											<? if ($sPageRight_I <> "Y") { ?>
												<input type="hidden" name="va_state" id="va_state" value="0">
											<? } else { ?>
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="requesState">상태</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form select module-b style-b type-line normal-04 medium flex">
																<?= makeSelectBoxMobile($conn,"VA_STATE","va_state",""," 선택 ","",$rs_va_state)?>
															</span>
														</div>
													</div>
												</div>
											<? } ?>
											</div>

											<? if (($s_adm_no <> "4") && ($s_adm_no <> "1") && ($s_adm_no <> "25")){ 

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

													$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);

													if (sizeof($arr_leader) <= 0) {
														$LEVEL = $LEVEL - 1; //leader가 존재하지 않을 때 한단계 위!
														$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
													}
													
													$leader_no					= $arr_leader[0]["ADM_NO"];
													$leader_headquarters_code	= $arr_leader[0]["HEADQUARTERS_CODE"];
													$leader_name				= $arr_leader[0]["ADM_NAME"];
													$leader_email				= $arr_leader[0]["ADM_EMAIL"];
													$leader_dept_code			= $arr_leader[0]["DEPT_CODE"];
													$leader_position_code		= $arr_leader[0]["POSITION_CODE"];

													$arr_leader_all	= selectAdminLeaderAll($conn, $year); 

											?>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="approver">승인위치</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form select module-b style-b type-line normal-04 medium flex">
																<select class="form-elem" id="approver" name="va_state_pos">
																<? for ($i = 0 ; $i < sizeof($arr_leader_all) ; $i++) { 
																		 if ($my_level < $arr_leader_all[$i]["LEVEL"]) {  //리더 아래LEVEL은 나오지 않도록 2022-03-02
																				continue;
																		 } else {
																?>
																	<option value="<?=$arr_leader_all[$i]["ADM_NO"]?>" <?if ($leader_no == $arr_leader_all[$i]["ADM_NO"]){?>selected<?}?>>
																		<?=$arr_leader_all[$i]["ADM_NAME"]?>[<?=$arr_leader_all[$i]["POSITION_CODE"]?>]
																		<? if ($arr_leader_all[$i]["DEPT_CODE"] <> "") {?>
																					_<?=$arr_leader_all[$i]["DEPT_CODE"]?>
																		<? } ?>
																	</option>
																	<? } ?>
																<? } ?>
																</select>
															</span>
														</div>
													</div>
												</div>
											</div>
											<? } else {?>
													<input type="hidden" name="va_state_pos" value="25">
											<? } ?>
							<!--// 2022-04-07 전자결재 end -->
										</div>
									</fieldset>
									<!-- //SmartDay Input Form -->
									<!-- //submit-form -->
								</div>
							</div>
							<!-- //section -->
						</div>
						<!-- //content-body -->
						<!-- content-side -->
						<div class="content-side">
							<!-- noti-board -->
							<div class="noti-board module-a type-none normal-02 small attr-information">
								<div class="board-wrap">
									<div class="board-head"><p class="board-subject"><span class="board-name">연차등록 안내</span></p></div>
									<div class="board-body">
										<p class="para em accent-01">당일 연차 등록이 안되며, 사전등록을 할 시 내용 수정이 불가하오니 작성 시 유의하시기 바랍니다.</p>
										<p class="para em accent-01">연차 등록 시 '신청' 상태 이며 경영 지원팀에서 '승인' 처리 되어야 정상 사용이 가능 합니다.</p>
										<p class="para em normal-01">대체휴무 : 휴일, 주말 출근자 및 기타 사항</p>
										<p class="para em normal-01">리프레시 휴가 : 2년 근무자만 등록 가능</p>
										<p class="para em normal-03">1. 리플레쉬(2년근속) 휴가는 부득이한 경우를 제외하고는 1개월 사용을 원칙으로 합니다.(1일~말일) 단, 업무상 부득이한 경우에는 리더의 사유서 제출로 주 단위 사용이 가능합니다.</p>
										<p class="para em normal-03">2. 리플레쉬 휴가 결제는 기존 연차와 달리 대표님 최종 결제를 받으셔야 합니다.</p>
									</div>
								</div>
							</div>
							<!-- //noti-board -->
						</div>
						<!-- //content-side -->
					</div>
					<!-- //content -->
				</div>
				<!-- //local-body -->
				<!-- local-util -->
				<div class="local-util">
					<div class="button-display module-a style-b type-d">
						<span class="button-area">
							<button type="button" class="btn module-b style-a type-fill accent-01 x-large flex" onClick="js_save()"><span class="btn-text">확인</span></button>
						</span>
					</div>
				</div>
				<!-- //local-util -->
			</div>
			<!-- //local -->
		</div>
		<!-- //page-body -->
				<hr />
		<!-- page-foot -->
		<div class="page-foot" id="footer">
			<p class="copyright">U:COMPANION. ALL RIGHT RESERVED.</p>
		</div>
		<!-- //page-foot -->
	</div>
	<!-- //page -->
	<hr />

<!-- 결재 상태 변경 -->
<!-- toast-popup -->
<div class="toast-popup module-a style-b type-a medium" id="signoffForm" data-bui-toggle="toastPopup">
	<div class="popup-page-body">
		<!-- popup-local -->
		<div class="popup-local">
			<!-- popup-local-head -->
			<div class="popup-local-head">
				<h2 class="popup-local-title"><span class="popup-local-name">결재 상태 변경</span></h2>
			</div>
			<!-- //popup-local-head -->
			<!-- popup-local-body -->
			<div class="popup-local-body">
				<div class="popup-content">
					<!-- popup-content-body -->
					<div class="popup-content-body">
						<!-- section -->
						<div class="section module-a style-a type-a">
							<div class="section-head"><h3 class="section-title"><span class="section-name">결재 상태 변경</span></h3></div>
							<div class="section-body">
								<!-- submit-form -->
								<fieldset class="submit-form module-a type-a medium">
									<legend>결제 상태 입력 서식</legend>
									<div class="form-list">
										<div class="form-item">
											<div class="form-wrap">
												<div class="form-head"><label class="form-name" for="expensesType">결제위치</label></div>
												<div class="form-body">
													<div class="form-area">
														<span class="form select module-b style-b type-line normal-04 medium flex">
															<select class="form-elem" id="expensesType">
																<option>선택</option>
																<option>한수진[대표이사]</option>
															</select>
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="form-item">
											<div class="form-wrap">
												<div class="form-head"><label class="form-name" for="expensesType">결제상태</label></div>
												<div class="form-body">
													<div class="form-area">
														<span class="form select module-b style-b type-line normal-04 medium flex">
															<select class="form-elem" id="expensesType">
																<option>선택</option>
															</select>
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="form-item">
											<div class="form-wrap">
												<div class="form-head"><label class="form-name" for="expensesProjectName">사유</label></div>
												<div class="form-body">
													<div class="form-area">
														<span class="form textfield module-b style-b type-line normal-04 medium flex">
															<input type="text" class="form-elem" id="expensesProjectName" placeholder="프로젝트명을 입력해주세요." value="UCOMP 인트라넷" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								<!-- //submit-form -->
							</div>
						</div>
						<!-- //section -->
					</div>
					<!-- //popup-content-body -->
				</div>
			</div>
			<!-- //popup-local-body -->
			<!-- popup-local-util -->
			<div class="popup-local-util">
				<div class="button-display module-a style-b type-d">
					<span class="button-area">
						<button type="submit" class="btn module-b style-a type-fill accent-01 x-large flex"><span class="btn-text">총 #개 결재하기</span></button>
					</span>
				</div>
			</div>
			<!-- //popup-local-util -->
		</div>
		<!-- //popup-local -->
	</div>
</div>
<!-- //toast-popup -->


<!-- 임직원 근무 현황 상세 -->
<!-- content-popup -->
<div class="content-popup module-a style-a type-a small" id="schedule" data-bui-toggle="contentsPopup">
	<div class="popup-page-body">
		<!-- popup-local -->
		<div class="popup-local">
			<!-- popup-local-head -->
			<div class="popup-local-head">
				<h2 class="popup-local-title"><span class="popup-local-name">임직원 근무 현황</span></h2>
			</div>
			<!-- //popup-local-head -->
			<!-- popup-local-body -->
			<div class="popup-local-body">
				<div class="popup-content">
					<!-- popup-content-body -->
					<div class="popup-content-body">
						<!-- goods-display -->
						<div class="goods-display module-a style-b type-a">
							<div class="goods-list">
								<div class="goods-item">
									<div class="goods-wrap">
										<div class="goods-inform">
											<div class="goods-head">
												<p class="goods-title">
													<span class="goods-name">홍길순</span>
													<span class="goods-position">선임</span>
													<span class="goods-department">경영기획팀</span>
													<span class="goods-team">재무/회계</span>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- //goods-display -->
						<!-- data-display -->
						<div class="data-display module-a style-a type-a small">
							<ul class="data-list">
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">일자</span></div>
										<div class="data-body">2022-03-07</div>
									</div>
								</li>
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">업무연락</span></div>
										<div class="data-body">전화, 팀즈, 이메일</div>
									</div>
								</li>
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">업무내역</span></div>
										<div class="data-body">인트라넷 모바일 작업, 운영업무 대응</div>
									</div>
								</li>
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">연락처</span></div>
										<div class="data-body">010-2729-5982</div>
									</div>
								</li>
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">사유</span></div>
										<div class="data-body">오미크론 확산으로 인한 동선 최소화 재택 근무 격주 시행</div>
									</div>
								</li>
							</ul>
						</div>
						<!-- //data-display -->
					</div>
					<!-- //popup-content-body -->
				</div>
			</div>
			<!-- //popup-local-body -->
		</div>
		<!-- //popup-local -->
	</div>
</div>
<!-- //content-popup -->

<!-- 이미지 크게 보기 -->
<!-- content-popup -->
<div class="image-popup module-a style-a type-a medium" id="imageEnlarge" data-bui-toggle="imageEnlarge">
	<div class="popup-page-body">
		<!-- popup-local -->
		<div class="popup-local">
			<!-- popup-local-head -->
			<div class="popup-local-head">
				<h2 class="popup-local-title"><span class="popup-local-name">이미지 크게 보기</span></h2>
			</div>
			<div class="popup-local-body">
				<div class="popup-content">
					<img src="./../../assets/images/@temp/img_receipt_01_enlarge.png" alt="" />
				</div>
			</div>
			<!-- //popup-local-body -->
		</div>
		<!-- //popup-local -->
	</div>
</div>
<!-- //content-popup -->
</div>
<script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.1.1/air-datepicker.min.js"></script>

<script type="text/javascript">

	var disabledDays = [<?=$unable_dates?>]; 

	/**
	 * @module AirDatepicker datepickerInline
	 */

	 var now = new Date('<?=$selectedDate?>'); 
	 var nowDayOfWeek = now.getDay(); 
	 var nowDay = now.getDate(); 
	 var nowMonth = now.getMonth(); 
	 var nowYear = now.getYear(); 
	 nowYear += (nowYear < 2000) ? 1900 : 0; 
	 var weekStartDate = new Date(nowYear, nowMonth, nowDay - nowDayOfWeek); 
	 var weekEndDate = new Date(nowYear, nowMonth, nowDay + (6 - nowDayOfWeek));
	 //value.push(nowYear+formatDate(weekStartDate));
	 //value.push(nowYear+formatDate(weekEndDate));

	document.addEventListener('DOMContentLoaded', function() {
		const datepicker = new AirDatepicker('#datepickerInline', {
			inline: true,
			multipleDates: true,
			firstDay: 0,
			locale: {
				days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
				daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				daysMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
				monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				today: 'Today',
				clear: 'Clear',
				dateFormat: 'yyyy-MM-dd',
				timeFormat: 'hh:mm aa',
			},
			navTitles: {
				days: 'yyyy.MM'
			},
			prevHtml: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false"><title>전월</title><path d="M15.52,6.37a1.24,1.24,0,0,1,0,1.76L11.65,12l3.87,3.87a1.25,1.25,0,1,1-1.77,1.76L8.12,12l5.63-5.63A1.25,1.25,0,0,1,15.52,6.37Z"></path></svg>',
			nextHtml: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false"><title>차월</title><path d="M8.48,17.63a1.24,1.24,0,0,1,0-1.76L12.35,12,8.48,8.13a1.25,1.25,0,1,1,1.77-1.76L15.88,12l-5.63,5.63A1.25,1.25,0,0,1,8.48,17.63Z"></path></svg>',
			onSelect: function(date, formattedDate, datepicker) {

				document.getElementById('periodLeave').value = date.formattedDate;

			},
			onRenderCell({date, cellType}) {
				if (cellType == 'day') {
					var _date = (date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());
					var isDisabled = disabledDays.indexOf(_date) != -1;
					return {
						disabled: isDisabled
					}
				}
			}
	    });
	});

	/**
	 * @module buiExpand workingStatus
	 */
	const workingStatus = new buiExpand('[data-bui-expand="workingStatus"]', {
		accordion: false,
		activeClass: 'active',
		buttonClass: 'btn expand',
		buttonText: '<svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>펼치기</title><path d="M6.37,8.48a1.24,1.24,0,0,1,1.76,0L12,12.35l3.87-3.87a1.25,1.25,0,1,1,1.76,1.77L12,15.88,6.37,10.25A1.25,1.25,0,0,1,6.37,8.48Z"></path></svg>',
		buttonActiveText: '<svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>접기</title><path d="M17.63,15.52a1.24,1.24,0,0,1-1.76,0L12,11.65,8.13,15.52a1.25,1.25,0,1,1-1.76-1.77L12,8.12l5.63,5.63A1.25,1.25,0,0,1,17.63,15.52Z"></path></svg>',
		selectedDates: [], // 오늘 날짜

		activeAfterCallBack: function() {
			datepicker.update({
				minDate: false, // 금주 시작
				maxDate: false, // 금주 마지막
			});
		},
		inactiveAfterCallBack: function() {
			datepicker.update({
				minDate: weekStartDate, // 금주 시작
				maxDate: weekEndDate, // 금주 마지막
			});
		},
	});


</script>
</form>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>