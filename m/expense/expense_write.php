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
	include "../../_common/common_header_mobile.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/expense/expense.php";
	require "../../manager/approval/approval_mailform.php";

#====================================================================
	$savedir1 = $g_physical_path."upload_data/expense";
#====================================================================

	///* 각 계정 테스트용
	//$_SESSION['s_adm_no']="252";

	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];
	$year		 = "202206";
	//*/ 

	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
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
				$file_size				= $_FILES["ex_files"]["size"][$i];
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
			$rs_ex_no							= trim($arr_rs[0]["EX_NO"]);
			$rs_ex_type						= trim($arr_rs[0]["EX_TYPE"]);
			$rs_ex_title					= trim($arr_rs[0]["EX_TITLE"]);
			$rs_ex_date						= trim($arr_rs[0]["EX_DATE"]);
			$rs_ex_total_price		= trim($arr_rs[0]["EX_TOTAL_PRICE"]);
			$rs_va_user						= trim($arr_rs[0]["VA_USER"]);
			$rs_headquarters_code	= trim($arr_rs[0]["HEADQUARTERS_CODE"]);
			$rs_dept_code					= trim($arr_rs[0]["DEPT_CODE"]);
			$rs_va_state					= trim($arr_rs[0]["VA_STATE"]);
			$rs_va_state_pos			= trim($arr_rs[0]["VA_STATE_POS"]);
			//$rs_va_flag					= trim($arr_rs[0]["VA_FLAG"]);
			//$rs_va_img					= trim($arr_rs[0]["VA_IMG"]);

			$arr_rs_name					= selectAdmin2022($conn, $rs_va_user);
			$rs_va_user_name			= trim($arr_rs_name[0]["ADM_NAME"]);
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
		if($new_ex_no) $new_ex_no = $ex_no; 
		
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
				$file_size				= $_FILES["ex_files"]["size"][$i];
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
		document.location.href = "expense_ok.php?ex_no=<?=$new_ex_no?>";
</script>
<?	
		mysql_close($conn);
		exit;
	}	

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

<script type="text/javascript" src="/manager/js/httpRequest.js"></script> <!-- Ajax js -->
<script type="text/javascript">

var sel_files =[]; //이미지 정보들을 담을 배열

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

	ex_date_len = $('input[name="exd_date[]"]').length-1; // 마지막 div태그는 추가용이므로

	for (i=0;i<ex_date_len ;i++) {
		if ($('input[name="exd_date[]"]')[i].value == "") {
			alert((i+1)+'번째 날짜를 선택해주세요');
			$('input[name="exd_date[]"]')[i].focus();
			return ;
		}
	}

	ex_price_len = $('input[name="exd_price[]"]').length-1;

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

		if(document.querySelector('#ex_img div') == null){
			alert('이미지를 첨부해 주세요.');
			return ;
		}
	}
	//alert($('input[name="exd_no[]"]')[0].value);

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

</script>
</head>
<div id="wrap">
	<!-- page -->
	<div id="page">
		<!-- page-body -->
		<div class="page-body page-default page-approval">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-title" id="localTitle"><span class="local-name">결제관리</span></h2>
					<!-- local-navi -->
					<div class="local-navi">
						<ul class="navi-list">
							<li class="navi-item current" title="현재 선택된 항목"><a class="navi-name" href="#">연차결재</a></li>
							<li class="navi-item"><a class="navi-name" href="#">지출결재</a></li>
						</ul>
					</div>
					<!-- //local-navi -->
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-head -->
						<div class="content-head">
							<h3 class="content-title"><span class="content-name">지출결의 등록</span></h3>
							<div class="content-navi">
								<a class="btn" href="javascript:history.back();"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>뒤로</title><path d="M12.7,5.7a1,1,0,0,0-1.4-1.4l-7,7a1,1,0,0,0,0,1.4l7,7a1,1,0,0,0,1.4-1.4L7.4,13H19a1,1,0,0,0,0-2H7.4Z"></path></svg></a>
							</div>
						</div>
						<!-- //content-head -->
						<!-- content-body -->
						<form class="content-body" name="frm" method="post" enctype="multipart/form-data">
<!--<form name="frm" method="post" enctype="multipart/form-data">-->
<input type="hidden" name="ex_no" value="<?=$rs_ex_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="ex_ip" value="<?=$_SERVER['REMOTE_ADDR']?>" />
<input type="hidden" name="ex_files_cnt" value="" />
<input type="hidden" name="adm_email" value="" />
<input type="hidden" name="leader_no" value="" />
<body>
							<!-- noti-board -->
							<div class="noti-board module-a style-b type-fill accent-01 x-small attr-caution">
								<div class="board-wrap">
									<div class="board-head"><p class="board-subject"><span class="board-name">승인 이후 수정이 불가하오니 내용 확인 후 등록 바랍니다.</span></p></div>
								</div>
							</div>
							<!-- //noti-board -->
							<!-- submit-form -->

							<fieldset class="submit-form module-a type-a medium">
								<legend>지출결의 입력 서식</legend>
								<div class="form-list">
									<div class="form-item">
										<div class="form-wrap">
											<div class="form-head"><label class="form-name" for="requesterName">신청자</label></div>
											<div class="form-body">
												<div class="form-area">
													<span class="form textfield module-b style-b type-line normal-04 medium flex">
													<? if (($s_adm_no <> "1") && ($s_adm_no <> "178") && ($s_adm_no <> "4")) { ?>
															<input type="text" class="form-elem" value="<?=$s_adm_nm?>" readonly="readonly">
															<input type="hidden" name="va_user" id="va_user" value="<?=$s_adm_no?>">
													<? } else { ?>
															<input type="text" name="con_va_user_name" value="<?=$rs_va_user_name?>" onBlur="js_name(this.value)" class="form-elem" id="requesterName" placeholder="" value="이지혜" readonly="readonly" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
															<input type="hidden" name="va_user" id="va_user" value="<?=$rs_va_user?>">
													<? } ?>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="form-item">
										<div class="form-wrap">
											<div class="form-head"><label class="form-name" for="requesterDepartment">본부</label></div>
											<div class="form-body">
												<div class="form-area">
													<?
													$arr_rs = selectAdmin2022($conn,$s_adm_no);
													$LEADER_YN						= $arr_rs[0]["LEADER_YN"];
													$HEADQUARTERS_CODE		= $arr_rs[0]["HEADQUARTERS_CODE"];
													$OCCUPATION_CODE			= $arr_rs[0]["OCCUPATION_CODE"];
													$DEPT_CODE						= $arr_rs[0]["DEPT_CODE"];
													$DEPT_UNIT_NAME				= $arr_rs[0]["DEPT_UNIT_NAME"];
													$ADM_EMAIL						= $arr_rs[0]["ADM_EMAIL"];
													$LEVEL								= $arr_rs[0]["LEVEL"];
													$LEADER_TITLE					= $arr_rs[0]["LEADER_TITLE"];
													$my_dept_code					= $DEPT_CODE;
													$my_headquarters_code	= $HEADQUARTERS_CODE;
													$my_position_code			= $POSITION_CODE;
													$my_level							= $LEVEL;

													if (($s_adm_no <> "1") && ($s_adm_no <> "178") && ($s_adm_no <> "4") && ($s_adm_no <> "43") && ($s_adm_no <> "44")) { ?>
													<span class="form textfield module-b style-b type-line normal-04 medium flex">
														<input type="text" class="form-elem" value="<?=$HEADQUARTERS_CODE?>" readonly >
														<input type="hidden" name="headquarters_code" id="headquarters_code" value="<?=$HEADQUARTERS_CODE?>">
														<!--
														<input type="text" class="form-elem" id="requesterDepartment" placeholder="" value="서비스 운영 본부" readonly="readonly" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
														-->
													</span>
												<? } else { ?>
														<span class="form select module-b style-b type-line normal-04 medium flex">
															<?= makeSelectBoxMobile($conn,"HEADQUARTERS_2022","headquarters_code","125px","선택","",$rs_headquarters_code)?>
														</span>
												<? } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-item">
										<div class="form-wrap">
											<div class="form-head"><label class="form-name" for="requesterTeam">부서</label></div>
											<div class="form-body">
												<div class="form-area">
													<? if (($s_adm_no <> "1") && ($s_adm_no <> "178") && ($s_adm_no <> "4") && ($s_adm_no <> "43") && ($s_adm_no <> "44")) { ?>
													<span class="form textfield module-b style-b type-line normal-04 medium flex">
														<input type="text" class="form-elem" value="<?=$DEPT_CODE?>" readonly >
														<input type="hidden" name="dept_code" id="dept_code" value="<?=$DEPT_CODE?>">
														<!--<input type="text" class="form-elem" id="requesterTeam" placeholder="" value="UX팀" readonly="readonly" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />-->
													</span>
													<? } else { ?>
														<span class="form select module-b style-b type-line normal-04 medium flex">
															<?= makeSelectBoxMobile($conn,"DEPT_2022","dept_code","125px","선택","",$rs_dept_code)?>
														</span>
													<? } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-item">
										<div class="form-wrap">
											<div class="form-head"><label class="form-name" for="requestTitle">제목</label></div>
											<div class="form-body">
												<div class="form-area">
												<?
													if ($ex_no == "")	{
														$str_title = date("m", time())."월 지출결의_".$s_adm_nm;
														$str_date	 = date("Y-m-d", time());
													} else {
														$str_title = $rs_ex_title;
														$str_date	 = $rs_ex_date;
													}
												?>
													<span class="form textfield module-b style-b type-line normal-04 medium flex">
														<input type="text" name="ex_title" class="form-elem" value="<?=$str_title?>" id="requestTitle" placeholder="" />
														<input type="hidden" name="ex_date" class="txt" value="<?=$str_date?>" />
														<!--<input type="text" class="form-elem" id="requestTitle" placeholder="" value="02월 지출결의_이지혜" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />-->
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="form-item">
										<div class="form-wrap">
											<div class="form-head"><label class="form-name" for="approver">승인위치</label></div>
											<div class="form-body">
											<?	
													$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);

													if (sizeof($arr_leader) <= 0) {
														$LEVEL = $LEVEL - 1; //leader가 존재하지 않을 때 한단계 위!
														$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
													}
													
													$leader_no								= $arr_leader[0]["ADM_NO"];
													$leader_headquarters_code = $arr_leader[0]["HEADQUARTERS_CODE"];
													$leader_name							= $arr_leader[0]["ADM_NAME"];
													$leader_email							= $arr_leader[0]["ADM_EMAIL"];
													$leader_dept_code					= $arr_leader[0]["DEPT_CODE"];
													$leader_position_code			= $arr_leader[0]["POSITION_CODE"];

													$arr_leader_all = selectAdminLeaderAll($conn, $year); 
											?>
												<div class="form-area">
													<span class="form select module-b style-b type-line normal-04 medium flex">
														<select name="va_state_pos" class="form-elem" id="approver">
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

									<div class="form-item">
										<div class="form-wrap">
											<div class="form-head"><label class="form-name" for="va_state">상태</label></div>
											<div class="form-body">
												<div class="form-area">
													<? if ($sPageRight_I <> "Y") { ?>
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="hidden" name="va_state" value="0">
																<input type="text" class="form-elem" value="신청">
															</span>
													<? } else { ?>
															<span class="form select module-b style-b type-line normal-04 medium flex">
															<? if (($s_adm_no == 1) || ($s_adm_no == 178)|| ($s_adm_no == 4)){ ?>
																	<?= makeSelectBoxMobile($conn,"VA_STATE","va_state",""," 선택 ","",$rs_va_state)?>
															<? } else { ?>
																		<select name="va_state" class="form-elem" id="va_state">
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
												</div>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<!-- //submit-form -->
							<!-- section -->
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
									$rs_exd_content			= trim($arr_rs_date[$i]["EXD_CONTENT"]);
									$rs_exd_project			= trim($arr_rs_date[$i]["EXD_PROJECT"]);
									$rs_exd_price				= trim($arr_rs_date[$i]["EXD_PRICE"]);
									$rs_exd_memo				= trim($arr_rs_date[$i]["EXD_MEMO"]);
							?>
							<div class="section module-a style-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name">지출내역</span></h4></div>
								<div class="section-body">
									<!-- submit-form -->
									<fieldset class="submit-form module-a type-a medium">
										<legend>지출내역 입력 서식</legend>
										<div class="form-list">
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesDate">날짜</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form date module-b style-b type-line normal-04 medium flex">
																<input type="date" name="exd_date[]" value="<?=$rs_exd_date?>" class="form-elem" placeholder="날짜를 선택해주세요." />
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesType">지출타입</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form select module-b style-b type-line normal-04 medium flex">
																<?= makeSelectBoxMobile($conn,"EXPENSE_TYPE","exd_type[]","50px","선택","",$rs_exd_type)?>
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesHistory">상세내역</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="text" value="<?=$rs_exd_content?>" name="exd_content[]" class="form-elem" placeholder="지출 상세내역을 입력해주세요." value="12시 지나 퇴근이라 택시탐" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesProjectName">프로젝트</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="text" value="<?=$rs_exd_project?>" name="exd_project[]" class="form-elem" placeholder="프로젝트명을 입력해주세요." value="UCOMP 인트라넷" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesAmount">금액</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="text" value="<?=$rs_exd_price?>" name="exd_price[]" class="form-elem" onBlur="js_sum()" placeholder="금액을 입력해주세요." value="11,000" pattern="^[0-9]+$" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
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
							<?
								}
							} else {
							?>
							<div class="section module-a style-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name">지출내역</span></h4></div>
								<div class="section-body">
									<!-- submit-form -->
									<fieldset class="submit-form module-a type-a medium">
										<legend>지출내역 입력 서식</legend>
										<div class="form-list">
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesDate">날짜</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form date module-b style-b type-line normal-04 medium flex">
																<input type="date" name="exd_date[]" value="<?=date("Y-m-d")?>" class="form-elem" placeholder="날짜를 선택해주세요." />
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesType">지출타입</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form select module-b style-b type-line normal-04 medium flex">
																<?= makeSelectBoxMobile($conn,"EXPENSE_TYPE","exd_type[]","50px","선택","",$rs_exd_type)?>
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesHistory">상세내역</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="text" value="<?=$rs_exd_content?>" name="exd_content[]" class="form-elem" placeholder="지출 상세내역을 입력해주세요." value="" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesProjectName">프로젝트</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="text" value="<?=$rs_exd_project?>" name="exd_project[]" class="form-elem" placeholder="프로젝트명을 입력해주세요." value="" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-item">
												<div class="form-wrap">
													<div class="form-head"><label class="form-name" for="expensesAmount">금액</label></div>
													<div class="form-body">
														<div class="form-area">
															<span class="form textfield module-b style-b type-line normal-04 medium flex">
																<input type="text" value="<?=$rs_exd_price?>" name="exd_price[]" onBlur="js_sum()" class="form-elem" placeholder="금액을 입력해주세요." value="" pattern="^[0-9]+$" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
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
							<?
							}
							?>

							<div id="imsi">
							</div>

							<!-- button-display -->
							<div class="button-display module-a style-a type-c">
								<span class="button-area">
									<button type="button" onClick="js_inputAdd()" class="btn module-c style-b type-line accent-02 small symbol-ltr-fill-plus flex"><span class="btn-text">내역추가</span></button>
								</span>
							</div>
							<!-- //button-display -->

							<!-- section -->
							<div class="section module-a style-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name">영수증 사진 첨부</span></h4></div>
								<div class="section-body">
									<!-- file-upload -->
									<fieldset class="file-upload module-a style-a type-a attr-scroll">
										<!-- <legend>파일 등록 서식</legend> -->
										<div class="upload-wrap">
											<div class="upload-head">
												<div class="file-to-upload"><input type="file" class="file-elem" title="파일 선택" name="ex_files[]" id="input_imgs" value="<?=$rs_va_img?>" accept="image/*" multiple onChange="js_images_name(this.files.length)" /></div>
												<div class="uploaded-files"><span class="head">선택된 파일</span> <span class="body" id="ex_img_cnt">0건</span></div>
											</div>
											<div class="upload-body">
												<div class="file-list" id="ex_img">
												<? if (sizeof($arr_rs_file) > 0) { 
															for ($i=0 ; $i < sizeof($arr_rs_file); $i++){
																$rs_file_nm			= trim($arr_rs_file[$i]["FILE_NM"]);
												?>
																<div class="file-item" style="--thumbnail-image: url('/upload_data/expense/<?=$rs_file_nm?>');" id="file_<?=$i?>">
																	<span class="file-name" ><?=$rs_file_nm?></span>
																<? if (($va_user <> "1") && ($va_user <> "178") && ($va_user <> "43") && ($va_user <> "44")) {
																			if (($sPageRight_I <> "Y") || ($rs_va_state == "") || ($rs_va_state == "0") || ($va_user == "4")) { ?>
																				<button type="button" class="btn file-delete" onClick="js_img_delete('<?=$ex_no?>','<?=$rs_file_nm?>')"><span class="btn-text">삭제</span></button>
																<?		}
																	 }
																?>
																</div>
												<?
															} 
													 } 
												?>
												</div>
											</div>
										</div>
									</fieldset>
									<!-- //file-upload -->
								</div>
							</div>
							<!-- //section -->
						</div>
						<!-- //content-body -->
					</div>
					<!-- //content -->
				</div>
				<!-- //local-body -->
				<!-- local-util -->
				<div class="local-util">
					<div class="request-amount">
						<span class="head">총 <input type="text" name="total_count" value="<?=$i?>" style="border:0;text-align:right;width:20px;">건</span>
						<span class="body"><span class="text"><input type="text" name="ex_total_price" value="<?=$rs_ex_total_price?>" style="border:0;text-align:right;width:140px;"></span><span class="unit">원</span></span>
					</div>
					<div class="button-display module-a style-b type-d">
						<span class="button-area">
							<a href="#signoffForm" class="btn module-b style-a type-fill accent-01 x-large flex" onclick="js_save();/*toastPopup.active('signoffForm');*/"><span class="btn-text">결재 등록하기</span></a>
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
					<img src="./../../assets/images/@temp/img_receipt_01_enlarge.png" alt="" id="image_popup"/>
				</div>
			</div>
			<!-- //popup-local-body -->
		</div>
		<!-- //popup-local -->
	</div>
</div>
<!-- //content-popup -->
</div>
<iframe id="ifr_hidden" src="" frameborder="No" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>

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

		var t = $("#section_id").html();
		$("#imsi").append(t);

/*
		var detail_cnt =$("#item_add").find("tr").length;

		var t = "<tr>";
				t +="<td><span class='inpbox'><input type='text' class='date' name='exd_date[]' autocomplete='off' onClick='datep()' readonly /></span></td>";
				t +="<td><span class='optionbox'><?= makeSelectBox($conn,'EXPENSE_TYPE','exd_type[]','50px','선택','',$rs_exd_type)?></span></td>";
				t +="<td><span class='inpbox'><input type='text' name='exd_content[]' class='txt' /></span></td>";
				t +="<td><span class='inpbox'><input type='text' name='exd_project[]' class='txt' /></span></td>";
				t +="<td><span class='inpbox'><input type='text' name='exd_price[]' class='txt' style='text-align:right' onBlur='js_sum()' onkeyup='js_numcheck(this, event.keyCode)' /></span></td>";
				t +="<td><img src='../images/del.png' width='30' onClick='js_image(this)' style='cursor:pointer'></td>";
				t +="</tr>";

		$("#item_add").append(t);
		datep();  //append후 date 박스 클릭 안되는 오류를 막기위해
*/
	}

	function js_inputRemove(t){
		//$(t).parent().parent().next().remove();
		$(t).parent().parent().remove();
		js_sum();
	}

	function js_sum(){

		var detail_cnt = $('input[name="exd_price[]"]').length;
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

		$("#ex_img *").remove();
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
				//str_image = "<a href=\"javascript:void(0);\" onClick=\"deleteImageAction("+index+")\" id=\"img_id_"+index+"\"><img src='"+event.target.result+"' width='100px' height='150px' style='vertical-align:bottom;'>"+event.target.fileName+"</a> ";
				str_image = "<div class=\"file-item\"  onClick=\"deleteImageAction("+index+")\" id=\"img_id_"+index+"\" style=\"--thumbnail-image: url('"+event.target.result+"');\"><span class=\"file-name\">"+event.target.fileName+"</span> <button type=\"button\" class=\"btn file-delete\"><span class=\"btn-text\">삭제</span></button></div></a>";

				$("#ex_img").append(str_image); //event가 for문 밖으로 나가면 사라짐 ..그래서 += 이용하지 않음
				index++;
			}
		}
		$("#ex_img_cnt").html(i+"건");
		//$("#ex_img").= str_image_name;

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
		$("#ex_img_cnt").html(input.files.length+"건");

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

<div id="section_id" style="display:none">
	<!-- section -->
	<div class="section module-a style-a type-a">
		<div class="section-head"><h4 class="section-title">
		<span class="section-name">지출내역 
			<div class="section-func">
				<span class="button-area">
					<button type="button" class="btn delete" onClick="$(this).parent().parent().parent().parent().parent().parent().remove();"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>삭제</title><path d="M19,6.77A1.25,1.25,0,1,0,17.23,5L12,10.23,6.77,5A1.25,1.25,0,0,0,5,6.77L10.23,12,5,17.23A1.25,1.25,0,1,0,6.77,19L12,13.77,17.23,19A1.25,1.25,0,1,0,19,17.23L13.77,12Z"></path></svg></button>
				</span>
			</div>
		<!--<button type="button" onClick="$(this).parent().parent().parent().parent().remove();">X</button>--></span></h4></div>
		<div class="section-body">
			<!-- submit-form -->
			<fieldset class="submit-form module-a type-a medium">
				<legend>지출내역 입력 서식</legend>
				<div class="form-list">
					<div class="form-item">
						<div class="form-wrap">
							<div class="form-head"><label class="form-name" for="expensesDate2">날짜</label></div>
							<div class="form-body">
								<div class="form-area">
									<span class="form date module-b style-b type-line normal-04 medium flex">
										<input type="date" name="exd_date[]" class="form-elem" placeholder="날짜를 선택해주세요." value="<?=date("Y-m-d")?>" />
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-item">
						<div class="form-wrap">
							<div class="form-head"><label class="form-name" for="expensesType2">지출타입</label></div>
							<div class="form-body">
								<div class="form-area">
									<span class="form select module-b style-b type-line normal-04 medium flex">
										<?= makeSelectBoxMobile($conn,"EXPENSE_TYPE","exd_type[]","50px","선택","",$rs_exd_type)?>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-item">
						<div class="form-wrap">
							<div class="form-head"><label class="form-name" for="expensesHistory2">상세내역</label></div>
							<div class="form-body">
								<div class="form-area">
									<span class="form textfield module-b style-b type-line normal-04 medium flex">
										<input type="text" name="exd_content[]" class="form-elem" placeholder="지출 상세내역을 입력해주세요." value="" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-item">
						<div class="form-wrap">
							<div class="form-head"><label class="form-name" for="expensesProjectName2">프로젝트</label></div>
							<div class="form-body">
								<div class="form-area">
									<span class="form textfield module-b style-b type-line normal-04 medium flex">
										<input type="text" name="exd_project[]" class="form-elem" placeholder="프로젝트명을 입력해주세요." value="" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-item">
						<div class="form-wrap">
							<div class="form-head"><label class="form-name" for="expensesAmount2">금액</label></div>
							<div class="form-body">
								<div class="form-area">
									<span class="form textfield module-b style-b type-line normal-04 medium flex">
										<input type="text" name="exd_price[]" onBlur="js_sum()" class="form-elem" placeholder="금액을 입력해주세요." pattern="^[0-9]+$" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
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

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>