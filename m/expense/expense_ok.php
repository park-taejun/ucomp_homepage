<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
#====================================================================
# File Name    : expense_ok.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-19
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

#====================================================================
# DML Process
#====================================================================

	#echo $adm_no;

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

	function js_mail(adm, adm_no) {

		var frm = document.frm;

		bDelOK = confirm(' 승인요청건이 있다는 메시지만 전송됩니다!\n 승인 요청 메일을 보내시겠습니까?');
		
		if (bDelOK==true) {

			mode = "email";
			email = adm;
			leader_no = adm_no;

			var request = $.ajax({
				url:"/manager/expense/expense_mail.php",
				type:"POST",
				data:{mode:mode
						 ,email:email
						 ,leader_no:leader_no 
						 },
				success: function(data){
					alert("메일 발송 완료!");
				}
			});
		}
	}

</script>
</head>
<body>
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
						<div class="content-body">
							<!-- info-board -->
							<div class="info-board module-a style-a type-c attr-done">
								<div class="board-wrap">
									<div class="board-head">
										<p class="board-subject">지출결의</p>
										<p class="board-summary">등록이 완료되었습니다!</p>
									</div>
								</div>
							</div>
							<!-- //info-board -->
							<!-- noti-board -->
							<div class="noti-board module-a style-b type-fill accent-01 x-small attr-caution">
								<div class="board-wrap">
									<div class="board-head"><p class="board-subject"><span class="board-name">승인 이후 수정이 불가하오니 내용 확인 후 등록 바랍니다.</span></p></div>
								</div>
							</div>
							<!-- //noti-board -->
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
									<!-- data-display -->
									<div class="data-display module-a style-a type-a small">
										<ul class="data-list">
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">일자</span></div>
													<div class="data-body"><?=$rs_exd_date?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">지출타입</span></div>
													<div class="data-body"><?= $rs_exd_type?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">상세내역</span></div>
													<div class="data-body"><?=$rs_exd_content?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">프로젝트</span></div>
													<div class="data-body"><?=$rs_exd_project?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">금액</span></div>
													<div class="data-body"><?=$rs_exd_price?></div>
												</div>
											</li>
										</ul>
									</div>
									<!-- //data-display -->
								</div>
							</div>
							<?
								}
							}
							?>
							<!-- //section -->

							<!-- section -->
							<div class="section module-a style-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name">영수증 사진 첨부</span></h4></div>
								<div class="section-body">
									<!-- post-display -->
									<div class="post-display module-c style-a type-c attr-scroll">
										
<div class="post-list">
<? if (sizeof($arr_rs_file) > 0) { 
			for ($i=0 ; $i < sizeof($arr_rs_file); $i++){
				$rs_file_nm			= trim($arr_rs_file[$i]["FILE_NM"]);
?>
	<li class="post-item">
		<div class="post-wrap">
			<div class="post-figure"><a href="#imageEnlarge" class="post-thumbnail" title="확대보기" onclick="imageEnlarge.active('imageEnlarge');$('#image_popup').attr('src', '/upload_data/expense/<?=$rs_file_nm?>');"><img src="/upload_data/expense/<?=$rs_file_nm?>" alt="" /></a></div>
		</div>
	</li>
<?
			} 
	 } 
?>	
</div>

									</div>
									<!-- //post-display -->
								</div>
							</div>
							<!-- //section -->
						</div>
						<!-- //content-body -->
						<!-- content-side -->
						<div class="content-side">
							<!-- info-board -->
							<div class="info-board module-b style-a type-c">
								<div class="board-wrap">
									<div class="board-head">
										<p class="board-summary"><strong class="em"><?=selectAdminName($conn, $rs_va_state_pos)?>[<?=selectAdminPosition($conn, $rs_va_state_pos, $year)?>]님</strong>에게 승인요청 메일을 보내세요!</p>
									</div>
									<div class="board-util">
										<!-- button-display -->
										<div class="button-display module-a style-a type-d">
											<span class="button-area">
												<a href="javascript:js_mail('<?=selectAdminEmail2($conn, $rs_va_state_pos)?>', <?=$rs_va_state_pos?>);" class="btn module-c style-b type-line accent-02 large flex"><span class="btn-text">승인요청 메일발송</span></a>
											</area>
										</div>
										<!-- //button-display -->
									</div>
								</div>
							</div>
							<!-- //info-board -->
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
							<a href="expense_list.php" class="btn module-b style-a type-fill accent-01 x-large flex"><span class="btn-text">확인</span></a>
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
				<div class="popup-content" >
					<img src="./../../assets/images/@temp/img_receipt_01_enlarge.png" alt="" id="image_popup" />
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