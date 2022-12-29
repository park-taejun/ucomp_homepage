<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : supplies_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-01-02
# Modify Date  : 
#	Copyright    : Copyright @Ucom Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "SU001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/supplies/supplies.php";

	$title				= SetStringToDB($title);
	$su_type			= SetStringToDB($su_type);
	$su_model			= SetStringToDB($su_model);
	//$su_price			= SetStringToDB($su_price);
	//$buy_link			= SetStringToDB($buy_link);
	//$ask_adm_no		= SetStringToDB($ask_adm_no);
	//$ask_date			= SetStringToDB($ask_date);
	//$ask_state		= SetStringToDB($ask_state);
	//$buy_state		= SetStringToDB($buy_state);
	$buy_company	= SetStringToDB($buy_company);
	//$buy_date			= SetStringToDB($buy_date);
	//$pay_type			= SetStringToDB($pay_type);
	//$buy_price		= SetStringToDB($buy_price);
	$buy_memo			= SetStringToDB($buy_memo);
	$memo					= SetStringToDB($memo);

	#echo $adm_no;

#====================================================================
# DML Process
#====================================================================
	if ($mode == "I") {
		
		$ask_date = date("Y-m-d",strtotime("0 day"));

		$arr_data = array("TITLE"=>$title,
											"SU_TYPE"=>$su_type,
											"SU_MODEL"=>$su_model,
											"SU_PRICE"=>$su_price,
											"BUY_LINK"=>$buy_link,
											"ASK_ADM_NO"=>$ask_adm_no,
											"ASK_DATE"=>$ask_date,
											"ASK_STATE"=>"0",
											"BUY_STATE"=>"0",
											"MEMO"=>$memo,
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$new_su_no =  insertSupplies($conn, $arr_data);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], $title."(구매 요청 등록)", "Insert");
		
		$EMAIL		= "park@ucomp.co.kr";
		$NAME			= "인트라넷";
		$SUBJECT	= "장비 구매 신청이 접수 되었습니다.";
		$CONTENT	= "(".$title.") ".$memo;
		$mailto		= "park@ucomp.co.kr";
		$result_send_mail = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

		$mailto		= "gio1202@ucomp.co.kr";
		$result_send_mail = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

		$result = true;
	}

	if ($mode == "U") {

		if ($_SESSION['s_adm_group_no'] <= 3) {

			$arr_data = array("TITLE"=>$title,
												"SU_TYPE"=>$su_type,
												"SU_MODEL"=>$su_model,
												"SU_PRICE"=>$su_price,
												"BUY_LINK"=>$buy_link,
												"ASK_STATE"=>$ask_state,
												"BUY_STATE"=>$buy_state,
												"BUY_COMPANY"=>$buy_company,
												"BUY_DATE"=>$buy_date,
												"PAY_TYPE"=>$pay_type,
												"BUY_PRICE"=>$buy_price,
												"MEMO"=>$memo,
												"BUY_MEMO"=>$buy_memo,
												"UP_ADM"=>$_SESSION['s_adm_no']
												);

		} else {

			$arr_data = array("TITLE"=>$title,
												"SU_TYPE"=>$su_type,
												"SU_MODEL"=>$su_model,
												"SU_PRICE"=>$su_price,
												"BUY_LINK"=>$buy_link,
												"MEMO"=>$memo
												);

		}

		$result			=  updateSupplies($conn, $arr_data, $su_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], $title." (구매 요청 수정)", "Update");

	}

	if ($mode == "S") {

		$arr_rs = selectSupplies($conn, $su_no);

		$rs_su_no					= trim($arr_rs[0]["SU_NO"]); 
		$rs_title					= SetStringFromDB($arr_rs[0]["TITLE"]); 
		$rs_su_type				= trim($arr_rs[0]["SU_TYPE"]); 
		$rs_su_model			= SetStringFromDB($arr_rs[0]["SU_MODEL"]); 
		$rs_su_price			= trim($arr_rs[0]["SU_PRICE"]); 
		$rs_buy_link			= trim($arr_rs[0]["BUY_LINK"]); 
		$rs_ask_adm_no		= trim($arr_rs[0]["ASK_ADM_NO"]); 
		$rs_ask_date			= trim($arr_rs[0]["ASK_DATE"]); 
		$rs_ask_state			= trim($arr_rs[0]["ASK_STATE"]); 
		$rs_buy_state			= trim($arr_rs[0]["BUY_STATE"]); 
		$rs_buy_company		= SetStringFromDB($arr_rs[0]["BUY_COMPANY"]); 
		$rs_buy_date			= trim($arr_rs[0]["BUY_DATE"]); 
		$rs_pay_type			= trim($arr_rs[0]["PAY_TYPE"]); 
		$rs_buy_price			= trim($arr_rs[0]["BUY_PRICE"]); 
		$rs_buy_memo			= SetStringFromDB($arr_rs[0]["BUY_MEMO"]); 
		$rs_memo					= SetStringFromDB($arr_rs[0]["MEMO"]); 
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		$rs_reg_adm				= trim($arr_rs[0]["REG_ADM"]); 
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 

		if ($rs_reg_adm == $_SESSION['s_adm_no']) {
			$sPageRight_U = "Y";
			if ($rs_ask_state == "0") {
				$sPageRight_D = "Y";
			}
		}

	}

	if ($mode == "D") {
		$result = deleteSupplies($conn, $s_adm_no, $su_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "구매 요청 삭제 (등록 번호 : ".(int)$su_no.") ", "Delete");
	}


	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_su_type=".$con_su_type."&con_ask_adm_no=".$con_ask_adm_no."&con_ask_state=".$con_ask_state."&con_buy_state=".$con_buy_state."&search_field=".$search_field."&search_str=".$search_str;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "supplies_list.php<?=$strParam?>";
</script>
<?
		mysql_close($conn);
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

<script type="text/javascript">

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

function js_list() {
	var frm = document.frm;
		
	frm.method = "get";
	frm.action = "supplies_list.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var su_no = "<?= $su_no ?>";
	
	if (isNull(frm.title.value)) {
		alert('제목을 입력하세요.');
		frm.title.focus();
		return ;		
	}

	if (frm.su_type.value == "") {
		alert('구매 품목을 선택하세요.');
		frm.su_type.focus();
		return ;		
	}

	if (isNull(frm.su_model.value)) {
		alert('모델명을 입력해주세요.');
		frm.su_model.focus();
		return ;		
	}

	if (isNull(frm.su_price.value)) {
		alert('예상 비용을 입력해주세요.');
		frm.su_price.focus();
		return ;
	}

	if (isNull(frm.memo.value)) {
		alert('상세내용을 입력해주세요.');
		frm.memo.focus();
		return ;
	}

	if (frm.ask_adm_no.value == "") {
		alert('기안자를 선택해주세요.');
		frm.memo.focus();
		return ;
	}

	if (isNull(su_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.su_no.value = su_no;
	}

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_delete() {

	var frm = document.frm;
	var su_no = "<?= $su_no ?>";

	bDelOK = confirm('자료를 삭제 하시겠습니까?');
	
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.su_no.value = su_no;
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
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
				<h3><strong><?=$p_menu_name?></strong></h3>

<form name="frm" method="post">
<input type="hidden" name="su_no" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="con_su_type" value="<?=$con_su_type?>">
<input type="hidden" name="con_ask_adm_no" value="<?=$con_ask_adm_no?>">
<input type="hidden" name="con_ask_state" value="<?=$con_ask_state?>">
<input type="hidden" name="con_buy_state" value="<?=$con_buy_state?>">
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="boardwrite">

					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:24%" />
						</colgroup>
						<tbody>
							<tr>
								<th>제목</th>
								<td colspan="5">
									<span class="inpbox"><input type="text" class="txt" style="width:95%" name="title" value="<?=$rs_title?>" /></span>
								</td>
							</tr>
							<tr>
								<th>구매 품목</th>
								<td colspan="5">
									<span class="optionbox">
										<?= makeSelectBoxOnChange($conn, "EQUIPMENT" , "su_type", "", "자재 선택", "", $rs_su_type); ?>
									</span>
								</td>
							</tr>
							<tr>
								<th>모델명</th>
								<td colspan="3"><span class="inpbox"><input type="text" class="txt" style="width:95%" name="su_model" value="<?=$rs_su_model?>" /></span></td>
								<th>예상가격</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:120px" name="su_price" value="<?=$rs_su_price?>" onkeyup="return isNumber(this)" /></span></td>
							</tr>

							<tr>
								<th>구매링크</th>
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:95%" name="buy_link" value="<?=$rs_buy_link?>" placeholder="http://www.auction.co.kr"/></span></td>
							</tr>

							<tr>
								<th>상세내용</th>
								<td colspan="5">
									<span class="textareabox">
										<textarea class="txt" name="memo"><?=$rs_memo?></textarea>
									</span>	
								</td>
							</tr>
							
							<? if ($_SESSION['s_adm_group_no'] <= 3) { ?>
							<tr>
								<th>기안자</th>
								<td colspan="5">
									<span class="optionbox">
										<?=makeEmpSelectBox($conn, "ask_adm_no" , "" , "선택하세요", "", $rs_ask_adm_no)?>
									</span>
								</td>
							</tr>
							<? } else { ?>
							<input type="hidden" name="ask_adm_no" id="ask_adm_no" value="<?=$_SESSION['s_adm_no']?>">
							<? } ?>

						</tbody>
					</table>
				</div>

				<? if ($_SESSION['s_adm_group_no'] <= 3) { ?>
				<h3><strong>구매 정보 등록</strong></h3>

				<div class="boardwrite">

					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:24%" />
						</colgroup>
						<tbody>
							<tr>
								<th>신청상태</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"ASK_STATE","ask_state","","","",$rs_ask_state)?>
									</span>
								</td>
								<th>구매상태</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"BUY_STATE","buy_state","","","",$rs_buy_state)?>
									</span>
								</td>
								<th>구매일</th>
								<td>
									<span class="inpbox">
										<input type="text" class="txt date" style="width:95%" name="buy_date" value="<?=$rs_buy_date?>" />
									</span>
								</td>
							</tr>

							<tr>
								<th>구매처</th>
								<td colspan="3">
									<span class="inpbox">
										<input type="text" class="txt" style="width:95%" name="buy_company" value="<?=$rs_buy_company?>" />
									</span>
								</td>
								<th>구매가</th>
								<td>
									<span class="inpbox">
										<input type="text" class="txt" style="width:120px" name="buy_price" value="<?=$rs_buy_price?>" onkeyup="return isNumber(this)" />
									</span>
								</td>
							</tr>
							<tr>
								<th>결제방식</th>
								<td colspan="5">
									<span class="optionbox">
										<?= makeSelectBox($conn,"PAY_TYPE","pay_type","","","",$rs_pay_type)?>
									</span>
								</td>
							</tr>

							<tr>
								<th>구매메모</th>
								<td colspan="5">
									<span class="textareabox">
										<textarea class="txt" name="buy_memo"><?=$rs_buy_memo?></textarea>
									</span>	
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?
					}
				?>


				<div class="btnright">
				<? if ($eq_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<? } ?>
					<? if ($sPageRight_D == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<? } ?>
				<? }?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>
      <!-- // E: mwidthwrap -->
			</div>
		</div>
	</div>

	<!-- //S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>