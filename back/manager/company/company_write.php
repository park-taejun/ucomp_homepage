<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : admin_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright    : Copyright @C&C Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "CP002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/company/company.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode	= trim($mode);

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$date_start			= trim($date_start);
	$date_end				= trim($date_end);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	
	//echo $pb_nm; 
	//echo $$mode;
	
	$cp_type				= SetStringToDB($cp_type);
	$cp_nm					= SetStringToDB($cp_nm);
	$cp_phone				= SetStringToDB($cp_phone);
	$cp_hphone			= SetStringToDB($cp_hphone);
	$cp_fax					= SetStringToDB($cp_fax);
	$cp_addr				= SetStringToDB($cp_addr);
	$re_addr				= SetStringToDB($re_addr);
	$homepage				= SetStringToDB($homepage);
	$biz_no					= SetStringToDB($biz_no);
	$ceo_nm					= SetStringToDB($ceo_nm);
	$upjong					= SetStringToDB($upjong);
	$uptea					= SetStringToDB($uptea);
	$manager_nm			= SetStringToDB($manager_nm);
	$phone					= SetStringToDB($phone);
	$hphone					= SetStringToDB($hphone);
	$fphone					= SetStringToDB($fphone);
	$email					= SetStringToDB($email);
	$ad_type				= SetStringToDB($ad_type);
	$account_bank		= SetStringToDB($account_bank);
	
	$result	= false  ;

#====================================================================
# DML Process
#====================================================================
	
	
	if ($mode == "I") {
		
		$result =  insertCompany($conn, $cp_type, $cp_nm, $cp_phone, $cp_hphone, $cp_fax, $cp_zip, $cp_addr, $re_zip, $re_addr, $homepage, $biz_no, $ceo_nm, $upjong, $uptea, $dc_rate, $manager_nm, $phone, $hphone, $fphone, $email, $email_tf, $contract_start, $contract_end, $ad_type, $account_bank, $account, $memo, $use_tf, $s_adm_no);

	}

	if ($mode == "S") {

		$arr_rs = selectCompany($conn, $cp_no);

		//CP_NO, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, BIZ_NO, CEO_NM, UPJONG, UPTEA, DC_RATE, 
		//MANAGER_NM, PHONE, HPHONE, FPHONE, CONTRACT_START, CONTRACT_END, CONTRACTS_NM, CONTRACTS_RNM, USE_TF, DEL_TF, 
		//REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

		$rs_cp_no								= trim($arr_rs[0]["CP_NO"]); 
		$rs_cp_nm								= SetStringFromDB($arr_rs[0]["CP_NM"]); 
		$rs_cp_type							= SetStringFromDB($arr_rs[0]["CP_TYPE"]); 
		$rs_ad_type							= SetStringFromDB($arr_rs[0]["AD_TYPE"]); 
		$rs_cp_phone						= SetStringFromDB($arr_rs[0]["CP_PHONE"]); 
		$rs_cp_hphone						= SetStringFromDB($arr_rs[0]["CP_HPHONE"]); 
		$rs_cp_fax							= SetStringFromDB($arr_rs[0]["CP_FAX"]); 
		$rs_cp_zip							= trim($arr_rs[0]["CP_ZIP"]); 
		$rs_cp_addr							= SetStringFromDB($arr_rs[0]["CP_ADDR"]); 
		$rs_re_zip							= trim($arr_rs[0]["RE_ZIP"]); 
		$rs_re_addr							= SetStringFromDB($arr_rs[0]["RE_ADDR"]); 
		$rs_biz_no							= trim($arr_rs[0]["BIZ_NO"]); 
		$rs_ceo_nm							= SetStringFromDB($arr_rs[0]["CEO_NM"]); 
		$rs_upjong							= SetStringFromDB($arr_rs[0]["UPJONG"]); 
		$rs_uptea								= SetStringFromDB($arr_rs[0]["UPTEA"]); 
		$rs_account_bank				= SetStringFromDB($arr_rs[0]["ACCOUNT_BANK"]); 
		$rs_account							= trim($arr_rs[0]["ACCOUNT"]); 
		$rs_homepage						= SetStringFromDB($arr_rs[0]["HOMEPAGE"]); 
		$rs_memo								= trim($arr_rs[0]["MEMO"]); 
		$rs_dc_rate							= trim($arr_rs[0]["DC_RATE"]); 
		$rs_manager_nm					= SetStringFromDB($arr_rs[0]["MANAGER_NM"]); 
		$rs_phone								= SetStringFromDB($arr_rs[0]["PHONE"]); 
		$rs_hphone							= SetStringFromDB($arr_rs[0]["HPHONE"]); 
		$rs_fphone							= SetStringFromDB($arr_rs[0]["FPHONE"]); 
		$rs_email								= SetStringFromDB($arr_rs[0]["EMAIL"]); 
		$rs_email_tf						= trim($arr_rs[0]["EMAIL_TF"]); 
		$rs_contract_start			= trim($arr_rs[0]["CONTRACT_START"]); 
		$rs_contract_end				= trim($arr_rs[0]["CONTRACT_END"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm							= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date						= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm							= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date							= trim($arr_rs[0]["UP_DATE"]); 
		$rs_del_adm							= trim($arr_rs[0]["DEL_ADM"]); 
		$rs_del_date						= trim($arr_rs[0]["DEL_DATE"]); 
		

		if ($rs_contract_start <> "0000-00-00 00:00:00") {
			$rs_contract_start = date("Y-m-d",strtotime($rs_contract_start));
		} else {
			$rs_contract_start = "";
		}


		if ($rs_contract_end <> "0000-00-00 00:00:00") {
			$rs_contract_end = date("Y-m-d",strtotime($rs_contract_end));
		} else {
			$rs_contract_end = "";
		}

	}

	if ($mode == "U") {
		
		$result = updateCompany($conn, $cp_type, $cp_nm, $cp_phone, $cp_hphone, $cp_fax, $cp_zip, $cp_addr, $re_zip, $re_addr, $homepage, $biz_no, $ceo_nm, $upjong, $uptea, $dc_rate, $manager_nm, $phone, $hphone, $fphone, $email, $email_tf, $contract_start, $contract_end, $ad_type, $account_bank, $account, $memo, $use_tf, $s_adm_no, $cp_no);
	}

	if ($mode == "D") {
		$result = deleteCompany($conn,$cp_no);
	}

	
	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&con_cp_type=".$con_cp_type;
		
		if ($mode == "U") {
?>	
<script language="javascript">
		location.href =  "company_write.php<?=$strParam?>&mode=S&cp_no=<?=$cp_no?>";
</script>
<?
		} else {
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		location.href =  "company_list.php<?=$strParam?>";
</script>
<?
		}
		exit;
	}	
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="javascript">

	function sample6_execDaumPostcode(a) {
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

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				if (a == "O") {
					document.getElementById('cp_zip').value = data.zonecode; //5자리 새우편번호 사용
					document.getElementById('cp_addr').value = fullAddr;
					// 커서를 상세주소 필드로 이동한다.
					document.getElementById('cp_addr').focus();
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				if (a == "R") {
					document.getElementById('re_zip').value = data.zonecode; //5자리 새우편번호 사용
					document.getElementById('re_addr').value = fullAddr;
					// 커서를 상세주소 필드로 이동한다.
					document.getElementById('re_addr').focus();
				}

			}
		}).open();
	}

$(document).ready(function() {
	$(".date").datepicker({
		prevText: "이전달",
		nextText: "다음달",
		monthNames: [ "1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월" ], 
		monthNamesShort: [ "1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월" ], 
		dayNames: [ "일요일","월요일","화요일","수요일","목요일","금요일","토요일" ], 
		dayNamesShort: [ "일","월","화","수","목","금","토" ], 
		dayNamesMin: [ "일","월","화","수","목","금","토" ], 
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd"
		,minDate: new Date(1970, 4-1, 15)	//(연, 월-1, 일)
	//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
	});
});

	// 조회 버튼 클릭 시 
	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "company_list.php";
		frm.submit();
	}

	// 저장 버튼 클릭 시 
	function js_save() {
		
		var cp_no = "<?= $cp_no ?>";
		var frm = document.frm;
		
		if (isNull(frm.cp_nm.value)) {
			alert('업체명을 입력해주세요.');
			frm.cp_nm.focus();
			return ;		
		}
		
		if (frm.cp_type.value == "") {
			alert('업체구분을 선택해주세요.');
			frm.cp_type.focus();
			return ;		
		}
		
		/*
		if (frm.ad_type.value == "") {
			alert('결재구분을 선택해주세요.');
			frm.ad_type.focus();
			return ;		
		}
		*/

		if (isNull(frm.biz_no.value)) {
			alert('사업자 등록번호를 입력해주세요.');
			frm.biz_no.focus();
			return ;		
		}

		if (isNull(frm.ceo_nm.value)) {
			alert('대표자명을 입력해주세요.');
			frm.ceo_nm.focus();
			return ;		
		}

		if (isNull(frm.cp_phone.value)) {
			alert('대표 전화번호를 입력해주세요.');
			frm.cp_phone.focus();
			return ;		
		}
		
		/*
		if (isNull(frm.account_bank.value)) {
			alert('거래은행을 입력해주세요.');
			frm.account_bank.focus();
			return ;		
		}

		if (isNull(frm.account.value)) {
			alert('계좌번호를 입력해주세요.');
			frm.account.focus();
			return ;		
		}
		*/

		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}

		if (frm.rd_email_tf[0].checked == true) {
			frm.email_tf.value = "Y";
		} else {
			frm.email_tf.value = "N";
		}

		if (isNull(cp_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}

		frm.method = "post";
		frm.action = "company_write.php";
		frm.submit();
	}

	//우편번호 찾기
	function js_post(zip, addr) {
		var url = "/_common/common_post.php?zip="+zip+"&addr="+addr;
		NewWindow(url, '우편번호찾기', '390', '370', 'NO');
	}

	/**
	* 파일 첨부에 대한 선택에 따른 파일첨부 입력란 visibility 설정
	*/
	function js_fileView(obj,idx) {
		
		var frm = document.frm;
		
		if (idx == 01) {
			if (obj.selectedIndex == 2) {
				frm.contracts_nm.style.visibility = "visible";
			} else {
				frm.contracts_nm.style.visibility = "hidden";
			}
		}

	}


</script>
</head>
<body>

<div id="wrap">
<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>
	<!-- S: container -->
	<div class="container">
		<div class="contentsarea">
			<div class="menu-holiday">
				<h3><strong><?=$p_menu_name?></strong>
				</h3>

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="cp_no" value="<?= $cp_no?>">
<input type="hidden" name="con_cp_type" value="<?= $con_cp_type?>">
<input type="hidden" name="date_start" value="<?= $date_start ?>">
<input type="hidden" name="date_end" value="<?= $date_end ?>">
<input type="hidden" name="search_field" value="<?= $search_field ?>">
<input type="hidden" name="search_str" value="<?= $search_str ?>">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">


				<div class="boardwrite">

					<table>
						<colgroup>
							<col style="width:12%">
							<col style="width:38%">
							<col style="width:12%">
							<col style="width:38%">
						</colgroup>
						<tbody>
							<tr>
								<th>업체명</th>
								<td>
									<span class="inpbox"><input type="text" name="cp_nm" value="<?= $rs_cp_nm ?>" style="width:60%;" itemname="업체명" required class="txt"></span>
								</td>
								<th>업체구분</th>
								<td>
									<span class="optionbox" style="width:125px">
										<?= makeSelectBox($conn,"CP_TYPE","cp_type","","선택","",$rs_cp_type)?>
									</span>
								</td>
							</tr>
							<tr>
								<th>사업자	등록번호</th>
								<td>
									<span class="inpbox" style="width:140px"><input type="Text" name="biz_no" value="<?= $rs_biz_no?>" itemname="사업자	등록번호" required class="txt"></span><b>'-' 포함하여 입력해 주세요</b>
								</td>
								<th>대표자명</th>
								<td>
									<span class="inpbox"><input type="Text" name="ceo_nm" value="<?= $rs_ceo_nm ?>" style="width:30%;" itemname="대표자명" required class="txt"></span>
								</td>
							</tr>
							<tr>
								<th>대표 전화번호</th>
								<td>
									<span class="inpbox"><input type="Text" name="cp_phone" value="<?= $rs_cp_phone?>" style="width:120px;" itemname="대표 전화번호" required class="txt" onkeyup="return isPhoneNumber(this)"></span>
								</td>
								<th>대표 FAX</th>
								<td>
									<span class="inpbox"><input type="Text" name="cp_fax" value="<?= $rs_cp_fax?>" style="width:120px;" class="txt" onkeyup="return isPhoneNumber(this)"></span>
								</td>
							</tr>

							<tr>
								<th>주소</th>
								<td colspan="3" class="address">
									<span class="inpbox post"><input type="Text" name="cp_zip" id="cp_zip"  value="<?= $rs_cp_zip?>" maxlength="7" class="txt"></span>
									<span class="inpbox address"><input type="Text" name="cp_addr" id="cp_addr" value="<?= $rs_cp_addr?>" class="txt"></span>
									<button type="button" class="btn-border-white" id="btn_search" onclick="sample6_execDaumPostcode('O')">주소검색</button>
								</td>
							<tr>
							<!--
							<tr>
								<th>반품 주소</th>
								<td colspan="3">
									<input type="Text" name="re_zip" value="<?= $rs_re_zip?>" style="width:60px;" maxlength="7" class="txt">
									<input type="Text" name="re_addr" value="<?= $rs_re_addr?>" style="width:65%;" class="txt">
									<a href="javascript:js_post('re_zip','re_addr');"><img src="/manager/images/admin/btn_filesch.gif" alt="찾기" align="absmiddle" /></a>
								</td>
							<tr>
							-->
							<tr>
								<th>업종</th>
								<td><span class="inpbox"><input type="Text" name="upjong" value="<?= $rs_upjong?>"class="txt"></span></td>
								<th>업태</th>
								<td><span class="inpbox"><input type="Text" name="uptea" value="<?= $rs_uptea?>" class="txt"></span></td>
							</tr>

							<tr>
								<th>거래은행</th>
								<td><span class="inpbox"><input type="Text" name="account_bank" value="<?= $rs_account_bank?>" itemname="거래은행" required class="txt"></span></td>
								<th>계좌번호</th>
								<td><span class="inpbox"><input type="Text" name="account" value="<?= $rs_account?>" itemname="계좌번호" required class="txt" onkeyup="return isPhoneNumber(this)"></span></td>
							</tr>

							<tr>
								<th>계약 기간</th>
								<td class="lpd20 right" colspan="3">
									<span class="inpbox" style="width:125px;"><input name="contract_start" type="text" class="date txt"  readonly value="<?= $rs_contract_start ?>"></span>&nbsp;&nbsp;~&nbsp;&nbsp;
									<span class="inpbox" style="width:125px;"><input name="contract_end" type="text" class="date txt" readonly value="<?= $rs_contract_end ?>"></span>
								</td>
							</tr>


							<tr>
								<!--
								<th>활인율</th>
								<td>
									<input type="Text" name="dc_rate" value="<?= $rs_dc_rate?>" value="" style="width:70px;" class="txt" onkeyup="return isPhoneNumber(this)"> %
								</td>
								-->
								<th>홈페이지</th>
								<td>
									<span class="inpbox"><input type="Text" name="homepage" value="<?= $rs_homepage?>" class="txt"></span>
								</td>
								<th>사용여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>><label>사용</label></span></span>
										<span class="iradio"><input type="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?>><label>미사용</label></span>
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									</div>
								</td>
							</tr>
							<tr>
								<th>업체메모</th>
								<td colspan="3" class="memo">
									<span class="textareabox">
										<textarea class="txt" name="memo"><?= $rs_memo ?></textarea>
									</span>
								</td>
							</tr>

						</tbody>
					</table>
				</div>
				
				<div class="sp20"></div>

				<div class="boardlist search">

					<table>
						<colgroup>
							<col style="width:12%">
							<col style="width:38%">
							<col style="width:12%">
							<col style="width:38%">
						</colgroup>
						<tbody>
							<tr>
								<th>담당자 명</th>
								<td>
									<span class="inpbox"><input type="Text" name="manager_nm" value="<?= $rs_manager_nm ?>" class="txt"></span>
								</td>
								<th>전화번호</th>
								<td>
									<span class="inpbox"><input type="Text" name="phone" value="<?= $rs_phone ?>" class="txt" onkeyup="return isPhoneNumber(this)"></span>
								</td>
							</tr>
							<tr>
								<th>휴대 전화번호</th>
								<td>
									<span class="inpbox"><input type="Text" name="hphone" value="<?= $rs_hphone ?>" class="txt" onkeyup="return isPhoneNumber(this)"></span>
								</td>
								<th>FAX 번호</th>
								<td>
									<span class="inpbox"><input type="Text" name="fphone" value="<?= $rs_fphone ?>" class="txt" onkeyup="return isPhoneNumber(this)"></span>
								</td>
							<tr>
							<tr>
								<th>이메일</th>
								<td>
									<span class="inpbox"><input type="Text" name="email" value="<?= $rs_email ?>" class="txt"></span>
								</td>
								<th>이메일 수신여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" name="rd_email_tf" value="Y" <? if (($rs_email_tf =="Y") || ($rs_email_tf =="")) echo "checked"; ?>><label>수신</label></span>
										<span class="iradio"><input type="radio" name="rd_email_tf" value="N" <? if ($rs_email_tf =="N") echo "checked"; ?>><label>미수신</label></span>
										<input type="hidden" name="email_tf" value="">
									</div>
								</td>
							<tr>
						</tbody>
					</table>
				</div>

				<div class="btnright">
				<? if ($adm_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<? } ?>
				<? }?>

				<? if ($s_adm_cp_type == "운영") { ?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				<? } ?>
				
				<? if ($s_adm_cp_type == "운영") { ?>
				<? if ($adm_no <> "") {?>
					<? if ($sPageRight_D == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					<? } ?>
				<? } ?>
				<? } ?>
				</div>
      <!-- // E: mwidthwrap -->
</form>
			</div>
		</div>
	</div>
	<!-- //E: container -->

	<!-- S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>
<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
