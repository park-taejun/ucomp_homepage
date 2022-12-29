<?session_start();?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "SY002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";
	
#====================================================================
# common_header
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/syscode/syscode.php";
	require "../../_classes/biz/payment/payment.php";

#====================================================================
# Request Parameter
#====================================================================
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<?

	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$sel_pick_date	= $_POST['sel_pick_date']!=''?$_POST['sel_pick_date']:$_GET['sel_pick_date'];
	$refund_amount	= $_POST['refund_amount']!=''?$_POST['refund_amount']:$_GET['refund_amount'];
	$contents			= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];

	$mode 					= SetStringToDB($mode);
	$sel_pick_date	= SetStringToDB($sel_pick_date);
	$refund_amount	= SetStringToDB($refund_amount);
	$contents				= SetStringToDB($contents);
	
	$result = false;

	if($mode==""){
		$mode="S";
	}
#====================================================================
# DML Process
#====================================================================

	if ($mode == "U") {
		//echo $dcode_no."<br>";
		$pay_result_msg="환불";

		$result = updateRefundPayment($conn, $sel_pick_date, $refund_amount, $contents, $pay_result_msg, $seq_no, $_SESSION['s_adm_no']);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "CMS 출금 환불처리 ", "Update");
	}

	if ($mode == "C") {
		//echo $dcode_no."<br>";
		$pay_result_msg="정상처리";

		$result = updateRefundCancelPayment($conn, $pay_result_msg, $seq_no, $_SESSION['s_adm_no']);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "CMS 출금 환불취소처리 ", "Update");
	}

	if (($mode == "S")) {

		$arr_rs = selectPaymentInfo($conn, $seq);

		//	SEQ_NO,M_NO,PAY_YYYY,PAY_MM,PAY_REASON,CMS_AMOUNT,RES_PAY_DATE,RES_CMS_AMOUNT,
		//	CMS_CHARGE,RES_PAY_NO,CASH_RECIPT,PAY_TYPE,PAY_RESULT,PAY_RESULT_CODE,
		//	PAY_RESULT_MSG,SEND_FLAG,SEND_DATE,REG_DATE,SEND_FILE_NAME,

		$rs_cms_amount			= trim($arr_rs[0]["CMS_AMOUNT"]); 
		$rs_res_cms_amount	= trim($arr_rs[0]["RES_CMS_AMOUNT"]); 
	}

	if (($mode == "D")) {

		$arr_rs = selectPaymentInfo($conn, $seq);

		$rs_refund_date			= trim($arr_rs[0]["REFUND_DATE"]);

		$refund_date = left($rs_refund_date,4)."-".substr($rs_refund_date,4,2)."-".right($rs_refund_date,2);


		$rs_refund_memo			= trim($arr_rs[0]["REFUND_MEMO"]); 
		$rs_cms_amount			= trim($arr_rs[0]["REFUND_AMOUNT"]);
		$rs_refund_adm_name	= trim($arr_rs[0]["REFUND_ADM_NAME"]);
		
	}


	if ($result) {
?>	
<script language="javascript">
	alert('정상 처리 되었습니다.');
	opener.js_search();
	self.close();
</script>
<?
		//exit;
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<!--<script type="text/javascript" src="../js/httpRequest.js"></script>--> <!-- Ajax js -->
<style type="text/css">
	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
</style>
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 368px; }
-->
</style>
<script language="javascript">

	$(document).ready(function() {

		var dt	= new Date();
		var y		= dt.getFullYear(); 
		var m		= dt.getMonth() + 1; 
		var d		= dt.getDate() + 1; 
		var h		= dt.getHours(); 

		//if (h >= 17) d++;

		mindt = y+"-"+m+"-"+d;


		$(".date").datepicker({
			dateFormat: "yy-mm-dd"
		//	,minDate: mindt
		//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
	//		,beforeShowDay: disableAllTheseDays
		});
	});

	function disableAllTheseDays(date) { 
		var m = date.getMonth(), d = date.getDate(), y = date.getFullYear(); 
		for (i = 0; i < disabledDays.length; i++) { 
			if($.inArray(y + '-' +(m+1) + '-' + d,disabledDays) != -1) { 
				return [false]; 
			} 
		}

		var noWeekend = jQuery.datepicker.noWeekends(date); 
		return noWeekend[0] ? [true] : noWeekend; 
		//return [true];
	} 


	// 저장 버튼 클릭 시 
	function js_save() {
		
		var frm = document.frm;
		
		if (isNull(frm.sel_pick_date.value)) {
			alert('환불일은 선택해주세요.');
			frm.sel_pick_date.focus();
			return ;		
		}

		if (isNull(frm.refund_amount.value)) {
			alert('환불금액이 잘못되었습니다. 다시 실행해주세요.');
			self.close();
			return ;		
		}

		if (parseInt(frm.cms_amount.value) < parseInt(frm.refund_amount.value)) {
			alert("납부당비 보다 환불금액이 클 수 없습니다.");
			return;
		}

		frm.mode.value = "U";

		frm.method = "post";
		frm.target = "";
		frm.action = "refund_popup.php";
		frm.submit();
	}

	function js_cancel() {

		var frm = document.frm;

		frm.mode.value = "C";

		frm.method = "post";
		frm.target = "";
		frm.action = "refund_popup.php";
		frm.submit();

	}

	$(document).ready(function() {
		this.focus();
	});

</script>

</head>
<body id="popup_code" onload="frm.dcode.focus();">

<form name="frm" method="post">
<input type="hidden" name="mode" value="" >
<input type="hidden" name="seq_no" value="<?=$seq?>">


<div id="popupwrap_code">
	<h1>CMS 출금 환불</h1>
	<div id="postsch">
		<div class="addr_inp">

		<table cellpadding="0" cellspacing="0" width="95%">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
						<colgroup>
							<col width="20%">
							<col width="30%">
							<col width="20%">
							<col width="30%">
						</colgroup>
							<tr>
								<td colspan="4"><b><?= $rs_pcode_nm ?></b></td>
							</tr>
							<tr>
								<th>환불일</th>
								<td colspan="3">
									<input type="Text" name="sel_pick_date" id="sel_pick_date" class="date" value="<?=$refund_date?>" style="width:100px;height:20px;border:1px solid #dfdfdf;padding-left:5px" readonly>
								</td>
							</tr>
							<tr>
								<th>환불금액</th>
								<td colspan="3">
									<input type="Text" name="refund_amount" value="<?=$rs_cms_amount?>" style="width:100px;height:20px;border:1px solid #dfdfdf;padding-left:5px">
									<input type="hidden" name="cms_amount" value="<?=$rs_res_cms_amount?>">
								</td>
							<tr>
							<? if ($mode == "D") { ?>
							<tr>
								<th>환불사유</th>
								<td colspan="3">
									<textarea name="contents" style="width:350px;height:73px;border:1px solid #dfdfdf;padding-left:5px;padding-top:5px"><?=$rs_refund_memo?></textarea>
								</td>
							<tr>
							<tr>
								<th>환불관리자</th>
								<td colspan="3">
									<?=$rs_refund_adm_name?>
								</td>
							<tr>
							<? } else { ?>
							<tr>
								<th>환불사유</th>
								<td colspan="3">
									<textarea name="contents" style="width:350px;height:100px;border:1px solid #dfdfdf;padding-left:5px;padding-top:5px"></textarea>
								</td>
							<tr>
							<? } ?>
					</table>
				</td>
			</tr>
		</table>
		</div>
		<div class="btn">
			<? if ($mode == "D") { ?>
			<input type="button" name="aa" value="   수  정   " class="btntxt"  style="cursor:pointer;height:25px;" onclick="js_save();"> 
			<input type="button" name="bb" value=" 환불취소 " class="btntxt"  style="cursor:pointer;height:25px;" onclick="js_cancel();"> 
			<? } else { ?>
			<input type="button" name="aa" value=" 환불처리 " class="btntxt"  style="cursor:pointer;height:25px;" onclick="js_save();"> 
			<? } ?>
		</div>
	</div>
	<br />
	<div class="bot_close2"><a href="javascript: window.close();"><img src="../images/admin/icon_pclose.gif" alt="닫기" /></a></div>
</div>

<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>