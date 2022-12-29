<?session_start();?>
<?
# =============================================================================
# File Name    : pop_group_order.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2016-06-14
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
	$menu_right = "MB007"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/group/group.php";

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
		$level_str = "1 뎁스";
	} else if (strlen($m_level) == 3) {
		$level_str = "2 뎁스";
	} else if (strlen($m_level) == 6) {
		$level_str = "3 뎁스";
	} else if (strlen($m_level) == 9) {
		$level_str = "4 뎁스";
	} else if (strlen($m_level) == 12) {
		$level_str = "5 뎁스";
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
	$arr_rs = listGroup($conn, $group_kind, $group_cd, $use_tf, $del_tf, $search_field, $search_str);

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

				var tempCode = document.frm.page_no[preid-2].value;
			
				document.frm.page_no[preid-2].value = document.frm.page_no[preid-1].value;
				document.frm.page_no[preid-1].value = tempCode;
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
	
				var tempCode = document.frm.page_no[preid-1].value;
				document.frm.page_no[preid-1].value = document.frm.page_no[preid].value;
				document.frm.page_no[preid].value = tempCode;
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
	document.frm.action = "pop_group_order_dml.php";
	document.frm.submit();

}


</script>
</head>
<body id="popup_code">

<form name="frm" method="post" action="javascript:check_data();">
<input type="hidden" name='mode' value='add'>
<input type="hidden" name='m_level' value='<?=$m_level?>'>
<input type="hidden" name="group_kind" value="<?=$group_kind?>">

<div id="popupwrap_code">
	<h1>조직 순서 변경</h1>
	<div id="postsch_code">
		<h2>* 조직의 순서를 조정하는 화면 입니다.</h2>
		<div class="addr_inp">

		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
			<colgroup>
				<col width="20%">
				<col width="80%">
			</colgroup>
			<thead>
				<tr>
					<th>조직분류</th>
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
								<col width="25%" />
								<col width="75%" />
							</colgroup>
							<thead>
								<tr>
									<th>번호</th>
									<th class="end">조직명</th>
								</tr>
							</thead>
							<tbody>
				<?
					$nCnt = 0;
					
					$sPage_no = "";

					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							$GROUP_NO				= trim($arr_rs[$j]["GROUP_NO"]);
							$GROUP_CD				= trim($arr_rs[$j]["GROUP_CD"]);
							$GROUP_NAME			= trim($arr_rs[$j]["GROUP_NAME"]);
							$GROUP_SEQ01		= trim($arr_rs[$j]["GROUP_SEQ01"]);
							$GROUP_SEQ02		= trim($arr_rs[$j]["GROUP_SEQ02"]);
							$GROUP_SEQ03		= trim($arr_rs[$j]["GROUP_SEQ03"]);
							$GROUP_SEQ04		= trim($arr_rs[$j]["GROUP_SEQ04"]);
							$GROUP_SEQ05		= trim($arr_rs[$j]["GROUP_SEQ05"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
							
							if (strlen($m_level) == 0) {

								if (strlen(Trim($GROUP_CD)) == 3) {
									
									for ($j_sub = 0; $j_sub < sizeof($arr_rs) ; $j_sub++) {
										
										$SUB_GROUP_NO	= trim($arr_rs[$j_sub]["GROUP_NO"]);
										$SUB_GROUP_CD	= trim($arr_rs[$j_sub]["GROUP_CD"]);

										if (Trim($GROUP_CD) == substr(Trim($SUB_GROUP_CD),0,3)) {
											$sPage_no = $sPage_no ."^". $SUB_GROUP_NO;
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
										<?=$GROUP_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($GROUP_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($GROUP_SEQ)?>">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
								}
							}

							#중 분류인 경우
							if (strlen($m_level) == 3) {

								if ((substr($m_level,0,3) == substr(Trim($GROUP_CD),0,3)) && (strlen(Trim($GROUP_CD)) == 6)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_GROUP_NO	= trim($arr_rs[$j_sub]["GROUP_NO"]);
										$SUB_GROUP_CD	= trim($arr_rs[$j_sub]["GROUP_CD"]);

										if (Trim($GROUP_CD) == substr(Trim($SUB_GROUP_CD),0,6)) {
											$sPage_no = $sPage_no ."^". $SUB_GROUP_NO;
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
										<?=$GROUP_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($GROUP_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($GROUP_SEQ)?>">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
								}
							}
						
							#세부 분류인 경우

							if (strlen($m_level) == 6) {

								if ((substr($m_level,0,6) == substr(Trim($GROUP_CD),0,6)) && (strlen(Trim($GROUP_CD)) == 9)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_GROUP_NO	= trim($arr_rs[$j_sub]["GROUP_NO"]);
										$SUB_GROUP_CD	= trim($arr_rs[$j_sub]["GROUP_CD"]);
										
										if (Trim($GROUP_CD) == substr(Trim($SUB_GROUP_CD),0,9)) {
											
											$sPage_no = $sPage_no ."^". $SUB_GROUP_NO;

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
										<?=$GROUP_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($GROUP_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($GROUP_SEQ)?>">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
								}
							}
						

							if (strlen($m_level) == 9) {

								if ((substr($m_level,0,9) == substr(Trim($GROUP_CD),0,9)) && (strlen(Trim($GROUP_CD)) == 12)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_GROUP_NO	= trim($arr_rs[$j_sub]["GROUP_NO"]);
										$SUB_GROUP_CD	= trim($arr_rs[$j_sub]["GROUP_CD"]);
										
										if (Trim($GROUP_CD) == substr(Trim($SUB_GROUP_CD),0,12)) {
											
											$sPage_no = $sPage_no ."^". $SUB_GROUP_NO;

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
										<?=$GROUP_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($GROUP_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($GROUP_SEQ)?>">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
								}
							}

							if (strlen($m_level) == 12) {

								if ((substr($m_level,0,12) == substr(Trim($GROUP_CD),0,12)) && (strlen(Trim($GROUP_CD)) == 15)) {
									
									for ($j_sub = 0 ; $j_sub < sizeof($arr_rs); $j_sub ++) {
										
										$SUB_GROUP_NO	= trim($arr_rs[$j_sub]["GROUP_NO"]);
										$SUB_GROUP_CD	= trim($arr_rs[$j_sub]["GROUP_CD"]);
										
										if (Trim($GROUP_CD) == substr(Trim($SUB_GROUP_CD),0,15)) {
											
											$sPage_no = $sPage_no ."^". $SUB_GROUP_NO;

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
										<?=$GROUP_NAME?>
										<input type="hidden" name="catid[]" value="<?=trim($GROUP_NO)?>">
										<input type="hidden" name="cat_id[]" value="<?=trim($GROUP_SEQ)?>">
										<input type="hidden" name="arr_page_no[]" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
										<input type="hidden" name="page_no" value="<?=substr($sPage_no,1,strlen($sPage_no))?>"> 
									</td>
								</tr>
				
				<?
									$nCnt = $nCnt++;
									$sPage_no = "";
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