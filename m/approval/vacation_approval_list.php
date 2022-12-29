<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : vacation_approval_list.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-27
# Modify Date  : 
#	Copyright : Copyright @ucom Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "EX002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header_mobile.php"; 

#=======================================================0==============
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/approval/vacation_approval.php";

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize						= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field					= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str						= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$con_headquarters_code			= $_POST['con_headquarters_code']!=''?$_POST['con_headquarters_code']:$_GET['con_headquarters_code'];  
	$con_va_user					= $_POST['con_va_user']!=''?$_POST['con_va_user']:$_GET['con_va_user'];

	$order							= $_POST['order']!=''?$_POST['order']:$_GET['order'];
	$mm								= $_POST['mm']!=''?$_POST['mm']:$_GET['mm'];
	$yy								= $_POST['yy']!=''?$_POST['yy']:$_GET['yy'];
	#List Parameter


	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field	= SetStringToDB($search_field);
	$search_str		= SetStringToDB($search_str);
	$search_field	= trim($search_field);
	$search_str		= trim($search_str);

#============================================================
# Page process
#============================================================

	if ($nPage <> "" && $nPageSize <> 0) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "" && $nPageSize <> 0) {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 200;
	}

	$nPageBlock	= 200;

	if (($con_headquarters_code <> "") || ($con_va_user <> "")){
		$search_field = $con_headquarters_code;
		$search_str		= $con_va_user ;
	}

//$_SESSION['s_adm_id']="soo";
//$_SESSION['s_adm_no']="25";

	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];
	$year = "202206";

	if ($yy == ""){
		$today_y = date("Y");
	} else {
		$today_y = $yy;
	}

	if ($mm == "") {
		$today_m = intval(date("m"));
	} else {
		$today_m = $mm; 
	}

	$today_m_1 = $today_m - 1;
	$today_y_1 = $today_y;

	if ($today_m == 1) {
		$today_y_1 = $today_y - 1;
		$today_m_1 = 12;
	} 

	if ($today_m == 13) {
		$today_y_1 = $today_y;
		$today_y = $today_y + 1;
		$today_m = 1;
	}

	$today_m_str = $today_y_1." - ".$today_m_1.", ".$today_m;
	//if ($today_m == 1) $today_m_str = $today_y."-".$today_m;

	$arr_rs = selectAdminLeaderYN($conn, $s_adm_no, $year); // leader(결재권한) 유무
	$LEADER_YN						= $arr_rs["LEADER_YN"];
	$HEADQUARTERS_CODE				= $arr_rs["HEADQUARTERS_CODE"];
	$OCCUPATION_CODE				= $arr_rs["OCCUPATION_CODE"];
	$DEPT_CODE						= $arr_rs["DEPT_CODE"];
	$DEPT_UNIT_NAME					= $arr_rs["DEPT_UNIT_NAME"];
	$LEVEL							= $arr_rs["LEVEL"];
	$POSITION_CODE					= $arr_rs["POSITION_CODE"];
	$my_dept_code					= $DEPT_CODE;
	$my_headquarters_code			= $HEADQUARTERS_CODE;
	$my_position_code				= $POSITION_CODE;
	$my_level						= $LEVEL;

	//리더의 no를 위해
	$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);

	if (sizeof($arr_leader) <= 0) {
		$LEVEL = $LEVEL - 1; //leader가 존재하지 않을 때 한단계 위!
		$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
	}

	if (sizeof($arr_leader) > 0) {
		$leader_no					= $arr_leader[0]["ADM_NO"];
		$leader_headquarters_code	= $arr_leader[0]["HEADQUARTERS_CODE"];
		$leader_name				= $arr_leader[0]["ADM_NAME"];
		$leader_email				= $arr_leader[0]["ADM_EMAIL"];
		$leader_dept_code			= $arr_leader[0]["DEPT_CODE"];
		$leader_position_code		= $arr_leader[0]["POSITION_CODE"];
	}
	//리더의 no end

	if (($s_adm_no == "1") || ($s_adm_no == "178") || ($s_adm_no == "4") || ($s_adm_no == "43") || ($s_adm_no == "44")){  //관리자경우 모두 보임!
		$LEVEL = "0";
		$LEADER_YN = "Y";
	}

	if ($LEADER_YN <> "Y") {
		header("location:/m/vacation/vacation_my.php");
		exit;
	}

	$r				= $_POST['r']!=''?$_POST['r']:$_GET['r'];
	$mode			= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ex_no			= $_POST['ex_no']!=''?$_POST['ex_no']:$_GET['ex_no'];

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
	
<script type="text/javascript" >

	function js_sel(t){
		if ((t == "2") || (t == "3")) {
			document.getElementById('23vastate').style.display='block';
			document.getElementById('memo').focus();
		} else {
			document.getElementById('23vastate').style.display='none';
		}
	}

	function js_check(){

		frm = document.cfrm;
		check = document.getElementsByName("chk[]");

		if($("#all_check span").text() == "전체선택"){
			for (i=0;i<check.length;i++) {
					check.item(i).checked=true;
			}
			$("#all_check span").text("선택해제");
		} else {
			for (i=0;i<check.length;i++) {
					check.item(i).checked=false;
			}
			$("#all_check span").text("전체선택");
		}
		return false;
	}

	function js_checked(){

		var frm = document.cfrm;
		var check = document.getElementsByName("chk[]");
		var sel_pos_ck = document.getElementById("sel_pos_ck");

		frm.leader_no.value = sel_pos_ck.options[sel_pos_ck.selectedIndex].value;
		leader_name = sel_pos_ck.options[sel_pos_ck.selectedIndex].text;
		chk = "";
		for (i=0;i<check.length;i++) {
			if (check.item(i).checked){
				chk = 1;
			}
		}	

		if(chk==""){
			alert("선택항목이 없습니다!");
			return false;

		} else {
			<? if ($s_adm_no == "25") { ?>
				re = confirm("선택한 항목을 승인하시겠습니까?");
			<? } else { ?>
				re = confirm("선택한 항목을 "+leader_name+" (으)로 승인 인계하시겠습니까?");
			<? } ?>
				if (re==true) {
					frm.mode.value = "OK_ALL"; 
					frm.method = "get";
					frm.action = "vacation_approval_list_dml.php";
					frm.submit();
				}
		}
	}

	function js_all(){

		var frm = document.cfrm;
		var check = document.getElementsByName("chk[]");
		var sel_pos_ck = document.getElementById("sel_pos_ck");

		frm.leader_no.value = sel_pos_ck.options[sel_pos_ck.selectedIndex].value;
		leader_name = sel_pos_ck.options[sel_pos_ck.selectedIndex].text;

		<? if ($s_adm_no == "25") { ?>
			re = confirm("일괄 승인하시겠습니까?");
		<? } else { ?>
			re = confirm(leader_name+" (으)로 일괄승인하시겠습니까?");
		<? } ?>
		
		if (re==true) {
			for (i=0;i<check.length;i++) {
					check.item(i).checked=true;
			}
			frm.mode.value = "OK_ALL"; 
			frm.method = "get";
			frm.action = "vacation_approval_list_dml.php";
			frm.submit();
		}

	}

	function js_view(t){
		location.href="vacation_approval_content?mode=S&seq_no="+t;
	}

	function js_approval(){

		var chk_length = $("input:checkbox[name='chk[]']:checked").length;

		if(chk_length == 0){
			alert("선택항목이 없습니다!");
			return false;
		} else {
			$("#total_count span").text("총 "+ chk_length + "개 결재하기");
			toastPopup.active('signoffForm');
		}
	}

</script>
</head>
<body>
<form id="wrap" name="cfrm">
	<!-- page -->
	<div id="page">
				<div id="skip" class="page-skip"><a class="skip-item" href="#content">본문 바로가기</a></div>
		<!-- page-head -->
		<div class="page-head" id="header">
			<h1 class="page-title" id="pageTitle"><a class="page-name" href="#">유컴패니온 인트라넷</a></h1>
		</div>
		<!-- //page-head -->
		<hr />
		<!-- page-navi -->
		<div class="page-navi">
			<ul class="navi-list">
				<li class="navi-item"><a class="navi-name" href="/m/main/main.php">메인</a></li>
				<li class="navi-item"><a class="navi-name" href="/m/notice/board_list.php">공지사항</a></li>
				<li class="navi-item"><a class="navi-name" href="/m/vacation/vacation.php">근무현황</a></li>
				<li class="navi-item"><a class="navi-name" href="/m/member/admin_list.php">임직원 조회</a></li>
				<li class="navi-item current" title="선택 됨"><span class="navi-name">결재관리</span></li>
			</ul>
		</div>
		<!-- //page-navi -->
		<hr />
		<!-- page-body -->
		<div class="page-body page-intro-tab page-approval">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-title" id="localTitle"><span class="local-name">결제관리</span></h2>
					<!-- local-navi -->
					<div class="local-navi">
						<ul class="navi-list">
							<li class="navi-item current" title="현재 선택된 항목"><span class="navi-name">연차결재</span></li>
							<li class="navi-item"><a class="navi-name" href="approval_list.php">지출결재</a></li>
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
							<h3 class="content-title"><span class="content-name">연차결제</span></h3>
							<!-- tab-display -->
							<div class="tab-display module-c style-a type-d medium">
								<ul class="tab-list">
								<? if (($LEADER_YN == "Y") || ($s_adm_no == 1) || ($s_adm_no == 178) || ($s_adm_no == 4) || ($s_adm_no == 43) || ($s_adm_no == 44)) { ?> 
									<li class="tab-item current" title="현재 선택된 항목"><span class="tab-name">연차결재</span></li>
									<li class="tab-item"><a class="tab-name" href="vacation_approval_all_list.php">모든요청</a></li>
								<? }
									 if (($s_adm_no <> 25) && ($s_adm_no <> 178) && ($s_adm_no <> 43 ) && ($s_adm_no <> 44 )) { ?>
									<li class="tab-item"><a class="tab-name" href="/m/vacation/vacation_my.php">나의요청</a></li>
								<? } ?>
								</ul>
							</div>
							<!-- //tab-display -->
						</div>
						<!-- //content-head -->
						<!-- content-body -->
						<? 
							$state = $r; //신청
							$arr_rs   = listVacationApproval($conn, $s_adm_no, $state, $search_field, $search_str); 
						?>
						<div class="content-body">
							<!-- post-display -->
							<div class="post-display module-b style-b type-a">
								<!-- post-info -->
								<div class="post-info">
									<div class="info-list">
										<div class="info-item">
											<!-- data-list -->
											<p class="data-list">
												<span class="data-item">
													<span class="head">모든 결재 문서</span>
													<span class="body"><?=sizeof($arr_rs)?>건</span>
												</span>
											</p>
											<!-- //data-list -->
										</div>
										<div class="info-item">
											<div class="button-display">
												<span class="button-area">
													<button class="btn module-a normal-03 small"><span class="btn-text">전체삭제</span></button>
													<button class="btn module-a normal-03 small" id="all_check" onClick="return js_check()"><span class="btn-text">전체선택</span></button>
												</span>
											</div>
										</div>
									</div>
								</div>
								<!-- //post-info -->
								
<div class="post-list">
<?
	if (sizeof($arr_rs) > 0) {

		for($j = 0 ; $j < sizeof($arr_rs) ; $j++){

				$SEQ_NO						= Trim($arr_rs[$j]["SEQ_NO"]);
				$VA_TYPE					= Trim($arr_rs[$j]["VA_TYPE"]);
				$VA_SDATE					= Trim($arr_rs[$j]["VA_SDATE"]);
				$VA_EDATE					= Trim($arr_rs[$j]["VA_EDATE"]);
				$VA_MDATE					= Trim($arr_rs[$j]["VA_MDATE"]);
				$VA_MEMO					= Trim($arr_rs[$j]["VA_MEMO"]);
				$VA_USER					= Trim($arr_rs[$j]["VA_USER"]);
				$VA_STATE					= Trim($arr_rs[$j]["VA_STATE"]);
				$VA_STATE_POS				= Trim($arr_rs[$j]["VA_STATE_POS"]);
				$MEMO						= Trim($arr_rs[$j]["MEMO"]);
				$VA_LOG						= explode("//", $arr_rs[$j]["VA_LOG"]);
				$REG_DATE					= substr($arr_rs[$j]["REG_DATE"], 0, 16);

				$VA_USER_NAME				= selectAdminName($conn, $VA_USER);
				$VA_USER_HEADQUARTERS_CODE	= selectAdminHeadquarters($conn, $VA_USER, $year);
				$VA_USER_DEPT_CODE			= selectAdminDept($conn, $VA_USER, $year);
				$VA_USER_POSITION_CODE		= selectAdminPosition($conn, $VA_USER, $year);
				$VA_STATE_NAME				= selectVaState($conn, $VA_STATE);
				$VA_TYPE_NAME				= selectVaType($conn, $VA_TYPE);

				$VA_STATE_POS_NAME			= selectAdminName($conn, $VA_STATE_POS);
				$VA_STATE_POS_POSITION_CODE = selectAdminPosition($conn, $VA_STATE_POS, $year);

				foreach($VA_LOG as $row) {
					$row_each	= explode("/", $row);
					$each_name			 = selectAdminName($conn, $row_each[0]);
					$each_postion		 = selectAdminPosition($conn, $row_each[0], $year);
					if ($each_name == "") continue; //첫 공백 제외
					$each_state			 = selectVaState($conn, $row_each[1]);
					if ($each_state == "미결") $each_state = "승인";
					$each_date			 = $row_each[2];
					$va_log_str	.= $each_name." ".$each_postion."(".$each_state.":".$each_date.")<br>";
				}

				$m_color				 = selectVaStateMobileClass($conn, $VA_STATE);
				$m_str					 = selectVaStateMobile($conn, $VA_STATE);

				//대표, 경영팀장, 관리자 : 모든 데이터
				//본부장, 이사 : 각 2개의 본부
				//팀장 : 같은 본부
				if (($s_adm_no == 14) || ($s_adm_no == 25) || ($s_adm_no == 1) ||($s_adm_no == 178) || ($s_adm_no == 4) || ($s_adm_no == 43) || ($s_adm_no == 44)) {
						$condition = true;
				} elseif ($my_level == 2){
						if ($my_headquarters_code == "") {  //본부 총괄 이사
							$condition = true;
						} else {
							$condition = (substr($my_headquarters_code,0,5) == substr($HEADQUARTERS_CODE,0,5)) ;
						}
				} else {
						$condition = ($my_headquarters_code == $HEADQUARTERS_CODE) ;
				}

				//진행중은 모두 보이도록 
				//if ($r == 4) $condition = true;  //2022-03-17 제거

				if($condition) { //같은 본부만 출력
?>
	<div class="post-item">
		<div class="post-wrap">
			<div class="post-select">
			<? if (($s_adm_no <> 1) && ($s_adm_no <> 178) && ($s_adm_no <> 4) && ($s_adm_no <> 43) && ($s_adm_no <> 44) && ($r == "")) {?>
				<span class="form module-a style-c type-a medium checkbox"><input type="checkbox" name="chk[]" value="<?=$SEQ_NO?>" class="form-elem" title="항목 선택"></span>
				<input type="hidden" name="vastate[]" value="<?=$VA_DATE?>">
			<? } ?>
			</div>
			<div class="post-inform">
				<div class="post-type">
					<p class="data-list">
						<span class="data-item"><span class="mark module-a style-b type-fill  <?=$m_color?> medium"><span class="mark-text"><?=$m_str?></span></span></span>
						<span class="data-item"><span class="mark module-a style-b type-line normal-03 medium"><span class="mark-text"><?=selectAdminName($conn, $VA_STATE_POS)." ".selectAdminPosition($conn, $VA_STATE_POS, $year)?></span></span></span>
					</p>
				</div>
				<div class="post-head">
					<p class="post-title">
						<? if ($VA_SDATE <> "") { 
								if($VA_SDATE <> $VA_EDATE) { 
									$mdate = $VA_SDATE."~".$VA_EDATE;
								} else {
									$mdate = $VA_SDATE;
								}
							} else {
								$mdate = $VA_MDATE;
								$mdate = str_replace("'", " ", $mdate);
								$mdate = substr($mdate, 0, strlen($mdate)-1);
							}
						?>
						<a class="post-name" onClick="js_view('<?=$SEQ_NO?>')" ><?=$VA_TYPE_NAME?> - <?=$mdate?></a>
					</p>
				</div>
				<div class="post-data">
					<ul class="data-list">
						<li class="data-item writer">
							<span class="head">글쓴이</span>
							<span class="body">
								<span class="name"><?=$VA_USER_NAME?> <?=selectAdminPosition($conn, $VA_USER, $year)?></span>
								<span class="department"><?=$HEADQUARTERS_CODE?></span>
								<span class="team"><?=$DEPT_CODE?></span>
							</span>
						</li>
						<li class="data-item posted">
							<span class="head">등록일</span>
							<span class="body">
								<span class="date"><?=$REG_DATE?></span>
							</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?
			}
		}
	} else {
?>
		<div>데이터가 없습니다!</div>
<?
	}
?>
</div>

							</div>
							<!-- //post-display -->
						</div>
						<!-- //content-body -->
					</div>
					<!-- //content -->
				</div>
				<!-- //local-body -->
				<!-- local-util -->
				<div class="local-util">
					<div class="button-display module-a style-b type-d">
						<span class="button-area">
							<a href="javascript:void(0)" class="btn module-b style-a type-fill accent-01 x-large flex" onclick="js_approval()"><span class="btn-text">결재하기</span></a>
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
													<? if (sizeof($arr_rs) > 0) { ?>
														 <input type="hidden" name="mode" id="mode" value="">
														 <input type="hidden" name="leader_yn" value="<?=$LEADER_YN?>">
														 <input type="hidden" name="headquarters_code" value="<?=$HEADQUARTERS_CODE?>">
														 <input type="hidden" name="dept_code" value="<?=$DEPT_CODE?>">
														 <input type="hidden" name="dept_unit_name" value="<?=$DEPT_UNIT_NAME?>">
														 <input type="hidden" name="level" value="<?=$LEVEL?>">
														 <? if ($r == "") {?>
															 <? if (($LEADER_YN == "Y") && ($s_adm_no <> 1) &&($s_adm_no <> 178) && ($s_adm_no <> 4) && ($s_adm_no <> 43) && ($s_adm_no <> 44)) { //리더들만 승인가능하도록, 시스템 관리자와 경영팀 불가!  ?>
																	<input type="hidden" name="leader_no" value="">
														<span class="form select module-b style-b type-line normal-04 medium flex">
														<? $arr_leader_all = selectAdminLeaderAll($conn, $year); ?> 
															<select name="sel_pos_ck" id="sel_pos_ck" class="form-elem" id="expensesType">
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
																<?	 } ?>
																<? } ?>
															</select>
														</span>
														<?	 } ?>
														<? } ?>
													<? } ?>
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
															<select name="sel" id="sel" class="form-elem" id="expensesType" onChange="js_sel(this.value)">
																<?	if ($s_adm_no == "25") { // (($s_adm_no == "1") || ($s_adm_no == "25")) {
																			$str_state = "1";
																		} else {
																			$str_state = "4";
																		}
																?>
																<option value="<?=$str_state?>">승인
																<option value="2">보류
																<option value="3">반려
															</select>
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="form-item" id="23vastate" style="display:none">
											<div class="form-wrap">
												<div class="form-head"><label class="form-name" for="memo">사유</label></div>
												<div class="form-body">
													<div class="form-area">
														<span class="form textfield module-b style-b type-line normal-04 medium flex">
															<input type="text" name="memo" id="memo" placeholder="보류/반려 사유를 입력해주세요" class="form-elem" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
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
						<button type="submit" onclick="return js_checked()" id="total_count" class="btn module-b style-a type-fill accent-01 x-large flex"><span class="btn-text">총 #개 결재하기</span></button>
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
</form>
</body>
</html>