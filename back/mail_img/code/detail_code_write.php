<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: detail_code_write.php
	// 	Description : 소분류 코드 추가 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn.inc";

	$query = "select pcode_name from tb_code_parent where pcode = '$pcode'";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$pcode_name = $list[pcode_name];

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
<!--	
	function Sendit() {
		with(document.frm) {
		
			if (dcode.value == "") {
				alert("코드값을 입력해 주십시오."); //코드값을 입력해 주십시오.
				dcode.focus();
				return;
			}

			if (dcode_name.value == "") {
				alert("코드명을 입력해 주십시오."); //코드명(한국어)를 입력해 주십시오.
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
					<td height="30" style="padding-left:20px"><a href="#">시스템 관리</a> > <a href="#">코드관리</a> > <a href="#">세부분류 코드</a> > <a href="#"><?echo $pcode_name?> 등록</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<form name="frm" action="detail_code_dml.php" method="post">
				<input type="hidden" name="mode" value="add">
				<input type="hidden" name="pcode" value="<?echo $pcode?>">
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td>* 시스템에서 사용할 코드 입력 화면 입니다.<br>
									* 코드값과 코드명은 필수 입력 항목입니다. 코드값은 중복 체크 됩니다.<br>
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
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												<input type="text" name="dcode" size="25" maxlength="6">&nbsp;
											</td>
											<td>
												<!--<a href="javascript:NewWindow('pop_dcode_dup.asp?pcode=<%=sPcode%>&dcode='+document.frm.dcode.value, 'pop_dcode_dup', '500', '200', 'No')"><img src="<%=g_ImagePath%>btn_repeat.gif" border="0"></a>-->
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">코드값(extra)</td>
								<td style="padding-left:10px" colspan="3">
									* extra 코드값이 필요한 경우 사용<br>
									<input type="text" name="dcode_ext" size="25">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">코드명</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="dcode_name" size="25">
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
									<a href="detail_code_list.php?pcode=<?echo $pcode?>"><img src="../images/button_list_01.gif" border="0"></a>
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