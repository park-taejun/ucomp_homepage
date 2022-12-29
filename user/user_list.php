<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : user_list.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2021-12-21
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
	$menu_right = "AD007"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#$sPageRight_F		= "Y";

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
	require "../../_classes/biz/admin/user.php";

/*
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$con_use_tf					= $_POST['con_use_tf']!=''?$_POST['con_use_tf']:$_GET['con_use_tf'];
	$adm_no							= $_POST['adm_no']!=''?$_POST['adm_no']:$_GET['adm_no'];
*/
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	//$con_group_no							= $_POST['con_group_no']!=''?$_POST['con_group_no']:$_GET['con_group_no'];

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= "Y";

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

	$nListCnt =totalCntUserTest($conn, $con_group_no, $con_headquarters_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$con_use_tf = "Y";

	#$del_tf = "Y";

	$arr_rs = listUserTest($conn, $con_group_no, $con_headquarters_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);
	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "사용자 리스트 조회", "List");

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

	// 조회 버튼 클릭 시 
	$(document).on("click", "#btn_search", function() {
		var frm = document.frm;
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "user_list.php";
		frm.submit();
	});
	//

	function js_view(rn, adm_no) {

		var frm = document.frm;
		
		frm.adm_no.value = adm_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "user_view.php";
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
				<h3><strong><?=$p_menu_name?></strong></h3>
				<div class="boardlist search">

<form id="searchBar" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="adm_no" value="">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="con_use_tf" value="<?=$con_use_tf?>">
<input type="hidden" name="menu_cd" value="<?//=$menu_cd?>" >
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=(int)$nPage?>">
<input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>">

					<table>
						<colgroup>
							<col style="width:100px" />
							<col style="width:12%" />
							<col style="width:100px" />
							<col style="width:12%" />
							<col style="width:100px" />
							<col style="width:auto" />
						</colgroup>
						<tbody>
							<tr>
								<!--
								<th scope="row">회사명</th>
								<td>
									<span class="optionbox">
										<?= makeCompanySelectBoxWithObj($conn, "con_com_code", '', $con_com_code);?>
									</span>
								</td>
								-->
								<th scope="row">본부명</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"HEADQUARTERS_2022","con_headquarters_code","125px","전체","",$con_headquarters_code)?>
									</span>
								</td>
								<th scope="row">부서명</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"DEPT_2022","con_dept_code","125px","전체","",$con_dept_code)?>
									</span>
								</td>
								<!--
								<th scope="row">관리자그룹</th>
								<td>
									<span class="optionbox">
										<?= makeAdminGroupSelectBox($conn, "con_group_no" , "125px", "전체", "", $con_group_no); ?>
									</span>
								</td>
								-->
								<th scope="row">검색조건</th>
								<td>
									<div class="searchbox">
										<span class="optionbox" style="min-width:60px;width:83px;">
											<select name="nPageSize" style="width:80px;">
												<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >20개씩</option>
												<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >50개씩</option>
												<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >100개씩</option>
											</select>&nbsp;
										</span>
										<span class="optionbox" style="min-width:50px;width:70px;">
											<select name="search_field" style="width:68px;">
												<option value="ADM_NAME" <? if ($search_field == "ADM_NAME") echo "selected"; ?> >이름</option>
												<option value="ADM_ID" <? if ($search_field == "ADM_ID") echo "selected"; ?> >ID</option>
											</select>
										</span>
										<span class="inpbox"><input type="text" value="<?=$search_str?>" name="search_str" class="txt" onkeypress="if(event.keyCode == 13){document.getElementById('btn_search').click()}"/></span>
										<button type="button" class="btn-border-white" id="btn_search">검색</button>
									</div>
								</td>

							</tr>
						</tbody>
					</table>
</form>
				</div>
				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:5%" />
							<col style="width:15%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:20%" />
							<col style="width:10%" />
						</colgroup>
						<thead>
							<tr>
								<th scope="col">번호</th>
								<th scope="col">본부명</th>
								<th scope="col">부서명</th>
								<th scope="col">직급</th>
								<th scope="col">직책</th>
								<th scope="col">이름</th>
								<th scope="col">연락처</th>
								<th scope="col">Email</th>
								<th scope="col">오늘근무타입</th>
							</tr>
						</thead>
						<tbody>

						<?
							$nCnt = 0;
							
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$ADM_ID						= trim($arr_rs[$j]["ADM_ID"]);
									$ADM_NO						= trim($arr_rs[$j]["ADM_NO"]);
									$ADM_NAME					= SetStringFromDB($arr_rs[$j]["ADM_NAME"]);
									$ADM_HPHONE				= trim($arr_rs[$j]["ADM_HPHONE"]);
									$ADM_EMAIL				= trim($arr_rs[$j]["ADM_EMAIL"]);
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

									$VA_TYPE		= selectAdminVacation($conn, $ADM_NO); //사용자 연차 및 스마트데이 확인용
									$VA_NAME		= getDcodeName($conn, "VA_TYPE", $VA_TYPE);

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

									$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;
						?>

								<tr>
									<td><a href="javascript:js_view('<?= $rn ?>','<?= $ADM_NO ?>');"><?=$rn?></a></td>
									<td><?= $HEADQUARTERS_CODE ?></td>
									<td><?= $DEPT_CODE ?></td>
									<td><?= $POSITION_NAME ?></td>
									<td><?= $LEADER_TITLE ?></td>
									<td><a href="javascript:js_view('<?= $rn ?>','<?= $ADM_NO ?>');"><?= $ADM_NAME ?></a></td>
									<td><?= $ADM_HPHONE ?></td>
									<td><?= $ADM_EMAIL ?></td>
									<td><?= $VA_NAME?></td>
								</tr>

						<?	
								}
							} else { 
						?> 
								<tr>
									<td align="center" height="50" colspan="10">데이터가 없습니다. </td>
								</tr>
						<? 
							}
						?>
						</tbody>
					</table>
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
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&con_use_tf=".$con_use_tf."&con_com_code=".$con_com_code."&con_dept_code=".$con_dept_code."&con_group_no=".$con_group_no."&con_headquarters_code=".$con_headquarters_code;

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
<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
