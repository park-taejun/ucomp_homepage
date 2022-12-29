<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<TITLE>킴스보드 - 웹에디터</TITLE>

<?if(!strstr($HTTP_USER_AGENT,'MSIE') || strstr($HTTP_USER_AGENT,'Opera')):?>
<script language=javascript>
alert('\n웹에디터 기능은 익스플로러에서만 사용가능합니다.   \n');
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