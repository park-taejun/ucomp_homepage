<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
# ===================================================================
# File Name    : event_read.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.04.27
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# ===================================================================

# ===================================================================
	$s_adm_no = $_SESSION['s_adm_no'];
# ===================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$menu_right = "EV002"; // 메뉴마다 셋팅 해 주어야 합니다
#====================================================================
# common_header Check Session
#====================================================================

	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/event/event.php";

#====================================================================
# DML Process
#====================================================================

	if ($mode == "D") {
		$result = deleteEvent($conn, $s_adm_no, $seq_no);
	}

	if ($mode == "S") {

		$arr_rs = selectEvent($conn, $seq_no);

		$rs_seq_no				= trim($arr_rs[0]["SEQ_NO"]); 
		$rs_ev_type				= trim($arr_rs[0]["EV_TYPE"]); 
		$rs_title					= trim($arr_rs[0]["TITLE"]); 
		$rs_ev_start			= trim($arr_rs[0]["EV_START"]); 
		$rs_ev_start_time	= trim($arr_rs[0]["EV_START_TIME"]); 
		$rs_ev_end				= trim($arr_rs[0]["EV_END"]); 
		$rs_ev_end_time		= trim($arr_rs[0]["EV_END_TIME"]); 
		$rs_ev_query			= trim($arr_rs[0]["EV_QUERY"]); 
		$rs_all_flag			= trim($arr_rs[0]["ALL_FLAG"]); 
		$rs_file_nm				= trim($arr_rs[0]["FILE_NM"]); 

		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 

		$arr_ex = selectEventEx($conn, $rs_seq_no);

		// 전체 대상 인원
		$all_mem = getAllEventMem($conn, $seq_no, $rs_all_flag);

	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_ev_type=".$con_ev_type."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "event_list.php<?=$strParam?>";
</script>
</head>
</html>
<?
		exit;
	}	
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<script language="javascript" type="text/javascript">
<!--

$(document).ready(function() {
	$(".date").datepicker({
		dateFormat: "yy-mm-dd"
		,minDate: new Date(2013, 4-1, 15)	//(연, 월-1, 일)
	//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
	});
});


function js_list() {
	document.location = "event_list.php<?=$strParam?>";
}

function js_view(seq_no) {

	var frm = document.frm;
		
	frm.seq_no.value = seq_no;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
	frm.action = "event_write.php";
	frm.submit();
		
}

function js_delete() {

	var frm = document.frm;

		bDelOK = confirm('자료를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}

}

function js_chk_mem_list(ex_no, seq_no) {

		var url = "event_chk_mem_popup.php?mode=S&ex_no="+ex_no+"&seq_no="+seq_no;
		NewWindow(url, '답변자리스트', '1017', '700', 'YES');

}

//-->
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

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
<input type="hidden" name="con_ev_type_code" value="<?=$con_ev_type_code?>" />

			<h3 class="conTitle"><?=$p_menu_name?></h3>
			<div class="sp10"></div>

			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">

				<colgroup>
					<col width="120" />
					<col width="*" />
					<col width="120" />
					<col width="*" />
				</colgroup>

				<tr>
					<th>내용</th>
					<td colspan="3" class="line">
						<?=nl2br($rs_title)?>
					</td>
				</tr>

				<tr> 
					<th>대상구분</th>
					<td colspan="3" class="line">
						<? if (($rs_all_flag =="Y") || ($rs_all_flag =="")) echo "회원전체"; ?>
						<? if ($rs_all_flag =="N") echo "선택회원"; ?>
					</td>
				</tr>
				<tr> 
					<th>전송여부</th>
					<td colspan="3" class="line">
						<? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "미전송"; ?>
						<? if ($rs_use_tf =="N") echo "전송"; ?>
					</td>
				</tr>
			</table>

			<div class="sp20"></div>

			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">

				<colgroup>
					<col width="120" />
					<col width="*" />
					<col width="*" />
					<col width="70" />
				</colgroup>
				<?

					if (sizeof($arr_ex) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_ex); $j++) {
							$EX_NO					= trim($arr_ex[$j]["EX_NO"]);
							$SEQ_NO					= trim($arr_ex[$j]["SEQ_NO"]);
							$EX							= trim($arr_ex[$j]["EX"]);
							
							// 전체 중에 몇명인지..
							
				?>
				<tr>
					<th>답변</th>
					<td colspan="2" class="line">
						<?=$EX?> 
					</td>
					<td class="line">
						<?
							// 대상인원
							// 선택 인원
							$chk_mem_cnt = getEventChkMem($conn, $EX, $rs_ev_start, $rs_ev_end);
						?>
						<?=number_format($all_mem)?> / 
						<a href="javascript:void(0);" onClick="js_chk_mem_list('<?=$EX_NO?>','<?=$seq_no?>');"><b><?=number_format($chk_mem_cnt)?></b></a>
					</td>
				</tr>
				<?
						}
					}
				?>
				<tr>
					<th>기간</th>
					<td colspan="3" class="line">
						<? if ($rs_ev_start) { ?>
							<?=$rs_ev_start?> ~ <?=$rs_ev_end?>
						<? } ?>
					</td>
				</tr>
				</tr>
			</table>

			<div class="btnArea">
				<ul class="fRight">
				<?	if ($sPageRight_I == "Y") {?>
				<li><a href="javascript:js_view('<?=$seq_no?>');"><img src="../images/admin/btn_modify.gif" alt="수정" /></a></li>
				<?	}?>
				<li><a href="javascript:js_list();"><img src="../images/admin/btn_list.gif" alt="목록" /></a></li>
				<?	if (($seq_no <> "") && ($sPageRight_D == "Y")) {?>
				<li><a href="javascript:js_delete();"><img src="../images/admin/btn_delete.gif" alt="삭제" /></a></li>
				<?	}?>
				</ul>
			</div>
		</section>
		<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>