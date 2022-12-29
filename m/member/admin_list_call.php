<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : admin_list.php
# Modlue       : 
# Writer       : JeGal Jeong 
# Create Date  : 2022-04-28
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#$sPageRight_F		= "Y";

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

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$con_use_tf					= $_POST['con_use_tf']!=''?$_POST['con_use_tf']:$_GET['con_use_tf'];
	$adm_no							= $_POST['adm_no']!=''?$_POST['adm_no']:$_GET['adm_no'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	//$con_group_no							= $_POST['con_group_no']!=''?$_POST['con_group_no']:$_GET['con_group_no'];

	if ($con_use_tf == "") $con_use_tf = "Y";

	if ($mode == "T") {
		updateAdminUseTF($conn, $use_tf, $s_adm_no, (int)$adm_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "모바일 관리자 사용여부 변경 (관리자번호 : ".(int)$adm_no.")", "Update");

	}

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= SetStringToDB($use_tf);

	$del_tf = "N";
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

	//$nListCnt =totalCntAdmin($conn, $con_group_no, $con_com_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str);
	$nListCnt =totalCntAdminTest($conn, $con_group_no, $con_headquarters_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str);


	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	if (($s_adm_no <> 1) and ($s_adm_no <> 4)) {
		$con_use_tf = "Y";
	} else {
		$con_use_tf = $con_use_tf;
	}
	#$del_tf = "Y";

	//$arr_rs = listAdmin($conn, $con_group_no, $con_com_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);
	$arr_rs = listAdminTest($conn, $con_group_no, $con_headquarters_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "모바일 관리자 관리 리스트 조회", "List");

	#echo sizeof($arr_rs);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/m_common_script.php";
?>
	<style>
	a {text-decoration:none;color: inherit;}

	</style>
	<link rel="stylesheet" type="text/css" href="/m/assets/css/layout.front.css">

	<!--
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	-->
	<script type="text/javascript">

	$(document).ready(function(){

 		var clipboard = new Clipboard('.clipboard');//로드 시 한번 선언 
		

/*
		const localBody = document.querySelector('.local-body');

		var limit = 7;
		var start = 0;
		var action = "inactive";
		function load_country_data(limit, start){
			$.ajax({
				url:"fetch.php",
				method: "POST",
				data:{limit:limit, start:start},
				cache:false,
				success:function(data){
					$('#load_data').append(data);
					if(data == ''){
						$('#load_data_message').html("<button type='button' class='btn btn-info'>No Data Found</button>");
						action = 'active';
					}else{
						$('#load_data_message').html("<button type='button' class='btn btn-warning'>Please Wait...</button>");
						action = 'inactive';
					}
				}
			});
		}

		if (action == 'inactive'){
			action = 'active';
			load_country_data(limit, start);
		}

		localBody.addEventListener('scroll', function() {
			var localBodyTop		= $('.local-body').scrollTop();
			var localBodyHeight	= $('.local-body').height();
			var winHeight				= $(window).height();
			alert(" localBodyTop : "+ localBodyTop+"\n localBodyHeight : "+ localBodyHeight+"\n winHeight : " + winHeight +"\n action : "+action
				);
			if(localBodyTop + localBodyHeight	> winHeight && action == 'inactive') {
				//alert(limit);
				var limit = 7;
				var start = 0;
				var action = "inactive";
				function load_country_data(limit, start){
					$.ajax({
						url:"fetch.php",
						method: "POST",
						data:{limit:limit, start:start},
						cache:false,
						success:function(data){
							$('#load_data').append(data);
							if(data == ''){
								$('#load_data_message').html("<button type='button' class='btn btn-info'>No Data Found</button>");
								action = 'active';
							}else{
								$('#load_data_message').html("<button type='button' class='btn btn-warning'>Please Wait...</button>");
								action = 'inactive';
							}
						}
					});
				}

				if (action == 'inactive'){
					action = 'active';
					load_country_data(limit, start);
				}

			}

		});
    var docHeight = $(document).height();
    var winHeight = $(window).height();
    $(window).scroll(function() {
       if($(window).scrollTop() + winHeight >= docHeight) {
           alert('Here is Bottom of This Page');
       }
    });
*/
	});

	$(window).scroll(function() {
		alert('aa');
	});

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
				<li class="navi-item current" title="선택 됨"><span class="navi-name">임직원 조회</span></li>
				<li class="navi-item"><a class="navi-name" href="/m/approval/vacation_approval_list.php">결재관리</a></li>
			</ul>
		</div>
		<!-- //page-navi -->
		<hr />
		<!-- page-body -->
		<div class="page-body page-intro page-member">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-title" id="localTitle"><span class="local-name">임직원 조회</span></h2>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-head -->
						<div class="content-head">
							<h3 class="content-title"><span class="content-name">임직원 조회</span></h3>
							<!-- data-finder -->
							<form class="data-finder" id="searchBar" name="frm" method="post">
							<input type="hidden" name="adm_no" value="">
							<input type="hidden" name="use_tf" value="">
							<input type="hidden" name="con_use_tf" value="<?=$con_use_tf?>">
							<input type="hidden" name="menu_cd" value="<?//=$menu_cd?>" >
							<input type="hidden" name="mode" value="">
								<!-- submit-form -->
								<fieldset class="submit-form module-a">
									<legend>구성원 검색 서식</legend>
									<div class="form-list">
										<div class="form-item">
											<div class="form-head"><label class="form-name" for="searchScope">검색어</label></div>
											<div class="form-body">
												<div class="form-area">
													<span class="form select module-b style-b type-line normal-04 medium" style="width: 128rem;">
														<select class="form-elem" id="searchScope" name="search_field" onChange="search_opt_change(this.value)">
															<option value="ADM_NAME" <?if ($search_field == 'ADM_NAME'){?> selected <?}?>>이름</option>
															<option value="HEADQUARTERS_2022" <?if ($search_field == 'HEADQUARTERS_2022'){?> selected <?}?>>본부</option>
															<option value="DEPT_2022" <?if ($search_field == 'DEPT_2022'){?> selected <?}?>>부서</option>
														</select>
													</span>
													<?
														if (($search_field == "") || ($search_field == "ADM_NAME")) {
															$name_display = "block";
															$opt1_display = "none";
															$opt2_display = "none";
														} else if ($search_field == "HEADQUARTERS_2022") {
															$name_display = "none";
															$opt1_display = "block";
															$opt2_display = "none";
															
														} else if ($search_field == "DEPT_2022") {
															$name_display = "none";
															$opt1_display = "none";
															$opt2_display = "block";
														}
													?>
													<span class="form textfield module-b style-b type-line normal-04 medium flex" id="search_txt" style="display:<?=$name_display?>">
														<input type="search" value="<?=$search_str?>" name="search_str" class="form-elem" id="searchKeyword" placeholder="이름을 입력해 주세요." onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
														<span class="form-func">
															<button type="button" class="btn" id="btn_search" onClick="search_opt_search($('#searchKeyword').val())"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>검색</title><path d="M19.63,17.87l-2-2a7.5,7.5,0,1,0-1.76,1.76l2,2a1.24,1.24,0,0,0,1.76-1.76ZM11.5,16.5a5,5,0,1,1,5-5A5,5,0,0,1,11.5,16.5Z"></path></svg></button>
														</span>
													</span>
													<span class="form select module-b style-b type-line normal-04 medium" style="width: 200rem;display:<?=$opt1_display?>" id="search_opt1">
														<?= makeSelectBoxMobile($conn,"HEADQUARTERS_2022","con_headquarters_code","200","전체","",$con_headquarters_code)?>
													</span>
													<span class="form select module-b style-b type-line normal-04 medium" style="width: 200rem;display:<?=$opt2_display?>" id="search_opt2">
														<?= makeSelectBoxMobile($conn,"DEPT_2022","con_dept_code","200","전체","",$con_dept_code)?>
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
							<!-- goods-display -->
							<div class="goods-display module-a style-a type-a">
								<!-- goods-info -->
								<div class="goods-info">
									<div class="info-list">
										<div class="info-item">
											<!-- data-list -->
											<p class="data-list">
												<span class="data-item">
													<span class="head">근무상태</span>
												</span>
											</p>
											<!-- //data-list -->
										</div>
										<div class="info-item">
											<div class="lamp-info">
												<div class="info-wrap">
													<div class="info-head"><span class="info-name">근무형태 표시</span></div>
													<div class="info-body">
														<ul class="data-list">
															<li class="data-item">정상근무</li>
															<li class="data-item">스마트데이</li>
															<li class="data-item">휴가</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- //goods-info -->
								
<div class="goods-list">

<?
	$nCnt = 0;
	
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs) ; $j++) {
			
			$ADM_ID						= trim($arr_rs[$j]["ADM_ID"]);
			$ADM_NO						= trim($arr_rs[$j]["ADM_NO"]);
			$ADM_NAME					= SetStringFromDB($arr_rs[$j]["ADM_NAME"]);
			$ADM_HPHONE				= trim($arr_rs[$j]["ADM_HPHONE"]);
			$ADM_EMAIL				= trim($arr_rs[$j]["ADM_EMAIL"]);
			$GROUP_NO					= trim($arr_rs[$j]["GROUP_NO"]);
			$COM_CODE					= trim($arr_rs[$j]["COM_CODE"]);
			$HEADQUARTERS_CODE= trim($arr_rs[$j]["HEADQUARTERS_CODE"]);
			$DEPT_CODE				= trim($arr_rs[$j]["DEPT_CODE"]);
			$POSITION_CODE		= trim($arr_rs[$j]["POSITION_CODE"]);
			$LEADER_TITLE			= trim($arr_rs[$j]["LEADER_TITLE"]);
			$DEPT_NAME				= trim($arr_rs[$j]["DEPT_NAME"]);
			$POSITION_NAME		= trim($arr_rs[$j]["POSITION_NAME"]); //관리자그룹이름
			$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
			$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
			$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
			$ENTER_DATE				= trim($arr_rs[$j]["ENTER_DATE"]);

			$ADM_PROFILE			= trim($arr_rs[$j]["PROFILE"]); //사진 추가
			if ($ADM_PROFILE) { 
					$IMG_SRC="/upload_data/profile/".$ADM_PROFILE;
			} else {
					$IMG_SRC="/upload_data/profile/sys1.png";	
			}

			$GROUP_NAME			= getGroupName($conn, $GROUP_NO); 
			//$DEPT_NAME			= getDcodeName($conn, "DEPT", $DEPT_CODE); 
			//$POSITION_NAME	= getDcodeName($conn, "POSITION", $POSITION_CODE); 
			$CP_NM					= getCompanyName($conn, $COM_CODE); 

			$VA_TYPE		= selectAdminVacation($conn, $ADM_NO); //사용자 연차 및 스마트데이 확인용
			$VA_NAME		= getDcodeName($conn, "VA_TYPE", $VA_TYPE);

			switch ($VA_NAME) {																						////////////////////// ① 확인
				case "스마트데이" : $va_display = "type-fill accent-03";
					break;
				case "연차" : $va_display = "type-line normal-04";
					break;
				case "오전반차" : $va_display = "type-line normal-04";
					break;
				case "하계,동계휴가" : $va_display = "type-line normal-04";
					break;
				case "미사용연차" : $va_display = "type-line normal-04";
					break;
				case "미사용반차" : $va_display = "type-line normal-04";
					break;
				case "오후반차" : $va_display = "type-line normal-04";
					break;
				default : $va_display = "type-line normal-10";
			}

			$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

			$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;
?>
	<div class="goods-item">
		<div class="goods-wrap" style="--default-picture: url(<?=$IMG_SRC?>);">
			<div class="goods-inform">
				<div class="goods-head">
					<p class="goods-title">
						<span class="goods-name"><?=$ADM_NAME?></span>
						<span class="goods-position"><?=$POSITION_NAME?></span>
						<? if ($HEADQUARTERS_CODE <> "") { ?>
							<span class="goods-department"><?=$HEADQUARTERS_CODE?></span>
						<? } ?>
						<? if ($DEPT_CODE <> "") { ?>
							<span class="goods-team"><?=$DEPT_CODE?></span>
						<? } ?>
					</p>
				</div>
				<div class="goods-type">
					<p class="data-list">
						<span class="data-item"><span class="lamp module-a style-c <?=$va_display?> small"><span class="lamp-text"><?=$VA_NAME?></span></span></span>
					</p>
				</div>
				<div class="goods-data">
					<ul class="data-list">
						<li class="data-item mobilephone">
							<div class="head">휴대전화</div>
							<div class="body">
								<div class="text" tabindex="0"><?=$ADM_HPHONE?></div>
								<!--<input type="hidden" id="adm_hp_<?=$j?>" value="<?=$ADM_HPHONE?>">-->
								<!-- tooltip -->
								<div class="tooltip module-a style-a type-b" style="display:block">
									<div class="tooltip-wrap">
										<div class="tooltip-body">
											<ul class="data-list">
												<li class="data-item"><a href="tel:<?=$ADM_HPHONE?>">전화걸기</a></li>
												<li class="data-item"><a href="javascript:void(0);" class="clipboard" data-clipboard-text="<?=$ADM_HPHONE?>">복사하기</a></li>
											</ul>
										</div>
									</div>
								</div>
								<!-- //tooltip -->
							</div>
						</li>
						<li class="data-item email">
							<span class="head">이메일</span>
							<span class="body"><a class="text" href="mailto:<?=$ADM_EMAIL?>"><?=$ADM_EMAIL?></a></span>
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
							<!-- //goods-display -->
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
<script>
	function search_opt_change(txt){

		var frm = document.frm;

		if (txt == "" || txt == "ADM_NAME"){
			frm.con_headquarters_code.value = "";
			frm.con_dept_code.value = "";
			$('#search_txt').css('display','block');
			$('#search_opt1').css('display','none');
			$('#search_opt2').css('display','none');
		} else if (txt == 'HEADQUARTERS_2022') {
			frm.search_str.value = "";
			frm.con_dept_code.value = "";
			$('#search_txt').css('display','none');
			$('#search_opt1').css('display','block');
			$('#search_opt2').css('display','none');
		} else if (txt == 'DEPT_2022') {
			frm.search_str.value = "";
			frm.con_headquarters_code.value = "";
			$('#search_txt').css('display','none');
			$('#search_opt1').css('display','none');
			$('#search_opt2').css('display','block');
		}
		frm.method = "POST";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function search_opt_search(txt){
		var frm = document.frm;
		//frm.nPage.value = "1";
		//frm.search_str.value= txt;
		var idx = frm.search_field.selectedIndex;

		if (idx == 0) {
			frm.con_headquarters_code.value = "";
			frm.con_dept_code.value = "";
		} else if (idx == 1){
			frm.search_str.value = "";
			frm.con_dept_code.value = "";
		} else if (idx ==2){
			frm.search_str.value = "";
			frm.con_headquarters_code.value = "";
		}
		frm.method = "POST";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

</script>
