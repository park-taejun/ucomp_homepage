<html>
<head>
<title>����(Align) ����</title>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<style>
A:link {text-decoration:none; color:black;}
A:visited {text-decoration:none; color:black;}
A:hover {  text-decoration:none;  color:#3E8FFC;}

body,table,td,input,select,textarea
{
	font-family : verdana,����;
	font-size   : 9pt;
}
img
{
	border : 0;
}
.Blink
{
	cursor : pointer;
	text-decoration : underline;
	color : blue;
}
</style>
</head>

<body leftmargin=0 topmargin=0 marginwidth=0 scroll=no scrolling=no>



<table width=100% cellspacing=0 cellpadding=5>
<tr height=33 bgcolor=dfdfdf>
<td>
&nbsp;&nbsp;
<img src='./image/dot_01.gif' align=absmiddle>
<b>��� �����Ͻðڽ��ϱ�?</b>
</td>
<td align=right>
<a href="javascript:window.close();"><img src='./image/btn_close.gif' border=0 align=absmiddle>�ݱ�</a>
</td>
</tr>
</table>

<table width=100% cellspacing=0 cellpadding=0>
<tr><td height=1 bgcolor=c0c0c0><img src='./image/blank.gif' height=1 width=1></td></tr>
</table>


<br>

<table border="0" cellpadding="0" cellspacing="0" width="280" align=center>
<tr>
	<td width="6"><img src="./image/bit_box01.gif" width="6" height="4"></td>
	<td width="268" background="./image/bit_box02.gif"></td>
	<td width="6"><img src="./image/bit_box03.gif" width="6" height="4"></td>
</tr>
<tr>
	<td background="./image/bit_box04.gif"></td>
	<td style="padding-left:10px; padding-top:10px; padding-right:10px;" valign="top" height="105" bgcolor="#ffffff">

<!---------------------------------------------------------------------------------->

	<script language=javascript>
	function align_aply()
	{
		var f = document.aform;
		var g = '';

		for (var i = 0; i < 4; i++)
		{
			if(f.align[i].checked == true)
			{
				g = f.align[i].value;
			}
		}

		opener.document.getElementById('InsertAlign').value = g;
		window.close();
	}
	</script>


	<table border="0" cellpadding="0" cellspacing="0" align="center">
	<form name=aform>
	<tr style="padding: 15 0 0 5">
	<td width="25"></td>
	<td width="16" valign="top" style="padding-top:19px"><input type="radio" name="align" value="top"<?if($align=='top'):?> checked<?endif?>></td>
	<td width="75"> <img src="./image/align_middle.gif"></td>
	<td width="16" valign="top" style="padding-top:19px"><input type="radio" name="align" value="bottom"<?if($align=='bottom'):?> checked<?endif?>></td>
	<td width="75"> <img src="./image/align_bottom.gif"></td>
	<td width="16" valign="top" style="padding-top:19px"><input type="radio" name="align" value="left"<?if($align=='left'):?> checked<?endif?>></td>
	<td width="75"> <img src="./image/align_left.gif"></td>
	<td width="16" valign="top" style="padding-top:19px"><input type="radio" name="align" value="right"<?if($align=='right'):?> checked<?endif?>></td>	
	<td width="75"> <img src="./image/align_right.gif"></td>
	</tr>
	<tr style="padding: 10 0 0 0">
	<td></td>
	<td></td>
	<td style="padding-left:2px">&nbsp;<font style="font-size:11">����</font></td>
	<td></td>
	<td style="padding-left:2px">&nbsp;<font style="font-size:11">�Ʒ�</font></td>
	<td></td>
	<td style="padding-left:2px">&nbsp;<font style="font-size:11">����</font></td>
	<td></td>
	<td nowrap><font style="font-size:11">������</font></td>
	</tr>
	</table>

<!---------------------------------------------------------------------------------->

	</td>
	<td background="./image/bit_box05.gif"></td>
</tr>
<tr>
	<td><img src="./image/bit_box06.gif" width="6" height="6"></td>
	<td background="./image/bit_box07.gif"></td>
	<td><img src="./image/bit_box08.gif" width="6" height="6"></td>
</tr>
</table>

<table width=100%>
<tr>
<td align=center height=40>
<img src='./image/btn_ok.gif' border=0 style='cursor:pointer;' onclick="align_aply()">
</td>
</tr>
</form>
</table>

</body>
</html>