<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";
		
	// ���� �ʵ�
	$odr_str = "regdate desc";

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " where title like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " where faq like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_faq ".$que;
		$query2 = "select * from tb_faq ".$que. " order by ".$odr_str;

	} else {
		$query = "select count(*) from tb_faq ";
		$query2 = "select * from tb_faq order by ".$odr_str;
	}

	#echo $query2;
	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$Totalfaq = $row[0];

	$Listfaq = 10;
	$PageScale = 10;
	$TotalPage = ceil($Totalfaq / $Listfaq);		// �� ��������

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
	$First = $Listfaq * $Prev;
	$Last = $First + $Listfaq;
	if ($Last > $Totalfaq)
		$Last = $Totalfaq;

	$Scale = floor($page / ($Listfaq * $PageScale));

	# �Խù� ��ȣ
	$Numberfaq = $Totalfaq - $First;
		
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">

function form_check(){

	if (frmSearch.qry_str.value=="") {
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
	document.frmSearch.faq_id.value = id; 
	document.frmSearch.action= "faq_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "faq_input.php";
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
		document.frmSearch.faq_id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "faq_db.php";
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
				sValues += "^"+frmSearch.CheckItem.value+"^";
			}
		}
	}
	sValues  +=")";
	return sValues;
}

</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">
<FORM name="frmSearch" method="post" action="faq_list.php">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>���� ���� ����</B></TD>
	<TD align="right" width="500" align="center" bgcolor=silver>
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
	<TH width="7%">�� ȣ</TH>
	<TH width="*%">�� ��</TH>
	<TH width="20%">�����</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($Totalfaq) {

		for ($i = 0; $i < $Last; ++$i) {

			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s = date("Y-m-d", strtotime($obj->regdate));
	
?>					
<TR align="center">                    
	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->faq_id?>"></TD>
	<TD><?echo $obj->faq_id?></TD>
	<TD align="left"><A HREF="javascript:onView('<?echo $obj->faq_id?>')"><?echo $obj->title?></A></TD>
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
	<TD align="center" colspan=4>
<?

$Scale = floor(($page - 1) / $PageScale);

if ($Totalfaq > $Listfaq)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str'>�Ǿ�</a>]";
	// ����������
	if (($Totalfaq + 1) > ($Listfaq * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str'>����".$PageScale."��</a>]";
	}

	echo "&nbsp;";

	// ������ ��ȣ
	for ($vj = 0; $vj < $PageScale; $vj++)
	{
//		$ln = ($Scale * $PageScale + $vj) * $Listfaq + 1;
		$vk = $Scale * $PageScale + $vj + 1;
		if ($vk < $TotalPage + 1)
		{
			if ($vk != $page)
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// ���� ������
	if ($Totalfaq > (($Scale + 1) * $Listfaq * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str'>����".$PageScale."��</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str'>�ǵ�</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="faq_id" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
mysql_close($connect);
?>