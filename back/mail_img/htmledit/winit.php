<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<TITLE>Ŵ������ - ��������</TITLE>

<?if(!strstr($HTTP_USER_AGENT,'MSIE') || strstr($HTTP_USER_AGENT,'Opera')):?>
<script language=javascript>
alert('\n�������� ����� �ͽ��÷η������� ��밡���մϴ�.   \n');
top.close();
</script>
<?endif?>

</HEAD>
<BODY LEFTMARGIN=0 TOPMARGIN=0>
<INPUT TYPE=HIDDEN ID='editSetting1' VALUE="HTML">
<INPUT TYPE=HIDDEN ID='editSetting2' VALUE="HTML">
<TEXTAREA ID='ContentArea' STYLE='display:none;'></TEXTAREA>
<IFRAME NAME='EditFrame' SRC='./wedit.html' WIDTH=100% HEIGHT=100% FRAMEBORDER=0></IFRAME>
</BODY>
</HTML>

<script>
document.getElementById('editSetting2').value    = opener.document.CommentForm.RP_HTML.value;
document.getElementById('ContentArea').innerText = opener.document.CommentForm.RP_CONTENT.innerText;
</script>