<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
?>		
<HTML>
<HEAD>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="faq_list.php";
		document.frm.submit();
	}

	function goIn() {

		if (document.frm.title.value == "") {
			alert("제목을 입력하세요.");
			document.frm.title.focus();
			return;
		}
    	
		if (document.frm.faq.value == "") {
			alert("내용을 입력하세요.");
			document.frm.faq.focus();
			return;
		}
    	    	
		document.frm.target = "frhidden";
		document.frm.action = "faq_db.php";
		document.frm.submit();	
	}

	function containsCharsOnly(input,chars) {
    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
          		return false;
    	}
    	return true;	
	}

	function isNumber(input) {
    	var chars = "0123456789";
    	return containsCharsOnly(input,chars);
	}	
	
//-->
</SCRIPT>
</HEAD>
<BODY>
<form name='frm' method='post' action='faq_db.php'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>자주 묻는 질문</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="등록" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" width="100%">
<tr>
	<td width="15%" bgcolor="#DDDDDD" align="right">
		제목 :
	</td>
	<td width="85%" bgcolor="#EEEEEE" colspan="3">
		<input type="text" name="title" size="70">
	</td>
</tr>
<tr>
	<td width="15%" bgcolor="#DDDDDD" align="right">
		내용 :
	</td>
	<td width="85%" bgcolor="#EEEEEE" colspan="3">
		<textarea name="faq" cols="75" rows="15"></textarea>
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
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
</FORM>
</body>
</html>