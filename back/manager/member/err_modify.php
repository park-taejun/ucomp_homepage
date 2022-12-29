<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : member_list.php
# Modlue       : 
# Writer       : GIRINGRIM 
# Create Date  : 2011.01.19
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
	$menu_right = "MB005"; // 메뉴마다 셋팅 해 주어야 합니다

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

#====================================================================
# Request Parameter
#====================================================================

	$mm_subtree	 = "7";

	#List Parameter
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$mode 				= trim($mode);
	$m_no				= trim($m_no);
	
	$result = false;
#====================================================================
# DML Process
#====================================================================

	if ($mode == "U") {

		$arr_rs = selectMember($conn, $m_no);

		$rs_cms_flag				= trim($arr_rs[0]["CMS_FLAG"]);
		$rs_send_flag				= trim($arr_rs[0]["SEND_FLAG"]);
		$rs_cms_result			= trim($arr_rs[0]["CMS_RESULT"]);
		$rs_cms_result_msg	= trim($arr_rs[0]["CMS_RESULT_MSG"]);

		$rs_m_birth					= trim($arr_rs[0]["M_BIRTH"]); 

		$rs_m_5					= trim($arr_rs[0]["M_5"]);
		$rs_m_6					= trim($arr_rs[0]["M_6"]);
		$rs_m_7					= trim($arr_rs[0]["M_7"]);
		$rs_m_8					= trim($arr_rs[0]["M_8"]);
		$rs_m_9					= trim($arr_rs[0]["M_9"]);
		$rs_m_10				= trim($arr_rs[0]["M_10"]);
		$rs_m_11				= trim($arr_rs[0]["M_11"]);
		$rs_m_12				= trim($arr_rs[0]["M_12"]);
		$rs_m_13				= trim($arr_rs[0]["M_13"]);
		$rs_req_day			= trim($arr_rs[0]["REQ_DAY"]);
		$rs_cms_birth		= trim($arr_rs[0]["M_CMS_BIRTH"]);

		$rs_cms_birth_yy = left($rs_cms_birth,4);
		$rs_cms_birth_mm = right(left($rs_cms_birth,6),2);
		$rs_cms_birth_dd = right($rs_cms_birth,2);

		$rs_m_memo			= trim($arr_rs[0]["M_MEMO"]);

		$old_str = $rs_m_5.$rs_m_6.$rs_m_7.$rs_m_8.$rs_m_9.$rs_m_10.$rs_m_11.$rs_m_12.$rs_m_13.$rs_req_day.$rs_cms_birth;

		if ($cms_amount=="etc"){
			$cms_amount=$m_13;
			$m_13="etc";
		}

		if($card_birth<>""){
			$c_birth=$card_birth;
		}

	#====================================================================
		$savedir1 = $g_physical_path."upload_data/member";
	#====================================================================

		switch ($flag) {
			case "insert" :
				$m_signature = upload($_FILES[file_nm], $savedir1, "3000", array('jpg'));

				if ($m_signature <> "") {
					$img_link = $g_site_url."/upload_data/member/".$m_signature;
					create_thumbnail($img_link, $g_physical_path."upload_data/member_resize/".$m_signature,"480");
				}

				// 동의서 파일 등록
				$str_id = right("00000000".$m_no, 8);
				if ($m_signature <> "") {
					system("cp /home/httpd/html/upload_data/member_resize/".$m_signature." /home/payinfo-thebill/data/send/30040111.".$str_id.".1.jpg");
				}

				break;

			case "keep" :
				$m_signature	= $old_file_nm;

				$str_id = right("00000000".$m_no, 8);
				if ($m_signature <> "") {
					system("cp /home/httpd/html/upload_data/member_resize/".$m_signature." /home/payinfo-thebill/data/send/30040111.".$str_id.".1.jpg");
				}
				
			break;
			case "delete" :
				$m_signature		= "";
				break;
			case "update" :
				
				$m_signature = upload($_FILES[file_nm], $savedir1, "3000", array('jpg'));
				if ($m_signature <> "") {
					$img_link = $g_site_url."/upload_data/member/".$m_signature;
					//echo $img_link;
					//echo $g_physical_path."upload_data/member_resize/";
					create_thumbnail($img_link, $g_physical_path."upload_data/member_resize/".$m_signature,"480");
				}

				// 동의서 파일 등록
				$str_id = right("00000000".$m_no, 8);
				if ($m_signature <> "") {
					system("cp /home/httpd/html/upload_data/member_resize/".$m_signature." /home/payinfo-thebill/data/send/30040111.".$str_id.".1.jpg");
				}

				break;
		}

		$new_nick_date = "";
		$en_m_password	=  encrypt($key, $iv, $m_reg02);

		$str_m_jumin		= $m_reg01."-".$m_reg02;
		$en_m_jumin			=  encrypt($key, $iv, $str_m_jumin);

		$str_mtel				= $mtel_01."-".$mtel_02."-".$mtel_03;
		$en_mtel				=  encrypt($key, $iv, $str_mtel);

		$en_cms_info01	=  encrypt($key, $iv, $cms_info01);
		$en_cms_info02	=  encrypt($key, $iv, $cms_info02);
		$en_cms_info03	=  encrypt($key, $iv, $cms_info03);
		$en_cms_info04	=  encrypt($key, $iv, $cms_info04);
		$en_cms_info05	=  encrypt($key, $iv, $cms_info05);

		if ($is_pay <> "Y") {
			$is_pay					= "N";
			$en_cms_info01	= "";
			$en_cms_info02	= "";
			$en_cms_info03	= "";
			$en_cms_info04	= "";
			$en_cms_info05	= "";
		}

		$new_str = $is_pay.$pay_type.$en_cms_info01.$en_cms_info02.$en_cms_info03.$en_cms_info04.$en_cms_info05.$cms_amount.$m_13.$cms_pay_day.$c_birth;

		if ($m_email_01 <> "") {
			$m_email = $m_email_01."@".$m_email_02;
		}
		
		$str_update		= date("Y-m-d H:i:s",strtotime("0 month"));
		
		if ($rs_m_memo == "") { 
			$m_memo = $str_update." : ".$rs_cms_result_msg;
		} else {
			$m_memo = $rs_m_memo."\n".$str_update." : ".$rs_cms_result_msg;
		}
		
		$str_cms_flag		= "N";
		$str_send_flag	= "0";
		$str_cms_result	= "";

		$arr_data = array("M_NAME"=>$m_name,
											"M_JUMIN"=>$en_m_jumin,
											"M_EMAIL"=>$m_email,
											"M_HP"=>$en_mtel,
											"M_SIGNATURE"=>$m_signature,
											"M_PROFILE"=>$m_profile,
											"M_MEMO"=>$m_memo,
											"M_6"=>$pay_type,
											"M_7"=>$en_cms_info01,
											"M_8"=>$en_cms_info02,
											"M_9"=>$en_cms_info03,
											"M_10"=>$en_cms_info04,
											"M_11"=>$en_cms_info05,
											"M_12"=>$cms_amount,
											"M_13"=>$m_13,
											"M_CMS_BIRTH"=>$c_birth,
											"REQ_DAY"=>$cms_pay_day,
											"CMS_FLAG"=>$str_cms_flag,
											"SEND_FLAG"=>$str_send_flag,
											"CMS_RESULT"=>$str_cms_result
											);

		$result =  updateMember($conn, $arr_data, $en_m_password, $new_nick_date, $m_level, $m_id);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "CMS 등록오류 수정", "Update");

	}

	if ($mode == "S") {

		$arr_rs = selectMember($conn, $m_no);
		
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
		$rs_req_day					= trim($arr_rs[0]["REQ_DAY"]);

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
			$rs_card_name		= $rs_cms_info05;
		}
	}

	if (($result)&&($mode!="D")) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&m_no=".$m_no."&mode=S&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&Ngroup_cd=".$Ngroup_cd;
?>	
<script language="javascript">
		alert('정보 변경 후 재등록 요청 접수 되었습니다.');
		document.location.href = "err_list.php<?=$strParam?>";
</script>
<?
		exit;
	}elseif(($result)&&($mode=="D")){
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&m_no=".$m_no."&mode=S&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&Ngroup_cd=".$Ngroup_cd;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "err_list.php<?=$strParam?>";
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

	function js_check_data() {

		var frm = document.frm;
		var m_no = "<?=$rs_m_no?>";

		frm.mode.value = "U";

		if (frm.m_name.value.trim() == "") {
			 alert("이름을 입력해주십시오.");
			frm.m_name.focus();
			return;
		}

		if (frm.m_reg01.value.trim() == "") {
			 alert("주민등록번호를 입력해주십시오.");
			frm.m_reg01.focus();
			return;
		}

		if (frm.m_reg02.value.trim() == "") {
			 alert("주민등록번호를 입력해주십시오.");
			frm.m_reg02.focus();
			return;
		}

		if (frm.mtel_02.value.trim() == "") {
			 alert("휴대폰번호를 입력해주십시오.");
			frm.mtel_02.focus();
			return;
		}

		if (frm.mtel_03.value.trim() == "") {
			 alert("휴대폰번호를 입력해주십시오.");
			frm.mtel_03.focus();
			return;
		}

		var is_pay = "Y";
		var pay_type = $('input:radio[name=rd_pay_type]:checked').val();
		var cms_mobile = $("#pay_mtel_01").val()+"-"+$("#pay_mtel_02").val()+"-"+$("#pay_mtel_03").val();
		var cms_info01 = "";
		var cms_info02 = "";
		var cms_info03 = "";
		var cms_info04 = "";
		var cms_info05 = "";
		var cms_amount = $('input:radio[name=cms_amount]:checked').val();

		if (is_pay == "Y") {

			if (pay_type == null) {
				alert("결제방식을 선택 하세요.");
				return;
			}

			if (pay_type == "mobile") {
			
				cms_info01 = cms_mobile;
				cms_info02 = $('input:radio[name=mobile_com]:checked').val();

				if ($("#pay_mtel_02").val().trim() == "") {
					alert("전화번호를 입력하세요.");
					$("#pay_mtel_02").focus();
					return;
				}

				if ($("#pay_mtel_03").val().trim() == "") {
					alert("전화번호를 입력하세요.");
					$("#pay_mtel_03").focus();
					return;
				}

				if (cms_info02 == null) {
					alert("통신사를 선택하세요.");
					return;
				}
			}

			if (pay_type == "cms") {
				cms_info01 = $("#bank_code").val();
				cms_info02 = $("#bank_no").val();
				cms_info03 = $("#bank_name").val();

				if (cms_info01 == "") {
					alert("은행을 선택하세요.");
					$("#bank_code").focus();
					return;
				}

				if (cms_info02 == "") {
					alert("계좌번호를 입력하세요.");
					$("#bank_no").focus();
					return;
				}

				if (cms_info03 == "") {
					alert("예금주를 입력하세요.");
					$("#bank_name").focus();
					return;
				}

				if (frm.c_sel_year.value.trim() == "") {
					alert("년을 선택해주십시오.");
					frm.c_sel_year.focus();
					return;
				}

				if (frm.c_sel_month.value.trim() == "") {
					alert("월을 선택해주십시오.");
					frm.c_sel_month.focus();
					return;
				}

				if (frm.c_sel_day.value.trim() == "") {
					alert("일을 선택해주십시오.");
					frm.c_sel_day.focus();
					return;
				}

				frm.c_birth.value = frm.c_sel_year.value+""+frm.c_sel_month.value+""+frm.c_sel_day.value;

			}

			if (pay_type == "card") {

				cms_info01 = $("#card_code").val();
				cms_info02 = $("#card_no").val();
				cms_info03 = $("#card_yy").val();
				cms_info04 = $("#card_mm").val();
				cms_info05 = $("#card_name").val();

				if (cms_info01 == "") {
					alert("카드사를 선택하세요.");
					$("#card_code").focus();
					return;
				}

				if (cms_info02 == "") {
					alert("카드번호를 선택하세요.");
					$("#card_no").focus();
					return;
				}

				if (cms_info03 == "") {
					alert("유효기간 년을 선택하세요.");
					$("#card_yy").focus();
					return;
				}

				if (cms_info04 == "") {
					alert("유효기간 월을 선택하세요.");
					$("#card_mm").focus();
					return;
				}

				if (cms_info05 == "") {
					alert("카드 소유주 이름을 입력하세요.");
					$("#card_name").focus();
					return;
				}

				if (frm.card_sel_year.value.trim() == "") {
					alert("년을 선택해주십시오.");
					frm.card_sel_year.focus();
					return;
				}

				if (frm.card_sel_month.value.trim() == "") {
					alert("월을 선택해주십시오.");
					frm.card_sel_month.focus();
					return;
				}

				if (frm.card_sel_day.value.trim() == "") {
					alert("일을 선택해주십시오.");
					frm.card_sel_day.focus();
					return;
				}

				frm.card_birth.value = frm.card_sel_year.value+""+frm.card_sel_month.value+""+frm.card_sel_day.value;

			}

			if (cms_amount == null) {
				alert("당비약정금액을 선택하세요.");
				return;
			}else{
				if (cms_amount == "etc") {
					if (frm.m_13.value.trim() == "") {
						alert("약정금액을 입력해주세요.");
						frm.m_13.focus();
						return;
					}
				}
			}
			
			frm.is_pay.value		 = is_pay;
			frm.pay_type.value	 = pay_type;
			frm.cms_info01.value = cms_info01;
			frm.cms_info02.value = cms_info02;
			frm.cms_info03.value = cms_info03;
			frm.cms_info04.value = cms_info04;
			frm.cms_info05.value = cms_info05;
			frm.cms_amount.value = cms_amount;

		}
		
		frm.target = "";
		frm.action = "../member/err_modify.php";
		frm.submit();

	}

	function js_sel_email() {
		var frm = document.frm;
		frm.m_email_02.value = frm.sel_email.value;
	}

	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "err_list.php";
		frm.submit();
	}

	function js_pay_type(pay_type) {
		
		$("#mobile_01").hide();
		$("#mobile_02").hide();
		$("#cms_01").hide();
		$("#cms_02").hide();
		$("#cms_03").hide();
		$("#cms_04").hide();
		$("#card_01").hide();
		$("#card_02").hide();
		$("#card_03").hide();
		$("#card_04").hide();
		$("#card_05").hide();

		if (pay_type == "mobile") {
			$("#mobile_01").show();
			$("#mobile_02").show();
		}

		if (pay_type == "cms") {
			$("#cms_01").show();
			$("#cms_02").show();
			$("#cms_03").show();
			$("#cms_04").show();
		}

		if (pay_type == "card") {
			$("#card_01").show();
			$("#card_02").show();
			$("#card_03").show();
			$("#card_04").show();
			$("#card_05").show();
		}

	}

	$(document).ready(function() {

		js_pay_type("<?=$rs_pay_type?>");

	});


	function js_fileView(obj) {
	
		var frm = document.frm;
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change").style.display = "inline";
		} else {
			document.getElementById("file_change").style.display = "none";
		}
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
		<form name="frm" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="" >
		<input type="hidden" name="m_no" value="<?= $m_no ?>">
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
		<input type="hidden" name="Ngroup_cd" value="<?=$Ngroup_cd?>">

		<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
		<input type="hidden" name="use_tf" value="<?=$rs_use_tf?>">
		<input type="hidden" name="m_id_enabled" value="" id="m_id_enabled">
		<input type="hidden" name="m_nick_enabled" value="" id="m_nick_enabled">
		<input type="hidden" name="m_email_enabled" value="" id="m_email_enabled">

		<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
				<caption>게시판 생성</caption>
					<colgroup>
						<col width="20%" />
						<col width="80%" />
					</colgroup>
					<tbody>
						<tr>
							<th>아이디</td>
							<td>
								<?= $rs_m_id?>
								<input type="hidden" name="m_id" id="m_id" value="<?=$rs_m_id?>" maxLength="20">
							</td>
						</tr>
						<tr>
							<th>이름</td>
							<td>
								<input type="Text" name="m_name" value="<?= $rs_m_name ?>" style="width:95px;" class="txt">
							</td>
						</tr>
						<tr>
							<th>주민등록번호</td>
							<td>
								<input type="Text" name="m_reg01" value="<?= $arrJumin[0] ?>" style="width:95px;" maxlength="6" class="txt"> -
								<input type="Text" name="m_reg02" value="<?= $arrJumin[1] ?>" style="width:95px;" maxlength="7" class="txt">
							</td>
						</tr>

						<tr>
							<th>휴대폰번호</th>
							<td>
								<?=makeSelectBox($conn, "MOBILE","mtel_01","80","","",$arr_m_tel[0])?> -
								<input type="text" id="mtel_02" name="mtel_02" maxlength="4" style="width:80px" value="<?=$arr_m_tel[1]?>"> - 
								<input type="text" id="mtel_03" name="mtel_03" maxlength="4" style="width:80px" value="<?=$arr_m_tel[2]?>">
							</td>
						</tr>

						<tr>
							<th>이메일</th>
							<td>
								<input type="hidden" name='old_email' id="old_email" value='<?=$rs_m_email?>'>
								<input type="text" name="m_email_01" id="m_email_01" value="<?=$arr_m_email[0]?>" onchange="frm.m_email_enabled.value='';" style="width:100px;"/> @ 
								<input type="text" name="m_email_02" id="m_email_02" value="<?=$arr_m_email[1]?>" onchange="frm.m_email_enabled.value='';" style="width:100px;"/>
								<?= makeSelectBoxOnChange($conn,"EMAIL","sel_email","150","직접입력","",$arr_m_email[1])?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="hidden" name ="m_email" id="m_email" value="<?=$rs_m_email?>">
							</td>
						</tr>

						<tr>
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
						</tr>
						<tr class="end">
							<th>CMS 심사 결과</th>
							<td>
								<b><font color="red"><?=$rs_cms_result_msg?></font></b>
							</td>
						</tr>

					</table>

		<input type="hidden" name="is_pay" value="">
		<input type="hidden" name="pay_type" value="">
		<input type="hidden" name="cms_info01" value="">
		<input type="hidden" name="cms_info02" value="">
		<input type="hidden" name="cms_info03" value="">
		<input type="hidden" name="cms_info04" value="">
		<input type="hidden" name="cms_info05" value="">
		<input type="hidden" name="cms_amount" value="">

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
							<col width="20%" />
							<col width="80%" />
						</colgroup>
						<tr>
							<th>당비납부 방법</th>
							<td>
								<!--<input type="radio" name="rd_pay_type" value="mobile" class="radio" onClick="js_pay_type('mobile')" <? if ($rs_pay_type == "mobile") { echo "checked";} ?> /> 휴대폰&nbsp;&nbsp;&nbsp;-->
								<input type="radio" name="rd_pay_type" value="cms" class="radio" onClick="js_pay_type('cms')" <? if ($rs_pay_type == "cms") { echo "checked";} ?>/> CMS&nbsp;&nbsp;&nbsp;
								<input type="radio" name="rd_pay_type" value="card" class="radio" onClick="js_pay_type('card')"  <? if ($rs_pay_type == "card") { echo "checked";} ?> /> 신용카드&nbsp;&nbsp;&nbsp;
							</td>
						</tr>

						<tr id="mobile_01" style="display:none">
							<th>휴대폰 번호</th>
							<td>
								<?=makeSelectBox($conn, "MOBILE","pay_mtel_01","80","","",$arr_pay_mtel[0])?> -
								<input type="text" id="pay_mtel_02" name="pay_mtel_02" maxlength="4" style="width:80px" value="<?=$arr_pay_mtel[1]?>"> - 
								<input type="text" id="pay_mtel_03" name="pay_mtel_03" maxlength="4" style="width:80px" value="<?=$arr_pay_mtel[2]?>">
							</td>
						</tr>
						<tr id="mobile_02" style="display:none">
							<th>통신사</th>
							<td>
								<?=makeRadioBox($conn,"MOBILE_COM","mobile_com", $rs_mobile_com)?>
							</td>
						</tr>

						<tr id="cms_01" style="display:none">
							<th>은행명</th>
							<td>
								<?=makeSelectBox($conn, "BANK_CODE","bank_code","200","은행을 선택하세요.","",$rs_bank_code)?>
							</td>
						</tr>
						<tr id="cms_02" style="display:none">
							<th>계좌번호</th>
							<td>
								<input type="text" id="bank_no" name="bank_no" style="width:200px" onkeyup="return isNumber(this)" value="<?=$rs_bank_no?>">
							</td>
						</tr>
						<tr id="cms_03" style="display:none">
							<th>예금주</th>
							<td>
								<input type="text" id="bank_name" name="bank_name" style="width:200px" value="<?=$rs_bank_name?>">
							</td>
						</tr>

						<tr id="cms_04" style="display:none">
							<th>생년월일</th>
							<td>
								<select name="c_sel_year" style="width:100px;">
									<option value="">년</option>
										<?
											$end_yy = date("Y",strtotime("0 day"));

											for ($k = $end_yy; $k > ($end_yy-110) ; $k--) {
										?>
									<option value="<?=$k?>" <?if($rs_cms_birth_yy==$k){?>selected<?}?>><?=$k?></option>
										<?
											}
										?>
								</select> 
								<?= makeSelectBox($conn,"MONTH","c_sel_month","100","월","",$rs_cms_birth_mm)?>
								<?= makeSelectBox($conn,"DAY","c_sel_day","100","일","",$rs_cms_birth_dd)?>
								<input type="hidden" name="c_birth" value="">
							</td>
						</tr>

						<tr id="card_01" style="display:none">
							<th>카드사</th>
							<td>
								<?=makeSelectBox($conn, "CARD_CODE","card_code","200","카드사을 선택하세요.","",$rs_card_code)?>
							</td>
						</tr>
						<tr id="card_02" style="display:none">
							<th>카드번호</th>
							<td>
								<input type="text" id="card_no" name="card_no" style="width:200px" onkeyup="return isNumber(this)" value="<?=$rs_card_no?>">
							</td>
						</tr>
						<tr id="card_03" style="display:none">
							<th>유효기간</th>
							<td>
								<input type="text" id="card_yy" name="card_yy" style="width:20px" maxlength="2" onkeyup="return isNumber(this)" value="<?=$rs_card_yy?>"> 년
								<input type="text" id="card_mm" name="card_mm" style="width:20px" maxlength="2" onkeyup="return isNumber(this)" value="<?=$rs_card_mm?>"> 월
							</td>
						</tr>

						<tr id="card_04" style="display:none">
							<th>카드소유주 이름</th>
							<td>
								<input type="text" id="card_name" name="card_name" style="width:200px" value="<?=$rs_card_name?>">
							</td>
						</tr>

						<tr id="card_05" style="display:none">
							<th>생년월일</th>
							<td>
								<select name="card_sel_year" style="width:100px;">
									<option value="">년</option>
										<?
											$end_yy = date("Y",strtotime("0 day"));

											for ($k = $end_yy; $k > ($end_yy-110) ; $k--) {
										?>
									<option value="<?=$k?>" <?if($rs_cms_birth_yy==$k){?>selected<?}?>><?=$k?></option>
										<?
											}
										?>
								</select> 
								<?= makeSelectBox($conn,"MONTH","card_sel_month","100","월","",$rs_cms_birth_mm)?>
								<?= makeSelectBox($conn,"DAY","card_sel_day","100","일","",$rs_cms_birth_dd)?>
								<input type="hidden" name="card_birth" value="">
							</td>
						</tr>


						<tr>
							<th>약정금액</th>
							<td>
								<?=makeRadioBox($conn,"CMS_AMOUNT","cms_amount", $rs_cms_amount)?>
								<div class="sp10"></div>
								<input type = 'radio' style='width:15px' class='chk' name= 'cms_amount' value='etc' <?if($rs_m_13=="etc"){?>checked<?}?>> 기타 : 
								<input type="text" name="m_13" style="width:80px" <?if($rs_m_13=="etc"){?>value="<?=$rs_cms_amount?>"<?}?>> 원
							</td>
						</tr>

						<tr>
							<th>당비출금일</th>
							<td>
								<?=makeRadioBox($conn,"CMS_PAY_DAY","cms_pay_day", $rs_req_day)?>
							</td>
						</tr>

						<tr class="end">
							<th>동의서</th>
							<td>
								<?
									if (strlen($rs_m_signature) > 3) {
								?>
								<a href="/_common/new_download_file.php?menu=member&m_no=<?= $m_no ?>"><?=$rs_m_signature?></a>&nbsp;&nbsp;
								<select name="flag" style="width:70px;" onchange="javascript:js_fileView(this)">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
								<input type="hidden" name="old_file_nm" value="<?= $rs_m_signature?>">
								<div id="file_change" style="display:none;">
									<input type="file" id="file_nm" name="file_nm" style="width:60%;border:none"/>
								</div>
								<?
									} else {
								?>
								<input type="file" name="file_nm" style="width:60%;border:none">
								<input TYPE="hidden" name="flag" value="insert">
								<?
									}
								?>
							</td>
						</tr>
					</table>


					<div class="btnArea">
						<ul class="fRight">
							<? 
								if ($sPageRight_I == "Y") {
									echo '<li><a href="javascript:js_check_data();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>';
								}
							?>
							<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
						</ul>
					</div>
				</section>

<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</form>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>