<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";
		
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and Title like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and Content like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_bodonews where NewsId = '".$NewsId."' ".$que;
		$query2 = "select * from tb_bodonews where NewsId = '".$NewsId."' ".$que. " order by RegDate desc";

	} else {
		$query = "select count(*) from tb_bodonews where NewsId = '".$NewsId."'";
		$query2 = "select * from tb_bodonews where NewsId = '".$NewsId."' order by RegDate desc";
	}
	
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
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="inc/admin.css" type="text/css">
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
	document.frmSearch.action= "bodonews_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "bodonews_input.php";
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
		document.frmSearch.action = "bodonews_db.php";
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
<FORM name="frmSearch" method="post" action="bodonews_list.php">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?
	if ($NewsId == "A1") {
?>
	<TD align="left"><B>�����ڷ�</B></TD>
<?
	} 
?>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0">�� ��</OPTION>
		<OPTION VALUE="1">�� ��</OPTION>
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
	<TH width="10%">�� ȣ</TH>
	<TH width="15%">�� ü</TH>
	<TH width="52%">�� ��</TH>
	<TH width="20%">�����</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s = date("Y-m-d [H:i]", strtotime($obj->RegDate));
	
?>					
<TR align="center">                    

	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->SeqNo?>"></TD>
	<TD><A HREF="javascript:onView('<?echo $obj->SeqNo?>')"><?echo $obj->SeqNo?></A></TD>
	<TD align="left"><?echo $obj->Source?></TD>
	<TD align="left"><?echo $obj->Title?></TD>
	<TD><?echo $date_s?></TD>
</TR>
<?
			}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="center" colspan=5>
<?

$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&NewsId=$NewsId&idxfield=$idxfield&qry_str=$qry_str'>�Ǿ�</a>]";
	// ����������
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&NewsId=$NewsId&qry_str=$qry_str'>����".$PageScale."��</a>]";
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
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&NewsId=$NewsId&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// ���� ������
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&NewsId=$NewsId&idxfield=$idxfield&qry_str=$qry_str'>����".$PageScale."��</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&NewsId=$NewsId&idxfield=$idxfield&qry_str=$qry_str'>�ǵ�</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="NewsId" value="<?echo $NewsId?>">
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
mysql_close($connect);
?>