<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : admin_approval_right.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-04-05
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD008"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header_ptj.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc_ptj.php";
	require "../../_classes/biz/admin_ptj/admin.php";
	require "../../_classes/biz/adminapprovalright/adminapprovalright.php";

	$app_type						= $_POST['app_type']!=''?$_POST['app_type']:$_GET['app_type'];
	$adm_no							= $_POST['adm_no']!=''?$_POST['adm_no']:$_GET['adm_no'];
	$app_right					= $_POST['app_right']!=''?$_POST['app_right']:$_GET['app_right'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$con_use_tf = "Y";

	if($app_type == "") $app_type = "0";
#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= SetStringToDB($nPage);
	$nPageSize	= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf					= SetStringToDB($use_tf);

	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "" && $nPageSize <> 0) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "" && $nPageSize <> 0) {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	//$nListCnt =totalCntAdmin($conn, $con_group_no, $con_com_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str);
	$nListCnt =totalCntAdminApprovalRight($conn, $con_group_no, $con_headquarters_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$con_use_tf = "Y";

	#$del_tf = "Y";

	//$arr_rs = listAdmin($conn, $con_group_no, $con_com_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);
	$arr_rs = listAdminApprovalRight($conn, $con_group_no, $con_headquarters_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 권한 조회", "List");

	#echo sizeof($arr_rs);

	if ($mode == "I") {

			$result = UpdateAdminApprovalRight($conn, $app_type, $adm_no, $app_right, $_SESSION['s_adm_id']);
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "승인 권한 등록 (관리자 아이디 : ".$adm_id.") ", "Update");
?>	
		<script language="javascript">
			alert('저장 되었습니다.');
			document.location.href = "admin_approval_right.php?app_type=<?=$app_type?>";
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
<script type="text/javascript" >

function js_save() {

	var frm = document.frm;

	var total = frm["app_right_chk[]"].length;

	for(var i=0; i<total; i++) {
		if(frm["app_right_chk[]"][i].checked == true) {
			frm["app_right[]"][i].value="1";
		} else {
			frm["app_right[]"][i].value="0";
		}
	}

	frm.mode.value = "I";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

</script>
</head>
<body>
<form name="frm" method="post">
<input type="hidden" name="mode">
<input type="hidden" name="app_type" value="<?=$app_type?>">
<div id="wrap">
<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area_ptj.php";
?>
	<!-- S: container -->
	<div class="container">
		<div class="contentsarea">
			<div class="menu-holiday">
				<h3><strong><?=$p_menu_name?></strong>
				</h3>
				<p class="btnleft">
					<span class="btnset">
						<button type="button" class="btn-border-white <? if($app_type == "0"){?> on <?}?>" onClick="document.location='admin_approval_right.php'">연차</button><!-- 활성화시 on클래스 -->
						<button type="button" class="btn-border-white <? if($app_type == "1"){?> on <?}?>" onClick="document.location='admin_approval_right.php?app_type=1'">지출</button>
					</span>
				</p>
				<!--
				<div style="width:100%; padding-bottom:10px; text-align:right">
					<input type="radio" name="app_type" value="0" checked> 연차 &nbsp;&nbsp;
					<input type="radio" name="app_type" value="1"> 지출
				</div>
				-->
				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:5%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:5%" />
							<col style="width:10%" />
							<col style="width:10%" />
						</colgroup>
						<thead>
							<tr>
								<th scope="col">번호</th>
								<th scope="col">직급</th>
								<th scope="col">직책</th>
								<th scope="col">이름</th>
								<th scope="col">본부명</th>
								<th scope="col">부서명</th>
								<th scope="col">전결 권한</th>
							</tr>
						</thead>
						<tbody>

						<?
							$nCnt = 0;
							
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$ADM_NO						= trim($arr_rs[$j]["ADM_NO"]);
									$ADM_NAME					= SetStringFromDB($arr_rs[$j]["ADM_NAME"]);
									$GROUP_NO					= trim($arr_rs[$j]["GROUP_NO"]);
									$COM_CODE					= trim($arr_rs[$j]["COM_CODE"]);
									$HEADQUARTERS_CODE= trim($arr_rs[$j]["HEADQUARTERS_CODE"]);
									$DEPT_CODE				= trim($arr_rs[$j]["DEPT_CODE"]);
									$POSITION_CODE		= trim($arr_rs[$j]["POSITION_CODE"]);
									$LEADER_TITLE			= trim($arr_rs[$j]["LEADER_TITLE"]);
									$DEPT_NAME				= trim($arr_rs[$j]["DEPT_NAME"]);
									$POSITION_NAME		= trim($arr_rs[$j]["POSITION_NAME"]); //관리자그룹이름
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
									$ENTER_DATE				= trim($arr_rs[$j]["ENTER_DATE"]);

									$GROUP_NAME			= getGroupName($conn, $GROUP_NO); 
									//$DEPT_NAME			= getDcodeName($conn, "DEPT", $DEPT_CODE); 
									//$POSITION_NAME	= getDcodeName($conn, "POSITION", $POSITION_CODE); 
									$CP_NM					= getCompanyName($conn, $COM_CODE); 

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

									$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;

									$arr_rs_app = selectApp($conn, $app_type, $ADM_NO);

						?>

								<tr>
									<td><?=$rn?></td>
									<td><?= $POSITION_NAME ?></td>
									<td><?= $LEADER_TITLE ?></td>
									<td><?= $ADM_NAME ?></td>
									<td><?= $HEADQUARTERS_CODE ?></td>
									<td><?= $DEPT_CODE ?></td>
									<td>
										<input type="checkbox" name="app_right_chk[]" <?if ($arr_rs_app == "1") {?>checked<? } ?> />
										<input type="hidden" name="app_right[]">
										<input type="hidden" name="adm_no[]" value="<?=$ADM_NO?>">
									</td>
								</tr>

						<?	
								}
							} else { 
						?> 
								<tr>
									<td align="center" height="50" colspan="7">데이터가 없습니다. </td>
								</tr>
						<? 
							}
						?>
						</tbody>
					</table>
				</div>

				<div class="btnright">
					<? if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">저장</button>
					<? } ?>
				</div>

				<p class="paging">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&con_use_tf=".$con_use_tf."&con_com_code=".$con_com_code."&con_headquarters_code=".$con_headquarters_code."&con_dept_code=".$con_dept_code."&con_group_no=".$con_group_no;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
				</p>

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
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
