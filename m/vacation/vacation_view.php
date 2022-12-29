<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : vacation_view.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-19
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
	$menu_right = "VA003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/vacation/vacation.php";
	require "../../_classes/biz/admin/admin.php";

	$seq_no		= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];

	$s_adm_id = $_SESSION['s_adm_id'];
	$s_adm_no = $_SESSION['s_adm_no'];
	$year		  = "202206";

	if ($con_year == "") {
		$con_year = date("Y",time());
	}

	if ($con_va_user == "") {
		$con_va_user = $s_adm_no;
	}

	$arr_rs = selectUserVacationMobile($conn, $seq_no);

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
		<!-- page-body -->
		<div class="page-body page-default">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-title" id="localTitle"><span class="local-name">결제관리</span></h2>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-head -->
						<div class="content-head">
							<h3 class="content-title"><span class="content-name">연차결재 조회</span></h3>
							<div class="content-navi">
								<a class="btn" href="javascript:history.back()"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>뒤로</title><path d="M12.7,5.7a1,1,0,0,0-1.4-1.4l-7,7a1,1,0,0,0,0,1.4l7,7a1,1,0,0,0,1.4-1.4L7.4,13H19a1,1,0,0,0,0-2H7.4Z"></path></svg></a>
							</div>
						</div>
						<!-- //content-head -->
						<!-- content-body -->
						<div class="content-body">
							<!-- post-display -->
							<div class="post-display module-b type-a">
								
<div class="post-list">
<?
	$nCnt = 0;

	if (sizeof($arr_rs) > 0) {
		
		for ($i = 0 ; $i < sizeof($arr_rs); $i++) {
			
			$SEQ_NO						= $arr_rs[$i]["SEQ_NO"]; 
			$VA_TYPE					= $arr_rs[$i]["VA_TYPE"]; 
			$VA_USER					= $arr_rs[$i]["VA_USER"]; 
			$ADM_NAME					= selectAdminName($conn, $VA_USER);
			$HEADQUARTERS_CODE= selectAdminHeadquarters($conn, $VA_USER, $y);
			$DEPT_CODE				= selectAdminDept($conn, $VA_USER, $y);
			$POSITION_CODE		= selectAdminPosition($conn, $VA_USER, $y);
			$VA_STATE					= $arr_rs[$i]["VA_STATE"]; //승인상태
			$VA_STATE_POS			= $arr_rs[$i]["VA_STATE_POS"]; //승인위치
			$VA_MEMO					= $arr_rs[$i]["VA_MEMO"];
			$VA_SDATE					= $arr_rs[$i]["VA_SDATE"];
			$VA_EDATE					= $arr_rs[$i]["VA_EDATE"];
			$REG_DATE					= $arr_rs[$i]["REG_DATE"];

			$VA_DATE					= $arr_rs[$i]["VA_DATE"];

			$VA_TYPE_STR			= selectVaType($conn, $VA_TYPE);
			$m_color					= selectVaStateMobileClass($conn, $VA_STATE);
			$m_str						= selectVaStateMobile($conn, $VA_STATE);

			if($VA_STATE == 1){
				$va_memo_str	= "신청이 완료되었습니다.";
			} else {
				$va_memo_str	= $VA_MEMO;
			}

?>
	<div class="post-item">
		<div class="post-wrap">
			<div class="post-inform">
				<div class="post-type">
					<p class="data-list">
						<span class="data-item"><span class="mark module-a style-b type-fill <?=$m_color?> medium"><span class="mark-text"><?=$m_str?></span></span></span>
						<? if ($VA_STATE_POS <> "") { ?>	
							<span class="data-item"><span class="mark module-a style-b type-line normal-03 medium"><span class="mark-text"><?=selectAdminName($conn, $VA_STATE_POS)?> 
							<?=selectAdminPosition($conn, $VA_STATE_POS, $year)?></span></span></span>
						<? } ?>
					</p>
				</div>
				<div class="post-head">
					<p class="post-title">
						<a class="post-name" href="#"><?=$VA_TYPE_STR?> - <?=$VA_DATE?></a>
					</p>
				</div>
				<div class="post-data">
					<ul class="data-list">
						<li class="data-item status">
							<span class="head">처리 상태</span>
							<span class="body">
								<span class="em normal-04"><?=$va_memo_str?></span>
								<p> </p>
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
			<div>데이터가 없습니다. </div>
<? 
	}
?>	
</div>

							</div>
							<!-- //post-display -->
							<?
								$arr_rs_date = selectUserVacationDateMobile($conn, $seq_no);
								if ($VA_TYPE_STR == "스마트데이") {
							?>
							<!-- section -->
							<div class="section module-a style-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name"><?=$VA_TYPE_STR?></span></h4></div>
								<div class="section-body">
									<!-- data-display -->
									<div class="data-display module-a style-a type-a small">
										<ul class="data-list">
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">일자</span></div>
													<div class="data-body"><?=$VA_SDATE?></div>
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
													<div class="data-body"><?=$VA_MEMO?></div>
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
													<div class="data-body"><?=$VA_MEMO?></div>
												</div>
											</li>
										</ul>
									</div>
									<!-- //data-display -->
								</div>
							</div>
							<!-- //section -->
							<?
								} else {
							?>
							<!-- section -->
							<div class="section module-a style-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name"><?=$VA_TYPE_STR?></span></h4></div>
								<div class="section-body">
									<!-- data-display -->
									<div class="data-display module-a style-a type-a small">
										<ul class="data-list">
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">일자</span></div>
													<div class="data-body"><?=$VA_SDATE?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">사유</span></div>
													<div class="data-body"><?=$VA_MEMO?></div>
												</div>
											</li>
										</ul>
									</div>
									<!-- //data-display -->
								</div>
							</div>
							<!-- //section -->
							<?
								}
							?>
						</div>
						<!-- //content-body -->
						<!-- content-side -->
						<div class="content-side">
							<!-- noti-board -->
							<div class="noti-board module-a type-none normal-02 small attr-information">
								<div class="board-wrap">
									<div class="board-head"><p class="board-subject"><span class="board-name">승인 이후 수정이 불가하오니 내용 확인 후 등록 바랍니다.</span></p></div>
									<div class="board-body">
										<p class="para em accent-01">당일 연차 등록이 안되며, 사전등록을 할 시 내용 수정이 불가하오니 작성 시 유의하시기 바랍니다.</p>
										<p class="para em accent-01">연차 등록 시 '신청' 상태 이며 경영 지원팀에서 '승인' 처리 되어야 정상 사용이 가능 합니다.</p>
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