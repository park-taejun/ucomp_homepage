<?
	include "../admin_session_check.inc";
	include "../../dbconn.inc";

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and name like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and code like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = " and memo like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_code_parent ";
		$query2 = "select * from from tb_code_parent order by pcode_name";

	} else {
		$query = "select count(*) from tb_code_parent  ";
		$query2 = "select * from tb_code_parent order by pcode_name";
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="../inc/admin.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<script LANGUAGE="JavaScript">
<!--
function goEdit(pcode_no) {
	var frm = document.frm_search; 
	frm.pcode_no.value = pcode_no;
	frm.action = "parent_code_modify.php";
	frm.submit();
}
//-->
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<td height="30" style="padding-left:20px"><a href="#">시스템 관리</a> > <a href="#">코드관리</a> > <a href="#">대분류 코드</a> > <a href="#">리스트</a></td>
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
						<br>
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
							</tr>
							<tr height="32"> 
								<td align="right" width="100%" style="padding-right:5px" bgcolor="#F1F1F1">
									<a href="parent_code_write.php"><img src="../images/button_write.gif" border="0" align="absmiddle"></a>
								</td>
							</tr>
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
						    </tr>
								<form name="frm_search" action="parent_code_list.asp" method="post">
								<input type="hidden" name="pcode_no" value="">	
						</form>
						</table>
						    <!-- 검색조건 끝 -->
						<br>
						<table cellpadding="0" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="2" bgcolor="#000000" colspan="3"></td>
							</tr>
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td>코드<!--코드--></td>
								<td>코드명<!--코드명--></td>
								<td>메뉴<!--메뉴--></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="3"></td>
							</tr>
							<?
							  $result = mysql_query($query2);
	
  							while($row = mysql_fetch_array($result)) {				
									
									$pcode = $row[pcode];
									$pcode_no = $row[pcode_no];
									$pcode_name = $row[pcode_name];
										
							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td><?echo $pcode?></td>
								<td><a href="javascript:goEdit('<?echo $pcode_no?>');"><?echo $pcode_name?></a></td>
								<td>[<a href="detail_code_list.php?pcode=<?echo $pcode?>">세부분류코드</a>]</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="3"></td>
							</tr>
							<?
								}
							?>
							<tr>
								<td height="10"><br></td>
							</tr>
							<tr>
								<td align="center" colspan="3">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
mysql_close($connect);
?>