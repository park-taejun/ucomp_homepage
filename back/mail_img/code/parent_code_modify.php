<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn.inc";

	$query = "select * from tb_code_parent where pcode_no = '".$pcode_no."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$pcode = $list[pcode];
	$pcode_no = $list[pcode_no];
	$pcode_name = $list[pcode_name];
	$pcode_memo = $list[pcode_memo];
	
?>		
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv=Content-Type content="text/html; charset=euc-kr">
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
<!--	
/*
	Submit Data
*/
	function Sendit() {
		with(document.frm) {
		
			if (pcode_name.value == "") {
				alert("�ڵ���� �Է��� �ֽʽÿ�."); //�ڵ��(�ѱ���)�� �Է��� �ֽʽÿ�.
				pcode_name.focus();
				return;
			}
			submit();
		}
	}

	function goList() {
		document.frm.action="parent_code_list.php";
		document.frm.submit();
	}

	function goDelete() {

		bDelOK = confirm('���� ���� �Ͻðڽ��ϱ�?'); //���� ���� �Ͻðڽ��ϱ�?
			
		if ( bDelOK ==true ) {
			document.frm.mode.value = "del";
			document.frm.submit();
		}
		else {
			return;
		}

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
					<td height="30" style="padding-left:20px"><a href="#">�ý��� ����</a> > <a href="#">�ڵ����</a> > <a href="#">��з� �ڵ�</a> > <a href="#">����</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<form name="frm" action="parent_code_dml.php" method="post">
				<input type="hidden" name="mode" value="mod">
				<input type="hidden" name="pcode" value="<?echo $pcode?>">
				<input type="hidden" name="pcode_no" value="<?echo $pcode_no?>">
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="80%"> 
							<tr>
								<td>* �ý��ۿ��� ����� �ڵ��� ��з��� ����, �����ϴ� ȭ�� �Դϴ�.<!--�ý��ۿ��� ����� �ڵ��� ��з��� ����, �����ϴ� ȭ�� �Դϴ�.--><br>
									* �ڵ���� �ʼ� �Է� �׸��Դϴ�. �ڵ尪�� ���� �Ͻ� �� �����ϴ�.<!--�ڵ���� �ʼ� �Է� �׸��Դϴ�. �ڵ尪�� ���� �Ͻ� �� �����ϴ�.-->
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="80%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�ڵ�<!--�ڵ�--></td>
								<td style="padding-left:10px" colspan="3">
									<?echo $pcode?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�ڵ��<!--�ڵ��(�ѱ���)--></td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="pcode_name" size="25" value="<?echo $pcode_name?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">�ڵ弳��<!--�ڵ弳��--></td>
								<td style="padding-left:10px" colspan="3">
									<textarea style="width:90%;height:50px" name="pcode_memo"><?echo $pcode_memo?></textarea>
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
									<a href="javascript:Sendit();"><img src="../images/button_modify.gif" border="0"></a>&nbsp;
									<a href="javascript:goDelete();"><img src="../images/button_delete.gif" border="0"></a>&nbsp;
									<a href="javascript:goList();"><img src="../images/button_list_01.gif" border="0"></a>
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