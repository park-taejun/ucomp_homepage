<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : equipment_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-10-16
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
	$menu_right = "EQ002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/equipment/equipment.php";

	$mm_subtree	 = "4";

	$eq_coname	= SetStringToDB($eq_coname);
	$eq_model		= SetStringToDB($eq_model);
	$eq_info01	= SetStringToDB($eq_info01);
	$eq_info02	= SetStringToDB($eq_info02);
	$eq_info03	= SetStringToDB($eq_info03);
	$eq_info04	= SetStringToDB($eq_info04);
	$eq_info05	= SetStringToDB($eq_info05);
	$eq_info06	= SetStringToDB($eq_info06);
	$eq_info07	= SetStringToDB($eq_info07);
	$eq_memo		= SetStringToDB($eq_memo);

	#echo $adm_no;

#====================================================================
# DML Process
#====================================================================
	if ($mode == "I") {
		
		// 입고 등록
		if (($eq_type == "EQ001") || ($eq_type == "EQ002")) {
	
			$arr_data = array("EQ_TYPE"=>$eq_type,
												"EQ_CONAME"=>$eq_coname,
												"EQ_MDATE"=>$eq_mdate,
												"EQ_MODEL"=>$eq_model,
												"EQ_INFO01"=>$eq_info01,
												"EQ_INFO02"=>$eq_info02,
												"EQ_INFO03"=>$eq_info03,
												"EQ_INFO04"=>$eq_info04,
												"EQ_INFO05"=>$eq_info05,
												"EQ_INDATE"=>$eq_indate,
												"EQ_RECDATE"=>$eq_indate,
												"EQ_RETDATE"=>$eq_retdate,
												"EQ_DISDATE"=>$eq_disdate,
												"EQ_MEMO"=>$eq_memo,
												"EQ_USER"=>$eq_user,
												"EQ_STATE"=>$eq_state,
												"REG_ADM"=>$_SESSION['s_adm_no']
												);
		
		} else if ($eq_type == "EQ003") {

			$arr_data = array("EQ_TYPE"=>$eq_type,
												"EQ_CONAME"=>$eq_coname,
												"EQ_MDATE"=>$eq_mdate,
												"EQ_MODEL"=>$eq_model,
												"EQ_INFO06"=>$eq_info06,
												"EQ_INDATE"=>$eq_indate,
												"EQ_RECDATE"=>$eq_indate,
												"EQ_RETDATE"=>$eq_retdate,
												"EQ_DISDATE"=>$eq_disdate,
												"EQ_MEMO"=>$eq_memo,
												"EQ_USER"=>$eq_user,
												"EQ_STATE"=>$eq_state,
												"REG_ADM"=>$_SESSION['s_adm_no']
												);

		} else {

			$arr_data = array("EQ_TYPE"=>$eq_type,
												"EQ_CONAME"=>$eq_coname,
												"EQ_MDATE"=>$eq_mdate,
												"EQ_MODEL"=>$eq_model,
												"EQ_INDATE"=>$eq_indate,
												"EQ_RECDATE"=>$eq_indate,
												"EQ_RETDATE"=>$eq_retdate,
												"EQ_DISDATE"=>$eq_disdate,
												"EQ_MEMO"=>$eq_memo,
												"EQ_USER"=>$eq_user,
												"EQ_STATE"=>$eq_state,
												"REG_ADM"=>$_SESSION['s_adm_no']
												);
		}

		$new_eq_no =  insertEquipment($conn, $arr_data);
		$str_eq_cd = $eq_type."-".right("0000000000".$new_eq_no, 8);
		
		// 자재번호 업데이트
		$arr_data = array("EQ_CD"=>$str_eq_cd);
		$result = updateEquipment($conn, $arr_data, $new_eq_no);

		// 자재 입고 히스토리 등록

		$arr_data = array("EQ_NO"=>$new_eq_no,
											"EQ_CD"=>$str_eq_cd,
											"EQ_RECDATE"=>$eq_indate,
											"EQ_NEXTUSER"=>$eq_user,
											"EQ_STATE"=>"입고",
											"REG_ADM"=>$_SESSION['s_adm_no']
											);
		$new_eqh_no =  insertEquipmentHistory($conn, $arr_data);
		

	}

	if ($mode == "S") {

		$arr_rs = selectEquipment($conn, $eq_no);

		$rs_eq_no					= trim($arr_rs[0]["EQ_NO"]); 
		$rs_eq_cd					= trim($arr_rs[0]["EQ_CD"]); 
		$rs_eq_type				= trim($arr_rs[0]["EQ_TYPE"]); 
		$rs_eq_coname			= SetStringFromDB($arr_rs[0]["EQ_CONAME"]); 
		$rs_eq_mdate			= trim($arr_rs[0]["EQ_MDATE"]); 
		$rs_eq_model			= SetStringFromDB($arr_rs[0]["EQ_MODEL"]); 
		$rs_eq_info01			= SetStringFromDB($arr_rs[0]["EQ_INFO01"]); 
		$rs_eq_info02			= SetStringFromDB($arr_rs[0]["EQ_INFO02"]); 
		$rs_eq_info03			= SetStringFromDB($arr_rs[0]["EQ_INFO03"]); 
		$rs_eq_info04			= SetStringFromDB($arr_rs[0]["EQ_INFO04"]); 
		$rs_eq_info05			= SetStringFromDB($arr_rs[0]["EQ_INFO05"]); 
		$rs_eq_info06			= SetStringFromDB($arr_rs[0]["EQ_INFO06"]); 
		$rs_eq_info07			= SetStringFromDB($arr_rs[0]["EQ_INFO07"]); 
		$rs_eq_info08			= SetStringFromDB($arr_rs[0]["EQ_INFO08"]); 
		$rs_eq_indate			= trim($arr_rs[0]["EQ_INDATE"]); 
		$rs_eq_recdate		= trim($arr_rs[0]["EQ_RECDATE"]); 
		$rs_eq_retdate		= trim($arr_rs[0]["EQ_RETDATE"]); 
		$rs_eq_disdate		= trim($arr_rs[0]["EQ_DISDATE"]); 
		$rs_eq_memo				= SetStringFromDB($arr_rs[0]["EQ_MEMO"]); 
		$rs_eq_user				= trim($arr_rs[0]["EQ_USER"]); 
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_eq_type=".$con_eq_type."&con_eq_user=".$con_eq_user."&con_eq_state=".$con_eq_state."&search_field=".$search_field."&search_str=".$search_str;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "equipment_list.php<?=$strParam?>";
</script>
<?
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
	frm.action = "equipment_list.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var eq_no = "<?= $eq_no ?>";
	
	if (frm.eq_type.value == "") {
		alert('등록 자재를 선택해주세요.');
		frm.eq_type.focus();
		return ;		
	}

	if (isNull(frm.eq_model.value)) {
		alert('모델명을 입력해주세요.');
		frm.eq_model.focus();
		return ;		
	}

	if (frm.eq_state.value == "") {
		alert('상태를 선택해주세요.');
		frm.eq_state.focus();
		return ;		
	}

	if (isNull(eq_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.eq_no.value = frm.eq_no.value;
	}

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}


function js_eq_type() {
	var eq_type = $("#eq_type").val();
	
	$("#eq01").hide();
	$("#eq02").hide();
	$("#eq03").hide();
	
	if ((eq_type == "EQ001") || (eq_type == "EQ002")) {
		$("#eq01").show();
		$("#eq02").show();
	}

	if (eq_type == "EQ003") {
		$("#eq03").show();
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
<input type="hidden" name="eq_no" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="con_eq_type" value="<?=$con_eq_type?>">
<input type="hidden" name="con_eq_user" value="<?=$con_eq_user?>">
<input type="hidden" name="con_eq_state" value="<?=$con_eq_state?>">
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
								<th>등록 자재</th>
								<td colspan="5">
									<span class="optionbox">
										<?= makeSelectBoxOnChange($conn, "EQUIPMENT" , "eq_type", "", "자재 선택", "", $rs_eq_type); ?>
									</span>
								</td>
							</tr>
							<tr>
								<th>모델명</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:95%" name="eq_model" value="<?=$rs_eq_model?>" /></span></td>
								<th>제조사</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:95%" name="eq_coname" value="<?=$rs_eq_coname?>" /></span></td>
								<th>제조일</th>
								<td><span class="inpbox"><input type="text" class="txt date" style="width:120px" name="eq_mdate" value="<?=$rs_eq_mdate?>" autocomplete="off" /></span></td>
							</tr>

							<tr id="eq01" style="display:none">
								<th>CPU</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:65%" name="eq_info01" value="<?=$rs_eq_info01?>" /></span></td>
								<th>RAM</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:65%" name="eq_info02" value="<?=$rs_eq_info02?>" /></span></td>
								<th>GPU</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:65%" name="eq_info03" value="<?=$rs_eq_info03?>" /></span></td>
							</tr>

							<tr id="eq02" style="display:none">
								<th>HDD</th>
								<td><span class="inpbox"><input type="text" class="txt" style="width:65%" name="eq_info04" value="<?=$rs_eq_info04?>" /></span></td>
								<th>OS</th>
								<td colspan="3"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="eq_info05" value="<?=$rs_eq_info05?>" /></span></td>
							</tr>

							<tr id="eq03" style="display:none">
								<th>해상도</th>
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:35%" name="eq_info06" value="<?=$rs_eq_info06?>" /></span></td>
							</tr>

							<tr>
								<th>메모</th>
								<td colspan="5">
									<span class="textareabox">
										<textarea class="txt" name="eq_memo"><?=$rs_eq_memo?></textarea>
									</span>	
								</td>
							</tr>

							<tr>
								<th>사용자</th>
								<td>
									<span class="optionbox">
										<?=makeEmpSelectBox($conn, "eq_user" , "" , "미지급", "NONE", $rs_eq_user)?>
									</span>
									<input type="hidden" name="old_eq_user" value="<?=$rs_eq_user?>"> 
								</td>
								<th>상태</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn, "EQ_STATE" , "eq_state", "", "상태 선택", "", $rs_eq_state); ?>
									</span>
									<input type="hidden" name="old_eq_state" value="<?=$rs_eq_state?>"> 
								</td>
								<th>입고일</th>
								<td colspan="3">
									<span class="inpbox"><input type="text" class="txt date" name="eq_indate" value="<?=$rs_eq_indate?>" autocomplete="off" /></span>
									<input type="hidden" name="old_eq_indate" value="<?=$rs_eq_indate?>">
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="btnright">
				<? if ($eq_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
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