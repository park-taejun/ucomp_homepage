<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_group_input.php
	// 	Description : ������ �׷� ��� ȭ��
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";

	$query = "select * from tb_big_menu order by big_menu";
	$result = mysql_query($query);

?>
<HTML>
<HEAD>
<TITLE><?echo $g_site_title?></TITLE>
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
<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="menu_list.php";
		document.frm.submit();
	}

	function goIn() {
	
		if (document.frm.big_menu.value == "-1") {
			alert("�޴� �׷��� ���� �ϼ���.");
			document.frm.big_menu.focus();
			return;
		}

		if (document.frm.small_menu.value == "") {
			alert("�޴����� �Է��ϼ���.");
			document.frm.small_menu.focus();
			return;
		}

		if (document.frm.menu_url.value == "") {
			alert("�޴� ��θ� �Է��ϼ���.");
			document.frm.menu_url.focus();
			return;
		}
								
		document.frm.target = "frhidden";
		document.frm.action = "menu_db.php";
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
					<td height="30" style="padding-left:20px"><a href="#">�����ڱ��� ����</a> > <a href="#">�޴�����</a> > <a href="#">���</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<form name='frm' method='post' action='menu_db.php'>
			<input type="hidden" name="mode" value="add">
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
									* ������ �޴��� �Է��ϴ� ȭ�� �Դϴ�.<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�޴��׷� ����</td>
								<td style="padding-left:10px" colspan="3">
									<select name="big_menu">
										<option value="-1">�� ��</option>
										<?
											while($row = mysql_fetch_array($result)) {
										?>
										<option value="<?echo $row[big_menu]?>"><?echo $row[big_menu_name]?></option>
										<?
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�޴���</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="small_menu" size="30" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�޴� ���</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="menu_url" size="50" value=""> 
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�޴� ����</td>
								<td style="padding-left:10px" colspan="3">
									<textarea name="menu_info" cols="70" rows="3"></textarea>
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
									<a href="javascript:goIn();"><img src="images/button_save.gif" border="0"></a>&nbsp;
									<a href="javascript:goBack();"><img src="images/button_list_01.gif" border="0"></a>&nbsp;
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