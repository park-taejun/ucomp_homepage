<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";

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
	var month_name=new Array('1��','2��','3��','4��','5��','6��','7��','8��','9��','10��','11��','12��');
	var day_name=new Array('��','��','ȭ','��','��','��','��');

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
		document.frm.action="broad_list.php";
		document.frm.submit();
	}

	function goIn() {
	
		if (document.frm.Title.value == "") {
			alert("���� �Է��ϼ���.");
			document.frm.Title.focus();
			return;
		}

		if (document.frm.data.value == "") {
			alert("���� �Է��ϼ���.");
			document.frm.data.focus();
			return;
		}

		//if( checkIt(document.frm) ){
		//	document.frm.action = "broad_db.php";
		//  document.frm.submit()
		//} else {
		//  return;
		//}
								
		document.frm.action = "broad_db.php";
		document.frm.submit();
		
	}

	function regFile() {
//		document.frm_file.target = "frhidden";
//		document.frm_file.submit(); 
	}

	function goPre() {
					
		if (document.frm.Title.value == "") {
			alert("������ �Է��ϼ���.");
			document.frm.Title.focus();
			return;
		}
										
		if (document.frm.FileName01.value == "") {
			alert("÷�������� �����ϼ���.");
			document.frm.FileName01.focus();
			return;
		}
		
		//if (document.frm.FileName02.value == "") {
		//	alert("�̹����� �����ϼ���.");
		//	document.frm.FileName02.focus();
		//	return;
		//}

		//if (document.frm.FileName03.value == "") {
		//	alert("�̹����� �����ϼ���.");
		//	document.frm.FileName03.focus();
		//	return;
		//}

		if (document.frm.data.value == "") {
			alert("������ �Է��ϼ���.");
			document.frm.data.focus();
			return;
		}
				
//	document.frm.target = "_new";
		document.frm.action = "/people/people_read_pre.php";
		document.frm.submit();
		
	}
	
	function checkIt(form) {      
		getEditCheck(form);
		form.data.value = form.BB_CONTENT.value;
		if(!form.data.value) {
			alert('������ �Է��ϼ���!');
      return false;
		}
		return true;
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
		<td height="30" style="padding-left:20px"><a href="#">����Ȱ������</a> > <a href="#"><?echo $_menu_str?></a> > <a href="#"><?echo $_mode_str?></a></td>
	</tr>
	<tr>
		<td height="1" bgcolor="#CCCCCC"></td>
	</tr>
</table>

<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<form name='frm' method='post' action='broad_db.php' enctype='multipart/form-data'>
				<input type="hidden" name="page" value="<?echo $page?>">									
				<input type="hidden" name="mode" value="add">
				<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
				<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
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
									* <?echo $_menu_str?> �Է� ȭ�� �Դϴ�.<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�� ��</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="Title" size="90" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�� ó</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="source" size="40" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�濵��</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="in_date" size="8" value="<?echo $this_date?>" readOnly=1>	<img src="../images/month_icon.gif" align="absmiddle" style="cursor: hand;" onClick="javascript:show_cal(frm.in_date_v,frm.in_date,'yyyy-MM-dd',1);">
									<input name="in_date_v" type="hidden" value="<?echo $this_date_v?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�� ��</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="data" size="10" value=""> ��) 02��  30��
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�̹���÷��</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="FileName01" size="30" value="">&nbsp;&nbsp; (100 * 100 pixel)�� ������� �ø�����.
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">������÷��</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="FileName02" size="30" value="">&nbsp;&nbsp; ���� ����� 10M ������ ��� ��� �ϼ���.
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<!--
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">÷�� �̹���</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="FileName03" size="50" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							-->
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">��������</td>
								<td style="padding-left:10px" colspan="3">
									���� ����� 10M �̻��� ��� FTP�� ������ ������ �ø��� �� �Ʒ��� ��θ� �Է��ϼ���.<br>
									<input type="text" name="Ref_url" size="50" value=""> ��) /multi/MVI_0001.mpg
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�ۼ���</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="writer" size="20" value="<?echo $s_adm_name?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">���̱�</td>
								<td style="padding-left:10px" colspan="3">
									<input type="checkbox" name="bshow" value="1"> ���̱�� ���� �Ͻðڽ��ϱ�? �� �� ��� ���� �Ͻÿ�.
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