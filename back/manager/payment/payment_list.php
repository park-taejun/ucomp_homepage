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
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다
	//$menu_cd="0501";

	$menu_right = "PM002"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/payment/payment.php";
#====================================================================
# Request Parameter
#====================================================================

	// 로그인한 관리자 권한에 따라 보여주는 내용이 다릅니다. -- 여기 부터

	if (($_SESSION['s_adm_position_code'] == "") || ($_SESSION['s_adm_position_code'] == "중앙당")) {
		$sel_area_cd = trim($sel_area_cd);
	} else {
		$sel_area_cd = $_SESSION['s_adm_position_code'];
	}

	if ((($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) && ($_SESSION['s_adm_organization'] == "")) {
		$sel_party = trim($sel_party);
	} else {
		$sel_party = $_SESSION['s_adm_dept_code'];
	}

	if ($_SESSION['s_adm_organization'] == "") {
		$Ngroup_cd = trim($Ngroup_cd);
	} else {
		if (strlen(trim($Ngroup_cd)) > strlen($_SESSION['s_adm_organization'])) {
			$Ngroup_cd = trim($Ngroup_cd);
		} else {
			$Ngroup_cd = $_SESSION['s_adm_organization'];
		}
	}
	
	// --------------------------------------------------------- 여기 까지

	$arr_year = getPaymentYear($conn);

	if ($mode == "U") {

		$row_cnt = count($M_NO);
		for ($k = 0; $k < $row_cnt; $k++) {

			$tmp_b_no = (int)$M_NO[$k];
			$m_levels=(int)$m_level[$k];
			$result= changeMemberlevel($conn, $tmp_b_no, $m_levels);

		}
	}

	if (($mode == "U")&&($result)) {
		?>
		<script type="text/javascript">
		<!--
			alert('수정되었습니다.');
		//-->
		</script>
		<?
	}

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
	
	if ($order_field == "")
		$order_field = "M_DATETIME";
	
	if ($order_str=="")
		$order_str = "DESC";

#============================================================
# Page process
#============================================================

	if (($nPage <> "") && ($nPage <> 0)) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

	$is_direct = "Y";

#===============================================================
# Get Search list count
#===============================================================
	$nListCnt =totalCntCmsPayment($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $pay_reason, $Ngroup_cd, $p_seq_no, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listCmsPayment($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $pay_reason, $Ngroup_cd, $p_seq_no, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$arr_sum_rs			= listSumCmsPayment($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $Ngroup_cd, $p_seq_no, $search_field, $search_str);
	$SUM_CMS_AMOUNT = listSumCmsAmount($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $Ngroup_cd, $p_seq_no, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "직접 당비 납부 조회", "List");

	if ($Ngroup_cd) {

		if(strlen($Ngroup_cd) == 3){
			$group_cd_01=$Ngroup_cd;
		}
		if(strlen($Ngroup_cd) == 6){
			$group_cd_01=substr($Ngroup_cd,0,3);
			$group_cd_02=substr($Ngroup_cd,0,6);
		}
		if(strlen($Ngroup_cd) == 9){
			$group_cd_01=substr($Ngroup_cd,0,3);
			$group_cd_02=substr($Ngroup_cd,0,6);
			$group_cd_03=substr($Ngroup_cd,0,9);
		}

		if(strlen($Ngroup_cd) == 12){
			$group_cd_01=substr($Ngroup_cd,0,3);
			$group_cd_02=substr($Ngroup_cd,0,6);
			$group_cd_03=substr($Ngroup_cd,0,9);
			$group_cd_04=substr($Ngroup_cd,0,12);
		}

		if(strlen($Ngroup_cd) == 15){
			$group_cd_01=substr($Ngroup_cd,0,3);
			$group_cd_02=substr($Ngroup_cd,0,6);
			$group_cd_03=substr($Ngroup_cd,0,9);
			$group_cd_04=substr($Ngroup_cd,0,12);
			$group_cd_05=substr($Ngroup_cd,0,15);
		}
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

<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">


	$(document).ready(function() {

		$( "#search_str" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});

		$(document).on("change","#group_cd_01", function(){
			js_sel_party($("#group_cd_01").val(), '');
		});

		$(document).on("change","#group_cd_02", function(){
			js_sel_party($("#group_cd_01").val(), $("#group_cd_02").val());
		});

		$(document).on("change","#group_cd_03", function(){
			js_sel_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val());
		});

		$(document).on("change","#group_cd_04", function(){
			js_sel_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val(), $("#group_cd_04").val());
		});

		$(document).on("change","#group_cd_05", function(){
			document.frm.Ngroup_cd.value=$("#group_cd_05").val();
		});

	});


	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_del_mem(seq_no) {
		var frm = document.frm;
		bDelOK = confirm('선택하신 회원을 탈퇴처리 하시겠습니까?');
		if (bDelOK) {
			frm.mode.value = "D";
			frm.seq_no.value = seq_no;
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_modify(seq) {

		var frm = document.frm;
		frm.seq_no.value = seq;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "payment_modify.php";
		frm.submit();
		
	}

	function js_write(){

		var frm = document.frm;
		frm.target = "";
		frm.method = "get";
		frm.action = "payment_write.php";
		frm.submit();
		
	}

	function js_modify2() {

		var frm = document.frm;
		
		/*
		var chk_cnt = 0;
		check=document.getElementsByName("chk[]");
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
		if (chk_cnt == 0) {
			alert("선택 하신 회원이 없습니다.");
		} else {
			bDelOK = confirm('선택하신 회원의 정보를 수정하시겠습니까?');
		
			if (bDelOK==true) {
				frm.mode.value = "U";
				frm.target = "";
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
		*/
		bDelOK = confirm('회원의 등급을 수정하시겠습니까?');
		if (bDelOK==true) {
			frm.mode.value = "U";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_delete() {

		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
	
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
	
		if (chk_cnt == 0) {
			alert("선택 하신 회원이 없습니다.");
		} else {

			bDelOK = confirm('선택하신 회원을 탈퇴처리 하시겠습니까?');
		
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
	}

	function js_excel_list() {
		var frm = document.frm;
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER[PHP_SELF])?>";
		frm.submit();

	}

	function js_sel_party(group_cd_01, group_cd_02, group_cd_03, group_cd_04) {

		var frm = document.frm;
		var party_val = "";

		party_val = frm.sel_party.value;

		if (party_val == "") {
			$("#add_group").hide();
		} else {
		
			var request = $.ajax({
				url:"/_common/get_next_group.php",
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

		$("#Ngroup_cd").val("");
		if ((group_cd_01 != "") && (group_cd_01 != null)) $("#Ngroup_cd").val(group_cd_01);
		if ((group_cd_02 != "") && (group_cd_02 != null)) $("#Ngroup_cd").val(group_cd_02);
		if ((group_cd_03 != "") && (group_cd_03 != null)) $("#Ngroup_cd").val(group_cd_03);
		if ((group_cd_04 != "") && (group_cd_04 != null)) $("#Ngroup_cd").val(group_cd_04);
	}

	function js_refund_pop(seq, mode){
		window.open('refund_popup.php?mode='+mode+'&seq='+seq,'refund','width=520,height=310,scrollbars=no');
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

<form id="bbsList" name="frm" method="post" action="javascript:js_serch();">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
<input type="hidden" name="Ngroup_cd" id="Ngroup_cd" value="<?=$Ngroup_cd?>"/>

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							
							<tr>
								<th>소속지역</th>
								<td>
									<?
										if (($_SESSION['s_adm_position_code'] == "") || ($_SESSION['s_adm_position_code'] == "중앙당")) {
									?>
									<?= makeSelectBox($conn,"AREA_CD","sel_area_cd", "250","소속지역 선택", "",$sel_area_cd);?>
									<?
										} else {
									?>
									<?=getDcodeName($conn, "AREA_CD", $_SESSION['s_adm_position_code'])?>
									<input type="hidden" name="sel_area_cd" value="<?=$_SESSION['s_adm_position_code']?>">
									<?
										}
									?>
								</td>
								<th>소속당</th>
								<td>
									<?
										if ((($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) && ($_SESSION['s_adm_organization'] == "")) {
									?>
									<?= makeSelectBoxOnChange($conn,"PARTY","sel_party", "250","소속당 선택", "",$sel_party);?>
									<?
										} else {
									?>
									<?=getDcodeName($conn, "PARTY", $_SESSION['s_adm_dept_code'])?>
									<input type="hidden" name="sel_party" value="<?=$_SESSION['s_adm_dept_code']?>">
									<?
										}
									?>
								</td>
							</tr>

							<tr id="add_group" style="display:none">
								<th>소속조직</th>
								<td colspan="3">
								<div class="sp5"></div>
								<div id="group_div"><select name="group_cd_01" id="group_cd_01" style="width:200px;"></select></div>
								
								<div class="sp5"></div>
								</td>
							</tr>
	
							<tr>
								<th>당비구분</th>
								<td >
									<?= makeSelectBox($conn,"PARTY_MEM_FEES","pay_reason","150","당비구분 선택","",$pay_reason)?>
								</td>
								<th>신청년월</th>
								<td >
									<select style="width:100px" name="sel_pay_yyyy">
										<option value="">년 선택</option>
										<?
											if (sizeof($arr_year) > 0) {
												for ($m = 0 ; $m < sizeof($arr_year); $m++) {
													$RS_PAY_YYYY	= trim($arr_year[$m]["PAY_YYYY"]);

													if ($sel_pay_yyyy == $RS_PAY_YYYY) {
										?>
										<option value="<?=$RS_PAY_YYYY?>" selected><?=$RS_PAY_YYYY?></option>
										<?
													} else {
										?>
										<option value="<?=$RS_PAY_YYYY?>"><?=$RS_PAY_YYYY?></option>
										<?
													}
												}
											}
										?>
									</select>
									<?= makeSelectBox($conn,"MONTH","sel_pay_mm", "100","월 선택", "",$sel_pay_mm);?>
								</td>
							</tr>


							<tr class="last">
								<th>처리결과</th>
								<td>
									<?= makeSelectBox($conn,"CMS_PAY_STATE","sel_pay_state", "150","처리결과 선택", "",$sel_pay_state);?>
								</td>
								<!--
								<th>납부방법</th>
								<td>
									<select name="sel_pay_type" style="width:150px">
										<option value="">납부방법 선택</option>
										<option value="D" <? if ($sel_pay_type == "D") echo "selected"; ?>>직접납부</option>
									</select>
								</td>
								-->

								<th>검색조건</th>
								<td>
									<select name="nPageSize" style="width:84px;">
										<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >20개씩</option>
										<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >50개씩</option>
										<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >100개씩</option>
										<option value="200" <? if ($nPageSize == "200") echo "selected"; ?> >200개씩</option>
										<option value="300" <? if ($nPageSize == "300") echo "selected"; ?> >300개씩</option>
										<option value="400" <? if ($nPageSize == "400") echo "selected"; ?> >400개씩</option>
										<option value="500" <? if ($nPageSize == "500") echo "selected"; ?> >500개씩</option>
									</select>&nbsp;
									<select name="search_field" style="width:84px;">
										<option value="M_NAME" <? if ($search_field == "M_NAME") echo "selected"; ?> >성명</option>
										<!--
										<option value="M_NICK" <? if ($search_field == "M_NICK") echo "selected"; ?> >닉네임</option>
										-->
										<option value="M_ID" <? if ($search_field == "M_ID") echo "selected"; ?> >아이디</option>
										<option value="M_EMAIL" <? if ($search_field == "M_EMAIL") echo "selected"; ?> >이메일</option>
										<option value="M_3" <? if ($search_field == "M_3") echo "selected"; ?> >소속당</option>
									</select>&nbsp;

									<input type="text" value="<?=$search_str?>" name="search_str" id="search_str" size="15"class="txt" />
									<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 <?=number_format($nListCnt)?>건</li>
						</ul>
						<p class="fRight">
							<? if ($sPageRight_I == "Y") { ?>
							<a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a>
							<? } ?>
						</p>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="4%" /><!-- 번호 -->
							<col width="5%" /><!-- 아이디 -->
							<col width="6%" /><!-- 이름 -->
							<col width="6%" /><!-- 생년월일 -->
							<col width="9%" /><!-- 광역시/도 -->
							<col width="5%" /><!-- 출금년월 -->
							<col width="6%" /><!-- 당비구분 -->
							<col width="7%" /><!-- 약정당비 -->
							<col width="7%" /><!-- 납부당비 -->
							<col width="6%" /><!-- 실제출금일 -->
							<col width="6%" /><!-- 출금수수료 -->
							<col width="6%" /><!-- 환불금액 -->
							<col width="4%" /><!-- 출금상태 -->
							<col width="8%" /><!-- 처리결과 -->
							<col width="8%" /><!-- 출금신청일 -->
							<col width="7%" /><!-- 환불 -->
						</colgroup>

						<thead>
							<tr>
								<th>번호</th>
								<th>아이디</th>
								<th>이름</th>
								<th>생년월일</th>
								<th>광역시/도</th>
								<th>신청년월</th>
								<th>당비구분</th>
								<th>약정당비</th>
								<th>납부당비</th>
								<th>실제출금일</th>
								<th>출금수수료</th>
								<th>환불금액</th>
								<th>출금상태</th>
								<th>처리결과</th>
								<th>출금신청일</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>

						<?
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									//A.MEM_NO, A.MEM_TYPE AS M_MEM_TYPE, A.MEM_NM, A.MEM_NICK, 
									//A.MEM_ID, A.BIRTH_DATE, A.HPHONE, A.USE_TF, B.SEQ_NO, B.COMM_NO, B.MEM_TYPE

									$rn								= trim($arr_rs[$j]["rn"]);
									$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
									$M_NO							= trim($arr_rs[$j]["M_NO"]);
									$M_ID							= trim($arr_rs[$j]["M_ID"]);
									$M_NAME						= trim($arr_rs[$j]["M_NAME"]);
									$SIDO							= trim($arr_rs[$j]["SIDO"]);
									$PAY_YYYY					= trim($arr_rs[$j]["PAY_YYYY"]);
									$PAY_MM						= trim($arr_rs[$j]["PAY_MM"]);
									$PAY_TYPE					= trim($arr_rs[$j]["PAY_TYPE"]);
									$CMS_AMOUNT				= trim($arr_rs[$j]["CMS_AMOUNT"]);
									$RES_CMS_AMOUNT		= trim($arr_rs[$j]["RES_CMS_AMOUNT"]);
									$RES_PAY_DATE			= trim($arr_rs[$j]["RES_PAY_DATE"]);
									$RES_PAY_DATE			= trim($arr_rs[$j]["RES_PAY_DATE"]);
									$CMS_CHARGE 			= trim($arr_rs[$j]["CMS_CHARGE"]);
									$RES_PAY_NO				= trim($arr_rs[$j]["RES_PAY_NO"]);
									$PAY_RESULT				= trim($arr_rs[$j]["PAY_RESULT"]);
									$PAY_RESULT_CODE	= trim($arr_rs[$j]["PAY_RESULT_CODE"]);
									$PAY_RESULT_MSG		= trim($arr_rs[$j]["PAY_RESULT_MSG"]);
									$SEND_FLAG				= trim($arr_rs[$j]["SEND_FLAG"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
									$M_BIRTH					= trim($arr_rs[$j]["M_BIRTH"]);
									$PAY_REASON			= trim($arr_rs[$j]["PAY_REASON"]);
									$REFUND_AMOUNT		= trim($arr_rs[$j]["REFUND_AMOUNT"]);

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

							<tr <? if ($j == (sizeof($arr_rs)-1)) {?> class="last" <? } ?> >
								<td><?=$rn?></td>
								<td><a href="javascript:js_modify('<?=$SEQ_NO?>');"><?=$M_ID?></a></td>
								<td><a href="javascript:js_modify('<?=$SEQ_NO?>');"><?=$M_NAME?></a></td>
								<td><?=$M_BIRTH?></td>
								<td><?=$SIDO?></td>
								<td><?=$PAY_YYYY?>-<?=$PAY_MM?></td>
								<td><?=$PAY_REASON?></td>
								
								<td style="text-align:right;padding-right:20px"><?=number_format($CMS_AMOUNT)?></td>
								<td style="text-align:right;padding-right:20px"><?=number_format($RES_CMS_AMOUNT)?></td>
								<td><?=$RES_PAY_DATE?></td>
								<td style="text-align:right;padding-right:20px"><?=number_format($CMS_CHARGE)?></td>
								<td style="text-align:right;padding-right:20px"><?=number_format($REFUND_AMOUNT)?></td>
								<td><font color="<?=$str_color?>"><?=$PAY_RESULT?></font></td>
								<td><font color="<?=$str_color?>"><?=$PAY_RESULT_MSG?></font></td>
								<td><?=substr($REG_DATE ,0,10)?></td>
								<td>
								<?if($PAY_RESULT=="Y"){?>
								<input type="button" name="aa" value=" 환불 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="js_refund_pop('<?=$SEQ_NO?>','S');">
								<?}?>
								<?if($PAY_RESULT=="R"){?>
								<input type="button" name="aa" value=" 조회 " style="border:1px solid #dfdfdf;cursor:pointer;height:25px;" onclick="js_refund_pop('<?=$SEQ_NO?>','D');">
								<?}?>
								</td>
							</tr>
						<?			
								}
							} else { 
						?>
							<tr>
								<td align="center" height="50" colspan="16">데이터가 없습니다. </td>
							</tr>
						<? 
							}
						?>

						</tbody>
					</table>
				</fieldset>

					<?
						if (sizeof($arr_sum_rs) > 0) {

							//$SUM_CMS_AMOUNT							= trim($arr_sum_rs[0]["SUM_CMS_AMOUNT"]);
							$SUM_RES_CMS_AMOUNT					= trim($arr_sum_rs[0]["SUM_RES_CMS_AMOUNT"]);
							$SUM_REF_CMS_AMOUNT					= trim($arr_sum_rs[0]["SUM_REF_CMS_AMOUNT"]);
							$SUM_CMS_CHARGE							= trim($arr_sum_rs[0]["SUM_CMS_CHARGE"]);

					?>
					<br><br>
					<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
						<caption>내용 입력란</caption>
						<colgroup>
							<col width="10%" />
							<col width="15%" />
							<col width="10%" />
							<col width="15%" />
							<col width="10%" />
							<col width="15%" />
							<col width="10%" />
							<col width="15%" />
						</colgroup>
						<tr>
							<th>약정 총액</th>
							<td><b><?=number_format($SUM_CMS_AMOUNT)?></b> 원</td>
							<th>납부 총액</th>
							<td><b><?=number_format($SUM_RES_CMS_AMOUNT)?></b> 원</td>
							<th>환불 총액</th>
							<td><b><?=number_format($SUM_REF_CMS_AMOUNT)?></b> 원</td>
							<th>수수료 총액</th>
							<td><b><?=number_format($SUM_CMS_CHARGE)?></b> 원</td>
						</tr>
					</table>
					<?
						}
					?>

			</form>
			<div class="btnArea">
				<ul class="fRight">
					<!--
					<input type="button" name="aa" value=" 엑셀 리스트 " class="btntxt"  style="cursor:hand" onclick="js_excel_list();"> 
					-->
				</ul>
			</div>
			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&sel_pay_yyyy=".$sel_pay_yyyy."&sel_pay_mm=".$sel_pay_mm."&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&sel_pay_state=".$sel_pay_state."&Ngroup_cd=".$Ngroup_cd;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>

					<!-- --------------------- 페이지 처리 화면 END -------------------------->
		</div>
		</section>
		<iframe src="about:blank" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
	if($Ngroup_cd || $sel_party) {
?>
	<script type="text/javascript">
	<!--
		js_sel_party('<?=$group_cd_01?>', '<?=$group_cd_02?>', '<?=$group_cd_03?>', '<?=$group_cd_04?>', '<?=$group_cd_05?>');
	//-->
	</script>
<?
	}

#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
