//텍스트모드 체크
function CheckTextMode()
{
	var CodingType = document.getElementById('Elay_CodingType').value;
	if (CodingType == 'TEXT')
	{
		alert('\n텍스트편집모드 에서는 사용하실 수 없습니다.          \n');
		document.getElementById('Elay_Content').focus();
		return true;
	}
}
//일반태그적용
function TagEdit(tag)
{
	if(CheckTextMode()) return false;
	frames.EditArea.focus();
	SelectedObj = WriteFrm.selection.createRange();
	SelectedObj.execCommand(tag);
	frames.EditArea.focus();
}

//폰트태그적용
function FontChange(type , value)
{
	if(CheckTextMode()) return false;
	frames.EditArea.focus();
	SelectedObj = WriteFrm.selection.createRange();
	SelectedObj.execCommand(type , '' , value);
	frames.EditArea.focus();
}

//링크
function getLink()
{
	if(CheckTextMode()) return false;
	frames.EditArea.focus();
	SelectedObj = WriteFrm.selection.createRange();
	SelectedObj.execCommand('CreateLink',1,'');
	frames.EditArea.focus();
}

// 코딩타입
function getCodingType(type)
{
	var TagChec = document.getElementById('Elay_Srcview');
	var AreaSrc = frames.EditArea;
	var AreaObj = document.getElementById('Elay_EditArea');
	var TextObj = document.getElementById('Elay_Content');

	if (type.value == 'HTML')
	{
		if (!confirm('\n위지위그모드로 전환하면 텍스트편집 이전상태로 전환됩니다.     \n\n전환하시겠습니까?\n'))
		{
			type.value = "TEXT";
			return false;
		}
		TextObj.innerText = "";
		AreaObj.style.display = "block";
		TextObj.style.display = "none";
		if(parent.document.getElementById('useup').value!='0' || parent.document.getElementById('uselk').value!='0') 
		{
		document.getElementById('media_align').style.display = 'inline';
		}
		AreaSrc.focus();
	}
	else {
		if (!confirm('\n텍스트모드로 전환하면 위지위그 효과가 사라집니다.     \n\n전환하시겠습니까?\n'))
		{
			type.value = "HTML";
			return false;
		}
		if (TagChec.checked)
		{
			AreaSrc.document.body.innerHTML = TextObj.innerText;
			TagChec.checked = false;
		}
		
		TextObj.innerText = AreaSrc.document.body.innerHTML;
		AreaObj.style.display = "none";
		TextObj.style.display = "block";
		if(parent.document.getElementById('useup').value!='0' || parent.document.getElementById('uselk').value!='0') 
		{
		document.getElementById('media_align').style.display = 'none';
		}
		TextObj.focus();
	}
}

//소스보기
function SrcView(flag)
{
	var CodingType = document.getElementById('Elay_CodingType').value;
	
	if (flag == false)
	{
		document.getElementById('Elay_Srcview').checked = !document.getElementById('Elay_Srcview').checked;
		flag = document.getElementById('Elay_Srcview');
	}

	if (CodingType == 'TEXT')
	{
		alert('\n텍스트편집모드 에서는 사용하실 수 없습니다.          \n');
		flag.checked = false;
		return false;
	}
	
	var AreaSrc = frames.EditArea;
	var AreaObj = document.getElementById('Elay_EditArea');
	var TextObj = document.getElementById('Elay_Content');


	if (flag.checked)
	{	
		flag.checked = true;
		TextObj.innerText = AreaSrc.document.body.innerHTML;
		AreaSrc.document.body.innerHTML = "<STYLE>body,table,td,input,select,textarea{font-family : verdana,굴림;font-size   : 9pt;line-height : 140%;}img{border : 0;}A:link {text-decoration:none; color:black;}A:visited {text-decoration:none; color:black;}A:hover {  text-decoration:none;  color:#3E8FFC;}P{margin-top:2px;margin-bottom:2px;}</STYLE>";
		AreaObj.style.display = "none";
		TextObj.style.display = "block";
		TextObj.focus();
	}
	else {
		flag.checked = false;
		if (TextObj.innerText) AreaSrc.document.body.innerHTML = TextObj.innerText;
		TextObj.innerText = "";
		AreaObj.style.display = "block";
		TextObj.style.display = "none";
		AreaSrc.focus();
	}
}

//색상선택
function ColorChartView(color_type)
{
	if(CheckTextMode()) return false;
	SelectedObj = WriteFrm.selection.createRange();
    var color = showModalDialog("./color.html",0,"dialogHeight=192px;dialogWidth=225px; scrollbars=no; status=0; help=0");
    if (color != null)
    {
        SelectedObj.execCommand(color_type, '', color);
    }
	frames.EditArea.focus();
}

//테이블삽입
function getTableSet()
{
	if(CheckTextMode()) return false;
	frames.EditArea.focus();
    var table = showModalDialog("./table.html",0,"dialogheight=190px; dialogwidth=430px;scrollbars=no;status=0;help=0");
    if (table != null) 
    {
		var sel = WriteFrm.selection.createRange();
		sel.pasteHTML(table);
		sel.select();
    }
	frames.EditArea.focus();
}

//이미지삽입
//function getImageInsert()
//{
//	if(CheckTextMode()) return false;
//	frames.EditArea.focus();
//    var table = showModalDialog("./image.html",0,"dialogheight=295px; dialogwidth=430px;scrollbars=no;status=0;help=0");
//    if (table != null) 
//    {
//		var sel = WriteFrm.selection.createRange();
//		sel.pasteHTML(table);
//		sel.select();
//    }
//	frames.EditArea.focus();
//}

//미리보기
function PreView()
{
	window.open('./preview.html','prewin','width=700,height=550,scrollbars=yes');
}

//인쇄
function getPrint()
{
	var CodingType = document.getElementById('Elay_CodingType').value;
	if (CodingType == 'TEXT')
	{
		var ReplaceText = document.getElementById('Elay_Content').value.replace('<', '&lt;').replace('>' , '&gt;').replace('"' , '&quot;');
		frames.PrintArea.document.body.innerHTML = "";		
		frames.PrintArea.document.body.innerHTML = "<PRE>" + ReplaceText + "</PRE>";
		frames.PrintArea.print();
	}
	else {
		var AreaSrc = frames.EditArea;
		AreaSrc.print();
	}
}
//HTML,TEXT 셋팅
function getAreaSet(otype,type)
{
	var ef = frames;
	var AreaSrc = ef.EditArea;
	var AreaObj = ef.document.getElementById('Elay_EditArea');
	var TextObj = ef.document.getElementById('Elay_Content');
	var SelType = ef.document.getElementById('Elay_CodingType');

	if (otype == 'TEXT' && type != 'HTML')
	{
		ef.document.getElementById('Elay_Menu_01').style.display = "none";
		ef.document.getElementById('Elay_Menu_02').style.display = "none";
	}
	else {
		ef.document.getElementById('Elay_Menu_01').style.display = "block";
		ef.document.getElementById('Elay_Menu_02').style.display = "block";
	}

	if (type == 'TEXT')
	{
		SelType.value = 'TEXT';
		TextObj.innerText = parent.opener.document.frm.eng_data.value;
		AreaSrc.document.body.innerHTML = "";
		AreaObj.style.display = "none";
		TextObj.style.display = "block";
		if(parent.document.getElementById('useup').value!='0' || parent.document.getElementById('uselk').value!='0') 
		{
			document.getElementById('media_align').style.display = 'none';
		}
		TextObj.focus();
	}
	else {
		SelType.value = 'HTML';
		TextObj.innerText = "";
		AreaSrc.document.body.innerHTML = parent.opener.document.frm.eng_data.value;;
		AreaObj.style.display = "block";
		TextObj.style.display = "none";
		if(parent.document.getElementById('useup').value!='0' || parent.document.getElementById('uselk').value!='0') 
		{
		document.getElementById('media_align').style.display = 'inline';
		}
		//AreaSrc.focus();
	}
}
//로딩체크
function isLoading(set1 , set2)
{
	if(document.getElementById('EditArea').readyState == "complete")
	{
		document.getElementById('LodingLay').style.display = "none";
		getAreaSet(set1,set2);
	}
	else {
		setTimeout("isLoading()",500);
	}
}