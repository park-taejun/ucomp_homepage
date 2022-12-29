<?session_start();?>
<?
# =============================================================================
# File Name    : pop_menu_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.12.10
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");


#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#==============================================================================
# Confirm right
#==============================================================================

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";


#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/menu/menu.php";
	require "../../_classes/biz/admin/admin.php";


#====================================================================
# Request Parameter
#====================================================================

	$group_name					= $_POST['group_name']!=''?$_POST['group_name']:$_GET['group_name'];
	$group_no						= $_POST['group_no']!=''?$_POST['group_no']:$_GET['group_no'];
	$group_no = trim($group_no);
	$group_no		= (int)$group_no;


	$arr_rs = selectAdminGroup($conn, $group_no);

	$rs_group_name = trim($arr_rs[0]["GROUP_NAME"]); 

	$arr_rs_right = listAdminGroupMenuRight($conn, $group_no);
	
	$con_use_tf		= "Y";
	$con_del_tf		= "N";
	$search_field	= "";
	$search_str		= "";
	
	$nExist = "0";

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);


	$arr_rs = listAdminMenu($conn, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 권한 엑셀다운 (그룹 번호 : ".$group_no.") ", "List");

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->

</head>
<body id="popup_file">

<form name="frm" action="admingroup_right_dml.php" method="post">
<input type="hidden" name="group_no" value="<?=$group_no?>">	

<div id="popupwrap_file">
	<div id="postsch">
	*그룹별 권한 목록/<?=$rs_group_name?>/
		<div class="addr_inp">

		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					<table id='t' cellpadding="0" class="rowstable" cellspacing="0" border="0" width="100%">
						<colgroup>
							<col width="150">
							<col width="80">
							<col width="80">
							<col width="80">
							<col width="80">
							<col width="80">
							<!--
							<col width="10%">
							<col width="10%">
							-->
						</colgroup>
						<thead>
							<tr>
								<th scope="col">메뉴명</th>
								<th scope="col">조회</th>
								<th scope="col">등록</th>
								<th scope="col">수정</th>
								<th scope="col">삭제</th>
								<th class="end"  scope="col">파일</th>
								<!--
								<th scope="col">상단노출</th>
								<th scope="col">메인노출</th>
								-->
							</tr>
						</thead>
						<tbody>
						<?
							
							if (sizeof($arr_rs) > 0) {
						
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
									//MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_FLAG, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_RIGHT
							
									$MENU_NO				= trim($arr_rs[$j]["MENU_NO"]);
									$MENU_CD				= trim($arr_rs[$j]["MENU_CD"]);
									$MENU_NAME			= trim($arr_rs[$j]["MENU_NAME"]);
									$MENU_URL				= trim($arr_rs[$j]["MENU_URL"]);
									$MENU_FLAG			= trim($arr_rs[$j]["MENU_FLAG"]);
									$MENU_SEQ01			= trim($arr_rs[$j]["MENU_SEQ01"]);
									$MENU_SEQ02			= trim($arr_rs[$j]["MENU_SEQ02"]);
									$MENU_SEQ03			= trim($arr_rs[$j]["MENU_SEQ03"]);
									$MENU_RIGHT			= trim($arr_rs[$j]["MENU_RIGHT"]);
									$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE				= trim($arr_rs[$j]["REG_DT"]);

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

									if (strlen($MENU_CD) == 2) {
										$menu_str = "<font color='blue'>⊙ ".$MENU_NAME."</font>";
									} else {
										for ($menuspace = 0 ; $menuspace < strlen($MENU_CD) ;$menuspace++) {
											$menu_str = $menu_str ."&nbsp;";
										}

										if (strlen($MENU_CD) == 4) {
											$menu_str = $menu_str ."┗ <font color='navy'>".$MENU_NAME."</font>";
										} else if (strlen($MENU_CD) == 6) {
											$menu_str = $menu_str ."&nbsp;&nbsp;┗ <font color='gray'>".$MENU_NAME."</font>";
										}
									}

									//echo $MENU_CD;
						?>
							<tr align="center" height="25" bgcolor="#FFFFFF">
								<td class="modeual_nm">
									<?=$menu_str?>
							<!--
									<input type="hidden" name="menu_right[]" value="<?=$MENU_RIGHT?>">
									<input type="hidden" name="menu_cd[]" value="<?=$MENU_CD?>">
									<input type="hidden" name="menu_url[]" value="<?=$MENU_URL?>">
							-->
								</td>				
						<?
									if (sizeof($arr_rs_right) > 0) {
		
										for ($jk = 0 ; $jk < sizeof($arr_rs_right); $jk++) {

											$SUB_MENU_CD	= trim($arr_rs_right[$jk]["MENU_CD"]);
											$READ_FLAG		= trim($arr_rs_right[$jk]["READ_FLAG"]);
											$REG_FLAG			= trim($arr_rs_right[$jk]["REG_FLAG"]);
											$UPD_FLAG			= trim($arr_rs_right[$jk]["UPD_FLAG"]);
											$DEL_FLAG			= trim($arr_rs_right[$jk]["DEL_FLAG"]);
											$FILE_FLAG		= trim($arr_rs_right[$jk]["FILE_FLAG"]);
											$TOP_FLAG			= trim($arr_rs_right[$jk]["TOP_FLAG"]);
											$MAIN_FLAG		= trim($arr_rs_right[$jk]["MAIN_FLAG"]);
											
											//echo $SUB_MENU_CD."---".$MENU_CD."<br>";

											if ($MENU_CD == trim($SUB_MENU_CD)) { 
						
												$nExist = "1";

												if (trim($READ_FLAG) == "Y") {
						?>
								<td>Y
								</td>
						<?						
												} else { 
						?>
								<td>N
								</td>
						<?
												}

												if (trim($REG_FLAG) == "Y") {
						?>
								<td>Y
								</td>
						<?						
												} else { 
						?>
								<td>N
								</td>
						<?
												}

												if (trim($UPD_FLAG) == "Y") {
						?>
								<td>Y
								</td>
						<?						
												} else { 
						?>
								<td>N
								</td>
						<?
												}

												if (trim($DEL_FLAG) == "Y") {
						?>
								<td>Y
								</td>
						<?						
												} else { 
						?>
								<td>N
								</td>
						<?
												}

												if (trim($FILE_FLAG) == "Y") {
						?>
								<td>Y
								</td>
						<?						
												} else { 
						?>
								<td>N
								</td>
						<?
						
												}

												if (trim($TOP_FLAG) == "Y") {
						?>
								<!--
								<td>
									<input type="checkbox" name="chk_top[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','T');">
									<input type="hidden" name="top_chk[]" value="">
								</td>
								-->
								
						<?						
												} else { 
						?>
								<!--
								<td>
									<input type="checkbox" name="chk_top[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','T');">
									<input type="hidden" name="top_chk[]" value="">
								</td>
								-->
						<?
						
												}

												if (trim($MAIN_FLAG) == "Y") {
						?>
								<!--
								<td>
									<input type="checkbox" name="chk_main[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','M');">
									<input type="hidden" name="main_chk[]" value="">
								</td>
								-->
						<?						
												} else { 
						?>
								<!--
								<td>
									<input type="checkbox" name="chk_main[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','M');">
									<input type="hidden" name="main_chk[]" value="">
								</td>
								-->
						<?
						
												}

											}
										}
				
										if ($nExist == "0")  {
						?>

								<td>
									<input type="checkbox" name="chk_read[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','R');">
									<input type="hidden" name="read_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_reg[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','I');">
									<input type="hidden" name="reg_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_upd[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','U');">
									<input type="hidden" name="upd_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_del[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','D');">
									<input type="hidden" name="del_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_file[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','F');">
									<input type="hidden" name="file_chk[]" value="">
								</td>
								<!--
								<td>
									<input type="checkbox" name="chk_top[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','T');">
									<input type="hidden" name="top_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_main[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','M');">
									<input type="hidden" name="main_chk[]" value="">
								</td>
								-->
						<?
				
										}
				
										$nExist = "0";
				
									} else {
						?>
								<td>
									<input type="checkbox" name="chk_read[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','R');">
									<input type="hidden" name="read_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_reg[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','I');">
									<input type="hidden" name="reg_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_upd[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','U');">
									<input type="hidden" name="upd_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_del[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','D');">
									<input type="hidden" name="del_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_file[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','F');">
									<input type="hidden" name="file_chk[]" value="">
								</td>
								<!--
								<td>
									<input type="checkbox" name="chk_top[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','T');">
									<input type="hidden" name="top_chk[]" value="">
								</td>
								<td>
									<input type="checkbox" name="chk_main[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','M');">
									<input type="hidden" name="main_chk[]" value="">
								</td>
								-->
						<?
									}
						?>
							</tr>
						<?
										$menu_str = "";
									}
								} else {
						?>
							<tr align="center" bgcolor="#FFFFFF">
								<td height="25" colspan="7">등록 메뉴가 없습니다.<!--등록 메뉴가 없습니다.--></td>
							</tr>
						<?
								}
						?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		</div>
	<br />

	</div>

	<div class="bot_close"><a href="javascript: window.close();"><img src="../images/admin/icon_pclose.gif" alt="닫기" /></a></div>
</div>
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>