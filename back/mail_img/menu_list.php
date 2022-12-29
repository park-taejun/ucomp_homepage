<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_group_list.php
	// 	Description : ������ �׷� ����Ʈ ȭ��
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";
	
	$query = "select count(*) from tb_admin_menu m, tb_big_menu b where m.big_menu = b.big_menu";
	$query2 = "select m.menu_id, b.big_menu_name, m.small_menu, m.menu_url, m.big_menu from tb_admin_menu m, tb_big_menu b where m.big_menu = b.big_menu order by b.big_menu";

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

function onView(id) {
	document.frmSearch.menu_id.value = id; 
	document.frmSearch.action= "menu_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "menu_input.php";
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
		alert("�����Ͻ� �޴��� ������ �ֽʽÿ�.");
	    return;
	}
	
	bDelOK = confirm("���� ���� �Ͻðڽ��ϱ�?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.menu_id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "menu_db.php";
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<td height="30" style="padding-left:20px"><a href="#">�����ڱ��� ����</a> > <a href="#">�޴� ����</a> > <a href="#">����Ʈ</a></td>
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
						<FORM name="frmSearch" method="post" action="menu_list.php">
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
							</tr>
							<tr height="32"> 
								<td align="right" width="100%" style="padding-left:5px" bgcolor="#F1F1F1">
									<a href="javascript:goIn();"><img src="images/button_write.gif" border="0" align="absmiddle"></a>
									<a href="javascript:goDel();"><img src="images/button_delete.gif" border="0" align="absmiddle"></a>&nbsp;
								</td>
							</tr>
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
						    </tr>
						</table>
						    <!-- �˻����� �� -->
						<br>
						<table cellpadding="0" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="2" bgcolor="#000000" colspan="4"></td>
							</tr>
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td width="3%">&nbsp;</td>
								<td width="17%">�޴� �׷�</td>
								<td width="30%">�޴���</td>
								<td width="50%">�޴� ���</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="4"></td>
							</tr>
							<?
								$result2 = mysql_query($query2);

								if ($TotalArticle) {
									for ($i = 0; $i < $Last; ++$i) {
										mysql_data_seek($result2,$i);
										$obj = mysql_fetch_object($result2);

										if ($i >= $First) {
				
											$date_s = date("Y-m-d [H:i]", strtotime($obj->regDate));
	
							?>
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->menu_id?>"></td>
								<td align="left" style="padding: 0 0 0 10"><?echo $obj->big_menu_name?></td>
								<td align="left" style="padding: 0 0 0 10"><A HREF="javascript:onView('<?echo $obj->menu_id?>')"><?echo $obj->small_menu?></A></td>
								<td align="left" style="padding: 0 0 0 10"><?echo $obj->menu_url?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>							
							<?
										}
									}
								}
							?>
							<tr>
								<td align="center" colspan="5">
							<?
								$Scale = floor(($page - 1) / $PageScale);

								if ($TotalArticle > $ListArticle) {

									if ($page != 1)
										echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str'>�Ǿ�</a>]";
										// ����������
										if (($TotalArticle + 1) > ($ListArticle * $PageScale)) {
											$PrevPage = ($Scale - 1) * $PageScale;

											if ($PrevPage >= 0)
												echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str'>����".$PageScale."��</a>]";
										}

										echo "&nbsp;";

										// ������ ��ȣ
										for ($vj = 0; $vj < $PageScale; $vj++) {
										//	$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
											$vk = $Scale * $PageScale + $vj + 1;
											if ($vk < $TotalPage + 1) {
												if ($vk != $page)
													echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
												else
													echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
											}
										}

										echo "&nbsp;";
										// ���� ������
										if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale)) {
											$NextPage = ($Scale + 1) * $PageScale + 1;
											echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str'>����".$PageScale."��</a>]";
										}

										if ($page != $TotalPage)
											echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str'>�ǵ�</a>]&nbsp;&nbsp;";
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
<input type="hidden" name="menu_id" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
	mysql_close($connect);
?>