<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";

?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="photo_list.php";
		document.frm.submit();
	}

	function goIn() {
				
		if (document.frm.thumbnail.value == "") {
			alert("���� �̹����� ������ �ֽʽÿ�.");
			return;
		}

		if (document.frm.image_zoom.value == "") {
			alert("ū �̹����� ������ �ֽʽÿ�.");
			return;
		}
		
		document.frm.target = "frhidden";
		document.frm.action = "photo_db.php";
		document.frm.submit();
		
	}
		
//-->
</SCRIPT>
</HEAD>
<BODY>
<form name='frm' method='post' action='photo_db.php' enctype='multipart/form-data'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>ȫ������</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="���" name="btn3">
		<input type="button" onClick="goBack();" value="���" name="btn4">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table class="IN">
		<tr>
			<th>
				���� ���� :
			</th>
			<td>
				<input type="text" name="photo_title" size="50">
			</td>
		</tr>
		<tr>
			<th>
				���� �̹��� :
			</th>
			<td>
				<input type="file" name="thumbnail" size="40">
			</td>
		</tr>
		<tr>
			<th>
				ū �̹��� :
			</th>
			<td>
			<input type="file" name="image_zoom" size="40">
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<input type="hidden" name="mode" value="add">
</FORM>
</body>
</html>
