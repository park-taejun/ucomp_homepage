<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : admingroup_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";

#====================================================================
# Request Parameter
#====================================================================

	$result = false;

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$group_name					= $_POST['group_name']!=''?$_POST['group_name']:$_GET['group_name'];
	$group_no						= $_POST['group_no']!=''?$_POST['group_no']:$_GET['group_no'];

	#List Parameter
	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$group_name			= SetStringToDB($group_name);
	$use_tf				= SetStringToDB($use_tf);
	

	if ($mode == "I") {
		$result = insertAdminGroup($conn, $group_name, $use_tf, $s_adm_no);
		$nPage = "1";
		
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 그룹 생성 (".$group_name.") ", "Insert");

	}

	if ($mode == "U") {
		$result = updateAdminGroup($conn, $group_name, $use_tf, $s_adm_no, (int)$group_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 그룹 수정 (".$group_name.") ", "Update");
	}

	if ($mode == "D") {
		$result = deleteAdminGroup($conn, $s_adm_no, (int)$group_no);
		$nPage = "1";
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 그룹 삭제 (그룹번호 : ".(int)$group_no.") ", "Delete");
	}

	if ($result) {
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		location.href =  '<?=$_SERVER[PHP_SELF]?>?nPage=<?=$nPage?>&nPageSize=<?=$nPageSize?>';
</script>
</head>
</html>
<?
		exit;
	}

	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 30;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntAdminGroup($conn, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listAdminGroup($conn, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);
	
	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 그룹 조회", "List");

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


	function js_write() {

		var frm = document.frm; 
		
		if (frm.group_no.value == "") {
		  frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}

		if (frm.group_name.value == "") {
		  	alert("관리자 그룹명을 입력 하십시오."); //관리자 그룹명을 입력 하십시오.
			frm.group_name.focus();
			return;
		}

		frm.target = "";
		frm.method = "post";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "admingroup_list.php";
		frm.submit();

	}


	function js_view(group_no, group_name) {

		var frm = document.frm; 
		frm.group_no.value = group_no;
		frm.group_name.value = group_name;
		frm.text_mode.value = '[수정 모드]'; //수정 모드
		document.getElementById("btn_save").src = "../images/admin/btn_modify.gif";
		frm.mode.value = "U";
	}
	
	function js_cancel() {

		var frm = document.frm; 
		frm.group_no.value = "";
		frm.group_name.value = "";
		frm.text_mode.value = '[등록 모드]'; //수정 모드
		document.getElementById("btn_save").src = "../images/admin/btn_regist_02.gif";
		frm.mode.value = "I";
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "admingroup_list.php";
		frm.submit();
	}

	function js_delete(group_no) {

		var frm = document.frm;

		bDelOK = confirm('자료를 삭제 하시겠습니까?\n해당 그룹에 속한 관리자도 같이 삭제 됩니다.');
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.group_no.value = group_no;
			frm.target = "";
			//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
			frm.action = "admingroup_list.php";
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
				<div class="boardlist search">

		<form name="frm" method="post" action="javascript:js_search();">
		<input type="hidden" name="rn" value="">
		<input type="hidden" name="group_no" value="">
		<input type="hidden" name="mode" value="">
		<input type="hidden" name="nPage" value="<?=$nPage?>">
		<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

					<table>
						<colgroup>
							<col style="width:15%" />
							<col style="width:85%" />
						</colgroup>
						<tbody>
							<tr>
								<th class="long">관리자 그룹명</th>
								<td>
									<span class="inpbox" style="display:inline-block; width:40%"><input type="text" class="txt"  name="group_name" required/></span>&nbsp;&nbsp;&nbsp;
									<input type="text" class="txt" name="text_mode" style="display:inline-block; width:75px" style="border:0px; soild #FFF" value="[ 등록모드 ]" readonly="">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<? if ($sPageRight_I == "Y") {?>
				<p class="btnleft">
					<button type="button" class="btn-gray" onClick="js_write();">등록</button>
					<button type="button" class="btn-gray" onClick="js_cancel();">취소</button>
				</p>
				<? } ?>
				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:35%" />
							<col style="width:40%" />
							<col style="width:15%" />
						</colgroup>
						<thead>
							<tr>
								<th>번호</th>
								<th>그룹명</th>
								<th>메뉴설정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>

						<?
							$nCnt = 0;
							
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									#GROUP_NO, GROUP_NAME, GROUP_FLAG, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

									$rn					= trim($arr_rs[$j]["rn"]);
									$GROUP_NO		= trim($arr_rs[$j]["GROUP_NO"]);
									$GROUP_NAME	= trim($arr_rs[$j]["GROUP_NAME"]);
									$GROUP_FLAG	= trim($arr_rs[$j]["GROUP_FLAG"]);
									$USE_TF			= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF			= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE		= trim($arr_rs[$j]["REG_DT"]);

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
				
						?>
							<tr>
								<td><?=$rn?></td>
								<td>
									<a href="javascript:js_view('<?=$GROUP_NO?>','<?=$GROUP_NAME?>');"><?=$GROUP_NAME?></a>
								</td>
								<td>
								<? if ($sPageRight_U == "Y") { ?>
									<a href="javascript:NewWindow('pop_menu_list.php?group_no=<?=$GROUP_NO?>','pop_menu_list','820','650','YES')">[ + ]</a>
								<? } ?>
								</td>
								<td>
								<? if ($sPageRight_D == "Y") { ?>
									<? if ($GROUP_NO != 1) { ?>
										<button type="button" class="btn-gray" onClick="js_delete('<?=trim($GROUP_NO)?>');" style="width:80px">삭제</button>
									<? } ?>
								<? } ?>
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

</form>
				
			</div>
		</div>
	</div>

<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
<script>

</script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>