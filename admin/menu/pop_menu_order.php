<?session_start();?>
<?
# =============================================================================
# File Name    : pop_menu_order.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.12.07
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
	$menu_right = "AD004"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/menu/menu.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$order_field				= $_POST['order_field']!=''?$_POST['order_field']:$_GET['order_field'];
	$order_str					= $_POST['order_str']!=''?$_POST['order_str']:$_GET['order_str'];

	$m_level					= $_POST['m_level']!=''?$_POST['m_level']:$_GET['m_level'];

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$order_field		= SetStringToDB($order_field);
	$order_str			= SetStringToDB($order_str);

	$use_tf				= SetStringToDB($use_tf);

	$m_level = SetStringToDB($m_level);

	if (strlen($m_level) == 0) {
		$level_str = "대분류 메뉴";
	} else if (strlen($m_level) == 2) {
		$level_str = "중분류 메뉴";
	} else if (strlen($m_level) == 4) {
		$level_str = "소분류 메뉴";
	}

?>
<?

#====================================================================
# Declare variables
#====================================================================

#====================================================================
# Get Result set from stored procedure
#====================================================================

	$del_tf = "N";

	$arr_rs = listAdminMenu($conn, $use_tf, $del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 메뉴 순서 조회", "List");

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<script type="text/javascript" src="../js/common.js"></script>

<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 300px; }
-->
</style>

<script type="text/javascript" >

/*
 * @(#)menu.js
 * 
 * 페이지설명 : 메뉴 순서 바꾸기 스크립트 파일
 * 작성  일자 : 2003.12.01
 */  


var preid = -1;

function js_up(n) {
	
	preid = parseInt(n);

	if (preid > 1) {
		
		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid-1].innerHTML;

		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid-1].cells;

		for(var j=0 ; j < cells1.length; j++) {
			
			if (j != 0) {
				var temp = cells2[j].innerHTML;

				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;

				var tempCode = document.frm.menu_no[preid-2].value;
			
				document.frm.menu_no[preid-2].value = document.frm.menu_no[preid-1].value;
				document.frm.menu_no[preid-1].value = tempCode;
			}
		}
		
		//preid = preid - 1;
		js_change_order();

	} else {
		alert("가장 상위에 있습니다. ");
	}
}


function js_down(n) {

	preid = parseInt(n);

	if (preid < document.getElementById("t").rows.length-1) {
		
		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid+1].innerHTML;
		
		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid+1].cells;
		
		for(var j=0 ; j < cells1.length; j++) {

			if (j != 0) {
				var temp = cells2[j].innerHTML;

			
				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;
	
				var tempCode = document.frm.menu_no[preid-1].value;
				document.frm.menu_no[preid-1].value = document.frm.menu_no[preid].value;
				document.frm.menu_no[preid].value = tempCode;
			}
		}
		
		//preid = preid + 1;	
		js_change_order();
	} else{
		alert("가장 하위에 있습니다. ");
	}
}


function js_change_order() {
	
	if(document.getElementById("t").rows.length < 2) {
		alert("순서를 저장할 메뉴가 없습니다");//순서를 저장할 메뉴가 없습니다");
		return;
	}

	document.frm.mode.value = "O";
	document.frm.target = "ifr_hidden";
	document.frm.action = "pop_menu_order_dml.php";
	document.frm.submit();

}


</script>
</head>
<body id="popup_code">

<form name="frm" method="post" action="javascript:check_data();">
<input type=hidden name='mode' value='add'>
<input type=hidden name='m_level' value='<?=$m_level?>'>

<div id="popupwrap_code">
	<h1>관리자 메뉴 순서 변경</h1>
	<div id="postsch_code">
		<h2>* 관리자 메뉴의 순서를 조정하는 화면 입니다.</h2>
		<div class="addr_inp">

		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
			<colgroup>
				<col width="20%">
				<col width="80%">
			</colgroup>
			<thead>
				<tr>
					<th>메뉴분류</th>
					<td>
						<?=$level_str?>
					</td>
				</tr>
			</thead>
		</table>
		</div>
		<br>
		<div class="addr_inp">	
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="100%" align="left">
					<div id="pop_table_scroll">
						<table id='t' cellpadding="0" class="rowstable" cellspacing="0" border="0" width="100%">
							<colgroup>
								<col width="15%" />
								<col width="30%" />
								<col width="55%" />
							</colgroup>
							<thead>
								<tr>
									<th>번호</th>
									<th>메뉴명</th>
									<th class="end">메뉴URL</th>
								</tr>
							</thead>
							<tbody>
				<?
					$nCnt = 0;
					
					$sMenu_no = "";

					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							//SEQ, MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_FLAG, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_RIGHT
							
							$MENU_SEQ			= trim($arr_rs[$j]["SEQ"]);
							$MENU_NO			= trim($arr_rs[$j]["MENU_NO"]);
							$MENU_CD			= trim($arr_rs[$j]["MENU_CD"]);
							$MENU_NAME		= trim($arr_rs[$j]["MENU_NAME"]);
							$MENU_URL			= trim($arr_rs[$j]["MENU_URL"]);
							$MENU_FLAG		= trim($arr_rs[$j]["MENU_FLAG"]);
							$MENU_SEQ01		= trim($arr_rs[$j]["MENU_SEQ01"]);
							$MENU_SEQ02		= trim($arr_rs[$j]["MENU_SEQ02"]);
							$MENU_SEQ03		= trim($arr_rs[$j]["MENU_SEQ03"]);
							$MENU_RIGHT		= trim($arr_rs[$j]["MENU_RIGHT"]);
							$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF				= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
							
							if (strlen($m_level) == 0) {

								if (strlen(Trim($MENU_CD)) == 2) {
									
									for ($j_sub = 0; $j_sub < sizeof($arr_rs) ; $j_sub++) {
										
										$SUB_MENU_NO	= trim($arr_rs[$j_sub]["MENU_NO"]);
										$SUB_MENU_CD	= trim($arr_rs[$j_sub]["MENU_CD"]);

										if (Trim($MENU_CD) == substr(Trim($SUB_MENU_CD),0,2)) {
											$sMenu_no = $sMenu_no ."^". $SUB_MENU_NO;
										}
									}
					

				?>
								<tr>
									<td class="sort">
										<span><?=($nCnt++ + 1)?></span>
										<a href="javascript:js_up('<?=($nCnt)?>');"><img src="../images/common/btn/icon_arr_top.gif" alt="" /></a> 
										<a href="javascript:js_down('<?=($nCnt)?>');"><img src="../images/common/btn/icon_arr_bot.gif" alt="" /></a>
									</td>
									<td class="modeual_nm">
										<?=$MENU_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($MENU_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($MENU_SEQ)?>">
										<input type="hidden" name="arr_menu_no[]" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
										<input type="hidden" name="menu_no" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
									</td>
									<td class="modeual_nm"><?=$MENU_URL?></td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sMenu_no = "";
								}
							}

							#중 분류인 경우
							if (strlen($m_level) == 2) {

								if ((substr($m_level,0,2) == substr(Trim($MENU_CD),0,2)) && (strlen(Trim($MENU_CD)) == 4)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_MENU_NO	= trim($arr_rs[$j_sub]["MENU_NO"]);
										$SUB_MENU_CD	= trim($arr_rs[$j_sub]["MENU_CD"]);

										if (Trim($MENU_CD) == substr(Trim($SUB_MENU_CD),0,4)) {
											$sMenu_no = $sMenu_no ."^". $SUB_MENU_NO;
										}
									}
				?>
								<tr>
									<td class="sort">
										<span><?=($nCnt++ + 1)?></span>
										<a href="javascript:js_up('<?=($nCnt)?>');"><img src="../images/common/btn/icon_arr_top.gif" alt="" /></a> 
										<a href="javascript:js_down('<?=($nCnt)?>');"><img src="../images/common/btn/icon_arr_bot.gif" alt="" /></a>
									</td>
									<td class="modeual_nm">
										<?=$MENU_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($MENU_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($MENU_SEQ)?>">
										<input type="hidden" name="arr_menu_no[]" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
										<input type="hidden" name="menu_no" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
									</td>
									<td class="modeual_nm"><?=$MENU_URL?></td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sMenu_no = "";
								}
							}
						
							#세부 분류인 경우

							if (strlen($m_level) == 4) {

								if ((substr($m_level,0,4) == substr(Trim($MENU_CD),0,4)) && (strlen(Trim($MENU_CD)) == 6)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_MENU_NO	= trim($arr_rs[$j_sub]["MENU_NO"]);
										$SUB_MENU_CD	= trim($arr_rs[$j_sub]["MENU_CD"]);
										
										if (Trim($MENU_CD) == substr(Trim($SUB_MENU_CD),0,6)) {
											
											$sMenu_no = $sMenu_no ."^". $SUB_MENU_NO;

										}
									}

				?>
								<tr>
									<td class="sort">
										<span><?=($nCnt++ + 1)?></span>
										<a href="javascript:js_up('<?=($nCnt)?>');"><img src="../images/common/btn/icon_arr_top.gif" alt="" /></a> 
										<a href="javascript:js_down('<?=($nCnt)?>');"><img src="../images/common/btn/icon_arr_bot.gif" alt="" /></a>
									</td>
									<td class="modeual_nm">
										<?=$MENU_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($MENU_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($MENU_SEQ)?>">
										<input type="hidden" name="arr_menu_no[]" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
										<input type="hidden" name="menu_no" value="<?=substr($sMenu_no,1,strlen($sMenu_no))?>"> 
									</td>
									<td class="modeual_nm"><?=$MENU_URL?></td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sMenu_no = "";
								}
							}
						
		
						}
					} else {
				?>
								<tr align="center" bgcolor="#FFFFFF">
									<td height="25" colspan="12">등록 목록이 없습니다.<!--한국어 등록 목록이 없습니다.--></td>
								</tr>
				<?   
					}  
				?>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>
	</div>
	<br />
	<div class="bot_close"><a href="javascript: window.close();"><img src="../images/admin/icon_pclose.gif" alt="닫기" /></a></div>
</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>