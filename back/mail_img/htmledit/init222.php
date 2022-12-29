<?if(strstr($HTTP_USER_AGENT,'MSIE') && !strstr($HTTP_USER_AGENT,'Opera')):?>
<INPUT TYPE=HIDDEN NAME='BB_HTML' VALUE="">
<INPUT TYPE=HIDDEN NAME='BB_CONTENT' VALUE="">
<INPUT TYPE=HIDDEN ID='editSetting1' VALUE="<?=$bbs[WriteHtml]?>">
<INPUT TYPE=HIDDEN ID='editSetting2' VALUE="<?=$wStartHtml?>">
<input type=hidden ID='useup' value='<?=$FupPerm?>'>
<input type=hidden ID='uselk' value='<?=$FlkPerm?>'>
<TEXTAREA ID='ContentArea' STYLE='display:none;'><?=$reply_comment?></TEXTAREA>
<!--
<TEXTAREA ID='ContentArea' STYLE='display:none;'><?=$wSetContent?></TEXTAREA>
-->
<IFRAME NAME='EditFrame' SRC='../bbs/lib/htmledit/edit.html' WIDTH=100% HEIGHT=100% FRAMEBORDER=0></IFRAME>
<?else:?>
<INPUT TYPE=HIDDEN NAME='BB_HTML' VALUE="TEXT">
<TEXTAREA NAME='BB_CONTENT' ROWS=15 COLS=70 STYLE='width:100%;height:100%;padding:5px;'><?=$reply_comment?></TEXTAREA>
<!--
<TEXTAREA NAME='BB_CONTENT' ROWS=15 COLS=70 STYLE='width:100%;height:100%;padding:5px;'><?=$wSetContent?></TEXTAREA>
-->
<?endif;?>
<script language=javascript>
// 본문체크
function getEditCheck(f)
{
	if(navigator.userAgent.indexOf('MSIE') == -1 || navigator.userAgent.indexOf('Opera') != -1)
	{
		if (!f.BB_CONTENT.value)
		{
			alert('\n내용을 입력해 주세요.     \n');
			f.BB_CONTENT.focus();
			return true;
		}
	}
	else {

		var ef = frames.EditFrame;
		var Contents,Contentf;

		if (ef.document.getElementById('Elay_CodingType').value == 'HTML' && ef.document.getElementById('Elay_Srcview').checked == false)
		{
			Contentf = ef.EditArea;
			Contents = Contentf.document.body.innerHTML;
		}
		else {
			Contentf = ef.document.getElementById('Elay_Content');
			Contents = Contentf.innerText;
		}
		if (!Contents)
		{
			alert('\n내용을 입력해 주세요.     \n');
			Contentf.focus();
			return true;
		}
		
		f.BB_CONTENT.value = Contents;
		f.BB_HTML.value = ef.document.getElementById('Elay_CodingType').value;
	}
}
</script>