<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn.inc";

	$sYY = date(Y);
	$sMM = date(m);
	$sDD = date(d);

	if (empty($this_date)) {
		$this_date_v = $sYY.$sMM.$sDD;	
		$this_date = $sYY."-".$sMM."-".$sDD;	
	}

?>		
<HTML>
<HEAD>
<TITLE><?echo $g_site_title?></TITLE>
<link rel="stylesheet" href="../inc/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<SCRIPT language="javascript">
<!--
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

	function goBack() {
//		document.frm.target = "frmain";
		document.frm.action="photo_list.php";
		document.frm.submit();
	}

	function goIn() {
	
		if (document.frm.source.value == "") {
			alert("분류를 선택하세요.");
			document.frm.source.focus();
			return;
		}

		if (document.frm.Title.value == "") {
			alert("제목를 입력하세요.");
			document.frm.Title.focus();
			return;
		}

		if (document.frm.memo.value == "") {
			alert("내용요약를 입력하세요.");
			document.frm.memo.focus();
			return;
		}

		if (document.frm.FileName01.value == "") {
			alert("첨부 이미지를 선택하세요.");
			document.frm.FileName01.focus();
			return;
		}

		document.frm.action = "photo_db.php";
		document.frm.submit()
	}
								

	//팝업
	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}


	function setData(lan, value) {
		if (lan == "han") {
			document.frm.data.value = value;
		} else {
			document.frm.eng_data.value = value;
		}
		getData(lan);
	}
	
	function getData(lan) {
		if (lan == "han") {
			div1.innerHTML = document.frm.data.value;
		} else {
			div2.innerHTML = document.frm.eng_data.value;
		}
	}
//-->
</SCRIPT>
</HEAD>
<BODY>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<object id="calendar" data="../inc/cm_calendar.htm" type="text/x-scriptlet" style='position:absolute;display:none;width:180;height:180;' VIEWASTEXT></object>
<script for="calendar" event="onscriptletevent(id, view)">
<!--
	set_cal2(id,view,document.all.dateRegFrom, document.all.dateRegFrom_v,document.all.dateRegTo, document.all.dateRegTo_v);
//-->
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->

<?	$_mode = "in"; ?>
<?	include "../inc/other_title.inc"; ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
	<tr>
		<td height="30" style="padding-left:20px"><a href="#">연구실적관리</a> > <a href="#"><?echo $_menu_str?></a> > <a href="#"><?echo $_mode_str?></a></td>
	</tr>
	<tr>
		<td height="1" bgcolor="#CCCCCC"></td>
	</tr>
</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<form name='frm' method='post' action='photo_db.php' enctype='multipart/form-data'>
				<input type="hidden" name="page" value="<?echo $page?>">									
				<input type="hidden" name="mode" value="add">
				<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
				<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
				<INPUT type="hidden" name="sel_source" value="<?echo $sel_source?>">
				<input type="hidden" name="BoardId" value="<?echo $BoardId?>">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="../images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td>
									* <?echo $_menu_str?> 입력 화면 입니다.<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">분 류</td>
								<td style="padding-left:10px" colspan="3">
									<select name="source">
										<option value="">분류선택</option>
										<?
											$query = "select * from tb_code_detail where pcode='PHOTO' and view_chk = 'Y' order by dcode_seq ";
											$result = mysql_query($query);
	
  										while($row = mysql_fetch_array($result)) {				
									
												$dcode = $row[dcode];
												$dcode_no = $row[dcode_no];
												$dcode_name = $row[dcode_name];
										?>
										<option value="<?echo $dcode?>"><?echo $dcode_name?></option>
										<?
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">제 목</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="Title" size="90" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">영문제목</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="eng_Title" size="90" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">내용요약</td>
								<td style="padding-left:10px" colspan="3">
									<textarea name="memo" cols="50" rows="3"></textarea>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">영문내용요약</td>
								<td style="padding-left:10px" colspan="3">
									<textarea name="eng_memo" cols="50" rows="3"></textarea>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">찰영일</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="in_date" size="8" value="<?echo $this_date?>" readOnly=1>	<img src="../images/month_icon.gif" align="absmiddle" style="cursor: hand;" onClick="javascript:show_cal(frm.in_date_v,frm.in_date,'yyyy-MM-dd',1);">
									<input name="in_date_v" type="hidden" value="<?echo $this_date_v?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">이미지첨부</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="FileName01" size="30" value="">&nbsp;&nbsp; (85 * 85 pixel)의 사이즈로 올리세요.
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<!--
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">이미지첨부</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="FileName02" size="30" value="">&nbsp;&nbsp; 파일 사이즈가 10M 이하일 경우 사용 하세요.
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">첨부 이미지</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="FileName03" size="50" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							-->
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">내 용</td>
								<td style="padding-left:10px" colspan="3" bgcolor="#FFFFFF">
									<? $image_path = "OTHER_".$BoardId;?>
									<br><div id=div1></div><br>
									<a href="javascript:NewWindow('edit_han.php?image_path=<?echo $image_path?>&lan=han', 'edit_html', '800', '500', 'No');"><img src="../images/button_write.gif" border ="0"></a>
									<input type="hidden" name="data" value="">
									<input type="hidden" name="isHtml" value="1">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>							
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">영문내용</td>
								<td style="padding-left:10px" colspan="3" bgcolor="#FFFFFF">
									<br><div id=div2></div><br>
									<a href="javascript:NewWindow('edit_eng.php?image_path=<?echo $image_path?>&lan=eng', 'edit_html', '800', '500', 'No');"><img src="../images/button_write.gif" border ="0"></a>
									<input type="hidden" name="eng_data" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>							
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">작성자</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="writer" size="20" value="<?echo $s_adm_name?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">보이기</td>
								<td style="padding-left:10px" colspan="3">
									<input type="checkbox" name="bshow" value="1"> 보이기로 설정 하시겠습니까? 예 일 경우 선택 하시요.
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">영문보이기</td>
								<td style="padding-left:10px" colspan="3">
									<input type="checkbox" name="eng_bshow" value="1"> 영문사이트에 보이기로 설정 하시겠습니까? 예 일 경우 선택 하시요.
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<a href="javascript:goIn();"><img src="../images/button_save.gif" border="0"></a>&nbsp;
									<a href="javascript:goBack();"><img src="../images/button_list_01.gif" border="0"></a>&nbsp;
									<!--<a href="javascript:goPre();"><img src="../images/button_ok.gif" border="0"></a>&nbsp;-->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</form>
			</table>
<!-- Main Section End -->
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?
	mysql_close($connect);
?>