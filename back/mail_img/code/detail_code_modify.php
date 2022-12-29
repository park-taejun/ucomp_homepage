<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn.inc";

	$query = "select pcode_name from tb_code_parent where pcode = '$pcode'";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$pcode_name = $list[pcode_name];

	$query = "select * from tb_code_detail where pcode = '".$pcode."' and dcode_no = '".$dcode_no."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$dcode = $list[dcode];
	$pcode = $list[pcode];
	$dcode_no = $list[dcode_no];
	$dcode_name = $list[dcode_name];
	$view_chk = $list[view_chk];
	$dcode_ext = $list[dcode_ext];
	$dcode_seq = $list[dcode_seq];
	
?>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
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

//	alert("<%=sParameter%>");

/*
	Submit Data
*/
	function Sendit() {
		with(document.frm) {
		
			if (dcode_name.value == "") {
				alert("코드명을 입력해 주십시오."); // 코드명(한국어)를 입력해 주십시오.
				dcode_name.focus();
				return;
			}
			submit();
		}
	}

	function setExtra() {
		var frm = document.frm
		frm.dcode_ext.value = frm.sel_nation.value;
	}

	function goList() {
		document.frm.action="detail_code_list.php";
		document.frm.submit();
	}

	function goDelete() {

		bDelOK = confirm("정말 삭제 하시겠습니까?");//정말 삭제 하시겠습니까?
			
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
					<td height="30" style="padding-left:20px">시스템 관리<!--시스템 관리--></a> > <a href="#">코드관리<!--코드관리--></a> > <a href="#">세부분류 코드<!--세부분류 코드--></a> > <a href="#"><?echo $pcode_name?>수정<!--수정--></a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<form name="frm" action="detail_code_dml.php" method="post">
				<input type="hidden" name="mode" value="mod">
				<input type="hidden" name="pcode" value="<?echo $pcode?>">
				<input type="hidden" name="dcode_no" value="<?echo $dcode_no?>">
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td>* 시스템에서 사용할 코드를 수정, 삭제하는 화면 입니다. <br>
									* 코드명은 필수 입력 항목입니다. 코드값은 수정 하실 수 없습니다.<br>								
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">코드값</td>
								<td style="padding-left:10px" colspan="3">
									<?echo $dcode?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">코드값(extra)</td>
								<td style="padding-left:10px" colspan="3">
									* 별도 코드값이 필요한 경우 사용<br>
									<input type="text" name="dcode_ext" size="25" value="<?echo $dcode_ext?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">코드명</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="dcode_name" size="25" value="<?echo $dcode_name?>">
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
