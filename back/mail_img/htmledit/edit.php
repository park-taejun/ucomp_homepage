<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<TITLE>- ????????</TITLE>
<style>
body,p,table,tr,td,input,select,textarea,iframe
{
	font-family : ????;
	font-size : 9pt;
}
.BtnOver 
{
	border : 1px solid #F0F0E7; 
	padding : 1px; 
	cursor : hand;
}
#Elay_EditArea
{
	border :1 solid #DFDFDF;
	overflow : yes;
}
#Elay_Content
{
	width : 100%;
	height : 100%;
	line-height : 130%;
	padding : 9pt;
	overflow : auto;
	display : none;
	border :1 solid #DFDFDF;
	font-family : ????;
	font-size:9pt;
}
</style>
<script language="javascript">

//?̹???????
function getImageInsert()
{
	if(CheckTextMode()) return false;
	frames.EditArea.focus();
    var table = showModalDialog("./image.php?image_path=<?echo $image_path?>",0,"dialogheight=295px; dialogwidth=430px;scrollbars=no;status=0;help=0");
    if (table != null) 
    {
		var sel = WriteFrm.selection.createRange();
		sel.pasteHTML(table);
		sel.select();
    }
	frames.EditArea.focus();
}

</script>
</HEAD>

<BODY LEFTMARGIN=0 TOPMARGIN=0 SCROLL=no>

<TABLE WIDTH=100% HEIGHT=100% BGCOLOR='#F0F0E7' CELLSPACING=0 CELLPADDING=1 STYLE='border:1 solid dfdfdf;'>
<TR ID='Elay_Menu_01' style='display:none;'>
<TD HEIGHT=20>


<TABLE CELLSPACING=0 CELLPADDING=0>
<TR>
<TD WIDTH=5></TD>
<TD>

<SELECT onchange="FontChange('FontName' , this.value);">
<option value='verdana'>verdana</option>
<option value='Tahoma'>Tahoma</option>
<option value='????'>????</option>
<option value='????'>????</option>
<option value='?ü?'>?ü?</option>
<option value='????'>????</option>
</SELECT>

<SELECT onchange="FontChange('FontSize' , this.value);">
<option value=1>8pt</option>
<option value=2 selected>9pt</option>
<option value=3>12pt</option>
<option value=4>14pt</option>
<option value=5>18pt</option>
<option value=6>24pt</option>
<option value=7>36pt</option>
</SELECT>

</TD>
<TD WIDTH=10></TD>
<TD>

<IMG SRC='./image/b.gif' class='BtnOver' onclick="TagEdit('Bold');" alt='????'>
<IMG SRC='./image/i.gif' class='BtnOver' onclick="TagEdit('Italic');" alt='?????̱?'>
<IMG SRC='./image/u.gif' class='BtnOver' onclick="TagEdit('Underline');" alt='????'>

<IMG SRC='./image/line.gif'>

<IMG SRC='./image/left.gif' class='BtnOver' onclick="TagEdit('JustifyLeft');" alt='????????'>
<IMG SRC='./image/center.gif' class='BtnOver' onclick="TagEdit('JustifyCenter');" alt='?߾?????'>
<IMG SRC='./image/right.gif' class='BtnOver' onclick="TagEdit('JustifyRight');" alt='??????????'>

<IMG SRC='./image/line.gif'>

<IMG SRC='./image/gleft.gif' class='BtnOver' onclick="TagEdit('Outdent');" alt='???????? ?б?'>
<IMG SRC='./image/gright.gif' class='BtnOver' onclick="TagEdit('Indent');" alt='?????????? ?б?'>
<IMG SRC='./image/hr.gif' class='BtnOver' onclick="TagEdit('InsertHorizontalRule');" alt='?и?????'>
<IMG SRC='./image/num.gif' class='BtnOver' onclick="TagEdit('InsertOrderedList');" alt='??ȣ????'>
<IMG SRC='./image/ul.gif' class='BtnOver' onclick="TagEdit('InsertUnorderedList');" alt='???б?ȣ'>

<IMG SRC='./image/line.gif'>

<IMG SRC='./image/bgcolor.gif' class='BtnOver' onclick="ColorChartView('Backcolor');" alt='???ڹ?????'>
<IMG SRC='./image/textcolor.gif' class='BtnOver' onclick="ColorChartView('Forecolor');" alt='???ڻ?'>

<IMG SRC='./image/line.gif'>

<IMG SRC='./image/table.gif' class='BtnOver' onclick="getTableSet();" alt='???̺?????'>
<IMG SRC='./image/a.gif' class='BtnOver' onclick="getLink();" alt='??ũ'>
<IMG SRC='./image/img.gif' class='BtnOver' onclick="getImageInsert();" alt='?̹???????'>
<IMG SRC='./image/tag.gif' class='BtnOver' onclick="SrcView(false);" alt='?±׺???'>
</TD>
<TD WIDTH=5></TD>
</TR>
</TABLE>

</TD>
</TR>
<TR WIDTH=100% HEIGHT=100%>
<TD VALIGN=TOP>


<DIV ID='LodingLay' style='position:relative;left:150;top:150;'>
<TABLE CELLSPACING=0 CELLPADDING=5 BGCOLOR='#ECE9D8'>
<TR>
<TD><IMG SRC='./image/loading.gif'></TD>
</TR>
</TABLE>
</DIV>

<IFRAME NAME='EditArea' ID='Elay_EditArea' WIDTH='100%' HEIGHT='100%' FRAMEBORDER='0'></IFRAME>
<TEXTAREA ID='Elay_Content'></TEXTAREA>

</TD>
</TR>
<TR ID='Elay_Menu_02' style='display:none;'>
<TD HEIGHT=20>

<TABLE WIDTH=100%>
<TR>
<TD>
<SELECT ID='Elay_CodingType' onchange="getCodingType(this);">
<option value='HTML'>????????</option>
<option value='TEXT'>TEXT????</option>
</SELECT>
<INPUT TYPE=checkbox ID=Elay_Srcview onclick='SrcView(this);' >?±׺???

<span id=media_align style='display:none;'>
<img src='./image/img.gif' align=absmiddle>
<a style='cursor:pointer;' onclick="window.open('align.php?align='+document.getElementById('InsertAlign').value , 'alignwin' , 'left=0,top=0,width=300,height=200');"><font color=blue>?̵??????? ????</font></a>
</span>

<input type=hidden ID='InsertAlign' value='left'>

</TD>
<TD ALIGN=RIGHT>

<IMG SRC='./image/btn_print.gif' STYLE='cursor:hand;' onclick="getPrint();">
<IMG SRC='./image/btn_preview.gif' STYLE='cursor:hand;' onclick="PreView();">
<IFRAME NAME='PrintArea' SRC='./blank.html' WIDTH='0' HEIGHT='0' FRAMEBORDER='0'></IFRAME>

</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<INPUT TYPE=HIDDEN ID='Elay_Color' VALUE=''>
<script language=javascript src='btn.js'></script>
<script language=javascript src='edit.js'></script>
<script language=javascript>
EditForm = frames.EditArea.document;
EditForm.write("<STYLE>body,table,td,input,select,textarea{font-family : verdana,????;font-size   : 9pt;line-height : 140%;}img{border : 0;}A:link {text-decoration:none; color:black;}A:visited {text-decoration:none; color:black;}A:hover {  text-decoration:none;  color:#3E8FFC;}P{margin-top:2px;margin-bottom:2px;}</STYLE>");
EditForm.designMode = "on";
WriteFrm = frames.EditArea.document;
setTimeout("isLoading(parent.document.getElementById('editSetting1').value,parent.document.getElementById('editSetting2').value);",500);
</script>
</BODY>
</HTML>