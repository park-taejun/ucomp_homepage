<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : vacation_approval_content.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-08-18
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
	require "../../_classes/biz/vacation/vacation.php";

	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];
	$year				= "202206";

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

	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$seq_no				= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];

#====================================================================
# DML Process
#====================================================================

	#echo $adm_no;

	if ($mode == "S") { 
	
		$arr_rs				= selectVacation($conn, $seq_no);

		$rs_seq_no						= trim($arr_rs[0]["SEQ_NO"]);
		$rs_va_type						= trim($arr_rs[0]["VA_TYPE"]);
		$rs_va_sdate					= trim($arr_rs[0]["VA_SDATE"]);
		$rs_va_edate					= trim($arr_rs[0]["VA_EDATE"]);
		$rs_va_mdate					= trim($arr_rs[0]["VA_MDATE"]);
		$rs_va_memo						= trim($arr_rs[0]["VA_MEMO"]);
		$rs_va_user						= trim($arr_rs[0]["VA_USER"]);
		$rs_va_state					= trim($arr_rs[0]["VA_STATE"]);
		$rs_va_state_pos				= trim($arr_rs[0]["VA_STATE_POS"]);
		$rs_va_flag						= trim($arr_rs[0]["VA_FLAG"]);
		$rs_va_img						= trim($arr_rs[0]["VA_IMG"]);

		$rs_reg_date					= substr($arr_rs[0]["REG_DATE"], 0, 16);
		$rs_va_log						= explode("//", $arr_rs[0]["VA_LOG"]);
		$rs_va_period					= trim($arr_rs[0]["VA_PERIOD"]);
		$rs_va_mdate					= trim($arr_rs[0]["VA_MDATE"]);
		$rs_va_way						= trim($arr_rs[0]["VA_WAY"]);
		$rs_va_hphone					= trim($arr_rs[0]["VA_HPHONE"]);
		$rs_memo						= trim($arr_rs[0]["MEMO"]);

		$rs_va_user_name				= selectAdminName($conn, $rs_va_user) ; //이름
		$rs_va_user_headquarters_code	= selectAdminHeadquarters($conn, $rs_va_user, $year);
		$rs_va_user_dept_code			= selectAdminDept($conn, $rs_va_user, $year);
		$rs_va_user_position_code		= selectAdminPosition($conn, $rs_va_user, $year);
		$rs_va_state_name				= selectVaState($conn, $rs_va_state);
		$rs_va_type_name				= selectVaType($conn, $rs_va_type);

		$rs_va_state_pos_name			= selectAdminName($conn, $rs_va_state_pos);
		$rs_va_state_pos_position_code	= selectAdminPosition($conn, $rs_va_state_pos, $year);

		$rs_va_mdate_tmp				= rtrim($rs_va_mdate, ",");
		$m_color						= selectVaStateMobileClass($conn, $rs_va_state);
		$m_str							= selectVaStateMobile($conn, $rs_va_state);
		$rs_va_log_str					= "";

		foreach($rs_va_log as $row) {
			$row_each	= explode("/", $row);
			$each_name			 = selectAdminName($conn, $row_each[0]);
			$each_postion		 = selectAdminPosition($conn, $row_each[0], $year);
			if ($each_name == "") continue; //첫 공백 제외
			$each_state			 = selectVaState($conn, $row_each[1]);
			if ($each_state == "미결") $each_state = "승인";
			$each_date			 = $row_each[2];
			$rs_va_log_str	.= $each_name." ".$each_postion."(".$each_state.":".$each_date.")<br>";
		}

		if($rs_va_sdate <> $rs_va_edate) {
			$va_date = $rs_va_sdate." ~ ". substr($rs_va_edate, 5, 5);
		} else {
			if($rs_va_sdate <> "") {
				$va_date = $rs_va_sdate;
			} else {
				$va_date = rTrim($rs_va_mdate, ",");
				$va_date = str_replace("'", " ", $va_date);
			}
		}
		
		$rs_va_memo			= str_replace("\n\n", "\n", $rs_va_memo);
		$rs_va_memo			= str_replace("\n", "<br>", $rs_va_memo);

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
						<?=$rs_va_type_name?>
					</p>
				</div>
				<div class="post-data">
					<ul class="data-list">
						<li class="data-item writer">
							<span class="head">글쓴이</span>
							<span class="body">
								<span class="name"><?=$rs_va_user_name?> <?=selectAdminPosition($conn, $rs_va_user, $year)?></span>
								<span class="department"><?=$rs_va_user_headquarters_code?></span>
								<span class="team"><?=$rs_va_user_dept_code?></span>
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
							<span class="head">내용</span>
							<span class="body">
								<span class="em accent-01"><?=$rs_va_memo?></span>
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
							<? if ($rs_va_type == 5) { ?>
							<!-- section -->
							<div class="section module-a style-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name"><?=$rs_va_type_name?></span></h4></div>
								<div class="section-body">
									<!-- data-display -->
									<div class="data-display module-a style-a type-a small">
										<ul class="data-list">
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">일자</span></div>
													<div class="data-body"><?=$va_date?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">업무연락</span></div>
													<div class="data-body"><?=$rs_va_way?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">업무내역</span></div>
													<div class="data-body"><?=$rs_va_memo?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">연락처</span></div>
													<div class="data-body"><?=$rs_va_hphone?></div>
												</div>
											</li>
										</ul>
									</div>
									<!-- //data-display -->
								</div>
							</div>
							<!-- //section -->
							<? } else {?>
							<!-- section -->
							<div class="section module-a style-a type-a">
								<div class="section-head"><h4 class="section-title"><span class="section-name"><?=$rs_va_type_name?></span></h4></div>
								<div class="section-body">
									<!-- data-display -->
									<div class="data-display module-a style-a type-a small">
										<ul class="data-list">
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">일자</span></div>
													<div class="data-body"><?=$va_date?></div>
												</div>
											</li>
											<li class="data-item">
												<div class="data-wrap">
													<div class="data-head"><span class="data-name">사유</span></div>
													<div class="data-body"><?=$rs_va_memo?></div>
												</div>
											</li>
										</ul>
									</div>
									<!-- //data-display -->
								</div>
							</div>
							<!-- //section -->
							<? } ?>
						</div>
						<!-- //content-body -->
						<!-- content-side -->
						<div class="content-side">
							<!-- noti-board -->
							<div class="noti-board module-a type-none normal-02 small attr-information">
								<div class="board-wrap">
									<div class="board-head"><p class="board-subject"><span class="board-name">연차등록 안내</span></p></div>
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
		<!--<%-include('../_templates/_footer.front.ejs') %>-->
	</div>
	<!-- //page -->
	<!--<%-include('../_templates/_content.popup.ejs') %>-->
</div>
</body>
</html>