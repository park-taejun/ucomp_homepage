<?session_start();?>
<?
# =============================================================================
# File Name    : vacation_approval_list_dml.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-27
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
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
	include "../../_common/common_header_mobile.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/approval/vacation_approval.php";
	require "../../manager/approval/approval_mailform.php";


/* 
테스트옹 아이디와 no
supasonic...132

park      9

brandon   95

minyn0106   27

//프론트
iikasam    119

//ux
bklee  81
raysoo   92

dosahyun   26
park01   76

gio1202    14

youj0904...152 (기획팀장)
jyeom...226(엄이사)
soo   25

*/

//$_SESSION['s_adm_id']="soo";
//$_SESSION['s_adm_no']="25";

	$s_adm_id=$_SESSION['s_adm_id'];
	$s_adm_no=$_SESSION['s_adm_no'];
	$year		 = "202206";

	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$seq_no				= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$va_state			= $_POST['va_state']!=''?$_POST['va_state']:$_GET['va_state'];
	$leader_no		= $_POST['leader_no']!=''?$_POST['leader_no']:$_GET['leader_no']; 

#====================================================================
# DML Process
#====================================================================

	//결재 다음 라인
	$arr_rs = selectAdminLeaderYN($conn, $s_adm_no, $year); //리더인지 아닌지
	$LEADER_YN						= $arr_rs["LEADER_YN"];
	$HEADQUARTERS_CODE		= $arr_rs["HEADQUARTERS_CODE"];
	$OCCUPATION_CODE			= $arr_rs["OCCUPATION_CODE"];
	$DEPT_CODE						= $arr_rs["DEPT_CODE"];
	$DEPT_UNIT_NAME				= $arr_rs["DEPT_UNIT_NAME"];
	$LEVEL								= $arr_rs["LEVEL"];
	$LEADER_TITLE					= $arr_rs["LEADER_TITLE"];
	$my_dept_code					= $DEPT_CODE;
	$my_headquarters_code	= $HEADQUARTERS_CODE;
	$my_position_code			= $POSITION_CODE;
	$my_level							= $LEVEL;

	$approval_right = ""; //최종 승인 권한 
	$approval_right_leader= selectAdminApprovalRightLeader($conn, $s_adm_no);
	if (sizeof($approval_right_leader) > 0) {
		$approval_right = "Y";
	} 

	if ($approval_right <> "Y") {

		$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);

		if (sizeof($arr_leader) <= 0) {
			$LEVEL = $LEVEL - 1; //leader가 존재하지 않을 때 한단계 위!
			$arr_leader = selectAdminPartLeader($conn, $LEADER_YN, $HEADQUARTERS_CODE, $OCCUPATION_CODE, $DEPT_CODE, $DEPT_UNIT_NAME, $LEVEL, $LEADER_TITLE, $year);
		}
		
		if (sizeof($arr_leader) > 0) {
			$leader_leader_no					= $arr_leader[0]["ADM_NO"];
			$leader_headquarters_code = $arr_leader[0]["HEADQUARTERS_CODE"];
			$leader_name							= $arr_leader[0]["ADM_NAME"];
			$leader_email							= $arr_leader[0]["ADM_EMAIL"];
			$leader_dept_code					= $arr_leader[0]["DEPT_CODE"];
			$leader_position_code			= $arr_leader[0]["POSITION_CODE"];
			$leader_leader_title			= $arr_leader[0]["LEADER_TITLE"];
		}
	}

//승인 위치 수정
if ($mode == "OK_U") {

	//결재 로그 남기기
	$ex_log = "";
	$arr_rs_va_log = selectVacationApproval($conn, $seq_no);
	
	$va_log = $arr_rs_va_log."//".$s_adm_no."/".$va_state."/".date("Y-m-d H:i");

	if ($va_state == "2" || $va_state == "3") { //
		$arr_data = array("SEQ_NO"=>$seq_no,
											"VA_STATE"=>$va_state,
											"MEMO"=>$memo,
											"VA_LOG"=>$va_log
										);
	} else {
		$arr_data = array("SEQ_NO"=>$seq_no,
											"VA_STATE"=>$va_state,
											"VA_STATE_POS"=>$leader_no,
											"VA_LOG"=>$va_log
										);
	}
	$result = updateVacationApproval($conn, $arr_data, $ex_no);

	$leader_name	= selectAdminName($conn, $leader_no);
	$leader_email	= selectAdminEmail2($conn, $leader_no);
	$leader_title =	selectAdminLeaderTitle($conn, $leader_no, $year); 

	//mail start
	if ($approval_right <> "Y"){  
		$SUBJECT	= "연차 승인을 기다리고 있습니다(테스트 베타 버전)";
		$mailto		= $leader_email;////////////////////////////////////////실무적용 해제할것
		$EMAIL		= "management@ucomp.co.kr";
		$NAME			= "유컴패니온";
		$CONTENT  = $mail_string;

		//11111111111111111111111111111$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 

		//개인 승인위치 변경 알림 메일 발송
			$arr_adm_email = selectAdminEmail($conn, $ex_no);
			$SUBJECT	= "결재위치가 변경되었습니다(테스트 베타 버전)";
			$mailto		= $arr_adm_email[0]["ADM_EMAIL"];////////////////////////////////////////실무적용 해제할것
			$EMAIL		= "management@ucomp.co.kr";
			$NAME			= "유컴패니온";
			$CONTENT  = "<br /> [결재위치 변경건] <hr><br /> 결재위치가 ". $leader_name. " ".$leader_title." (으)로 변경되었습니다<br /><br /> - ".date("Y-m-d").". ". $ADM_NAME." ".$LEADER_TITLE. " 승인합니다! ";
			//2222222222222222222222222222$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 

		//개인 end 
	} else {
		if ($va_state == "1"){
			//개인 승인완료 메일 발송
				$arr_adm_email = selectAdminEmail($conn, $ex_no);
				$SUBJECT	= "승인완료되었습니다.(테스트 베타 버전)";
				$mailto		= $arr_adm_email[0]["ADM_EMAIL"];///////////////////////////////////////실무적용 해제할것
				$EMAIL		= "management@ucomp.co.kr";
				$NAME			= "유컴패니온";
				$CONTENT  = "<br /> [연차 승인완료 알림] <hr><br /> 승인이 완료 되었습니다<br /><br /> - ".date("Y-m-d").". 승인완료! ";
			//////////////////////////////////////	$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 

			//개인 end 
		}
	}
	//mail end

	$str_result = "success";
	$arr_result = array("result"=>$result, "msg"=>$str_result);

	echo json_encode(1);

}

if ($mode == "OK_ALL") {

	$result = updateVacationApprovalAll($conn, $chk, $leader_no, $s_adm_no);

	//mail start
	if ($approval_right <> "Y"){ 
		$SUBJECT	= "연차 승인을 기다리고 있습니다(테스트 베타 버전)";
		$mailto		= $leader_email;///////////////////////////////////////실무적용 해제할것
		$EMAIL		= "management@ucomp.co.kr";
		$NAME			= "유컴패니온";
		$CONTENT  = $mail_string;

		///33333333333333333333333333333 $mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);   //실제 발송시 주석 해제할 것 

		//개인 승인위치 변경 알림 메일 발송
		for ($i=0; $i < sizeof($chk) ; $i++){
			$arr_adm_email = selectAdminEmail($conn, $chk[$i]);
			$SUBJECT	= "결재위치가 변경되었습니다(테스트 베타 버전)";
			$mailto		= $arr_adm_email[0]["ADM_EMAIL"];///////////////////////////////////////실무적용 해제할것
			$EMAIL		= "management@ucomp.co.kr";
			$NAME			= "유컴패니온";
			$CONTENT  = "<br /> [결재위치 변경건] <hr><br /> 결재위치가 ". $leader_name. " ".$leader_leader_title." 으로 변경되었습니다<br /><br /> - ".date("Y-m-d").". ". $ADM_NAME." ".$LEADER_TITLE. " 승인합니다! ";
			$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 
		}
		//개인 end 

	} else {

			//개인 승인완료 메일 발송
			for ($i=0; $i < sizeof($chk) ; $i++){
				$arr_adm_email = selectAdminEmail($conn, $chk[$i]);
				$SUBJECT	= "승인완료되었습니다.(테스트 베타 버전)";
				$mailto		= $arr_adm_email[0]["ADM_EMAIL"];///////////////////////////////////////실무적용 해제할것
				$EMAIL		= "management@ucomp.co.kr";
				$NAME			= "유컴패니온";
				$CONTENT  = "<br /> [결재 승인완료 알림] <hr><br /> 승인이 완료 되었습니다<br /><br /> - ".date("Y-m-d").". 승인완료! ";
				$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);  //실제 발송시 주석 해제할 것 
			}
			//개인 end 

	}
	//mail end

	if ($result){
?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script language="javascript">
			alert("결재 상태가 변경되었습니다.");
			location.href="vacation_approval_list.php";
		</script>

<?
	}
}

#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>