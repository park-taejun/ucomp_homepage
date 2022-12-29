<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : admin_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-12-10
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
	$menu_right = "AD002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/admin/admin.php";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$adm_no							= $_POST['adm_no']!=''?$_POST['adm_no']:$_GET['adm_no'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	//$con_group_no							= $_POST['con_group_no']!=''?$_POST['con_group_no']:$_GET['con_group_no'];

	if ($mode == "T") {

		updateAdminUseTF($conn, $use_tf, $s_adm_no, (int)$adm_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 사용여부 변경 (관리자번호 : ".(int)$adm_no.")", "Update");

	}

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
	
	$use_tf				= SetStringToDB($use_tf);
	
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

	$nListCnt =totalCntAdmin($conn, $con_group_no, $con_com_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listAdmin($conn, $con_group_no, $con_com_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 관리 리스트 조회", "List");

	#echo sizeof($arr_rs);

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


	$(document).on("click", "#btn_write", function() { 
		menu_cd=document.frm.menu_cd.value;
		document.location.href = "admin_write.php?menu_cd="+menu_cd;
	});

	// 조회 버튼 클릭 시 
	$(document).on("click", "#btn_search", function() {
		var frm = document.frm;
		frm.nPage.value = "1";
		frm.method = "get";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "admin_list.php";
		frm.submit();
	});

	function js_view(rn, adm_no) {

		var frm = document.frm;
		
		frm.adm_no.value = adm_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "admin_write.php";
		frm.submit();
		
	}

	function js_toggle(adm_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('사용 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.adm_no.value = adm_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
			frm.action = "admin_list.php";
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
					
					<? if ($sPageRight_I == "Y") { ?><button type="button" class="btn-navy" id="btn_write">등록하기</button><? } ?>

				</h3>
				<div class="boardlist search">

<form id="searchBar" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="adm_no" value="">
<input type="hidden" name="use_tf" value="">
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
							<col style="width:12%" />
							<col style="width:100px" />
							<col style="width:auto" />
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">회사명</th>
								<td>
									<span class="optionbox">
										<?= makeCompanySelectBoxWithObj($conn, "con_com_code", '', $con_com_code);?>
									</span>
								</td>
								<th scope="row">부서명</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"DEPT","con_dept_code","125px","전체","",$con_dept_code)?>
									</span>
								</td>
								<th scope="row">관리자그룹</th>
								<td>
									<span class="optionbox">
										<?= makeAdminGroupSelectBox($conn, "con_group_no" , "125px", "전체", "", $con_group_no); ?>
									</span>
								</td>
								<th scope="row">검색조건</th>
								<td>
									<div class="searchbox">
										<span class="optionbox">
											<select name="nPageSize" style="width:84px;">
												<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >20개씩</option>
												<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >50개씩</option>
												<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >100개씩</option>
											</select>&nbsp;
										</span>
										<span class="optionbox">
											<select name="search_field" style="width:80px;">
												<option value="ADM_NAME" <? if ($search_field == "ADM_NAME") echo "selected"; ?> >이름</option>
												<option value="ADM_ID" <? if ($search_field == "ADM_ID") echo "selected"; ?> >ID</option>
											</select>
										</span>
										<span class="inpbox"><input type="text" value="<?=$search_str?>" name="search_str" class="txt" /></span>
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
							<col style="width:10%" />
							<col style="width:15%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:10%" />
							<col style="width:15%" />
						</colgroup>
						<thead>
							<tr>
								<th scope="col">번호</th>
								<th scope="col">회사명</th>
								<th scope="col">부서명</th>
								<th scope="col">직책</th>
								<th scope="col">이름</th>
								<th scope="col">ID</th>
								<th scope="col">연락처</th>
								<th scope="col">관리자그룹</th>
								<th scope="col">등록일</th>
								<th scope="col">사용여부</th>
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
									$GROUP_NO					= trim($arr_rs[$j]["GROUP_NO"]);
									$COM_CODE					= trim($arr_rs[$j]["COM_CODE"]);
									$DEPT_CODE				= trim($arr_rs[$j]["DEPT_CODE"]);
									$POSITION_CODE		= trim($arr_rs[$j]["POSITION_CODE"]);
									$DEPT_NAME				= trim($arr_rs[$j]["DEPT_NAME"]);
									$POSITION_NAME		= trim($arr_rs[$j]["POSITION_NAME"]);
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);

									$GROUP_NAME			= getGroupName($conn, $GROUP_NO); 
									//$DEPT_NAME			= getDcodeName($conn, "DEPT", $DEPT_CODE); 
									//$POSITION_NAME	= getDcodeName($conn, "POSITION", $POSITION_CODE); 
									$CP_NM					= getCompanyName($conn, $COM_CODE); 

									if ($USE_TF == "Y") {
										$STR_USE_TF = "<font color='navy'>사용중</font>";
									} else {
										$STR_USE_TF = "<font color='red'>사용안함</font>";
									}

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

									$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;

						?>

								<tr>
									<td><a href="javascript:js_view('<?= $rn ?>','<?= $ADM_NO ?>');"><?=$rn?></a></td>
									<td><?= $CP_NM ?></td>
									<td><?= $DEPT_NAME ?></td>
									<td><?= $POSITION_NAME ?></td>
									<td><a href="javascript:js_view('<?= $rn ?>','<?= $ADM_NO ?>');"><?= $ADM_NAME ?></a></td>
									<td><a href="javascript:js_view('<?= $rn ?>','<?= $ADM_NO ?>');"><?= $ADM_ID?></a></td>
									<td><?= $ADM_HPHONE ?></td>
									<td><?= $GROUP_NAME ?></td>
									<td><?= $REG_DATE ?></td>
									<td><a href="javascript:js_toggle('<?=$ADM_NO?>','<?=$USE_TF?>');"><?= $STR_USE_TF ?></a></td>
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
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

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
