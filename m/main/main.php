<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : main.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-30
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
	require "../../_classes/biz/approval/approval.php";
	require "../../_classes/biz/board/board.php";
	
	$year		  = "202206";
	// 내 남은 연차 조회
	$con_year = date("Y",time());

	$arr_rs = listUserVacationYear($conn, $con_year, "", "", $_SESSION['s_adm_no']);
	
	$j = 0;

	$VA_CNT							= trim($arr_rs[$j]["VA_CNT"]);
	
	$M_1M							= trim($arr_rs[$j]["1M"]);
	$M_2M							= trim($arr_rs[$j]["2M"]);
	$M_3M							= trim($arr_rs[$j]["3M"]);
	$M_4M							= trim($arr_rs[$j]["4M"]);
	$M_5M							= trim($arr_rs[$j]["5M"]);
	$M_6M							= trim($arr_rs[$j]["6M"]);
	$M_7M							= trim($arr_rs[$j]["7M"]);
	$M_8M							= trim($arr_rs[$j]["8M"]);
	$M_9M							= trim($arr_rs[$j]["9M"]);
	$M_10M							= trim($arr_rs[$j]["10M"]);
	$M_11M							= trim($arr_rs[$j]["11M"]);
	$M_12M							= trim($arr_rs[$j]["12M"]);
	$M_13M							= trim($arr_rs[$j]["13M"]);

	$M_1S							= trim($arr_rs[$j]["1S"]);
	$M_2S							= trim($arr_rs[$j]["2S"]);
	$M_3S							= trim($arr_rs[$j]["3S"]);
	$M_4S							= trim($arr_rs[$j]["4S"]);
	$M_5S							= trim($arr_rs[$j]["5S"]);
	$M_6S							= trim($arr_rs[$j]["6S"]);
	$M_7S							= trim($arr_rs[$j]["7S"]);
	$M_8S							= trim($arr_rs[$j]["8S"]);
	$M_9S							= trim($arr_rs[$j]["9S"]);
	$M_10S							= trim($arr_rs[$j]["10S"]);
	$M_11S							= trim($arr_rs[$j]["11S"]);
	$M_12S							= trim($arr_rs[$j]["12S"]);
	$M_13S							= trim($arr_rs[$j]["13S"]);
	
	$use_tot = $M_1M + $M_2M + $M_3M + $M_4M + $M_5M + $M_6M + $M_7M + $M_8M + $M_9M + $M_10M + $M_11M + $M_12M + $M_13M;  
	$use_sd_tot = $M_1S + $M_2S + $M_3S + $M_4S + $M_5S + $M_6S + $M_7S + $M_8S + $M_9S + $M_10S + $M_11S + $M_12S + $M_13S; 


	$arr_rs_typeA = listMainVacation($conn, "1", $con_year, $_SESSION['s_adm_no']);
	$arr_rs_typeB = listMainVacation($conn, "5", $con_year, $_SESSION['s_adm_no']);
	$arr_rs_typeC = listMainVacation($conn, "6", $con_year, $_SESSION['s_adm_no']);

	$arr_rs_bbs = listBoardMain($conn, "B_1_1", "", "Y", "N", 10);
	
?>

<!DOCTYPE html>
<html lang="ko">
<head>
	<title><?=$g_title_name?></title>
	<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/m_common_main_script.php";
?>
	<link rel="stylesheet" type="text/css" href="/m/assets/css/layout.front.css">
	<link rel="stylesheet" type="text/css" href="/m/assets/css/page.main.css">
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
				<li class="navi-item current" title="선택 됨"><span class="navi-name">메인</span></li>
				<li class="navi-item"><a class="navi-name" href="/m/notice/board_list.php">공지사항</a></li>
				<li class="navi-item"><a class="navi-name" href="/m/vacation/vacation.php">근무현황</a></li>
				<li class="navi-item"><a class="navi-name" href="/m/member/admin_list.php">임직원 조회</a></li>
				<li class="navi-item"><a class="navi-name" href="/m/approval/vacation_approval_list.php">결재관리</a></li>
			</ul>
		</div>
		<!-- //page-navi -->
		<hr />
		<!-- page-body -->
		<div class="page-body">
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
							<!-- info-board -->
							<div class="info-board main-info">
								<div class="board-wrap">
									<div class="board-head">
										<p class="board-summary">
											<span class="wbr">turn professional</span>
											<span class="wbr">level up!</span>
										</p>
									</div>
								</div>
							</div>
							<!-- //info-board -->
							<!-- section -->
							<?
								$ADM_NO					= $_SESSION['s_adm_no'];
								$arr_rs					= selectAdmin2022($conn, $ADM_NO);
								$ADM_NAME				= $arr_rs[0]["ADM_NAME"];
								$ADM_HPHONE				= trim($arr_rs[0]["ADM_HPHONE"]);
								$ADM_EMAIL				= trim($arr_rs[0]["ADM_EMAIL"]);
								$GROUP_NO				= trim($arr_rs[0]["GROUP_NO"]);
								$COM_CODE				= trim($arr_rs[0]["COM_CODE"]);
								$HEADQUARTERS_CODE		= trim($arr_rs[0]["HEADQUARTERS_CODE"]);
								$DEPT_CODE				= trim($arr_rs[0]["DEPT_CODE"]);
								$POSITION_CODE			= trim($arr_rs[0]["POSITION_CODE"]);
								$LEADER_TITLE			= trim($arr_rs[0]["LEADER_TITLE"]);
								$LEADER_YN				= trim($arr_rs[0]["LEADER_YN"]);
								$DEPT_NAME				= trim($arr_rs[0]["DEPT_NAME"]);
								$POSITION_NAME			= trim($arr_rs[0]["POSITION_NAME"]); //관리자그룹이름
								$COMMUTE_TIME			= trim($arr_rs[0]["COMMUTE_TIME"]);
								$USE_TF					= trim($arr_rs[0]["USE_TF"]);
								$DEL_TF					= trim($arr_rs[0]["DEL_TF"]);
								$REG_DATE				= trim($arr_rs[0]["REG_DATE"]);
								$ENTER_DATE				= trim($arr_rs[0]["ENTER_DATE"]);
								$ADM_PROFILE			= trim($arr_rs[0]["PROFILE"]); //사진 추가
								if ($ADM_PROFILE) { 
										$IMG_SRC="/upload_data/profile/".$ADM_PROFILE;
								} else {
										$IMG_SRC="/upload_data/profile/sys1.png";	
								}

								$GROUP_NAME				= getGroupName($conn, $GROUP_NO); 
								$CP_NM					= getCompanyName($conn, $COM_CODE); 

								$VA_TYPE				= selectAdminVacation($conn, $ADM_NO); //사용자 연차 및 스마트데이 확인용
								$VA_NAME				= getDcodeName($conn, "VA_TYPE", $VA_TYPE);

								switch ($VA_NAME) {				////////////////////// ① 확인
									case "스마트데이" : $va_display = "type-fill accent-03";
										break;
									case "연차" : $va_display = "type-line normal-04";
										break;
									default : $va_display = "type-line normal-10";
								}

								$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

								$APPROVAL_VACATION_COUNT = selectApprovalVacationCountMobile($conn, $ADM_NO); //관리자 승인해야 할 연차결재수
								$APPROVAL_EXPENSE_COUNT	 = selectApprovalExpenseCountMobile($conn, $ADM_NO); //관리자 승인해야 할 지출결재수

								//결재 zero css Start ///////////////////////////////////////////////////////////////////여기 opacity~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
								$approval_css1		= "data-item dayoff-approve"; 
								if ($APPROVAL_VACATION_COUNT == 0) {
									$approval_css1 = "data-item expenses";
								}

								$approval_css2		= "data-item dayoff-approve"; 
								if ($APPROVAL_EXPENSE_COUNT == 0) {
									$approval_css2 = "data-item expenses";
								}

								$USER_APPROVAL_VACATION_COUNT	 = selectUserApprovalVacationCountMobile($conn, $ADM_NO); //사용자 승인되지 않은 연차수
								$USER_APPROVAL_EXPENSE_COUNT	 = selectUserApprovalExpenseCountMobile($conn, $ADM_NO); //사용자 승인되지 않은 지출결재수

								$approval_css3		= "data-item my-dayoff"; 
								if ($USER_APPROVAL_VACATION_COUNT == 0) {
									$approval_css3 = "data-item my-expenses";
								}

								$approval_css4		= "data-item my-dayoff"; 
								if ($USER_APPROVAL_EXPENSE_COUNT == 0) {
									$approval_css4 = "data-item my-expenses";
								}
								//결재 zero css End

							?>
							<div class="section my-info">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">내 정보</span></h3>
									</div>
									<div class="section-body">
										<!-- goods-display -->
										<div class="goods-display">
											<div class="goods-wrap" style="--default-picture: url(<?=$IMG_SRC?>);">
												<div class="goods-inform">
													<div class="goods-head">
														<p class="goods-title">
															<span class="goods-name"><?=$ADM_NAME?></span>
															<span class="goods-department"><?=$HEADQUARTERS_CODE?></span>
															<span class="goods-team"><?=$DEPT_CODE?> <?=$POSITION_CODE?></span>
														</p>
													</div>
													<div class="goods-data">
														<ul class="data-list">
															<li class="data-item working-hours">
																<span class="head">출퇴근시간</span>
																<span class="body"><?=selectCommuteTime($conn, $COMMUTE_TIME)?></span>
															</li>
															<li class="data-item day-off">
																<span class="head">내 남은 연차</span>
																<span class="body"><?=($VA_CNT - $use_tot)?>일</span>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<!-- //goods-display -->
									</div>
								</div>
							</div>
							<!-- //section -->
							<!-- section -->
							<? if ($LEADER_YN == "Y") { ?>
							<div class="section module-a type-a approve">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">결재하기</span></h3>
									</div>
									<div class="section-body">
										<!-- data-display -->
										<div class="data-display">
											<ul class="data-list">
												<li class="data-item dayoff-approve" onClick="location.href='/m/approval/vacation_approval_list.php'">
													<div class="data-wrap">
														<span class="data-head"><span class="data-name">연차 결재</span></span>
														<span class="data-body" id="approval_zero1"><?=$APPROVAL_VACATION_COUNT?></span>
													</div>
												</li>
												<li class="data-item expenses" onClick="location.href='/m/approval/approval_list.php'">
													<div class="data-wrap">
														<span class="data-head"><span class="data-name">지출 결재</span></span>
														<span class="data-body" id="approval_zero2"><?=$APPROVAL_EXPENSE_COUNT?></span>
													</div>
												</li>
											</ul>
										</div>
										<!-- //data-display -->
									</div>
								</div>
							</div>
							<? } ?>
							<!-- section -->
							<!-- //section -->
							<div class="section module-a type-a inquire">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">조회하기</span></h3>
									</div>
									<div class="section-body">
										<!-- data-display -->
										<div class="data-display">
											<ul class="data-list">
												<li class="data-item my-dayoff" onClick="location.href='/m/vacation/vacation_my.php'">
													<div class="data-wrap">
														<span class="data-head"><span class="data-name">나의 연차 결재</span></span>
														<span class="data-body" id="approval_zero3"><?=$USER_APPROVAL_VACATION_COUNT?></span>
													</div>
												</li>
												<li class="data-item my-expenses" onClick="location.href='/m/expense/expense_list.php'">
													<div class="data-wrap">
														<span class="data-head"><span class="data-name">나의 지출 결재</span></span>
														<span class="data-body" id="approval_zero4"><?=$USER_APPROVAL_EXPENSE_COUNT?></span>
													</div>
												</li>
											</ul>
										</div>
										<!-- //data-display -->
										<!-- button-display -->
										<div class="button-display">
											<span class="button-area working-status">
												<a class="btn module-b style-b type-fill normal-04 large symbol-rtl-fill-chevron-right" href="/m/vacation/vacation.php"><span class="btn-text">임직원 근무 현황</span></a>
											</span>
											<span class="button-area employees">
												<a class="btn module-b style-b type-fill normal-04 large symbol-rtl-fill-chevron-right" href="/m/member/admin_list.php"><span class="btn-text">임직원 조회</span></a>
											</span>
										</div>
										<!-- //button-display -->
									</div>
								</div>
							</div>
							<div class="section module-a type-a announce">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">공지사항</span></h3>
									</div>
									<div class="section-body">
										<div class="post-latest module-a style-a type-a">
											<div class="post-list">
											<? 
												if (sizeof($arr_rs_bbs) > 0) {
													for ($j = 0 ; $j < sizeof($arr_rs_bbs); $j++) {
														$B_NO			= SetStringFromDB($arr_rs_bbs[$j]["B_NO"]);
														$B_CODE			= SetStringFromDB($arr_rs_bbs[$j]["B_CODE"]);
														$TITLE			= SetStringFromDB($arr_rs_bbs[$j]["TITLE"]);
														$WRITER_NM		= trim($arr_rs_bbs[$j]["WRITER_NM"]);
														$REG_DATE		= trim($arr_rs_bbs[$j]["REG_DATE"]);

														$is_new = "";
														if ($REG_DATE >= date("Y-m-d H:i:s", (strtotime("0 day") - ($b_new_hour * 3600)))) {
															if ($MAIN_TF <> "N") {
																$is_new = "N";
															}
														}
														
														$is_notice = "공지"; //[필독], [전체공지] 등
														$idx_n = strpos($TITLE, "]");

														if ( $idx_n > 0) {
															$is_notice = substr($TITLE, 1, $idx_n-1);
															$TITLE = substr($TITLE, $idx_n+1);
														}

											?>

												<div class="post-item">
													<div class="post-wrap">
														<div class="post-inform">
															<div class="post-type">
																<p class="data-list">
																<? if ($is_new == "N") { ?>
																	<span class="data-item"><span class="mark module-b style-c type-fill accent-01 small"><span class="mark-text">N</span></span></span>
																<? } ?>
																	<span class="data-item"><span class="mark module-b style-c type-line normal-01 small"><span class="mark-text"><?=$is_notice?></span></span></span>
																</p>
															</div>
															<div class="post-head">
																<p class="post-title"><a class="post-name" href="/m/notice/board_list.php#n_<?=$B_NO?>" style="text-decoration:none"><?=$TITLE?></a></p>
															</div>
															<div class="post-data">
																<ul class="data-list">
																	<li class="data-item writer">
																		<span class="head">글쓴이</span>
																		<span class="body">
																			<span class="name"><?=$WRITER_NM?></span>
																		</span>
																	</li>
																	<li class="data-item posted">
																		<span class="head">등록일</span>
																		<span class="body">
																			<span class="date"><?=substr($REG_DATE, 0, 10)?></span>
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
										?>
<!--
												<div class="post-item">
													<div class="post-wrap">
														<div class="post-inform">
															<div class="post-type">
																<p class="data-list">
																	<span class="data-item"><span class="mark module-b style-c type-line normal-03 small"><span class="mark-text">공지</span></span></span>
																</p>
															</div>
															<div class="post-head">
																<p class="post-title"><span class="post-name">최대 두줄까지 노출되고, 내용이 넘칠 시 ...</span></p>
															</div>
															<div class="post-data">
																<ul class="data-list">
																	<li class="data-item writer">
																		<span class="head">글쓴이</span>
																		<span class="body">
																			<span class="name">유컴관리자</span>
																		</span>
																	</li>
																	<li class="data-item posted">
																		<span class="head">등록일</span>
																		<span class="body">
																			<span class="date">2022-03-24</span>
																		</span>
																	</li>
																</ul>
															</div>
														</div>
													</div>
												</div>
												<div class="post-item">
													<div class="post-wrap">
														<div class="post-inform">
															<div class="post-type">
																<p class="data-list">
																	<span class="data-item"><span class="mark module-b style-c type-fill accent-01 small"><span class="mark-text">N</span></span></span>
																	<span class="data-item"><span class="mark module-b style-c type-line normal-01 small"><span class="mark-text">필독 D-29</span></span></span>
																</p>
															</div>
															<div class="post-head">
																<p class="post-title"><span class="post-name">유컴패니온 공지사항 입니다.</span></p>
															</div>
															<div class="post-data">
																<ul class="data-list">
																	<li class="data-item writer">
																		<span class="head">글쓴이</span>
																		<span class="body">
																			<span class="name">유컴관리자</span>
																		</span>
																	</li>
																	<li class="data-item posted">
																		<span class="head">등록일</span>
																		<span class="body">
																			<span class="date">2022-03-24</span>
																		</span>
																	</li>
																</ul>
															</div>
														</div>
													</div>
												</div>
-->
											</div>
										</div>
									</div>
									<div class="section-side">
										<span class="button-area">
											<a class="btn more-data" href="/m/notice/board_list.php"><span class="btn-text">전체보기</span></a> 
										</span>
									</div>
								</div>
							</div>
							<!-- //section -->
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
			<!-- section -->
			<div class="section company-info">
				<div class="section-head"><h2 class="section-subject"><span class="section-name">기업정보</span></h2></div>
				<div class="section-body">
					<ul class="navi-list">
						<li class="navi-item"><a class="navi-name" href="/manager/main/main.php">PC버전</a></li>
						<li class="navi-item"><a class="navi-name" href="/m/login/logout.php">로그아웃</a></li>
					</ul>
				</div>
			</div>
			<!-- //section -->
			<p class="copyright">U:COMPANION. ALL RIGHT RESERVED.</p>
		</div>
		<!-- //page-foot -->
	</div>
	<!-- //page -->
</div>
</body>
</html>