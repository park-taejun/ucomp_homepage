//�ؽ�Ʈ��� üũ
function CheckTextMode()
{
	var CodingType = document.getElementById('Elay_CodingType').value;
	if (CodingType == 'TEXT')
	{
		alert('\n�ؽ�Ʈ������� ������ ����Ͻ� �� �����ϴ�.          \n');
		document.getElementById('Elay_Content').focus();
		return true;
	}
}
//�Ϲ��±�����
function TagEdit(tag)
{
	if(CheckTextMode()) return false;
	frames.EditArea.focus();
	SelectedObj = WriteFrm.selection.createRange();
	SelectedObj.execCommand(tag);
	frames.EditArea.focus();
}

//��Ʈ�±�����
function FontChange(type , value)
{
	if(CheckTextMode()) return false;
	frames.EditArea.focus();
	SelectedObj = WriteFrm.selection.createRange();
	SelectedObj.execCommand(type , '' , value);
	frames.EditArea.focus();
}

//��ũ
function getLink()
{
	if(CheckTextMode()) return false;
	frames.EditArea.focus();
	SelectedObj = WriteFrm.selection.createRange();
	SelectedObj.execCommand('CreateLink',1,'');
	frames.EditArea.focus();
}

// �ڵ�Ÿ��
function getCodingType(type)
{
	var TagChec = document.getElementById('Elay_Srcview');
	var AreaSrc = frames.EditArea;
	var AreaObj = document.getElementById('Elay_EditArea');
	var TextObj = document.getElementById('Elay_Content');

	if (type.value == 'HTML')
	{
		if (!confirm('\n�������׸��� ��ȯ�ϸ� �ؽ�Ʈ���� �������·� ��ȯ�˴ϴ�.     \n\n��ȯ�Ͻðڽ��ϱ�?\n'))
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
		if (!confirm('\n�ؽ�Ʈ���� ��ȯ�ϸ� �������� ȿ���� ������ϴ�.     \n\n��ȯ�Ͻðڽ��ϱ�?\n'))
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

//�ҽ�����
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
		alert('\n�ؽ�Ʈ������� ������ ����Ͻ� �� �����ϴ�.          \n');
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
		AreaSrc.document.body.innerHTML = "<STYLE>body,table,td,input,select,textarea{font-family : verdana,����;font-size   : 9pt;line-height : 140%;}img{border : 0;}A:link {text-decoration:none; color:black;}A:visited {text-decoration:none; color:black;}A:hover {  text-decoration:none;  color:#3E8FFC;}P{margin-top:2px;margin-bottom:2px;}</STYLE>";
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

//������
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

//���̺����
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

//�̹�������
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

//�̸�����
function PreView()
{
	window.open('./preview.html','prewin','width=700,height=550,scrollbars=yes');
}

//�μ�
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
//HTML,TEXT ����
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
//�ε�üũ
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