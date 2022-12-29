<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : request_write.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-23
# Modify Date  : 
#	Copyright    : Copyright @Ucomp Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	// $menu_right = "RQ002"; // 메뉴마다 셋팅 해 주어야 합니다
	$menu_right = "QR001"; // 메뉴마다 셋팅 해 주어야 합니다  
	

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
	require "../../_classes/biz/request/request.php";

#====================================================================
# Request Parameter
#====================================================================

	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$request_no				= $_POST['request_no']!=''?$_POST['request_no']:$_GET['request_no'];
	$nPage					= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize				= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field			= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str				= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$con_request_cate		= $_POST['con_request_cate']!=''?$_POST['con_request_cate']:$_GET['con_request_cate'];
	$con_reply_state		= $_POST['con_reply_state']!=''?$_POST['con_reply_state']:$_GET['con_reply_state'];

	// 답변 정보
	$request_reply			= $_POST['request_reply']!=''?$_POST['request_reply']:$_GET['request_reply'];
	$reply_state			= $_POST['reply_state']!=''?$_POST['reply_state']:$_GET['reply_state'];

	$mode					= trim($mode);

	#List Parameter
	$nPage					= trim($nPage);
	$nPageSize				= trim($nPageSize);

	$con_request_cate		= trim($con_request_cate);
	$con_reply_state		= trim($con_reply_state);

	$search_field			= trim($search_field);
	$search_str				= trim($search_str);

	$request_reply			= SetStringToDB($request_reply);
	$reply_state			= trim($reply_state);
	
	$result	= false  ;

#====================================================================
# DML Process
#====================================================================
	
	
	if ($mode == "U") {
		
		$reply_date = date("Y-m-d H:i:s",strtotime("0 month"));

		$arr_data = array("REQUEST_REPLY"=>$request_reply,
											"REQUEST_REPLY_ADM"=>$_SESSION['s_adm_no'],
											"REPLY_DATE"=>$reply_date,
											"REPLY_STATE"=>$reply_state
										);

		$result =  updateRequest($conn, $arr_data, $request_no);
	}

	if ($mode == "D") {
		$result = deleteRequest($conn,$request_no);
	}


	if ($mode == "S") {

		$arr_rs = selectRequest($conn, $request_no);

		$rs_request_no					= trim($arr_rs[0]["REQUEST_NO"]); 
		$rs_request_cate				= trim($arr_rs[0]["REQUEST_CATE"]); 
		$rs_request_name				= SetStringFromDB($arr_rs[0]["REQUEST_NAME"]); 
		$rs_request_tel					= trim($arr_rs[0]["REQUEST_TEL"]); 
		$rs_request_email				= trim($arr_rs[0]["REQUEST_EMAIL"]); 
		$rs_request_title				= SetStringFromDB($arr_rs[0]["REQUEST_TITLE"]); 
		$rs_request_ip					= trim($arr_rs[0]["REQUEST_IP"]); 
		$rs_request_contents			= SetStringFromDB($arr_rs[0]["REQUEST_CONTENTS"]); 
		$rs_file_nm						= trim($arr_rs[0]["FILE_NM"]); 
		$rs_file_rnm					= trim($arr_rs[0]["FILE_RNM"]); 
		$rs_request_reply				= SetStringFromDB($arr_rs[0]["REQUEST_REPLY"]); 
		$rs_request_reply_adm			= trim($arr_rs[0]["REQUEST_REPLY_ADM"]); 
		$rs_reply_date					= trim($arr_rs[0]["REPLY_DATE"]); 
		$rs_reply_state					= trim($arr_rs[0]["REPLY_STATE"]); 
		$rs_use_tf						= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf						= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_date					= trim($arr_rs[0]["REG_DATE"]); 
		$rs_up_adm						= trim($arr_rs[0]["UP_ADM"]); 
		$rs_up_date						= trim($arr_rs[0]["UP_DATE"]); 
		$rs_del_adm						= trim($arr_rs[0]["DEL_ADM"]); 
		$rs_del_date					= trim($arr_rs[0]["DEL_DATE"]); 
		
		$rs_request_budget				= trim($arr_rs[0]["REQUEST_BUDGET"]); 
		$rs_request_month				= trim($arr_rs[0]["REQUEST_MONTH"]); 
		$rs_request_position			= trim($arr_rs[0]["REQUEST_POSITION"]); 
		$rs_request_company				= trim($arr_rs[0]["REQUEST_COMPANY"]); 
		
		if ($rs_reply_date) {
			$rs_reply_date				= date("Y-m-d H:i:s",strtotime($rs_reply_date));
		}
	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&con_request_cate=".$con_request_cate."&con_reply_state=".$con_reply_state;
		
		if ($mode == "U") {
?>	
<script language="javascript">
		location.href =  "request_write.php<?=$strParam?>&mode=S&request_no=<?=$request_no?>";
</script>
<?
		} else {
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		location.href =  "request_list.php<?=$strParam?>";
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
<script language="javascript">

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
		frm.action = "request_list.php";
		frm.submit();
	}

	// 저장 버튼 클릭 시 
	function js_save() {
		
		var request_no = "<?= $request_no ?>";
		var frm = document.frm;
		
		frm.mode.value = "U";

		frm.method = "post";
		frm.action = "request_write.php";
		frm.submit();
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
<input type="hidden" name="mode" value="">
<input type="hidden" name="request_no" value="<?= $request_no?>">
<input type="hidden" name="con_request_cate" value="<?= $con_request_cate?>">
<input type="hidden" name="con_reply_state" value="<?= $con_reply_state ?>">
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
								<th>프로젝트 유형</th>
								<td>
									<?= getDcodeName($conn, "REQUEST_CATE", $rs_request_cate);?>
								</td>
								<th>문의일자</th>
								<td>
									<?=$rs_reg_date?>
								</td>
							</tr>
							<tr>
								<th>문의 제목</th>
								<td colspan="3">
									<?=$rs_request_title?>
								</td>
							</tr>
							<tr>
								<th>예산</th>
								<td>
									<?=$rs_request_budget?>만원
								</td>
								<th>기간</th>
								<td>
									<?=$rs_request_month?>개월
								</td>								
							</tr>
							<tr>
								<th>담당자명</th>
								<td colspan="3">
									<?=$rs_request_name?>
								</td>
							</tr>
							<tr>
								<th>직책</th>
								<td>  
									<?=$rs_request_position?>
								</td>
								<th>회사명</th>
								<td>
									<?=$rs_request_company?>
								</td>								
							</tr>
							<tr>
								<th>이메일</th>
								<td>
									<?=$rs_request_email?>
								</td>
								<th>연락처</th>
								<td>
									<?=$rs_request_tel?>
								</td>								
							</tr>
							<tr>
								<th>상세 문의 내용</th>
								<td colspan="3">
									<?=nl2br($rs_request_contents)?>
								</td>
							</tr>
							<!--
							<tr>
								<th>첨부파일</th>
								<td colspan="3">
									<a href="../../_common/new_download_file.php?menu=request&request_no=<?= $rs_request_no ?>"><?=$rs_file_rnm?></a>
								</td>
							</tr>
							-->
						</tbody>
					</table>
				</div>

				<div class="sp20"></div>

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
								<th>답변 (처리내용)</th>
								<td colspan="3" class="memo">
									<span class="textareabox">
										<textarea class="txt" name="request_reply" id="request_reply"><?= $rs_request_reply ?></textarea>
									</span>
								</td>
							</tr>

							<tr>
								<th>답변 담당자</th>
								<td>
									<?=getAdminName($conn, $rs_request_reply_adm)?>
								</td>
							</tr>
							<tr>
								<th>답변상태</th>
								<td>
									<?= makeSelectBox($conn,"REPLY_STATE","reply_state","125pc","","",$rs_reply_state)?>
								</td>
								<th>답변일</th>
								<td>
									<?=$rs_reply_date?>
								</td>
						</tbody>
					</table>
				</div>

				<div class="btnright">
				<? if ($request_no <> "" ) {?>
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
				<? if ($request_no <> "") {?>
					<? if ($sPageRight_D == "Y") {?>
					<!--<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>-->
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
<script type="text/javascript" src="/admin/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
