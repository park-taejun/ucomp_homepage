<?
	include "admin_session_check.inc";
	include "../dbconn.inc";

	$sYY = date(Y);
			
	if ($sYYYY == "") {
		$sYYYY = $sYY;
	} 

	
	
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and Title like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and Ref_url like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_call_paper where code_id = '".$code_id."' and DuDate like '".$sYYYY."%'  ".$que;
		$query2 = "select * from tb_call_paper where code_id = '".$code_id."' and DuDate like '".$sYYYY."%' ".$que. " order by DuDate desc";

	} else {
		$query = "select count(*) from tb_call_paper where code_id = '".$code_id."' and DuDate like '".$sYYYY."%'";
		$query2 = "select * from tb_call_paper where code_id = '".$code_id."' and DuDate like '".$sYYYY."%' order by DuDate desc";
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
<link rel="stylesheet" href="inc/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
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
	document.frmSearch.SeqNo.value = id; 
	document.frmSearch.action= "paper_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "paper_input.php";
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
		document.frmSearch.SeqNo.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "paper_db.php";
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

function setBshow(iBshow, num) {
	document.frmSearch.bshow.value = iBshow;
	document.frmSearch.mode.value = "bshow";
	document.frmSearch.SeqNo.value = num; 
	document.frmSearch.action= "paper_db.php";
	document.frmSearch.submit();
}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="init();">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->

<?	$_mode = "list"; ?>
<?	include "./inc/paper_title.inc"; ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
	<tr>
		<td height="30" style="padding-left:20px"><a href="#">BBS ����</a> > <a href="#"><?echo $_menu_str?></a> > <a href="#"><?echo $_mode_str?></a></td>
	</tr>
	<tr>
		<td height="1" bgcolor="#CCCCCC"></td>
	</tr>
</table>

<!-- Site Map Section End -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
 				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30">
						<img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Form --->					
						<FORM name="frmSearch" method="post" action="paper_list.php">
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td colspan="3"></td>
							</tr>
							<tr height="32"> 
								<td width="70" style="padding : 0,0,0,10"  bgcolor="#F1F1F1">									
									�������
								</td>
								<td  bgcolor="#F1F1F1">
									<select name="sYYYY">
										<? 
											$start_year = $sYYYY - 5;
											$end_year = $sYYYY + 5;

											for ($i = $start_year ; $i <= $end_year ; $i++) {
												if ($i == $sYYYY ) {
													echo "<option value='$i' selected>$i</option>";
												} else {	
													echo "<option value='$i'>$i</option>";
												}
											}
										?>
									</select>
								</td>
								<td align="right"  style="padding-left:5px" bgcolor="#F1F1F1">
									<SELECT NAME="idxfield">
										<OPTION VALUE="0">�� ��</OPTION>
										<OPTION VALUE="1">URL</OPTION>
									</SELECT>
									<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
									<a href="javascript:document.frmSearch.submit();"><img src="images/button_serch.gif" border="0" align="absmiddle"></a>
									<a href="javascript:goIn();"><img src="images/button_write.gif" border="0" align="absmiddle"></a>
									<a href="javascript:goDel();"><img src="images/button_delete.gif" border="0" align="absmiddle"></a>
								</td>
							</tr>
							<tr height="2" bgcolor="#CCCCCC"> 
								<td colspan="3"></td>
						    </tr>
						</table>
						    <!-- �˻����� �� -->
						<br>
						<table cellpadding="0" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="2" bgcolor="#000000" colspan="7"></td>
							</tr>
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td width="3%">&nbsp;</td>
								<td width="10%">��ȣ</td>
								<td width="27%">����</td>
								<td width="20%">��¥</td>
								<td width="20%">�������</td>
								<td width="10%">��ũ</td>
								<td width="10%">���̱�</td>
							</tr>
							<tr>
							<?
								$result2 = mysql_query($query2);
								
								if ($TotalArticle) {

									for ($i = 0; $i < $Last; ++$i) {
										mysql_data_seek($result2,$i);
										$obj = mysql_fetch_object($result2);

										if ($i >= $First) {
				
											$Sdate_s = date("Y-m-d", strtotime($obj->StartDate));
											$Edate_s = date("Y-m-d", strtotime($obj->EndDate));
											$Ddate_s = date("Y-m-d", strtotime($obj->DuDate));
	
							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->SeqNo?>"></td>
								<td><?echo $obj->SeqNo?></td>
								<td align="left" style="padding: 0 0 0 10"><A HREF="javascript:onView('<?echo $obj->SeqNo?>')"><?echo $obj->Title?></A></td>
								<td><?echo $Sdate_s?> ~ <?echo $Edate_s?></td>
								<td><?echo $Ddate_s?></td>
								<td><a href="<?echo $obj->Ref_url?>" target="_new">GO</a></td>
								<td>
							<?
											if ($obj->bshow == "") {
												$t_bshow = "0";
											} else {
												$t_bshow = $obj->bshow; 
											}
	
											if ($t_bshow == "0") {
												echo "<a href='javascript:setBshow(1,$obj->SeqNo);'><img src='images/ico_show0.gif' border=0></a>";
											} else {
												echo "<a href='javascript:setBshow(0,$obj->SeqNo);'><img src='images/ico_show1.gif' border=0></a>";
											}
							?>	
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?
										}
									}
								}
							?>
							<tr>
								<td align="center" colspan="7">
								
							<?
								$Scale = floor(($page - 1) / $PageScale);

								if ($TotalArticle > $ListArticle) {
									if ($page != 1)
										echo "[<a href='".$PHP_SELF."?page=1&code_id=$code_id&sYYYY=$sYYYY&sidxfield=$idxfield&qry_str=$qry_str'>�Ǿ�</a>]";
									
									// ����������
									if (($TotalArticle + 1) > ($ListArticle * $PageScale)) {
										$PrevPage = ($Scale - 1) * $PageScale;
										if ($PrevPage >= 0)
											echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&code_id=$code_id&sYYYY=$sYYYY&qry_str=$qry_str'>����".$PageScale."��</a>]";
									}
									echo "&nbsp;";
									// ������ ��ȣ
									for ($vj = 0; $vj < $PageScale; $vj++) {
									//		$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
										$vk = $Scale * $PageScale + $vj + 1;
										if ($vk < $TotalPage + 1) {
											if ($vk != $page)
												echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&code_id=$code_id&sYYYY=$sYYYY&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
											else
												echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
										}
									}
										
									echo "&nbsp;";
									// ���� ������
									if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale)) {
										$NextPage = ($Scale + 1) * $PageScale + 1;
										echo "[<a href='".$PHP_SELF."?page=".$NextPage."&code_id=$code_id&sYYYY=$sYYYY&idxfield=$idxfield&qry_str=$qry_str'>����".$PageScale."��</a>]";
									}
										
									if ($page != $TotalPage)
										echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&code_id=$code_id&sYYYY=$sYYYY&idxfield=$idxfield&qry_str=$qry_str'>�ǵ�</a>]&nbsp;&nbsp;";
								} else 
									echo "&nbsp;[1]&nbsp;";	
							?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="code_id" value="<?echo $code_id?>">
<input type="hidden" name="SeqNo" value="">
<input type="hidden" name="bshow" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
	mysql_close($connect);
?>
