<html>
<head>
<link href="http://www.golfchosun.com/images/css/style_index.css" rel="stylesheet" type="text/css">
</head>
<script language="javascript">
<!--
function checkForm(form) {
	if(document.all.Iwait.value=="1"){
		alert("������Դϴ� ��ø� ��ٷ��ּ���...");
		return false;
	}
	
	if (!checkVar(form.Iwriter,'�۾��̸� �Է��ϼ���.')) return false;
	if (!checkVar(form.Ititle,'������ �Է��ϼ���.')) return false;

	document.all.Icontent.value=document.all.yourFieldNameHere.value;
	if (!checkVar(form.Icontent,'������ �ۼ� ���ϼ̽��ϴ�. ������ �ۼ��� �ֽʽÿ�.')){
		editor_insertHTML('yourFieldNameHere','');	//��Ŀ�� �̵��� ���� ���.
		return false;
	}


	// ���� Ȯ���� äũ ����.
	var tFile1=document.all.Ifile1.value;
	var tFile2=document.all.Ifile2.value;
	
	if(tFile1!=""){
		tFile1=tFile1.toLowerCase();
		tFile1=tFile1.split(".");
		if(tFile1.length!=2){
			alert('�ùٸ� ������ �����Ͽ� �ֽñ� �ٶ��ϴ�.');
			return false;
		}else{
	
			if(tFile1[1]=='jpg'||tFile1[1]=='gif'||tFile1[1]=='bmp'||tFile1[1]=='doc'||tFile1[1]=='xls'||tFile1[1]=='hwp'||tFile1[1]=='zip'||tFile1[1]=='txt'){
			}else{
				alert('Ȯ���ڰ� jpg,gif,bmp,doc,xls,hwp,zip,txt �� ���ϸ� ����Ͻ� �� �ֽ��ϴ�.');
				return false;
			}
		}
	}

	if(tFile2!=""){
		tFile2=tFile2.toLowerCase();
		tFile2=tFile2.split(".");
		if(tFile2.length!=2){
			alert('�ùٸ� ������ �����Ͽ� �ֽñ� �ٶ��ϴ�.');
			return false;
		}else{
	
			if(tFile2[1]=='jpg'||tFile2[1]=='gif'||tFile2[1]=='bmp'||tFile2[1]=='doc'||tFile2[1]=='xls'||tFile2[1]=='hwp'||tFile2[1]=='zip'||tFile2[1]=='txt'){
			}else{
				alert('Ȯ���ڰ� jpg,gif,bmp,doc,xls,hwp,zip,txt �� ���ϸ� ����Ͻ� �� �ֽ��ϴ�.');
				return false;
			}
		}
	}
	// ���� Ȯ���� äũ ��.

	document.all.Iwait.value="1";
	form.submit();
}
function goDelete(){

	if(confirm("���� ���� �����Ͻðڽ��ϱ�?")){

		inputform.mode.value="delete";
		inputform.submit();
	}
	
}
//-->
</script>
<body leftmargin="0" topmargin="0">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
   <td valign="top" align='right' width="993" >
		
			<input type="hidden" name="Iwait" value="0">
			<form action="input_save.html" method="POST" enctype="multipart/form-data" name="inputform">
			<table width="95%" border="0" cellpadding=0 cellspacing=0 style="color:666666">
				<tr>
					<td>
						<input type="hidden" name="Iref" value="">
						<input type="hidden" name="Ireftemp" value="">
						<input type="hidden" name="Irestep" value="">
						<input type="hidden" name="Irelevel" value="">
						<input type="hidden" name="Ipos" value="">
						<input type="hidden" name="Iidx" value="">
						<input type="hidden" name="IGotoPage" value="">
						<input type="hidden" name="mode" value="">
						<input type="hidden" name="board" value="TN_CLUB_loveball">
						<input type="hidden" name="Iboard" value="TN_CLUB_loveball">
						<input type="hidden" name="m" value="">
						<input type="hidden" name="subval" value="">
						<input type="hidden" name="Icategori" value="12">
						<input type="hidden" name="Icategori_desc" value="">
						<input type="hidden" name="Ipasswd">
						<input type="hidden" name="Iprewriter" value="">
						<input type="hidden" name="Ipresms" value="">

						<table width="95%" border="0" cellpadding="0" cellspacing="0">
							<tr> 
								<td colspan=4 align="center"  bgcolor="#FFFFFF"  style="padding:10 0 10 0;border-left:1 solid CCCCCC;border-right:1 solid CCCCCC;">
									<table width="" border="0" cellpadding="0" cellspacing="0">
										<tr> 
											<td align="center"><br> 
											<!-- DHTML Input stasrt-->


<script language="Javascript1.2"><!-- // load htmlarea
	_editor_url = "dhtml/";                     // URL to htmlarea files
	var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
	
	if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
	if (win_ie_ver >= 5.5) {
  
		document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
		document.write(' language="Javascript1.2"></scr' + 'ipt>');  
	
	} else { 
		
		document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); 
	
	}
// -->
</script>


												<input type=hidden name='for_editor_board_type' value='TN_CLUB_loveball'>
												<textarea name="yourFieldNameHere" style="width:620; height:400">
												</textarea>
												<br>
												<span style="display:none;">
												<a href="javascript:editor_insertHTML('yourFieldNameHere','<font style=\'background-color: yellow\'>','</font>',1);">
												Highlight selected text</a> - <a href="javascript:editor_insertHTML('yourFieldNameHere',':)');">
												Insert Smiley</a> <span onclick="document.all.Icontent.value=editor_getHTML('yourFieldNameHere');">
												getHTML</span> <a href="javascript:editor_setHTML('yourFieldNameHere','<b>Hello World</b>!!');">setHTML</a> 
												</span>
												<script language="javascript1.2">
													editor_generate('yourFieldNameHere');
												</script>
 
<script language="javascript1.2">
	var config = new Object();    // create new config object

	config.width = "90%";
	config.height = "200px";
	config.bodyStyle = 'background-color: white; font-family: "Verdana"; font-size: x-small;';
	config.debug = 0;

	// NOTE:  You can remove any of these blocks and use the default config!

	config.toolbar = [
	['fontname'],
	['fontsize'],
	['fontstyle'],
	['linebreak'],
	['bold','italic','underline','separator'],
	['strikethrough','subscript','superscript','separator'],
	['justifyleft','justifycenter','justifyright','separator'],
	['OrderedList','UnOrderedList','Outdent','Indent','separator'],
	['forecolor','backcolor','separator'],
	['HorizontalRule','Createlink','InsertImage','htmlmode','separator'],
	['about','help','popupeditor'],
	];

	config.fontnames = {
	"Arial":           "arial, helvetica, sans-serif",
	"Courier New":     "courier new, courier, mono",
	"Georgia":         "Georgia, Times New Roman, Times, Serif",
	"Tahoma":          "Tahoma, Arial, Helvetica, sans-serif",
	"Times New Roman": "times new roman, times, serif",
	"Verdana":         "Verdana, Arial, Helvetica, sans-serif",
	"impact":          "impact",
	"WingDings":       "WingDings"
	};

	config.fontsizes = {
		"1 (8 pt)":  "1",
		"2 (10 pt)": "2",
		"3 (12 pt)": "3",
		"4 (14 pt)": "4",
		"5 (18 pt)": "5",
		"6 (24 pt)": "6",
		"7 (36 pt)": "7"
	};

	//config.stylesheet = "http://www.domain.com/sample.css";

	config.fontstyles = [   // make sure classNames are defined in the page the content is being display as well in or they won't work!
		{ name: "headline",     className: "headline",  classStyle: "font-family: arial black, arial; font-size: 28px; letter-spacing: -2px;" },
		{ name: "arial red",    className: "headline2", classStyle: "font-family: arial black, arial; font-size: 12px; letter-spacing: -2px; color:red" },
		{ name: "verdana blue", className: "headline4", classStyle: "font-family: verdana; font-size: 18px; letter-spacing: -2px; color:blue" }

	// leave classStyle blank if it's defined in config.stylesheet (above), like this:
	//  { name: "verdana blue", className: "headline4", classStyle: "" }  
	];
</script>
<!-- DHTML Input end-->              <br> </td>
						</tr>
							</table>
							<textarea  rows="13" name="Icontent" style="display:none;background:;color:#666666;border:1 solid D4D4D4;width:570"></textarea>
						</td>
					</tr>
				</table>
				<table width="95%" border="0" cellpadding="0" cellspacing="0">
					<tr> 
						<td	height="50" colspan="6" align="center"> <img src="/Mclub/board/style/default/images/btn_regist.gif" alt="���" style="cursor:hand" border="0" onclick="javascript:if (checkForm(document.inputform)) alert('OK');">&nbsp;&nbsp;&nbsp;<a href="javascript:if (confirm('����Ͻðڽ��ϱ�?')) history.go(-1);"><img src="/Mclub/board/style/default/images/btn_cancel.gif" alt="���" border="0"></a>&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
				</table>
			</form>
		</td>
  </tr>
</table>    <!--bottom ��������-->