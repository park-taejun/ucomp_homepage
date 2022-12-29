<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
#====================================================================
# File Name    : approval_content.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-25
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
	include "../../_common/common_header_mobile.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/expense/expense.php";

#====================================================================
	$savedir1 = $g_physical_path."upload_data/expense";
#====================================================================

	///* 각 계정 테스트용
	//$_SESSION['s_adm_id']="jeonbg";
	//$_SESSION['s_adm_no']="25";

	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];
	$year		 = "202206";
	//*/ 

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

	$mode			= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ex_no			= $_POST['ex_no']!=''?$_POST['ex_no']:$_GET['ex_no'];
	$exd_no			= $_POST['exd_no']!=''?$_POST['exd_no']:$_GET['exd_no'];
	$va_user		= $_POST['va_user']!=''?$_POST['va_user']:$_GET['va_user'];
	$ex_date		= $_POST['ex_date']!=''?$_POST['ex_date']:$_GET['ex_date'];

#====================================================================
# DML Process
#====================================================================

	#echo $adm_no;


	if ($mode == "S") { 
		
		$arr_rs = selectExpense($conn, $ex_no);
		

		if (sizeof($arr_rs) > 0) {
			$rs_ex_no				= trim($arr_rs[0]["EX_NO"]);
			$rs_ex_type				= trim($arr_rs[0]["EX_TYPE"]);
			$rs_ex_title			= trim($arr_rs[0]["EX_TITLE"]);
			$rs_ex_date				= trim($arr_rs[0]["EX_DATE"]);
			$rs_ex_total_price		= trim($arr_rs[0]["EX_TOTAL_PRICE"]);
			$rs_va_user				= trim($arr_rs[0]["VA_USER"]);
			$rs_headquarters_code	= trim($arr_rs[0]["HEADQUARTERS_CODE"]);
			$rs_dept_code			= trim($arr_rs[0]["DEPT_CODE"]);
			$rs_va_state			= trim($arr_rs[0]["VA_STATE"]);
			$rs_va_state_pos		= trim($arr_rs[0]["VA_STATE_POS"]);
			$rs_ex_log				= explode("//", $arr_rs[0]["EX_LOG"]);

			$rs_reg_date			= trim($arr_rs[0]["REG_DATE"]);
			$rs_ex_memo				= trim($arr_rs[0]["EX_MEMO"]);
			//$rs_va_flag			= trim($arr_rs[0]["VA_FLAG"]);
			//$rs_va_img			= trim($arr_rs[0]["VA_IMG"]);

			$arr_rs_name			= selectAdmin2022($conn, $rs_va_user);
			$rs_va_user_name		= trim($arr_rs_name[0]["ADM_NAME"]);
			$m_color				= selectVaStateMobileClass($conn, $rs_va_state);
			$m_str					= selectVaStateMobile($conn, $rs_va_state);
			$rs_ex_log_str = "";


			foreach($rs_ex_log as $row) {
				$row_each	= explode("/", $row);
				$each_name			 = selectAdminName($conn, $row_each[0]);
				$each_postion		 = selectAdminPosition($conn, $row_each[0], $year);
				if ($each_name == "") continue; //첫 공백 제외
				$each_state			 = selectVaState($conn, $row_each[1]);
				if ($each_state == "미결") $each_state = "승인";
				$each_date			 = $row_each[2];
				$rs_ex_log_str	.= $each_name." ".$each_postion."(".$each_state.":".$each_date.")<br>";
			}
		}
	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "expense_list.php<?=$strParam?>";
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
<script type="text/javascript" >

	function js_save(ex_no) {

		var frm				 = document.frm;
		var sel_pos_ck		 = document.getElementById("sel_pos_ck");
		var sel				 = document.getElementById("sel");
		var memo			 = document.getElementById("memo");


		frm.mode.value		= "OK_U";
		frm.ex_no.value		= ex_no;
		frm.leader_no.value	= sel_pos_ck.options[sel_pos_ck.selectedIndex].value;
		frm.va_state.value	= sel.options[sel.selectedIndex].value;
		frm.ex_memo.value	= memo.value;

		var request = $.ajax({
			url:"/m/approval/approval_list_dml.php",
			type:"POST",
			data:$("#frm").serialize(),
			dataType:"text",
			error:function(request, error){
				alert("Can't do because: " + error);
			},
			success: function(){
				alert("결재 상태가 변경되었습니다.");
			}
		});

		location.href="/m/approval/approval_list.php";

	}

	function js_sel(t){
		if ((t == "2") || (t == "3")) {
			document.getElementById('23vastate').style.display='block';
			document.getElementById('memo').focus();
		} else {
			document.getElementById('23vastate').style.display='none';
		}
	}

	function js_view(t){
		location.href="approval_content?mode=S&ex_no="+t;
	}

	function js_approval(){

		$("#total_count span").text("총 1개 결재하기");
		toastPopup.active('signoffForm');

	}

</script>
</head>
<body>
<form name="frm" id="frm" method="post">
<input type="hidden" name="mode" id="mode" value="">
<input type="hidden" name="ex_no" value="">
<input type="hidden" name="va_state" value="">
<input type="hidden" name="leader_no" value="">
<input type="hidden" name="level" value="">
<input type="hidden" name="ex_memo" value="">
</form>
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
							<li class="navi-item"><a class="navi-name" href="#">연차결재</a></li>
							<li class="navi-item current" title="현재 선택된 항목"><a class="navi-name" href="#">지출결재</a></li>
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
								<a class="btn" href="javascript:history.back()"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>뒤로</title><path d="M12.7,5.7a1,1,0,0,0-1.4-1.4l-7,7a1,1,0,0,0,0,1.4l7,7a1,1,0,0,0,1.4-1.4L7.4,13H19a1,1,0,0,0,0-2H7.4Z"></path></svg></a>
							</div>
						</div>
						<!-- //content-head -->
						<!-- content-body -->
						<div class="content-body">
							<!-- post-display -->
							<div class="post-display module-b type-a">
								
<div class="post-list">
	
	<div class="post-item">
		<div class="post-wrap">
			<div class="post-inform">
				<div class="post-type">
					<p class="data-list">
						<span class="data-item"><span class="mark module-a style-b type-fill <?=$m_color?> medium"><span class="mark-text"><?=$m_str?></span></span></span>
						<span class="data-item"><span class="mark module-a style-b type-line normal-03 medium">
						<span class="mark-text"><?=selectAdminName($conn, $rs_va_state_pos)." ".selectAdminPosition($conn, $rs_va_state_pos, $year)?></span></span></span>
					</p>
				</div>
				<div class="post-head">
					<p class="post-title">
						<?=$rs_ex_title?>
					</p>
				</div>
				<div class="post-data">
					<ul class="data-list">
						<li class="data-item writer">
							<span class="head">글쓴이</span>
							<span class="body">
								<span class="name"><?=$rs_va_user_name?> <?=selectAdminPosition($conn, $rs_va_user, $year)?></span>
								<span class="department"><?=$rs_headquarters_code?></span>
								<span class="team"><?=$rs_dept_code?></span>
							</span>
						</li>
						<li class="data-item posted">
							<span class="head">등록일</span>
							<span class="body">
								<span class="date"><?=substr($rs_reg_date,0,16)?></span>
							</span>
						</li>
					</ul>
				</div>
				<div class="post-data">
					<ul class="data-list">
						<li class="data-item status">
							<span class="head">처리 상태</span>
							<span class="body">
								<span class="em accent-01"><?=$rs_ex_memo?></span>
								<!-- <p><?=$rs_ex_log_str?></p> //승인과정 출력하고 싶다면-->
							</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
</div>

							</div>
							<!-- //post-display -->
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
									$rs_exd_content				= trim($arr_rs_date[$i]["EXD_CONTENT"]);
									$rs_exd_project				= trim($arr_rs_date[$i]["EXD_PROJECT"]);
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
												<div class="form-head"><label class="form-name" for="expensesProjectName">사유</label></div>
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
						<button type="submit" onclick="return js_save('<?=$ex_no?>')" id="total_count" class="btn module-b style-a type-fill accent-01 x-large flex"><span class="btn-text">총 #개 결재하기</span></button>
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