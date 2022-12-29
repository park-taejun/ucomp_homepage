<HTML>
<HEAD>
<meta http-equiv="MSThemeCompatible" content="Yes">
<TITLE>이미지삽입</TITLE>
<style>
html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt; position: absolute; };
</style>
</HEAD>
<BODY style="background: threedface; color: windowtext;" scroll=no>

<DIV id=divFileName style="left: 0.98em; top: 1.2168em; width: 8em; height: 1.2168em; ">이미지 URL:</DIV>
<INPUT ID=txtFileName type=text style="left: 8.54em; top: 1.0647em; width: 21.5em;height: 2.1294em; " tabIndex=10>

<DIV id=divAltText style="left: 0.98em; top: 4.1067em; width: 8em; height: 1.2168em; ">이미지 설명:</DIV>
<INPUT type=text ID=txtAltText tabIndex=15 style="left: 8.54em; top: 3.8025em; width: 21.5em; height: 2.1294em; ">

<DIV id=divAltText style="left: 0.98em; top: 6.9966em; width: 8em; height: 1.2168em; ">이미지 첨부:</DIV>
<BUTTON ID=btnImageUp style="left: 8.54em; top: 6.5403em; width: 7em; height: 2.2em;" type=button tabIndex=40 onclick="up_images();"> 이미지 첨부  </BUTTON>

<FIELDSET id=fldLayout style="left: .9em; top: 9.1em; width: 17.08em; height: 7.6em;">
<LEGEND id=lgdLayout>Layout</LEGEND>
</FIELDSET>

<FIELDSET id=fldSpacing style="left: 18.9em; top: 9.1em; width: 11em; height: 7.6em;">
<LEGEND id=lgdSpacing>Spacing</LEGEND>
</FIELDSET>

<DIV id=divAlign style="left: 1.82em; top: 11.126em; width: 4.76em; height: 1.2168em; ">Alignment :</DIV>
<SELECT size=1 ID=selAlignment tabIndex=20 style="left: 10.36em; top: 10.8218em; width: 6.72em; height: 1.2168em; ">
<OPTION value=""> Not set </OPTION>
<OPTION value=left> Left </OPTION>
<OPTION value=right> Right </OPTION>
<OPTION value=textTop> Texttop </OPTION>
<OPTION value=absMiddle> Absmiddle </OPTION>
<OPTION value=baseline SELECTED> Baseline </OPTION>
<OPTION value=absBottom> Absbottom </OPTION>
<OPTION value=bottom> Bottom </OPTION>
<OPTION value=middle> Middle </OPTION>
<OPTION value=top> Top </OPTION>
</SELECT>

<DIV id=divHoriz style="left: 19.88em; top: 11.126em; width: 4.76em; height: 1.2168em; ">Hspace :</DIV>
<INPUT ID=txtHorizontal style="left: 24.92em; top: 10.8218em; width: 4.2em; height: 2.1294em; ime-mode: disabled;" type=text size=3 maxlength=3 value="" tabIndex=25>

<DIV id=divBorder style="left: 1.82em; top: 14.0159em; width: 8.12em; height: 1.2168em; ">Border :</DIV>
<INPUT ID=txtBorder style="left: 10.36em; top: 13.5596em; width: 6.72em; height: 2.1294em; ime-mode: disabled;" type=text size=3 maxlength=3 value="" tabIndex=21>

<DIV id=divVert style="left: 19.88em; top: 14.0159em; width: 3.64em; height: 1.2168em; ">Vspace :</DIV>
<INPUT ID=txtVertical style="left: 24.92em; top: 13.5596em; width: 4.2em; height: 2.1294em; ime-mode: disabled;" type=text size=3 maxlength=3 value="" tabIndex=30>

<BUTTON ID=btnOK style="left: 31.36em; top: 1.0647em; width: 7em; height: 2.2em; " type=button tabIndex=40 onclick="reTurnTable();">확인</BUTTON>
<BUTTON ID=btnCancel style="left: 31.36em; top: 3.6504em; width: 7em; height: 2.2em; " type=reset tabIndex=45 onClick="window.close();">취소</BUTTON>

</BODY>
</HTML>


<script language=javascript>

function reTurnTable()
{
	if (document.getElementById('txtFileName').value)
	{
		var img = '<IMG SRC="' + document.getElementById('txtFileName').value+ '"'
				  + ' ALT="' + document.getElementById('txtAltText').value+ '"'
				  + ' ALIGN="' + document.getElementById('selAlignment').value+ '"'
				  + ' BORDER="' + document.getElementById('txtBorder').value+ '"'
				  + ' HSPACE="' + document.getElementById('txtHorizontal').value+ '"'
				  + ' VSPACE="' + document.getElementById('txtVertical').value+ '">\n';

		window.returnValue = img;
	}
	window.close();
}

function up_images() {

		var	url = "image_up.php?image_path=<?echo $image_path?>";
		//var attacheWin = window.open(url, "fileupload", "left=50,top=50,width=400,height=150");
		NewWindow(url, "fileupload", "400", "110", "No");
		//showModalDialog("image_up.html?board_type=<? echo $board_type; ?>", "imgUpLoad", "dialogWidth:400px;dialogHeight:150px;resizable: no; help: no; status: no; scroll: no; ");
}

function NewWindow(mypage, myname, w, h, scroll) {
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}


</script>