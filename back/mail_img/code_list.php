<?
	include "admin_session_check.inc";
	include "../dbconn.inc";

	$parent = $parent;
		
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and name like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and code like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = " and memo like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_code where parent = '".$parent."' ".$que;
		$query2 = "select * from tb_code where parent = '".$parent."' ".$que. " order by code";

	} else {
		$query = "select count(*) from tb_code where parent = '".$parent."'";
		$query2 = "select * from tb_code where parent = '".$parent."' order by code";
	}

	#echo $query."<br>";
	#echo $query2;

	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 10;
	$PageScale = 10;
	$TotalPage = ceil($TotalArticle / $ListArticle);		// �� ��������

	if (!$TotalPage)
		$TotalPage = 0;

	if (empty($page))
		$page = 1;


	# ���� ������
	$Prev = $page - 1;
	if ($Prev < 0)
		$Prev = 0;

	# ���� ������
	$Next = $page + 1;
	if ($Next > $TotalPage)
		$Next = $TotalPage;

	# ���� ������ ���� ���� ���
	$First = $ListArticle * $Prev;
	$Last = $First + $ListArticle;
	if ($Last > $TotalArticle)
		$Last = $TotalArticle;

	$Scale = floor($page / ($ListArticle * $PageScale));

	# �Խù� ��ȣ
	$NumberArticle = $TotalArticle - $First;
	
?>
<html>
<head>
<title>:::::������ ������ �ý���:::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</STYLE>
<script language="javascript">

function form_check(){

	if (frmSearch.query.value=="") {
		alert("�˻�� ��������");
		return false;
	} else {
		frmSearch.submit();
	}

}

function init(){
<?	if (!empty($qry_str)) {  ?>
		document.frmSearch.qry_str.value="<?echo $qry_str ?>";
		document.frmSearch.idxfield.options[<?echo $idxfield ?>].selected = true;
<?	} ?>

}

function onView(id) {
	document.frmSearch.id.value = id; 
	document.frmSearch.action= "code_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "code_input.php";
	document.frmSearch.submit();
}

function goDel() {

	var check_count = 0;
	var total = document.frmSearch.length;
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.elements[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("�����Ͻ� �ڷḦ ������ �ֽʽÿ�.");
	    return;
	}
	
	bDelOK = confirm("���� ���� �Ͻðڽ��ϱ�?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "code_db.php";
		document.frmSearch.submit();
	}
	else {
		return;
	}

}

function getIds(){
	var sValues = "(";
	if(frmSearch.CheckItem != null){
		if(frmSearch.CheckItem.length != null){
			for(i=0; i<frmSearch.CheckItem.length; i++){
				if(frmSearch.CheckItem[i].checked == true){
					if(sValues != "("){
						sValues += ",";
					}
					sValues +="^"+frmSearch.CheckItem[i].value+"^";
				}
			}
		}else{
			if(frmSearch.CheckItem.checked == true){
				sValues += frmSearch.CheckItem.value;
			}
		}
	}
	sValues  +=")";
	return sValues;
}

</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">
<br>
<br>
<FORM name="frmSearch" method="post" action="code_list.php">
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table width='99%' bgcolor="#EEEEEE">
			<tr align="center">
				<td align="left">
					<b><a href="code_list.php?parent=JOB">����</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=ML">ȸ�����</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=SZ">��ǥ��</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=BSZ">����</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=AW">������</a></b>&nbsp;&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<br>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?if ($parent == 'JOB') {?>
	<TD align="left"><B>����</B></TD>
<?} else if ($parent == 'ML') {?>
	<TD align="left"><B>ȸ�����</B></TD>
<?} else if ($parent == 'SZ') {?>
	<TD align="left"><B>��ǥ��</B></TD>
<?} else if ($parent == 'BSZ') {?>
	<TD align="left"><B>����</B></TD>
<?} else if ($parent == 'AW') {?>
	<TD align="left"><B>������</B></TD>
<?} else if ($parent == 'LT') {?>
	<TD align="left"><B>Length</B></TD>
<?} else if ($parent == 'CR') {?>
	<TD align="left"><B>Colour</B></TD>
<?}?>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0">�� ��</OPTION>
		<OPTION VALUE="1">�� ��</OPTION>
		<OPTION VALUE="2">�� ��</OPTION>
	</SELECT>
	<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
	<INPUT TYPE="submit" VALUE="�˻�">
	<INPUT TYPE="button" VALUE="���" onClick="goIn();">
	<INPUT TYPE="button" VALUE="����" onClick="goDel();">	
	</TD>
</TR>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="3%">&nbsp;</TH> 
	<TH width="20%">�� ��</TH>
	<TH width="20%">�� ��</TH>
	<TH width="57%">�� ��</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
	
?>					
<TR align="center">                    

	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->id?>"></TD>
	<TD align="left"><A HREF="javascript:onView('<?echo $obj->id?>')"><?echo $obj->code?></A></TD>
	<TD align="left"><?echo $obj->name?></TD>
	<TD align="left"><?echo $obj->memo?></TD>
</TR>
<?
			}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="center" colspan=4>
<?
$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>�Ǿ�</a>]";
	// ����������
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>����".$PageScale."��</a>]";
	}

	echo "&nbsp;";

	// ������ ��ȣ
	for ($vj = 0; $vj < $PageScale; $vj++)
	{
//		$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
		$vk = $Scale * $PageScale + $vj + 1;
		if ($vk < $TotalPage + 1)
		{
			if ($vk != $page)
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// ���� ������
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>����".$PageScale."��</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>�ǵ�</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="parent" value="<?echo $parent?>">
</form>
</body>
</html>
<?
mysql_close($connect);
?>