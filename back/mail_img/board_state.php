<?
	include "admin_session_check.inc";
	include "../dbconn.inc";


	$sYY = date(Y);
	$sMM = date(m);
	$sDD = date(d);

	$chkp = trim($chkp);

	if (empty($start_date)) {
		$start_date_v = $sYY.$sMM.$sDD;	
		$start_date = $sYY."-".$sMM."-01";	
	} else {
		$start_date_v = trim($start_date_v);	
		$start_date = trim($start_date);	
	}

	if (empty($end_date)) {
		$end_date_v = $sYY.$sMM.$sDD;	
		$end_date = $sYY."-".$sMM."-".$sDD;	
	} else {
		$end_date_v = trim($end_date_v);	
		$end_date = trim($end_date);	
  }
	
	if ($chkp == "1") {
		$qry_con1 = " and wdate >= '$start_date' and date_add(wdate, interval -1 day) < '$end_date' "; 
		$qry_con2 = " and RegDate >= '$start_date' and date_add(RegDate, interval -1 day) < '$end_date' "; 
		$qry_con3 = " and a.RegDate >= '$start_date' and date_add(a.RegDate, interval -1 day) < '$end_date' "; 
	} else {
		$qry_con1 = ""; 
		$qry_con2 = ""; 
		$qry_con3 = ""; 	
	}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="inc/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<script language="javascript">

	var month_name=new Array('1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월');
	var day_name=new Array('일','월','화','수','목','금','토');

	function show_cal(obj_id,obj_view,datetype,select) {
	   cur_id = obj_id;
	   cur_view = obj_view;
	   
	   if (window.event.clientY + 10 + parseInt(document.all.calendar.style.height) > document.body.clientHeight){
	       document.all.calendar.style.top = document.body.clientHeight - parseInt(document.all.calendar.style.height);
	       }
	   else {
	       document.all.calendar.style.top = window.event.clientY + 10;
	       }
	       
	   if (window.event.clientX - 100 + parseInt(document.all.calendar.style.width) > document.body.clientWidth)
	       document.all.calendar.style.left = document.body.clientWidth - parseInt(document.all.calendar.style.width);
	   else 
	       document.all.calendar.style.left = window.event.clientX - 100;

	   document.all.calendar.style.display = 'block';
	   document.all.calendar.month_name=month_name;
	   document.all.calendar.day_name=day_name;
	   document.all.calendar.datetype = datetype;
	   document.all.calendar.select = select;
	   document.all.calendar.calWidth = document.all.calendar.style.width;
	   document.all.calendar.curDate = obj_id.value;
	   showcal = true;
	}

	function set_cal(id,view)
	{
	   document.all.calendar.style.display = 'none';

	   if (id == "" || view == "") return;
	   
	   cur_id.value = id;
	   cur_view.value = view;
	}

	function set_cal2(id,view,start_id,start,end_id,end)
	{
	   document.all.calendar.style.display = 'none';

	   if (id == "" || view == "") return;
	   
	   cur_id.value = id;
	   cur_view.value = view;
	   
	   if (cur_id == start_id) {
	      if (cur_id.value > end_id.value) {
		 if (cur_id.value.substring(8) > end_id.value.substring(8)) end_id.value = cur_id.value;
	         else end_id.value = cur_id.value.substring(0,8)+end_id.value.substring(8);
	         end.value = cur_view.value;
	      }
	   } else if (cur_id == end_id) {
	      if (cur_id.value < start_id.value) {
		 if (cur_id.value.substring(8) < start_id.value.substring(8)) start_id.value = cur_id.value; 
	         start_id.value = cur_id.value.substring(0,8)+start_id.value.substring(8);
	         start.value = cur_view.value;
	      }
	   }   
	}

	function check_p() {
		if (document.frm.chkp.checked == 1) {
			document.frm.start_date.disabled = 0;
			document.frm.end_date.disabled = 0;
			document.frm.btn_s.disabled = 0;
			document.frm.btn_e.disabled = 0;
		} else {
			document.frm.start_date.disabled = 1;
			document.frm.end_date.disabled = 1;
			document.frm.btn_s.disabled = 1;
			document.frm.btn_e.disabled = 1;
		}
	}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="check_p();">
<object id="calendar" data="./inc/cm_calendar.htm" type="text/x-scriptlet" style='position:absolute;display:none;width:180;height:180;' VIEWASTEXT></object>
<script for="calendar" event="onscriptletevent(id, view)">
<!--
	set_cal2(id,view,document.all.dateRegFrom, document.all.dateRegFrom_v,document.all.dateRegTo, document.all.dateRegTo_v);
//-->
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->

<?	$_mode = "list"; ?>
<?	include "./inc/paper_title.inc"; ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
	<tr>
		<td height="30" style="padding-left:20px"><a href="#">게시물 관리</a> > <a href="#">게시물 등록현황</a> > <a href="#">조회</a></td>
	</tr>
	<tr>
		<td height="1" bgcolor="#CCCCCC"></td>
	</tr>
</table>

<!-- Site Map Section End -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
 				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30">
						<img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Form --->					
						<FORM name="frm" method="post" action="board_state.php">
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td colspan="4"></td>
							</tr>
							<tr height="32"> 
								<td width="70" style="padding : 0,0,0,10"  bgcolor="#F1F1F1">									
									기간 선택
								</td>
								<td width="35" align="left" bgcolor="#F1F1F1">									
									<input type="checkbox" name="chkp" <?if ($chkp == "1") echo "checked";?> onClick=check_p(); value="1">
								</td>
								<td  bgcolor="#F1F1F1" >
									<input type="text" name="start_date" size="8" value="<?echo $start_date?>" readOnly=1>	
										<img name="btn_s" src="images/month_icon.gif" align="absmiddle" style="cursor: hand;" onClick="javascript:show_cal(frm.start_date_v,frm.start_date,'yyyy-MM-dd',1);">
									<input name="start_date_v" type="hidden" value="<?echo $start_date_v?>?>"> 
									~
									<input type="text" name="end_date" size="8" value="<?echo $end_date?>" readOnly=1>	
										<img name="btn_e" src="images/month_icon.gif" align="absmiddle" style="cursor: hand;" onClick="javascript:show_cal(frm.end_date_v,frm.end_date,'yyyy-MM-dd',1);">
									<input name="end_date_v" type="hidden" value="<?echo $end_date_v?>?>">
								</td>
								<td align="right"  style="padding-right:5px" bgcolor="#F1F1F1">
									<a href="javascript:document.frm.submit();"><img src="images/button_serch.gif" border="0" align="absmiddle"></a>
								</td>
							</tr>
							<tr height="2" bgcolor="#CCCCCC"> 
								<td colspan="4"></td>
						    </tr>
						</table>
						    <!-- 검색조건 끝 -->
						<br>
						<table cellpadding="0" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="2" bgcolor="#000000" colspan="7"></td>
							</tr>
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td width="35%">항목</td>
								<td width="35%">세부항목</td>
								<td width="30%">게시물수</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>							
							<tr>
							<?
								$total_cnt = 0;
								
								#BBS
								
								$query = "select count(*) cnt from tb_bbs where code ='G1' ".$qry_con1;
								$result = mysql_query($query);
								$list = mysql_fetch_array($result);

								$cnt = $list[cnt];
								
								#echo $query;

								$total_cnt = $total_cnt + $cnt; 

							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td>BBS</td>
								<td>-</td>
								<td><?echo $cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?
								#NEWS
								
								$query = "select count(*) cnt from tb_other_board where BoardId = 'B1' ".$qry_con2;
								$result = mysql_query($query);
								$list = mysql_fetch_array($result);

								$cnt = $list[cnt];
								
								$total_cnt = $total_cnt + $cnt; 
		
							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td>NEWS</td>
								<td>-</td>
								<td><?echo $cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?
								#Call for Paper
								
								$query = "select count(*) cnt from tb_call_paper where code_id = 'K1' ".$qry_con2;
								$result = mysql_query($query);
								$list = mysql_fetch_array($result);

								$cnt = $list[cnt];

								$total_cnt = $total_cnt + $cnt; 
		
							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td>Call for Paper</td>
								<td>-</td>
								<td><?echo $cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?
								#Focus
								
								$query = "select count(*) cnt from tb_other_board where BoardId = 'F1' ".$qry_con2;
								$result = mysql_query($query);
								$list = mysql_fetch_array($result);

								$cnt = $list[cnt];

								$total_cnt = $total_cnt + $cnt; 
		
							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td>FOCUS</td>
								<td>-</td>
								<td><?echo $cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>							
							<tr align="center" height="25" bgcolor='#E9E9E9'>
								<td>동영상</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?
								#동영상
								
								#$query = "select b.dcode_name 
								#            from tb_other_board a, tb_code_detail b 
								#           where a.source = b.dcode and a.BoardId = 'B4' ";
								
								$query = "select count(*) cnt, b.dcode_name 
								            from tb_other_board a, tb_code_detail b 
								           where a.source = b.dcode and a.BoardId = 'B4' ".$qry_con3." group by b.dcode_name ";
								
								$result = mysql_query($query);
								$total_mov_cnt = 0;
  							while($row = mysql_fetch_array($result)) {				
									
									$cnt = $row[cnt];
									$dcode_name = $row[dcode_name];
									
									$total_mov_cnt = $total_mov_cnt + $cnt;
									
									$total_cnt = $total_cnt + $cnt; 
							?>
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td>&nbsp;</td>
								<td><?echo $dcode_name?></td>
								<td><?echo $cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?	
								}
							?>					
							<tr align="center" height="25" bgcolor='#E9E9E9'>
								<td>동영상 합계</td>
								<td>&nbsp;</td>
								<td><?echo $total_mov_cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>							
							<tr align="center" height="25" bgcolor='#E9E9E9'>
								<td>사진</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?
								#사진
								
								#$query = "select b.dcode_name 
								#            from tb_other_board a, tb_code_detail b 
								#           where a.source = b.dcode and a.BoardId = 'B4' ";
								
								$query = "select count(*) cnt, b.dcode_name 
								            from tb_other_board a, tb_code_detail b 
								           where a.source = b.dcode and a.BoardId = 'B5' ".$qry_con3." group by b.dcode_name ";
								
								$result = mysql_query($query);

  							$total_pho_cnt = 0;
								
								while($row = mysql_fetch_array($result)) {				
									
									$cnt = $row[cnt];
									$dcode_name = $row[dcode_name];
									$total_pho_cnt = $total_pho_cnt + $cnt;

									$total_cnt = $total_cnt + $cnt; 
							?>
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td>&nbsp;</td>
								<td><?echo $dcode_name?></td>
								<td><?echo $cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<tr>
								<td align="center" colspan="7">
								</td>
							</tr>
							<?	
								}
							?>					
							<tr align="center" height="25" bgcolor='#E9E9E9'>
								<td>사진 합계</td>
								<td>&nbsp;</td>
								<td><?echo $total_pho_cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>							
							<?
								#신문 보도
								
								$query = "select count(*) cnt from tb_other_board where BoardId = 'B2' ".$qry_con2;
								$result = mysql_query($query);
								$list = mysql_fetch_array($result);

								$cnt = $list[cnt];

								$total_cnt = $total_cnt + $cnt; 
		
							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td>신문 보도</td>
								<td>-</td>
								<td><?echo $cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?
								#방송 보도
								
								$query = "select count(*) cnt from tb_other_board where BoardId = 'B3' ".$qry_con2;
								$result = mysql_query($query);
								$list = mysql_fetch_array($result);

								$cnt = $list[cnt];

								$total_cnt = $total_cnt + $cnt; 
		
							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td>방송 보도</td>
								<td>-</td>
								<td><?echo $cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>							
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>							
							<tr align="center" height="25" bgcolor='#E9E9E9'>
								<td>총합계</td>
								<td>&nbsp;</td>
								<td><?echo $total_cnt?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>							
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>							
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?
	mysql_close($connect);
?>
