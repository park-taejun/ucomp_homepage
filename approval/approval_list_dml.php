<?session_start();?>
<?
# =============================================================================
# File Name    : approval_list_dml.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2021-12-02
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

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
	include "../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/admin/admin.php";
	require "../_classes/biz/approval/approval.php";
	require "../approval/approval_mailform.php";


//$_SESSION['s_adm_id']="soo";
//$_SESSION['s_adm_no']="25";

	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];
	$year		 = "202206";

	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ex_no				= $_POST['ex_no']!=''?$_POST['ex_no']:$_GET['ex_no'];
	$va_state			= $_POST['va_state']!=''?$_POST['va_state']:$_GET['va_state'];
	$leader_no			= $_POST['leader_no']!=''?$_POST['leader_no']:$_GET['leader_no']; 

#====================================================================
# DML Process
#====================================================================

	//결재 다음 라인
	$arr_rs = selectAdminLeaderYN($conn, $s_adm_no, $year); //리더인지 아닌지
	$LEADER_YN				= $arr_rs["LEADER_YN"];
	$HEADQUARTERS_CODE		= $arr_rs["HEADQUARTERS_CODE"];
	$OCCUPATION_CODE		= $arr_rs["OCCUPATION_CODE"];
	$DEPT_CODE				= $arr_rs["DEPT_CODE"];
	$DEPT_UNIT_NAME			= $arr_rs["DEPT_UNIT_NAME"];
	$LEVEL					= $arr_rs["LEVEL"];
	$LEADER_TITLE			= $arr_rs["LEADER_TITLE"];
	$my_dept_code			= $DEPT_CODE;
	$my_headquarters_code	= $HEADQUARTERS_CODE;
	$my_position_code		= $POSITION_CODE;
	$my_level				= $LEVEL;

	if ($LEVEL <> "0") {

		//$arr_leader = selectAdminLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
		$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);

		if (sizeof($arr_leader) <= 0) {
			$LEVEL = $LEVEL - 1; //leader가 존재하지 않을 때 한단계 위!
			//$arr_leader = selectAdminLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
			$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
		}
		
		if (sizeof($arr_leader) > 0) {
			$leader_leader_no			= $arr_leader[0]["ADM_NO"];
			$leader_headquarters_code	= $arr_leader[0]["HEADQUARTERS_CODE"];
			$leader_name				= $arr_leader[0]["ADM_NAME"];
			$leader_email				= $arr_leader[0]["ADM_EMAIL"];
			$leader_dept_code			= $arr_leader[0]["DEPT_CODE"];
			$leader_position_code		= $arr_leader[0]["POSITION_CODE"];
			$leader_leader_title		= $arr_leader[0]["LEADER_TITLE"];
/*
		$va_state_pos					= $arr_leader["ADM_NO"];
		$va_state_pos_name				= $arr_leader["ADM_NAME"];
		$va_state_pos_leader_title		= $arr_leader["LEADER_TITLE"];
		$va_state_pos_email				= $arr_leader["ADM_EMAIL"];
*/

		}
	}

if ($mode == "OK") {

?>
			<button type="button" class="btn-popclose" onclick="modalHide('pop-planner')" title="닫기">닫기</button>
			<h1>결재 상태 변경</h1>
			<div class="popcontents">
				<p class="point">결재 상태를 변경하는 곳입니다.</p>
				<div class="boardwrite">
					<table>
						<tbody>
							<tr>
								<th>결재위치</th>
								<td>
									<span class="optionbox" style="width:150px">
									<? $arr_leader_all = selectAdminLeaderAll($conn, $year); ?> 
										<select name="sel_pos" id="sel_pos">
											<? for ($i = 0 ; $i < sizeof($arr_leader_all) ; $i++) {
													 if ($my_level < $arr_leader_all[$i]["LEVEL"]) {  //리더 아래LEVEL은 나오지 않도록 2022-03-02
															continue;
													 } else {
											?>
													<option value="<?=$arr_leader_all[$i]["ADM_NO"]?>" <?if ($leader_leader_no == $arr_leader_all[$i]["ADM_NO"]){?>selected<?}?>>
													<?=$arr_leader_all[$i]["ADM_NAME"]?>[<?=$arr_leader_all[$i]["POSITION_CODE"]?>]
													<? if ($arr_leader_all[$i]["DEPT_CODE"] <> "") {?>
														 _<?=$arr_leader_all[$i]["DEPT_CODE"]?>
													<? } ?>
													</option>
												<? } ?>
											<? } ?>
										</select>
									</span>
								</td>
							</tr>
							<tr>
								<th>결재상태</th>
								<td>
									<span class="optionbox" style="width:150px">
										<select name="sel" id="sel" onChange="js_sel(this.value)">
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
								</td>
							</tr>
						</tbody>
					</table>
					<div id="23vastate" style="display:none;padding-top:10px;">
							<input type="text" name="memo" id="memo" placeholder="보류/반려 사유를 입력해주세요" style="width:100%" />
					</div>
				</div>

				<p class="btncenter">
					<button type="button" class="btn-navy" onclick="modalHide('pop-planner')">취소</button>
					<button type="button" class="btn-navy" onclick="js_save('<?=$ex_no?>');">확인</button>
				</p>
			</div>
<?
}

//승인 위치 수정
if ($mode == "OK_U") {

	//결재 로그 남기기
	$ex_log = "";
	$arr_rs_ex_log = selectApproval($conn, $ex_no);
	
	$ex_log = $arr_rs_ex_log."//".$s_adm_no."/".$va_state."/".date("Y-m-d H:i");

	if ($va_state == "2" || $va_state == "3") { //
		$arr_data = array("EX_NO"=>$ex_no,
											"VA_STATE"=>$va_state,
											"EX_MEMO"=>$ex_memo,
											"EX_LOG"=>$ex_log
										);
	} else {
		$arr_data = array("EX_NO"=>$ex_no,
											"VA_STATE"=>$va_state,
											"VA_STATE_POS"=>$leader_no,
											"EX_LOG"=>$ex_log
										);
	}
	$result = updateApproval($conn, $arr_data, $ex_no);

	$leader_name	= selectAdminName($conn, $leader_no);
	$leader_email	= selectAdminEmail2($conn, $leader_no);
	$leader_title	= selectAdminLeaderTitle($conn, $leader_no, $year); 

	//mail start
	if ($s_adm_no <> "25"){ 
		$SUBJECT	= "지출결의 결재 승인을 기다리고 있습니다(테스트 베타 버전)";
		// $mailto		= $leader_email;//실무적용 해제할것
		$mailto		= "cadt@ucomp.co.kr";//실무적용 해제할것
		$EMAIL		= "management@ucomp.co.kr";
		$NAME		= "유컴패니온";
		$CONTENT  = $mail_string;

		$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 

		//개인 승인위치 변경 알림 메일 발송
			$arr_adm_email	= selectAdminEmail($conn, $ex_no);
			$SUBJECT		= "결재위치가 변경되었습니다(테스트 베타 버전)";
			$mailto			= $arr_adm_email[0]["ADM_EMAIL"];//실무적용 해제할것
			$EMAIL			= "management@ucomp.co.kr";
			$NAME			= "유컴패니온";
			$CONTENT		= "<br /> [결재위치 변경건] <hr><br /> 결재위치가 ". $leader_name. " ".$leader_title." (으)로 변경되었습니다<br /><br /> - ".date("Y-m-d").". ". $ADM_NAME." ".$LEADER_TITLE. " 승인합니다! ";
			$mail_flag		= sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 
		//개인 end 

	} else {
		if ($va_state == "1"){
			//개인 승인완료 메일 발송
				$arr_adm_email	= selectAdminEmail($conn, $ex_no);
				$SUBJECT		= "승인완료되었습니다.(테스트 베타 버전)";
				$mailto			= $arr_adm_email[0]["ADM_EMAIL"];//실무적용 해제할것
				$EMAIL			= "management@ucomp.co.kr";
				$NAME			= "유컴패니온";
				$CONTENT		= "<br /> [결재 승인완료 알림] <hr><br /> 승인이 완료 되었습니다<br /><br /> - ".date("Y-m-d").". 승인완료! ";
				$mail_flag		= sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 
			//개인 end 
		}
	}
	//mail end

	$str_result = "성공";
	$arr_result = array("result"=>$result, "msg"=>$str_result);

	//echo json_encode($arr_result);
	echo json_encode($arr_result);

}

if ($mode == "OK_ALL") {

	$result = updateApprovalAll($conn, $chk, $leader_no, $s_adm_no);

	//mail start
	if ($s_adm_no <> "25"){ 
		$SUBJECT	= "지출결의 결재 승인을 기다리고 있습니다(테스트 베타 버전)";
		// $mailto		= $leader_email;//실무적용 해제할것
		$mailto		= "cadt@ucomp.co.kr";
		$EMAIL		= "management@ucomp.co.kr";
		$NAME		= "유컴패니온";
		$CONTENT	= $mail_string;

		$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);   //실제 발송시 주석 해제할 것 

		//개인 승인위치 변경 알림 메일 발송
		for ($i=0; $i < sizeof($chk) ; $i++){
			$arr_adm_email	= selectAdminEmail($conn, $chk[$i]);
			$SUBJECT		= "결재위치가 변경되었습니다(테스트 베타 버전)";
			$mailto			= $arr_adm_email[0]["ADM_EMAIL"];//실무적용 해제할것
			$EMAIL			= "management@ucomp.co.kr";
			$NAME			= "유컴패니온";
			$CONTENT		= "<br /> [결재위치 변경건] <hr><br /> 결재위치가 ". $leader_name. " ".$leader_leader_title." 으로 변경되었습니다<br /><br /> - ".date("Y-m-d").". ". $ADM_NAME." ".$LEADER_TITLE. " 승인합니다! ";
			$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 
		}
		//개인 end 

	} else {

			//개인 승인완료 메일 발송
			for ($i=0; $i < sizeof($chk) ; $i++){
				$arr_adm_email	= selectAdminEmail($conn, $chk[$i]);
				$SUBJECT		= "승인완료되었습니다.(테스트 베타 버전)";
				$mailto			= $arr_adm_email[0]["ADM_EMAIL"];//실무적용 해제할것
				$EMAIL			= "management@ucomp.co.kr";
				$NAME			= "유컴패니온";
				$CONTENT		= "<br /> [결재 승인완료 알림] <hr><br /> 승인이 완료 되었습니다<br /><br /> - ".date("Y-m-d").". 승인완료! ";
				$mail_flag		= sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 
			}
			//개인 end 

	}
	//mail end

	if ($result){
?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script language="javascript">
			alert("승인이 완료되었습니다!");
			location.href="approval_list.php";
		</script>

<?
	}
}

#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>