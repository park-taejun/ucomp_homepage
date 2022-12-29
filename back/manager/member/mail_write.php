<?session_start();?>
<?
# =============================================================================
# File Name    : board_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2011.06.01
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../../_common/config.php";
	require "../../../_classes/community/util/util.php";
	require "../../../_classes/community/etc/etc.php";
	require "../../../_classes/biz/member/member.php";
	require "../../../_classes/community/cadmin/admin.php";
	



	$emailing_del_tf = "N";
	if ($mode == "E1") {//선택된 사람에게
		$row_cnt = count($chk);
		$targetString="이메일,고객명Æ";
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_m_no = $chk[$k];

			$mresult = selectMember($conn, $tmp_m_no);
			$rs_mem_id					= trim($mresult[0]["MEM_ID"]); 
			$rs_mem_nm					= trim($mresult[0]["MEM_NM"]); 
			$rs_email1					= trim($mresult[0]["EMAIL1"]); 
			$rs_email2					= trim($mresult[0]["EMAIL2"]); 
			$rs_email						= $rs_email1."@".$rs_email2;
			$rs_email_tf				= trim($mresult[0]["EMAIL_TF"]); 
			
			if($rs_email_tf=="Y")$targetString.="\r\n".$rs_email.",".$rs_mem_nm."Æ";
		}
	}

	if ($mode == "E2") {//검색된 사람에게
		
		$targetString="이메일,고객명Æ";
		//$arr_rs_em = listMember($conn, $mem_type, $email_tf, $use_tf, $emailing_del_tf, $search_field, $search_str, $nPage, $nPageSize);
		$arr_rs_em = listCommunityMember($conn, $comm_no, $con_mem_type, $con_use_tf, $emailing_del_tf, $search_field, $search_str, $order_field, $order_str, 1, 100000);
		$nCnt = 0;
		
		if (sizeof($arr_rs_em) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs_em); $j++) {
		
				$rs_mem_id					= trim($arr_rs_em[$j]["MEM_ID"]); 
				$rs_mem_nm					= trim($arr_rs_em[$j]["MEM_NM"]); 
				$rs_email1					= trim($arr_rs_em[$j]["EMAIL1"]); 
				$rs_email2					= trim($arr_rs_em[$j]["EMAIL2"]); 
				$rs_email						= $rs_email1."@".$rs_email2;
				$rs_email_tf				= trim($arr_rs_em[$j]["EMAIL_TF"]); 
				
				if($rs_email_tf=="Y")$targetString.="\r\n".$rs_email.",".$rs_mem_nm."Æ";
			}
		}
	}

	if ($mode == "E3") {//전체 회원에게
		
		$targetString="이메일,고객명Æ";
		//$arr_rs_em = listMember($conn, $mem_type, $email_tfxxx, $use_tf, $emailing_del_tf, $search_field, $search_str, 1, 100000);
		$arr_rs_em = listCommunityMember($conn, $comm_no, $con_mem_type, $con_use_tf, $emailing_del_tf, $search_fieldxxx, $search_strxxx, $order_field, $order_str, 1, 100000);
		$nCnt = 0;
		
		if (sizeof($arr_rs_em) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs_em); $j++) {
		
				$rs_mem_id					= trim($arr_rs_em[$j]["MEM_ID"]); 
				$rs_mem_nm					= trim($arr_rs_em[$j]["MEM_NM"]); 
				$rs_email1					= trim($arr_rs_em[$j]["EMAIL1"]); 
				$rs_email2					= trim($arr_rs_em[$j]["EMAIL2"]); 
				$rs_email						= $rs_email1."@".$rs_email2;
				$rs_email_tf				= trim($arr_rs_em[$j]["EMAIL_TF"]); 
				
				if($rs_email_tf=="Y")$targetString.="\r\n".$rs_email.",".$rs_mem_nm."Æ";
			}
		}
	}


	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&bb_code=".$bb_code."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str."&menu_cd=".$menu_cd;

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "member_list.php<?=$strParam?>";
</script>
</head>
</html>
<?
		exit;
	}	
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
<link href="../css/common.css" rel="stylesheet" />

<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="/manager/js/calendar2.js"></script>
<script type="text/javascript" src="../../../_common/SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">
<!--

function js_list() {
	document.location = "member_list.php<?=$strParam?>";
}


function js_setview(nm, mail) {

	var frm = document.frm;
	frm.targetString.value=frm.targetString.value+"\r\n"+mail+","+nm+"Æ";
	
}

function js_save() {

	var frm = document.frm;
	
	if(document.frm.mailTitle.value==""){
		alert('제목을 입력해주세요.');
		document.frm.mailTitle.focus();
		return;
	}

	if(document.frm.senderName.value==""){
		alert('작성자를 입력해주세요.');
		document.frm.senderName.focus();
		return;
	}

	frm.mode.value = "SEND";

	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.

	window.open("","emails","height=400 width=400");
	frm.method = "post";
	frm.target = "emails";
	frm.action = "http://mailer.goupp.org/api/massmail";
	frm.submit();

}



//-->
</script>

</head>
<body>

<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";

?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2>대량메일발송 관리</h2>
		</div>
		
		<section class="conBox">

			<form name="frm" method="post" >
			<input type="hidden" name="mode" value="" />
			<input type="hidden" name="menu_cd" value="<?=$menu_cd?>" />
			<!--//썬더메일설정값-->
			<input type="hidden" name="writer" value="admin" />
			<input type="hidden" name="method" value="massmail" />
			<input type="hidden" name="targetType" value="string" />
			<input type="hidden" name="contentType" value="content" />

			<input type="hidden" name="fileOneToOne" value="[$email]≠이메일ø[$name]≠고객명">
			<input type="hidden" name="nPage" value="<?=$nPage?>" />
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

			<h3 class="conTitle">대량메일발송</h3>
			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
					<thead>					

						<tr>
							<th scope="row">제목</th>
							<td colspan="3">
								<input type="text" class="txt" name="mailTitle" value="안녕하세요 [$name]님" style="width: 95%;" />
							</td>
						</tr>

						<tr>
							<th>작성자</th>
							<td class="line">
								<input type="text" class="txt" name="senderName" value="통합진보당" style="width: 120px;" />
							</td>
							<th>EMAIL</th>
							<td class="line">
								<input type="text" class="txt" name="senderEmail" value="admin@goupp.org" style="width: 220px;" />
							</td>
						</tr>
						<tr style="display:none">
							<th>받는사람</th>
							<td  colspan="3" class="line">
								<input type="text" class="txt" name="receiverName" value="[$name]" style="width: 220px;" />
							</td>
						</tr>

						

						<tr> 
							<th>받는사람정보</th>
							<td colspan="3" class="contentswrite line" style="padding: 10px 10px 10px 15px">
									<br>
									필드구분 : ,<br>
									받는사람구분 : Æ<br>
									우측 목록에서 검색후 [아이디]를 선택하면 추가됩니다.<br>
									<br>
									<div style="float:left;padding-left:0px;width:970px;height:520px;text-align:top;">
										<span class="fl" style="padding-left:0px;width:400px;height:500px;">
											<textarea name="targetString" id="targetString"  style="padding-left:0px;width:400px;height:500px;"><?=$targetString?></textarea>
										</span>
										<span class="fl" style="padding-left:0px;width:540px;height:500px;">
											<iframe src="mail_write_iframe4list.php?con_mem_type=<?=$con_mem_type?>&menu_cd=<?=$menu_cd?>" name="ifr_hidden" id="ifr_hidden" frameborder="no" marginwidth="0" marginheight="0" border="0"  style="padding-left:0px;width:540px;height:500px;"></iframe>
										</span>
									</div>
							</td>
						</tr>
						<tr> 
							<th>내용</th>
							<td colspan="3" class="contentswrite line" style="padding: 10px 10px 10px 15px">
							템플릿 일대일 치환 사용 변수<br>
							[$name] : 내용중에 <b>[$name]</b>를 입력하면 해당자리에 받는사람 이름이 자동 치환되어 발송됨<br>
							[$email] : 내용중에 <b>[$email]</b>를 입력하면 해당자리에 받는사람 이메일이 자동 치환되어 발송됨<br>
									<?
										// ================================================================== 수정 부분								
									?>
										 <span class="fl" style="padding-left:0px;width:740px;height:500px;"><textarea name="mailContent" id="mailContent"  style="padding-left:0px;width:730px;height:400px;"></textarea></span>
									<?
										// ================================================================== 수정 부분
									?>
							</td>
						</tr>

						<tr>
							<th>발송방식</th>
							<td class="line">						
							<input type="radio" class="radio" name="sendType" value="1" checked onclick="document.frm.sendSchedule.value='';document.frm.sendSchedule.disabled=true;"/> 즉시발송&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="radio" name="sendType" value="2"  onclick="document.frm.sendSchedule.disabled=false;"/> 예약발송
							</td>
							<th>예약발송시간</th>
							<td class="line">
								<input type="text" class="txt" name="sendSchedule" value="" style="width: 220px;" disabled="1"/><a href="javascript:show_calendar('document.frm.sendSchedule', document.frm.sendSchedule.value);" onFocus="blur();"><!--
								--><img src="/manager/images/bu/ic_calendar.gif" alt="" /></a>&nbsp;&nbsp;&nbsp;&nbsp;ex)YYYY-MM-DD hh:mm:ss
							</td>
						</tr>
						<tr>
							<th>연동결과설명</th>
							<td colspan="3" class="line">							
								-100	데이터 연동 성공<br>
								10	필수 파라미터 미입력, 데이터 값 오류<br>
								20	썬더메일 연결 실패<br>
								30	대상자 등록 실패<br>
								40	메일 등록 실패<br>
								90	기타<br>
							</td>
						</tr>

						
					</tbody>
				</table>
				</form>
			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fRight">
					<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
				</ul>
			</div>
		</section>
		<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
<SCRIPT LANGUAGE="JavaScript">
<!--
var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "mailContent",
	sSkinURI: "../../../_common/SE2.1.1.8141/SmartEditor2Skin.html",
	htParams : {bUseToolbar : true, 
	fOnBeforeUnload : function(){ 
		// alert('야') 
	}
	}, 
	fCreator: "createSEditor2"
});
//-->
</SCRIPT>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>