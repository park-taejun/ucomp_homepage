<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: parent_code_write.php
	// 	Description : ��з� �߰� ȭ��
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "../admin_session_check.inc";
	include "../inc/global_init.inc";

?>		
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" href="../inc/tour.css" type="text/css">
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
	
	function Sendit() {
		with(document.frm) {
		
			if (pcode.value == "") {
				alert("�ڵ尪�� �Է��� �ֽʽÿ�."); //�ڵ尪�� �Է��� �ֽʽÿ�.
				pcode.focus();
				return;
			}

			if (pcode_name.value == "") {
				alert("�ڵ���� �Է��� �ֽʽÿ�."); //�ڵ��(�ѱ���)�� �Է��� �ֽʽÿ�.
				pcode_name.focus();
				return;
			}
			submit();
		}
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
					<td height="30" style="padding-left:20px"><a href="#">�ý��� ����</a> > <a href="#">�ڵ����</a> > <a href="#">��з� �ڵ�</a> > <a href="#">���</td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<form name="frm" action="parent_code_dml.php" method="post">
			<input type="hidden" name="mode" value="add">
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="80%"> 
							<tr>
								<td>* �ý��ۿ��� ����� �ڵ��� ��з��� ����ϴ� ȭ�� �Դϴ�.<!--�ý��ۿ��� ����� �ڵ��� ��з��� ����ϴ� ȭ�� �Դϴ�.--><br>
									* �ڵ尪�� �ڵ���� �ʼ� �Է� �׸��Դϴ�. �ڵ尪�� �ߺ� üũ �˴ϴ�.<!--�ڵ尪�� �ڵ���� �ʼ� �Է� �׸��Դϴ�. �ڵ尪�� �ߺ� üũ �˴ϴ�.--><br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="80%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�ڵ尪<!--�ڵ尪--></td>
								<td style="padding-left:10px" colspan="3">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												<input type="text" name="pcode" size="25" maxlength="6">&nbsp;
											</td>
											<td>
												<!--<a href="javascript:NewWindow('pop_pcode_dup.php?pcode='+document.frm.pcode.value, 'pop_pcode_dup', '500', '200', 'No')"><img src="../images/button_overlap.gif" border="0"></a>-->
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="25%" bgcolor="#E9E9E9" align="center" height="25">�ڵ��<!--�ڵ��(�ѱ���)--></td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="pcode_name" size="25" maxlength="30">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td bgcolor="#E9E9E9" align="center" height="25">�ڵ弳��<!--�ڵ弳��--></td>
								<td style="padding-left:10px" colspan="3">
									<textarea style="width:90%;height:50px" name="pcode_memo"></textarea>
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
									<a href="javascript:Sendit();"><img src="../images/button_receipt.gif" border="0"></a>&nbsp;
									<a href="parent_code_list.php"><img src="../images/button_list_01.gif" border="0"></a>
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

