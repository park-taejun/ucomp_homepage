<?
	$lan = trim($lan);
?>
<HTML>
<HEAD>
<TITLE><?echo $g_site_title?></TITLE>
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
<SCRIPT language="javascript">
<!--

	function send() {
		
		if( checkIt(document.frm) ){
			opener.setData('<?echo $lan?>', document.frm.data.value);	
			self.close();	
		} else {
		  return;
		}
	}


	function checkIt(form) {      
		getEditCheck(form);
		form.data.value = form.BB_CONTENT.value;
		//if(!form.data.value) {
		//	alert('내용을 입력하세요!');
    //  return false;
		//}
		return true;
	}

//-->
</SCRIPT>
</HEAD>
<BODY>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<form name='frm' method='post'>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="../images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td>
									* 내용 입력 화면 입니다.<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">내 용</td>
								<td style="padding-left:10px" colspan="3" bgcolor="#FFFFFF" align=center height="400">
									<!--<textarea name="Content" style="width:690px; height:200px"></textarea>-->
									<input type="hidden" name="data" value="">
									<?include("../htmledit/init_edit_han.php");?>									
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>							
							<tr>
								<td height="10" colspan="4"></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<a href="javascript:send();"><img src="../images/button_ok.gif" border="0"></a>&nbsp;
									<a href="javascript:self.close();;"><img src="../images/button_cancle.gif" border="0"></a>&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</form>
			</table>
<!-- Main Section End -->
		</td>
	</tr>
</table>
</body>
</html>