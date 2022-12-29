<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : vacation.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-02
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
	require "../../_classes/biz/holiday/holiday.php";
	require "../../_classes/biz/admin/admin.php";

	$year										= $_POST['year']!=''?$_POST['year']:$_GET['year'];
	$month									= $_POST['month']!=''?$_POST['month']:$_GET['month'];
	$con_headquarters_code	= $_POST['con_headquarters_code']!=''?$_POST['con_headquarters_code']:$_GET['con_headquarters_code'];
	$con_dept_code					= $_POST['con_dept_code']!=''?$_POST['con_dept_code']:$_GET['con_dept_code'];

	$selectedDate						= $_POST['selectedDate']!=''?$_POST['selectedDate']:$_GET['selectedDate'];

	function Get_TotalDays($year,$month) { 
		$day = 1; 
		while(checkdate($month,$day,$year)) { 
			$day ++ ; 
		} 
		$day--; 
		return $day; 
	}

	if(!$year) {
		$year = date("Y",time());
	} 

	if(!$month) {
		$month = date("m",time());
	}

	$sDate = $year."-".$month."-01";

	# Get_TotalDays 함수로 이번달의 총 일자를 구한다
	# 몇일 까지 있는지를 검사한후 쿼리해서 결과 값을 가져온다

	$total_days = Get_TotalDays($year,$month) ;
	$this_date = date("Y-m-d",strtotime("0 day"));

	if ($selectedDate == ""){
		$selectedDate = $this_date;
	}

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "연차 관리 조회", "List");

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
				<li class="navi-item"><a class="navi-name" href="#">메인</a></li>
				<li class="navi-item"><a class="navi-name" href="#">공지사항</a></li>
				<li class="navi-item current" title="선택 됨"><a class="navi-name" href="#">근무현황</a></li>
				<li class="navi-item"><a class="navi-name" href="#">임직원 조회</a></li>
				<li class="navi-item"><a class="navi-name" href="#">결재관리</a></li>
			</ul>
		</div>
		<!-- //page-navi -->
		<hr />
		<!-- page-body -->
		<div class="page-body page-intro page-holiday">
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
							<h3 class="content-title"><span class="content-name">임직원 근무 현황</span></h3>
							<!-- data-finder --> 
							<form class="data-finder">
								<!-- submit-form -->
								<fieldset class="submit-form module-a">
									<legend>구성원 검색 서식</legend>
									<div class="form-list">
										<div class="form-item">
											<div class="form-head"><label class="form-name" for="searchScope">검색어</label></div>
											<div class="form-body">
												<div class="form-area">
													<span class="form select module-b style-b type-line normal-04 medium" style="width: 128rem;">
														<select class="form-elem" id="searchCategory" title="분류 선택">
															<option>전체</option>
															<option>경영기획팀</option>
															<option>기업부설연구소</option>
															<option>플랫폼사업본부</option>
															<option>서비스 운영 본부</option>
															<option>디지털 구축 본부</option>
														</select>
													</span>
													<span class="form textfield module-b style-b type-line normal-04 medium flex">
														<input type="search" class="form-elem" id="searchKeyword" placeholder="이름을 입력해 주세요." title="이름 입력" onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
														<span class="form-func">
															<button type="button" class="btn"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>검색</title><path d="M19.63,17.87l-2-2a7.5,7.5,0,1,0-1.76,1.76l2,2a1.24,1.24,0,0,0,1.76-1.76ZM11.5,16.5a5,5,0,1,1,5-5A5,5,0,0,1,11.5,16.5Z"></path></svg></button></span>
														</span>
													</span>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								<!-- //submit-form -->
							</form>
							<!-- //data-finder -->
						</div>
						<!-- //content-head -->
						<!-- content-body -->
						<div class="content-body">
							<!-- datepicker -->
							<div class="datepicker-inline style-b" id="datepickerInline" data-bui-expand="workingStatus"></div>
							<form name="frm_date" method="get">
							<input type="hidden" name="selectedDate" value="<?=$selectedDate?>">
							</form>
							<!-- //datepicker -->
							<!-- section -->
							<?
								$holiday = getHolidayDate($conn, $selectedDate);
								if (strpos($holiday, "[휴일]")){
									$holiday = str_replace("[휴일]", "", $holiday);
							?>
							<!-- info-board -->
							<div class="info-board module-c type-c attr-nodata">
								<div class="board-wrap">
									<div class="board-head">
										<p class="board-summary"><strong class="em accent-01">[휴일]</strong> <?=$holiday?></p>
									</div>
								</div>
							</div>
							<!-- //nodata -->
							<?
								} else {
							?>
							<div class="section module-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name">스마트데이</span></h4></div>
								<div class="section-body">
									<!-- data-display -->
									<div class="data-display module-b style-a type-a normal-03 small">
										<!-- data-list -->
										<ul class="data-list">
										<?
											$arr_rs = selectVacationMobile($conn, $selectedDate, "5");  //스마트데이
											for($i = 0 ; $i < sizeof($arr_rs); $i++) {
												$ADM_NAME = selectAdminName($conn, $arr_rs[$i]["VA_USER"]);
												$VA_STATE = selectVaState($conn, $arr_rs[$i]["VA_STATE"]); //승인상태
												$VA_MEMO	= $arr_rs[$i]["VA_MEMO"];
										?>

											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><a href="#schedule" class="data-name symbol-rtl-fill-circle-more" onclick="contentsPopup.active('schedule');"><?=$ADM_NAME?></a></div>
													<div class="data-body"><?=$VA_MEMO?></div>
												</div>
											</li>
										<?
											}
										?>
										</ul>
										<!-- //data-list -->
									</div>
									<!-- //data-display -->
								</div>
							</div>
							<!-- //section -->
							<!-- section -->
							<div class="section module-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name">휴가</span></h4></div>
								<div class="section-body">
									<!-- data-display -->
									<div class="data-display module-b style-a type-a normal-03 small">
										<!-- data-list -->
										<ul class="data-list">
										<?
											$arr_rs = selectVacationMobile($conn, $selectedDate, "1");  //스마트데이 외
											for($i = 0 ; $i < sizeof($arr_rs); $i++) {
												$ADM_NAME = selectAdminName($conn, $arr_rs[$i]["VA_USER"]);
												$VA_STATE = selectVaState($conn, $arr_rs[$i]["VA_STATE"]); //승인상태
												$VA_TYPE  = selectVaType($conn, $arr_rs[$i]["VA_TYPE"]); //휴가형태
												$VA_MEMO	= $arr_rs[$i]["VA_MEMO"];
										?>

											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><a href="#schedule" class="data-name symbol-rtl-fill-circle-more" onclick="contentsPopup.active('schedule');"><?=$ADM_NAME?></a></div>
													<div class="data-body"><?=$VA_TYPE?></div>
												</div>
											</li>
										<?
											}
							} //휴일 체크
										?>
										</ul>
										<!-- //data-list -->
									</div>
									<!-- //data-display -->
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
					<img src="/m/assets/images/@temp/img_receipt_01_enlarge.png" alt="" />
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



	document.addEventListener('DOMContentLoaded', function() {
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

		const datepicker = new AirDatepicker('#datepickerInline', {
			inline: true,
			toggleSelected: false,
			multipleDates: false,
			firstDay: 0,
			todayButton: new Date(),
			locale: {
				days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
				daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				daysMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
				monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				today: 'Today',
				clear: 'Clear',
				timepicker: true,
				dateFormat: 'MM/dd/yyyy',
				timeFormat: 'hh:mm aa',
				firstDay: 0,
			},
			navTitles: {
				days: 'yyyy.MM'
			},
			prevHtml: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false"><title>전월</title><path d="M15.52,6.37a1.24,1.24,0,0,1,0,1.76L11.65,12l3.87,3.87a1.25,1.25,0,1,1-1.77,1.76L8.12,12l5.63-5.63A1.25,1.25,0,0,1,15.52,6.37Z"></path></svg>',
			nextHtml: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false"><title>차월</title><path d="M8.48,17.63a1.24,1.24,0,0,1,0-1.76L12.35,12,8.48,8.13a1.25,1.25,0,1,1,1.77-1.76L15.88,12l-5.63,5.63A1.25,1.25,0,0,1,8.48,17.63Z"></path></svg>',
			minDate: weekStartDate, // 금주 시작
			maxDate: weekEndDate, // 금주 마지막
			selectedDates: ['<?=$selectedDate?>'], // 오늘 날짜
			onSelect: function(date, formattedDate, datepicker) {
				//console.log(date.datepicker.focusDate); // 선택한 날짜
				sel = new Date(date.datepicker.focusDate);
				m = sel.getMonth() + 1;
				d = sel.getDate();
				if(m < 10) { m = "0" + m ;}
				if(d < 10) { d = "0" + d ;}
				dd= sel.getFullYear()+"-"+m+"-"+d;
				/*
				$.ajax({
					type: 'GET',
					url: 'ajax_vacation.php',
					data: dd,
					error: function(error) {
						alert("Error!");
					},
					success: function(result){
						var e = 
					}
				});
				*/
				var frm = document.frm_date;
				frm.selectedDate.value = dd;
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
				
			},
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
	});
</script>
</body>
</html>
