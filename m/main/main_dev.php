<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : main.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-04-26
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================

	if ($_SESSION['s_adm_no'] == "") {

		$next_url = "../login.php";

?>
<meta http-equiv='Refresh' content='0; URL=<?=$next_url?>'>
<?
			exit;
	}

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/vacation/vacation.php";
	require "../../_classes/biz/board/board.php";
	

	// 내 남은 연차 조회
	$con_year = date("Y",time());

	$arr_rs = listUserVacationYear($conn, $con_year, "", "", $_SESSION['s_adm_no']);
	
	$j = 0;

	$VA_CNT						= trim($arr_rs[$j]["VA_CNT"]);
	
	$M_1M							= trim($arr_rs[$j]["1M"]);
	$M_2M							= trim($arr_rs[$j]["2M"]);
	$M_3M							= trim($arr_rs[$j]["3M"]);
	$M_4M							= trim($arr_rs[$j]["4M"]);
	$M_5M							= trim($arr_rs[$j]["5M"]);
	$M_6M							= trim($arr_rs[$j]["6M"]);
	$M_7M							= trim($arr_rs[$j]["7M"]);
	$M_8M							= trim($arr_rs[$j]["8M"]);
	$M_9M							= trim($arr_rs[$j]["9M"]);
	$M_10M						= trim($arr_rs[$j]["10M"]);
	$M_11M						= trim($arr_rs[$j]["11M"]);
	$M_12M						= trim($arr_rs[$j]["12M"]);
	$M_13M						= trim($arr_rs[$j]["13M"]);

	$M_1S							= trim($arr_rs[$j]["1S"]);
	$M_2S							= trim($arr_rs[$j]["2S"]);
	$M_3S							= trim($arr_rs[$j]["3S"]);
	$M_4S							= trim($arr_rs[$j]["4S"]);
	$M_5S							= trim($arr_rs[$j]["5S"]);
	$M_6S							= trim($arr_rs[$j]["6S"]);
	$M_7S							= trim($arr_rs[$j]["7S"]);
	$M_8S							= trim($arr_rs[$j]["8S"]);
	$M_9S							= trim($arr_rs[$j]["9S"]);
	$M_10S						= trim($arr_rs[$j]["10S"]);
	$M_11S						= trim($arr_rs[$j]["11S"]);
	$M_12S						= trim($arr_rs[$j]["12S"]);
	$M_13S						= trim($arr_rs[$j]["13S"]);
	
	$use_tot = $M_1M + $M_2M + $M_3M + $M_4M + $M_5M + $M_6M + $M_7M + $M_8M + $M_9M + $M_10M + $M_11M + $M_12M + $M_13M;  
	$use_sd_tot = $M_1S + $M_2S + $M_3S + $M_4S + $M_5S + $M_6S + $M_7S + $M_8S + $M_9S + $M_10S + $M_11S + $M_12S + $M_13S; 


	$arr_rs_typeA = listMainVacation($conn, "1", $con_year, $_SESSION['s_adm_no']);
	$arr_rs_typeB = listMainVacation($conn, "5", $con_year, $_SESSION['s_adm_no']);
	$arr_rs_typeC = listMainVacation($conn, "6", $con_year, $_SESSION['s_adm_no']);

	$arr_rs_bbs = listBoardMain($conn, "B_1_1", "", "Y", "N", 30);
	
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
				<li class="navi-item current" title="선택 됨"><a class="navi-name" href="#">메인</a></li>
				<li class="navi-item"><a class="navi-name" href="#">공지사항</a></li>
				<li class="navi-item"><a class="navi-name" href="#">근무현황</a></li>
				<li class="navi-item"><a class="navi-name" href="#">임직원 조회</a></li>
				<li class="navi-item"><a class="navi-name" href="#">결재관리</a></li>
			</ul>
		</div>
		<!-- //page-navi -->
		<hr />
		<!-- page-body -->
		<div class="page-body page-default">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-title" id="localTitle"><span class="local-name">메인</span></h2>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-body -->
						<div class="content-body">
							outline

							page h1
								local h2
									content h3
										section h4


							<!-- section -->
							<div class="section">
								<div class="section-wrap">
									<div class="section-head">
										<h4 class="section-subject"><span class="section-name">섹션 제목이 들어갑니다.</span></h4>
									</div>
									<div class="section-body">
									</div>
								</div>
							</div>
							<!-- //section -->

							<div class="info-board module-a style-a type-c attr-done">
								<div class="board-wrap">
									<div class="board-head">
										<p class="board-subject">스마트데이</p>
										<p class="board-summary">등록이 완료되었습니다!</p>
										<p><a href="/m/login/logout.php">로그아웃</a></p>
									</div>
								</div>
							</div>
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