<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
# =============================================================================
# File Name    : event_chk_mem_popup.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2014.07.11
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
	$menu_right = "EV002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";
	
#====================================================================
# common_header
#====================================================================
	require "../../_common/common_header.php"; 


#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/event/event.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode 			= trim($mode);
	$seq_no			= trim($seq_no);
	$ex_no			= trim($ex_no);
	
	$arr_rs = selectEvent($conn, $seq_no);
	$rs_ev_start	= trim($arr_rs[0]["EV_START"]); 
	$rs_ev_end		= trim($arr_rs[0]["EV_END"]); 

	$arr_rs = getEventEx($conn, $seq_no, $ex_no);
	$rs_ex		= trim($arr_rs[0]["EX"]); 

	//echo $start_date;
	//echo $end_date;

	$result = false;
#====================================================================
# DML Process
#====================================================================
	
	if ($mode == "S") {
		$arr_rs_mem = getEventChkMemList($conn, $rs_ex, $rs_ev_start, $rs_ev_end);
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<!--<script type="text/javascript" src="../js/httpRequest.js"></script>--> <!-- Ajax js -->

<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  height: 420; background-color:#f7f7f7; overflow: auto; height: 525px; border:1px solid #d1d1d1;}
-->
</style>
<script language="javascript">

</script>

</head>
<body id="popup_stock">
<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" >
<input type="hidden" name="seq_no" value="<?= $seq_no ?>">
<input type="hidden" name="ex_no" value="<?= $ex_no?>">

<div id="popupwrap_stock">
	<h1>설문 참여자 리스트</h1>
	<div id="postsch">
		<h2>* 설문 참여자를 조회 합니다.</h2>

		<div class="sp20"></div>
		<div class="addr_inp">	
		<table cellpadding="0" cellspacing="0" border="0" width="95%">
		<tr>
			<td width="100%" align="left">
				<div id="pop_table_list">
					<div id="pop_table_scroll">
						<table id='t' cellpadding="0" class="rowstable" cellspacing="0" border="0" width="100%">
							<colgroup>
								<col width="10%">
								<col width="20%">
								<col width="40%">
								<col width="15%">
								<col width="15%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">NO.</th>
									<th scope="col">수신전화번호</th>
									<th scope="col">내용</th>
									<th scope="col">통신사</th>
									<th class="end" scope="col">답변일</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;

								if (sizeof($arr_rs_mem) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs_mem); $j++) {
										
										#rn, DCODE_NO, PCODE, DCODE, DCODE_NM, DCODE_SEQ_NO, 
										#USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

										$rn							= sizeof($arr_rs_mem) - $j;
										$OAADDR					= trim($arr_rs_mem[$j]["OAADDR"]);
										$TXT						= trim($arr_rs_mem[$j]["TXT"]);
										$TELECOM				= trim($arr_rs_mem[$j]["TELECOM"]);
										$INSERT_TIME		= trim($arr_rs_mem[$j]["INSERT_TIME"]);

										$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
										
										if ($UP_DATE != "") {
											$UP_DATE = date("Y-m-d",strtotime($UP_DATE));
										}
										
										if ($mode == "QS") {
											$result = insertEventMem($conn, $seq_no, $MEM_ID, $MEM_NM);
										}
							?>
								<tr>
									<td class="sort"><span><?=$rn?></span></td>
									<td><?=$OAADDR?></td>
									<td><?=$TXT?></td>
									<td><?=$TELECOM?></td>
									<td><?=$INSERT_TIME?></td>
								</tr>
							<?			
									}
								} else { 
							?> 
								<tr>
									<td height="50" align="center" colspan="5">데이터가 없습니다. </td>
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

		<div class="btn">
			<a href="javascript:self.close();"><img src="../images/common/btn/btn_close01.gif" alt="닫기" /></a>
		</div>

	</div>
	<!--
	<div class="bot_close"><a href="javascript: window.close();"><img src="../images/admin/icon_pclose.gif" alt="닫기" /></a></div>
	-->
</div>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>