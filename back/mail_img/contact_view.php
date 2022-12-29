<?
	include "admin_session_check.inc";
	include "../dbconn.inc";
	include "./inc/global_init.inc";
	
	$query = "select * from tb_contact where con_no = '".$id."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$con_no = $list[con_no];
	$contents = $list[contents];
	$com_name = $list[com_name];
	$com_site = $list[com_site];
	$name = $list[name];
	$phone1 = $list[phone1];
	$phone2 = $list[phone2];
	$phone3 = $list[phone3];
	$hphone1 = $list[hphone1];
	$hphone2 = $list[hphone2];
	$hphone3 = $list[hphone3];
	$email = $list[email];
	$addfile = $list[addfile];
	
?>		
<HTML>
<HEAD>
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
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="contact_list.php";
		document.frm.submit();
	}

//-->
</SCRIPT>
</HEAD>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<td height="30" style="padding-left:20px"><a href="#">사이트 관리</a> > <a href="#">Contact us</a> > <a href="#">조회</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<form name='frm' method='post' action='contact_db.php'>
				<INPUT type="hidden" name="id" value="<?echo $con_no?>">
				<input type='hidden' name='page' value="<?echo $page?>">
				<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
				<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td>
									* Contact us<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->	
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">제안내용</td>
								<td style="padding-left:10px" colspan="3">
									<?echo nl2br($contents)?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">제안사명</td>
								<td style="padding-left:10px" colspan="3">
									<?echo $com_name?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">회사홈페이지</td>
								<td style="padding-left:10px" colspan="3">
									<a href="<?echo $com_site?>" target="_new"><?echo $com_site?></a>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">부서및담당자</td>
								<td style="padding-left:10px" colspan="3">
									<?echo $name?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">연락처</td>
								<td style="padding-left:10px" colspan="3">
									<?echo $phone1?>-<?echo $phone2?>-<?echo $phone3?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">핸드폰</td>
								<td style="padding-left:10px" colspan="3">
									<?echo $hphone1?>-<?echo $hphone2?>-<?echo $hphone3?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">이메일</td>
								<td style="padding-left:10px" colspan="3">
									<a href="mailto:<?echo $email?>"><?echo $email?></a>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">첨부파일</td>
								<td style="padding-left:10px" colspan="3">
									<!--<a href="/con_files/down_file.php?file_name=<?echo $addfile?>"><?echo $addfile?></a>-->
									<a href="/con_files/<?echo $addfile?>"><?echo $addfile?></a>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<!--<a href="javascript:check_form();"><img src="images/button_save.gif" border="0"></a>&nbsp;-->
									<a href="javascript:goBack();"><img src="images/button_list_01.gif" border="0"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
<!-- Main Section End -->
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?
mysql_close($connect);
?>