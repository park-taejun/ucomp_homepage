<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : expense_list.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-19
# Modify Date  :
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
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
	require "../../_common/common_header_mobile.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/expense/expense.php";

	$con_year				= $_POST['con_year']!=''?$_POST['con_year']:$_GET['con_year'];
	$con_dept_code	= $_POST['con_dept_code']!=''?$_POST['con_dept_code']:$_GET['con_dept_code'];
	$con_va_user		= $_POST['con_va_user']!=''?$_POST['con_va_user']:$_GET['con_va_user'];

	if ($con_year == "") {
		$con_year = date("Y",time());
	}

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

#===============================================================
# Get Search list count
#===============================================================

//$_SESSION['s_adm_no'] = 1;

	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];

	$year		 = "202206";

	if ($con_va_user <> ""){
		$va_user = $con_va_user;
	} else {
		$va_user = $s_adm_no;
	}

	if (($va_user == "1") || ($va_user == "4") || ($va_user == "178") || ($va_user == "43") || ($va_user == "44")) {  //관리자들은 모두 보이도록 하지만 결재는 안됨
		$va_user = ""; 
	}

	$nListCnt =totalCntExpense($conn, $va_type, $va_user); //$con_group_no, $con_com_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listExpense($conn, $va_user, $nPage, $nPageSize);

	$arr_rs_leader = selectAdminLeaderYN($conn, $s_adm_no, $year); //관리자보기
	$LEADER_YN = $arr_rs_leader["LEADER_YN"];
	$LEVEL		 = $arr_rs_leader["LEVEL"];
	$my_level	 = $LEVEL;

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

<script language="javascript">

	function js_write() {
		document.location = "expense_write.php";
	}

	function js_excel_print() {
		var frm = document.frm;
		frm.method = "post";
		frm.target = "";
		frm.action = "expense_excel_list.php";
		frm.submit();
	}

//사용자 이름으로 검색 수정 2021.08.17
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
					frm.con_va_user.value = "";
					frm.con_va_user_name.focus();
					return false;
				} else {
					frm.con_va_user.value = data.msg;
					frm.action = "<?=$_SERVER[PHP_SELF]?>";
					frm.method = "post";
					frm.target = "";
					frm.submit();
				}
			});
		} else {
			frm.con_va_user.value = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.method = "post";
			frm.target = "";
			frm.submit();
		}
	}

</script>
</head>

<body>
<div id="wrap">
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
					<h2 class="local-title" id="localTitle"><span class="local-name">결재관리</span></h2>
					<!-- local-navi -->
					<div class="local-navi">
						<ul class="navi-list">
							<li class="navi-item"><a class="navi-name" href="/m/approval/vacation_approval_list.php">연차결재</a></li>
							<li class="navi-item current" title="현재 선택된 항목"><span class="navi-name">지출결재</span></li>
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
					<? if ($LEADER_YN <> "Y") { ?>
						<div class="content-head">
							<h3 class="content-title"><span class="content-name">지출결재</span></h3>
						</div>
					<? } else { ?>
						<div class="content-head">
							<h3 class="content-title"><span class="content-name">지출결제</span></h3>
							<!-- tab-display -->
							<div class="tab-display module-c style-a type-d medium">
								<ul class="tab-list">
								<? if (($LEADER_YN == "Y") || ($s_adm_no == 1) || ($s_adm_no == 178) || ($s_adm_no == 4) || ($s_adm_no == 43) || ($s_adm_no == 44)) { ?> 
									<li class="tab-item"><a class="tab-name" href="/m/approval/approval_list.php">지출결재</a></li>
									<li class="tab-item"><a class="tab-name" href="/m/approval/approval_all_list.php">모든요청</a></li>
								<? }
									 if (($my_level <> 0) && ($s_adm_no <> 1 ) && ($s_adm_no <> 178) && ($s_adm_no <> 4 ) && ($s_adm_no <> 43 ) && ($s_adm_no <> 44 )) { ?>
									<li class="tab-item current" title="현재 선택된 항목"><a class="tab-name" href="/m/expense/expense_list.php">나의요청</a></li>
								<? } ?>
								</ul>
							</div>
							<!-- //tab-display -->
						</div>
					<? } ?>

						<!-- //content-head -->
						<!-- content-body -->
						<div class="content-body">
							<!-- post-display -->
							<div class="post-display module-b style-b type-a">
								<!-- post-info -->
								<div class="post-info">
									<div class="info-list">
										<div class="info-item">
										<? if ($LEADER_YN <> "Y") { ?>
											<!-- data-list -->
											<p class="data-list">
												<span class="data-item">
													<span class="head">나의요청</span>
												</span>
											</p>
											<!-- //data-list -->
										<? } ?>
										</div>
									</div>
								</div>
								<!-- //post-info -->

<form name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="con_va_user" value="<?=$con_va_user?>">
<input type="hidden" name="va_user" value="">

<div class="post-list">

<?
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$rn								= trim($arr_rs[$j]["rn"]);
			$EX_NO						= trim($arr_rs[$j]["EX_NO"]);
			$EX_TITLE					= SetStringFromDB($arr_rs[$j]["EX_TITLE"]);
			$EX_DATE					= trim($arr_rs[$j]["EX_DATE"]);
			$EX_MEMO					= Trim($arr_rs[$j]["EX_MEMO"]);
			$EX_TOTAL_PRICE		= trim($arr_rs[$j]["EX_TOTAL_PRICE"]);
			$VA_USER					= trim($arr_rs[$j]["VA_USER"]);
			$HEADQUARTERS_CODE= Trim($arr_rs[$j]["HEADQUARTERS_CODE"]);
			$DEPT_CODE				= trim($arr_rs[$j]["DEPT_CODE"]);
			$VA_STATE					= trim($arr_rs[$j]["VA_STATE"]);
			$VA_STATE_POS			= trim($arr_rs[$j]["VA_STATE_POS"]);
			
			$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
			$VA_STATE_NAME		=	selectVaState($conn, $VA_STATE);
			$va_color = "";
			$m_color	= selectVaStateMobileClass($conn, $VA_STATE);
			$m_str		= selectVaStateMobile($conn, $VA_STATE);

			$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;

?>

	<div class="post-item">
		<div class="post-wrap">
			<div class="post-inform">
				<div class="post-type">
					<p class="data-list">
						<span class="data-item"><span class="mark module-a style-b type-fill <?=$m_color?> medium"><span class="mark-text"><?=$m_str?></span></span></span>
						<span class="data-item"><span class="mark module-a style-b type-line normal-03 medium"><span class="mark-text"><?=selectAdminName($conn, $VA_STATE_POS)." ".selectAdminPosition($conn, $VA_STATE_POS, $year)?></span></span></span>
					</p>
				</div>
				<div class="post-head">
					<p class="post-title">
						<a class="post-name" href="expense_view.php?ex_no=<?=$EX_NO?>"><?=$EX_TITLE?></a>
					</p>
				</div>
				<div class="post-data">
					<ul class="data-list">
						<li class="data-item writer">
							<span class="head">글쓴이</span>
							<span class="body">
								<span class="name"><?=selectAdminName($conn, $VA_USER)?></span>
								<span class="department"><?=selectAdminHeadquarters($conn, $VA_USER, $year)?></span>
								<span class="team"><?=selectAdminPosition($conn, $VA_USER, $year)?></span>
							</span>
						</li>
						<li class="data-item posted">
							<span class="head">등록일</span>
							<span class="body">
								<span class="date"><?=substr($REG_DATE,0,16)?></span>
							</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?			
		}
	} else { 
?> 
		<div>
			데이터가 없습니다.
		</div>
<? 
	}
?>

</div>

							</div>
							<!-- //post-display -->
							<!-- button-display -->
							<? if (($s_adm_no <> "1") && ($s_adm_no <> "178") && ($s_adm_no <> "43") && ($s_adm_no <> "44")) { ?>
							<div class="button-display module-fab">
								<span class="button-area">
									<a href="javascript:js_write()" class="btn module-ico style-c type-fill accent-01 x-large symbol-fill-write"><span class="btn-text">작성</span></a>
								</span>
							</div>
							<? } ?>
							<!-- //button-display -->
						</div>
						<!-- //content-body -->
					</div>
					<!-- //content -->
				</div>
				<!-- //local-body -->
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
</body>
</html>