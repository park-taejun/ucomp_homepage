<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : payment_write.php
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
	$menu_right = "PM002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/payment/payment.php";
	require "../../_classes/biz/member/member.php";

#====================================================================
# Request Parameter
#====================================================================

	$mm_subtree	 = "7";

	$seq_no				= trim($seq_no);

	$arr_rs = selectPaymentInfo($conn, $seq_no);

	$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]); 
	$rs_m_no						= trim($arr_rs[0]["M_NO"]); 
	$rs_m_name					= trim($arr_rs[0]["M_NAME"]); 
	$rs_pay_yyyy				= trim($arr_rs[0]["PAY_YYYY"]);
	$rs_pay_mm					= trim($arr_rs[0]["PAY_MM"]); 
	$rs_pay_reason			= trim($arr_rs[0]["PAY_REASON"]); 
	$rs_cms_amount			= trim($arr_rs[0]["CMS_AMOUNT"]); 
	$rs_res_pay_date		= trim($arr_rs[0]["RES_PAY_DATE"]); 
	$rs_res_cms_amount	= trim($arr_rs[0]["RES_CMS_AMOUNT"]); 
	$rs_cms_charge			= trim($arr_rs[0]["CMS_CHARGE"]); 
	$rs_res_pay_no			= trim($arr_rs[0]["RES_PAY_NO"]); 
	$rs_cash_recipt			= trim($arr_rs[0]["CASH_RECIPT"]); 
	$rs_pay_type				= trim($arr_rs[0]["PAY_TYPE"]); 
	$rs_pay_result			= trim($arr_rs[0]["PAY_RESULT"]);
	$rs_pay_result_code	= trim($arr_rs[0]["PAY_RESULT_CODE"]);
	$rs_pay_result_msg	= trim($arr_rs[0]["PAY_RESULT_MSG"]);
	$rs_send_flag				= trim($arr_rs[0]["SEND_FLAG"]);
	$rs_send_date				= trim($arr_rs[0]["SEND_DATE"]);
	$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);
	$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]);
	$rs_pay_memo				= trim($arr_rs[0]["PAY_MEMO"]);
	$rs_del_adm					= trim($arr_rs[0]["DEL_ADM"]);
	$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]);
	$rs_adm_name				= trim($arr_rs[0]["ADM_NAME"]);

	if ($rs_res_pay_date) {
		$rs_res_pay_date = left($rs_res_pay_date, 4)."-".right(left($rs_res_pay_date, 6),2)."-".right($rs_res_pay_date,2);
	}

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

?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />
<link href="../js/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
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

	$(document).ready(function() {

		$(".date").datepicker({
			dateFormat: "yy-mm-dd"
		});

	});

	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "payment_list.php";
		frm.submit();
	}

	function js_delete() {

		var mode = "D";
		var seq_no = <?=$seq_no?>;
		
		bDelOK = confirm('납부 정보를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {

			var request = $.ajax({
				url:"/manager/payment/payment_dml.php",
				type:"POST",
				data:{mode:mode, seq_no:seq_no},
				dataType:"html"
			});

			request.done(function(msg) {
				if (msg == "T") {
					alert('당비 납부정보가 삭제 되었습니다.');
					js_list();
					return;
				}

				if (msg == "F") {
					alert("당비 납부정보 삭제가 실패 하였습니다.");
					return;
				}

			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});


		}

	}

	$(document).ready(function() {
		set_member_info('<?=$rs_m_no?>');
		js_get_history_list('payment', '5');
	});

	function set_member_info(m_no) {

		var request = $.ajax({
			url:"/manager/member/get_member_info.php",
			type:"POST",
			data:{m_no:m_no},
			dataType:"html"
		});

		request.done(function(msg) {
			$("#member_info").html(msg);
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	}

	function chk_data() {
		
		var mode = "U";
		var seq_no = <?=$seq_no?>;
		var sel_year = $("#sel_year").val().trim();
		var cms_amount = $("#cms_amount").val().trim();
		var sel_month = $("#sel_month").val().trim();
		var res_cms_amount = $("#res_cms_amount").val().trim();
		var pay_memo = $("#pay_memo").val().trim();
		var res_pay_date = $("#res_pay_date").val().trim();
		var pay_reason = $("#pay_reason").val().trim();
		
		if (res_cms_amount == "") {
			alert("납부 당비를 입력 하세요.");
			return;
		}

		if (res_pay_date == "") {
			alert("입금일을 선택 하세요.");
			return;
		}

		if (cms_amount == "") {
			alert("약정 당비를 입력 하세요.");
			return;
		}


		var request = $.ajax({
			url:"/manager/payment/payment_dml.php",
			type:"POST",
			data:{mode:mode, seq_no:seq_no,sel_year:sel_year, sel_month:sel_month, res_cms_amount:res_cms_amount, pay_memo:pay_memo, res_pay_date:res_pay_date, pay_reason:pay_reason},
			dataType:"html"
		});

		request.done(function(msg) {
			
			if (msg == "T") {
				
				alert('당비 납부정보가 수정 되었습니다.');
				js_list();
				
				return;
			}

			if (msg == "F") {
				alert("당비 납부정보가 수정이 실패 하였습니다.");
				return;
			}

		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});


	}


	function js_get_history_list(type, cnt) {
		
		if (cnt == "ALL") {
			$("#btn_payment_all").hide();
			$("#btn_payment_5").show();
		} else {
			$("#btn_payment_all").show();
			$("#btn_payment_5").hide();
		}
		
		var m_no = "<?=$rs_m_no?>";

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
		<form name="frm" method="post">

		<input type="hidden" name="sel_pay_yyyy" value="<?=$sel_pay_yyyy?>">
		<input type="hidden" name="sel_pay_mm" value="<?=$sel_pay_mm?>">
		<input type="hidden" name="sel_area_cd" value="<?=$sel_area_cd?>">
		<input type="hidden" name="sel_pay_type" value="<?=$sel_pay_type?>">
		<input type="hidden" name="sel_party" value="<?=$sel_party?>">
		<input type="hidden" name="nPage" value="<?=$nPage?>">
		<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
		<input type="hidden" name="search_field" value="<?=$search_field?>">
		<input type="hidden" name="search_str" value="<?=$search_str?>">
		<input type="hidden" name="Ngroup_cd" value="<?=$Ngroup_cd?>">

		<input type="hidden" id="m_no" name="m_no">

		<h3 class="conTitle"><?=$p_menu_name?></h3>
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
						<th>이름</td>
						<td colspan="3">
							<?=$rs_m_name?>
						</td>
					</tr>
					<tr class="end">
						<th>납부년월</td>
						<td>
								<select name="sel_year" id="sel_year" style="width:100px;">
									<?
										$end_yy = date("Y",strtotime("5 year"));

										for ($k = $end_yy; $k > ($end_yy-15) ; $k--) {
									?>
								<option value="<?=$k?>" <? if ($rs_pay_yyyy == $k) { echo "selected";} ?>><?=$k?></option>
									<?
										}
									?>
							</select> 년
							<?= makeSelectBox($conn,"MONTH","sel_month","100","","",$rs_pay_mm)?> 월
						</td>
							<th>납부 당비</th>
							<td>
								<input type="text" id="res_cms_amount" name="res_cms_amount" value="<?=$rs_res_cms_amount?>" class="txt" style="width:100px;" onkeyup="return isNumber(this)" /> 원
							</td>
						</tr>
					</tr>
					<tr>
						<th>당비구분</td>
						<td colspan="3">
							<?= makeSelectBox($conn,"PARTY_MEM_FEES","pay_reason","100","당비구분선택","",$rs_pay_reason)?>
						</td>
					</tr>
					<tr>
						<th>입금일</td>
						<td>
							<input type="Text" name="res_pay_date" id="res_pay_date" style="width:95px;" class="date" value="<?=$rs_res_pay_date?>">
						</td>
						<th>약정 당비</td>
						<td>
							<input type="Text" name="cms_amount" value="<?=$rs_cms_amount?>" id="cms_amount"  class="txt" style="width:100px;" onkeyup="return isNumber(this)" > 원
						</td>
					</tr>
					<tr>
						<th>납부메모</td>
						<td colspan="3">
							<textarea id="pay_memo" name="pay_memo" style="width:80%;"><?=$rs_pay_memo?></textarea>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="btnArea">
				<ul class="fRight">
					<? 
						if ($s_adm_no == $rs_reg_adm || $sPageRight_U == "Y") {
							echo '<li><a href="javascript:chk_data();"><img src="../images/btn/btn_rewrite.gif" alt="수정" /></a></li>';
							if ($seq_no <> "") {
								echo '<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>';
							}
						}
					?>
					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
				</ul>
			</div>

			<h3 class="conTitle">당원 정보</h3>
			<div id="member_info">
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
							<th>이름</td>
							<td>&nbsp;</td>
							<th>주민등록번호</td>
							<td>&nbsp;</td>
						</tr>

						<tr>
							<th>휴대폰번호</th>
							<td>&nbsp;</td>
							<th>연락처</th>
							<td>&nbsp;</td>
						</tr>

						<tr>
							<th>성별</th>
							<td>
								&nbsp;
							</td>

							<th>생년월일</th>
							<td>
								&nbsp; 년 &nbsp; 월 &nbsp; 일
							</td>
						</tr>
						<tr>
							<th>주소</th>
							<td colspan="3">
								&nbsp;
							</td>
						</tr>

						<tr>
							<th>소속당</th>
							<td>&nbsp;
							</td>
							<th>소속단체</th>
							<td>
								&nbsp;
							</td>
						</tr>

						<tr class="end">
							<th>당비약정여부</th>
							<td>
								&nbsp;
							</td>
							<th>약정당비</th>
							<td>
								&nbsp;
							</td>
						</tr>
					</table>
					</div>

</form>

			<div class="sp10"></div>

		<form id="bbsList">
					<h3 class="conTitle">당비 납부 정보</h3>

					<div class="expArea">
					<p class="fRight">
						<input type="button" name="aa" id="btn_payment_all" value=" 전체 보기 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;" onclick="js_get_history_list('payment','ALL');">
						<input type="button" name="aa" id="btn_payment_5" value=" 최근 5 건 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;display:none" onclick="js_get_history_list('payment','5');">
					</p>
					</div>

					<table class="secBtm">
						<colgroup>
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
							<col width="10%" />
						</colgroup>

						<thead>
						<tr>
							<th>순서</th>
							<th>신청년월</th>
							<th>납부방법</th>
							<th>약정당비</th>
							<th>납부당비</th>
							<th>출금일</th>
							<th>출금수수료</th>
							<th>환불금액</th>
							<th>출금상태</th>
							<th>처리결과</th>
							<th>출금신청일</th>
						</tr>
						</thead>
						<tbody id="payment_list">
						<tr>
							<td colspan="11">&nbsp;</td>
						</tr>
						</tbody>
					</table>

		</form>

				</section>

<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>
