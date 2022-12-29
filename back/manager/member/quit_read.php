<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : member_read.php
# Modlue       : 
# Writer       : GIRINGRIM 
# Create Date  : 2016-04-06
# Modify Date  : 
#	Copyright : Copyright @GIRINGRIM.Com. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "MB006"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";	
	
#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/member/member.php";
	require "../../_classes/biz/group/group.php";

#====================================================================
# Request Parameter
#====================================================================

	$mm_subtree	 = "7";

	#List Parameter
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	$Ngroup_cd			= trim($Ngroup_cd);
	$sel_party				= trim($sel_party);
	$online_flag			= trim($online_flag);

	$mode 				= trim($mode);
	$m_no				= trim($m_no);
	
	$result = false;
#====================================================================
# DML Process
#====================================================================

	if ($mode == "D") {
		$result = quitMember($conn, $m_id);
	}

	if ($mode == "S") {

		$arr_rs = selectQuitMember($conn, $m_no);
		
		$rs_m_no						= trim($arr_rs[0]["M_NO"]); 
		$rs_m_id						= trim($arr_rs[0]["M_ID"]); 
		$rs_m_name					= trim($arr_rs[0]["M_NAME"]); 
		$rs_m_jumin					= trim($arr_rs[0]["M_JUMIN"]);
		$rs_m_email					= trim($arr_rs[0]["M_EMAIL"]); 
		$rs_m_password_q		= trim($arr_rs[0]["M_PASSWORD_Q"]); 
		$rs_m_password_a		= trim($arr_rs[0]["M_PASSWORD_A"]); 
		$rs_m_birth					= trim($arr_rs[0]["M_BIRTH"]); 
		$rs_m_tel						= trim($arr_rs[0]["M_TEL"]); 
		$rs_m_hp						= trim($arr_rs[0]["M_HP"]); 
		$rs_m_sex						= trim($arr_rs[0]["M_SEX"]); 
		$rs_m_zip1					= trim($arr_rs[0]["M_ZIP1"]); 
		$rs_m_addr1					= trim($arr_rs[0]["M_ADDR1"]); 
		$rs_m_addr2					= trim($arr_rs[0]["M_ADDR2"]); 

		$rs_o_m_zip					= trim($arr_rs[0]["M_O_ZIP"]); 
		$rs_o_m_addr1				= trim($arr_rs[0]["M_O_ADDR1"]); 
		$rs_o_m_addr2				= trim($arr_rs[0]["M_O_ADDR2"]); 

		$rs_m_organization	= trim($arr_rs[0]["M_ORGANIZATION"]); 

		if($rs_m_organization){
			$arr_rs_Group = selectGroupAsGroupCD($conn, $rs_m_organization);
			$Group_nm					= trim($arr_rs_Group[0]["GROUP_NAME"]); 
		}else{
			$Group_nm="없음";
		}

		$rs_m_online_flag		= trim($arr_rs[0]["M_ONLINE_FLAG"]); 

		$rs_sido						= trim($arr_rs[0]["SIDO"]); 
		$rs_sigungu					= trim($arr_rs[0]["SIGUNGU"]); 
		$rs_m_mailling			= trim($arr_rs[0]["M_MAILLING"]); 
		$rs_m_sms						= trim($arr_rs[0]["M_SMS"]); 
		$rs_m_nick_date			= trim($arr_rs[0]["M_NICK_DATE"]); 
		$rs_m_level					= trim($arr_rs[0]["M_LEVEL"]); 
		$rs_m_signature			= trim($arr_rs[0]["M_SIGNATURE"]); 
		
		$rs_job							= trim($arr_rs[0]["M_1"]);
		$rs_com_name				= trim($arr_rs[0]["M_2"]);
		$rs_party						= trim($arr_rs[0]["M_3"]);
		$rs_group						= trim($arr_rs[0]["M_4"]);

		$rs_is_pay					= trim($arr_rs[0]["M_5"]);
		$rs_pay_type				= trim($arr_rs[0]["M_6"]);
		$rs_cms_info01			= trim($arr_rs[0]["M_7"]);
		$rs_cms_info02			= trim($arr_rs[0]["M_8"]);
		$rs_cms_info03			= trim($arr_rs[0]["M_9"]);
		$rs_cms_info04			= trim($arr_rs[0]["M_10"]);
		$rs_cms_info05			= trim($arr_rs[0]["M_11"]);
		$rs_cms_amount			= trim($arr_rs[0]["M_12"]);
		$rs_m_13						= trim($arr_rs[0]["M_13"]);

		$rs_cms_birth				= trim($arr_rs[0]["M_CMS_BIRTH"]);

		if ($rs_cms_birth) {
			$rs_cms_birth_yy = left($rs_cms_birth,4);
			$rs_cms_birth_mm = right(left($rs_cms_birth,6),2);
			$rs_cms_birth_dd = right($rs_cms_birth,2);
		} else {
			$rs_cms_birth_yy = left($rs_m_birth,4);
			$rs_cms_birth_mm = right(left($rs_m_birth,6),2);
			$rs_cms_birth_dd = right($rs_m_birth,2);
		}

		$rs_cms_flag				= trim($arr_rs[0]["CMS_FLAG"]);
		$rs_send_flag				= trim($arr_rs[0]["SEND_FLAG"]);
		$rs_cms_result			= trim($arr_rs[0]["CMS_RESULT"]);
		$rs_cms_result_msg	= trim($arr_rs[0]["CMS_RESULT_MSG"]);
		$rs_m_memo					= trim($arr_rs[0]["M_MEMO"]);

		$strJumin = decrypt($key, $iv, $rs_m_jumin);
		$arrJumin = explode("-",$strJumin);

		$str_m_tel = decrypt($key, $iv, $rs_m_hp);
		$arr_m_tel = explode("-",$str_m_tel);

		$str_tel = decrypt($key, $iv, $rs_m_tel);
		$arr_tel = explode("-",$str_tel);
		
		$arr_m_email = explode("@",$rs_m_email);

		$rs_m_birth_yy = left($rs_m_birth,4);
		$rs_m_birth_mm = right(left($rs_m_birth,6),2);
		$rs_m_birth_dd = right($rs_m_birth,2);

		$rs_cms_info01 = decrypt($key, $iv, $rs_cms_info01);
		$rs_cms_info02 = decrypt($key, $iv, $rs_cms_info02);
		$rs_cms_info03 = decrypt($key, $iv, $rs_cms_info03);
		$rs_cms_info04 = decrypt($key, $iv, $rs_cms_info04);
		$rs_cms_info05 = decrypt($key, $iv, $rs_cms_info04);

		if ($rs_pay_type == "mobile") {
			$arr_pay_mtel			= explode("-", $rs_cms_info01);
			$rs_mobile_com		= $rs_cms_info02;
		}

		if ($rs_pay_type == "cms") {
			$rs_bank_code		= $rs_cms_info01;
			$rs_bank_no			= $rs_cms_info02;
			$rs_bank_name		= $rs_cms_info03;
		}

		if ($rs_pay_type == "card") {
			$rs_card_code = $rs_cms_info01;
			$rs_card_no		= $rs_cms_info02;
			$rs_card_yy		= $rs_cms_info03;
			$rs_card_mm		= $rs_cms_info04;
			$rs_card_name	= $rs_cms_info05;
		}

		if ($rs_m_sex == "M") {
			$str_sex = "남자";
		} else {
			$str_sex = "여자";
		}

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당원정보 조회 (당원 번호 : ".$m_no.")", "Read");

	}


	if (($result)&&($mode!="D")) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&m_no=".$m_no."&mode=S&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&start_date=".$start_date."&end_date=".$end_date."&Ngroup_cd=".$Ngroup_cd;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "member_modify.php<?=$strParam?>";
</script>
<?
		exit;
	}elseif(($result)&&($mode=="D")){
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&m_no=".$m_no."&mode=S&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&start_date=".$start_date."&end_date=".$end_date."&Ngroup_cd=".$Ngroup_cd;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "member_list.php<?=$strParam?>";
</script>
<?
		exit;
	}
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />
<script src="http://code.jquery.com/jquery-1.6.min.js"></script>
<script src="../../js/jcarousellite_1.0.1.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script src="../js/common.js"></script>
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

<script language="javascript">

	function js_modify(seq) {

		var frm = document.frm;
		frm.m_no.value = seq;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "member_modify.php";
		frm.submit();
		
	}

	function js_delete() {
		
		bDelOK = confirm('정말 탈당처리 하시겠습니까?');//정말 삭제 하시겠습니까?
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.method = "post";
			frm.action = "member_modify.php";
			frm.submit();
		} else {
			return;
		}
	}


	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "quit_list.php";
		frm.submit();
	}


	function js_get_history_list(type, cnt, m_no) {
		
		if (type == "modify") {
			if (cnt == "ALL") {
				$("#btn_history_all").hide();
				$("#btn_history_5").show();
			} else {
				$("#btn_history_all").show();
				$("#btn_history_5").hide();
			}
		} else {
			if (cnt == "ALL") {
				$("#btn_payment_all").hide();
				$("#btn_payment_5").show();
			} else {
				$("#btn_payment_all").show();
				$("#btn_payment_5").hide();
			}
		}

		var mode = type;
		var request = $.ajax({
			url:"/manager/member/ajax_member_history.php",
			type:"POST",
			data:{mode:mode,cnt:cnt,m_no:m_no},
			dataType:"html"
		});

		request.done(function(msg) {
			
			if (type == "modify") {
				$("#modify_list").html(msg);
			} else {
				$("#payment_list").html(msg);
			}

			//alert(msg);
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});
	}

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
			<h2><?=$p_parent_menu_name?></h2>
		</div>

		<section class="conBox">

		<form name="frm" method="post" >
		<input type="hidden" name="mode" value="" >
		<input type="hidden" name="m_no" value="<?= $m_no ?>">
		<input type="hidden" name="m_id" id="m_id" value="<?=$rs_m_id?>" >
		<input type="hidden" name="search_field" value="<?= $search_field ?>">
		<input type="hidden" name="search_str" value="<?= $search_str ?>">

		<input type="hidden" name="order_field" value="<?= $order_field ?>">
		<input type="hidden" name="order_str" value="<?= $order_str ?>">
		
		<input type="hidden" name="nPage" value="<?=$nPage?>">
		<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

		<input type="hidden" name="sel_area_cd" value="<?=$sel_area_cd?>">
		<input type="hidden" name="sel_pay_type" value="<?=$sel_pay_type?>">

		<input type="hidden" name="sel_party" value="<?=$sel_party?>">
		<input type="hidden" name="is_agree" value="<?=$is_agree?>">

		<input type="hidden" name="start_date" value="<?=$start_date?>">
		<input type="hidden" name="end_date" value="<?=$end_date?>">

		<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
		<input type="hidden" name="use_tf" value="<?=$rs_use_tf?>">
		<input type="hidden" name="m_id_enabled" value="" id="m_id_enabled">
		<input type="hidden" name="m_nick_enabled" value="" id="m_nick_enabled">
		<input type="hidden" name="m_email_enabled" value="" id="m_email_enabled">

		<input type="hidden" name="Ngroup_cd" value="<?=$Ngroup_cd?>">
		<input type="hidden" name="sel_party" value="<?=$sel_party?>">
		<input type="hidden" name="online_flag" value="<?=$online_flag?>">
		</form>

		<h3 class="conTitle"><?=$p_parent_menu_name?></h3>
			<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
				<caption>게시판 생성</caption>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
					<tbody>
						<tr>
							<th>아이디</td>
							<td colspan="3">
								<?= $rs_m_id?>
							</td>
						</tr>
						<tr>
							<th>이름</td>
							<td><?= $rs_m_name ?></td>
							<th>주민등록번호</td>
							<td><?= $arrJumin[0] ?> -<?= $arrJumin[1] ?></td>
						</tr>

						<tr>
							<th>휴대폰번호</th>
							<td><?=$arr_m_tel[0]?> - <?=$arr_m_tel[1]?> - <?=$arr_m_tel[2]?></td>
							<th>연락처</th>
							<td><?=$arr_tel[0]?> - <?=$arr_tel[1]?> - <?=$arr_tel[2]?></td>
						</tr>

						<tr>
							<th>이메일</th>
							<td colspan="3"><?=$rs_m_email?></td>
						</tr>

						<tr>
							<th>성별</th>
							<td>
								<? if ($rs_m_sex == "1") { echo "남"; } ?>
								<? if ($rs_m_sex == "2") { echo "여"; } ?>
							</td>

							<th>생년월일</th>
							<td>
								<?=$rs_m_birth_yy?> 년 <?=$rs_m_birth_mm?> 월 <?=$rs_m_birth_dd?> 일
							</td>
						</tr>
						<tr>
							<th>주소</th>
							<td colspan="3">
								[<?=$rs_m_zip1?>] <?=$rs_m_addr1?> <?=$rs_m_addr2?>
							</td>
						</tr>

						<tr>
							<th>소속당</th>
							<td>
								<?=getDcodeName($conn,"PARTY", $rs_party)?>
							</td>
							<th>소속단체</th>
							<td>
								<?=$rs_group?>
							</td>
						</tr>

						<tr>
							<th>조직</th>
							<td colspan="3"><?=$Group_nm?>
							
							</td>
						</tr>

						<tr>
							<th>직업</th>
							<td><?=$rs_job?></td>
							<th>직장명</th>
							<td><?=$rs_com_name?></td>
						</tr>

						<tr>
							<th>직장주소</th>
							<td colspan="3">
								[<?=$rs_o_m_zip?>] <?=$rs_o_m_addr1?> <?=$rs_o_m_addr2?>
							</td>
						</tr>

						<tr class="end">
							<th>CMS 등록 구분</th>
							<td>
								<? if ($rs_cms_flag == "N") { ?>신규<? } ?>
								<? if ($rs_cms_flag == "U") { ?>수정<? } ?>
								<? if ($rs_cms_flag == "D") { ?>삭제<? } ?>
								<? if ($rs_cms_flag == "R") { ?>등록<? } ?>
								<? if ($rs_cms_flag == "F") { ?>오류<? } ?>
								&nbsp;&nbsp;
								<? if ($rs_send_flag == "0") { ?><? } ?>
								<? if ($rs_send_flag == "1") { ?><font color="red">심사중</font><? } ?>
							</td>
							<th>CMS 심사 결과</th>
							<td>
								<?=$rs_cms_result_msg?>
							</td>
						</tr>

						<tr>
							<th>가입형태</td>
							<td colspan="3">
								<?
								if($rs_m_online_flag=="off"){
									echo "오프라인";
								}elseif($rs_m_online_flag=="on"){
									echo "온라인";
								}
								?>
							</td>
						</tr>

						<tr  class="end">
							<th>관리자메모</td>
							<td colspan="3">
								<?=nl2br($rs_m_memo)?>
							</td>
						</tr>

					</table>


					<h3 class="conTitle">당비 정보</h3>
					<div class="expArea">
					<p class="fRight">
						<? if ($rs_send_flag == "1") { ?>
						<font color="red"><b>* CMS 심사승인 중인 당원 입니다. 심사 기간중 당비 정보는 수정 하실 수 없습니다. 당비 정보를 수정 하셔도 반영 되지 않습니다. (약정금액 수정 가능)</b></font>
						<? } else { ?>
							<? if ($rs_cms_flag == "R") { ?>
								<font color="red"><b>* CMS 카드 출금일 (25일) 휴대전화 (15일)</b></font><br/>
								<font color="red"><b>* 출금일이 영업일 기준 최소 4일 전일 경우만 수정 승인 처리 하셔야 합니다.</b></font>
							<? } ?>
						<? } ?>
					</p>
					</div>
					<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
						<caption>게시판 생성</caption>
						<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
						</colgroup>
						<tr>
							<th>당비납부 여부</th>
							<td>
								<? if ($rs_is_pay == "Y") { echo "약정함";} ?>
								<? if ($rs_is_pay == "N") { echo "약정 안함";} ?>
							</td>
							<th>당비납부 방법</th>
							<td>
								<? if ($rs_pay_type == "mobile") { echo "휴대폰";} ?>
								<? if ($rs_pay_type == "cms") { echo "CMS";} ?>
								<? if ($rs_pay_type == "card") { echo "신용카드";} ?>
								<? if ($rs_pay_type == "cash") { echo "직접납부";} ?>
							</td>
						</tr>
						<? 
							if ($rs_is_pay == "Y") {
								if ($rs_pay_type == "mobile") {
						?>
						<tr>
							<th>휴대폰 번호</th>
							<td>><?=$arr_pay_mtel[0]?> - <?=$arr_pay_mtel[1]?> - <?=$arr_pay_mtel[2]?></td>
							<th>통신사</th>
							<td><?=getDcodeName($conn,"MOBILE_COM",$rs_mobile_com)?></td>
						</tr>
						<?
								}
								if ($rs_pay_type == "cms") {
						?>
						<tr>
							<th>은행명</th>
							<td><?=getDcodeName($conn, "BANK_CODE",$rs_bank_code)?></td>
							<th>계좌번호</th>
							<td><?=$rs_bank_no?></td>
						</tr>
						<tr>
							<th>예금주</th>
							<td ><?=$rs_bank_name?></td>
							<th>생년월일</th>
							<td>
								<?=$rs_cms_birth_yy?> 년 <?=$rs_cms_birth_mm?> 월 <?=$rs_cms_birth_dd?> 일
							</td>
						</tr>
						<?
								}
								if ($rs_pay_type == "card") {
						?>
						<tr>
							<th>카드사</th>
							<td>><?=getDcodeName($conn, "CARD_CODE",$rs_card_code)?></td>
							<th>카드번호</th>
							<td><?=$rs_card_no?></td>
						</tr>
						<tr>
							<th>유효기간</th>
							<td colspan="3"><?=$rs_card_yy?> 년 <?=$rs_card_mm?> 월</td>
						</tr>
						<tr>
							<th>카드소유주</th>
							<td ><?=$rs_card_name?></td>
							<th>생년월일</th>
							<td>
								<?=$rs_cms_birth_yy?> 년 <?=$rs_cms_birth_mm?> 월 <?=$rs_cms_birth_dd?> 일
							</td>
						</tr>
						<?
								}
						?>
						<tr>
							<th>약정금액</th>
							<td>
								<?=getDcodeName($conn,"CMS_AMOUNT",$rs_cms_amount)?>
							</td>
							<th>동의서</th>
							<td>
								<? if (strlen($rs_m_signature) > 3) { ?>
								<font color="navy">O</font>
								<? } else { ?>
								<font color="red">X</font>
								<? } ?>
							</td>
						</tr>
						<tr>
							<th>당비출금일</th>
							<td colspan="3">
								<?=$rs_req_day?> 일
							</td>
						</tr>
						<?
							}
						?>
					</table>

					<div class="btnArea">
						<ul class="fRight">
							<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
						</ul>
					</div>
					

		<form id="bbsList">

		<?
			$arr_rs_history = listModifyHistoryMember($conn, $m_no, "5");
			
			if (sizeof($arr_rs_history) > 0) {
		?>
					<h3 class="conTitle">당원 수정 이력</h3>

					<div class="expArea">
					<p class="fRight">
						<input type="button" name="aa" id="btn_history_all" value=" 전체 보기 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;" onclick="js_get_history_list('modify','ALL','<?=$m_no?>');">
						<input type="button" name="aa" id="btn_history_5" value=" 최근 5 건 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;display:none" onclick="js_get_history_list('modify','5','<?=$m_no?>');">
					</p>
					</div>

					<table class="secBtm">

						<colgroup>
							<col width="8%" />
							<col width="72%" />
							<col width="8%" />
							<col width="12%" />
						</colgroup>

						<thead>
						<tr>
							<th>순서</th>
							<th>변경 내용</th>
							<th>변경 관리자</th>
							<th>변경일</th>
						</tr>
						</thead>
						<tbody id="modify_list">
						<?
							for ($j = 0 ; $j < sizeof($arr_rs_history); $j++) {
								
								$M_MEMO				= trim($arr_rs_history[$j]["M_MEMO"]);
								$ADM_NAME			= trim($arr_rs_history[$j]["ADM_NAME"]);
								$UP_DATE			= trim($arr_rs_history[$j]["UP_DATE"]);
								
								$DEC_M_MEMO		= decrypt($key, $iv, $M_MEMO);
						?>
						<tr>
							<td><?=sizeof($arr_rs_history) - $j?></td>
							<td style="text-align:left"><?=nl2br($DEC_M_MEMO)?></td>
							<td><?=$ADM_NAME?></td>
							<td><?=$UP_DATE?></td>
						</tr>
						<?
							}
						?>
						</tbody>
					</table>
		<?
			}
		?>

		<?
			$arr_rs_pay_history = listPaymentHistoryMember($conn, $m_no, "5");
			
			if (sizeof($arr_rs_pay_history) > 0) {
		?>
					<h3 class="conTitle">당비 납부 정보</h3>

					<div class="expArea">
					<p class="fRight">
						<input type="button" name="aa" id="btn_payment_all" value=" 전체 보기 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;" onclick="js_get_history_list('payment','ALL','<?=$m_no?>');">
						<input type="button" name="aa" id="btn_payment_5" value=" 최근 5 건 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;display:none" onclick="js_get_history_list('payment','5','<?=$m_no?>');">
					</p>
					</div>

					<table class="secBtm">

						<colgroup>
							<col width="10%" />
							<col width="9%" />
							<col width="9%" />
							<col width="9%" />
							<col width="9%" />
							<col width="9%" />
							<col width="9%" />
							<col width="9%" />
							<col width="9%" />
							<col width="9%" />
							<col width="9%" />
						</colgroup>

						<thead>
						<tr>
							<th>순서</th>
							<th>신청년월</th>
							<th>납부방법</th>
							<th>약정당비</th>
							<th>납부당비</th>
							<th>출금일</th>
							<th>출수수료</th>
							<th>환불금액</th>
							<th>출금상태</th>
							<th>처리결과</th>
							<th>출금신청일</th>
						</tr>
						</thead>
						<tbody id="payment_list">
						<?
							for ($j = 0 ; $j < sizeof($arr_rs_pay_history); $j++) {
								
								$M_NO							= trim($arr_rs_pay_history[$j]["M_NO"]);
								$M_ID							= trim($arr_rs_pay_history[$j]["M_ID"]);
								$M_NAME						= trim($arr_rs_pay_history[$j]["M_NAME"]);
								$SIDO							= trim($arr_rs_pay_history[$j]["SIDO"]);
								$PAY_YYYY					= trim($arr_rs_pay_history[$j]["PAY_YYYY"]);
								$PAY_MM						= trim($arr_rs_pay_history[$j]["PAY_MM"]);
								$PAY_TYPE					= trim($arr_rs_pay_history[$j]["PAY_TYPE"]);
								$CMS_AMOUNT				= trim($arr_rs_pay_history[$j]["CMS_AMOUNT"]);
								$RES_CMS_AMOUNT		= trim($arr_rs_pay_history[$j]["RES_CMS_AMOUNT"]);
								$RES_PAY_DATE			= trim($arr_rs_pay_history[$j]["RES_PAY_DATE"]);
								$RES_PAY_DATE			= trim($arr_rs_pay_history[$j]["RES_PAY_DATE"]);
								$CMS_CHARGE 			= trim($arr_rs_pay_history[$j]["CMS_CHARGE"]);
								$RES_PAY_NO				= trim($arr_rs_pay_history[$j]["RES_PAY_NO"]);
								$PAY_RESULT				= trim($arr_rs_pay_history[$j]["PAY_RESULT"]);
								$PAY_RESULT_CODE	= trim($arr_rs_pay_history[$j]["PAY_RESULT_CODE"]);
								$PAY_RESULT_MSG		= trim($arr_rs_pay_history[$j]["PAY_RESULT_MSG"]);
								$SEND_FLAG				= trim($arr_rs_pay_history[$j]["SEND_FLAG"]);
								$REFUND_AMOUNT		= trim($arr_rs_pay_history[$j]["REFUND_AMOUNT"]);
								$REG_DATE					= trim($arr_rs_pay_history[$j]["REG_DATE"]);

								if ($PAY_TYPE == "B") $str_pay_type = "CMS";
								if ($PAY_TYPE == "C") $str_pay_type = "신용카드";
								if ($PAY_TYPE == "H") $str_pay_type = "휴대폰";
								if ($PAY_TYPE == "D") $str_pay_type = "직접납부";

								if ($PAY_RESULT == "Y") {
									$str_color = "navy";
								} else {
									$str_color = "red";
								}
								

						?>
						<tr>
							<td><?=sizeof($arr_rs_pay_history) - $j?></td>
							<td><?=$PAY_YYYY?>-<?=$PAY_MM?></td>
							<td><?=$str_pay_type?></td>
							<td style="text-align:right;padding-right:20px"><?=number_format($CMS_AMOUNT)?></td>
							<td style="text-align:right;padding-right:20px"><?=number_format($RES_CMS_AMOUNT)?></td>
							<td><?=$RES_PAY_DATE?></td>
							<td style="text-align:right;padding-right:20px"><?=number_format($CMS_CHARGE)?></td>
							<td style="text-align:right;padding-right:20px"><?=number_format($REFUND_AMOUNT)?></td>
							<td><font color="<?=$str_color?>"><?=$PAY_RESULT?></font></td>
							<td><font color="<?=$str_color?>"><?=$PAY_RESULT_MSG?></font></td>
							<td><?=substr($REG_DATE ,0,10)?></td>
						</tr>
						<?
							}
						?>
						</tbody>
					</table>
		<?
			}
		?>


		</form>

				</section>

<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
<a href="javascript:check_dup();">ss</a>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>