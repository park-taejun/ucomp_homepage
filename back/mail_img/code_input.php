<?
	include "admin_session_check.inc";
?>		
<HTML>
<HEAD>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE>:::::������ ������ �ý���:::::</TITLE>

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="code_list.php";
		document.frm.submit();
	}

	function goIn() {
					
		if (document.frm.code.value == "") {
			alert("�ڵ带 �Է��ϼ���.");
			document.frm.code.focus();
			return;
		}
		
		if (document.frm.name.value == "") {
			alert("������ �Է��ϼ���.");
			document.frm.name.focus();
			return;
		}
				
		document.frm.target = "frhidden";
		document.frm.action = "code_db.php";
		document.frm.submit();
		
	}

		
//-->
</SCRIPT>
</HEAD>
<BODY>
<br>
<br>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table width='99%' bgcolor="#EEEEEE">
			<tr align="center">
				<td align="left">
					<b><a href="code_list.php?parent=JOB">����</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=ML">ȸ�����</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=SZ">��ǥ��</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=BSZ">����</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=AW">������</a></b>&nbsp;&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<br>
<form name='frm' method='post' action='code_db.php'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?if ($parent == 'JOB') {?>
	<TD align="left"><B>����</B></TD>
<?} else if ($parent == 'ML') {?>
	<TD align="left"><B>ȸ�����</B></TD>
<?} else if ($parent == 'SZ') {?>
	<TD align="left"><B>��ǥ��</B></TD>
<?} else if ($parent == 'BSZ') {?>
	<TD align="left"><B>����</B></TD>
<?} else if ($parent == 'AW') {?>
	<TD align="left"><B>������</B></TD>
<?} else if ($parent == 'LT') {?>
	<TD align="left"><B>Length</B></TD>
<?} else if ($parent == 'CR') {?>
	<TD align="left"><B>Colour</B></TD>
<?}?>

	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="���" name="btn3">
		<input type="button" onClick="goBack();" value="���" name="btn4">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		code :
	</th>
	<td>
		<input type="text" name="code" size="25" value="">
	</td>
</tr>
<tr>
	<th>
		�̸� :
	</th>
	<td>
		<input type="text" name="name" size="25" value="">
	</td>
</tr>
<tr>
	<th>
		���� :
	</th>
	<td>
		<textarea name="memo" cols=60 rows=3></textarea>
	</td>
</tr>
</TABLE>
<table border="0">
<tr>
	<td width="20">
		&nbsp;
	</td>
	<td>
</td>
</tr>
</table>
<input type="hidden" name="mode" value="add">
<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="parent" value="<?echo $parent?>">
</FORM>
</body>
</html>
