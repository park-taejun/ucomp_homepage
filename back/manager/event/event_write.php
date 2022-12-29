<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
# ===================================================================
# File Name    : event_write.php
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

	if ($mode == "I") {
		$new_seq_no =  insertEvent($conn, $ev_type, $title, $ev_start, $ev_start_time, $ev_end, $ev_end_time, $ev_query, $all_flag, $file_nm, $use_tf, $s_adm_no);
		
		$row_cnt = count($ex);

		for ($k = 0; $k < $row_cnt; $k++) {
			if ($ex[$k]) {
				$result = insertEventEx($conn, ($k+1), $new_seq_no, $ex[$k]);
			}
		}

	}

	if ($mode == "U") {
		$result = updateEvent($conn,$ev_type, $title, $ev_start, $ev_start_time, $ev_end, $ev_end_time, $ev_query, $all_flag, $file_nm, $use_tf, $s_adm_no, $seq_no);

		$row_cnt = count($ex);

		for ($k = 0; $k < $row_cnt; $k++) {
			if ($ex[$k]) {
				$result = insertEventEx($conn, ($k+1), $seq_no, $ex[$k]);
			}
		}


	}


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


function js_save() {

	var frm = document.frm;
	var seq_no = "<?=$seq_no ?>";

	frm.title.value = frm.title.value.trim();
	if (frm.title.value == "") {
		alert('제목을 입력해주세요.');
		frm.title.focus();
		return ;		
	}

	if (document.frm.rd_all_flag == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_all_flag[0].checked == true) {
			frm.all_flag.value = "Y";
		} else {
			frm.all_flag.value = "N";
		}
	}

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (isNull(seq_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.seq_no.value = frm.seq_no.value;
	}


	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
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


	function cal_pre(tmpForm,flag) {
		var max_len=0;
		var frm;

		if(tmpForm == 'frm'){
			max_len=80;
		}else{
			max_len=130;
		}
		if(flag == 'Y'){
			frm='parent';
		} else if(flag == 'K'){
			frm='wrt_frm';
		} else{
			frm='document';
		}
		cal_byte(tmpForm,max_len,frm);
	}

	function cal_byte(aquery,max_len,frm) {
		
		var tmpStr;
		var byte;
		var temp=0;
		var onechar;
		var tcount;
		var cnt=0;
		tcount = 0;

		tmpStr = eval(frm+"."+aquery+".title");
		byte = 	 eval(frm+"."+aquery+".cbyte");

		temp = tmpStr.value.length;

		msg_length = cal_msglen(tmpStr.value,aquery,frm);
		byte.value = msg_length;

		return;
	}

	function assert_msglen(tmpStr, maximum) {
		var k = 0;
		var tcount = 0;
		var onechar;
		var msglen = tmpStr.length;

		for(k=0;k<msglen;k++) {
			onechar = tmpStr.charAt(k);
			if(escape(onechar).length > 4) {
				tcount += 2;
			} else {
				tcount++;
			}

			if(tcount>maximum) {
				tmpStr = tmpStr.substring(0,k-1);
				break;
			}
		}
		return tmpStr;
	}

	function cal_msglen(message,aquery,frm) {
		var nbytes = 0;
		var cnt=0;

		for (i=0; i<message.length; i++) {
			var ch = message.charAt(i);
			if (escape(ch).length > 4) {			
				if(aquery == 'gsms'){cnt = han_ck(aquery,nbytes,frm);}
				nbytes += 2;
				nbytes -= cnt;

			} else {
				nbytes++;
			}
		}

		return nbytes;
	}

	function han_ck(aquery,tcount,frm) {
		var tmpStr;
		var temp=0;
		var cnt=2;
	
		tmpStr = eval(frm+"."+aquery+".SND_MSG");
		temp = tmpStr.value.length;

		tmpStr = tmpStr.value.substring(0,tcount);
		alert('해외 전송에서 한글은 전송하지 않습니다');

		var msg = eval(frm+"."+aquery+".SND_MSG");
		msg.value = tmpStr;
		return cnt;
	}

	function js_chk_sms() {
		var frm = document.frm;
		if (frm.sms_type.checked == true) {
			frm.contents.value = frm.sms_msg.value;
			frm.callback.value = frm.callback_msg.value;
		} else {
			frm.contents.value = "";
			frm.callback.value = "";
		}
		cal_pre('frm','N');
	}


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
						<textarea name="title" style="height:70px;width:200px;" onkeyup="javascript:cal_pre('frm','N')"/><?=$rs_title?></textarea><br>
						<input type=text name="cbyte"  readonly value=0 style='background-color:#CCCCCC; border-width:0; border-color:#000000; border-style:solid;text-align:right;width: 20px;'>/<input type=text readonly style='background-color:#CCCCCC; border-width:0; border-color:#000000; border-style:solid;text-align:right;width: 20px;' value='80'> Byte
						<div class="sp10"></div>
						* 80 Byte 이상 입력 하시면 LMS로 발송 됩니다.<br>
						* 이름이 들어갈 부분은 #NAME 으로 넣어 주십시오.
					</td>
				</tr>
			</table>
			<div class="sp10"></div>
			<div style="width: 95%; height: 20px; text-align: right; vertical-align:middle; margin: 10px 0 10px 0;">
				<div style="display:inline; margin-bottom: 5px;"></div> <a href="javascript:js_addRow('myTbody');"><b>답변추가</b></a>
			</div>
			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
				<colgroup>
					<col width="120" />
					<col width="*" />
					<col width="120" />
					<col width="*" />
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
					<td colspan="3" class="line">
						<input type="text" name="ex[]" class="txt" value="<?=$EX?>" style="width: 90%;" />
					</td>
				</tr>
				<?
						}
					} else {
				?>
				<tr>
					<th>답변</th>
					<td colspan="3" class="line">
						<input type="text" name="ex[]" class="txt" value="<?=$EX?>" style="width: 90%;" />
					</td>
				</tr>
				<?
					}
				?>
				<tbody id="myTbody">

				</tbody>
				<tr> 
					<th>대상구분</th>
					<td colspan="3" class="line">
						<input type="radio" class="radio" name="rd_all_flag" value="Y" <? if (($rs_all_flag =="Y") || ($rs_all_flag =="")) echo "checked"; ?>> 회원전체&nbsp;&nbsp;<span style="width:30px;"></span>
						<input type="radio" class="radio" name="rd_all_flag" value="N" <? if ($rs_all_flag =="N")echo "checked"; ?>> 선택회원 
						<input type="hidden" name="all_flag" value="<?= $rs_all_flag ?>"> 
					</td>
				</tr>

				<tr class="end">
					<th>시작일</th>
					<td class="line">
						<input type="text" name="ev_start" class="date" value="<?=$rs_ev_start?>" style="width: 90px;" readonly="1"/>
					</td>
					<th>종료일</th>
					<td class="line">
						<input type="text" name="ev_end" class="date" value="<?=$rs_ev_end?>" style="width: 90px;" readonly="1"/>
					</td>
				</tr>
			</table>

			<div class="btnArea">
				<ul class="fRight">
				<?	if ($sPageRight_I == "Y") {?>
				<li><a href="javascript:js_save();"><img src="../images/admin/btn_confirm.gif" alt="확인" /></a></li>
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
<script>
	var rowCnt = <?=$j+1?>;

	function js_addRow(tbodyId) {
		
		var myTbody = document.getElementById(tbodyId);
		var rowId = rowCnt++;
		var row_1 = document.createElement("tr");
		var rowId_1 = "row_1_" + rowId;

		var cell_1_1 = document.createElement("th");
		var cell_1_2 = document.createElement("td");

		cell_1_1.setAttribute("class", "line");
		cell_1_2.setAttribute("class", "line");
		cell_1_2.setAttribute("colspan", "3");

		if (isIE()) {
			row_1.id = rowId_1;
		//	button = document.createElement("<input onclick=\"delRow('" + rowId + "')\">");
		} else {
			row_1.setAttribute("id", rowId_1);
		//	button.setAttribute("onclick", "delRow('" + rowId + "')");
		}

		cell_1_1.innerHTML = "답변";
		cell_1_2.innerHTML = "<input type='text' name='ex[]' class='txt' value='' style='width: 90%;' /> <a href='javascript:js_delRow("+rowCnt+")'>삭제</a>";

		row_1.appendChild(cell_1_1);
		row_1.appendChild(cell_1_2);

		myTbody.appendChild(row_1);


		//alert("st");
	}

	function js_delRow(i) {
		var cnt = i - 1;
		rowId = "row_1_"+cnt;
		var row = document.getElementById(rowId);
		row.parentNode.removeChild(row);
	}
</script>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>