<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 

//error_reporting(E_ALL);
//ini_set("display_errors", 1);


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
	$menu_right = "MB002"; // 메뉴마다 셋팅 해 주어야 합니다

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


	if ($mode == "I") {

	#====================================================================
		$savedir1 = $g_physical_path."upload_data/member";
	#====================================================================

		$m_signature = upload($_FILES[file_nm], $savedir1, "20", array('jpg'));
		
		if ($m_signature <> "") {
			$img_link = $g_site_url."/upload_data/member/".$m_signature;
			create_thumbnail($img_link, $g_physical_path."upload_data/member_resize/".$m_signature,"480");
		}

		/*
		echo $m_reg02."<br>";
		echo $m_name."<br>";
		echo $m_reg01."<br>";
		echo $m_reg02."<br>";
		echo $m_sex."<br>";
		echo $tel_01."<br>";
		echo $tel_02."<br>";
		echo $tel_03."<br>";
		echo $mtel_01."<br>";
		echo $mtel_02."<br>";
		echo $mtel_03."<br>";
		echo $postcode."<br>";
		echo $address."<br>";
		echo $address2."<br>";
		echo $job."<br>";
		echo $com_name."<br>";
		echo $party."<br>";
		echo $group."<br>";

		echo $sido."<br>";
		echo $sigungu."<br>";

		echo $is_pay."<br>";
		echo $pay_type."<br>";

		echo $cms_info01."<br>";
		echo $cms_info02."<br>";
		echo $cms_info03."<br>";
		echo $cms_info04."<br>";
		echo $cms_info05."<br>";
		echo $cms_amount."<br>";
		*/

		$en_m_password	=  encrypt($key, $iv, $m_reg02);
		$str_m_jumin		= $m_reg01."-".$m_reg02;
		$en_m_jumin			=  encrypt($key, $iv, $str_m_jumin);
		$str_tel				= $tel_01."-".$tel_02."-".$tel_03;
		$en_tel					=  encrypt($key, $iv, $str_tel);
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

		if ($m_email_01 <> "") {
			$m_email = $m_email_01."@".$m_email_02;
		}

		if ($cms_amount=="etc"){
			$cms_amount=$m_13;
			$m_13="etc";
		}
		
		if($card_birth<>""){
			$c_birth=$card_birth;
		}
		

		$arr_data = array("M_NAME"=>$m_name,
											"M_JUMIN"=>$en_m_jumin,
											"M_PASSWORD"=>$m_reg02,
											"M_SEX"=>$m_sex,
											"M_BIRTH"=>$m_birth,
											"M_NICK_DATE"=>date("Y-m-d",strtotime("0 day")),
											"M_EMAIL"=>$m_email,
											"M_MAILLING"=>$m_mailling,
											"M_TEL"=>$en_tel,
											"M_HP"=>$en_mtel,
											"M_SMS"=>$m_sms,
											"M_ZIP1"=>$postcode,
											"M_ADDR1"=>$address,
											"M_ADDR2"=>$address2,
											"M_O_ZIP"=>$o_postcode,
											"M_O_ADDR1"=>$o_address,
											"M_O_ADDR2"=>$o_address2,
											"M_ORGANIZATION"=>$group_cd,
											"M_ONLINE_FLAG"=>$m_online_flag,
											"M_SIGNATURE"=>$m_signature,
											"M_PROFILE"=>$m_profile,
											"M_TODAY_LOGIN"=>date("Y-m-d H:i:s",strtotime("0 day")),
											"M_DATETIME"=>date("Y-m-d H:i:s",strtotime("0 day")),
											"M_IP"=>$_SERVER[REMOTE_ADDR],
											"M_LEVEL"=>$g_register_level,
											"M_LOGIN_IP"=>$_SERVER[REMOTE_ADDR],
											"M_OPEN_DATE"=>date("Y-m-d",strtotime("0 day")),
											"M_1"=>$job,
											"M_2"=>$com_name,
											"M_3"=>$party,
											"M_4"=>$group,
											"M_5"=>$is_pay,
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
											"SIDO"=>$sido,
											"SIGUNGU"=>$sigungu,
											"M_MEMO"=>$m_memo,
											"M_EMAIL_CERTIFY"=>date("Y-m-d H:i:s",strtotime("0 day")));

		$new_mem_no =  insertMember($conn, $arr_data);
		$str_id = right("00000000".$new_mem_no, 8);
		$result = updateMemberID($conn, $str_id, $new_mem_no);

		// 동의서 파일 등록
		if ($m_signature <> "") {
			system("cp /home/httpd/html/upload_data/member_resize/".$m_signature." /home/payinfo-thebill/data/send/30040111.".$str_id.".1.jpg");
		}

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당원 등록 (당원 번호 : ".$new_mem_no.")", "Insert");


	}

	if ($new_mem_no) {
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
<link href="../js/jquery-ui.min.css" rel="stylesheet" />
<link href="../css/jquery-ui.css" type="text/css" media="all" rel="stylesheet"  />
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
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
	
	function js_list() {
		var frm = document.frm;
		
		frm.method = "post";
		frm.target = "";
		frm.action = "member_list.php";
		frm.submit();
	}

	function chk_realname() {
		
		var frm = document.frm;

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

		var m_name = frm.m_name.value.trim();
		var m_reg01 = frm.m_reg01.value.trim();
		var m_reg02 = frm.m_reg02.value.trim();

		// 실명인증
		var request = $.ajax({
			url:"/ajax/realname_dml.php",
			type:"POST",
			data:{m_name:m_name, m_reg01:m_reg01, m_reg02:m_reg02},
			dataType:"html"
		});

		

		request.done(function(msg) {
	
			if (msg == "1") {
				js_check_data();
				return true;

			} else if (msg == "2") {
				var URL ="https://www.namecheck.co.kr/front/personal/register_online.jsp?menu_num=1&page_num=0&page_num_1=0";
				var status = ""; 
				window.open(URL,"",status); 
				return false;

			} else if (msg == "3") {
				
				var URL ="https://www.namecheck.co.kr/front/personal/register_online.jsp?menu_num=1&page_num=0&page_num_1=0";
				var status = ""; 
				window.open(URL,"",status); 
				return false;

			} else if (msg == "50") {
				
				var URL ="https://www.credit.co.kr/ib20/mnu/BZWPNSOUT01"; 
				var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
				window.open(URL,"",status); 
				return false;
			
			} else if (msg == "DUP") {
				alert("해당 주민번호로 등록된 당원 정보가 있습니다.");
				return false;
			} else {
				alert("실명 인증에 실패 하였습니다.");
				return false;
			}

		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});
	}


	function js_check_data() {

		var frm = document.frm;
		var m_no = "<?=$rs_m_no?>";
		
		if (m_no == "") {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}

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


		if (frm.sel_year.value.trim() == "") {
			alert("년을 선택해주십시오.");
			frm.sel_year.focus();
			return;
		}

		if (frm.sel_month.value.trim() == "") {
			alert("월을 선택해주십시오.");
			frm.sel_month.focus();
			return;
		}

		if (frm.sel_day.value.trim() == "") {
			alert("일을 선택해주십시오.");
			frm.sel_day.focus();
			return;
		}

		frm.m_birth.value = frm.sel_year.value+""+frm.sel_month.value+""+frm.sel_day.value;
		//alert(frm.m_birth.value);

		if (document.frm.rd_m_sex == null) {
		} else {
			if (frm.rd_m_sex[0].checked == true) {
				frm.m_sex.value = "1";
			} else {
				frm.m_sex.value = "2";
			}
		}

		var party = $('input:radio[name=party]:checked').val();
		var job = $("#job").val();
		var com_name = $("#com_name").val();
		var group = $("#group").val();

		if (party == null) {
			alert("소속정당을 선택하세요.\n없을 경우 없음을 선택하세요.");
			return;
		}

		//if (party!="없음") {
		//if(frm.group_cd.value==""){
		//	alert('조직 (지역)을 선택해주세요.');
		//	return;
		//}
		//}

		
		var is_pay = $('input:radio[name=rd_is_pay]:checked').val();
		var pay_type = $('input:radio[name=rd_pay_type]:checked').val();
		var cms_mobile = $("#pay_mtel_01").val()+"-"+$("#pay_mtel_02").val()+"-"+$("#pay_mtel_03").val();
		var cms_info01 = "";
		var cms_info02 = "";
		var cms_info03 = "";
		var cms_info04 = "";
		var cms_info05 = "";
		var cms_amount = $('input:radio[name=cms_amount]:checked').val();
		var cms_pay_day = $('input:radio[name=cms_pay_day]:checked').val();
		

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

			if (cms_pay_day == null) {
				alert("당비출금일을 선택하세요.");
				return;
			}

			frm.is_pay.value			= is_pay;
			frm.pay_type.value		= pay_type;
			frm.cms_info01.value	= cms_info01;
			frm.cms_info02.value	= cms_info02;
			frm.cms_info03.value	= cms_info03;
			frm.cms_info04.value	= cms_info04;
			frm.cms_info05.value	= cms_info05;
			frm.cms_amount.value	= cms_amount;
			frm.cms_pay_day.value = cms_pay_day;


		}

		frm.target = "";
		frm.action = "../member/member_write.php";
		frm.submit();

	}

	function js_sel_email() {
		var frm = document.frm;
		frm.m_email_02.value = frm.sel_email.value;
	}

	//var pay_type = "";

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


	function sample6_execDaumPostcode(str) {
		new daum.Postcode({
			oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}
			
			if(str=="o"){
			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById('o_postcode').value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById('o_address').value = fullAddr;
			// 커서를 상세주소 필드로 이동한다.
			document.getElementById('o_address2').focus();
			}else{
			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById('postcode').value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById('address').value = fullAddr;
			document.getElementById('sido').value = data.sido;
			document.getElementById('sigungu').value = data.sigungu;

			// 커서를 상세주소 필드로 이동한다.
			document.getElementById('address2').focus();
			}

			
		}
		}).open();
	}


	function chk_file_size() {
		var frm = document.frm;

		if (frm.file_nm.value != "") {
			frm.target = "ifr_hidden";
			frm.action = "chk_file_size.php";
			frm.submit();
		}

	}

	function js_party(group_cd_01, group_cd_02, group_cd_03, group_cd_04) {
		
		var frm = document.frm;
		var party_val = "";

		for (i=0 ; i < frm.party.length ; i++) {
			if (frm.party[i].checked == true) {
				party_val = frm.party[i].value;
			}
		}

		if (party_val=="") {
			$("#add_group").hide();
		
		} else {
		
			var request = $.ajax({
				url:"/_common/get_next_group_in.php",
				type:"POST",
				data:{party_val:party_val, group_cd_01:group_cd_01, group_cd_02:group_cd_02, group_cd_03:group_cd_03, group_cd_04:group_cd_04},
				dataType:"html"
			});

			request.done(function(msg) {
				//alert(msg);
				$("#group_div").html(msg);
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});

			$("#add_group").show();
		}

		$("#group_cd").val("");
		if ((group_cd_01 != "") && (group_cd_01 != null)) $("#group_cd").val(group_cd_01);
		if ((group_cd_02 != "") && (group_cd_02 != null)) $("#group_cd").val(group_cd_02);
		if ((group_cd_03 != "") && (group_cd_03 != null)) $("#group_cd").val(group_cd_03);
		if ((group_cd_04 != "") && (group_cd_04 != null)) $("#group_cd").val(group_cd_04);
		//if ((group_cd_05 != "") && (group_cd_05 != null)) $("#group_cd").val(group_cd_05);
	}


	$(document).ready(function() {

		$(document).on("change","#group_cd_01", function(){
			//document.frm.group_cd.value=$("#group_cd_01").val();
			js_party($("#group_cd_01").val(), '');
		});

		$(document).on("change","#group_cd_02", function(){
			//document.frm.group_cd.value=$("#group_cd_02").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val());
		});

		$(document).on("change","#group_cd_03", function(){
			//document.frm.group_cd.value=$("#group_cd_03").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val());
		});

		$(document).on("change","#group_cd_04", function(){
			//document.frm.group_cd.value=$("#group_cd_04").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val(), $("#group_cd_04").val());
		});

		$(document).on("change","#group_cd_05", function(){
			document.frm.group_cd.value=$("#group_cd_05").val();
		});

		$("#m_reg02").blur(function() {

			if ($("#m_reg02").val().length == "7") {
				
				var jumin_01 = $("#m_reg01").val().trim();
				var jumin_02 = $("#m_reg02").val().trim();

				var request = $.ajax({
					url:"/manager/member/chk_dup_jumin.php",
					type:"POST",
					data:{jumin_01:jumin_01, jumin_02:jumin_02},
					dataType:"html"
				});

				request.done(function(msg) {
					if (msg == "DUP") {
						alert("중복된 주민등록번호가 존재 합니다.");
						//$("#m_reg01").val("");
						$("#m_reg02").val("");
						return;
					} else {
						
						if ((jumin_02.substring(0,1) == "1") || (jumin_02.substring(0,1) == "3")) {
							document.frm.rd_m_sex[0].checked = true;
						} else {
							document.frm.rd_m_sex[1].checked = true;
						}
					}
				});

				request.fail(function(jqXHR, textStatus) {
					alert("Request failed : " +textStatus);
					return false;
				});
			}
		});

		var cache = {};

		$("#group_name").autocomplete({
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response(cache[term]);
					return;
				}

				var party_val = "";

				for (i=0 ; i < frm.party.length ; i++) {
					if (frm.party[i].checked == true) {
						party_val = frm.party[i].value;
					}
				}

				$.getJSON( "/_common/get_group_list.php?party_val="+encodeURIComponent(party_val)+"&str="+ encodeURIComponent($("#group_name").val()), request, function( data, status, xhr ) {
					cache[term] = data;
					response(data);
				});
			},
			minLength: 2,
			select: function( event, ui) {
				
				$("#group_cd").val(ui.item.group_cd);
				$("#group_name").val(ui.item.group_name);
				
				//set_member_info(ui.item.m_no);
				//alert(ui.item.m_name);
				//alert(ui.item.label);
				//$(".supplyer").val(ui.item.value);
				//$("input[name=con_cate_03]").val(ui.item.id);
			}
			
			}).bind( "blur", function( event ) {
			
				if ($("#group_cd").val() == "") {
					alert("조직명으로 검색 후 포함될 조직을 클릭 하세요.");
					return;
				}

		});


	});


	function js_next_obj(chklen, chk_obj, next_obj) {
		if ($("#"+chk_obj).val().length >= parseInt(chklen)) {
			$("#"+next_obj).focus();
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
		<input type="hidden" name="mem_id" value="<?= $rs_mem_id ?>">
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
		<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">

		<input type="hidden" name="is_pay" value="">
		<input type="hidden" name="pay_type" value="">
		<input type="hidden" name="cms_info01" value="">
		<input type="hidden" name="cms_info02" value="">
		<input type="hidden" name="cms_info03" value="">
		<input type="hidden" name="cms_info04" value="">
		<input type="hidden" name="cms_info05" value="">
		<input type="hidden" name="cms_amount" value="">
		<input type="hidden" name="cms_pay_day" value="">
		<input type="hidden" name="m_online_flag" value="off">

		<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
				<caption>게시판 생성</caption>
					<colgroup>
						<col width="20%" />
						<col width="80%" />
					</colgroup>
					<tbody>
						<tr>
							<th>이름</td>
							<td>
								<input type="Text" name="m_name" value="<?= $rs_m_name ?>" style="width:95px;" class="txt">
							</td>
						</tr>
						<tr>
							<th>주민등록번호</td>
							<td>
								<input type="Text" name="m_reg01" id="m_reg01" value="<?= $rs_m_reg01 ?>" style="width:95px;" maxlength="6" class="txt" onKeyUp="js_next_obj(6, 'm_reg01', 'm_reg02')"> -
								<input type="Text" name="m_reg02" id="m_reg02" value="<?= $rs_m_reg02 ?>" style="width:95px;" maxlength="7" class="txt" onKeyUp="js_next_obj(7, 'm_reg02', 'mtel_01')">
							</td>
						</tr>

						<tr>
							<th>휴대폰번호</th>
							<td>
								<?=makeSelectBox($conn, "MOBILE","mtel_01","80","","",$rs_mtel[0])?> -
								<input type="text" id="mtel_02" name="mtel_02" maxlength="4" style="width:80px" value="<?=$rs_mtel[1]?>" onKeyUp="js_next_obj(4, 'mtel_02', 'mtel_03')"> - 
								<input type="text" id="mtel_03" name="mtel_03" maxlength="4" style="width:80px" value="<?=$rs_mtel[2]?>" onKeyUp="js_next_obj(4, 'mtel_03', 'tel_01')">
								&nbsp;&nbsp;
								SMS 수신동의 :&nbsp;&nbsp;
								<input type="radio" class="radio" name="m_sms" value="1"  checked> 동의함
								<input type="radio" class="radio" name="m_sms" value="0"> 동의안함
							</td>
						</tr>

						<tr>
							<th>연락처</th>
							<td>
								<?=makeSelectBox($conn, "TEL","tel_01","80","","",$rs_tel[0])?> -
								<input type="text" id="tel_02" name="tel_02" maxlength="4" style="width:80px" value="<?=$rs_tel[1]?>" onKeyUp="js_next_obj(4, 'tel_02', 'tel_03')"> - 
								<input type="text" id="tel_03" name="tel_03" maxlength="4" style="width:80px" value="<?=$rs_tel[2]?>" onKeyUp="js_next_obj(4, 'tel_03', 'm_email_01')">
							</td>
						</tr>
						<tr>
							<th>이메일</th>
							<td>
								<input type="hidden" name='old_email' id="old_email" value='<?=$rs_m_email?>'>
								<input type="text" name="m_email_01" id="m_email_01" value="<?=$arr_m_email[0]?>" onchange="frm.m_email_enabled.value='';" style="width:100px;"/> @ 
								<input type="text" name="m_email_02" id="m_email_02" value="<?=$arr_m_email[1]?>" onchange="frm.m_email_enabled.value='';" style="width:100px;"/>
								<?= makeSelectBoxOnChange($conn,"EMAIL","sel_email","150","직접입력","",$arr_m_email[1])?>
								&nbsp;&nbsp;
								Email 수신동의 :&nbsp;&nbsp;
								<input type="radio" class="radio" name="m_mailling" value="1" checked> 동의함
								<input type="radio" class="radio" name="m_mailling" value="0" > 동의안함
							</td>
						</tr>

						<tr>
							<th>생년월일</th>
							<td>
								<select name="sel_year" style="width:100px;">
								<option value="">년</option>
									<?
										$end_yy = date("Y",strtotime("0 day"));

										for ($k = $end_yy; $k > ($end_yy-110) ; $k--) {
									?>
								<option value="<?=$k?>"><?=$k?></option>
									<?
										}
									?>
							</select> 
							<?= makeSelectBox($conn,"MONTH","sel_month","100","월","",$rs_dept_code)?>
							<?= makeSelectBox($conn,"DAY","sel_day","100","일","",$rs_dept_code)?>
							<input type="hidden" name="m_birth" value="">
							</td>
						</tr>

						<tr>
							<th>성별</th>
							<td>
								<input type="radio" name="rd_m_sex" value="1" class="radio" checked /> 남
								<input type="radio" name="rd_m_sex" value="2" class="radio"/> 여
								<input type="hidden" name="m_sex" value="">
							</td>
						</tr>

						<tr>
							<th>주소</th>
							<td>
								<input type="text" id="postcode" name="postcode" style="width:60px" readonly="1"> 
								<a href="javascript:void(0)" class="btn gray_post" onclick="sample6_execDaumPostcode('j');">주소검색</a><br />
								<div class="sp5"></div>
								<input type="text" id="address" name="address" style="width:300px">
								<input type="text" id="address2" name="address2" style="width:300px">
								<div class="sp5"></div>
								<em>* 자택주소는 주민등록상 주소지로 상세주소까지 기재하여야 합니다.</em>
							</td>
						</tr>

						<tr>
							<th>소속 지역</th>
							<td>
								<?= makeSelectBox($conn,"AREA_CD","sido","150","소속지역선택","",$rs_sido)?>
								<!--<input type="text" style="width:150px" id="sido" name="sido" value="<?=$rs_sido?>"> -->
								<input type="hidden" style="width:150px" id="sigungu" name="sigungu" value="<?=$rs_sigungu?>">
							</td>
						</tr>

						<tr>
							<th>소속당</th>
							<td>
								<?=makeRadioBoxOnClick($conn,"PARTY","party", $rs_party)?>
							</td>
						</tr>
						<tr id="add_group" style="display:none">
							<th>조직</th>
							<td>
								<div class="sp5"></div>
								<div id="group_div"><select name="group_cd_01" id="group_cd_01" style="width:200px;"></select></div>

								<div class="sp10"></div>
								<input type="text" name="group_name" id="group_name" class="txt" style="width:500px;"/>
								<font color='red'><b>조직코드</b></font> <input type="text" name="group_cd" id="group_cd" class="txt" style="width:100px;"/>
								<div class="sp5"></div>
							</td>
						</tr>

						<tr>
							<th>직업</th>
							<td><input type="text" name="job" value="<?=$rs_m_tel?>" class="txt" style="width:200px;"/></td>
						</tr>

						<tr>
							<th>직장명</th>
							<td><input type="text" name="com_name" value="<?=$rs_m_tel?>" class="txt" style="width:200px;"/></td>
						</tr>
						<tr>
							<th>직장주소</th>
							<td>
								<input type="text" id="o_postcode" name="o_postcode" style="width:60px" readonly="1"> 
								<a href="javascript:void(0)" class="btn gray_post" onclick="sample6_execDaumPostcode('o');">주소검색</a><br />
								<div class="sp5"></div>
								<input type="text" id="o_address" name="o_address" style="width:300px">
								<input type="text" id="o_address2" name="o_address2" style="width:300px">
								<div class="sp5"></div>
							</td>
						</tr>

						<tr class="end">
							<th>소속단체</th>
							<td><input type="text" name="group" value="<?=$rs_m_tel?>" class="txt" style="width:200px;"/></td>
						</tr>

					</table>
					
					<h3 class="conTitle">당비 정보</h3>
					<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
						<caption>게시판 생성</caption>
						<colgroup>
							<col width="20%" />
							<col width="80%" />
						</colgroup>
						<tr>
							<th>당비납부 여부</th>
							<td>
								<input type="radio" name="rd_is_pay" value="Y" class="radio" checked /> 약정함&nbsp;&nbsp;&nbsp;
								<input type="radio" name="rd_is_pay" value="N" class="radio"/> 약정 안함
							</td>
						</tr>
						<tr>
							<th>당비납부 방법</th>
							<td>
						<!--		<input type="radio" name="rd_pay_type" value="mobile" class="radio" onClick="js_pay_type('mobile')" /> 휴대폰&nbsp;&nbsp;&nbsp;-->
								<input type="radio" name="rd_pay_type" value="cms" class="radio" onClick="js_pay_type('cms')" /> CMS&nbsp;&nbsp;&nbsp;
								<input type="radio" name="rd_pay_type" value="card" class="radio" onClick="js_pay_type('card')" /> 신용카드&nbsp;&nbsp;&nbsp;
								<input type="radio" name="rd_pay_type" value="cash" class="radio" onClick="js_pay_type('cash')" /> 직접납부&nbsp;&nbsp;&nbsp;
							</td>
						</tr>

						<tr id="mobile_01" style="display:none">
							<th>휴대폰 번호</th>
							<td>
								<?=makeSelectBox($conn, "MOBILE","pay_mtel_01","80","","",$rs_pay_mtel[0])?> -
								<input type="text" id="pay_mtel_02" name="pay_mtel_02" maxlength="4" style="width:80px" value="<?=$rs_pay_mtel[1]?>"> - 
								<input type="text" id="pay_mtel_03" name="pay_mtel_03" maxlength="4" style="width:80px" value="<?=$rs_pay_mtel[2]?>">
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
								<input type="text" id="bank_no" name="bank_no" style="width:200px" onkeyup="return isNumber(this)">
							</td>
						</tr>

						<tr id="cms_03" style="display:none">
							<th>예금주</th>
							<td>
								<input type="text" id="bank_name" name="bank_name" style="width:200px">
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
									<option value="<?=$k?>"><?=$k?></option>
										<?
											}
										?>
								</select> 
								<?= makeSelectBox($conn,"MONTH","c_sel_month","100","월","",$rs_c_dept_code)?>
								<?= makeSelectBox($conn,"DAY","c_sel_day","100","일","",$rs_c_dept_code)?>
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
								<input type="text" id="card_no" name="card_no" style="width:200px" onkeyup="return isNumber(this)">
							</td>
						</tr>
						<tr id="card_03" style="display:none">
							<th>유효기간</th>
							<td>
								<input type="text" id="card_yy" name="card_yy" style="width:20px" maxlength="2" onkeyup="return isNumber(this)"> 년
								<input type="text" id="card_mm" name="card_mm" style="width:20px" maxlength="2" onkeyup="return isNumber(this)"> 월
							</td>
						</tr>

						<tr id="card_04" style="display:none">
							<th>카드소유주 이름</th>
							<td>
								<input type="text" id="card_name" name="card_name" style="width:200px">
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
									<option value="<?=$k?>"><?=$k?></option>
										<?
											}
										?>
								</select> 
								<?= makeSelectBox($conn,"MONTH","card_sel_month","100","월","",$rs_card_sel_month)?>
								<?= makeSelectBox($conn,"DAY","card_sel_day","100","일","",$rs_card_sel_day)?>
								<input type="hidden" name="card_birth" value="">
							</td>
						</tr>

						<tr>
							<th>약정금액</th>
							<td>
								<?=makeRadioBox($conn,"CMS_AMOUNT","cms_amount", $rs_cms_amount)?>
								<div class="sp10"></div>
								<input type = 'radio' style='width:15px' class='chk' name= 'cms_amount' value='etc'> 기타 : <input type="text" name="m_13" style="width:80px"> 원
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
								<input type="file" name="file_nm" style="width:60%;border:none" onChange="chk_file_size()">
								<input TYPE="hidden" name="flag" value="insert">
								<br />
								<font color="red">* 2 MB 이하로 등록해 주시시 바랍니다.</font>
							</td>
						</tr>

					</table>

					<div class="btnArea">
						<ul class="fRight">
							<? 
								if ($s_adm_no == $rs_reg_adm || $sPageRight_I == "Y") {
									echo '<li><a href="javascript:chk_realname();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>';
									if ($m_no <> "") {
										echo '<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>';
 									}
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
