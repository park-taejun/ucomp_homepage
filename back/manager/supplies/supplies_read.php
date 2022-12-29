<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : supplies_read.php
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

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	
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
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm				= trim($arr_rs[0]["REG_ADM"]); 

		if ($rs_reg_adm == $_SESSION['s_adm_no']) {
			$sPageRight_U = "Y";
			if ($rs_ask_state == "0") {
				$sPageRight_D = "Y";
			}
		}
	}

	if ($mode == "D") {
		
		echo "삭제";

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
<script type="text/javascript">

function js_list() {
	var frm = document.frm;
		
	frm.method = "post";
	frm.action = "supplies_list.php";
	frm.submit();
}

function js_modify() {
	var frm = document.frm;

	frm.mode.value = "S";
	frm.method = "post";
	frm.action = "supplies_write.php";
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
				<h3><strong><?=$p_menu_name?></strong>
				</h3>

<form name="frm" method="post">
<input type="hidden" name="su_no" value="<?=$su_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="con_su_type" value="<?=$con_su_type?>">
<input type="hidden" name="con_ask_adm_no" value="<?=$con_ask_adm_no?>">
<input type="hidden" name="con_ask_state" value="<?=$con_ask_state?>">
<input type="hidden" name="con_buy_state" value="<?=$con_buy_state?>">
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="boardlist search">
				
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
									<?=$rs_title?>
								</td>
							</tr>

							<tr>
								<th>구매 품목</th>
								<td colspan="5">
									<?= getDcodeName($conn, "EQUIPMENT", $rs_su_type); ?>
								</td>
							</tr>
							<tr>
								<th>모델명</th>
								<td colspan="3"><?=$rs_su_model?></td>
								<th>예상가격</th>
								<td><?=number_format($rs_su_price)?></td>
							</tr>

							<tr>
								<th>구매링크</th>
								<td colspan="5">
									<?
										$temp_link = $rs_buy_link;
										$temp_link = str_replace("http://","",$temp_link);
										$temp_link = str_replace("https://","",$temp_link);
										$temp_link = "//".$temp_link;
									?>
									<a href="<?=$temp_link?>" target="_blank"><?=$rs_buy_link?></a>
								</td>
							</tr>

							<tr>
								<th>상세내용</th>
								<td colspan="5">
									<?=nl2br($rs_memo)?>
								</td>
							</tr>

							<tr>
								<th>기안자</th>
								<td>
									<?=getEmpInfo($conn, $rs_ask_adm_no);?>
								</td>
								<th>신청일</th>
								<td>
									<?=$rs_ask_date?>
								</td>
								<th>신청상태</th>
								<td>
									<?= getDcodeName($conn, "ASK_STATE", $rs_ask_state) ?>
								</td>
							</tr>
						</tbody>
					</table>


				</div>
				<div class="btnright">
				<? if ($su_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_modify();" style="width:100px">수정</button>
					<? } ?>
					<? if ($sPageRight_D == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					<? } ?>
				<? }?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>

				<h2>구매 내역</h2>  

				<div class="boardlist search">

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
								<th>구매상태</th>
								<td colspan="5">
									<?= getDcodeName($conn, "BUY_STATE", $rs_buy_state) ?>
								</td>
							</tr>

							<tr>
								<th>구매처</th>
								<td colspan="5">
									<?= $rs_buy_company ?>
								</td>
							</tr>

							<tr>
								<th>구매일</th>
								<td><?=$rs_buy_date?></td>
								<th>구매방식</th>
								<td><?=getDcodeName($conn, "PAY_TYPE", $rs_pay_type) ?></td>
								<th>구매가</th>
								<td>
									<? if ($rs_buy_price <> "") { ?>
									<?=number_format($rs_buy_price)?>
									<? } ?>
								</td>
							</tr>

							<tr>
								<th>구매메모</th>
								<td colspan="5">
									<?=nl2br($rs_buy_memo)?>
								</td>
							</tr>
						</tbody>
					</table>

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